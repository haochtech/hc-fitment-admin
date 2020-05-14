<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {
    $keyword = $_GPC['keyword'];

    $condition = ' AND uniacid=:uniacid ';
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (name LIKE :name) ';
        $params[':name'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_news'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);

    $term = pdo_fetchall("SELECT id, name FROM " . tablename('slwl_fitment_term')
        . " WHERE uniacid=:uniacid ORDER BY displayorder DESC, id DESC", array(":uniacid" => $_W['uniacid']));

    foreach ($list as $key => $value) {
        foreach ($term as $k => $v) {
            if ($value['parentid'] == $v['id']) {
                $list[$key]['parent_cn'] = $v['name'];
            }
        }
    }

} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'parentid' => intval($_GPC['parentid']),
            'displayorder' => $_GPC['displayorder'],
            'name' => $_GPC['name'],
            'subtitle' => $_GPC['subtitle'],
            'createtime' => $_GPC['createtime'],
            'detail' => htmlspecialchars_decode($_GPC['detail']),
            'thumb' => $_GPC['thumb'],
            'out_thumb' => $_GPC['out_thumb'],
            'out_link' => $_GPC['out_link'],
            'enabled' => intval($_GPC['enabled']),
        );
        if ($id) {
            pdo_update('slwl_fitment_news', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_news', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 ' . $condition, $params);

    $condition_term = " AND uniacid=:uniacid ";
    $params_term = array(':uniacid' => $_W['uniacid']);
    $term = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 ' . $condition_term, $params_term);
    $parent = array();
    $children = array();
    foreach ($term as $k => $v) {
        if ($v['parentid'] == '0') {
            $parent[] = $v;
        } else {
            $children[] = $v;
        }
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_news', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} else {
    message('请求方法不存在');
}

include $this->template('web/news');

