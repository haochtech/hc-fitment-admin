<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;

load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {
    $condition = ' AND uniacid=:uniacid ';
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $sql = "SELECT * FROM " . tablename('slwl_fitment_giftbig') . ' WHERE 1 '
        . $condition . " ORDER BY enabled ASC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_giftbig') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);

} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'mark' => $_GPC['mark'],
            'enabled' => intval($_GPC['enabled'])
        );
        if ($id) {
            pdo_update('slwl_fitment_giftbig', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_giftbig', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_giftbig') . " where 1 " . $condition, $params);


} elseif ($operation == 'set') {

    if ($_W['ispost']) {
        $options = $_GPC['options'];

        $data = array();
        $data['setting_value'] = json_encode($options); // 压缩

        if ($_W['slwl']['set']['giftbig_set_settings']) {
            $where['uniacid'] = $_W['uniacid'];
            $where['setting_name'] = 'giftbig_set_settings';
            pdo_update('slwl_fitment_settings', $data, $where);
        } else {
            $data['uniacid'] = $_W['uniacid'];
            $data['setting_name'] = 'giftbig_set_settings';
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_settings', $data);
        }
        iajax(0, '保存成功！');
    }

    if ($_W['slwl']['set']['giftbig_set_settings']) {
        $settings = $_W['slwl']['set']['giftbig_set_settings'];
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_giftbig', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} else {
    message('请求方法不存在');
}

include $this->template('web/giftbig');

?>