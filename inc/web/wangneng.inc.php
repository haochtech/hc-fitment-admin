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
    $sql = "SELECT * FROM " . tablename('slwl_fitment_wn_case'). ' WHERE 1 '
        . $condition . " ORDER BY sort DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);

    for ($i=0; $i<=count($list); $i++) {  
        if (trim($list[$i]['imbg'])) {           
            $list[$i]['imbg_format'] = tomedia($list[$i]['imbg']);
        } else {
            if ($list[$i]['bg'] == '0') { $list[$i]['imbg_format'] = MODULE_URL.'img/pic/theme/blue-bg.jpg'; }
            else if($list[$i]['bg'] == '1') { $list[$i]['imbg_format'] = MODULE_URL.'img/pic/theme/ghost-bg.jpg'; }
            else if($list[$i]['bg'] == '2') { $list[$i]['imbg_format'] = MODULE_URL.'img/pic/theme/guofeng-bg.jpg'; }
        }   
       
    }

    // pdo_debug();exit;

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_wn_case') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);


} else if($operation == 'bannerlist'){
    $id = intval($_GPC['id']);     
    $keyword = $_GPC['keyword'];
    $condition = " AND uniacid=:uniacid  AND case_id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id'=>$id);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (title LIKE :name) ';
        $params[':name'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_form_banner'). ' WHERE 1 '
        . $condition . " ORDER BY sort DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_form_banner') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);
    $case_id = $id;

} else if ($operation == 'bannerpost') {
    $id = intval($_GPC['id']);  
    $case_id = intval($_GPC['case_id']); 
    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sort' => $_GPC['sort'],
            'title' => $_GPC['title'],
            'img' => $_GPC['img'],
            'url' => $_GPC['url'],
            'enabled'=> $_GPC['enabled'],
            'case_id' => $case_id        
        );
        if ($id) {
            pdo_update('slwl_fitment_form_banner', $data, array('id' => $id));
        } else {  
            $data['addtime'] = $_W['slwl']['datetime']['now'];           
            pdo_insert('slwl_fitment_form_banner', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_form_banner') . " where 1 " . $condition, $params);
   
} else if ($operation == 'bannerdel') {
    $id = intval($_GPC['id']);
    $rst = pdo_delete('slwl_fitment_form_banner', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }
} else if ($operation == 'post') {
    $id = intval($_GPC['id']);   
    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sort' => $_GPC['sort'],
            'name' => $_GPC['name'],
            'label_show' => $_GPC['label_show'],
            'bg'=> $_GPC['bg'],
            'imbg'=> $_GPC['imbg'],
            'detail' => htmlspecialchars_decode($_GPC['detail'])           
        );
        if ($id) {
            pdo_update('slwl_fitment_wn_case', $data, array('id' => $id));
        } else {  
            $data['addtime'] = $_W['slwl']['datetime']['now'];           
            pdo_insert('slwl_fitment_wn_case', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_wn_case') . " where 1 " . $condition, $params);

    if(empty($one)){
        $one = array('label_show'=>'0');
    }
} else if ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $rst = pdo_delete('slwl_fitment_wn_case', array('id' => $id));
    pdo_delete('slwl_fitment_wn_list', ['case_id'=>$id]);
    pdo_delete('slwl_fitment_form_banner', ['case_id'=>$id]);   
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }

} else if ($operation == 'list') {
    $keyword = $_GPC['keyword'];
    $condition = " AND uniacid=:uniacid AND case_id=:id ";
    $id = $_GPC['id'];

    $params = array(':uniacid' => $_W['uniacid'], ':id'=>$id);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (label LIKE :label) ';
        $params[':label'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_wn_list'). " WHERE 1 "
        . $condition . " ORDER BY sort DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
    $sql2 = "SELECT name FROM " . tablename('slwl_fitment_wn_case'). "WHERE id=$id" ;
    $list1 = pdo_fetchall($sql, $params);
    $case_name = pdo_fetch($sql2);
    $case_name = $case_name['name'];
    $case_id = $_GPC['id'];    
    $types = array(
        'input'=>'输入框',
        'select'=>'下拉列表',
        'date'=>'日期',
        'checkbox'=>'复选按钮',
        'radio'=>'单选按钮',
        'region'=>'省市区选择器',
        'textarea'=>'多行文本输入框'
    );
    for ($i = 0; $i<count($list1); $i++) {        
        $list1[$i]['type'] = $types[$list1[$i]['type']];
    }
    $list = $list1;  
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_wn_list') . ' WHERE 1 ' . $condition, $params);  
    $pager = pagination($total, $pindex, $psize);

} else if ($operation == 'view'){
    $id = intval($_GPC['id']); 
    $case_id = $_GPC['case_id'];
    $options = $_GPC['options'] ? $_GPC['options'] : '';
    $options = $options ? json_encode($options,JSON_UNESCAPED_UNICODE) : '';
    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'sort' => $_GPC['sort'],
            'case_id' => $_GPC['case_id'],
            'enabled' => $_GPC['enabled'],
            'label' => $_GPC['label'],
            'type' => $_GPC['type'],
            'placeholder' => $_GPC['placeholder'],
            'options' => $options     
        );

        if ($id) {
            pdo_update('slwl_fitment_wn_list', $data, array('id' => $id));
        } else {  
            $data['addtime'] = $_W['slwl']['datetime']['now'];         
            pdo_insert('slwl_fitment_wn_list', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
    }
    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);  
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_wn_list') . " where 1 " . $condition, $params);
    if (empty($one)) {
        $one = array('type'=>'input');
    }
    
} else if ($operation == 'dellist') {
    $id = intval($_GPC['id']);
    $rst = pdo_delete('slwl_fitment_wn_list', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }
} else {
    message('请求方法不存在');
}

include $this->template('web/wangneng');

?>