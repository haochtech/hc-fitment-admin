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
    $photo = array();
    $tmp_pic = array();

    if ($options) {
        foreach ($options['attachment'] as $k => $v) {
            $tmp_pic[$k]['attachment'] = $v;
        }

        foreach ($options['title'] as $k => $v) {
            $tmp_pic[$k]['title'] = $v;
        }

        foreach ($options['page_url'] as $k => $v) {
            $tmp_pic[$k]['page_url'] = $v;
        }

        foreach ($tmp_pic as $k=>$v){
            $photo[] = $v;
        }

        $data['setting_value'] = json_encode($photo); // 压缩
    } else {
        $data['setting_value'] = '';
    }

    if ($_W['slwl']['set']['set_adgroup']) {
        $where['uniacid'] = $_W['uniacid'];
        $where['setting_name'] = 'set_adgroup';
        pdo_update('slwl_fitment_settings', $data, $where);
    } else {
        $data['uniacid'] = $_W['uniacid'];
        $data['setting_name'] = 'set_adgroup';
        $data['addtime'] = $_W['slwl']['datetime']['now'];
        pdo_insert('slwl_fitment_settings', $data);
    }

    iajax(0, '保存成功！');
}

if ($_W['slwl']['set']['set_adgroup']) {
    $smeta = $_W['slwl']['set']['set_adgroup'];
}

include $this->template('web/adgroup');

?>