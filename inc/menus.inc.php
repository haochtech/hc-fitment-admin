<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

if(!defined('IN_IA')) { exit('Access Denied'); }

global $_GPC, $_W;

$mod_menu = [
	// 系统管理
	'basic'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_sys_manage'],
		'items'=>[
			'basic/set'=>['title'=>$_W['slwl']['lang']['ml_set'], 'url'=>webUrl('',['do'=>'basic/set']), 'show'=>true],
			'basic/default'=>['title'=>$_W['slwl']['lang']['ml_default'], 'url'=>webUrl('',['do'=>'basic/default']), 'show'=>true],
			'basic/color'=>['title'=>$_W['slwl']['lang']['ml_color'], 'url'=>webUrl('',['do'=>'basic/color']), 'show'=>true],
			'basic/buttons'=>['title'=>$_W['slwl']['lang']['ml_buttons'], 'url'=>webUrl('',['do'=>'basic/buttons']), 'show'=>true],
			'basic/menus'=>['title'=>$_W['slwl']['lang']['ml_menus'], 'url'=>webUrl('',['do'=>'basic/menus']), 'show'=>true],
			'basic/ucenter'=>['title'=>$_W['slwl']['lang']['ml_ucenter'], 'url'=>webUrl('',['do'=>'basic/ucenter']), 'show'=>true],
			'basic/cpright'=>['title'=>$_W['slwl']['lang']['ml_copyright'], 'url'=>webUrl('',['do'=>'basic/cpright']), 'show'=>true],
		]
	],
	// 组件管理
	'module'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_plugin'],
		'items'=>[
			'module/mod_wxapp'=>['title'=>$_W['slwl']['lang']['ml_mod_wxapp'], 'url'=>webUrl('',['do'=>'module/mod_wxapp']), 'show'=>true],
			'module/adv'=>['title'=>$_W['slwl']['lang']['ml_adv'], 'url'=>webUrl('',['do'=>'module/adv']), 'show'=>true],
			'module/adsp'=>['title'=>$_W['slwl']['lang']['ml_adsp'], 'url'=>webUrl('',['do'=>'module/adsp']), 'show'=>true],
			'module/adgroup'=>['title'=>$_W['slwl']['lang']['ml_adgroup'], 'url'=>webUrl('',['do'=>'module/adgroup']), 'show'=>true],
			'module/calc'=>['title'=>$_W['slwl']['lang']['ml_calc'], 'url'=>webUrl('',['do'=>'module/calc']), 'show'=>true],
			'module/adact'=>['title'=>$_W['slwl']['lang']['ml_adact'], 'url'=>webUrl('',['do'=>'module/adact']), 'show'=>true],
			'module/adpop'=>['title'=>$_W['slwl']['lang']['ml_adpop'], 'url'=>webUrl('',['do'=>'module/adpop']), 'show'=>true],
			'module/suspend'=>['title'=>$_W['slwl']['lang']['ml_suspend'], 'url'=>webUrl('',['do'=>'module/suspend']), 'show'=>true],
			'module/pro_list'=>['title'=>$_W['slwl']['lang']['ml_product'], 'url'=>webUrl('',['do'=>'module/pro_list']), 'show'=>true],
			'module/panorama'=>['title'=>$_W['slwl']['lang']['ml_panorama'], 'url'=>webUrl('',['do'=>'module/panorama']), 'show'=>true],
			'module/plugin_lang'=>['title'=>$_W['slwl']['lang']['ml_plugin_lang'], 'url'=>webUrl('',['do'=>'module/plugin_lang']), 'show'=>true],
		]
	],
	// 工地管理
	'site'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_site'],
		'items'=>[
			'site/manage'=>['title'=>$_W['slwl']['lang']['ml_site_manage'], 'url'=>webUrl('',['do'=>'site/manage']), 'show'=>true],
			'site/list'=>['title'=>$_W['slwl']['lang']['ml_site_list'], 'url'=>webUrl('',['do'=>'site/list']), 'show'=>true],
			'site/progress'=>['title'=>$_W['slwl']['lang']['ml_site_progress'], 'url'=>webUrl('',['do'=>'site/progress']), 'show'=>true],
			'site/type'=>['title'=>$_W['slwl']['lang']['ml_site_type'], 'url'=>webUrl('',['do'=>'site/type']), 'show'=>true],
			'site/style'=>['title'=>$_W['slwl']['lang']['ml_site_style'], 'url'=>webUrl('',['do'=>'site/style']), 'show'=>true],
			'site/mode'=>['title'=>$_W['slwl']['lang']['ml_site_mode'], 'url'=>webUrl('',['do'=>'site/mode']), 'show'=>true],
		]
	],
	// 内容管理
	'content'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_content'],
		'items'=>[
			'content/wangneng'=>['title'=>$_W['slwl']['lang']['ml_wangneng'], 'url'=>webUrl('',['do'=>'content/wangneng']), 'show'=>true],
			'content/form'=>['title'=>$_W['slwl']['lang']['ml_form'], 'url'=>webUrl('',['do'=>'content/form']), 'show'=>true],
			'content/pic_sole'=>['title'=>$_W['slwl']['lang']['ml_pic_sole'], 'url'=>webUrl('',['do'=>'content/pic_sole']), 'show'=>true],
			'content/pic_multi'=>['title'=>$_W['slwl']['lang']['ml_pic_multi'], 'url'=>webUrl('',['do'=>'content/pic_multi']), 'show'=>true],
			'content/term'=>['title'=>$_W['slwl']['lang']['ml_fitment_manual_term'], 'url'=>webUrl('',['do'=>'content/term']), 'show'=>true],
			'content/news'=>['title'=>$_W['slwl']['lang']['ml_fitment_manual_act'], 'url'=>webUrl('',['do'=>'content/news']), 'show'=>true],
			'content/act_term'=>['title'=>$_W['slwl']['lang']['ml_act_term'], 'url'=>webUrl('',['do'=>'content/act_term']), 'show'=>true],
			'content/act_news'=>['title'=>$_W['slwl']['lang']['ml_act_act'], 'url'=>webUrl('',['do'=>'content/act_news']), 'show'=>true],
			'content/designer'=>['title'=>$_W['slwl']['lang']['ml_designer'], 'url'=>webUrl('',['do'=>'content/designer']), 'show'=>true],
			'content/booking'=>['title'=>$_W['slwl']['lang']['ml_booking'], 'url'=>webUrl('',['do'=>'content/booking']), 'show'=>true],
			'content/guestbook'=>['title'=>$_W['slwl']['lang']['ml_guestbook'], 'url'=>webUrl('',['do'=>'content/guestbook']), 'show'=>true],
			'content/reserve'=>['title'=>$_W['slwl']['lang']['ml_reserve'], 'url'=>webUrl('',['do'=>'content/reserve']), 'show'=>true],
			'content/style'=>['title'=>$_W['slwl']['lang']['ml_style'], 'url'=>webUrl('',['do'=>'content/style']), 'show'=>true],
		]
	],
	// 客户管理
	'client'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_client'],
		'items'=>[
			'client/user_list'=>['title'=>$_W['slwl']['lang']['ml_user_list'], 'url'=>webUrl('',['do'=>'client/user_list']), 'show'=>true],
		]
	],
	// 商城管理
	'store'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_store'],
		'items'=>[
			'store/default'=>['title'=>$_W['slwl']['lang']['ml_store_default'], 'url'=>webUrl('',['do'=>'store/default']), 'show'=>true],
			'store/buttons'=>['title'=>$_W['slwl']['lang']['ml_store_buttons'], 'url'=>webUrl('',['do'=>'store/buttons']), 'show'=>true],
			'store/adsp'=>['title'=>$_W['slwl']['lang']['ml_store_adsp'], 'url'=>webUrl('',['do'=>'store/adsp']), 'show'=>true],
			'store/adgroup'=>['title'=>$_W['slwl']['lang']['ml_store_adgroup'], 'url'=>webUrl('',['do'=>'store/adgroup']), 'show'=>true],
			'store/adv'=>['title'=>$_W['slwl']['lang']['ml_store_adv'], 'url'=>webUrl('',['do'=>'store/adv']), 'show'=>true],
			'store/category'=>['title'=>$_W['slwl']['lang']['ml_store_category'], 'url'=>webUrl('',['do'=>'store/category']), 'show'=>true],
			'store/goods'=>['title'=>$_W['slwl']['lang']['ml_store_goods'], 'url'=>webUrl('',['do'=>'store/goods']), 'show'=>true],
			'store/order'=>['title'=>$_W['slwl']['lang']['ml_store_order'], 'url'=>webUrl('',['do'=>'store/order']), 'show'=>true],
			'store/sale'=>['title'=>$_W['slwl']['lang']['ml_store_sale'], 'url'=>webUrl('',['do'=>'store/sale']), 'show'=>true],
			'store/printer'=>['title'=>$_W['slwl']['lang']['ml_store_printer'], 'url'=>webUrl('',['do'=>'store/printer']), 'show'=>true],
		]
	],
	// 消息管理
	'msg'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_msg'],
		'items'=>[
			'msg/tipsmsg'=>['title'=>$_W['slwl']['lang']['ml_msg_admin'], 'url'=>webUrl('',['do'=>'msg/tipsmsg']), 'show'=>true],
			'msg/usermsg'=>['title'=>$_W['slwl']['lang']['ml_msg_user'], 'url'=>webUrl('',['do'=>'msg/usermsg']), 'show'=>true],
		]
	],
	// 其他设置
	'other'=>[
		'mod'=>'0',
		'title'=>$_W['slwl']['lang']['ml_other'],
		'items'=>[
			//'other/upwxapp'=>['title'=>$_W['slwl']['lang']['ml_upwxapp'], 'url'=>webUrl('',['do'=>'other/upwxapp']), 'show'=>true],
			'other/sdata'=>['title'=>$_W['slwl']['lang']['ml_sdata'], 'url'=>webUrl('',['do'=>'other/sdata']), 'show'=>true],
			'other/auth'=>['title'=>$_W['slwl']['lang']['ml_auth'], 'url'=>webUrl('',['do'=>'other/auth']), 'show'=>true],
			'other/upgrade'=>['title'=>$_W['slwl']['lang']['ml_upgrade'], 'url'=>webUrl('',['do'=>'other/upgrade']), 'show'=>true],
			'other/oplog'=>['title'=>'操作日志', 'url'=>webUrl('',['do'=>'other/oplog']), 'show'=>false],
		]
	],
	// 系统功能
	'system'=>[
		'mod'=>'0',
		'title'=>'系统',
		'items'=>[
			'system/dialoglink'=>['title'=>'选择器', 'url'=>webUrl('',['do'=>'system/dialoglink']), 'show'=>false],
		]
	],
];

if ($mod_menu) {
	foreach ($mod_menu as $key => $value) {
		if ($value['items']) {
			foreach ($value['items'] as $k => $v) {
				if (!($v['show'])) {
					unset($mod_menu[$key]['items'][$k]);
				}
			}
		}
	}
}

$first_mod = '0';
if ($_W['role'] == 'founder') {
	foreach ($mod_menu as $k => $v) {
		if (array_key_exists($_GPC['do'], $v['items'])) {
			$mod_menu[$k]['mod'] = '1';
			$first_mod = '1';
			break;
		}
	}
} else {
	unset($mod_menu['other']);

	if ($_W['slwl']['menus_auth']) {
		foreach ($mod_menu as $k => $v) {
			$tmp_menu = [];

			foreach ($v['items'] as $key => $value) {
				$tmp_for = $_W['current_module']['name'].'_menu_'.$key;
				array_push($tmp_menu, $tmp_for);

				if (!(in_array($tmp_for, $_W['slwl']['menus_auth']))) {
					unset($mod_menu[$k]['items'][$key]);
				}
			}
		}
	}

	foreach ($mod_menu as $key => $value) {
		if (array_key_exists($_GPC['do'], $value['items'])) {
			$mod_menu[$key]['mod'] = '1';
			$first_mod = '1';
		}
	}
}

// 删除没有子项的菜单
foreach ($mod_menu as $key => $value) {
	if (empty($value['items'])) {
		unset($mod_menu[$key]);
	}
}

// web页时展开第一个菜单列表项
if ($first_mod == '0') {
	if ($mod_menu) {
		foreach ($mod_menu as $k => $v) {
			$mod_menu[$k]['mod'] = '1';
			break;
		}
	}
}

$_W['menus_array']['left'] = $mod_menu;

// -----------------------------------------------------------------------------

// top菜单
$mod_menu_top = [
	// 顶部菜单
	'top'=>[
		'mod'=>'0',
		'title'=>'顶部菜单',
		'items'=>[
			'web'=>['title'=>$_W['slwl']['lang']['mt_default'], 'url'=>webUrl('web'), 'show'=>true],
			'module/calc'=>['title'=>$_W['slwl']['lang']['mt_calc'], 'url'=>webUrl('', ['do'=>'module/calc']), 'show'=>true],
			'content/pic_sole'=>['title'=>$_W['slwl']['lang']['mt_pic_sole'], 'url'=>webUrl('', ['do'=>'content/pic_sole']), 'show'=>true],
			'content/pic_multi'=>['title'=>$_W['slwl']['lang']['mt_pic_multi'], 'url'=>webUrl('', ['do'=>'content/pic_multi']), 'show'=>true],
			'content/designer'=>['title'=>$_W['slwl']['lang']['mt_designer'], 'url'=>webUrl('', ['do'=>'content/designer']), 'show'=>true],
			'content/guestbook'=>['title'=>$_W['slwl']['lang']['mt_guestbook'], 'url'=>webUrl('', ['do'=>'content/guestbook']), 'show'=>true],
			'site/list'=>['title'=>$_W['slwl']['lang']['mt_site'], 'url'=>webUrl('', ['do'=>'site/list']), 'show'=>true],
			'module/pro_list'=>['title'=>$_W['slwl']['lang']['mt_product'], 'url'=>webUrl('', ['do'=>'module/pro_list']), 'show'=>true],
			'store/goods'=>['title'=>$_W['slwl']['lang']['mt_good'], 'url'=>webUrl('', ['do'=>'store/goods']), 'show'=>true],
			//'other/upwxapp'=>['title'=>$_W['slwl']['lang']['ml_upwxapp'], 'url'=>webUrl('', ['do'=>'other/upwxapp']), 'show'=>true],
		]
	],
];

if ($_W['role'] != 'founder') {
	foreach ($mod_menu_top as $k => $v) {
		foreach ($v['items'] as $key => $value) {
			if ($key != 'web' && $key != 'upwxapp') {
				$tmp_for = $_W['current_module']['name'].'_menu_'.$key;
				if ($_W['slwl']['menus_auth']) {
					if (!(in_array($tmp_for, $_W['slwl']['menus_auth']))) {
						unset($mod_menu_top[$k]['items'][$key]);
					}
				}
			}
		}
	}
}

$_W['menus_array']['top'] = $mod_menu_top['top'];

// 工单
// $gongdang =
// 	webUrl('module/manage-system/module_detail', [
// 		'name'=>'slwl_fitment',
// 		'support'=>'',
// 		'type'=>'1',
// 		'tdsourcetag'=>'s_pctim_aiomsg'
// 	]);

?>
