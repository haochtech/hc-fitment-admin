<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;

load()->func('tpl');
$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

if ($operation == 'display') {
	$keyword = $_GPC['keyword'];

	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	if ($keyword != '') {
		$condition .= ' AND (name LIKE :name OR honour LIKE :honour OR mobile LIKE :mobile) ';
		$params[':name'] = '%'.$keyword.'%';
		$params[':honour'] = '%'.$keyword.'%';
		$params[':mobile'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_designer'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_designer') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'name' => $_GPC['name'],
			'honour' => $_GPC['honour'],
			'mobile' => $_GPC['mobile'],
			'mobile_status' => intval($_GPC['mobile_status']),
			'attr_1' => $_GPC['attr_1'],
			'attr_2' => $_GPC['attr_2'],
			'attr_3' => $_GPC['attr_3'],
			'attr_4' => $_GPC['attr_4'],
			'isrecommand' => intval($_GPC['isrecommand']),
			'thumb' => $_GPC['thumb'],
			'enabled' => intval($_GPC['enabled']),
		);
		if ($id) {
			pdo_update('slwl_fitment_designer', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_designer', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
	}
	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_designer') . " where 1 " . $condition, $params);


} elseif ($operation == 'set') {

	if ($_W['ispost']) {
		$options = $_GPC['options'];

		$data = array();
		$data['setting_value'] = json_encode($options); // 压缩

		if ($_W['slwl']['set']['designer_set_settings']) {
			$where['uniacid'] = $_W['uniacid'];
			$where['setting_name'] = 'designer_set_settings';
			pdo_update('slwl_fitment_settings', $data, $where);
		} else {
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_name'] = 'designer_set_settings';
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_settings', $data);
		}
		sl_ajax(0, '保存成功！');
	}

	if ($_W['slwl']['set']['designer_set_settings']) {
		$settings = $_W['slwl']['set']['designer_set_settings'];
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_designer', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} elseif ($operation == 'des_sole') {
	$desid = intval($_GPC['desid']);

	$condition = " AND uniacid=:uniacid AND desid=:desid ";
	$params = array(':uniacid' => $_W['uniacid'], ':desid'=>$desid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_sole') . ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

} elseif ($operation == 'des_sole_post') {
	$id = intval($_GPC['id']);
	$desid = intval($_GPC['desid']);

	if ($_W['ispost']) {

		$options = array();
		if($_GPC['options']) {
			foreach ($_GPC['options'] as $k=>$v){
				$options['options'][] = $v;
			}
		}

		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'name' => $_GPC['name'],
			'show_num' => intval($_GPC['show_num']),
			'fav_num' => intval($_GPC['fav_num']),
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb'],
			'desid' => $desid,
		);
		$data['options'] = json_encode($options); // 压缩
		if ($id) {
			pdo_update('slwl_fitment_pic_sole', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_sole', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}

	$condition = " AND uniacid=:uniacid AND enabled='1' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 1000;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$list = pdo_fetchall($sql, $params);

	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . " where 1 " . $condition, $params);

	$checks = json_decode($one['options'], true);

	if ($checks) {
		foreach ($list as $k => $v) {
			foreach ($checks['options'] as $key => $value) {
				if ($v['id']==$value) {
					$list[$k]['checked'] = '1';
					break;
				} else {
					$list[$k]['checked'] = '0';
				}
			}
		}
	}


} elseif ($operation == 'delete_des_sole') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_pic_sole', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} elseif ($operation == 'des_multi') {
	$desid = intval($_GPC['desid']);

	$condition = " AND uniacid=:uniacid AND desid=:desid ";
	$params = array(':uniacid' => $_W['uniacid'], ':desid'=>$desid);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'des_multi_post') {
	$id = intval($_GPC['id']);
	$desid = intval($_GPC['desid']);

	if ($_W['ispost']) {

		$options = array();
		if($_GPC['options']) {
			foreach ($_GPC['options'] as $k=>$v){
				$options['options'][] = $v;
			}
		}

		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'name' => $_GPC['name'],
			'show_num' => intval($_GPC['show_num']),
			'fav_num' => intval($_GPC['fav_num']),
			'enabled' => intval($_GPC['enabled']),
			'desid' => $desid,
		);
		$data['options'] = json_encode($options); // 压缩
		if ($id) {
			pdo_update('slwl_fitment_pic_multi', $data, array('id' => $id));
		} else {
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_pic_multi', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}

	$condition = " AND uniacid=:uniacid AND enabled='1' ";
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 1000;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
	$list = pdo_fetchall($sql, $params);

	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 " . $condition, $params);

	$checks = json_decode($one['options'], true);

	if ($checks) {
		foreach ($list as $k => $v) {
			foreach ($checks['options'] as $key => $value) {
				if ($v['id']==$value) {
					$list[$k]['checked'] = '1';
					break;
				} else {
					$list[$k]['checked'] = '0';
				}
			}
		}
	}

} elseif ($operation == 'des_multi_post_pic') {
	$id = intval($_GPC['id']);
	$desid = intval($_GPC['desid']);

	$condition = " AND uniacid=:uniacid AND id=:id ";
	$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 " . $condition, $params);

	if ($one) {
		$smeta = json_decode($one['smeta'], true);
	}

	if ($_W['ispost']) {

		$options = array();
		$data = array();
		$photo = array();
		$tmp_pic = array();
		$iscover = false;

		if ($_GPC['options']) {
			$options = $_GPC['options'];

			foreach ($options['attachment'] as $k => $v) {
				$tmp_pic[$k]['attachment'] = $v;
			}
			foreach ($options['title'] as $k => $v) {
				$tmp_pic[$k]['title'] = $v;
			}
			foreach ($options['cover'] as $k => $v) {
				$tmp_pic[$k]['cover'] = $v;

				if ($v == '1') {
					$iscover = true;
					$data['name'] = $options['title'][$k];
					$data['thumb'] = $options['attachment'][$k];
				}
			}
			foreach ($tmp_pic as $k=>$v){
				$photo[] = $v;
			}

			if ($iscover == false) {
				if ($one['name'] == '') {
					$data['name'] = @$photo[0]['title'];
				}
				if ($one['thumb'] == '') {
					$data['thumb'] = @$photo[0]['attachment'];
				}
			}

			$data['smeta'] = json_encode($photo); // 压缩
			pdo_update('slwl_fitment_pic_multi', $data, array('id' => $id));
			sl_ajax(0, '保存成功');
		} else {
			sl_ajax(1, '保存失败，数据为空');
		}
	}


} elseif ($operation == 'delete_des_multi') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_pic_multi', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/content/designer');

?>
