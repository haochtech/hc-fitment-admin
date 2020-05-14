<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');

if ($_W['ispost']) {
	$options = $_GPC['options'];

	if ($options) {
		if (empty($options['labour_cost']) || empty($options['stuff_cost'])) {
			sl_ajax(1, '材料费或人工费不能为空');
		}
		if (($options['labour_cost'] + $options['stuff_cost']) != '100') {
			sl_ajax(2, '材料费+人工费必需等于100');
		}
	} else {
		sl_ajax(1, '数据不能为空');
	}

	$data = array();
	$data['setting_value'] = json_encode($options); // 压缩

	if ($_W['slwl']['set']['calc_set_settings']) {
		$where['uniacid'] = $_W['uniacid'];
		$where['setting_name'] = 'calc_set_settings';
		pdo_update('slwl_fitment_settings', $data, $where);
	} else {
		$data['uniacid'] = $_W['uniacid'];
		$data['setting_name'] = 'calc_set_settings';
		$data['addtime'] = $_W['slwl']['datetime']['now'];
		pdo_insert('slwl_fitment_settings', $data);
	}
	sl_ajax(0, '保存成功');
}

if ($_W['slwl']['set']['calc_set_settings']) {
	$settings = $_W['slwl']['set']['calc_set_settings'];
}

include $this->template('web/module/calc');

?>
