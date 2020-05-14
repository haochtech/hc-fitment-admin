<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {

    if ($_W['ispost']) {
        $options = $_GPC['options'];

        $data = array();
        $data['setting_value'] = json_encode($options); // 压缩

        if ($_W['slwl']['set']['msg_user_settings']) {
            $where['uniacid'] = $_W['uniacid'];
            $where['setting_name'] = 'msg_user_settings';
            pdo_update('slwl_fitment_settings', $data, $where);
        } else {
            $data['uniacid'] = $_W['uniacid'];
            $data['setting_name'] = 'msg_user_settings';
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
            $rets_user = Aliyun\DySDKLite\Sms\send_user_msg($mobile, 1); // 发送用户短信通知

            // $rets_user = Aliyun\DySDKLite\Sms\send_user_msg('13579246810', '9999'); // 发送用户短信通知

            // dump($rets_user);exit;

            if ($rets_user['errcode'] != '0') {
                iajax(1, $rets_user['errmsg']);
            }
        }

        iajax(0, '更新短信设置成功');
    }

    if ($_W['slwl']['set']['msg_user_settings']) {
        $messages = $_W['slwl']['set']['msg_user_settings'];
    }


} elseif ($operation == 'post') {

    if ($_W['ispost']) {
        $options = $_GPC['options'];

        $data = array();
        $data['setting_value'] = json_encode($options); // 压缩

        if ($_W['slwl']['set']['tmplates_wx_set_settings']) {
            $where['uniacid'] = $_W['uniacid'];
            $where['setting_name'] = 'tmplates_wx_set_settings';
            pdo_update('slwl_fitment_settings', $data, $where);
        } else {
            $data['uniacid'] = $_W['uniacid'];
            $data['setting_name'] = 'tmplates_wx_set_settings';
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_settings', $data);
        }

        if ($options['wx_template_msg_show'] == '1') {
            $res = @send_wx_template_add();
        } else {
            $res = @send_wx_template_delete();
        }

        iajax(0, '保存成功');
    }

    if ($_W['slwl']['set']['tmplates_wx_set_settings']) {
        $tmp_wx = $_W['slwl']['set']['tmplates_wx_set_settings'];
    }


} else {
    message('请求方法不存在');
}

include $this->template('web/usermsg');

?>