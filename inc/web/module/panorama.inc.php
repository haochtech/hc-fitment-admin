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

	$sql = "SELECT * FROM " . tablename('slwl_fitment_panorama'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_panorama') . ' WHERE 1 ' . $condition, $params);
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
			'thumb' => $_GPC['thumb'],
			'view_count' => intval($_GPC['view_count']),
			'fav_count' => intval($_GPC['fav_count']),
			'pic_front' => $_GPC['pic_front'],
			'pic_right' => $_GPC['pic_right'],
			'pic_back' => $_GPC['pic_back'],
			'pic_left' => $_GPC['pic_left'],
			'pic_top' => $_GPC['pic_top'],
			'pic_bottom' => $_GPC['pic_bottom'],
			'enabled' => intval($_GPC['enabled']),
			'create_time' => $_GPC['create_time'],
		);
		if ($id) {
			$rst = pdo_update('slwl_fitment_panorama', $data, array('id' => $id));
			if ($rst !== false) {
				sl_ajax(0, $_W['slwl']['lang']['tips_success']);
			} else {
				sl_ajax(1, $_W['slwl']['lang']['tips_error']);
			}
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			$rst = pdo_insert('slwl_fitment_panorama', $data);
			$id = pdo_insertid();
			if ($rst !== false) {
				sl_ajax(0, $_W['slwl']['lang']['tips_success']);
			} else {
				sl_ajax(1, $_W['slwl']['lang']['tips_error']);
			}
		}
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_panorama') . ' WHERE 1 ' . $condition, $params);


} elseif ($operation == 'set') {

	if ($_W['ispost']) {
		$options = $_GPC['options'];

		$data = array();
		$data['setting_value'] = json_encode($options); // 压缩

		if ($_W['slwl']['set']['set_panorama']) {
			$where['uniacid'] = $_W['uniacid'];
			$where['setting_name'] = 'set_panorama';
			pdo_update('slwl_fitment_settings', $data, $where);
		} else {
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_name'] = 'set_panorama';
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}

		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	}

	if ($_W['slwl']['set']['set_panorama']) {
		$settings = $_W['slwl']['set']['set_panorama'];
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_panorama', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/module/panorama');

