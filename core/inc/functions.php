<?php

//decode by http://www.zx-xcx.com/
if (!defined("IN_IA")) {
	exit("Access Denied");
}
if (!function_exists("dump")) {
	function dump()
	{
		$args = func_get_args();
		foreach ($args as $val) {
			echo "<pre style=\"color: red\">";
			var_dump($val);
			echo "</pre>";
		}
	}
}
if (!function_exists("webUrl")) {
	function webUrl($do = '', $query = array(), $full = true)
	{
		global $_W;
		global $_GPC;
		if (!empty($_W["plugin"])) {
			if ($_W["plugin"] == "merch") {
				if (function_exists("merchUrl")) {
					return merchUrl($do, $query, $full);
				}
			}
			if ($_W["plugin"] == "newstore") {
				if (function_exists("newstoreUrl")) {
					return newstoreUrl($do, $query, $full);
				}
			}
		}
		$dos = explode("/", trim($do));
		$routes = array();
		$routes[] = $dos[0];
		if (isset($dos[1])) {
			$routes[] = $dos[1];
		}
		if (isset($dos[2])) {
			$routes[] = $dos[2];
		}
		if (isset($dos[3])) {
			$routes[] = $dos[3];
		}
		$r = implode(".", $routes);
		if (!empty($r)) {
			$query = array_merge(array("r" => $r), $query);
		}
		$query = array_merge(array("do" => "web"), $query);
		$query = array_merge(array("m" => "slwl_fitment"), $query);
		if ($full) {
			return $_W["siteroot"] . "web/" . substr(wurl("site/entry", $query), 2);
		}
		return wurl("site/entry", $query);
	}
}
if (!function_exists("m")) {
	function m($name = '')
	{
		static $_modules = array();
		if (isset($_modules[$name])) {
			return $_modules[$name];
		}
		$model = SLWL_FITMENT_CORE . "model/" . strtolower($name) . ".php";
		if (!is_file($model)) {
			exit(" Model " . $name . " Not Found!");
		}
		require_once $model;
		$class_name = ucfirst($name) . "_Slwl_fitmentModel";
		$_modules[$name] = new $class_name();
		return $_modules[$name];
	}
}
if (!function_exists("show_message")) {
	function show_message($msg = '', $url = '', $type = '')
	{
		$site = new Page();
		$site->message($msg, $url, $type);
		exit;
	}
}
if (!function_exists("shop_template_compile")) {
	function shop_template_compile($from, $to, $inmodule = false)
	{
		$path = dirname($to);
		if (!is_dir($path)) {
			load()->func("file");
			mkdirs($path);
		}
		$content = shop_template_parse(file_get_contents($from), $inmodule);
		if (IMS_FAMILY == "x" && !preg_match("/(footer|header|account\\/welcome|login|register)+/", $from)) {
			$content = str_replace("微擎", "系统", $content);
		}
		file_put_contents($to, $content);
	}
}
if (!function_exists("shop_template_parse")) {
	function shop_template_parse($str, $inmodule = false)
	{
		global $_W;
		$str = template_parse($str, $inmodule);
		if (strexists($_W["siteurl"], "merchant.php") || strexists($_W["siteurl"], "r=merch.mmanage")) {
			if (p("merch")) {
				$str = preg_replace("/{ifp\\s+(.+?)}/", "<?php if(mcv(\$1)) { ?>", $str);
				$str = preg_replace("/{ifpp\\s+(.+?)}/", "<?php if(mcp(\$1)) { ?>", $str);
				$str = preg_replace("/{ife\\s+(\\S+)\\s+(\\S+)}/", "<?php if( mce(\$1 ,\$2) ) { ?>", $str);
				return $str;
			}
		}
		if (strexists($_W["siteurl"], "newstoreant.php")) {
			if (p("newstore")) {
				$str = preg_replace("/{ifp\\s+(.+?)}/", "<?php if(mcv(\$1)) { ?>", $str);
				$str = preg_replace("/{ifpp\\s+(.+?)}/", "<?php if(mcp(\$1)) { ?>", $str);
				$str = preg_replace("/{ife\\s+(\\S+)\\s+(\\S+)}/", "<?php if( mce(\$1 ,\$2) ) { ?>", $str);
				$str = preg_replace("/{ifs\\s+(.+?)}/", "<?php if( mcs(\$1) ) { ?>", $str);
				return $str;
			}
		}
		$str = preg_replace("/{ifp\\s+(.+?)}/", "<?php if(cv(\$1)) { ?>", $str);
		$str = preg_replace("/{ifpp\\s+(.+?)}/", "<?php if(cp(\$1)) { ?>", $str);
		$str = preg_replace("/{ife\\s+(\\S+)\\s+(\\S+)}/", "<?php if( ce(\$1 ,\$2) ) { ?>", $str);
		return $str;
	}
}
if (!function_exists("httpGet")) {
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
}
if (!function_exists("deldir")) {
	function deldir($dir)
	{
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file == "." || $file == "..") {
				continue;
			}
			$fullpath = $dir . "/" . $file;
			if (!is_dir($fullpath)) {
				unlink($fullpath);
				continue;
			}
			deldir($fullpath);
		}
		closedir($dh);
		if (rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists("recurse_copy")) {
	function recurse_copy($src, $dst)
	{
		$dir = opendir($src);
		@mkdir($dst);
		while (false !== ($file = readdir($dir))) {
			if ($file == "." || $file == "..") {
				continue;
			}
			if (is_dir($src . "/" . $file)) {
				recurse_copy($src . "/" . $file, $dst . "/" . $file);
				continue;
			}
			@copy($src . "/" . $file, $dst . "/" . $file);
		}
		closedir($dir);
	}
}
if (!function_exists("my_tpl_form_field_color")) {
	function my_tpl_form_field_color($name, $value = '')
	{
		$s = '';
		if (!defined("TPL_INIT_COLOR")) {
			$s = "\r\n            <script type=\"text/javascript\">\r\n                \$(function(){\r\n                    \$(\".colorpicker\").each(function(){\r\n                        var elm = this;\r\n                        util.colorpicker(elm, function(color){\r\n                            \$(elm).parent().prev().prev().val(color.toHexString());\r\n                            \$(elm).parent().prev().css(\"background-color\", color.toHexString());\r\n                        });\r\n                    });\r\n                    \$(\".colorclean\").click(function(){\r\n                        \$(this).parent().prev().prev().val(\"\");\r\n                        \$(this).parent().prev().css(\"background-color\", \"#FFF\");\r\n                    });\r\n                });\r\n            </script>";
			define("TPL_INIT_COLOR", true);
		}
		$s .= "\r\n            <div class=\"input-group\">\r\n                <input class=\"form-control\" type=\"text\" name=\"" . $name . "\" placeholder=\"请选择颜色\" value=\"" . $value . "\">\r\n                <span class=\"input-group-addon\" style=\"width:35px;border-left:none;background-color:" . $value . "\"></span>\r\n                <span class=\"input-group-btn\">\r\n                    <button class=\"btn btn-default colorpicker\" type=\"button\">选择颜色<i class=\"fa fa-caret-down\"></i></button>\r\n                    <button class=\"btn btn-default colorclean\" type=\"button\" style=\"padding-top:5px;padding-bottom:5px;\"><span><i class=\"layui-icon\"></i></span></button>\r\n                </span>\r\n            </div>\r\n            ";
		return $s;
	}
}
if (!function_exists("my_tpl_form_show_link")) {
	function my_tpl_form_show_link($name, $value = '')
	{
		$s = '';
		if (!defined("TPL_INIT_show_link")) {
			$s = "\r\n            <script type=\"text/javascript\">\r\n                function showLink(elm) {\r\n                    myutil.myShowLink(function(href, permission) {\r\n                        var ipt = \$(elm).parent().prev();\r\n                        var ipts = \$(elm).parent().prev().prev();\r\n                        ipt.val(href);\r\n                        ipts.val(permission);\r\n                    });\r\n                }\r\n            </script>";
			define("TPL_INIT_show_link", true);
		}
		$s .= "\r\n        <div class=\"input-group\">\r\n            <input type=\"text\" class=\"form-control\" name=\"permission\" style=\"display: none\">\r\n            <input type=\"text\" class=\"form-control\" name=\"" . $name . "\" value=\"" . $value . "\">\r\n            <span class=\"input-group-btn\">\r\n                <a href=\"javascript:\"  class=\"btn btn-default\" onclick=\"showLink(this)\">选择链接</a>\r\n            </span>\r\n        </div>\r\n        ";
		return $s;
	}
}
if (!function_exists("my_tpl_form_field_category_2level")) {
	function my_tpl_form_field_category_2level($name, $parents, $children, $parentid, $childid)
	{
		$html = "\r\n            <script type=\"text/javascript\">\r\n                window._" . $name . " = " . json_encode($children) . ";\r\n            </script>";
		$html .= "<div class=\"layui-input-inline\">\r\n                    <select id=\"" . $name . "_parent\" lay-filter=\"" . $name . "\" name=\"" . $name . "[parentid]\">\r\n                        <option value=\"0\">请选择一级分类</option>";
		$ops = '';
		if (!empty($parents)) {
			foreach ($parents as $row) {
				$html .= "<option value=\"" . $row["id"] . "\" " . ($row["id"] == $parentid ? "selected=\"selected\"" : '') . ">" . $row["name"] . "</option>";
			}
		}
		$html .= "\r\n                    </select>\r\n                </div>\r\n                <div class=\"layui-input-inline\">\r\n                    <select id=\"" . $name . "_child\" name=\"" . $name . "[childid]\">\r\n                        <option value=\"0\">请选择二级分类</option>";
		if (!empty($parentid) && !empty($children[$parentid])) {
			foreach ($children[$parentid] as $row) {
				$html .= "<option value=\"" . $row["id"] . "\"" . ($row["id"] == $childid ? "selected=\"selected\"" : '') . ">" . $row["name"] . "</option>";
			}
		}
		$html .= "\r\n                    </select>\r\n                </div>\r\n            ";
		return $html;
	}
}
if (!function_exists("check_send_time")) {
	function check_send_time()
	{
		global $_W;
		$path = MODULE_ROOT . "/data/cache/";
		if (!is_dir($path)) {
			load()->func("file");
			mkdirs($path);
		}
		$file = $path . "sms_" . $_W["uniacid"] . ".json";
		$curr_data = array();
		$curr_data["ext_time"] = time();
		if (!file_exists($file)) {
			fileWriteJson($file, $curr_data);
		}
		$data = json_decode(file_get_contents($file));
		if (property_exists($data, "ext_time")) {
			if ($data->ext_time <= time() - 60 * 15) {
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
}
if (!function_exists("fileWriteJson")) {
	function fileWriteJson($fileAddress, $data)
	{
		$fp = fopen($fileAddress, "w");
		fwrite($fp, json_encode($data));
		fclose($fp);
	}
}
if (!function_exists("send_wx_template_add")) {
	function send_wx_template_add()
	{
		global $_W;
		$condition = " AND uniacid=:uniacid AND template_type='0' ";
		$params = array(":uniacid" => $_W["uniacid"]);
		$one = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_tipswx") . " WHERE 1 " . $condition, $params);
		if (empty($one)) {
			require_once MODULE_ROOT . "/lib/Common.class.php";
			$uniacid = $_W["uniacid"];
			$app = Common::get_app_info($uniacid);
			require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
			$jssdk = new JSSDK($app["appid"], $app["secret"], "token_name_" . $uniacid);
			$result = $jssdk->templates_add();
			if (property_exists($result, "errmsg") && $result->errmsg == "ok") {
				$data = array("uniacid" => $_W["uniacid"], "template_base_id" => "AT0104", "template_base_title" => "预约成功通知", "template_id" => $result->template_id);
				$data["addtime"] = date("Y-m-d H:i:s", time());
				pdo_insert("slwl_fitment_tipswx", $data);
				return "SUCCESS";
			} else {
				return "ERR";
			}
		} else {
			return "NONE";
		}
	}
}
if (!function_exists("send_wx_template_delete")) {
	function send_wx_template_delete()
	{
		global $_W;
		$condition = " AND uniacid=:uniacid AND template_type='0' ";
		$params = array(":uniacid" => $_W["uniacid"]);
		$one = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_tipswx") . " WHERE 1 " . $condition, $params);
		if (!empty($one)) {
			require_once MODULE_ROOT . "/lib/Common.class.php";
			$uniacid = $_W["uniacid"];
			$app = Common::get_app_info($uniacid);
			require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
			$jssdk = new JSSDK($app["appid"], $app["secret"], "token_name_" . $uniacid);
			$result = $jssdk->templates_delete($one["template_id"]);
			if (property_exists($result, "errmsg") && $result->errmsg == "ok") {
				pdo_delete("slwl_fitment_tipswx", array("id" => $one["id"]));
				return "SUCCESS";
			} else {
				return "ERR";
			}
		} else {
			return "NONE";
		}
	}
}
if (!function_exists("get_openid")) {
	function get_openid($uid)
	{
		global $_W;
		$condition = " AND uniacid=:uniacid AND id=:id ";
		$params = array(":uniacid" => $_W["uniacid"], ":id" => $uid);
		$one = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_users") . " WHERE 1 " . $condition, $params);
		if (!empty($one)) {
			return $one["openid"];
		} else {
			return "NONE";
		}
	}
}
if (!function_exists("get_template_id")) {
	function get_template_id()
	{
		global $_W;
		$condition = " AND uniacid=:uniacid AND template_type='0' ";
		$params = array(":uniacid" => $_W["uniacid"]);
		$one = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_tipswx") . " WHERE 1 " . $condition, $params);
		if (!empty($one)) {
			return $one["template_id"];
		} else {
			return "NONE";
		}
	}
}