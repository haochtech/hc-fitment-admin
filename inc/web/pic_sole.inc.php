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

    $condition = " AND uniacid=:uniacid AND desid='0' ";
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    if ($keyword != '') {
        $condition .= ' AND (name LIKE :name) ';
        $params[':name'] = '%'.$keyword.'%';
    }

    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {

        $options = array();
        if($_GPC['options']) {
            foreach ($_GPC['options'] as $k=>$v){
                $options['options'][] = $v;
            }
        }

        // 补充图片宽高
        $thumb = trim($_GPC['thumb']);
        $pic_width = '0';
        $pic_height = '0';
        if ($thumb) {
            $pic_info = getimagesize(tomedia($thumb));
            if ($pic_info) {
                $pic_width = $pic_info[0];
                $pic_height = $pic_info[1];
            }
        }

        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => $_GPC['displayorder'],
            'name' => $_GPC['name'],
            'thumb' => $thumb,
            'pic_width' => $pic_width,
            'pic_height' => $pic_height,
            'show_num' => intval($_GPC['show_num']),
            'fav_num' => intval($_GPC['fav_num']),
            'enabled' => intval($_GPC['enabled']),
        );
        $data['options'] = json_encode($options); // 压缩
        if ($id) {
            pdo_update('slwl_fitment_pic_sole', $data, array('id' => $id));
        } else {
            $picsn = 'SLWL'.date('YmdHis') . random(6, 1);
            $data['picsn'] = $picsn;
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_pic_sole', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
    }

    $condition = " AND uniacid=:uniacid AND enabled='1' ";
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 1000;
    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
    $list = pdo_fetchall($sql, $params);

    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . " where 1 " . $condition, $params);

    $checks = json_decode($one['options'], true);

    if ($checks) {
        foreach ($list as $k => $v) {
            foreach ($checks['options'] as $key => $value) {
                if ($v['id']==$value) {
                    $list[$k]['checked'] = '1';
                    break;
                } else {
                    $list[$k]['checked'] = '0';
                }
            }
        }
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_pic_sole', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} elseif ($operation == 'tag') {
    $condition = " AND uniacid=:uniacid ";
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 1000;
    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
    $list = pdo_fetchall($sql, $params);


} elseif ($operation == 'post_tag') {
    $id = intval($_GPC['id']);
    $tid = max(0, intval($_GPC['tid']));

    if ($_W['ispost']) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => $_GPC['displayorder'],
            'parentid' => $tid,
            'name' => $_GPC['name'],
            'enabled' => intval($_GPC['enabled']),
            'case' => intval($_GPC['case']),
            'isrecommand' => intval($_GPC['isrecommand']),
            'thumb' => $_GPC['thumb'],
        );
        if ($id) {
            pdo_update('slwl_fitment_pic_sole_option', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_pic_sole_option', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
    }

    if ($tid>0) {
        $where = " AND uniacid=:uniacid AND parentid='0' ";
        $where_params = array(':uniacid' => $_W['uniacid']);
        $term_list = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_sole_option') . ' WHERE 1 '
            . $where . " ORDER BY displayorder DESC, id DESC", $where_params);
    }

    if ($id) {
        $condition = " AND uniacid=:uniacid AND id=:id ";
        $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
        $one_tag = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole_option') . " where 1 " . $condition, $params);
    }


} elseif ($operation == 'delete_tag') {
    $id = intval($_GPC['id']);

    $condition = " AND uniacid=:uniacid AND parentid=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one_child = pdo_fetch("SELECT id FROM " . tablename('slwl_fitment_pic_sole_option') . " where 1 " . $condition, $params);
    if ($one_child) {
        iajax(1, '标签下存在子标签不能删除');
    }

    $rst = pdo_delete('slwl_fitment_pic_sole_option', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} elseif ($operation == 'build') {
    $id = intval($_GPC['id']);

    $condition_one = " AND uniacid=:uniacid AND id=:id ";
    $params_one = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition_one, $params_one);

    if (empty($one)) {
        iajax(1, '抱歉，不存在或是已经被删除');
        exit;
    }

    require_once MODULE_ROOT . "/lib/Common.class.php";
    $app = Common::get_app_info($_W['uniacid']);
    require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
    $jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);
    $rets = $jssdk->qrcode_create($one['picsn']);

    if ($rets && $rets['errcode'] == 0) {
        pdo_update('slwl_fitment_pic_sole', array('qrcode' => $rets['data']), array('id' => $id));

        $res = array(
            'thumb_url'=> tomedia($rets['data']),
        );

        iajax(0, $res);
    } else {
        iajax(1, $rets['errmsg'].'-'.$rets['data']);
    }
    exit;


} else {
    message('请求方法不存在');


}

include $this->template('web/pic_sole');

