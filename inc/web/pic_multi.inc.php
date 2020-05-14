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

    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition, $params);
    $pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if ($_W['ispost']) {

        $options = array();
        if($_GPC['options']){
            foreach ($_GPC['options'] as $k=>$v){
                $options['options'][] = $v;
            }
        }

        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => $_GPC['displayorder'],
            'name' => $_GPC['name'],
            'show_num' => intval($_GPC['show_num']),
            'fav_num' => intval($_GPC['fav_num']),
            'enabled' => intval($_GPC['enabled']),
        );
        $data['options'] = json_encode($options); // 压缩
        if ($id) {
            pdo_update('slwl_fitment_pic_multi', $data, array('id' => $id));
        } else {
            $picsn = 'SLWL'.date('YmdHis') . random(6, 1);
            $data['picsn'] = $picsn;
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_pic_multi', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }

    $condition = " AND uniacid=:uniacid AND enabled='1' ";
    $params = array(':uniacid' => $_W['uniacid']);
    $pindex = max(1, intval($_GPC['page']));
    $psize = 1000;
    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
        . $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
    $list = pdo_fetchall($sql, $params);

    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 " . $condition, $params);

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


} elseif ($operation == 'post_pic') {
    $id = intval($_GPC['id']);

    $condition = " AND uniacid=:uniacid AND id=:id ";
    $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 " . $condition, $params);

    if ($one) {
        $smeta = json_decode($one['smeta'], true);
    }

    if ($_W['ispost']) {

        $options = array();
        $data = array();
        $photo = array();
        $tmp_pic = array();
        $iscover = false;

        if ($_GPC['options']) {
            $options = $_GPC['options'];

            foreach ($options['attachment'] as $k => $v) {
                $tmp_pic[$k]['attachment'] = $v;
            }

            foreach ($options['title'] as $k => $v) {
                $tmp_pic[$k]['title'] = $v;
            }

            foreach ($options['cover'] as $k => $v) {
                $tmp_pic[$k]['cover'] = $v;

                if ($v == '1') {
                    $iscover = true;
                    $data['name'] = $options['title'][$k];
                    $data['thumb'] = $options['attachment'][$k];
                }
            }

            foreach ($tmp_pic as $k=>$v){
                $photo[] = $v;
            }

            if ($iscover == false) {
                if ($one['name'] == '') {
                    $data['name'] = @$photo[0]['title'];
                }
                if ($one['thumb'] == '') {
                    $data['thumb'] = @$photo[0]['attachment'];
                } else {

                }
            }

            // 补充图片宽高
            $pic_info = getimagesize(tomedia($data['thumb']));
            if ($pic_info) {
                $pic_width = $pic_info[0];
                $pic_height = $pic_info[1];
            } else {
                $pic_width = '0';
                $pic_height = '0';
            }
            $data['pic_width'] = $pic_width;
            $data['pic_height'] = $pic_height;


            $data['smeta'] = json_encode($photo); // 压缩

            pdo_update('slwl_fitment_pic_multi', $data, array('id' => $id));

            iajax(0, '保存成功');
            exit;
        } else {
            iajax(1, '保存失败，数据为空');
            exit;
        }
    }


} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);

    $rst = pdo_delete('slwl_fitment_pic_multi', array('id' => $id));
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
    $sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
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
            'thumb' => $_GPC['thumb']
        );
        if ($id) {
            pdo_update('slwl_fitment_pic_multi_option', $data, array('id' => $id));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_pic_multi_option', $data);
            $id = pdo_insertid();
        }
        iajax(0, '保存成功');
        exit;
    }

    if ($tid>0) {
        $where = " AND uniacid=:uniacid AND parentid='0' ";
        $where_params = array(':uniacid' => $_W['uniacid']);
        $term_list = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_multi_option') . ' WHERE 1 '
            . $where . " ORDER BY displayorder DESC, id DESC", $where_params);
    }

    if ($id) {
        $condition = " AND uniacid=:uniacid AND id=:id ";
        $params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
        $one_tag = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi_option') . " where 1 " . $condition, $params);
    }


} elseif ($operation == 'delete_tag') {
    $id = intval($_GPC['id']);

    $condition = " AND uniacid=:uniacid AND parentid=:parentid ";
    $params = array(':uniacid' => $_W['uniacid'], ':parentid' => $id);
    $one_child = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi_option') . " where 1 " . $condition, $params);
    if ($one_child) {
        iajax(1, '标签下存在子标签不能删除');
    }

    $rst = pdo_delete('slwl_fitment_pic_multi_option', array('id' => $id));
    if ($rst !== false) {
        iajax(0, $_W['slwl']['lang']['tips_success']);
    } else {
        iajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
    }


} elseif ($operation == 'build') {
    $id = intval($_GPC['id']);

    $condition_one = " AND uniacid=:uniacid AND id=:id ";
    $params_one = array(':uniacid' => $_W['uniacid'], ':id' => $id);
    $one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition_one, $params_one);
    if (empty($one)) {
        iajax(1, '抱歉，不存在或是已经被删除');
    }

    require_once MODULE_ROOT . "/lib/Common.class.php";
    $app = Common::get_app_info($_W['uniacid']);
    require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
    $jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);
    $rets = $jssdk->qrcode_create($one['picsn']);

    if ($rets && $rets['errcode'] == 0) {
        pdo_update('slwl_fitment_pic_multi', array('qrcode' => $rets['data']), array('id' => $id));

        $res = array(
            'thumb_url'=> tomedia($rets['data']),
        );

        iajax(0, $res);
    } else {
        iajax(1, $rets['errmsg'].'-'.$rets['data']);
    }
    exit;


} elseif ($operation == 'post_update') {
    if ($_W['ispost']) {

        $options['status'] = '1';

        $data = array();
        $data['uniacid'] = $_W['uniacid'];
        $data['setting_name'] = 'set_sync_pic_multi';
        $data['setting_value'] = json_encode($options);

        $condition_sync = " AND uniacid=:uniacid AND picsn='' ";
        $params_sync = array(':uniacid' => $_W['uniacid']);
        $sql_sync = "SELECT id FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_sync;
        $list_sync = pdo_fetchall($sql_sync, $params_sync);

        if ($list_sync) {
            foreach ($list_sync as $k => $v) {
                $picsn = 'SLWL' . $_W['slwl']['datetime']['nowYmdHis'] . str_pad($k, 6, '0', STR_PAD_LEFT);
                pdo_update('slwl_fitment_pic_multi', array('picsn'=>$picsn), array('id'=>$v['id']));
            }
        }

        // 生成宽高
        $condition_wh = " AND uniacid=:uniacid AND (pic_width='0' OR pic_height='0') ";
        $params_wh = array(':uniacid' => $_W['uniacid']);
        $sql_wh = "SELECT id, thumb FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_wh;
        $list_wh = pdo_fetchall($sql_wh, $params_wh);

        if ($list_wh) {
            foreach ($list_wh as $k => $v) {
                $pic_info = getimagesize(tomedia($v['thumb']));

                if ($pic_info) {
                    $pic_width = $pic_info[0];
                    $pic_height = $pic_info[1];
                } else {
                    $pic_width = '0';
                    $pic_height = '0';
                }

                pdo_update('slwl_fitment_pic_multi', array('pic_width'=>$pic_width, 'pic_height'=>$pic_height), array('id'=>$v['id']));
            }
        }

        if ($set) {
            pdo_update('slwl_fitment_settings', $data, array('id' => $set['id']));
        } else {
            $data['addtime'] = $_W['slwl']['datetime']['now'];
            pdo_insert('slwl_fitment_settings', $data);
        }
        iajax(0, 'ok');
        exit;
    }


} else {
    message('请求方法不存在');
}

include $this->template('web/pic_multi');

