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

	$condition = " AND uniacid=:uniacid ";
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	if ($keyword != '') {
		$condition .= ' AND (termname LIKE :termname) ';
		$params[':termname'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_act_term'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_act_term') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'termname' => $_GPC['termname'],
			'term_style' => $_GPC['term_style'],
			'isrecommand' => intval($_GPC['isrecommand']),
			'click_type' => intval($_GPC['click_type']),
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb']);
		if ($id) {
			pdo_update('slwl_fitment_act_term', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_act_term', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_act_term') . " where 1 " . $condition, $params);


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_act_term', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} elseif ($operation == 'set') {
	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_act_term_adv'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_act_term_adv') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post_set') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'title' => $_GPC['title'],
			'enabled' => intval($_GPC['enabled']),
			'page_url' => $_GPC['page_url'],
			'thumb' => $_GPC['thumb'],
		);
		if ($id) {
			pdo_update('slwl_fitment_act_term_adv', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_act_term_adv', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_act_term_adv') . " where 1 " . $condition, $params);


} elseif ($operation == 'delete_set') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_act_term_adv', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/content/act-term');

?>
