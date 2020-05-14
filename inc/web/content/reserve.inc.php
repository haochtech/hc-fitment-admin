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

	$sql = "SELECT * FROM " . tablename('slwl_fitment_reserve') . ' WHERE 1 '
		. $condition . " ORDER BY status ASC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_reserve') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	foreach ($list as $k => $v) {
		if ($v['money'] > 0) {
			$list[$k]['money_format'] = $v['money'] / 100;
		} else {
			$list[$k]['money_format'] = 0;
		}
	}

} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $_GPC['name'],
			'tel' => $_GPC['tel'],
			'msg' => $_GPC['msg'],
			'status' => intval($_GPC['status'])
		);
		if ($id) {
			pdo_update('slwl_fitment_reserve', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_reserve', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_reserve') . " where 1 " . $condition, $params);


} elseif ($operation == 'set') {

	if ($_W['ispost']) {
		$options = $_GPC['options'];

		if ($options['reserve_money']) {
			$options['reserve_money'] = $options['reserve_money'] * 100;
		}

		$data = array();
		$data['setting_value'] = json_encode($options); // 压缩

		if ($_W['slwl']['set']['reserve_set_settings']) {
			$where['uniacid'] = $_W['uniacid'];
			$where['setting_name'] = 'reserve_set_settings';
			pdo_update('slwl_fitment_settings', $data, $where);
		} else {
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_name'] = 'reserve_set_settings';
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}

		sl_ajax(0, '保存成功！');
	}

	if ($_W['slwl']['set']['reserve_set_settings']) {
		$settings = $_W['slwl']['set']['reserve_set_settings'];

		if ($settings['reserve_money']) {
			$settings['reserve_money'] = $settings['reserve_money'] / 100;
		}
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_reserve', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/content/reserve');

?>
