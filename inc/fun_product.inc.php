<?php
/**
 * 智享工场 Copyright (c) www.zx-xcx.com
 */

// 产品-模块
if(!defined('IN_IA')) { exit('Access Denied'); }

// 测试
if (!function_exists('pro_test'))
{
	function pro_test()
	{
		global $_GPC, $_W;

		dump($_W);
		echo "<hr>";
		dump($_GPC);

	}
}

// 产品-列表-配置
if (!function_exists('pro_list_config'))
{
	function pro_list_config()
	{
		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$tmp_config = array();
		if ($_W['slwl']['set']['set_pro_list']) {
			$tmp_config = $_W['slwl']['set']['set_pro_list'];
		}

		$data_bak = array(
			'config'=>$tmp_config,
		);

		return result(0, 'ok', $data_bak);
	}
}

// 产品-首页
if (!function_exists('pro_index_data'))
{
	function pro_index_data()
	{
		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$termid = max(0, intval($_GPC['tid'])); // 分类ID

		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_product_list') . ' WHERE 1 '
			. $condition . ' ORDER BY displayorder DESC, id DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		// 查收藏
		if ($list) {
			$ids = '';
			foreach ($list as $k=>$v) {
				$ids .= $v['id'] . ',';
			}
			$ids = substr($ids, 0, strlen($ids)-1);
			$where =' AND id_resource IN(' . $ids . ')';

			$condition_collect = " AND uniacid=:uniacid AND user=:uid AND type_resource='3' ";
			$condition_collect .= $where;
			$params_collect = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid);
			$sql_collect = "SELECT id_resource FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 ' . $condition_collect;
			$list_collect = pdo_fetchall($sql_collect, $params_collect);

			foreach ($list as $k => $v) {
				$list[$k]['fav'] = '0';

				if ($list_collect) {
					foreach ($list_collect as $key => $value) {
						if ($v['id'] == $value['id_resource']) {
							$list[$k]['fav'] = '1';
							break;
						}
					}
				}
			}

			foreach ($list as $k => $v) {
				$list[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		$list_news['items'] = $list;

		$data_bak = array(
			'topic'=>$list_news,
		);

		return result(0, 'ok', $data_bak);
	}
}

// 产品-搜索
if (!function_exists('pro_search'))
{
	function pro_search()
	{
		global $_GPC, $_W;

	}
}

// 产品-one
if (!function_exists('pro_one'))
{
	function pro_one()
	{
		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$id = intval($_GPC['aid']);

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_product_list') . ' WHERE 1 ' . $condition, $params);

		// 查收藏
		if ($one) {
			// 浏览数加 +1
			pdo_query("UPDATE " . tablename('slwl_fitment_product_list')
				. " set view_count=view_count+1 WHERE id=:id ", array(':id'=>$one['id']));

			$condition_collect = " AND uniacid=:uniacid AND user=:uid AND type_resource='3' AND id_resource=:id_resource ";
			$params_collect = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid, ':id_resource'=>$one['id']);
			$sql_collect = "SELECT id_resource FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 ' . $condition_collect;
			$one_collect = pdo_fetch($sql_collect, $params_collect);

			$one['fav'] = '0';

			if ($one_collect) {
				$one['fav'] = '1';
			}
		}

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
			$one['out_thumb_url'] = tomedia($one['out_thumb']);
		}

		$data_bak = array(
			'one'=>$one
		);

		return result(0, 'ok', $data_bak);
	}
}

// 收藏
if (!function_exists('pro_collect'))
{
	function pro_collect()
	{
		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$operation = $_GPC['op'] ? $_GPC['op'] : 'display';
		$isfav = isset($_GPC['isfav']) ? $_GPC['isfav'] : '0';
		$page = max(1, intval($_GPC['page']));
		$id = $_GPC['id'];

		// $post_params = $_GPC['__input']; // 参数
		// if ($post_params) {
			// ver
			// $ver = isset($post_params['ver']) ? $post_params['ver'] : '';
			// id
			// $id = isset($post_params['id']) ? intval($post_params['id']) : '';
			// 用户ID
			// $uid = isset($post_params['uid']) ? intval($post_params['uid']) : '';
			// 收藏状态
			// $isfav = isset($post_params['isfav']) ? $post_params['isfav'] : '0';
			// page，分页
			// $page = isset($post_params['page']) ? $post_params['page'] : '1';
			// 操作
			// $operation = isset($post_params['op']) ? $post_params['op'] : 'display';
		// }


		if (empty($uid)) {
			return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']);
		}

		if ($operation == 'display') {
			$condition_collect = " AND uniacid=:uniacid AND user=:uid AND type_resource='3' ";
			$params_collect = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid);
			$pindex_collect = max(1, intval($page));
			$psize_collect = 10;
			$sql_collect = "SELECT id_resource FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
				. $condition_collect . " ORDER BY addtime DESC LIMIT " . ($pindex_collect - 1) * $psize_collect .',' .$psize_collect;
			$list_collect = pdo_fetchall($sql_collect, $params_collect);

			$list_pro = array();
			if ($list_collect) {
				$flags = '';
				foreach ($list_collect as $item) {
					$flags .= $item['id_resource'] . ',';
				}

				$flags = substr($flags, 0, strlen($flags)-1);
				$where =' AND id IN(' . $flags . ')';

				$condition_pro = " AND uniacid=:uniacid AND enabled='1' ";
				$condition_pro .= $where;
				$params_pro = array(':uniacid' => $_W['uniacid']);
				$pindex_pro = max(1, intval($_GPC['page']));
				$psize_pro = 10;
				$sql_pro = "SELECT * FROM " . tablename('slwl_fitment_product_list'). ' WHERE 1 '
					. $condition_pro . " ORDER BY displayorder DESC, id DESC LIMIT "
					. ($pindex_pro - 1) * $psize_pro .',' .$psize_pro;

				$list_pro = pdo_fetchall($sql_pro, $params_pro);

				if ($list_pro) {
					foreach ($list_pro as $k => $v) {
						$list_pro[$k]['thumb_url'] = tomedia($v['thumb']);
						$list_pro[$k]['out_thumb_url'] = tomedia($v['out_thumb']);
					}
				}
			}

			$data_bak = array(
				'list_pro'=>$list_pro,
			);
			return result(0, 'ok', $data_bak);


		} else if ($operation == 'post') {
			if (empty($id)) {
				return result(1, $_W['slwl']['lang']['tips_not_exist_resource_id']);
			}

			$condition_fav = " AND uniacid=:uniacid AND id_resource=:id_resource AND user=:user AND type_resource='3' ";
			$params_fav = array(':uniacid'=>$_W['uniacid'], ':id_resource'=>$id, ':user'=>$uid);
			$one_fav = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 " . $condition_fav, $params_fav);

			if ($isfav) {
				if ($one_fav) {
					return result(0, 'ok');
				} else {
					$data = array(
						'uniacid' => $_W['uniacid'],
						'user' => $uid,
						'type_resource' => '3',
						'id_resource' => $id,
						'addtime' => $_W['slwl']['datetime']['now'],
					);

					$res = pdo_insert('slwl_fitment_fav', $data);

					pdo_query("UPDATE " . tablename('slwl_fitment_product_list') . " set fav_count=fav_count+1 WHERE id=:id ", array(':id'=>$id));

					if ($res > 0) {
						return result(0, 'ok', $res);
					} else {
						return result(1, $_W['slwl']['lang']['tips_error']);
					}
				}
			} else {
				// pdo_query("UPDATE " . tablename('slwl_fitment_product_list') . " set fav_count=fav_count-1 WHERE id=:id ", array(':id'=>$id));
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
