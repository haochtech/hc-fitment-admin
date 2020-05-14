<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_W, $_GPC, $_SL;

load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($_W['ispost']) {
    $agreement = intval($_GPC['agreement']);

    if ($agreement != '1') {
        iajax(1, '请勾选协议');
        exit;
    }

    // SN-单图
    $condition_sn_sole = " AND uniacid=:uniacid AND picsn='' ";
    $params_sn_sole = array(':uniacid' => $_W['uniacid']);
    $sql_sn_sole = "SELECT id FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 ' . $condition_sn_sole;
    $list_sn_sole = pdo_fetchall($sql_sn_sole, $params_sn_sole);

    if ($list_sn_sole) {
        foreach ($list_sn_sole as $k => $v) {
            $pic_sn_sole = 'SLWL' . $_SL->system->datetime->nowYmdHis . str_pad($k, 6, '0', STR_PAD_LEFT);
            pdo_update('slwl_fitment_pic_sole', array('picsn'=>$pic_sn_sole), array('id'=>$v['id']));
        }
    }

    // 生成宽高-单图
    $condition_wh_sole = " AND uniacid=:uniacid AND (pic_width='0' OR pic_height='0') ";
    $params_wh_sole = array(':uniacid' => $_W['uniacid']);
    $sql_wh_sole = "SELECT id, thumb FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 ' . $condition_wh_sole;
    $list_wh_sole = pdo_fetchall($sql_wh_sole, $params_wh_sole);

    if ($list_wh_sole) {
        foreach ($list_wh_sole as $k => $v) {
            $pic_info_sole = getimagesize(tomedia($v['thumb']));

            if ($pic_info_sole) {
                $pic_width_sole = $pic_info_sole[0];
                $pic_height_sole = $pic_info_sole[1];
            } else {
                $pic_width_sole = '0';
                $pic_height_sole = '0';
            }
            pdo_update('slwl_fitment_pic_sole', array('pic_width'=>$pic_width_sole, 'pic_height'=>$pic_height_sole), array('id'=>$v['id']));
        }
    }

    // SN-套图
    $condition_sn_multi = " AND uniacid=:uniacid AND picsn='' ";
    $params_sn_multi = array(':uniacid' => $_W['uniacid']);
    $sql_sn_multi = "SELECT id FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_sn_multi;
    $list_sn_multi = pdo_fetchall($sql_sn_multi, $params_sn_multi);

    if ($list_sn_multi) {
        foreach ($list_sn_multi as $k => $v) {
            $pic_sn_multi = 'SLWL' . $_SL->system->datetime->nowYmdHis . str_pad($k, 7, '0', STR_PAD_LEFT);
            pdo_update('slwl_fitment_pic_multi', array('picsn'=>$pic_sn_multi), array('id'=>$v['id']));
        }
    }

    // 生成宽高-套图
    $condition_wh_multi = " AND uniacid=:uniacid AND (pic_width='0' OR pic_height='0') ";
    $params_wh_multi = array(':uniacid' => $_W['uniacid']);
    $sql_wh_multi = "SELECT id, thumb FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_wh_multi;
    $list_wh_multi = pdo_fetchall($sql_wh_multi, $params_wh_multi);

    if ($list_wh_multi) {
        foreach ($list_wh_multi as $k => $v) {
            $pic_info_multi = getimagesize(tomedia($v['thumb']));

            if ($pic_info_multi) {
                $pic_width_multi = $pic_info_multi[0];
                $pic_height_multi = $pic_info_multi[1];
            } else {
                $pic_width_multi = '0';
                $pic_height_multi = '0';
            }
            pdo_update('slwl_fitment_pic_multi', array('pic_width'=>$pic_width_multi, 'pic_height'=>$pic_height_multi), array('id'=>$v['id']));
        }
    }

    // slwl_fitment_act_news
    // newsname => name
    $condition_act_news = " AND uniacid=:uniacid AND `name`='' ";
    $params_act_news = array(':uniacid' => $_W['uniacid']);
    $sql_act_news = "SELECT * FROM " . tablename('slwl_fitment_act_news'). ' WHERE 1 ' . $condition_act_news;
    $list_act_news = pdo_fetchall($sql_act_news, $params_act_news);

    if ($list_act_news) {
        foreach ($list_act_news as $k => $v) {
            if ((isset($v['newsname'])) && ($v['newsname'] != '')) {
                $data_act_news = array(
                    'name'=>$v['newsname'],
                );
                pdo_update('slwl_fitment_act_news', $data_act_news, array('id'=>$v['id']));
            }
        }
    }

    // slwl_fitment_news
    // newsname => name
    // newsmoney => money
    $condition_news = " AND uniacid=:uniacid AND (`name`='' OR `money`='') ";
    $params_news = array(':uniacid' => $_W['uniacid']);
    $sql_news = "SELECT * FROM " . tablename('slwl_fitment_news'). ' WHERE 1 ' . $condition_news;
    $list_news = pdo_fetchall($sql_news, $params_news);

    if ($list_news) {
        foreach ($list_news as $k => $v) {
            if ((isset($v['newsname'])) && ($v['newsname'] != '')) {
                $data_news = array(
                    'name'=>$v['newsname'],
                    'money'=>$v['newsmoney'],
                );
                pdo_update('slwl_fitment_news', $data_news, array('id'=>$v['id']));
            }
        }
    }

    iajax(0, '数据更新成功');
    exit;
}

include $this->template('web/data-transfer');

?>