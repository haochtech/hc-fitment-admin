<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');

$account = uni_fetch($_W['uniacid']);
$appid = $account['key'];
$secret = $account['secret'];

require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";

if (checksubmit('submit')) {
	$options = $_GPC['options'];

	$data = array();
	$data['setting_value'] = json_encode($options); // 压缩

	if ($_W['slwl']['set']['tipswx_settings']) {
		$where['uniacid'] = $_W['uniacid'];
		$where['setting_name'] = 'tipswx_settings';
		pdo_update('slwl_fitment_settings', $data, $where);
	} else {
		$data['uniacid'] = $_W['uniacid'];
		$data['setting_name'] = 'tipswx_settings';
		$data['addtime'] = $_W['slwl']['datetime']['now'];
		pdo_insert('slwl_fitment_settings', $data);
	}

	$jssdk = new JSSDK($appid, $secret, 'token_name_'.$_W['uniacid']);

	// $access_token = $jssdk->getAccessToken();
	// var_dump($access_token);

	// $res = $jssdk->pushTemplateMsg('oRnYe0fkdegm7KRKWB6YnCzLewvA', 'kkQ4eilVZCKjq5129AZYnUdiV8bBpDIH9oTarTN8x8I');

	// var_dump($res);

	// if ($res['errcode'] == 0) {
	//     message('更新设置成功', 'refresh');
	// } else {
	//     message('更新设置成功但获取access_token出错', 'refresh');
	// }

	// $type = $options['type'];
	// $sms_id = $options['users']['sms_id']; // 短信模板ID
	// $curr_date = date('m-d H:i:s', time()); // 变量最长只能15个字符

	// if (($type == 'Alidayu') && $mobile && (intval($_GPC['test']) > 0)){
	//     $res = $this->sendsms($mobile, array('time'=>$curr_date), $sms_id, array('appkey'=>$appkey, 'secret'=>$secret, 'sign'=>$sign));

	//     // message($res, 'refresh');
	// }

	message('更新微信消息设置成功', 'refresh');
}

if ($_W['slwl']['set']['tipswx_settings']) {
	$tips = $_W['slwl']['set']['tipswx_settings'];
}

include $this->template('web/msg/tipswx');

?>
