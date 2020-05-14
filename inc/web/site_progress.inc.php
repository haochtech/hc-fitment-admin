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
        $condition .= ' AND (title LIKE :title) ';
        $params[':title'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_site_progress'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_site_progress') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {

        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => $_GPC['displayorder'],
            'title' => $_GPC['title'],
            'thumb' => $_GPC['thumb'],
            'enabled' => intval($_GPC['enabled']),
        );
        if ($id) {
            pdo_update('slwl_fitment_site_progress', $data, array('id' => $id));
        } else {
            $data['create_time'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_site_progress', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }

    if ($id) {
        $condition = ' AND uniacid=:uniacid AND id=:id ';
        $params = array(':uniacid'=>$_W['uniacid'], ":id"=>$id);
        $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_site_progress') . ' where 1 ' . $condition, $params);
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $condition = ' AND uniacid=:uniacid AND id=:id ';
    $params = array(':uniacid'=>$_W['uniacid'], ":id"=>$id);
    $one = pdo_fetch("SELECT id FROM " . tablename('slwl_fitment_site_progress') . ' where 1 ' . $condition, $params);

    if (empty($one)) {
        iajax(1, '抱歉，分类不存在或是已经被删除');
        exit;
    }

    $rst = pdo_delete('slwl_fitment_site_progress', array('id' => $id));
    if ($rst !== false) {
        iajax(0, '删除成功');
    } else {
        iajax(1, '删除失败');
    }
    exit;


} else {
    message('请求方法不存在');
}

include $this->template('web/site/progress');

?>