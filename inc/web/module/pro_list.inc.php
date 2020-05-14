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
		$condition .= ' AND (title LIKE :title) ';
		$params[':title'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_product_list'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_product_list') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	foreach ($list as $k => $v) {
		$list[$k]['thumb_url'] = tomedia($v['thumb']);
	}


} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'title' => $_GPC['title'],
			'subtitle' => $_GPC['subtitle'],
			'createtime' => $_GPC['createtime'],
			'detail' => htmlspecialchars_decode($_GPC['detail']),
			'out_thumb' => $_GPC['out_thumb'],
			'out_link' => $_GPC['out_link'],
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb'],
			'view_count' => intval($_GPC['view_count']),
			'fav_count' => intval($_GPC['fav_count']),
		);
		if ($id) {
			pdo_update('slwl_fitment_product_list', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_product_list', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功！');
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_product_list') . ' WHERE 1 ' . $condition, $params);


} elseif ($operation == 'set') {

	if ($_W['ispost']) {
		$options = $_GPC['options'];

		$data = array();
		$data['setting_value'] = json_encode($options); // 压缩

		if ($_W['slwl']['set']['set_pro_list']) {
			$where['uniacid'] = $_W['uniacid'];
			$where['setting_name'] = 'set_pro_list';
			pdo_update('slwl_fitment_settings', $data, $where);
		} else {
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_name'] = 'set_pro_list';
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}

		sl_ajax(0, '保存成功！');
	}

	if ($_W['slwl']['set']['set_pro_list']) {
		$tmp_pro_list = $_W['slwl']['set']['set_pro_list'];
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_product_list', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/module/pro-list');

