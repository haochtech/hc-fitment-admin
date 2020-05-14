<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

// 新版商城-模块
if(!defined('IN_IA')) { exit('Access Denied'); }

// 测试
if (!function_exists('sys_test'))
{
	function sys_test()
	{
		global $_GPC, $_W;

		dump($_W);
		echo "<hr>";
		dump($_GPC);
	}
}

// 新版商城-首页
if (!function_exists('sys_config'))
{
	function sys_config()
	{
		global $_GPC, $_W;

		// 系统基本设置
		$settings['config'] = array();

		if ($_W['slwl']['set']['site_settings']) {
			$set_str = $_W['slwl']['set']['site_settings'];

			$set_str['thumb_url'] = tomedia($set_str['thumb']);
			$set_str['consult_img_url'] = tomedia($set_str['consult_img']);
			$set_str['ucenter_url'] = tomedia($set_str['ucenter']);
			$set_str['video_url'] = tomedia($set_str['video']);
			$set_str['logo_url'] = tomedia($set_str['logo']);
			$set_str['btn_act_bottom_status'] = isset($set_str['btn_act_bottom_status'])?$set_str['btn_act_bottom_status']:'1';
			$set_str['btn_act_bottom_calc_status'] = isset($set_str['btn_act_bottom_calc_status'])?$set_str['btn_act_bottom_calc_status']:'1';

		}
		$settings['config'] = $set_str;


		// 版权设置
		$settings['cpright'] = array();
		if ($_W['slwl']['set']['cpright_site_settings']) {
			$set_cp = $_W['slwl']['set']['cpright_site_settings'];

			$set_cp['logo_url'] = tomedia($set_cp['logo']);
		} else {
			$set_cp['cpright_show'] = '0';
		}
		$settings['cpright'] = $set_cp;


		// 首页设置
		if ($_W['slwl']['set']['default_set_settings']) {
			$set_def = $_W['slwl']['set']['default_set_settings'];

			if (isset($set_def['banner_height'])) {
				$set_def['banner_height'] = $set_def['banner_height'] . 'rpx';
			} else {
				$set_def['banner_height'] = '310rpx';
			}
			if (!isset($set_def['banner_show'])) { $set_def['banner_show'] = '1'; } // 轮播图
			if (!isset($set_def['adgroup_show'])) { $set_def['adgroup_show'] = '1'; } // 组合广告
			if (!isset($set_def['adsp_show'])) { $set_def['adsp_show'] = '1'; } // 首页广告
			if (!isset($set_def['titledf3_show'])) { $set_def['titledf3_show'] = '1'; } // 设计师展示
			if (!isset($set_def['titledf1_show'])) { $set_def['titledf1_show'] = '1'; } // 装修效果图
			if (!isset($set_def['titledf2_show'])) { $set_def['titledf2_show'] = '1'; } // 装修攻略
			if (!isset($set_def['actnews1_show'])) { $set_def['actnews1_show'] = '1'; } // 普通文章样式一
			if (!isset($set_def['actnews2_show'])) { $set_def['actnews2_show'] = '1'; } // 普通文章样式二
			if (!isset($set_def['term_style'])) { $set_def['term_style'] = '0'; } // 普通文章二样式
			if (!isset($set_def['site_show'])) { $set_def['site_show'] = '1'; } // 工地模块

			if (empty($set_def['titledf1'])) { $set_def['titledf1'] = '装修效果图'; }
			if (empty($set_def['titledf2'])) { $set_def['titledf2'] = '装修攻略'; }
			if (empty($set_def['titledf3'])) { $set_def['titledf3'] = '设计师展示'; }
			if (empty($set_def['titlemore'])) { $set_def['titlemore'] = '更多'; }
			if (empty($set_def['title_actnews2'])) { $set_def['title_actnews2'] = '新闻'; }

			$settings['defaults'] = $set_def;
		} else {
			$settings['defaults'] = array(
				'banner_height' => '310rpx',
				'titledf1' => '装修效果图',
				'titledf2' => '装修攻略',
				'titledf3' => '设计师展示',
				'titlemore' => '更多',
				'title_actnews2' => '新闻',
			);
		}


		// 颜色
		$settings['color'] = array();
		if ($_W['slwl']['set']['site_color_settings']) {
			$set_tmp = $_W['slwl']['set']['site_color_settings'];

			// if ($set_tmp['topcolor'] == '') { $set_tmp['topcolor'] = '#ffffff'; }
			$set_tmp['topcolor'] = empty($set_tmp['topcolor']) ? '#ffffff' : $set_tmp['topcolor'];
			$set_tmp['topfontcolor'] = empty($set_tmp['topfontcolor']) ? '#000000' : $set_tmp['topfontcolor'];
			$set_tmp['maincolor'] = empty($set_tmp['maincolor']) ? '#2fbd80' : $set_tmp['maincolor'];
			$set_tmp['subcolor'] = empty($set_tmp['subcolor']) ? '#0b5394' : $set_tmp['subcolor'];
			$set_tmp['btncolor'] = empty($set_tmp['btncolor']) ? '#2fbd80' : $set_tmp['btncolor'];
			$set_tmp['btn2color'] = empty($set_tmp['btn2color']) ? '#2fbd80' : $set_tmp['btn2color'];
			$set_tmp['bottomcolor'] = empty($set_tmp['bottomcolor']) ? '#ffffff': $set_tmp['bottomcolor'];
			$set_tmp['bottomfontcolor'] = empty($set_tmp['bottomfontcolor']) ? '#333333' : $set_tmp['bottomfontcolor'];
			$set_tmp['bottomfonthovercolor'] = empty($set_tmp['bottomfonthovercolor']) ? '#2fbd80' : $set_tmp['bottomfonthovercolor'];

			$settings['color'] = $set_tmp;
		}
		else {
			$settings['color'] = array(
				'topcolor'=>'#ffffff',
				'topfontcolor'=>'#000000',
				'maincolor'=>'#2fbd80',
				'subcolor'=>'#0b5394',
				'btncolor'=>'#2fbd80',
				'btn2color'=>'#2fbd80',
				'bottomcolor'=>'#ffffff',
				'bottomfontcolor'=>'#333333',
				'bottomfonthovercolor'=>'#2fbd80',
			);
		}

		// 小程序列表
		$condition_mod_wxapp = ' AND uniacid=:uniacid ';
		$params_mod_wxapp = array(':uniacid' => $_W['uniacid']);
		$pindex_mod_wxapp = max(1, intval($_GPC['page']));
		$psize_mod_wxapp = 100;
		$sql_mod_wxapp = "SELECT * FROM " . tablename('slwl_fitment_mod_wxapp'). ' WHERE 1 ' . $condition_mod_wxapp;
		$list_mod_wxapp = pdo_fetchall($sql_mod_wxapp, $params_mod_wxapp);

		$settings['wxapp'] = $list_mod_wxapp;


		// 图标
		$settings['menus'] = array();
		if ($_W['slwl']['set']['set_menus']) {
			$set_menus = $_W['slwl']['set']['set_menus'];

			if ($set_menus && isset($set_menus['items'])) {
				$settings['menus']['items'] = $set_menus['items'];
			} else {
				$settings['menus']['items'] = array();
			}
			$settings['menus']['enabled'] = $set_menus['enabled'];
		} else {
			$settings['menus']['items'] = array();
			$settings['menus']['enabled'] = 0;
		}


		// 在线预约-我的是否显示
		$settings['reserve']['reserve_show'] = 0;
		if ($_W['slwl']['set']['reserve_set_settings']) {
			$res_sets = $_W['slwl']['set']['reserve_set_settings'];

			if ($res_sets['reserve_show']) {
				$settings['reserve']['reserve_show'] = $res_sets['reserve_show'];
			}
		}


		// 弹窗
		$settings['adpop'] = array(
			'adpop_show' => '0',
		);
		if ($_W['slwl']['set']['adpop_set_settings']) {
			$res_tmp_adpop = $_W['slwl']['set']['adpop_set_settings'];

			$res_tmp_adpop['thumb_url'] = tomedia($res_tmp_adpop['thumb']);

			$settings['adpop'] = $res_tmp_adpop;
		}


		// 悬浮,按钮
		$settings['suspend'] = array(
			'suspend_show' => '0',
		);
		if ($_W['slwl']['set']['suspend_set_settings']) {
			$res_tmp_suspend = $_W['slwl']['set']['suspend_set_settings'];

			$res_tmp_suspend['thumb_url'] = tomedia($res_tmp_suspend['thumb']);

			$settings['suspend'] = $res_tmp_suspend;
		}

		// 全景-配置
		$settings['panorama'] = array(
			'status_panorama_in_pic_show'=>'1',
		);
		if ($_W['slwl']['set']['set_panorama']) {
			$res_tmp_panorama = $_W['slwl']['set']['set_panorama'];

			if (!isset($res_tmp_panorama['status_panorama_in_pic_show'])) {
				$res_tmp_panorama['status_panorama_in_pic_show'] = '1';
			}
			$settings['panorama'] = $res_tmp_panorama;
		}

		// 个人中心
		$settings['ucenter'] = array();
		if ($_W['slwl']['set']['set_ucenter']) {
			$res_sets = $_W['slwl']['set']['set_ucenter'];

			if ($res_sets) {
				$settings['ucenter'] = $res_sets;
			}
		}

		// 语言设置
		$settings['lang_name'] = 'zh_cn';
		if ($_W['slwl']['set']['set_plugin_lang']) {
			$res_lang = $_W['slwl']['set']['set_plugin_lang'];

			if ($res_lang) {
				$settings['lang_name'] = $res_lang['lang'];
			}
		}
		return result(0, 'ok', $settings);
	}
}



?>
