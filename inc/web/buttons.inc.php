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
        $rownum = 4;

        if ($_GPC['rownum']) {
            $rownum = $_GPC['rownum'];
        }

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
            $photo['items'][] = $v;
        }
    }
    $photo['rownum'] = $rownum;
    $photo['enabled'] = $_GPC['enabled'];


    $data['uniacid'] = $_W['uniacid'];
    $data['setting_value'] = json_encode($photo); // 压缩

    if ($_W['slwl']['set']['set_buttons']) {
        $where['uniacid'] = $_W['uniacid'];
        $where['setting_name'] = 'set_buttons';
        pdo_update('slwl_fitment_settings', $data, $where);
    } else {
        $data['uniacid'] = $_W['uniacid'];
        $data['setting_name'] = 'set_buttons';
        $data['addtime'] = $_W['slwl']['datetime']['now'];
        pdo_insert('slwl_fitment_settings', $data);
    }
    iajax(0, '保存成功');
}

if ($_W['slwl']['set']['set_buttons']) {
    $smeta = $_W['slwl']['set']['set_buttons'];
}

include $this->template('web/buttons');

?>