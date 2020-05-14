<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

if(!defined('IN_IA')) { exit('Access Denied'); }

global $_GPC, $_W;

if ($_W['slwl']['set']['set_plugin_lang']) {
	$lang_options = $_W['slwl']['set']['set_plugin_lang']['lang_options'];
	if ($lang_options) {
		foreach ($lang_options as $k => $v) {
			$_W['slwl']['lang'][$k] = $v;
		}
	}
} else {
	$lang_one = [

	// 后台公共元素.start
		'tips_success' => '成功',
		'tips_save_success' => '保存成功',
		'tips_error' => '失败',
		'tips_delete_error' => '删除失败',
		'tips_delete_ok' => '确定要删除吗',
		'tips_repair_auth' => '现在修复授权异常吗',
		'tips_think_again' => '再想想',
		'tips_repair' => '修复',
		'tips_repair_success' => '修复成功',
		'tips_not_exist_or_deleted' => '不存在或已删除',
		'tips_not_exist_address_info' => '地址信息不存在',
		'tips_not_exist_good_info' => '商品信息不存在',
		'tips_not_exist_resource_id' => '资源ID不存在',
		'tips_not_exist_user' => '用户不存在',
		'tips_not_exist_user_id' => '用户ID不存在',
		'tips_not_exist_ver' => '版本不存在',
		'tips_not_exist_id' => 'ID不存在',
		'tips_not_exist_operation' => '操作不存在',
		'tips_submit_error' => '保存过程发生错误，请与管理员联系',

		'pager_prev' => '上一页',
		'pager_first' => '首页',
		'pager_last' => '尾页',
		'pager_next' => '下一页',

		'order' => '排序',
		'order_drag' => '拖动排序',
		'order_placeholder' => '数字越大，排名越靠前',
		'pic' => '图片',
		'pic_other' => '其他图片',
		'status' => '状态',
		'progress' => '进度',
		'operation' => '操作',
		'visible' => '可见',

		'enabled' => '启用',
		'disabled' => '禁用',
		'show' => '显示',
		'hide' => '隐藏',
		'public' => '公开',
		'private' => '私有',

		'search' => '搜索',
		'submit' => '保存',

		'link' => '链接',
		'link_pic' => '链接图片',
		'link_pic_tips' => '图片大小宽 100% 高自适应，推荐 750 X 310 像素',
		'action' => '动作',
		'action_pic' => '动作图片',
		'action_pic_tips' => '图片大小宽度 100% 高度自适应，推荐 750 X 310 像素',
		'action_tips' => '可手动输入带 https 的链接，但小程序必需配置相关域名信息',

		'release_time' => '发布时间',
		'view_count' => '浏览数',
		'fav_count' => '收藏数',
		'setting' => '配置',
		'tel' => '电话',
		'tel_placeholder' => '联系电话',
		'name' => '名称',
		'contact_way' => '联系方式',
		'attribute' => '属性',
		'time' => '时间',
		'mark' => '备注',
		'nickname' => '昵称',
		'export' => '导出',
		'export_record' => '导出记录',
		'money' => '金额',
		'money_with_unit' => '金额(元)',
		'avatar' => '头像',
		'gender' => '性别',
		'gender_unknown' => '未知',
		'gender_boy' => '男',
		'gender_girl' => '女',

		'count' => '数量',
		'yuan' => '元',
		'square_cn' => '平方',
		'square' => '㎡',

		'save' => '保存',
		'add' => '添加',
		'edit' => '编辑',
		'delete' => '删除',
		'all_delete' => '全部删除',

		'set_cover' => '设为封面',
		'move_top' => '置顶',
		'move_up' => '上移',
		'move_down' => '下移',
		'move_bottom' => '置底',
		'replace_pic' => '换图',
		'replace' => '替换',

		'title' => '标题',
		'subtitle' => '简介',
		'subtitle_placeholder' => '简介',

		'address' => '地址',
		'address_placeholder' => '如：珠海市香洲区凤凰北路1008号',
		'recommand' => '首页推荐',

		'more' => '更多',
		'more_placeholder' => '更多 显示标题',

		'tips' => '提示',
		'wxapp_tips' => '小程序暂时不支持复杂的格式文本，如果发现前台显示空白，请把内容复制到文本文档里中转一下。',

		'coordinate' => '地理位置',
		'coordinate_lng' => '地理经度',
		'coordinate_lat' => '地理纬度',
		'coordinate_select' => '选择坐标',

		'region_select' => '选择地区',
		'region_province' => '地区-省份',
		'region_city' => '地区-城市',
		'region_district' => '地区-区',

		'term' => '分类',
		'act' => '文章',
		'content' => '内容',
		'select_placeholder' => '请选择...',

		'tag' => '标签',
		'tag_parent' => '父标签',
		'tag_child' => '子标签',
		'tag_child_add' => '添加子标签',
		'qrcode' => '二维码',
		'qrcode_show' => '显示二维码',

		'refresh' => '刷新',
		'process' => '处理',
		'processed' => '已处理',
		'unprocessed' => '未处理',
	// 后台公共元素.end

	// 后台主页.start
		'sys_info' => '系统信息',
		'php_ver' => 'PHP版本',
		'mysql_ver' => 'MYSQL版本',
		'app_ver' => '程序版本',
		'app_id' => '程序ID',
		'APPID' => 'APPID',
		'website_url' => '网站URL',
		'page_url' => '页面路径',
	// 后台主页.end

	// 菜单top.start
		'mt_default' => '首页',
		'mt_calc' => '计算器配置',
		'mt_pic_sole' => '单图',
		'mt_pic_multi' => '套图',
		'mt_designer' => '设计师管理',
		'mt_guestbook' => '留言管理',
		'mt_site' => '工地列表',
		'mt_product' => '产品管理',
		'mt_good' => '商品管理',
		'mt_upwxapp' => '上传小程序',
		'mt_sys_bak' => '返回系统',
	// 菜单top.end

	// 菜单left.start
		'ml_sys_manage' => '系统管理',
		'ml_set' => '基本设置',
		'ml_default' => '首页配置',
		'ml_color' => '颜色配置',
		'ml_buttons' => '按钮组配置',
		'ml_menus' => '底部导航',
		'ml_ucenter' => '个人中心配置',
		'ml_copyright' => '版权设置',

		'ml_plugin' => '组件管理',
		'ml_mod_wxapp' => '跳转小程序',
		'ml_adv' => '轮播图',
		'ml_adsp' => '首页广告',
		'ml_adgroup' => '组合广告',
		'ml_calc' => '计算器配置',
		'ml_adact' => '单页面',
		'ml_adpop' => '广告弹窗',
		'ml_suspend' => '悬浮按钮',
		'ml_product' => '产品管理',
		'ml_panorama' => '全景管理',
		'ml_plugin_lang' => '多语言管理',

		'ml_site' => '工地管理',
		'ml_site_manage' => '小区管理',
		'ml_site_list' => '工地列表',
		'ml_site_progress' => '工地进度',
		'ml_site_type' => '工地房型',
		'ml_site_style' => '装修风格',
		'ml_site_mode' => '装修方式',

		'ml_content' => '内容管理',
		'ml_wangneng' => '万能表单',
		'ml_form' => '万能表单信息',
		'ml_pic_sole' => '图库-单图',
		'ml_pic_multi' => '图库-套图',
		'ml_fitment_manual_term' => '装修攻略-分类',
		'ml_fitment_manual_act' => '装修攻略-文章',
		'ml_act_term' => '普通文章-分类',
		'ml_act_act' => '普通文章-文章',
		'ml_designer' => '设计师管理',
		'ml_booking' => '询价管理',
		'ml_guestbook' => '留言管理',
		'ml_reserve' => '在线预约',
		'ml_style' => '免费设计',

		'ml_client' => '客户管理',
		'ml_user_list' => '客户统计',

		'ml_store' => '商城管理',
		'ml_store_default' => '首页配置',
		'ml_store_buttons' => '首页按钮组',
		'ml_store_adsp' => '首页广告',
		'ml_store_adgroup' => '组合广告',
		'ml_store_adv' => '轮播图',
		'ml_store_category' => '商品分类',
		'ml_store_goods' => '商品管理',
		'ml_store_order' => '订单管理',
		'ml_store_sale' => '优惠券管理',
		'ml_store_printer' => '打印机配置',

		'ml_msg' => '消息提醒管理',
		'ml_msg_admin' => '管理员提醒',
		'ml_msg_user' => '用户提醒',

		'ml_other' => '其他设置',
		'ml_upwxapp' => '上传小程序',
		'ml_sdata' => '数据初始化',
		'ml_auth' => '系统授权',
		'ml_upgrade' => '系统同步',
	// 菜单left.end

	// 系统管理.start
		// 基本设置.start
			// tab_1.start
				'set_title_tab_1' => '基本设置',
				'set_name' => '小程序名称',
				'set_logo' => 'logo',
				'set_logo_tips' => '图片大小推荐 200 X 200 像素',
				// 'set_my_top_background_pic' => '我的-顶部背景图',
				// 'set_my_top_background_pic_tips' => '图片大小推荐 750 X 100 像素',
				'set_tel' => '联系电话',
				'set_tel_placeholder' => '联系电话',
				'set_address' => '联系地址',
				'set_address_placeholder' => '联系地址',
				'set_map_key' => '地图密钥',
				'set_map_key_placeholder' => '如：XX1XX-XX2XX-XX3XX-XX4XX-XX5XX-XX6XX',
				'set_map_application_tips_1' => '在线申请',
				'set_map_application_tips_2' => '用于计算器页面自动定位城市',
				'set_map_application_tips_3' => '1、申请KEY，选“移动端”，不需要其他设置，只要key。  复制KEY到 装修小程序后台填写。',
				'set_map_application_tips_4' => '2、上传新小程序。 （审核通过后才有效果，没发布前是没效果的。）',
				'set_map_application_tips_5' => '3、微信小程序官方后台， request域名再新增1个域名， https://apis.map.qq.com。',
			// tab_1.end

			// tab_2.start
				'set_title_tab_2' => '其他配置',
				'set_consult_button_status' => '咨询按钮状态',
				'set_consult_button_icon' => '咨询按钮图标',
				'set_consult_button_icon_tips' => '图片大小推荐 200 X 200 像素',
				'set_consult_button_icon_default' => '默认咨询图标',
				'set_userinfo_mobile_edit_enabled' => '手机号自定义',
				'set_userinfo_mobile_edit_enabled_tips' => '修改个人信息手机号是否可以编辑',
				'set_act_bottom_buttons_stauts' => '文章底部按钮-状态',
				'set_act_bottom_buttons_stauts_tips' => '默认显示按钮链接到报价页面',
				'set_act_bottom_buttons_calc_stauts' => '报价计算按钮-状态',
				'set_act_bottom_buttons_calc_stauts_tips' => '文章底部按钮-报价计算按钮-状态',
			// tab_2.end
		// 基本设置.end

		// 首页配置.start
			'default_title' => '首页配置',
			'default_adv' => '轮播图',
			'default_adv_show' => '启用',
			'default_adv_hide' => '禁用',
			'default_adv_height' => '轮播图-高度',
			'default_adv_height_placeholder' => '轮播图-高度一般为310左右',
			'default_adgroup' => '组合广告',
			'default_adsp' => '首页广告',
			'default_designe_display' => '设计师展示',
			'default_designe_display_title' => '设计师展示标题',
			'default_designe_display_title_placeholder' => '设计师展示标题',
			'default_fitment_effect_pic' => '装修效果图',
			'default_fitment_effect_pic_title' => '装修效果图标题',
			'default_fitment_effect_pic_title_placeholder' => '装修效果图标题',
			'default_fitment_manual' => '装修攻略',
			'default_fitment_manual_title' => '装修攻略标题',
			'default_fitment_manual_title_placeholder' => '装修攻略标题',
			'default_act_style_1' => '普通文章样式一',
			'default_act_style_2' => '普通文章样式二',
			'default_act_style_2_title' => '普通文章样式二-标题',
			'default_act_style_2_title_placeholder' => '普通文章样式二-标题',
			'default_act_list_style' => '普通文章二样式',
			'default_act_list_style_1' => '样式一(默认)',
			'default_act_list_style_2' => '样式二',
			'default_act_list_style_3' => '样式三',
			'default_act_list_style_4' => '样式四',
			'default_act_list_style_5' => '样式五',
			'default_act_list_style_6' => '样式六',
			'default_site' => '工地模块',
		// 首页配置.end

		// 颜色配置.start
			'color_title' => '颜色配置',
			'color_top_backgound' => '顶部背景颜色',
			'color_main' => '主体颜色值',
			'color_sub' => '副颜色值',
			'color_button_1' => '按钮1颜色值',
			'color_button_1_tips' => '页面上从左到右，第一个',
			'color_button_2' => '按钮2颜色值',
			'color_button_2_tips' => '页面上从左到右，第二个',
			'color_bottom_backgound' => '底部背景颜色',
			'color_bottom_backgound_font' => '底部字体颜色',
			'color_bottom_backgound_highlight' => '底部高亮颜色',
		// 颜色配置.end

		// 按钮组配置.start
			'buttons_title' => '导航按钮组',
			'buttons_status' => '状态',
			'buttons_number_of_row' => '每行个数',
			'buttons_number_of_row_placeholder' => '每一行排多少个按钮',
			'buttons_number_of_row_tips' => '设置导航条，每排多少个，最少 3 个，最多 5 个，其他值系统设置为 4 个',
			'buttons_max_nav_button_tips' => '最多支持 10 个',
		// 按钮组配置.end

		// 底部导航.start
			'menus_li_title' => '底部菜单配置',
			'menus_status' => '状态',
			'menus_max_bottom_menu_tips' => '最多支持 5 个',
			'menus_btn_default' => '首页',
			'menus_btn_pic' => '图库',
			'menus_btn_my' => '我的',
			'menus_btn_tel' => '拨号',
			'menus_btn_location' => '导航',
			'menus_btn_guestbook' => '留言',
			'menus_btn_reserve' => '预约',
			'menus_btn_site' => '工地',
			'menus_btn_store' => '商城',
			'menus_btn_product' => '产品',
			'menus_btn_panorama' => '全景',
			'menus_btn_custom_1' => '自定义1',
			'menus_btn_custom_2' => '自定义2',
		// 底部导航.end

		// 个人中心配置.start
			'ucenter_title' => '个人中心配置',
			'ucenter_collect_store' => '商品收藏',
			'ucenter_collect_pic' => '图片收藏',
			'ucenter_collect_site' => '工地收藏',
			'ucenter_collect_product' => '产品收藏',
			'ucenter_collect_panorama' => '全景收藏',
			'ucenter_order_my' => '我的订单',
			'ucenter_address_my' => '我的地址',
			'ucenter_sale_my' => '我的优惠券',
			'ucenter_reserve_my' => '我的预约',
			'ucenter_client_service_center' => '联系客服',
		// 个人中心配置.end

		// 版权设置.start
			// tab_1.start
				'copyright_title_tab_1' => '后台设置',
				'copyright_admin_copyright' => '版权信息-后台',
				'copyright_admin_copyright_placeholder' => '版权信息为空显示默认信息',
			// tab_1.end

			// tab_2.start
				'copyright_title_tab_2' => '小程序端设置',
				'copyright_wxapp_staus' => '状态',
				'copyright_wxapp_logo' => 'logo',
				'copyright_wxapp_logo_tips' => '图片大小推荐 200 X 200 像素',
				'copyright_wxapp_copyright_row_1' => '版权行一',
				'copyright_wxapp_copyright_row_1_placeholder' => '版权信息',
				'copyright_wxapp_copyright_row_2' => '版权行二',
				'copyright_wxapp_copyright_row_2_placeholder' => '版权信息',
				'copyright_wxapp_copyright_row_2_tips' => '版权里的第二行，有内容才显示',
				'copyright_wxapp_tel' => '联系电话',
				'copyright_wxapp_tel_placeholder' => '联系电话',
				'copyright_wxapp_tel_tips' => '用于点击版权拨打电话',
			// tab_2.end
		// 版权设置.end
	// 系统管理.end

	// 组件管理.start
		// 跳转小程序.start
			'mod_wxapp_title_tab' => '跳转小程序',
			'mod_wxapp_APPID' => 'APPID',
			'mod_wxapp_page' => '页面',
			// post
			'mod_wxapp_order_placeholder' => '数字越大，排名越靠前',
			'mod_wxapp_title_tips' => '最多只能绑定启用的十个小程序',
			'mod_wxapp_APPID_tips' => '请确保小程序与公众号已关联，填写小程序的APPID',
			'mod_wxapp_page_placeholder' => '跳转页面的小程序访问路径，如：pages/index/index',
		// 跳转小程序.end

		// 轮播图.start
			'adv_title_tab' => '轮播图',
			// post
			'adv_title_tips' => '为保证访问速度，最多只显示最近上传5张图片',
			'adv_pic_tips' => '图片大小推荐 750 * 310 像素',
		// 轮播图.end

		// 首页广告.start
			'adsp_title_tab' => '首页广告',
			// post
			'adsp_pic_tips' => '图片大小为宽 750 像素，高自定义',
		// 首页广告.end

		// 组合广告.start
			'adgroup_title_tab' => '组合广告',
			'adgroup_tips' => '只支持三图片',
		// 组合广告.end

		// 计算器配置.start
			'calc_title_tab' => '计算器配置',
			'calc_ad_pic' => '广告图片',
			'calc_ad_pic_tips' => '图片大小为宽 750 像素，高自定义',
			'calc_min_price' => '基础价格',
			'calc_square_price' => '单价',
			'calc_stuff_cost_title' => '材料费-标题',
			'calc_stuff_cost' => '材料费',
			'calc_stuff_cost_tips' => '不需要符号，如 40% ，直接填 40',
			'calc_labour_cost_title' => '人工费-标题',
			'calc_labour_cost' => '人工费',
			'calc_labour_cost_tips' => '不需要符号，如 60% ，直接填 60',
			'calc_style_cost_title' => '设计费-标题',
			'calc_style_cost' => '设计费',
			'calc_qt_cost_title' => '质检费-标题',
//			'calc_min_price' => '质检费',
			'calc_unit' => '单位',
			'calc_unit_plactholder' => '小程序端显示单位',

			'calc_market_price' => '现价',
			'calc_original_price' => '原价',

			'calc_info' => '说明',
			'calc_info_1' => '*报价为毛坯房装修预估价，暂不支持小数点，单位为元',
			'calc_info_2' => '*计算公式 总价= 基础价格 + (面积 * 单价) + 设计费现价 + 质检费现价',
			'calc_info_3' => '*人工费、材料费请填百分比，按100%分割（必需为100%），比如人工费40%，材料费60%',
			'calc_info_4' => '*举例：',
			'calc_info_5' => '*假如总价10W，人工费40%，材料费60%，前台会显示人工费4W，材料费6W',
			'calc_info_6' => '*举例2：',
			'calc_info_7' => '*假如总价10W，人工费40%，材料费60%，设计费1000，质检费2000。',
			'calc_info_8' => '*那么前台会显示：总价10W - 设计费1000 - 质检费2000 = N，再按40%、60%分给人工费、材料费。',
		// 计算器配置.end

		// 单页面.start
			'adact_title_tab' => '单页面',
		// 单页面.end

		// 广告弹窗.start
			'adpop_title_tab' => '广告弹窗',
			'adpop_ad_pic_tips' => '图片大小推荐 500 X 600 像素',
			'adpop_often' => '弹窗频率',
			'adpop_often_tips' => '每天弹窗频率，推荐值10',
		// 广告弹窗.end

		// 悬浮按钮.start
			'suspend_title_tab' => '悬浮按钮',
			'suspend_pic_tips' => '图片大小推荐 150 X 170 像素',
		// 悬浮按钮.end

		// 组件管理.产品管理.start
			'product_title_tab' => '产品管理',
			'product_subtitle' => '副标题',
			// 配置
			'product_list_style' => '列表样式',
			'product_list_style_1' => '样式一',
			'product_list_style_2' => '样式二',
			'product_list_style_3' => '样式三',
			'product_list_style_4' => '样式四',
			'product_list_style_5' => '样式五',
			'product_list_style_6' => '样式六',
			'product_list_style_7' => '样式七(默认)',
			'product_list_style_8' => '样式八',
		// 组件管理.产品管理.end

		// 组件管理.全景管理.start
			'panorama_title_tab' => '全景管理',
			'panorama_pic_tips' => '图片大小推荐 750 * 562 像素',
			'panorama_pic_front' => '前',
			'panorama_pic_right' => '右',
			'panorama_pic_back' => '后',
			'panorama_pic_left' => '左',
			'panorama_pic_top' => '上',
			'panorama_pic_bottom' => '下',
			// 配置
			'panorama_is_pic_display' => '是否在图库显示',
			'panorama_list_style' => '列表样式',
			'panorama_list_style_1' => '样式一',
			'panorama_list_style_2' => '样式二',
			'panorama_list_style_3' => '样式三',
			'panorama_list_style_4' => '样式四',
			'panorama_list_style_5' => '样式五',
			'panorama_list_style_6' => '样式六',
			'panorama_list_style_7' => '样式七(默认)',
			'panorama_list_style_8' => '样式八',
		// 组件管理.全景管理.end
	// 组件管理.end

	// 工地管理.start
		// 小区管理.start
			'site_manage_title_tab' => '小区管理',
			'site_manage_name' => '小区名称',
			'site_manage_name_placeholder' => '如：凤凰花园',
			'site_manage_region' => '地区',
			'site_manage_region_placeholder' => '请选择地区',
		// 小区管理.end

		// 工地列表.start
			'site_list_title_tab' => '工地列表',
			'site_list_sitedy' => '动态',
			'site_list_community' => '小区',
			'site_list_name' => '工地名称',
			'site_list_name_placeholder' => '给工地起个响亮的名字吸引更多人围观(30字符之内)',
			'site_list_project' => '项目编号',
			'site_list_project_placeholder' => '可输入合同编号或客户手机号(20字符之内)',
			'site_list_their_community' => '所属小区',
			'site_list_pic_tips' => '图片推荐 750 X 300 像素，宽度 100% 高度自适应',
			'site_list_budget' => '装修预算',
			'site_list_house_type' => '户型',
			'site_list_site_type_1' => '室',
			'site_list_site_type_2' => '厅',
			'site_list_site_type_3' => '厨',
			'site_list_site_type_4' => '卫',
			'site_list_area' => '面积',
			'site_list_area_placeholder' => '如：100',
			'site_list_area_unit' => '㎡',
			'site_list_site_progress' => '工地进度',
			'site_list_site_type' => '工地房型',
			'site_list_site_style' => '装修风格',
			'site_list_site_mode' => '装修方式',
		// 工地列表.end

		// 工地进度.start
			'site_progress_title_tab' => '工地进度',
			'site_progress_title_placeholder' => '如：开工大吉',
		// 工地进度.end

		// 工地房型.start
			'site_type_title_tab' => '工地房型',
			'site_type_title_placeholder' => '如：别墅',
		// 工地房型.end

		// 装修风格.start
			'site_style_title_tab' => '装修风格',
			'site_style_title_placeholder' => '如：现代',
		// 装修风格.end

		// 装修方式.start
			'site_mode_title_tab' => '装修方式',
			'site_mode_title_placeholder' => '如：全包',
		// 装修方式.end
	// 工地管理.end

	// 内容管理.start
		// 内容管理.单图管理.start
			'pic_sole_title_tab' => '单图管理',
			'pic_sole_pic_placeholder' => '图片宽度不小于 375 像素',
			'pic_sole_tag_title_placeholder' => '小程序端一级标题只能显示 4 个',
			'pic_sole_tag_suite' => '套数',
			'pic_sole_tag_suite_placeholder' => '用于首页推荐时显示',
			'pic_sole_tag_pic_tips' => '图片大小推荐 310 X 380 像素',
		// 内容管理.单图管理.end

		// 内容管理.套图管理.start
			'pic_multi_title_tab' => '套图管理',
			'pic_multi_pic_edit' => '编辑图片',
			'pic_multi_pic_placeholder' => '图片宽度不小于 375 像素',
			'pic_multi_tag_title_placeholder' => '小程序端一级标题只能显示 4 个',
			'pic_multi_tag_suite' => '套数',
			'pic_multi_tag_suite_placeholder' => '用于首页推荐时显示',
			'pic_multi_tag_pic_tips' => '图片大小推荐 310 X 380 像素',
		// 内容管理.套图管理.end

		// 内容管理.装修攻略.start
			'fitment_manual_term_title_tab' => '攻略分类',
			'fitment_manual_term_pic_placeholder' => '图片大小推荐 200 X 200 像素',

			'fitment_manual_act_title_tab' => '攻略文章',
			'fitment_manual_act_pic_placeholder' => '图片大小推荐 160 X 130 像素',
		// 内容管理.装修攻略.end

		// 内容管理.普通文章.start
			'act_term_title_tab' => '普通文章分类',
			'act_term_style_display' => '显示样式',
			'act_term_style' => '样式',
			'act_term_style_1' => '样式一(默认)',
			'act_term_style_2' => '样式二',
			'act_term_style_3' => '样式三',
			'act_term_style_4' => '样式四',
			'act_term_style_5' => '样式五',
			'act_term_style_6' => '样式六',
			'act_term_title_placeholder' => '为保证访问速度，最多只显示最近上传5张图片',
			'act_term_pic_tips' => '图片大小推荐 750 X 310 像素',

			'act_act_title_tab' => '普通文章',
			'act_act_pic_placeholder' => '图片大小推荐 160 X 130 像素',
		// 内容管理.普通文章.end

		// 内容管理.设计师管理.start
			'designer_title_tab' => '设计师',
			'designer_info' => '信息',
			'designer_custom_attr' => '自定义属性',
			'designer_sole_manage' => '单图作品管理',
			'designer_multi_manage' => '多图作品管理',
			'designer_edit_pic_class' => '编辑-图组',
			'designer_search_title_tel_honour' => '姓名、头衔、电话',
			'designer_recommand' => '推荐',
			'designer_recommand_not' => '未推荐',
			'designer_sole_pic' => '单图',
			'designer_multi_pic' => '多图',

			'designer_name' => '姓名',
			'designer_honour' => '头衔',
			'designer_pic_tips' => '图片大小推荐 200 X 200 像素',
			'designer_tel_status' => '电话显示-状态',
			'designer_custom_attr_1' => '自定义属性1',
			'designer_custom_attr_1_phaceholder' => '如：擅长风格：美式、中式、北欧',
			'designer_custom_attr_2' => '自定义属性2',
			'designer_custom_attr_2_phaceholder' => '如：5年以上 300套作品',
			'designer_custom_attr_3' => '自定义属性3',
			'designer_custom_attr_3_phaceholder' => '设计师详情页简介',
			'designer_avatar_tips' => '图片大小推荐 200 X 200 像素',

			'designer_set_detail_background' => '详情页背景颜色',
			'designer_set_top_pic' => '列表顶部图片',
			'designer_set_top_pic_tips' => '图片大小宽 750 像素，高自定义',
		// 内容管理.设计师管理.end

		// 内容管理.询价管理.start
			'booking_title_tab' => '询价管理',
			'booking_tips_content' => '导出操作比较耗服务器性能，尽量在非使用高峰期操作',
			'booking_time_quantum' => '时间段',
			'booking_search_field' => '昵称、联系方式',
		// 内容管理.询价管理.end

		// 内容管理.留言管理.start
			'guestbook_title_tab' => '留言管理',
			'guestbook_search_field' => '名称、联系方式',
			'guestbook_top_pic' => '顶部图片',
			'guestbook_top_pic_tips' => '图片大小宽 100% 高自适应，推荐 750 X 310 像素',
		// 内容管理.留言管理.end

		// 内容管理.在线预约.start
			'reserve_title_tab' => '在线预约',
			'reserve_search_field' => '名称、电话',
			'reserve_set_status' => '显示-状态',
			'reserve_set_status_tips' => '我的页面-是否显示',
			'reserve_set_pic' => '顶部图片',
			'reserve_set_pic_tips' => '图片大小宽 100% 高自适应，推荐 750 X 310 像素',
			'reserve_reserve_money' => '预约金额',
			'reserve_reserve_money_tips' => '0 为免费',
		// 内容管理.在线预约.end

		// 内容管理.免费设计.start
			'style_title_tab' => '免费设计',
			'style_search_field' => '名称、联系方式',
			'style_top_pic' => '顶部图片',
			'style_top_pic_tips' => '图片大小宽 100% 高自适应，推荐 750 X 310 像素',
		// 内容管理.免费设计.end
	// 内容管理.end

	// 客户管理.start
		// 客户管理.客户统计.start
			'user_list_title_tab' => '客户统计',
			'user_list_create_time' => '添加时间',
		// 客户管理.客户统计.end
	// 客户管理.end

	// 商城管理.start
		// 商城.首页配置.start
			'store_default_title_tab' => '首页配置',
			'store_default_adv' => '轮播图-状态',
			'store_default_adv_height' => '轮播图-高度',
			'store_default_adv_height_tips' => '轮播图-高度一般为310左右',
			'store_default_adgroup' => '组合广告-状态',
			'store_default_adsp' => '首页广告-状态',
			'store_default_newgood' => '新品首发-状态',
			'store_default_newgood_title' => '新品首发-标题',
			'store_default_hotgood' => '人气推荐-状态',
			'store_default_hotgood_title' => '人气推荐-标题',
		// 商城.首页配置.end

		// 商城.首页按钮组.start
			'store_buttons_title_tab' => '首页按钮组',
			'store_buttons_number_of_row' => '每行个数',
			'store_buttons_number_of_row_placeholder' => '每一行排多少个按钮',
			'store_buttons_number_of_row_tips' => '设置导航条，每排多少个，最少 3 个，最多 5 个，其他值系统设置为 4 个',
			'store_buttons_max_nav_button_tips' => '最多支持 10 个',
		// 商城.首页按钮组.end

		// 商城.首页广告.start
			'store_adsp_title_tab' => '首页广告',
		// 商城.首页广告.end

		// 商城.组合广告.start
			'store_adgroup_title_tab' => '组合广告',
			'store_adgroup_tips' => '只支持三图片',
		// 商城.组合广告.end

		// 商城.轮播图.start
			'store_adv_title_tab' => '轮播图',
			// post
			'store_adv_title_tips' => '为保证访问速度，最多只显示最近上传5张图片',
			'store_adv_pic_tips' => '图片大小推荐 750 * 375 像素',
		// 商城.轮播图.end

		// 商城.商品分类.start
			'store_category_title_tab' => '商品分类',
			// post
			'store_category_parent' => '上级分类',
			'store_category_name' => '分类名称',
			'store_category_pic' => '分类图片',
			'store_category_pic_tips' => '图片大小推荐 200 X 200 像素',
			'store_category_pic_ad' => '广告图片',
			'store_category_pic_ad_tips' => '图片大小推荐 540 X 180 像素',
			'store_category_ad_content' => '广告描述',
		// 商城.商品分类.end

		// 商城.商品管理.start
			'store_good_title_tab' => '商品管理',
			'store_good_search_field' => '商品标题',
			'store_good_inventory' => '库存',
			'store_good_sales' => '销量',
			'store_good_price' => '商品价格',
			'store_good_attr' => '商品属性(点击可编辑)',
			'store_good_piece' => '件',
			'store_good_button_new' => '新品',
			'store_good_button_hot' => '人气',
			'store_good_button_sale' => '上架',
			'store_good_button_unsale' => '下架',
			// post
				// tab
				'store_good_post_1' => '基本信息',
				'store_good_post_2' => '商品描述',
				'store_good_post_3' => '自定义属性',
				'store_good_post_4' => '商品规格',
			'store_good_enabled' => '是否上架',
			'store_good_enabled_show' => '上架',
			'store_good_enabled_hide' => '下架',
			'store_good_name' => '商品名称',
			'store_good_subtitle' => '副标题',
			'store_good_unit' => '商品单位',
			'store_good_unit_tips' => '如: 个/件/包',
			'store_good_new_recommend' => '新品推荐',
			'store_good_hot_recommend' => '人气推荐',
			'store_good_pic' => '商品图',
			'store_good_pic_tips' => '图片大小推荐 600 X 600 像素',
			'store_good_pic_other_tips' => '批量上传图片',
			'store_good_sn' => '商品编码',
			'store_good_code' => '商品条码',
			'store_good_price_sale' => '销售价',
			'store_good_price_original' => '原价格',
			'store_good_price_market' => '市场价',
			'store_good_inventory_tips' => '当前商品的库存数量',
			'store_good_inventory_status' => '库存状态',
			'store_good_inventory_status_1' => '拍下减库存',
			'store_good_inventory_status_2' => '付款减库存',
			'store_good_inventory_status_3' => '永不减库存',
			'store_good_sale_number' => '已出售数',

			'store_good_cattr_name' => '属性名称',
			'store_good_cattr_value' => '属性值',
			'store_good_cattr_add' => '属性值',

			'store_good_spec' => '商品规格',
			'store_good_spec_show' => '启用',
			'store_good_spec_tips' => '启用商品规格后，商品的价格 (商品的价格+规格价格) 及库存 (商品库存+规格库存)',
			'store_good_spec_add' => '添加规格',
			'store_good_spec_name' => '规格名',
			'store_good_spec_name_tips' => '(比如: 颜色)',
			'store_good_spec_add_item' => '添加规格项',
			'store_good_spec_item' => '规格项',
			'store_good_spec_item_tips' => '(比如: 红色)',
			'store_good_spec_item_refresh' => '刷新规格项目表',
			'store_good_spec_item_refresh_tips' => '(上面的规格如有任何变动都需点击"刷新规格项目表"按钮进行刷新规格项目表)',
			'store_good_spec_quick_set' => '快捷设置',
		// 商城.商品管理.end

		// 商城.订单管理.start
			'store_order_title_all' => '全部订单',
			'store_order_title_1' => '待付款',
			'store_order_title_2' => '待发货',
			'store_order_title_3' => '待收货',
			'store_order_title_4' => '已完成',
			'store_order_title_5' => '已退款',
			'store_order_title_0' => '已取消',

			'store_order_sn' => '订单号',
			'store_order_set_2' => '设已付款',
			'store_order_set_3' => '设已发货',
			'store_order_set_4' => '设已完成',
			'store_order_set_5' => '设已退款',
			'store_order_set_0' => '设已取消',


			'store_order_paid' => '已支付',
			'store_order_not_pay' => '未支付',
			'store_order_show' => '查看订单',
			'store_order_title_info' => '订单信息',
			'store_order_title_user' => '用户信息',
			'store_order_title_good' => '商品信息',
			'store_order_price' => '订单总价',
			'store_order_status' => '订单状态',
			'store_order_time' => '订单时间',
			'store_order_operation' => '更多操作',
			'store_order_mark' => '订单备注',
			'store_order_coupon' => '订单优惠券',
		// 商城.订单管理.end

		// 商城.优惠券管理.start
			'store_sale_title_tab' => '优惠券',
			'store_sale_total' => '发放总数',
			'store_sale_receive' => '领取数',
			// post
			'store_sale_enough' => '使用条件',
			'store_sale_enough_tips' => '消费满多少可用, 空或 0 不限制',
			'store_sale_use_date' => '使用期限',
			'store_sale_use_date_1' => '获得后',
			'store_sale_use_date_1_tips' => '天内有效(0 为不限时间使用)',
			'store_sale_use_date_2' => '在日期',
			'store_sale_use_date_2_placeholder' => '年月日 - 年月日',
			'store_sale_use_date_2_tips' => '内有效',
			'store_sale_discounts_way' => '优惠方式',
			'store_sale_discounts_way_1' => '立减',
			'store_sale_discounts_way_2' => '打折',
			'store_sale_discounts_way_3' => '返利',
			'store_sale_discounts_way_2_1' => '打',
			'store_sale_discounts_way_2_2' => '折',
			'store_sale_discounts_way_3_1' => '返',
			'store_sale_discounts_way_3_tips' => '带%为返消费金额的百分比: 如10% ，消费200元，返20元',
		// 商城.优惠券管理.end

		// 商城.打印机配置.start
			'store_printer_title_1' => '易联云打印机配置',
			'store_printer_1_name' => '终端号',
			'store_printer_1_key' => '密钥',
			'store_printer_1_uid' => '用户ID',
			'store_printer_1_uid_tips' => '用户id（管理中心系统集成里获取）',
			'store_printer_1_apikey' => 'apiKey',
			'store_printer_1_apikey_tips' => 'apiKey（管理中心系统集成里获取）',
			'store_printer_1_url_tips' => 'API接口地址(http://open.10ss.net:8888)',
			'store_printer_1_print_options' => '打印选项',
			'store_printer_1_print_options_tips' => '只支持付款完成打印',
		// 商城.打印机配置.end
	// 商城管理.end

	// 消息管理.start
		// 消息管理.管理员提醒.start
			'msg_admin_title_tab' => '管理员短信配置',
			'msg_admin_mp' => '短信平台',
			'msg_admin_mp_close' => '关闭',
			'msg_admin_mp_1' => '阿里大鱼',
			'msg_admin_mp_2' => '阿里云云通信',
			'msg_admin_mp_tips_1' => '15分钟内只会收到一条信息',
			'msg_admin_mp_tips_2' => '目前只支持阿里云云通信',
			'msg_admin_appkey' => '短信Appkey',
			'msg_admin_secret' => '短信secret',
			'msg_admin_sign' => '短信签名',
			'msg_admin_sign_tips' => '用于发送短信的签名，例如：微信科技',
			'msg_admin_sms_id' => '短信模板ID',
			'msg_admin_sms_id_tips' => '例如SMS_9626548，模板DEMO（报告老板，在 ${time} 有新的预约，请及时处理。） ${time}=时间，${phone}=手机号',
			'msg_admin_test_number' => '测试短信号码',
			'msg_admin_test_number_tips' => '例如 13579246810',
			'msg_admin_test_msg' => '测试短信发送',
			'msg_admin_test_msg_save_send' => '保存后测试短信',
			'msg_admin_test_msg_tips' => '你可以指定一个电话, 系统将在保存参数成功后尝试发送一条测试性的短信, 来检测短信通知是否正常工作',
		// 消息管理.管理员提醒.end

		// 消息管理.用户提醒.start
			'msg_user_title_tab_1' => '短信配置',
			'msg_user_title_tab_2' => '微信服务通知配置',
			'msg_user_mp' => '短信平台',
			'msg_user_mp_close' => '关闭',
			'msg_user_mp_1' => '阿里大鱼',
			'msg_user_mp_2' => '阿里云云通信',
			'msg_user_mp_tips_1' => '15分钟内只会收到一条信息',
			'msg_user_mp_tips_2' => '目前只支持阿里云云通信',
			'msg_user_appkey' => '短信Appkey',
			'msg_user_secret' => '短信secret',
			'msg_user_sign' => '短信签名',
			'msg_user_sign_tips' => '用于发送短信的签名，例如：微信科技',
			'msg_user_sms_id' => '短信模板ID',
			'msg_user_sms_id_tips' => '模板DEMO （【品牌名称】【品牌名称 为您送上免费详细报价服务】您家半包装修估价为 ${money} 元，具体报价以量房实测为准， 30分钟内装修管家给您回电，为您提供免费咨询，敬请留意，以免错失免费上门量房-0元设计-免费详细报价等服务）${time}=时间，${money}=估价变量',
			'msg_user_test_number' => '测试短信号码',
			'msg_user_test_number_tips' => '例如 13579246810',
			'msg_user_test_msg' => '测试短信发送',
			'msg_user_test_msg_save_send' => '保存后测试短信',
			'msg_user_test_msg_tips' => '你可以指定一个电话, 系统将在保存参数成功后尝试发送一条测试性的短信, 来检测短信通知是否正常工作',

			'msg_user_wx_msg_status' => '模板消息-状态',
			'msg_user_wx_msg_tips' => '目前只有在线预约后，通知用户',

		// 消息管理.用户提醒.end
	// 消息管理.end
	];

	foreach ($lang_one as $k => $v) {
		$_W['slwl']['lang'][$k] = $v;
	}

}

// $_W['slwl']['lang'] = $one;

?>
