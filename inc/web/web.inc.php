<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');

$account = uni_fetch($_W['uniacid']);
$app_id = $account['key'];
// $secret = $account['secret'];
unset($account);
// $rst = cache_updatecache();

include $this->template('web/web');

?>
