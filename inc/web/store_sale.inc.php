<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = ($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display') {
    $keyword = $_GPC['keyword'];

    $condition = " AND uniacid=:uniacid ";
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (title LIKE :title) ';
        $params[':title'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_store_sale'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_store_sale') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);

    foreach ($list as $k => $v) {
        $list[$k]['backmoney_format'] = number_format($v['backmoney'] / 100, 2);
    }


} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {
        @list($dt['start'], $dt['end']) = @explode(' 至 ', $_GPC['time']);

        $backmoney   = $_GPC['backmoney'] * 100;    // 立减

        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => intval($_GPC['displayorder']),
            'title' => $_GPC['title'],
            'intro' => $_GPC['intro'],
            'thumb'=>$_GPC['thumb'],
            'enough' => $_GPC['enough'],
            'timelimit' => $_GPC['timelimit'],
            'timedays1' => intval($_GPC['timedays1']),
            'timedays2' => json_encode($dt),
            'backtype' => $_GPC['backtype'],
            'backmoney' => $backmoney ,
            'discount' => $_GPC['discount'],
            'flbackmoney' => $_GPC['flbackmoney'],
            'backwhen' => $_GPC['backwhen'],
            'total' => $_GPC['total'],
            'enabled' => intval($_GPC['enabled']),
            'total' => intval($_GPC['total']),
        );

        if ($id) {
            pdo_update('slwl_fitment_store_sale', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_store_sale', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功！');
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_store_sale') . ' WHERE 1 ' . $condition, $params);

    $one['backmoney']   = $one['backmoney'] / 100;    // 销售价

    if ($one) {
        $time = json_decode($one['timedays2'], true);
        if ($time['start']) {
            $one['timestart'] = strtotime($time['start']);
        } else {
            $one['timestart'] = time();
        }
        if ($time['end']) {
            $one['timeend'] = strtotime($time['end']);
        } else {
            $one['timeend'] = strtotime("+1 months");
        }
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_store_sale', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} else {
    message('请求方法不存在');
}


include $this->template('web/store/sale');

?>