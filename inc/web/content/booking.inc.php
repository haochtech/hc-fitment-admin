<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;

load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {
	$keyword = $_GPC['keyword'];

	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	if ($keyword != '') {
		$condition .= ' AND (name LIKE :name OR tel LIKE :tel) ';
		$params[':name'] = '%'.$keyword.'%';
		$params[':tel'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_booking') . ' WHERE 1 '
		. $condition . " ORDER BY status ASC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_booking') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	foreach ($list as $k => $v) {
		if ($v['option1']) {
			$op1 = '';
			$op1 = json_decode($v['option1'], true);

			$str = '';
			foreach ($op1 as $key => $value) {
				$str .= '[' . $value . ']' . '<br>';
			}
			$str .= '['. round($v['money'] / 10000, 1) . '万元]' . '<br>';

			$list[$k]['sp1'] = $str;
		}
	}

	// dump($list);exit;

} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'name' => $_GPC['name'],
			'status' => intval($_GPC['status']),
			'mark' => $_GPC['mark'],
		);
		if ($id) {
			pdo_update('slwl_fitment_booking', $data, array('id' => $id));
		}
		sl_ajax(0, '保存成功');
		exit;
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_booking') . " where 1 " . $condition, $params);


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_booking', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} elseif ($operation == 'export') {
	$timestart = date('Y-m-d', time());
	$timeend = date('Y-m-d', time());


} elseif ($operation == 'export_post') {
	$time_start = $_GPC['timestart'];
	$time_end = $_GPC['timeend'];

	if (empty($time_start) || empty($time_end)) {
		message('时间段不能为空');
		exit;
	}

	// 读取记录
	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = 1;
	$psize = 5000;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_booking') . ' WHERE 1 ' . $condition . " ORDER BY status ASC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$list = pdo_fetchall($sql, $params);

	foreach ($list as $k => $v) {
		if ($v['option1']) {
			$op1 = '';
			$op1 = json_decode($v['option1'], true);

			$str = '';
			foreach ($op1 as $key => $value) {
				$str .= '[' . $value . ']' . chr(10);
			}
			$str .= '['. round($v['money'] / 10000, 1) . '万元]' . chr(10);

			$list[$k]['sp1'] = $str;
			$list[$k]['contact'] = $str . $v['option2'];
		}
		if ($v['status'] == '0') {
			$list[$k]['status_cn'] = '未处理';
		} else if ($v['status'] == '1') {
			$list[$k]['status_cn'] = '已处理';
		} else {
			$list[$k]['status_cn'] = $v['status'];
		}
	}

	// dump($list);exit;

	require_once MODULE_ROOT . "/lib/PHPExcel/PHPExcel.php";
	require_once MODULE_ROOT . "/lib/PHPExcel/ExcelHelper.php";

	//导出Excel
	$xlsName = '询价记录';
	$xlsCell = array(
		array('name', '昵称'),
		array('tel', '联系方式'),
		array('contact', '内容'),
		array('status_cn', '状态'),
		array('addtime', '时间'),
		array('mark', '备注'),
	);

	$myExcel = new \ExcelHelper();

	// $myExcel->exportExcel($xlsName, $xlsCell, $xlsData);
	$myExcel->exportExcel($xlsName, $xlsCell, $list);

	exit;
} else {
	message('请求方法不存在');
}

include $this->template('web/content/booking');

?>
