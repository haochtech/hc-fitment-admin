<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = ($_GPC['op']) ? $_GPC['op'] : 'display';

$account = uni_fetch($_W['uniacid']);
$app_id = $account['key'];
// $secret = $account['secret'];
unset($account);

if ($operation == 'display') {
	$where = ['uniacid'=>$_W['uniacid'], 'enabled'=>'1'];
	$total = pdo_count(sl_table_name('mod_wxapp'),$where);


} elseif ($operation == 'post') {
	if ($_W['ispost']) {

		// 查出所有跳转小程序appid
		$where = ['uniacid'=>$_W['uniacid'], 'enabled'=>'1'];
		$order_by = [];
		$limit = [10];
		$list_appid = pdo_getall(sl_table_name('mod_wxapp'), $where, '', '', $order_by, $limit);

		$app_id_list = sl_array_column($list_appid, 'appid');

		$domain_https = substr($_GPC['siteroot'], 0, 5);
		if ($domain_https != 'https') {
			sl_ajax(1, '域名必需为https');
		}
		$set_auth = [];
		if ($_W['slwl']['set']['auth_settings']) {
			$set_auth = $_W['slwl']['set']['auth_settings'];
		}

		$param = [];
		$param['app_id'] = $_GPC['app_id'];
		$param['plugin'] = $_GPC['plugin'];
		$param['ver'] = $_GPC['ver'];
		$param['version'] = $_GPC['version'];
		$param['desc'] = $_GPC['desc'];
		$param['uniacid'] = $_GPC['uniacid'];
		$param['siteroot'] = $_GPC['siteroot'];
		$param['app_id_list'] = $app_id_list;

		$param['host'] = $set_auth['domain'];
		$param['code'] = $set_auth['code'];

		$resp = ihttp_request(SLWL_AUTH_URL . 'Index/sl_upload_wxapp', $param);
		$rst = @json_decode($resp['content'], true);

		// dump($resp);
		// dump($rst);
		// exit;

		if ($rst && $rst['code'] == 0) {
			sl_ajax(0, $rst['data']);
		} else {
			if (empty($rst['msg'])) {
				sl_ajax(1, '未知错误或返回为空');
			}
			sl_ajax(1, $rst['msg']);
		}
	}
	exit;


} elseif ($operation == 'check_up') {
	if ($_W['ispost']) {
		$domain_https = substr($_GPC['siteroot'], 0, 5);
		if ($domain_https != 'https') {
			sl_ajax(1, '域名必需为https');
		}
		$set_auth = array();
		if ($_W['slwl']['set']['auth_settings']) {
			$set_auth = $_W['slwl']['set']['auth_settings'];
		}

		$param = array();
		$param['app_id'] = $_GPC['app_id'];
		$param['plugin'] = $_GPC['plugin'];
		$param['ver'] = $_GPC['ver'];
		$param['version'] = $_GPC['version'];
		$param['desc'] = $_GPC['desc'];
		$param['uniacid'] = $_GPC['uniacid'];
		$param['siteroot'] = $_GPC['siteroot'];

		$param['host'] = $set_auth['domain'];
		$param['code'] = $set_auth['code'];

		$resp = ihttp_request(SLWL_AUTH_URL . 'Index/wxapp_check_up', $param);
		$rst = @json_decode($resp['content'], true);

		// dump($resp);
		// var_dump($rst);
		// exit;

		if ($rst['code'] == '0') {
			sl_ajax(0, $rst);
		} else if ($rst['code'] == '1') {
			if (empty($rst['msg'])) {
				sl_ajax(1, '未知错误或返回为空');
			}
			sl_ajax(1, $rst['msg']);
		} else {
			if (empty($rst['code'])) {
				sl_ajax(1, '未知错误或返回为空code');
			}
			if (empty($rst['msg'])) {
				sl_ajax(1, '未知错误或返回为空msg');
			}
			sl_ajax($rst['code'], $rst['msg']);
		}
	}
	exit;


} elseif ($operation == 'check_preview') {
	if ($_W['ispost']) {
		$domain_https = substr($_GPC['siteroot'], 0, 5);
		if ($domain_https != 'https') {
			sl_ajax(1, '域名必需为https');
		}
		$set_auth = array();
		if ($_W['slwl']['set']['auth_settings']) {
			$set_auth = $_W['slwl']['set']['auth_settings'];
		}

		$param = array();
		$param['app_id'] = $_GPC['app_id'];
		$param['plugin'] = $_GPC['plugin'];
		$param['ver'] = $_GPC['ver'];
		$param['version'] = $_GPC['version'];
		$param['desc'] = $_GPC['desc'];
		$param['uniacid'] = $_GPC['uniacid'];
		$param['siteroot'] = $_GPC['siteroot'];

		$param['host'] = $set_auth['domain'];
		$param['code'] = $set_auth['code'];

		$resp = ihttp_request(SLWL_AUTH_URL . 'Index/wxapp_check_preview', $param);
		$rst = @json_decode($resp['content'], true);

		// dump($resp);
		// var_dump($rst);
		// exit;

		if ($rst['code'] == '0') {
			sl_ajax(0, $rst['data']);
		} else {
			if (empty($rst['msg'])) {
				sl_ajax(1, '未知错误或返回为空');
			}
			sl_ajax(1, $rst['msg']);
		}
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/other/upwxapp');

?>
