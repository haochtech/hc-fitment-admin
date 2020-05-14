<?php

if (!(defined('IN_IA'))) {
	exit('Access Denied');
}
!(defined('SLWL_DEBUG')) && define('SLWL_DEBUG', false);
!(defined('SLWL_PREFIX')) && define('SLWL_PREFIX', 'slwl_fitment_');
!(defined('SLWL_PATH')) && define('SLWL_PATH', IA_ROOT . '/addons/slwl_fitment/');
!(defined('SLWL_DATA')) && define('SLWL_DATA', SLWL_PATH . 'data/');
!(defined('SLWL_INC')) && define('SLWL_INC', SLWL_PATH . 'inc/');
!(defined('SLWL_AUTH_URL')) && define('SLWL_AUTH_URL','http://update.q14.cn/WxappFitment/');
!(defined('SLWL_FAMILY')) && define('SLWL_FAMILY', 'v');
!(defined('SLWL_MAIN_MODULE')) && define('SLWL_MAIN_MODULE', 'slwl_fitment');


?>
