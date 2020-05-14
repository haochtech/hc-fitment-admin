<?php

//decode by http://www.zx-xcx.com/
if (!defined("IN_IA")) {
	exit("Access Denied");
}
global $_W, $_GPC;
if ($_GPC["do"] == "set" || $_GPC["do"] == "cpright" || $_GPC["do"] == "default" || $_GPC["do"] == "order" || $_GPC["do"] == "giftbig") {
	$_W["mdo"] = "1";
} elseif ($_GPC["do"] == "adv" || $_GPC["do"] == "adsp" || $_GPC["do"] == "adgroup" || $_GPC["do"] == "adact" || $_GPC["do"] == "calc" || $_GPC["do"] == "suspend" || $_GPC["do"] == "adpop") {
	$_W["mdo"] = "2";
} elseif ($_GPC["do"] == "pic_sole" || $_GPC["do"] == "pic_multi" || $_GPC["do"] == "term" || $_GPC["do"] == "news" || $_GPC["do"] == "designer" || $_GPC["do"] == "guestbook" || $_GPC["do"] == "booking" || $_GPC["do"] == "reserve" || $_GPC["do"] == "act_term" || $_GPC["do"] == "act_news" || $_GPC["do"] == "style") {
	$_W["mdo"] = "3";
} elseif ($_GPC["do"] == "term_des" || $_GPC["do"] == "news_des") {
	$_W["mdo"] = "3";
} elseif ($_GPC["do"] == "tipsmsg" || $_GPC["do"] == "usermsg") {
	$_W["mdo"] = "4";
} elseif ($_GPC["do"] == "info" || $_GPC["do"] == "sdata" || $_GPC["do"] == "auth" || $_GPC["do"] == "upgrade" || $_GPC["do"] == "termflag" || $_GPC["do"] == "transfer") {
	$_W["mdo"] = "5";
} else {
	$_W["mdo"] = "1";
}
load()->model("wxapp");
$uniacid = $_W["uniacid"];
$wxapp_info = wxapp_fetch($uniacid);
$_W["wxapp_name"] = $wxapp_info["name"];
$_W["wxapp_version"] = $wxapp_info["version"]["modules"][0]["version"];
$condition_auth = " and uniacid=:uniacid and setting_name=:setting_name";
$params_auth = array(":uniacid" => "0", ":setting_name" => "auth_settings");
$set_auth = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_settings") . " WHERE 1 " . $condition_auth, $params_auth);
if (!empty($set_auth)) {
	$auth = json_decode($set_auth["setting_value"], true);
	$_W["slwl_auth"] = $auth["status"];
} else {
	$_W["slwl_auth"] = "0";
}
$uniacid = $_W["uniacid"];
$condition = " and uniacid=:uniacid and setting_name=:setting_name";
$params = array(":uniacid" => $uniacid, ":setting_name" => "cpright_site_settings");
$set = pdo_fetch("SELECT * FROM " . tablename("slwl_fitment_settings") . " WHERE 1 " . $condition . " limit 1", $params);
$_W["web_copyright"] = "@ " . $_W["wxapp_name"] . " 版权所有";
if (!empty($set)) {
	$settings = json_decode($set["setting_value"], true);
	if (!empty($settings["copyright"])) {
		$_W["web_copyright"] = $settings["copyright"];
	}
}