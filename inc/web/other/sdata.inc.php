<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;

$uniacid = $_W['uniacid'];
load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

// 幻灯片-删除数据
function fun_adv_data_clear($uniacid)
{
	$rst = pdo_delete('slwl_fitment_adv', array('uniacid' => $uniacid));
	if ($rst !== false) {
		return true;
	} else {
		return false;
	}
}
// 单图-删除数据
function fun_sole_data_clear($uniacid)
{
	$rst_1 = pdo_delete('slwl_fitment_pic_sole', array('uniacid' => $uniacid));
	$rst_2 = pdo_delete('slwl_fitment_pic_sole_option', array('uniacid' => $uniacid));
	if (($rst_1 !== false) && ($rst_2 !== false)) {
		return true;
	} else {
		return false;
	}
}
// 套图-删除数据
function fun_multi_data_clear($uniacid)
{
	$rst_1 = pdo_delete('slwl_fitment_pic_multi', array('uniacid' => $uniacid));
	$rst_2 = pdo_delete('slwl_fitment_pic_multi_option', array('uniacid' => $uniacid));
	if (($rst_1 !== false) && ($rst_2 !== false)) {
		return true;
	} else {
		return false;
	}
}
// 攻略-删除数据
function fun_guide_data_clear($uniacid)
{
	$rst_1 = pdo_delete('slwl_fitment_term', array('uniacid' => $uniacid));
	$rst_2 = pdo_delete('slwl_fitment_news', array('uniacid' => $uniacid));
	if (($rst_1 !== false) && ($rst_2 !== false)) {
		return true;
	} else {
		return false;
	}
}
// 普通文章-删除数据
function fun_news_data_clear($uniacid)
{
	$rst_1 = pdo_delete('slwl_fitment_act_term', array('uniacid' => $uniacid));
	$rst_2 = pdo_delete('slwl_fitment_act_news', array('uniacid' => $uniacid));
	if (($rst_1 !== false) && ($rst_2 !== false)) {
		return true;
	} else {
		return false;
	}
}
// 工地模块-删除数据
function fun_site_data_clear($uniacid)
{
	$rst_1 = pdo_delete('slwl_fitment_site_manage', array('uniacid' => $uniacid)); // 小区
	$rst_2 = pdo_delete('slwl_fitment_site_list', array('uniacid' => $uniacid)); // 工地列表
	$rst_3 = pdo_delete('slwl_fitment_site_list_dy', array('uniacid' => $uniacid)); // 工地动态
	$rst_4 = pdo_delete('slwl_fitment_site_progress', array('uniacid' => $uniacid)); // 工地进度
	$rst_5 = pdo_delete('slwl_fitment_site_type', array('uniacid' => $uniacid)); // 工地房型
	$rst_6 = pdo_delete('slwl_fitment_site_style', array('uniacid' => $uniacid)); // 装修风格
	$rst_7 = pdo_delete('slwl_fitment_site_mode', array('uniacid' => $uniacid)); // 装修方式
	if (($rst_1 !== false)
		&& ($rst_2 !== false)
		&& ($rst_3 !== false)
		&& ($rst_4 !== false)
		&& ($rst_5 !== false)
		&& ($rst_6 !== false)
		&& ($rst_7 !== false)
		) {
		return true;
	} else {
		return false;
	}
}
// 用户的收藏数据-删除
function fun_fav_data_clear($uniacid)
{
	$rst = pdo_delete('slwl_fitment_fav', array('uniacid' => $uniacid));
	if ($rst !== false) {
		return true;
	} else {
		return false;
	}
}


if ($_W['ispost'])
{
	$agreement = intval($_GPC['agreement']); // 协议
	if ($agreement != '1')
	{
		sl_ajax(1, '请勾选协议');
		exit;
	}

	$tablepre = $_W['config']['db']['tablepre']; // 表前缀

	$client_data_history_update = intval($_GPC['client_data_history_update']); // 客户管理-历史数据升级

	$adv_data_clear = intval($_GPC['adv_data_clear']); // 幻灯片-删除数据
	// $set_adv = intval($_GPC['set_adv']); // 幻灯片-添加数据

	$sole_data_clear = intval($_GPC['sole_data_clear']); // 单图-删除数据
	$sole_add_tag = intval($_GPC['sole_add_tag']); // 单图-添加数据

	$multi_data_clear = intval($_GPC['multi_data_clear']); // 套图-删除数据
	$multi_add_tag = intval($_GPC['multi_add_tag']); // 套图-添加数据

	$guide_data_clear = intval($_GPC['guide_data_clear']); // 攻略-删除数据
	$guide_add_class = intval($_GPC['guide_add_class']); // 攻略-添加数据

	$news_data_clear = intval($_GPC['news_data_clear']); // 普通文章-删除数据

	$site_data_clear = intval($_GPC['site_data_clear']); // 工地模块-删除数据
	$site_add_progress = intval($_GPC['site_add_progress']); // 工地模块-添加工地进度数据
	$site_add_type = intval($_GPC['site_add_type']); // 工地模块-添加工地房型数据
	$site_add_style = intval($_GPC['site_add_style']); // 工地模块-添加装修风格数据
	$site_add_mode = intval($_GPC['site_add_mode']); // 工地模块-添加装修方式数据


	$all_data_clear = intval($_GPC['all_data_clear']); // 全部数据-删除
	$user_data_collect_clear = intval($_GPC['user_data_collect_clear']); // 用户的收藏数据-删除

	// 幻灯片-删除数据
	if ($adv_data_clear == '1')
	{
		$rst_adv = fun_adv_data_clear($uniacid);
		if ($rst_adv == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 单图-删除数据
	if ($sole_data_clear == '1')
	{
		$rst_sole = fun_sole_data_clear($uniacid);
		if ($rst_sole == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 单图-添加数据
	if ($sole_add_tag == '1')
	{
		// 单图-风格
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '风格',
			'thumb' => '',
			'case' => '100',
			'isrecommand' => '0',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_sole_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert into `{$tablepre}slwl_fitment_pic_sole_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','宜家','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','现代','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','简欧','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','欧式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','北欧','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','中式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','简约','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','地中海','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','日式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','美式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','东南亚','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','田园','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','混塔','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','新古典','',100,0,1,'{$curr_date}');
		");

		// 单图-空间
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '空间',
			'thumb' => '',
			'case' => '6991',
			'isrecommand' => '1',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_sole_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_sole_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','客厅','',6556,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','厨房','',58977,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','卧室','',31666,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','阳台','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','卫生间','',100,0,1,'{$curr_date}'),
			('{$uniacid}',6,'{$parent_id}','餐厅','',5656,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','书房','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','儿童房','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','玄关','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','花园','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','衣帽间','',100,0,1,'{$curr_date}');
		");

		// 单图-局部
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '局部',
			'thumb' => '',
			'case' => '0',
			'isrecommand' => '0',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_sole_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_sole_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','衣柜','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','榻榻米','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','橱柜','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','鞋柜','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','背景墙','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','吊顶','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','酒柜','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','博古架','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','飘窗','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','楼梯','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','隐形门','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','吧台','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','隔断','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','相片墙','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','阁楼','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','窗帘','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','窗户','',100,0,1,'{$curr_date}');
		");

		// 单图-颜色
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '颜色',
			'thumb' => '',
			'case' => '0',
			'isrecommand' => '0',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_sole_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_sole_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','白色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','米色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','黄色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','橙色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','红色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','粉色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','绿色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','蓝色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','紫色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','黑色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','咖啡色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','灰色','',100,0,1,'{$curr_date}');
		");
	}
	// 套图-删除数据
	if ($multi_data_clear == '1')
	{
		$rst_multi = fun_multi_data_clear($uniacid);
		if ($rst_multi == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 套图-添加数据
	if ($multi_add_tag == '1')
	{
		// 套图标签-风格
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '风格',
			'thumb' => '',
			'case' => '2365',
			'isrecommand' => '1',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_multi_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert into `{$tablepre}slwl_fitment_pic_multi_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','宜家','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','现代','',6987,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','简欧','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','欧式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','北欧','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','中式','',69417,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','简约','',3694,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','地中海','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','日式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','美式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','东南亚','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','田园','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','混搭','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','新古典','',100,0,1,'{$curr_date}');
		");

		// 套图标签-户型
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '户型',
			'thumb' => '',
			'case' => '3699',
			'isrecommand' => '1',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_multi_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_multi_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','一居','',4656,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','二居','',3156,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','三居','',2587,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','四居','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','小户型','',6954,1,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','公寓','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','复式','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','别墅','',100,0,1,'{$curr_date}');
		");

		// 套图标签-面积
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '面积',
			'thumb' => '',
			'case' => '0',
			'isrecommand' => '0',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_multi_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_multi_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','60㎡以下','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','60-80㎡','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','80-100㎡','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','100-120㎡','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','120-150㎡','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','150㎡以上','',100,0,1,'{$curr_date}');
		");

		// 套图标签-颜色
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '颜色',
			'thumb' => '',
			'case' => '0',
			'isrecommand' => '0',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_pic_multi_option', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_pic_multi_option`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`case`,`isrecommand`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','白色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','米色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','黄色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','橙色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','红色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','粉色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','绿色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','蓝色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','紫色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','黑色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','咖啡色','',100,0,1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','灰色','',100,0,1,'{$curr_date}');
		");
	}
	// 攻略-删除数据
	if ($guide_data_clear == '1')
	{
		$rst_guide = fun_guide_data_clear($uniacid);
		if ($rst_guide == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 攻略-添加数据
	if ($guide_add_class == '1')
	{
		$site_root = $_W['siteroot'];

		$curr_y = date("Y", time());
		$curr_m = date("m", time());
		$curr_date = $_W['slwl']['datetime']['now'];

		// check 目录
		$tmppath = "../attachment/images/";
		$u       = $tmppath . $uniacid . "/";
		$y       = $u . $curr_y . "/";
		$m       = $y . $curr_m . "/";
		checkdir($tmppath);
		checkdir($u);
		checkdir($y);
		checkdir($m);

		$path = 'images/' . $uniacid . "/" . $curr_y . "/" . $curr_m . "/";
		$attr_url = $site_root . 'attachment/' . $path;

		// 复制文件
		@copy(MODULE_ROOT . '/public/image/sdata/term/01.png', ATTACHMENT_ROOT . $path . 'img_term_1.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/02.png', ATTACHMENT_ROOT . $path . 'img_term_2.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/03.png', ATTACHMENT_ROOT . $path . 'img_term_3.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/04.png', ATTACHMENT_ROOT . $path . 'img_term_4.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/05.png', ATTACHMENT_ROOT . $path . 'img_term_5.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/06.png', ATTACHMENT_ROOT . $path . 'img_term_6.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/07.png', ATTACHMENT_ROOT . $path . 'img_term_7.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/08.png', ATTACHMENT_ROOT . $path . 'img_term_8.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/09.png', ATTACHMENT_ROOT . $path . 'img_term_9.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/10.png', ATTACHMENT_ROOT . $path . 'img_term_10.png');
		@copy(MODULE_ROOT . '/public/image/sdata/term/11.png', ATTACHMENT_ROOT . $path . 'img_term_11.png');

		// 装修攻略模块-添加分类
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '装修攻略',
			'thumb' => '',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_term', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert into `{$tablepre}slwl_fitment_term`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','房产','{$path}img_term_1.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','设计','{$path}img_term_2.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','收房','{$path}img_term_3.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','预算','{$path}img_term_4.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','合同','{$path}img_term_5.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','材料','{$path}img_term_3.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','拆开','{$path}img_term_6.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','水电','{$path}img_term_7.png',1,'{$curr_date}');
		");

		// 装修攻略模块-添加分类-选材手册
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '选材手册',
			'thumb' => '',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_term', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_term`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','建材','{$path}img_term_6.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','家具','{$path}img_term_8.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','软装','{$path}img_term_9.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','预算','{$path}img_term_3.png',1,'{$curr_date}');
		");

		// 装修攻略模块-添加分类-家具百科
		$data = array();
		$data = array(
			'uniacid' => $uniacid,
			'displayorder' => '0',
			'parentid' => '0',
			'name' => '家具百科',
			'thumb' => '',
			'enabled' => '1',
			'addtime' => $curr_date
		);

		pdo_insert('slwl_fitment_term', $data);
		$parent_id = pdo_insertid();

		pdo_query("
			insert  into `{$tablepre}slwl_fitment_term`(`uniacid`,`displayorder`,`parentid`,`name`,`thumb`,`enabled`,`addtime`) values
			('{$uniacid}',0,'{$parent_id}','装修','{$path}img_term_6.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','建材','{$path}img_term_5.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','家具','{$path}img_term_9.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','房产','{$path}img_term_4.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','软装','{$path}img_term_9.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','家电','{$path}img_term_6.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','品牌','{$path}img_term_10.png',1,'{$curr_date}'),
			('{$uniacid}',0,'{$parent_id}','生活','{$path}img_term_11.png',1,'{$curr_date}');
		");
	}
	// 普通文章-删除数据
	if ($news_data_clear == '1')
	{
		$rst_news = fun_news_data_clear($uniacid);
		if ($rst_news == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 工地模块-删除数据
	if ($site_data_clear == '1')
	{
		$rst_site = fun_site_data_clear($uniacid);
		if ($rst_site == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 工地模块-添加工地进度数据
	if ($site_add_progress == '1')
	{
		pdo_query("
			insert  into `{$tablepre}slwl_fitment_site_progress`(`uniacid`,`displayorder`,`title`,`thumb`,`enabled`,`create_time`) values
			('{$uniacid}',0,'开工','',1,'{$curr_date}'),
			('{$uniacid}',0,'水电','',1,'{$curr_date}'),
			('{$uniacid}',0,'泥木','',1,'{$curr_date}'),
			('{$uniacid}',0,'油漆','',1,'{$curr_date}'),
			('{$uniacid}',0,'竣工','',1,'{$curr_date}');
		");
	}
	// 工地模块-添加工地房型数据
	if ($site_add_type == '1')
	{
		pdo_query("
			insert  into `{$tablepre}slwl_fitment_site_type`(`uniacid`,`displayorder`,`title`,`thumb`,`enabled`,`create_time`) values
			('{$uniacid}',0,'公寓','',1,'{$curr_date}'),
			('{$uniacid}',0,'别墅','',1,'{$curr_date}'),
			('{$uniacid}',0,'普通住宅','',1,'{$curr_date}'),
			('{$uniacid}',0,'大平层','',1,'{$curr_date}'),
			('{$uniacid}',0,'老公房','',1,'{$curr_date}'),
			('{$uniacid}',0,'公装','',1,'{$curr_date}'),
			('{$uniacid}',0,'复式','',1,'{$curr_date}'),
			('{$uniacid}',0,'其他','',1,'{$curr_date}');
		");
	}
	// 工地模块-添加装修风格数据
	if ($site_add_style == '1')
	{
		pdo_query("
			insert  into `{$tablepre}slwl_fitment_site_style`(`uniacid`,`displayorder`,`title`,`thumb`,`enabled`,`create_time`) values
			('{$uniacid}',0,'现代','',1,'{$curr_date}'),
			('{$uniacid}',0,'中式','',1,'{$curr_date}'),
			('{$uniacid}',0,'欧式','',1,'{$curr_date}'),
			('{$uniacid}',0,'美式','',1,'{$curr_date}'),
			('{$uniacid}',0,'混搭','',1,'{$curr_date}'),
			('{$uniacid}',0,'田园','',1,'{$curr_date}'),
			('{$uniacid}',0,'新古典','',1,'{$curr_date}'),
			('{$uniacid}',0,'简约','',1,'{$curr_date}'),
			('{$uniacid}',0,'地中海','',1,'{$curr_date}'),
			('{$uniacid}',0,'东南亚','',1,'{$curr_date}'),
			('{$uniacid}',0,'日式','',1,'{$curr_date}'),
			('{$uniacid}',0,'宜家','',1,'{$curr_date}'),
			('{$uniacid}',0,'北欧','',1,'{$curr_date}'),
			('{$uniacid}',0,'港式','',1,'{$curr_date}'),
			('{$uniacid}',0,'简美','',1,'{$curr_date}'),
			('{$uniacid}',0,'简欧','',1,'{$curr_date}'),
			('{$uniacid}',0,'LOFT','',1,'{$curr_date}'),
			('{$uniacid}',0,'新中式','',1,'{$curr_date}'),
			('{$uniacid}',0,'工业风','',1,'{$curr_date}'),
			('{$uniacid}',0,'法式','',1,'{$curr_date}'),
			('{$uniacid}',0,'台式','',1,'{$curr_date}'),
			('{$uniacid}',0,'轻奢','',1,'{$curr_date}');
		");
	}
	// 工地模块-添加装修方式数据
	if ($site_add_mode == '1')
	{
		pdo_query("
			insert  into `{$tablepre}slwl_fitment_site_mode`(`uniacid`,`displayorder`,`title`,`thumb`,`enabled`,`create_time`) values
			('{$uniacid}',1,'清包','',1,'{$curr_date}'),
			('{$uniacid}',2,'半包','',1,'{$curr_date}'),
			('{$uniacid}',3,'全包','',1,'{$curr_date}'),
			('{$uniacid}',4,'拎包入住','',1,'{$curr_date}'),
			('{$uniacid}',5,'整装','',1,'{$curr_date}');
		");
	}
	// 用户的收藏数据-删除
	if ($user_data_collect_clear == '1')
	{
		$rst_user = fun_fav_data_clear($uniacid);
		if ($rst_user == false) {
			sl_ajax(1, '幻灯片-删除数据失败');
		}
	}
	// 全部数据-删除
	if ($all_data_clear == '1')
	{
		fun_adv_data_clear($uniacid); // 幻灯片-删除数据
		fun_sole_data_clear($uniacid); // 单图-删除数据
		fun_multi_data_clear($uniacid); // 套图-删除数据
		fun_guide_data_clear($uniacid); // 攻略-删除数据
		fun_site_data_clear($uniacid); // 工地模块-删除数据
		fun_fav_data_clear($uniacid); // 用户的收藏数据-删除

		pdo_delete('slwl_fitment_act_news', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_act_term', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_act_term_adv', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_adgroup', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_adact', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_adsp', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_booking', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_calc_booking', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_designer', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_giftbig', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_guestbook', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_mod_wxapp', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_reserve', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_settings', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_style', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_term', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_term_des', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_tipswx', array('uniacid' => $uniacid));
		pdo_delete('slwl_fitment_users', array('uniacid' => $uniacid));
	}

	sl_ajax(0, '初始化数据成功');
	exit;
}

include $this->template('web/other/sdata');

?>
