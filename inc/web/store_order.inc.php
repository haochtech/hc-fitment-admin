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

    $status = $_GPC['status'];
    if ($status == '') {
        $where = '';
    } else {
        $where .= " AND status='$status' ";
    }
    $condition = ' AND uniacid=:uniacid ';
    $condition .= $where;
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $params[':ordersn'] = '%'.$keyword.'%';
    }
    if ($keyword != '') {
        $tmp = json_encode($keyword);
        $tmp = ltrim($tmp, '"');
        $tmp = rtrim($tmp, '"');
        $tmp = str_replace('\\', '\\\\', $tmp);
        $condition .= ' AND (`address` LIKE :address OR ordersn LIKE :ordersn) ';
        $params[':address'] = '%'.$tmp.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_store_order'). ' WHERE 1 '
        . $condition . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_store_order') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);

    foreach ($list as $k => $v) {
        $list[$k]['price_format'] = number_format($v['price'] / 100, 2);

        // 地址信息
        if ($v['address']) {
            $list_address = json_decode($v['address'], true);
        }
        $list[$k]['address'] = $list_address;
    }


} elseif ($operation == 'detail') {
    $id = intval($_GPC['id']);

    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $order = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_store_order') . ' WHERE 1 ' . $condition, $params);
    if (empty($order)) {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }

    // 地址信息
    if ($order['address']) {
        $list_address = json_decode($order['address'], true);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_address_info']);
    }
    $order['address'] = $list_address;

    // 商品信息
    if ($order['goods']) {
        $list_goods = json_decode($order['goods'], true);
        foreach ($list_goods as $k => $v) {
            $list_goods[$k]['thumb_url'] = tomedia($v['thumb']);
            $list_goods[$k]['price_format']   = number_format($v['price'] / 100, 2);
            $list_goods[$k]['original_price_format']  = number_format($v['original_price'] / 100, 2);
        }
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_good_info']);
    }
    $order['goods'] = $list_goods;

    $order['price_format'] = number_format($order['price'] / 100, 2);
    $order['discount_money_format'] = number_format($order['discount_money'] / 100, 2);
    $originalprice = $order['price'] + $order['discount_money']; // 原价 = 优惠后的价格 + 优惠价格
    $order['originalprice'] = $originalprice;
    $order['originalprice_format'] = number_format($originalprice / 100, 2);


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_store_order', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} elseif ($operation == 'order_status') {
    global $_GPC, $_W;
    $id = intval($_GPC['id']);
    $status = $_GPC['status'];

    if (($id == '') || ($status == '')) {
        iajax(1, '参数不能为空');
    }

    $data = array(
        'status'=>$status,
    );
    $rst = pdo_update("slwl_fitment_store_order", $data, array("id" => $id, "uniacid" => $_W['uniacid']));

    if ($rst !== false) {
        return result(0, $_W['slwl']['lang']['tips_success']);
    } else {
        return result(1, $_W['slwl']['lang']['tips_error']);
    }


} else {
    message('请求方法不存在');
}


include $this->template('web/store/order');
?>