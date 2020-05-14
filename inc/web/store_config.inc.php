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

    if ($_W['slwl']['set']['set_store_config']) {
        $where['uniacid'] = $_W['uniacid'];
        $where['setting_name'] = 'set_store_config';
        pdo_update('slwl_fitment_settings', $data, $where);
    } else {
        $data['uniacid'] = $_W['uniacid'];
        $data['setting_name'] = 'set_store_config';
        $data['addtime'] = $_W['slwl']['datetime']['now'];
        pdo_insert('slwl_fitment_settings', $data);
    }

    iajax(0, '保存成功！');
}

if ($_W['slwl']['set']['set_store_config']) {
    $settings = $_W['slwl']['set']['set_store_config'];
}

include $this->template('web/store-config');

?>