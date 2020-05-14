<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');

if ($_W['ispost']) {
	$options = $_GPC['options'];

	$data = array();
	$data['setting_value'] = json_encode($options); // 压缩

	if ($_W['slwl']['set']['msg_settings']) {
		$where['uniacid'] = $_W['uniacid'];
		$where['setting_name'] = 'msg_settings';
		pdo_update('slwl_fitment_settings', $data, $where);
	} else {
		$data['uniacid'] = $_W['uniacid'];
		$data['setting_name'] = 'msg_settings';
		$data['addtime'] = $_W['slwl']['datetime']['now'];
		pdo_insert('slwl_fitment_settings', $data);
	}

	// $type = $options['type'];
	// $appkey = $options['Alidayu']['appkey'];
	// $secret = $options['Alidayu']['secret'];
	$mobile = $options['Alidayu']['mobile']; // 手机号
	// $sign = $options['Alidayu']['sign']; // 短信签名
	// $sms_id = $options['Alidayu']['sms_id']; // 短信模板ID
	// $curr_date = date('m-d H:i:s', time()); // 变量最长只能15个字符

	if ($mobile && (intval($_GPC['test']) > 0)){
		include_once MODULE_ROOT . '/lib/smsfunctions.php';
		$rst = Aliyun\DySDKLite\Sms\send_sys_msg_admin($mobile, 1);

		if ($rst['errcode'] != '0') {
			sl_ajax(1, $rst['errmsg']);
		}
	}

	sl_ajax(0, '更新短信设置成功');
}

if ($_W['slwl']['set']['msg_settings']) {
	$messages = $_W['slwl']['set']['msg_settings'];
}

include $this->template('web/msg/tipsmsg');

?>
