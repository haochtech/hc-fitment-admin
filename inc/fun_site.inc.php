<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

// 工地-模块
if(!defined('IN_IA')) { exit('Access Denied'); }

// 测试
if (!function_exists('site_test'))
{
	function site_test()
	{
		global $_GPC, $_W;

		dump($_W);
		echo "<hr>";
		dump($_GPC);

	}
}

// 工地-首页
if (!function_exists('site_index_data'))
{
	function site_index_data()
	{
		global $_GPC, $_W;
		$ver = $_GPC['ver'];

		// 小区
		$condition_subdistrict = " AND uniacid=:uniacid AND enabled='1' ";
		$params_subdistrict = array(':uniacid' => $_W['uniacid']);
		$list_subdistrict = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_site_manage') . ' WHERE 1 '
			. $condition_subdistrict . '  ORDER BY displayorder DESC, id DESC ', $params_subdistrict);

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

		// // 装修方式
		// $condition_mode = " AND uniacid=:uniacid AND enabled='1' ";
		// $params_mode = array(':uniacid' => $_W['uniacid']);
		// $list_mode = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_mode').' WHERE 1 '.
		//     $condition_mode.' ORDER BY displayorder ASC, id ASC', $params_mode);

		$data_bak = array(
			'list_subdistrict'=>$list_subdistrict,
			'list_progress'=>$list_progress,
			'list_type'=>$list_type,
			'list_style'=>$list_style,
		);

		return result(0, 'ok', $data_bak);
	}
}

// 工地-搜索
if (!function_exists('site_search'))
{
	function site_search()
	{
		global $_GPC, $_W;
		$ver = $_GPC['ver'];

		$post_params = $_GPC['__input']; // 参数
		// dump($post_params);exit;
		if ($post_params) {
			// 分页
			$site_page = isset($post_params['page']) ? $post_params['page'] : 1;
			// 综合
			$site_order = isset($post_params['order']) ? $post_params['order'] : '';
			// 小区
			$site_subdistrict = isset($post_params['subdistrict']) ? ($post_params['subdistrict']) : '';
			// 工地进度
			$site_progress = isset($post_params['progress']) ? ('a'.$post_params['progress']) : '';
			// 工地房型
			$site_type = isset($post_params['type']) ? ('b'.$post_params['type']) : '';
			// 装修风格
			$site_style = isset($post_params['style']) ? ('c'.$post_params['style']) : '';
		}
		// dump($site_order);
		// dump($site_subdistrict);
		// dump($site_progress);
		// dump($site_type);
		// dump($site_style);
		// exit;

		// 工地列表+最后的动态
		$condition_site = " AND uniacid=:uniacid AND enabled='1' ";
		$params_site = array(':uniacid' => $_W['uniacid']);
		// $pindex_site = max(1, intval($_GPC['page']));
		$psize_site = 10;
		$pindex_site = max(1, intval($site_page));

		$order = '';
		if ($site_order) {
			if ($site_order == 'view') {
				$order .= " view_count DESC, ";
			} else if ($site_order == 'fav') {
				$order .= " fav_count DESC, ";
			}
		}
		$where = '';
		// 小区
		if ($site_subdistrict != '') {
			$where .= " AND site_id=:site_id  ";
			$params_site[':site_id'] = $site_subdistrict;
		}
		// 工地进度
		if ($site_progress != '') {
			$where .= " AND options LIKE :site_progress ";
			$params_site[':site_progress'] = "%{$site_progress}%";
		}
		// 工地房型
		if ($site_type != '') {
			$where .= " AND options LIKE :site_type ";
			$params_site[':site_type'] = "%{$site_type}%";
		}
		// 装修风格
		if ($site_style != '') {
			$where .= " AND options LIKE :site_style ";
			$params_site[':site_style'] = "%{$site_style}%";
		}

		$select_child_id = " (SELECT aa.id FROM ". tablename('slwl_fitment_site_list_dy')
			." AS aa WHERE aa.site_id = sfsl.id ORDER BY id DESC limit 1) as dy_id, ";
		$select_child_thumbs = " (SELECT bb.thumbs FROM ". tablename('slwl_fitment_site_list_dy')
			." AS bb WHERE bb.site_id = sfsl.id ORDER BY id DESC limit 1) as dy_thumbs ";

		$condition_site .= $where;
		$sql_site = "SELECT sfsl.*,". $select_child_id. $select_child_thumbs ." FROM " . tablename('slwl_fitment_site_list')
			." AS sfsl WHERE 1 " . $condition_site . " ORDER BY ".$order." displayorder DESC, id DESC LIMIT "
			.($pindex_site - 1) * $psize_site . ',' .$psize_site;
		$list_site = pdo_fetchall($sql_site, $params_site);

		// pdo_debug();exit;

		// 工地进度
		$condition_progress = " AND uniacid=:uniacid AND enabled='1' ";
		$params_progress = array(':uniacid' => $_W['uniacid']);
		$list_progress = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_progress').' WHERE 1 '.
			$condition_progress.' ORDER BY displayorder ASC, id ASC ', $params_progress);

		if ($list_site) {
			foreach ($list_site as $k => $v) {
				$list_site[$k]['thumb_url'] = tomedia($v['thumb']);
				$list_site[$k]['dy_thumbs_url'] = array();

				// options
				$v_options = json_decode($v['options'], true);

				// 处理图片相对路径
				if ($v['dy_thumbs']) {
					$show_number = 0;
					$array_thumbs = json_decode($v['dy_thumbs'], true);
					foreach ($array_thumbs as $key => $value) {
						$show_number += 1;
						if ($show_number > 3) {
							break;
						}
						$list_site[$k]['dy_thumbs_url'][] = tomedia($value);
					}
				}
				// 处理工地进度
				if ($v_options['site_progress']) {
					foreach ($list_progress as $key => $value) {
						if ($v_options['site_progress'] == 'a'.$value['id']) {
							$list_site[$k]['site_progress_cn'] = $value['title'];
							break;
						}
					}
				}
				unset($list_site[$k]['options']);
			}
		}

		$data_bak = array(
			'list_site'=>$list_site,
		);

		return result(0, 'ok', $data_bak);
	}
}

// 工地-one
if (!function_exists('site_one'))
{
	function site_one()
	{
		global $_GPC, $_W;

		$post_params = $_GPC['__input']; // 参数
		if ($post_params) {
			// uid
			$uid = isset($post_params['uid']) ? $post_params['uid'] : '';
			// ver
			$ver = isset($post_params['ver']) ? $post_params['ver'] : '';
			// id
			$id = isset($post_params['id']) ? $post_params['id'] : '';
		}

		if (empty($id)) {
			return result(1, $_W['slwl']['lang']['tips_not_exist_id']);
		}

		$condition = ' AND uniacid=:uniacid AND id=:id ';
		$params = array(':uniacid'=>$_W['uniacid'], ":id"=>$id);
		$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_site_list') . ' where 1 ' . $condition, $params);

		if ($one) {
			// 浏览数加 +1
			pdo_query("UPDATE " . tablename('slwl_fitment_site_list')
				. " set view_count=view_count+1 WHERE id=:id ", array(':id'=>$one['id']));

			if ($one['coordinate']) {
				$one_coordinate = json_decode($one['coordinate'], true);
				$one['coordinate_format'] = $one_coordinate;
			}

			// 查收藏 表  type_resource 类型0=单图，1=多图，2=工地
			$condition_fav = " AND uniacid=:uniacid AND user=:user AND id_resource=:id_resource AND type_resource='2' ";
			$params_fav = array(':uniacid' => $_W['uniacid'], ':user'=>$uid, ':id_resource'=>$id);
			$one_fav = pdo_fetch('SELECT * FROM '.tablename('slwl_fitment_fav').' WHERE 1 ' . $condition_fav, $params_fav);

			if ($one_fav) {
				$one['isfav'] = '1'; // 已收藏
			} else {
				$one['isfav'] = '0';
			}

			$one['thumb_url'] = tomedia($one['thumb']);
			// 装修动态
			$condition_list_dy = " AND uniacid=:uniacid AND site_id=:site_id AND enabled='1' ";
			$params_list_dy = array(':uniacid' => $_W['uniacid'], ':site_id'=>$id);
			$list_list_dy = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_list_dy').' WHERE 1 '.
				$condition_list_dy.' ORDER BY displayorder DESC, id DESC', $params_list_dy);

			if ($list_list_dy) {
				$datetime1 = new DateTime($one['create_time']); // 工地创建日期

				foreach ($list_list_dy as $k => $v) {
					$datetime2 = new DateTime($v['create_time']); // 动态日期
					// 计算日期差
					$interval = $datetime1->diff($datetime2);
					$list_list_dy[$k]['day_diff'] = $interval->format('%a');

					// 处理图片相对路径
					$v_thumbs = json_decode($v['thumbs'], true);
					if ($v_thumbs) {
						foreach ($v_thumbs as $key => $value) {
							$list_list_dy[$k]['thumbs_url'][] = tomedia($value);
						}
					}
				}
				$one['dy_list'] = $list_list_dy;
				unset($list_list_dy['thumbs']);
			}

			// 工地进度
			$condition_progress = " AND uniacid=:uniacid AND enabled='1' ";
			$params_progress = array(':uniacid' => $_W['uniacid']);
			$list_progress = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_progress').' WHERE 1 '.
				$condition_progress.' ORDER BY displayorder ASC, id ASC', $params_progress);

			// 装修风格
			$condition_style = " AND uniacid=:uniacid AND enabled='1' ";
			$params_style = array(':uniacid' => $_W['uniacid']);
			$list_style = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_style').' WHERE 1 '.
				$condition_style.' ORDER BY displayorder ASC, id ASC', $params_style);

			$one_options = json_decode($one['options'], true);

			if ($one_options) {
				// 户型
				$tmp_house_type = json_decode($one['house_type'], true);
				if ($tmp_house_type) {
					$one['house_type_format'] = $tmp_house_type;
				}
				// 装修预算
				if ($one['budget']) {
					$one['budget_format'] = $one['budget'] / 100;
				}
				// 工地进度
				if ($one_options['site_progress']) {
					foreach ($list_progress as $k => $v) {
						$list_progress[$k]['checked'] = '0';
						if (('a'.$v['id']) == $one_options['site_progress']) {
							// unset($list_progress[$k]);
							$list_progress[$k]['checked'] = '1';
						}
					}
				}
				// 装修风格
				$style_show = array();
				if ($one_options['site_style']) {
					foreach ($list_style as $k => $v) {
						$list_style[$k]['checked'] = '0';
						foreach ($one_options['site_style'] as $key => $value) {
							if ('c'.$v['id'] == $value) {
								$style_show[] = $v;
								$list_style[$k]['checked'] = '1';
							}
						}
					}
				}
				unset($one['options']);
			}
		}

		$data_bak = array(
			'one'=>$one,
			'list_progress'=>$list_progress,
			'list_style'=>$list_style,
			'style_show'=>$style_show,
		);

		return result(0, 'ok', $data_bak);
	}
}

// 收藏
if (!function_exists('site_collect'))
{
	function site_collect()
	{
		global $_GPC, $_W;

		$operation = ($_GPC['op']) ? $_GPC['op'] : 'display';
		$post_params = $_GPC['__input']; // 参数

		if ($post_params) {
			// ver
			$ver = isset($post_params['ver']) ? $post_params['ver'] : '';
			// id
			$id = isset($post_params['id']) ? intval($post_params['id']) : '';
			// 用户ID
			$uid = isset($post_params['uid']) ? intval($post_params['uid']) : '';
			// 收藏状态
			$isfav = isset($post_params['isfav']) ? $post_params['isfav'] : '0';
			// page，分页
			$page = isset($post_params['page']) ? $post_params['page'] : '1';
		}

		if (empty($uid)) {
			return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']);
		}

		if ($operation == 'display') {
			$condition_collect = " AND uniacid=:uniacid AND user=:uid AND type_resource='2' ";
			$params_collect = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid);
			$pindex_collect = max(1, intval($page));
			$psize_collect = 10;
			$sql_collect = "SELECT id_resource FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
				. $condition_collect . " ORDER BY addtime DESC LIMIT " . ($pindex_collect - 1) * $psize_collect .',' .$psize_collect;
			$list_collect = pdo_fetchall($sql_collect, $params_collect);

			if ($list_collect) {
				$flags = '';
				foreach ($list_collect as $item) {
					$flags .= $item['id_resource'] . ',';
				}

				$flags = substr($flags, 0, strlen($flags)-1);
				$where =' AND sfsl.id in(' . $flags . ')';

				// 工地列表+最后的动态
				$condition_site = " AND uniacid=:uniacid AND enabled='1' ";
				$params_site = array(':uniacid' => $_W['uniacid']);

				$select_child_id = " (SELECT aa.id FROM ". tablename('slwl_fitment_site_list_dy')
					." AS aa WHERE aa.site_id = sfsl.id ORDER BY id DESC limit 1) as dy_id, ";
				$select_child_thumbs = " (SELECT bb.thumbs FROM ". tablename('slwl_fitment_site_list_dy')
					." AS bb WHERE bb.site_id = sfsl.id ORDER BY id DESC limit 1) as dy_thumbs ";

				$condition_site .= $where;
				$sql_site = "SELECT sfsl.*,". $select_child_id . $select_child_thumbs . " FROM " . tablename('slwl_fitment_site_list')
					." AS sfsl WHERE 1 " . $condition_site . " ORDER BY displayorder DESC, id DESC ";
				$list_site = pdo_fetchall($sql_site, $params_site);

				// pdo_debug();exit;

				// 工地进度
				$condition_progress = " AND uniacid=:uniacid AND enabled='1' ";
				$params_progress = array(':uniacid' => $_W['uniacid']);
				$list_progress = pdo_fetchall('SELECT * FROM '.tablename('slwl_fitment_site_progress').' WHERE 1 '.
					$condition_progress.' ORDER BY displayorder ASC, id ASC ', $params_progress);

				foreach ($list_site as $k => $v) {
					$list_site[$k]['thumb_url'] = tomedia($v['thumb']);
					$list_site[$k]['dy_thumbs_url'] = array();

					// options
					$v_options = json_decode($v['options'], true);

					// 处理图片相对路径
					if ($v['dy_thumbs']) {
						$show_number = 0;
						$array_thumbs = json_decode($v['dy_thumbs'], true);
						foreach ($array_thumbs as $key => $value) {
							$show_number += 1;
							if ($show_number > 3) {
								break;
							}
							$list_site[$k]['dy_thumbs_url'][] = tomedia($value);
						}
					}
					// 处理工地进度
					if ($v_options['site_progress']) {
						foreach ($list_progress as $key => $value) {
							if ($v_options['site_progress'] == 'a'.$value['id']) {
								$list_site[$k]['site_progress_cn'] = $value['title'];
								break;
							}
						}
					}
					unset($list_site[$k]['options']);
				}
				$data_bak = array(
					'list_site'=>$list_site,
				);
				return result(0, 'ok', $data_bak);
			} else {
				$data_bak = array(
					'list_site'=>array(),
				);
				return result(0, 'ok', $data_bak);
			}


		} else if ($operation == 'post') {
			if (empty($id)) {
				return result(1, $_W['slwl']['lang']['tips_not_exist_resource_id']);
			}

			$condition_fav = " AND uniacid=:uniacid AND id_resource=:id_resource AND user=:user AND type_resource='2' ";
			$params_fav = array(':uniacid'=>$_W['uniacid'], ':id_resource'=>$id, ':user'=>$uid);
			$one_fav = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 " . $condition_fav, $params_fav);

			if ($isfav) {
				if ($one_fav) {
					return result(0, 'ok');
				} else {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'user' => $uid,
						'type_resource' => '2',
						'id_resource' => $id,
						'addtime' => $_W['slwl']['datetime']['now'],
					);

					$res = pdo_insert('slwl_fitment_fav', $data);

					pdo_query("UPDATE " . tablename('slwl_fitment_site_list') . " set fav_count=fav_count+1 WHERE id=:id ", array(':id'=>$id));

					if ($res > 0) {
						return result(0, 'ok', $res);
					} else {
						return result(1, $_W['slwl']['lang']['tips_error']);
					}
				}
			} else {
				// pdo_query("UPDATE " . tablename('slwl_fitment_site_list') . " set fav_count=fav_count-1 WHERE id=:id ", array(':id'=>$id));
				$rst = pdo_delete('slwl_fitment_fav', array('id' => $one_fav));
				if ($rst !== false) {
					return result(0, $_W['slwl']['lang']['tips_success']);
				} else {
					return result(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']);
				}
			}
		} else {
			// 操作不存在
			return result(1, $_W['slwl']['lang']['tips_not_exist_operation']);
		}
	}
}

?>
