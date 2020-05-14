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
		$condition .= ' AND (name LIKE :name) ';
		$params[':name'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_adact'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_adact') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'name' => $_GPC['name'],
			'subtitle' => $_GPC['subtitle'],
			'thumb' => $_GPC['thumb'],
			'detail' => htmlspecialchars_decode($_GPC['detail']),
			'out_thumb' => $_GPC['out_thumb'],
			'out_link' => $_GPC['out_link'],
			'enabled' => intval($_GPC['enabled']),
		);
		if ($id) {
			$rst = pdo_update('slwl_fitment_adact', $data, array('id' => $id));
			if ($rst !== false) {
				sl_ajax(0, '保存成功');
			} else {
				sl_ajax(1, 'ERR');
			}
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			$rst = pdo_insert('slwl_fitment_adact', $data);
			// $id = pdo_insertid();
			if ($rst !== false) {
				sl_ajax(0, '保存成功');
			} else {
				sl_ajax(1, 'ERR');
			}
		}
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_adact') . ' WHERE 1 ' . $condition, $params);


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_adact', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}




} else {
	message('请求方法不存在');
}

include $this->template('web/module/adact');

