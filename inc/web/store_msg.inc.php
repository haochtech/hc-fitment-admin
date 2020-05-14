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

    if ($_W['slwl']['set']['set_store_msg']) {
        $where['uniacid'] = $_W['uniacid'];
        $where['setting_name'] = 'set_store_msg';
        pdo_update('slwl_fitment_settings', $data, $where);
    } else {
        $data['uniacid'] = $_W['uniacid'];
        $data['setting_name'] = 'set_store_msg';
        $data['addtime'] = $_W['slwl']['datetime']['now'];
        pdo_insert('slwl_fitment_settings', $data);
    }

    $type = $options['type'];
    $appkey = $options['Alidayu']['appkey'];
    $secret = $options['Alidayu']['secret'];
    $mobile = $options['Alidayu']['mobile']; // 手机号
    $sign = $options['Alidayu']['sign']; // 签名
    $sms_id = $options['Alidayu']['sms_id']; // 短信模板ID
    $curr_date = date('m-d H:i:s', time()); // 变量最长只能15个字符

    if (($type == 'Alidayu') && ($mobile) && (intval($_GPC['test']) > 0)){
        $res = sendsms($mobile, array('time'=>$curr_date), $sms_id, array('appkey'=>$appkey, 'secret'=>$secret, 'sign'=>$sign));
    }
    iajax(0, '更新短信设置成功！');
}

if ($_W['slwl']['set']['set_store_msg']) {
    $messages = $_W['slwl']['set']['set_store_msg'];
}

include $this->template('web/store-msg');

?>