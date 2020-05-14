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

	// 工地列表
	$condition = ' AND uniacid=:uniacid ';
	$params = array(':uniacid' => $_W['uniacid']);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	if ($keyword != '') {
		$condition .= ' AND (title LIKE :title) ';
		$params[':title'] = '%'.$keyword.'%';
	}

	$sql = "SELECT * FROM " . tablename('slwl_fitment_site_list'). ' WHERE 1 ' .
		$condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_site_list') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);

	if ($list) {
		// 小区列表
		$flags = '';
		foreach ($list as $k => $v) {
			$flags .= $v['site_id'] . ',';
		}
		$flags = substr($flags, 0, strlen($flags)-1);
		$where =' AND id in(' . $flags . ')';

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$condition_subdistrict = " AND uniacid=:uniacid AND enabled='1' ";
		$condition_subdistrict .= $where;
		$params_subdistrict = array(':uniacid' => $_W['uniacid']);

		$list_subdistrict = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_site_manage') . ' WHERE 1 '
			. $condition_subdistrict . ' ORDER BY displayorder DESC, id DESC ', $params_subdistrict);

		foreach ($list as $k => $v) {
			foreach ($list_subdistrict as $key => $value) {
				// dump($v['site_id'].'------'.$value['id'].'---------------'.($v['site_id'] == $value['id']));
				if ($v['site_id'] == $value['id']) {
					$list[$k]['subdistrict_cn'] = $value['title'];
					break;
				}
			}
		}
	}


} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);

	if ($_W['ispost']) {
		// 地理位置转换
		$map = $_GPC['map'];
		if ($map['lng'] && $map['lat']) {
			$map_baidu = array('lng'=>$map['lng'], 'lat'=>$map['lat']);
			$map_qq = Convert_BD09_To_GCJ02($map['lat'], $map['lng']);
			$coordinate = array(
				'baidu'=>$map_baidu,
				'qq'=>$map_qq,
			);
		}
		$options = $_GPC['options'];
		$other_attr = $_GPC['attrs'];

		if ($other_attr['budget']) {
			$other_attr['budget'] = $other_attr['budget'] * 100;
		}

		// 工地房型
		if ($options['list_type']) {
			$tmp_list_type = array();
			foreach ($options['list_type'] as $k => $v) {
				$tmp_list_type[] = $v;
			}
			$options['list_type'] = $tmp_list_type;
		} else {
			$options['list_type'] = array();
		}
		// 装修风格
		if ($options['list_style']) {
			$tmp_list_style = array();
			foreach ($options['list_style'] as $k => $v) {
				$tmp_list_style[] = $v;
			}
			$options['list_style'] = $tmp_list_style;
		} else {
			$options['list_style'] = array();
		}
		// dump($options);
		// $str_json = json_encode($options);
		// echo $str_json.'<hr>';
		// dump(json_decode($str_json, true));
		// exit;

		// 装修预算
		if ($_GPC['budget']) {
			$budget = $_GPC['budget'] * 100;
		} else {
			$budget = 0;
		}
		// 户型
		$tmp_house_type = $_GPC['house_type'];
		$tmp_house_type['room'] = intval($tmp_house_type['room']);
		$tmp_house_type['hall'] = intval($tmp_house_type['hall']);
		$tmp_house_type['kitchen'] = intval($tmp_house_type['kitchen']);
		$tmp_house_type['toilet'] = intval($tmp_house_type['toilet']);
		$house_type = json_encode($tmp_house_type);

		$data = array(
			'uniacid' => $_W['uniacid'],
			'site_id' => $_GPC['site_id'],
			'displayorder' => $_GPC['displayorder'],
			'title' => $_GPC['title'],
			'project_code' => $_GPC['project_code'],
			'isrecommand' => intval($_GPC['isrecommand']),
			'visible' => intval($_GPC['visible']),
			'coordinate' => json_encode($coordinate),
			'address' => $_GPC['address'],
			'budget' => $budget,
			'house_type' => $house_type,
			'area' => $_GPC['area'],
			'options' => json_encode($options),
			'other_attr' => json_encode($other_attr),
			'enabled' => intval($_GPC['enabled']),
			'thumb' => $_GPC['thumb'],
			'view_count' => intval($_GPC['view_count']),
			'fav_count' => intval($_GPC['fav_count']),
		);
		if ($id) {
			pdo_update('slwl_fitment_site_list', $data, array('id' => $id));
		} else {
			$data['create_time'] = $_W['slwl']['datetime']['now'];
			pdo_insert('slwl_fitment_site_list', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}

	// 所有小区
	$condition_subdistrict = " AND uniacid=:uniacid ";
	$params_subdistrict = array(':uniacid' => $_W['uniacid']);
	$list_subdistrict = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_manage').' WHERE 1 '.
		$condition_subdistrict.' ORDER BY displayorder ASC, id ASC', $params_subdistrict);

	// 工地进度
	$condition_progress = " AND uniacid=:uniacid AND enabled='1' ";
	$params_progress = array(':uniacid' => $_W['uniacid']);
	$list_progress = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_progress').' WHERE 1 '.
		$condition_progress.' ORDER BY displayorder ASC, id ASC', $params_progress);

	// 工地房型
	$condition_type = " AND uniacid=:uniacid AND enabled='1' ";
	$params_type = array(':uniacid' => $_W['uniacid']);
	$list_type = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_type').' WHERE 1 '.
		$condition_type.' ORDER BY displayorder ASC, id ASC', $params_type);

	// 装修风格
	$condition_style = " AND uniacid=:uniacid AND enabled='1' ";
	$params_style = array(':uniacid' => $_W['uniacid']);
	$list_style = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_style').' WHERE 1 '.
		$condition_style.' ORDER BY displayorder ASC, id ASC', $params_style);

	// 装修方式
	$condition_mode = " AND uniacid=:uniacid AND enabled='1' ";
	$params_mode = array(':uniacid' => $_W['uniacid']);
	$list_mode = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_mode').' WHERE 1 '.
		$condition_mode.' ORDER BY displayorder ASC, id ASC', $params_mode);

	if ($id) {
		$condition = ' AND uniacid=:uniacid AND id=:id ';
		$params = array(':uniacid'=>$_W['uniacid'], ":id"=>$id);
		$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_site_list') . ' where 1 ' . $condition, $params);

		if ($one) {
			$one_coordinate = json_decode($one['coordinate'], true);

			if ($one_coordinate) {
				$tmp_map = array(
					'lng'=>$one_coordinate['baidu']['lng'],
					'lat'=>$one_coordinate['baidu']['lat'],
				);
			}

			$one_options = json_decode($one['options'], true);
			if ($one_options) {
				// 工地进度
				if ($one_options['site_progress']) {
					foreach ($list_progress as $k => $v) {
						$list_progress[$k]['checked'] = '0';
						if ('a'.$v['id'] == $one_options['site_progress']) {
							$list_progress[$k]['checked'] = '1';
						}
					}
				}
				// 工地房型
				if ($one_options['site_type']) {
					foreach ($list_type as $k => $v) {
						$list_type[$k]['checked'] = '0';
						foreach ($one_options['site_type'] as $key => $value) {
							if ('b'.$v['id'] == $value) {
								$list_type[$k]['checked'] = '1';
							}
						}
					}
				}
				// 装修风格
				if ($one_options['site_style']) {
					foreach ($list_style as $k => $v) {
						$list_style[$k]['checked'] = '0';
						foreach ($one_options['site_style'] as $key => $value) {
							if ('c'.$v['id'] == $value) {
								$list_style[$k]['checked'] = '1';
							}
						}
					}
				}
				// 装修方式
				if ($one_options['site_mode']) {
					foreach ($list_mode as $k => $v) {
						$list_mode[$k]['checked'] = '0';
						if ('d'.$v['id'] == $one_options['site_mode']) {
							$list_mode[$k]['checked'] = '1';
						}
					}
				}

			}
			// 户型
			$tmp_house_type = json_decode($one['house_type'], true);
			if ($tmp_house_type) {
				$one['house_type_format'] = $tmp_house_type;
			}
			// 装修预算
			if ($one['budget']) {
				$one['budget_format'] = $one['budget'] / 100;
			}
		}
	}


} elseif ($operation == 'delete') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_site_list', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_error']);
	}


} elseif ($operation == 'site_dy') {
	$site_id = intval($_GPC['sid']); // 工地ID

	$condition = " AND uniacid=:uniacid AND site_id=:site_id ";
	$params = array(':uniacid' => $_W['uniacid'], ':site_id'=>$site_id);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql = "SELECT * FROM " . tablename('slwl_fitment_site_list_dy'). ' WHERE 1 '
		. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;

	$list = pdo_fetchall($sql, $params);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_site_list_dy') . ' WHERE 1 ' . $condition, $params);
	$pager = pagination($total, $pindex, $psize);


} elseif ($operation == 'post_site_dy') {
	$id = intval($_GPC['id']); // 动态ID
	$site_id = intval($_GPC['sid']); // 工地ID
	$site_progress_val = intval($_GPC['site_progress']); // 保存进度ID
	$tmp_progress_title = ''; // 临时进度标题

	// 工地进度
	$condition_progress = " AND uniacid=:uniacid AND enabled='1' ";
	$params_progress = array(':uniacid' => $_W['uniacid']);
	$list_progress = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_progress').' WHERE 1 '.
		$condition_progress.' ORDER BY displayorder ASC, id ASC', $params_progress);

	if ($_W['ispost']) {
		// dump($site_progress_val);exit;
		if ($list_progress) {
			foreach ($list_progress as $k => $v) {
				if ($site_progress_val == $v['id']) {
					$tmp_progress_title = $v['title'];
					break;
				}
			}
		}

		$data = array(
			'uniacid' => $_W['uniacid'],
			'displayorder' => $_GPC['displayorder'],
			'title' => $_GPC['title'],
			'intro' => $_GPC['intro'],
			'thumb' => $_GPC['thumb'],
			'id_progress' => $_GPC['site_progress'],
			'id_title' => $tmp_progress_title,
			'enabled' => intval($_GPC['enabled']),
		);
		// 多图
		$thumbs = array();
		if ($_GPC['thumbs']) {
			foreach ($_GPC['thumbs'] as $k => $v) {
				$thumbs[] = $v;
			}
			$data['thumbs'] = json_encode($thumbs);
		}
		if ($id) {
			if (empty($site_id)) {
				sl_ajax(1, '工地ID不存在');
				exit;
			}
			pdo_update('slwl_fitment_site_list_dy', $data, array('id' => $id));
		} else {
			$data['create_time'] = $_W['slwl']['datetime']['now'];
			$data['site_id'] = $site_id;
			pdo_insert('slwl_fitment_site_list_dy', $data);
			$id = pdo_insertid();
		}
		sl_ajax(0, '保存成功');
		exit;
	}
	$condition_dy = ' AND uniacid=:uniacid AND id=:id ';
	$params_dy = array(':uniacid'=>$_W['uniacid'], ':id'=>$id);
	$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_site_list_dy') . ' WHERE 1 ' . $condition_dy, $params_dy);

	if ($one) {
		$thumbs = array();
		$tmp_imgs = json_decode($one['thumbs'], true);
		if ($tmp_imgs) {
			foreach ($tmp_imgs as $k => $v) {
				$thumbs[] = $v;
			}
		}

		// 工地进度
		if ($list_progress) {
			foreach ($list_progress as $k => $v) {
				$list_progress[$k]['checked'] = '0';
				if ($v['id'] == $one['id_progress']) {
					$tmp_progress_title = $v['title'];
					$list_progress[$k]['checked'] = '1';
				}
			}
		}
	}


} elseif ($operation == 'delete_site_dy') {
	$id = intval($_GPC['id']);

	$rst = pdo_delete('slwl_fitment_site_list_dy', array('id' => $id));
	if ($rst !== false) {
		sl_ajax(0, $_W['slwl']['lang']['tips_success']);
	} else {
		sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
	}


} else {
	message('请求方法不存在');
}

include $this->template('web/site/list');

?>
