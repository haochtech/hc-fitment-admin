<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');

if ($_W['ispost']) {
	$options = $_GPC['options'];

	$map = $_GPC['map'];
	if ($map['lng'] && $map['lat']) {
		$map_baidu = array('lng'=>$map['lng'], 'lat'=>$map['lat']);
		$map_qq = Convert_BD09_To_GCJ02($map['lat'], $map['lng']);
		$options['coordinate'] = array(
			'baidu'=>$map_baidu,
			'qq'=>$map_qq,
		);
	}

	$data = array();
	$data['setting_value'] = json_encode($options); // 压缩

	if ($_W['slwl']['set']['site_settings']) {
		$where['uniacid'] = $_W['uniacid'];
		$where['setting_name'] = 'site_settings';
		$rst = pdo_update('slwl_fitment_settings', $data, $where);
		if ($rst !== false) {
			sl_ajax(0, $_W['slwl']['lang']['tips_success']);
		} else {
			sl_ajax(1, $_W['slwl']['lang']['tips_error']);
		}
	} else {
		$data['uniacid'] = $_W['uniacid'];
		$data['setting_name'] = 'site_settings';
		$data['addtime'] = $_W['slwl']['datetime']['now'];
		$rst = pdo_insert('slwl_fitment_settings', $data);
		if ($rst !== false) {
			sl_ajax(0, $_W['slwl']['lang']['tips_success']);
		} else {
			sl_ajax(1, $_W['slwl']['lang']['tips_error']);
		}
	}
}

if ($_W['slwl']['set']['site_settings']) {
	$settings = $_W['slwl']['set']['site_settings'];

	if ($settings['coordinate']) {
		$tmp_map = array(
			'lng'=>$settings['coordinate']['baidu']['lng'],
			'lat'=>$settings['coordinate']['baidu']['lat'],
		);
	}

	if (empty($settings['titledf1_show']) && ($settings['titledf1_show'] != '0')) {
		$settings['titledf1_show'] = '1';
	}
	if (empty($settings['titledf2_show']) && ($settings['titledf2_show'] != '0')) {
		$settings['titledf2_show'] = '1';
	}
}

include $this->template('web/basic/set');

?>
