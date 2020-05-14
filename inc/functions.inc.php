<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

if(!defined('IN_IA')) { exit('Access Denied'); }

/**
 * 返回完整的表名-智能添加前缀
 * @param string  $table                表名-不带前缀
 * @param boolean $table_prefix         数据表前缀，默认不包含
 * @param string  $table_prefix_custom  自定义表前缀，可选
 * @return string                       表名
 */
function sl_table_name($table, $table_prefix=false, $table_prefix_custom='')
{
	if ($table_prefix_custom) {
		if ($table_prefix) {
			return tablename($table_prefix_custom . $table);
		} else {
			return $table_prefix_custom . $table;
		}
	} else {
		if ($table_prefix) {
			return tablename(SLWL_PREFIX . $table);
		} else {
			return SLWL_PREFIX . $table;
		}
	}
}

/**
 * 返回数组中指定的一列,兼容PHP5.5以下版本
 * @param array $arr
 * @param string $column
 * @return array 一个新的数组
 */
function sl_array_column($arr, $column)
{
	$array_new = [];
	if(version_compare(PHP_VERSION, '5.5.0', '<')) {
		// 是否是数组
		$this_type = gettype($arr);
		if ($this_type != 'array') {
			return [];
		}
		// 是否包含指定的列
		$this_in_array = in_array($column, $arr);
		if (!$this_in_array) {
			return [];
		}
		foreach ($arr as $key => $value) {
			$array_new[] = $value[$column];
		}
		return $array_new;
	} else {
		$array_new = array_column($arr, $column);
		return $array_new;
	}
}

/**
 * 判断是否为json数据
 * @param  [type]  $data 数据
 * @return boolean       true=是json数据,false非json数据
 */
function is_json($data) {
	return json_decode($data) ? true : false;
}

/**
 * 判断目录是否存在，不存在创建
 * @param  string $path 目录路径
 * @return              没有返回，目录是否存在，不存在创建
 */
function checkdir($path)
{
	if (!file_exists($path)) {
		mkdir($path, 777);
	}
}

/**
 * 深蓝版ajax返回
 * @param int $code 返回操作状态码
 * @param array/string $data 返回的数据
 * @return string json  返回json数据，并exit
 */
function sl_ajax($code, $data)
{
	header('Content-Type:application/json; charset=utf-8');
	if ($code == 0) {
		$data_bak = [
			'code'=>$code,
			'data'=>$data,
		];
	} else {
		$data_bak = [
			'code'=>$code,
			'msg'=>$data,
		];
	}
	exit(json_encode($data_bak));
}

/**
 * 生成随机字符串
 *
 * @param integer $length   生成字符串长度，默认为 6 个
 * @param boolean $alphabet 是否包括字母，默认没有
 * @param boolean $capital  是否包括大写字母，默认包含
 * @param boolean $special  包含特殊字符，默认不使用
 * @return string           随机字符串
 */
function sl_mt_rand($length=6, $alphabet=false, $capital=true, $special=false) {
	$chars = '123456789';
	if ($alphabet) {
		$chars .= "abcdefghijklmnpqrstuvwxyz";

		if ($capital) {
			$chars .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
		}
		if ($special) {
			$chars .= "~!@#$%^&*(){}_=-+*/?.,`";
		}
	}
	$str = "";
	for ($i = 0; $i < $length; $i++) {
		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
}

/**
 * 获取 web 端url地址
 * @param  string  $segment    路由参数
 * @param  array   $params     附加参数
 * @param  boolean $addhost    为true时，添加域名
 * @param  boolean $mod        为true时，添加模块标识
 * @return string              url
 */
function webUrl($segment='', $params=array(), $addhost=true, $mod=true)
{
	global $_GPC, $_W;

	$segment_type = gettype($segment);
	if ($segment_type != 'string') { return '参数必需为字符串'; }

	$params_type = gettype($params);
	if ($params_type != 'array' && $params_type != 'NULL') { return '参数必需为数组'; }

	if ($mod) {
		$array_mod = array('m'=>strtolower($_W['current_module']['name']));
		$params = array_merge($array_mod, $params);
	}

	if (stripos($segment, '/') === false) {
		$controller = 'site';
		$action = 'entry';
		$do = $segment;
	} else {
		list($controller, $action, $do) = explode('/', $segment);
	}
	$str_url = './index.php?';
	if (!empty($controller)) {
		$str_url .= "c={$controller}&";
	}
	if (!empty($action)) {
		$str_url .= "a={$action}&";
	}
	if (!empty($do)) {
		$str_url .= "do={$do}&";
	}
	if (!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$str_url .= rawurldecode($queryString);
	}

	if ($addhost) {
		return $_W['siteroot'] . 'web/' . substr($str_url, 2);
	} else {
		return $str_url;
	}
}

/**
 * 获取Mobile端url地址
 *
 * @param string    路由参数
 * @param array     附加参数
 * @param boolean   为false时，会自动添加微信后缀 - &wxref=mp.weixin.qq.com#wechat_redirect
 * @param boolean   为true时，会添加域名
 * @return string   ulr
 */
function wxappUrl($segment='', $params = array(), $noredirect = true, $addhost = true)
{
	global $_GPC, $_W;

	$params['a'] = 'wxapp';
	$params['m'] = strtolower($_W['current_module']['name']);
	return murl('entry/wxapp/'.$segment, $params, $noredirect, $addhost);
}

/**
 * 获取主模块 web 端url地址
 * @param  string  $segment    路由参数
 * @param  array   $params     附加参数
 * @param  boolean $addhost    为true时，会添加域名
 * @return string              url
 */
function mainModuleWebUrl($segment='', $params = array(), $addhost = true)
{
	global $_GPC, $_W;

	$params['m'] = SLWL_MAIN_MODULE;
	return webUrl($segment, $params, $addhost);
}

/**
 * 写文件操作,这个数据是，个数组
 * @param  string $fileAddress 文件路径
 * @param  res    $data        数据，一般是数组
 * @return string              true
 */
function fileWriteJson($fileAddress, $data) {
	$fp = fopen($fileAddress, "w");
	fwrite($fp, json_encode($data));
	fclose($fp);
}

/**
 * 写日志
 * @param string $title 标题
 * @param string $data 内容
 */
function putlog($title, $data='')
{
	global $_GPC, $_W;

	$return_type = gettype($data);
	$return_str = '未知';
	if ($return_type=='string') {
		@$return_str = $data;
	} else if ($return_type=='object') {
		@$return_str = json_encode($data, JSON_UNESCAPED_UNICODE);
	} else if ($return_type=='array') {
		@$return_str = json_encode($data, JSON_UNESCAPED_UNICODE);
	} else {
		@$return_str = $data;
	}
	$oplogs_data = array(
		'uniacid' => $_W['uniacid'],
		'op_domain' => $_W['siteroot'],
		'op_user' => $_W['username'],
		'op_type' => $_W['account']['name'] . '-' . $title,
		'op_txt' => $return_str,
		'addtime' => $_W['slwl']['datetime']['now'],
	);
	@pdo_insert(sl_table_name('oplogs'), $oplogs_data);
}

/**
 * 反向代理img获取
 * @param  string $url 资源路径
 * @return resource    资源内容
 */
function get_proxy_img($url)
{
	$php_search = stripos($url, 'php');
	if ($php_search !== false) {
		@putlog('不能包含php字符，只能下载jpg、gif、png的图片', $php_search);
		return '不能包含php字符，只能下载jpg、gif、png的图片';
	}

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// 经过测试可省略
	// curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$rst_content = curl_exec($curl);
	$rst_header = curl_getinfo($curl);

	// @putlog('下载header', $rst_header);

	$tmp_header = 'image/jpeg';

	$find_jpg = stripos($url, '.jpg');
	$find_jpeg = stripos($url, '.jpeg');
	$find_png = stripos($url, '.png');
	$find_gif = stripos($url, '.gif');
	if (!empty($rst_header['content_type'])) {
		$tmp_header = $rst_header['content_type'];
	} else {
		if ($find_jpg !== false || $find_jpeg !== false) {
			$tmp_header = 'image/jpeg';
		} else if ($find_png !== false) {
			$tmp_header = 'image/png';
		} else if ($find_gif !== false) {
			$tmp_header = 'image/gif';
		} else {
			$tmp_header = 'image/jpeg';
		}
	}

	@putlog('文件类型', $tmp_header);

	curl_close($curl);
	header("Content-type: ".$tmp_header);
	echo $rst_content;
}

/** Get请求 */
function httpGet($url)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);
	$res = curl_exec($curl);
	curl_close($curl);
	return $res;
}

/** 删除目录 */
function deldir($dir)
{
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}

/** 复制目录 */
function recurse_copy($src, $dst)
{
	$dir=opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				recurse_copy($src . '/' . $file,$dst . '/' . $file);
			}
			else {
				@copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
	}
	closedir($dir);
}

/** 添加模板消息 */
function send_wx_template_add()
{
	global $_GPC, $_W;

	$condition = " AND uniacid=:uniacid AND template_type='0' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$one = pdo_fetch('SELECT * FROM ' . sl_table_name('tipswx',true) . ' WHERE 1 ' . $condition, $params);

	if (empty($one)) {
		require_once MODULE_ROOT . "/lib/Common.class.php";
		$app = Common::get_app_info($_W['uniacid']);

		require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
		$jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);

		$result = $jssdk->templates_add();

		if (property_exists($result, 'errmsg') && ($result->errmsg == 'ok')) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'template_base_id' => 'AT0891',
				'template_base_title' => '留言回复通知',
				'template_id' => $result->template_id,
			);

			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert(sl_table_name('tipswx'), $data);
			// $id = pdo_insertid();

			return 'SUCCESS';
		} else {
			return 'ERR';
		}
	} else {
		return 'NONE';
	}
}

/** 删除模板消息 */
function send_wx_template_delete()
{
	global $_GPC, $_W;

	$condition = " AND uniacid=:uniacid AND template_type='0' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$one = pdo_fetch('SELECT * FROM ' . sl_table_name('tipswx',true) . ' WHERE 1 ' . $condition, $params);

	if ($one) {
		require_once MODULE_ROOT . "/lib/Common.class.php";
		$app = Common::get_app_info($_W['uniacid']);

		require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
		$jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);

		$result = $jssdk->templates_delete($one['template_id']);

		if (property_exists($result, 'errmsg') && ($result->errmsg == 'ok')) {
			pdo_delete(sl_table_name('tipswx'), array('id' => $one['id']));

			return 'SUCCESS';
		} else {
			return 'ERR';
		}
	} else {
		return 'NONE';
	}
}

/**
 * 是否超过 15 分钟
 * @return bool true=超过，false=没超过
 */
function check_send_time()
{
	global $_GPC, $_W;

	$path =  MODULE_ROOT . '/data/cache/';
	if (!is_dir($path)) {
		load()->func('file');
		mkdirs($path);
	}

	$file = $path . 'sms_' . $_W['uniacid'] . ".json";

	$curr_data = array();
	$curr_data['ext_time'] = time();
	if (!file_exists($file)) {
		fileWriteJson($file, $curr_data);
	}

	$data = json_decode(file_get_contents($file));
	if (property_exists($data, 'ext_time')) {
		if ($data->ext_time <= (time() - (60 * 15))) {
			fileWriteJson($file, $curr_data);
			return true;
		} else {
			return false;
		}
	} else {
		fileWriteJson($file, $curr_data);
		return true;
	}
}

/** 获取用户openid */
function get_openid($uid)
{
	global $_GPC, $_W;

	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id'=>$uid);
	$one = pdo_fetch('SELECT * FROM ' . sl_table_name('user',true) . ' WHERE 1 ' . $condition, $params);

	if ($one) {
		return $one['openid'];
	} else {
		return 'NONE';
	}
}

/** 获取 消息模板 */
function get_template_id()
{
	global $_GPC, $_W;

	$condition = " AND uniacid=:uniacid AND template_type='0' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$one = pdo_fetch('SELECT * FROM ' . sl_table_name('tipswx',true) . ' WHERE 1 ' . $condition, $params);

	if ($one) {
		return $one['template_id'];
	} else {
		return 'NONE';
	}
}

/** 获取 form_id */
function get_form_id($openid)
{
	global $_GPC, $_W;

	$valid_datetime = date("Y-m-d H:i:s", strtotime("-6 day"));
	$condition = " AND uniacid=:uniacid AND openid=:openid AND addtime>=:addtime AND status='0' AND form_id <> '' AND form_id <> 'the formId is a mock one' ";
	$params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':addtime' => $valid_datetime);
	$one = pdo_fetch('SELECT * FROM ' . sl_table_name('formid',true) . ' WHERE 1 ' . $condition, $params);

	if ($one) {
		// 更新
		$data = array(
			'status' => '1',
		);
		pdo_update(sl_table_name('formid'), $data, array('id' => $one['id']));

		return $one['form_id'];
	} else {
		return 'NONE';
	}
}

/**
 * 腾讯地图GCJ02坐标（中国正常） 转 百度地图BD09坐标
 * @param double $lat 纬度
 * @param double $lng 经度
 * @return array
 */
function Convert_GCJ02_To_BD09($lat, $lng)
{
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	$x = $lng;
	$y = $lat;
	$z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
	$theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
	$lng = strval($z * cos($theta) + 0.0065);
	$lat = strval($z * sin($theta) + 0.006);
	return array('lng' => $lng, 'lat' => $lat);
}

/**
 * 百度地图BD09坐标 转 腾讯地图GCJ02坐标（中国正常）
 * @param double $lat 纬度
 * @param double $lng 经度
 * @return array
 */
function Convert_BD09_To_GCJ02($lat, $lng)
{
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
	$x = $lng - 0.0065;
	$y = $lat - 0.006;
	$z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
	$theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
	$lng = strval($z * cos($theta));
	$lat = strval($z * sin($theta));
	return array('lng' => $lng, 'lat' => $lat);
}

/** 二级分类*/
function slwl_tpl_form_field_category_2level($name, $parents, $children, $parentid, $childid)
{
	$html = '
		<script type="text/javascript">
			window._' . $name . ' = ' . json_encode($children) . ';
		</script>';

		$html .=
			'<div class="layui-input-inline">
				<select id="' . $name . '_parent" lay-filter="' . $name . '" name="' . $name . '[parentid]">
					<option value="0">请选择一级分类</option>';
					$ops = '';
					if($parents) {
						foreach ($parents as $row) {
							$html .= '<option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
						}
					}
					$html .= '
				</select>
			</div>
			<div class="layui-input-inline">
				<select id="' . $name . '_child" name="' . $name . '[childid]">
					<option value="0">请选择二级分类</option>';
					if ($parentid && $children[$parentid]) {
						foreach ($children[$parentid] as $row) {
							$html .= '<option value="' . $row['id'] . '"' . (($row['id'] == $childid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
						}
					}
					$html .= '
				</select>
			</div>
		';
	return $html;
}

// 链接(动作)选择器
function slwl_tpl_form_show_link($name, $value='', $appid='', $param='')
{
	$s = '';
	if (!defined('TPL_INIT_show_link')) {
		$s = '
		<script type="text/javascript">
			function showLink(elm) {
				myutil.myShowLink(function(href, permission, param) {
					var ipt = $(elm).parent().prev();
					var ipts = $(elm).parent().prev().prev();
					var ipts_param = $(elm).parent().prev().prev().prev();
					ipt.val(href);
					ipts.val(permission);
					ipts_param.val(param);
				});
			}
		</script>';
		define('TPL_INIT_show_link', true);
	}
	$s .= '
	<div class="input-group">
		<input type="text" class="layui-input" name="'.$appid.'" value="'.$param.'" style="display: none">
		<input type="text" class="layui-input" name="permission" style="display: none">
		<input type="text" class="layui-input" name="'.$name.'" value="'.$value.'">
		<span class="input-group-btn">
			<a href="javascript:"  class="btn btn-default" onclick="showLink(this)">选择</a>
		</span>
	</div>
	';
	return $s;
}

// 颜色选择器
function slwl_tpl_form_field_color($name, $value='')
{
	$s = '';
	if (!defined('TPL_INIT_COLOR')) {
		$s = '
		<script type="text/javascript">
			$(function(){
				$(".colorpicker").each(function(){
					var elm = this;
					util.colorpicker(elm, function(color){
						$(elm).parent().prev().prev().val(color.toHexString());
						$(elm).parent().prev().css({"background-color":color.toHexString(),"border-color":color.toHexString()});
					});
				});
				$(".colorclean").click(function(){
					$(this).parent().prev().prev().val("");
					$(this).parent().prev().css({"background-color":"#fff", "border-color":"#e7e7eb"});
				});
			});
		</script>';
		define('TPL_INIT_COLOR', true);
	}
	$s .= "
		<div class='input-group'>
			<input class='layui-input input-color' type='text' name='{$name}' placeholder='请选择颜色' value='{$value}'>
			<span class='input-group-addon span-color' style='background-color:{$value};border-color:{$value}'></span>
			<span class='input-group-btn'>
				<button class='btn btn-default colorpicker' type='button'>选择颜色<i class='fa fa-caret-down'></i></button>
				<button class='btn btn-default colorclean' type='button'><span><i class='layui-icon'></i></span></button>
			</span>
		</div>
	";
	return $s;
}

// 上传-单图-多规格专用
function slwl_tpl_form_field_image_spec($name, $value = '', $default = '', $options = array())
{
	global $_GPC, $_W;
	if (empty($default)) {
		$default = './resource/images/nopic.jpg';
	}
	$val = $default;
	if ($value) {
		$val = tomedia($value);
	}
	if ($options['global']) {
		$options['global'] = true;
	} else {
		$options['global'] = false;
	}
	if (empty($options['class_extra'])) {
		$options['class_extra'] = '';
	}
	if (isset($options['dest_dir']) && $options['dest_dir']) {
		if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
			exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
		}
	}
	$options['direct'] = true;
	$options['multiple'] = false;
	if (isset($options['thumb'])) {
		$options['thumb'] = $options['thumb'];
	}
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['image']['limit']) * 1024;
	$s = '';
	if (!defined('TPL_INIT_IMAGE_SPEC')) {
		$s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
					options = '.str_replace('"', '\'', json_encode($options)).';
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, options);
				});
			}
			function deleteImage(elm){
				$(elm).prev().attr("src", "./resource/images/nopic.jpg");
				$(elm).parent().prev().find("input").val("");
			}
		</script>';
		define('TPL_INIT_IMAGE_SPEC', true);
	}

	$tmp_options_text = $options['extras']['text'] ? $options['extras']['text'] : '';
	$tmp_options_image = $options['extras']['image'] ? $options['extras']['image'] : '';
	$tmp_onerror = "onerror=\"this.src='{$default}'; this.title='图片未找到'\"";
	$s .= "
	<span class='{$options['class_extra']}'>
		<input type='hidden' name='{$name}' value='{$value} {$tmp_options_text}' class='layui-input spec-thumb'>
		<span class='input-group-btn select'>
			<button class='btn btn-default' type='button' onclick='showImageDialog(this);'>选择</button>
		</span>
	</span>
	<span class='img-thumb-box {$options['class_extra']}'>
		<img src='{$val}' {$tmp_onerror} class='img-responsive img-thumbnail {$tmp_options_image}' />
	</span>
	";
	return $s;
}

/** 上传单图 */
function slwl_tpl_form_field_image($name, $value = '', $default = '', $options = array())
{
	global $_GPC, $_W;
	if (empty($default)) {
		$default = MODULE_URL.'template/public/image/nopic.jpg';
	}
	$val = $default;
	if ($value) {
		$val = tomedia($value);
	}
	if ($options['global']) {
		$options['global'] = true;
	} else {
		$options['global'] = false;
	}
	if (empty($options['class_extra'])) {
		$options['class_extra'] = '';
	}
	if (isset($options['dest_dir']) && $options['dest_dir']) {
		if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
			exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
		}
	}
	$options['direct'] = true;
	$options['multiple'] = false;
	if (isset($options['thumb'])) {
		$options['thumb'] = $options['thumb'];
	}
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['image']['limit']) * 1024;
	$s = '';
	if (!defined('TPL_INIT_IMAGE')) {
		$s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
					options = '.str_replace('"', '\'', json_encode($options)).';
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, options);
				});
			}
			function deleteImage(elm){
				$(elm).prev().attr("src", "./resource/images/nopic.jpg");
				$(elm).parent().prev().find("input").val("");
			}
		</script>';
		define('TPL_INIT_IMAGE', true);
	}
	$tmp_options_text = $options['extras']['text'] ? $options['extras']['text'] : '';
	$tmp_options_image = $options['extras']['image'] ? $options['extras']['image'] : '';
	$tmp_onerror = "onerror=\"this.src='{$default}'; this.title='图片未找到'\"";
	$s .= "
		<div class='input-group {$options['class_extra']}'>
			<input type='text' name='{$name}' value='{$value} {$tmp_options_text}' class='layui-input'>
			<span class='input-group-btn'>
				<button class='btn btn-default' type='button' onclick='showImageDialog(this);'>选择图片</button>
			</span>
		</div>
		<div class='input-group {$options['class_extra']}' style='margin-top:.5em;'>
			<img src='{$val}' {$tmp_onerror} class='img-responsive img-thumbnail {$tmp_options_image}' width='150' />
			<em class='close' style='position:absolute; top: 0px; right: -14px;' title='删除这张图片' onclick='deleteImage(this)'>×</em>
		</div>
	";
	return $s;
}

// 上传多图
function slwl_tpl_form_field_multi_image($name, $value = array(), $options = array())
{
	global $_GPC, $_W;
	$default = './resource/images/nopic.jpg';
	$options['multiple'] = true;
	$options['direct'] = false;
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['image']['limit']) * 1024;
	if (isset($options['dest_dir']) && $options['dest_dir']) {
		if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
			exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
		}
	}
	$s = '';
	if (!defined('TPL_INIT_MULTI_IMAGE')) {
		$s = '
		<script type="text/javascript">
			function uploadMultiImage(elm) {
				var name = $(elm).next().val();
				util.image( "", function(urls){
					$.each(urls, function(idx, url){
						$(elm).parent().parent().next().append(\'<div class="multi-item"><img onerror="this.src=\\\'./resource/images/nopic.jpg\\\'; this.title=\\\'图片未找到.\\\'" src="\'+url.url+\'" class="img-responsive img-thumbnail"><input type="hidden" name="\'+name+\'[]" value="\'+url.attachment+\'"><em class="close" title="删除这张图片" onclick="deleteMultiImage(this)">×</em></div>\');
					});
				}, ' . json_encode($options) . ');
			}
			function deleteMultiImage(elm){
				$(elm).parent().remove();
			}
		</script>';
		define('TPL_INIT_MULTI_IMAGE', true);
	}
	$s .= "
	<div class='input-group'>
		<input type='text' class='layui-input' readonly='readonly' value='' placeholder='批量上传图片' autocomplete='off'>
		<span class='input-group-btn'>
			<button class='btn btn-default' type='button' onclick='uploadMultiImage(this);'>选择图片</button>
			<input type='hidden' value='{$name}' />
		</span>
	</div>
	<div class='input-group multi-img-details'>
	";
	$tmp_onerror = "onerror=\"this.src='{$default}'; this.title='图片未找到'\"";
	if (is_array($value) && count($value) > 0) {
		foreach ($value as $row) {
			$img = tomedia($row);
			$s .= "
			<div class='multi-item'>
				<img src='{$img}' {$tmp_onerror} class='img-responsive img-thumbnail'>
				<input type='hidden' name='{$name}[]' value='{$row}'>
				<em class='close' title='删除这张图片' onclick='deleteMultiImage(this)'>×</em>
			</div>
			";
		}
	}
	$s .= '</div>';
	return $s;
}

// 上传音频
function slwl_tpl_form_field_audio($name, $value = '', $options = array())
{
	if (!is_array($options)) {
		$options = array();
	}
	$options['direct'] = true;
	$options['multiple'] = false;
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['audio']['limit']) * 1024;
	$s = '';
	if (!defined('TPL_INIT_AUDIO')) {
		$s = '
		<script type="text/javascript">
			function showAudioDialog(elm, base64options, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					util.audio(val, function(url){
						if(url && url.attachment && url.url){
							btn.prev().show();
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
							setAudioPlayer();
						}
						if(url && url.media_id){
							ipt.val(url.media_id);
						}
					}, "" , ' . json_encode($options) . ');
				});
			}

			function setAudioPlayer(){
				require(["jquery.jplayer"], function(){
					$(function(){
						$(".audio-player").each(function(){
							$(this).prev().find("button").eq(0).click(function(){
								var src = $(this).parent().prev().val();
								if($(this).find("i").hasClass("fa-stop")) {
									$(this).parent().parent().next().jPlayer("stop");
								} else {
									if(src) {
										$(this).parent().parent().next().jPlayer("setMedia", {mp3: util.tomedia(src)}).jPlayer("play");
									}
								}
							});
						});

						$(".audio-player").jPlayer({
							playing: function() {
								$(this).prev().find("i").removeClass("fa-play").addClass("fa-stop");
							},
							pause: function (event) {
								$(this).prev().find("i").removeClass("fa-stop").addClass("fa-play");
							},
							swfPath: "resource/components/jplayer",
							supplied: "mp3"
						});
						$(".audio-player-media").each(function(){
							$(this).next().find(".audio-player-play").css("display", $(this).val() == "" ? "none" : "");
						});
					});
				});
			}
			setAudioPlayer();
		</script>';
		echo $s;
		define('TPL_INIT_AUDIO', true);
	}
	$s .= '
		<div class="input-group">
			<input type="text" value="' . $value . '" name="' . $name . '" class="layui-input audio-player-media" autocomplete="off" ' . ($options['extras']['text'] ? $options['extras']['text'] : '') . '>
			<span class="input-group-btn">
				<button class="btn btn-default audio-player-play" type="button" style="display:none;"><i class="fa fa-play"></i></button>
				<button class="btn btn-default" type="button" onclick="showAudioDialog(this, \'' . base64_encode(iserializer($options)) . '\',' . str_replace('"', '\'', json_encode($options)) . ');">选择媒体文件</button>
			</span>
		</div>
		<div class="input-group audio-player"></div>';
	return $s;
}

// 上传视频
function slwl_tpl_form_field_video($name, $value = '', $options = array())
{
	if(!is_array($options)){
		$options = array();
	}
	if (!is_array($options)) {
		$options = array();
	}
	$options['direct'] = true;
	$options['multi'] = false;
	$options['type'] = 'video';
	$options['fileSizeLimit'] = intval($GLOBALS['_W']['setting']['upload']['audio']['limit']) * 1024;
	$s = '';
	if (!defined('SLWL_TPL_INIT_VIDEO')) {
		$s = '
		<script type="text/javascript">
			function showVideoDialog(elm, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					util.audio(val, function(url){
						if(url && url.attachment && url.url){
							btn.prev().show();
							ipt.val(url.attachment);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url && url.media_id){
							ipt.val(url.media_id);
						}
					}, '.json_encode($options).');
				});
			}
		</script>';
		echo $s;
		define('SLWL_TPL_INIT_VIDEO', true);
	}

	$s .= '
		<div class="input-group">
			<input type="text" value="'.$value.'" name="'.$name.'" class="layui-input"
				autocomplete="off" '.($options['extras']['text'] ? $options['extras']['text'] : '').'>
			<span class="input-group-btn">
				<button class="btn btn-default" type="button"
				onclick="showVideoDialog(this,'.str_replace('"','\'', json_encode($options)).');">选择媒体文件</button>
			</span>
		</div>';
	return $s;
}

// 选择商品
function slwl_tpl_form_show_goods()
{
	$s = '';
	if (!defined('SLWL_TPL_FORM_SHOW_GOODS')) {
		$s = '
		<script type="text/javascript">
			function showLinkGoods(elm) {
				myutil.myShowGoodSelect(function(href, permission) {
					window.location.reload();
				});
			}
		</script>';
		define('SLWL_TPL_FORM_SHOW_GOODS', true);
	}
	$s .= '
		<button class="layui-btn layui-btn-sm" onclick="showLinkGoods(this)">添加</button>
	';
	return $s;
}

// 选择地理位置-百度地图-自定义了样式
function slwl_tpl_form_field_coordinate($field, $value = array())
{
	$s = '';
	if(!defined('SLWL_TPL_INIT_COORDINATE')) {
		$s .= '
			<script src="https://api.map.baidu.com/api?v=2.0&ak=F51571495f717ff1194de02366bb8da9&s=1"></script>
			<script>
				function showCoordinate(elm) {
					require(["util"], function(util){
						var val = {};
						var obj_lng = $(elm).closest(".box-lng-lat").find(".input-lng");
						var obj_lat = $(elm).closest(".box-lng-lat").find(".input-lat");
						val.lng = parseFloat(obj_lng.val());
						val.lat = parseFloat(obj_lat.val());
						myutil.map(val, function(r){
							obj_lng.val(r.lng);
							obj_lat.val(r.lat);
						});
					});
				}
			</script>';
		define('SLWL_TPL_INIT_COORDINATE', true);
	}
	$val_lng = $value['lng'];
	$val_lat = $value['lat'];
	$s .= "
		<div class='box-lng-lat'>
			<div class='layui-input-inline'>
				<input type='text' name='{$field}[lng]' value='{$val_lng}' lay-verify='required' placeholder='地理经度' class='layui-input input-lng' />
			</div>
			<div class='layui-input-inline'>
				<input type='text' name='{$field}[lat]' value='{$val_lat}' lay-verify='required' placeholder='地理纬度' class='layui-input input-lat' />
			</div>
			<button onclick='showCoordinate(this);' class='layui-btn' type='button'>选择坐标</button>
		</div>
		";
	return $s;
}

// 选择-图标
function slwl_tpl_form_field_icon($name, $value='')
{
	if(empty($value)){
		$value = 'iconfont icon-nav-sports';
	}
	$s = '';
	if (!defined('SLWL_TPL_FORM_FIELD_ICON')) {
		$s = '
		<script type="text/javascript">
			function showLinkFont(elm) {
				myutil.myShowFontSelect(function(href, permission, param) {
					let $obj = $(elm);
					let $obj_parent = $obj.closest(".input-group");
					$obj_parent.find("input").val(href);
					$obj_parent.find(".span-font i").attr("class", href);
				});
			}
		</script>';
		define('SLWL_TPL_FORM_FIELD_ICON', true);
	}
	$s .= "
	<div class='input-group'>
		<input class='layui-input' type='text' name='{$name}' value='{$value}'>
		<span class='input-group-addon span-font'><i class='{$value}'></i></span>
		<span class='input-group-btn'>
			<button class='btn btn-default' onclick='showLinkFont(this);'>选择图标</button>
		</span>
	</div>
	";
	return $s;
}

// 选择用户
function slwl_tpl_form_show_user($name, $value=[],$default='')
{
	if (empty($default)) {
		$default = MODULE_URL.'template/public/image/nopic.jpg';
	}
	$val = $default;
	if ($value['avatar']) {
		$val = tomedia($value['avatar']);
	}
	$s = '';
	if (!defined('SLWL_TPL_FORM_SHOW_USER')) {
		$s = '
		<script type="text/javascript">
			function showLinkUser(elm) {
				myutil.myShowUserSelect(function(e) {
					console.error(e);
					let _this = $(elm);
					let _this_parent = _this.closest(".box");
					_this_parent.find(".agentid").val(e.id);
					_this_parent.find(".nickname").val(e.nickname);
					_this_parent.find(".avatar").attr("src", e.avatar);
				});
			}
			function deleteUserImage(elm){
				$(elm).prev().attr("src", "'.$val.'");
				$(elm).parent().prev().find("input").val("");
			}
		</script>';
		define('SLWL_TPL_FORM_SHOW_USER', true);
	}
	$tmp_onerror = "onerror=\"this.src='{$default}'; this.title='图片未找到'\"";
	$s .= "
		<div class='box'>
			<div class='input-group'>
				<input type='hidden' name='{$name}' value='{$value['id']}' class='agentid'>
				<input type='text' value='{$value['nickname']}' class='layui-input nickname' readonly>
				<span class='input-group-btn'>
					<button class='btn btn-default' type='button' onclick='showLinkUser(this);'>选择用户</button>
				</span>
			</div>
			<div class='input-group' style='margin-top:.5em;'>
				<img src='{$val}' {$tmp_onerror} class='img-responsive img-thumbnail avatar' width='150' />
				<em class='close' style='position:absolute; top: 0px; right: -14px;' title='删除用户' onclick='deleteUserImage(this)'>×</em>
			</div>
		</div>
	";
	return $s;
}

if (!function_exists('result'))
{
	function result($errno, $message, $data = '')
	{
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}
}

if (!function_exists('slwl_upgrade_data'))
{
	function slwl_upgrade_data()
	{
		global $_GPC, $_W;

		// 删除异常 settings
		$where_del = " uniacid = '0' AND setting_name <> 'auth_settings' ";
		@pdo_delete('slwl_aicard_store_adv', $where_del);


		// 系统基本设置-更新坐标
		$condition_setting = " AND setting_name='site_settings' AND data_ver<>'2' ";
		$params_setting = array();
		$list_setting = @pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 '
			. $condition_setting, $params_setting);

		if ($list_setting) {
			foreach ($list_setting as $k => $v) {
				$site_settings = json_decode($v['setting_name']);

				if (isset($site_settings['lng']) && isset($site_settings['lat'])) {
					$map_baidu = Convert_GCJ02_To_BD09($site_settings['lat'], $site_settings['lng']);
					$map_qq = array('lng'=>$site_settings['lng'], 'lat'=>$site_settings['lat']);
					$site_settings['coordinate'] = array(
						'baidu'=>$map_baidu,
						'qq'=>$map_qq,
					);
					unset($site_settings['lng']);
					unset($site_settings['lat']);
					// dump($site_settings);exit;
					$data = array(
						'setting_value'=>json_encode($site_settings),
						'data_ver'=>'2',
					);
					$rst_site = pdo_update('slwl_fitment_settings', $data, array('id'=>$v['id']));
					if ($rst_site === false) {
						$data_return = array('code'=>'1','msg'=>'更新更新坐标失败');
						return $data_return;
					}
				} else {
					$data_users = array(
						'data_ver'=>'2',
					);
					$rst_users = pdo_update('slwl_fitment_users', $data_users, array('id'=>$v['id']));
					if ($rst_users === false) {
						$data_return = array('code'=>'1','msg'=>'更新更新坐标失败');
						return $data_return;
					}
				}
			}
		}


		// 更新buttton,menus,adgroup，表合并到 settings
		// 菜单配置
		$condition_menus = " AND setting_name=:setting_name ";
		$params_menus = array(':setting_name' => 'menus_settings');
		$list_menus = @pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_menus') . ' WHERE 1 '
			. $condition_menus, $params_menus);
		if ($list_menus) {
			foreach ($list_menus as $k => $v) {
				$data = array();
				$data['uniacid'] = $v['uniacid'];
				$data['setting_name'] = 'set_menus';
				$data['setting_value'] = $v['setting_value'];
				$data['addtime'] = $_W['slwl']['datetime']['now'];
				$rst_insert = pdo_insert('slwl_fitment_settings', $data);
				if ($rst_insert === false) {
					$data_return = array('code'=>'1','msg'=>'menus-转移配置失败');
					return $data_return;
				}
				$rst_delete = pdo_delete('slwl_fitment_menus', array('id' => $v['id']));
				if ($rst_delete === false) {
					$data_return = array('code'=>'1','msg'=>'menus-转移配置失败');
					return $data_return;
				}
			}
		}
		// 按钮组配置
		$condition_buttons = ' AND setting_name=:setting_name ';
		$params_buttons = array(':setting_name' => 'buttons_settings');
		$list_buttons = @pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_buttons') . ' WHERE 1 '
			. $condition_buttons, $params_buttons);
		if ($list_buttons) {
			foreach ($list_buttons as $k => $v) {
				$data = array();
				$data['uniacid'] = $v['uniacid'];
				$data['setting_name'] = 'set_buttons';
				$data['setting_value'] = $v['setting_value'];
				$data['addtime'] = $_W['slwl']['datetime']['now'];
				$rst_insert = pdo_insert('slwl_fitment_settings', $data);
				if ($rst_insert === false) {
					$data_return = array('code'=>'1','msg'=>'buttons-转移配置失败');
					return $data_return;
				}
				$rst_delete = pdo_delete('slwl_fitment_buttons', array('id' => $v['id']));
				if ($rst_delete === false) {
					$data_return = array('code'=>'1','msg'=>'buttons-删除配置失败');
					return $data_return;
				}
			}
		}
		// 组合广告配置
		$condition_adgroup = ' AND setting_name=:setting_name ';
		$params_adgroup = array(':setting_name' => 'adgroup_settings');
		$list_adgroup = @pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_adgroup') . ' WHERE 1 '
			. $condition_adgroup, $params_adgroup);
		if ($list_adgroup) {
			foreach ($list_adgroup as $k => $v) {
				$data = array();
				$data['uniacid'] = $v['uniacid'];
				$data['setting_name'] = 'set_adgroup';
				$data['setting_value'] = $v['setting_value'];
				$data['addtime'] = $_W['slwl']['datetime']['now'];
				$rst_insert = pdo_insert('slwl_fitment_settings', $data);
				if ($rst_insert === false) {
					$data_return = array('code'=>'1','msg'=>'adgroup-转移配置失败');
					return $data_return;
				}
				$rst_delete = pdo_delete('slwl_fitment_adgroup', array('id' => $v['id']));
				if ($rst_delete === false) {
					$data_return = array('code'=>'1','msg'=>'adgroup-删除配置失败');
					return $data_return;
				}
			}
		}

		// 修正用户表 nickname 的显示
		// 客户管理-历史数据升级
		$condition_users = " AND data_ver<>'1' ";
		$params_users = array();
		$sql_users = "SELECT * FROM " . tablename('slwl_fitment_users'). ' WHERE 1 ' . $condition_users;
		$list_users = pdo_fetchall($sql_users, $params_users);

		if ($list_users) {
			foreach ($list_users as $k => $v) {
				$is_json = json_decode($v['nicename']);
				if (is_null($is_json)) {
					$data_users = array(
						'nicename'=>json_encode($v['nicename'], JSON_UNESCAPED_UNICODE),
						'data_ver'=>'1',
					);
					$rst_users = pdo_update('slwl_fitment_users', $data_users, array('id'=>$v['id']));
					if ($rst_users === false) {
						$data_return = array('code'=>'1','msg'=>'客户管理-更新失败');
						return $data_return;
					}
				} else {
					$data_users = array(
						'data_ver'=>'1',
					);
					$rst_users = pdo_update('slwl_fitment_users', $data_users, array('id'=>$v['id']));
					if ($rst_users === false) {
						$data_return = array('code'=>'1','msg'=>'客户管理-更新失败');
						return $data_return;
					}
				}
			}
		}

		// 单图-生成SN
		$condition_sn_sole = " AND picsn='' ";
		$params_sn_sole = array();
		$sql_sn_sole = "SELECT id FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 ' . $condition_sn_sole;
		$list_sn_sole = pdo_fetchall($sql_sn_sole, $params_sn_sole);

		if ($list_sn_sole) {
			foreach ($list_sn_sole as $k => $v) {
				$pic_sn_sole = 'SLWL' . $_W['slwl']['datetime']['nowYmdHis'] . str_pad($k, 6, '0', STR_PAD_LEFT);
				pdo_update('slwl_fitment_pic_sole', array('picsn'=>$pic_sn_sole), array('id'=>$v['id']));
			}
		}

		// 单图-生成宽高
		$condition_wh_sole = " AND (pic_width='0' OR pic_height='0') ";
		$params_wh_sole = array();
		$sql_wh_sole = "SELECT id, thumb FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 ' . $condition_wh_sole;
		$list_wh_sole = pdo_fetchall($sql_wh_sole, $params_wh_sole);

		if ($list_wh_sole) {
			foreach ($list_wh_sole as $k => $v) {
				if ($v['thumb']) {
					$pic_info_sole = @getimagesize(tomedia($v['thumb']));
				}

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

		// 套图-生成SN
		$condition_sn_multi = " AND picsn='' ";
		$params_sn_multi = array();
		$sql_sn_multi = "SELECT id FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_sn_multi;
		$list_sn_multi = pdo_fetchall($sql_sn_multi, $params_sn_multi);

		if ($list_sn_multi) {
			foreach ($list_sn_multi as $k => $v) {
				$pic_sn_multi = 'SLWL' . $_W['slwl']['datetime']['nowYmdHis'] . str_pad($k, 7, '0', STR_PAD_LEFT);
				pdo_update('slwl_fitment_pic_multi', array('picsn'=>$pic_sn_multi), array('id'=>$v['id']));
			}
		}

		// 套图-生成宽高
		$condition_wh_multi = " AND (pic_width='0' OR pic_height='0') ";
		$params_wh_multi = array();
		$sql_wh_multi = "SELECT id, thumb FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 ' . $condition_wh_multi;
		$list_wh_multi = pdo_fetchall($sql_wh_multi, $params_wh_multi);

		if ($list_wh_multi) {
			foreach ($list_wh_multi as $k => $v) {
				if ($v['thumb']) {
					$pic_info_multi = @getimagesize(tomedia($v['thumb']));
				}

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

		// 攻略-历史数据升级
		// slwl_fitment_news
		// newsname => name
		$condition_news = " AND `name`='' ";
		$params_news = array();
		$sql_news = "SELECT * FROM " . tablename('slwl_fitment_news'). ' WHERE 1 ' . $condition_news;
		$list_news = pdo_fetchall($sql_news, $params_news);

		if ($list_news) {
			foreach ($list_news as $k => $v) {
				if ((isset($v['newsname'])) && ($v['newsname'] != '')) {
					$data_news = array(
						'name'=>$v['newsname'],
					);
					pdo_update('slwl_fitment_news', $data_news, array('id'=>$v['id']));
				}
			}
		}

		// 普通文章-历史数据升级
		// slwl_fitment_act_news
		// newsname => name
		$condition_act_news = " AND `name`='' ";
		$params_act_news = array();
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

		$data_return = array(
			'code'=>'0',
			'msg'=>'ok'
		);
		return $data_return;
	}
}
