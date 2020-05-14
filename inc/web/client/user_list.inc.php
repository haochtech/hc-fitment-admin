<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;
load()->func('tpl');
$operation = ($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'display') {
	$keyword = trim($_GPC['keyword']);

	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;

	if ($keyword != '') {
		$condition .= ' AND (nicename LIKE :nicename OR id = :id) ';
		$params[':nicename'] = '%'.$keyword.'%';
		$params[':id'] = $keyword;
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_users'). ' WHERE 1 '
		. $condition . " ORDER BY addtime DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	if ($list) {
		foreach ($list as $k => $v) {
			if ($v['mobile'] == '') {
				$list[$k]['mobile'] = '暂无';
			}

			$list[$k]['nickname'] = json_decode($v['nicename']);
		}
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/client/user_list');

?>
