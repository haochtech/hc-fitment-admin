<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {
	$condition = " AND uniacid=:uniacid AND enabled='1' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 1000;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_term_des') . ' WHERE 1 ' . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$list = pdo_fetchall($sql, $params);

	// foreach ($list as $k => $v) {
	//     $where = " AND uniacid=:uniacid AND parentid=:parentid ";
	//     $where_params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$v['id']);
	//     $list[$k]['child'] = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_term_des') . ' WHERE 1 ' . $where . " ORDER BY displayorder DESC, id DESC", $where_params);
	// }

} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	$tid = max(0, intval($_GPC['tid']));

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'parentid' => $tid,
			'name' => $_GPC['name'],
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb']
		);
		if ($id) {
			pdo_update('slwl_fitment_term_des', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_term_des', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}

	if ($tid>0) {
		$where = " AND uniacid=:uniacid AND parentid='0' ";
		$where_params = array(':uniacid' => $_W['uniacid']);
		$term_list = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_term_des') . ' WHERE 1 '
			. $where . " ORDER BY displayorder DESC, id DESC", $where_params);
	}

	if ($id) {
		$condition = " AND uniacid=:uniacid AND id=:id ";
		$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
		$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_term_des') . " where 1 " . $condition, $params);
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$condition_child = " AND uniacid=:uniacid AND parentid=:id ";
	$params_child = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one_child = pdo_fetch('SELECT id FROM ' . tablename('slwl_fitment_term_des') . ' WHERE 1 '
		. $condition_child, $params_child);

	if ($one_child) {
		sl_ajax(1, '分类下存在子分类不能删除');
	}

	$rst = pdo_delete('slwl_fitment_term_des', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/other/term_des');

?>
