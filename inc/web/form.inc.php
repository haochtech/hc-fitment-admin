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
    $id = $_GPC['case_id'] ? $_GPC['case_id'] : '';
    $condition = ' AND uniacid=:uniacid ';
    if ($id) { $condition = $condition.'AND case_id=:id '; }  
    $params = array(':uniacid' => $_W['uniacid']);
    if ($id) { $params[':id'] = $id; }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (options LIKE :name) ';
        $params[':name'] = '%'.$keyword.'%';       
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_form_wanneng') . ' WHERE 1 '
        . $condition . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
    $case_sql = "SELECT id, name FROM " . tablename('slwl_fitment_wn_case');
    $case_arr = pdo_fetchall($case_sql);
    $list = pdo_fetchall($sql, $params);

    for ($i=0; $i<count($list); $i++) {      
        $tmp = json_decode($list[$i]['options'], true);
        $str = '';
        foreach ($tmp as $k => $v) {
            $str .= $k . ' : ' . $v . '<br/>';
        }
        $list[$i]['options'] = $str;
    }

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_form_wanneng') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);

} else if ($operation == 'post') {
    $id = intval($_GPC['id']);
    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['name'],
            'contact' => $_GPC['contact'],
            'msg' => $_GPC['msg'],
            'status' => intval($_GPC['status'])
        );
        if ($id) {
            pdo_update('slwl_fitment_form_wanneng', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_form_wanneng', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_form_wanneng') . " where 1 " . $condition, $params);


} else if($operation == 'setsave'){
    $id = intval($_GPC['id']);  
    if ($_W['ispost']) { 
        $data =  [
            'uniacid' => $_W['uniacid'],
            'mark' => $_GPC['mark'],
            'enabled' => $_GPC['enabled'],
            'uptime' => $_W['slwl']['datetime']['now']
        ];       
        pdo_update('slwl_fitment_form_wanneng', $data, array('id' => $id));
        iajax(0, '保存成功！');
    }
    
} else if ($operation == 'set') {
    $id = intval($_GPC['id']); 
    $uniacid =  $_W['uniacid'];
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_form_wanneng') . " where id=$id AND uniacid=$uniacid "); 
} else if ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $rst = pdo_delete('slwl_fitment_form_wanneng', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }
} else {
    message('请求方法不存在');
}

include $this->template('web/form');

?>