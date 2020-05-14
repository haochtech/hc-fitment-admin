<?php

defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/slwl_fitment/defines.php';
require_once SLWL_PATH . 'version.php';
require_once SLWL_INC . 'basic.inc.php';
require_once SLWL_INC . 'lang.inc.php';
require_once SLWL_INC . 'functions.inc.php';
require_once SLWL_INC . 'menus.inc.php';
class Slwl_fitmentModuleSite extends WeModuleSite
{
	public function __construct()
	{
		checklogin();
	}
}
