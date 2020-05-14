<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

$account_table = table('account');
$account_table->searchWithType(array(ACCOUNT_TYPE_APP_NORMAL));

// $account_table->searchWithPage($pindex, $psize);
$wxapp_lists = $account_table->searchAccountList();

if ($wxapp_lists) {
	foreach ($wxapp_lists as &$account) {
		$account = uni_fetch($account['uniacid']);
		$account['versions'] = wxapp_get_some_lastversions($account['uniacid']);
		if ($account['versions']) {
			foreach ($account['versions'] as $version) {
				if ($version['current']) {
					$account['current_version'] = $version;
				}
			}
		}
	}
}
// var_dump($wxapp_lists);

if ($_W['ispost']) {
	$agreement = intval($_GPC['agreement']);
	$datadel = intval($_GPC['datadel']); // 是否删除原小程序数据

	$app_src = intval($_GPC['app_src']); // 原小程序ID
	$app_goal = intval($_GPC['app_goal']); // 目标小程序ID

	if ($agreement != '1') {
		sl_ajax(1, '请勾选协议');
		exit;
	}

	$site_root = $_W['siteroot'];

	// 图片变量
	$images_id_src = 'images/' . $app_src . '/'; // 图片原ID，正常
	$images_id_goal = 'images/' . $app_goal . '/'; // 图片目标ID，正常
	$images_id_src_special = 'images\/' . $app_src . '\/'; // 原ID，json
	$images_id_goal_special = 'images\/' . $app_goal . '\/'; // 目标ID，json

	$videos_id_src = 'videos/' . $app_src . '/'; // 视频原ID，正常
	$videos_id_goal = 'videos/' . $app_goal . '/'; // 视频目标ID，正常

	$test_goal_data_del = '1';
	$test_0 = '1';
	$test_1 = '1';
	$test_2 = '1';
	$test_3 = '1';
	$test_4 = '1';
	$test_5 = '1';
	$test_6 = '1';
	$test_7 = '1';
	$test_8 = '1';
	$test_9 = '1';
	$test_10 = '1';
	$test_11 = '1';
	$test_12 = '1';
	$test_13 = '1';
	$test_14 = '1';
	$test_15 = '1';

	// 清除目标小程序，原有数据
	if ($test_goal_data_del == '1') {
		pdo_delete('slwl_fitment_adact', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_adgroup', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_adsp', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_adv', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_booking', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_designer', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_fav', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_guestbook', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_news', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_pic_multi', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_pic_multi_option', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_pic_sole', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_pic_sole_option', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_reserve', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_settings', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_term', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_tipswx', array('uniacid' => $app_goal));
		pdo_delete('slwl_fitment_users', array('uniacid' => $app_goal));
	}

	// 复制目录
	if ($test_0 == '1') {
		// dump(ATTACHMENT_ROOT . 'images/' . $app_src);
		// dump(ATTACHMENT_ROOT . 'images/' . $app_goal);
		// dump(ATTACHMENT_ROOT . 'videos/' . $app_src);
		// dump(ATTACHMENT_ROOT . 'videos/' . $app_goal);

		$path_app_src_images = ATTACHMENT_ROOT . 'images/' . $app_src;
		$path_app_src_videos = ATTACHMENT_ROOT . 'videos/' . $app_src;

		if (file_exists($path_app_src_images)) {
			@recurse_copy($path_app_src_images, ATTACHMENT_ROOT  . 'images/' . $app_goal); // 图片
		}
		if (file_exists($path_app_src_videos)) {
			@recurse_copy($path_app_src_videos, ATTACHMENT_ROOT  . 'videos/' . $app_goal); // 视频
		}
	}

	// 单页面
	if ($test_1 == '1') {
		$condition_adact = ' AND uniacid=:uniacid ';
		$params_adact = array(':uniacid' => $app_src);
		$pindex_adact = 1;
		$psize_adact = 5000;
		$sql_adact = "SELECT * FROM " . tablename('slwl_fitment_adact'). ' WHERE 1 ' . $condition_adact . " ORDER BY id ASC LIMIT " . ($pindex_adact - 1) * $psize_adact .',' .$psize_adact;
		$list_adact = pdo_fetchall($sql_adact, $params_adact);

		foreach ($list_adact as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);
			$new_detail = str_ireplace($images_id_src, $images_id_goal, $v['detail']);
			$new_detail = str_ireplace($videos_id_src, $videos_id_goal, $new_detail);
			$new_out_thumb = str_ireplace($images_id_src, $images_id_goal, $v['out_thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'name' => $v['name'],
				'subtitle' => $v['subtitle'],
				'thumb' => $new_thumb,
				'detail' => $new_detail,
				'out_thumb' => $new_out_thumb,
				'out_link' => $v['out_link'],
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_adact', $data);
		}
	}

	// 组合广告
	if ($test_2 == '1') {
		$condition_adgroup = " AND uniacid=:uniacid AND setting_name=:setting_name ";
		$params_adgroup = array(':uniacid' => $app_src, ':setting_name'=>'set_adgroup');
		$set_adgroup = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_adgroup') . ' WHERE 1 '. $condition_adgroup, $params_adgroup);

		if ($set_adgroup) {
			$new_value = str_ireplace($images_id_src_special, $images_id_goal_special, $set_adgroup['setting_value']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'setting_name' => 'set_adgroup',
				'setting_value' => $new_value,
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_adgroup', $data);
		}
	}

	// 首页广告
	if ($test_3 == '1') {
		$condition_adsp = ' AND uniacid=:uniacid ';
		$params_adsp = array(':uniacid' => $app_src);
		$pindex_adsp = 1;
		$psize_adsp = 5000;
		$sql_adsp = "SELECT * FROM " . tablename('slwl_fitment_adsp'). ' WHERE 1 ' . $condition_adsp . " ORDER BY id ASC LIMIT " . ($pindex_adsp - 1) * $psize_adsp .',' .$psize_adsp;
		$list_adsp = pdo_fetchall($sql_adsp, $params_adsp);

		foreach ($list_adsp as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'displayorder' => $v['displayorder'],
				'enabled' => $v['enabled'],
				'page_url' => $v['page_url'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_adsp', $data);
		}
	}

	// 轮播图
	if ($test_4 == '1') {
		$condition_adv = ' AND uniacid=:uniacid ';
		$params_adv = array(':uniacid' => $app_src);
		$pindex_adv = 1;
		$psize_adv = 10;
		$sql_adv = "SELECT * FROM " . tablename('slwl_fitment_adv'). ' WHERE 1 '
			. $condition_adv . " ORDER BY id ASC LIMIT " . ($pindex_adv - 1) * $psize_adv .',' .$psize_adv;
		$list_adv = pdo_fetchall($sql_adv, $params_adv);

		foreach ($list_adv as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'advname' => $v['advname'],
				'thumb' => $new_thumb,
				'displayorder' => $v['displayorder'],
				'enabled' => $v['enabled'],
				'page_url' => $v['page_url'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_adv', $data);
		}
	}

	// 导航按钮组
	if ($test_5 == '1') {
		$condition_buttons = ' AND uniacid=:uniacid AND setting_name=:setting_name ';
		$params_buttons = array(':uniacid' => $app_src, ':setting_name'=>'set_buttons');
		$set_buttons = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 '. $condition_buttons, $params_buttons);

		if ($set_buttons) {
			$new_value = str_ireplace($images_id_src_special, $images_id_goal_special, $set_buttons['setting_value']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'setting_name' => 'set_buttons',
				'setting_value' => $new_value,
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}
	}

	// 设计师管理
	if ($test_6 == '1') {
		$condition_designer = ' AND uniacid=:uniacid ';
		$params_designer = array(':uniacid' => $app_src);
		$pindex_designer = 1;
		$psize_designer = 5000;
		$sql_designer = "SELECT * FROM " . tablename('slwl_fitment_designer'). ' WHERE 1 '
			. $condition . " ORDER BY id ASC LIMIT " . ($pindex_designer - 1) * $psize_designer .',' .$psize_designer;
		$list_designer = pdo_fetchall($sql_designer, $params_designer);

		foreach ($list_designer as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'name' => $v['name'],
				'honour' => $v['honour'],
				'mobile' => $v['mobile'],
				'attr_1' => $v['attr_1'],
				'attr_2' => $v['attr_2'],
				'attr_3' => $v['attr_3'],
				'attr_4' => $v['attr_4'],
				'isrecommand' => $v['isrecommand'],
				'thumb' => $new_thumb,
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_designer', $data);
		}
	}

	// 底部导航
	if ($test_7 == '1') {
		$condition_menus = ' AND uniacid=:uniacid AND setting_name=:setting_name ';
		$params_menus = array(':uniacid' => $app_src, ':setting_name'=>'set_menus');
		$set_menus = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 ' . $condition_menus, $params_menus);

		if ($set_menus) {
			$new_value = str_ireplace($images_id_src_special, $images_id_goal_special, $set_menus['setting_value']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'setting_name' => 'set_menus',
				'setting_value' => $new_value,
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}
	}

	// 攻略文章
	if ($test_8 == '1') {
		$condition_news = ' AND uniacid=:uniacid ';
		$params_news = array(':uniacid' => $app_src);
		$pindex_news = 1;
		$psize_news = 5000;
		$sql_news = "SELECT * FROM " . tablename('slwl_fitment_news'). ' WHERE 1 '
			. $condition_news . " ORDER BY id ASC LIMIT " . ($pindex_news - 1) * $psize_news .',' .$psize_news;
		$list_news = pdo_fetchall($sql_news, $params_news);

		foreach ($list_news as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);
			$new_detail = str_ireplace($images_id_src, $images_id_goal, $v['detail']);
			$new_detail = str_ireplace($videos_id_src, $videos_id_goal, $new_detail);
			$new_out_thumb = str_ireplace($images_id_src, $images_id_goal, $v['out_thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'parentid' => $v['parentid'],
				'name' => $v['name'],
				'subtitle' => $v['subtitle'],
				'thumb' => $new_thumb,
				'detail' => $new_detail,
				'createtime' => $v['createtime'],
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_news', $data);
		}
	}

	// 图库-套图
	if ($test_9 == '1') {
		$condition_pic_multi = " AND uniacid=:uniacid AND desid='0' ";
		$params_pic_multi = array(':uniacid' => $app_src);
		$pindex_pic_multi = 1;
		$psize_pic_multi = 5000;
		$sql_pic_multi = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
			. $condition_pic_multi . " ORDER BY id ASC LIMIT " . ($pindex_pic_multi - 1) * $psize_pic_multi .',' .$psize_pic_multi;
		$list_pic_multi = pdo_fetchall($sql_pic_multi, $params_pic_multi);

		foreach ($list_pic_multi as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);
			$new_smeta = str_ireplace($images_id_src_special, $images_id_goal_special, $v['smeta']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'options' => $v['options'],
				'smeta' => $new_smeta,
				'search_tag' => $v['search_tag'],
				'show_num' => $v['show_num'],
				'fav_num' => $v['fav_num'],
				'desid' => $v['desid'],
				'enabled' => $v['enabled'],
				'createtime' => $v['createtime'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_multi', $data);
		}
	}

	// 图库-套图-选项(tag)
	if ($test_10 == '1') {
		$condition_pic_multi_options = " AND uniacid=:uniacid AND enabled='1' ";
		$params_pic_multi_options = array(':uniacid' => $app_src);
		$pindex_pic_multi_options = 1;
		$psize_pic_multi_options = 5000;
		$sql_pic_multi_options = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
			. $condition_pic_multi_options . " ORDER BY id ASC LIMIT "
			. ($pindex_pic_multi_options - 1) * $psize_pic_multi_options .',' .$psize_pic_multi_options;
		$list_pic_multi_options = pdo_fetchall($sql_pic_multi_options, $params_pic_multi_options);

		foreach ($list_pic_multi_options as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'parentid' => $v['parentid'],
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'case' => $v['case'],
				'isrecommand' => $v['isrecommand'],
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_multi_option', $data);
		}
	}

	// 图库-单图
	if ($test_11 == '1') {
		$condition_pic_sole = " AND uniacid=:uniacid AND desid='0' ";
		$params_pic_sole = array(':uniacid' => $app_src);
		$pindex_pic_sole = 1;
		$psize_pic_sole = 5000;
		$sql_pic_sole = "SELECT * FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 '
			. $condition_pic_sole . " ORDER BY id ASC LIMIT " . ($pindex_pic_sole - 1) * $psize_pic_sole .',' .$psize_pic_sole;
		$list_pic_sole = pdo_fetchall($sql_pic_sole, $params_pic_sole);

		foreach ($list_pic_sole as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'options' => $v['options'],
				'search_tag' => $v['search_tag'],
				'show_num' => $v['show_num'],
				'fav_num' => $v['fav_num'],
				'desid' => $v['desid'],
				'enabled' => $v['enabled'],
				'createtime' => $v['createtime'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_sole', $data);
		}
	}

	// 图库-单图-选项(tag)
	if ($test_12 == '1') {
		$condition_pic_sole_options = " AND uniacid=:uniacid AND enabled='1' ";
		$params_pic_sole_options = array(':uniacid' => $app_src);
		$pindex_pic_sole_options = 1;
		$psize_pic_sole_options = 5000;
		$sql_pic_sole_options = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
			. $condition_pic_sole_options . " ORDER BY id ASC LIMIT "
			. ($pindex_pic_sole_options - 1) * $psize_pic_sole_options .',' .$psize_pic_sole_options;
		$list_pic_sole_options = pdo_fetchall($sql_pic_sole_options, $params_pic_sole_options);

		foreach ($list_pic_sole_options as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'parentid' => $v['parentid'],
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'case' => $v['case'],
				'isrecommand' => $v['isrecommand'],
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_sole_option', $data);
		}
	}

	// setting
	if ($test_13 == '1') {
		$condition_settings = ' AND uniacid=:uniacid ';
		$params_settings = array(':uniacid' => $app_src);
		$list_set_settings = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 '
			. $condition_settings . " ORDER BY id ASC ", $params_settings);

		foreach ($list_set_settings as $k => $v) {
			$new_value = str_ireplace($images_id_src_special, $images_id_goal_special, $v['setting_value']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'setting_name' => $v['setting_name'],
				'setting_value' => $new_value,
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}
	}

	// 攻略分类
	if ($test_14 == '1') {
		$condition_term = " AND uniacid=:uniacid AND enabled='1' ";
		$params_term = array(':uniacid' => $app_src);
		$pindex_term = 1;
		$psize_term = 5000;
		$sql_term = "SELECT * FROM " . tablename('slwl_fitment_term'). ' WHERE 1 '
			. $condition_term . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_term - 1) * $psize_term .',' .$psize_term;
		$list_term = pdo_fetchall($sql_term, $params_term);

		foreach ($list_term as $k => $v) {
			$new_thumb = str_ireplace($images_id_src, $images_id_goal, $v['thumb']);

			$data = array();
			$data = array(
				'uniacid' => $app_goal,
				'displayorder' => $v['displayorder'],
				'parentid' => $tid,
				'name' => $v['name'],
				'thumb' => $new_thumb,
				'enabled' => $v['enabled'],
			);
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_term', $data);
		}
	}

	if ($test_15 == '1') {
		if ($datadel == '1') {
			pdo_delete('slwl_fitment_adact', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_adgroup', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_adsp', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_adv', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_booking', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_designer', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_fav', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_guestbook', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_news', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_pic_multi', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_pic_multi_option', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_pic_sole', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_pic_sole_option', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_reserve', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_settings', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_term', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_tipswx', array('uniacid' => $app_src));
			pdo_delete('slwl_fitment_users', array('uniacid' => $app_src));
		}
	}

	sl_ajax(0, '转移数据成功');
	exit;
}

include $this->template('web/other/transfer');

?>
