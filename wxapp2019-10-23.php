<?php


defined('IN_IA') or exit('Access Denied');

require_once IA_ROOT . '/addons/slwl_fitment/defines.php';
require_once SLWL_PATH . 'version.php';
require_once SLWL_INC . 'basic.inc.php';
require_once SLWL_INC . 'lang.inc.php';
require_once SLWL_INC . 'functions.inc.php';
require_once SLWL_INC . 'fun_sys.inc.php'; // 系统-模块
require_once SLWL_INC . 'fun_store.inc.php'; // 新版商城-模块
require_once SLWL_INC . 'fun_site.inc.php'; // 工地-模块
require_once SLWL_INC . 'fun_product.inc.php'; // 产品-模块
require_once SLWL_INC . 'fun_panorama.inc.php'; // 全景-模块
class Slwl_fitmentModuleWxapp extends WeModuleWxapp
{
	public function doPageSL_test()
	{
		global $_GPC, $_W;
	}

	// 获取系统设置
	public function doPageSmk_config()
	{
		sys_config();
	}

	// 幻灯片
	public function doPageSmk_adv()
	{
		global $_GPC, $_W;

		$uid = $_GPC['i'];

		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$psize = 10;

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_adv') . ' WHERE 1 '
			. $condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 文章列表，传分类ID
	// 必需传 tid=value
	public function doPageSmk_news()
	{
		global $_GPC, $_W;
		$uid = $_GPC['i'];

		$order_id = intval($_GPC['oid']); // 默认排序
		$order_price = intval($_GPC['pid']); // 价格排序

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$termid = max(0, intval($_GPC['tid'])); // 分类ID
		$condition = " AND uniacid=:uniacid AND enabled='1' AND termid=:termid ";
		$params = array(':uniacid' => $_W['uniacid'], ':termid'=>$termid);
		// $params = array(':uniacid' => $uid);

		$orderby = ' ORDER BY displayorder DESC, id DESC ';

		if ($order_id == '2') {
			$orderby = ' ORDER BY displayorder ASC, id ASC ';
		}

		if ($order_price == '1') {
			$orderby = ' ORDER BY newsmoney DESC ';
		} else if ($order_price == '2') {
			$orderby = ' ORDER BY newsmoney ASC ';
		}

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		$term = pdo_fetchall("SELECT id,termname FROM " . tablename('slwl_fitment_term')
			. " WHERE uniacid=:uniacid ORDER BY displayorder DESC, id DESC", array(":uniacid" => $_W['uniacid']));

		foreach ($list as $k => $v) {
			$list[$k]['newsmoney_format'] = $v['newsmoney']/100;
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		foreach ($list as $key => $value) {
			foreach ($term as $k => $v) {
				if ($value['termid'] == $v['id']) {
					$list[$key]['term_cn'] = $v['termname'];
				}
			}
		}

		foreach ($list as $k => $v) {
			$list[$k]['createtime_cn'] = date('Y-m-d H:i:s', $v['createtime']);
		}

		$oby = array('orderID'=>$order_id, 'orderPrice'=>$order_price);

		$res = array(
			'list' => $list,
			'orderby' => $oby
		);

		return $this->result(0, 'ok', $res);
	}

	// 文章列表，传分类标识
	// 必需传 flag=value
	public function doPageSmk_news_flag()
	{
		global $_GPC, $_W;
		$uid = $_GPC['i'];

		$term_flag = $_GPC['flag'];

		if (empty($term_flag)) {
			return $this->result(0, 'ok', array());
		}

		$term = pdo_fetchall("SELECT id,termname FROM " . tablename('slwl_fitment_term')
			. " WHERE uniacid=:uniacid AND term_flag=:term_flag ORDER BY displayorder DESC, id DESC",
			array(":uniacid" => $_W['uniacid'],':term_flag'=>$term_flag));

		if (empty($term)) {
			return $this->result(0, 'ok', array());
		}

		$flags = '';
		foreach ($term as $item) {
			$flags .= $item['id'] . ',';
		}
		$flags = substr($flags, 0, strlen($flags)-1);
		$where =' AND termid IN(' . $flags . ')';

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(5, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$condition .= $where;
		$params = array(':uniacid' => $_W['uniacid']);
		// $params = array(':uniacid' => $uid);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['money_format'] = $v['money'] / 100;
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		foreach ($list as $key => $value) {
			foreach ($term as $k => $v) {
				if ($value['termid'] == $v['id']) {
					$list[$key]['term_cn'] = $v['termname'];
				}
			}
		}

		foreach ($list as $k => $v) {
			$list[$k]['createtime_cn'] = date('Y-m-d H:i:s', $v['createtime']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取分类下第一篇文章
	public function doPageSmk_act_first()
	{
		global $_GPC, $_W;
		$uid = $_GPC['i'];

		$condition = " AND uniacid=:uniacid AND termid=:termid AND enabled='1' ";
		$termid = max(0, intval($_GPC['tid'])); // 分类ID
		$params = array(':uniacid' => $_W['uniacid'], 'termid' => $termid);
		// $params = array(':uniacid' => $uid, 'id' => $id);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit 1', $params);

		foreach ($list as $k => $v) {
			$list[$k]['createtime_cn'] = date('Y-m-d H:i:s', $v['createtime']);
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		foreach ($list as $k => $v) {
			$list[$k]['newsmoney_format'] = $v['newsmoney']/100;
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取分类
	public function doPageSmk_term()
	{
		global $_GPC, $_W;

		$psize = 8;
		$condition = " AND uniacid=:uniacid AND enabled='1' AND parentid='0' ";
		$params = array(':uniacid' => $_W['uniacid']);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
			. $condition . ' ORDER BY displayorder DESC, id ASC limit 0,' . $psize, $params);

		if ($list) {
			$one_psize = 100;
			$one_condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
			$one_params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$list[0]['id']);
			$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
				. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);

			foreach ($ones as $k => $v) {
				$ones[$k]['thumb_url'] = tomedia($v['thumb']);
			}

			$list[0]['ones'] = $ones;
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取指定分类下的子分类
	public function doPageSmk_term_child()
	{
		global $_GPC, $_W;
		$tid = $_GPC['tid'];

		$psize = 100;
		$condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
		$params = array(':uniacid' => $_W['uniacid'], ':parentid'=>$tid);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
			. $condition . ' ORDER BY displayorder DESC, id ASC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}



	// 获取推荐
	public function doPageSmk_news_recommand()
	{
		global $_GPC, $_W;

		$psize = 3;
		$condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		// $params = array(':uniacid' => $uid);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['newsmoney_format'] = $v['newsmoney']/100;
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取新品
	public function doPageSmk_news_new()
	{
		global $_GPC, $_W;

		$psize =  max(5, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' AND isnew='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		// $params = array(':uniacid' => $uid);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['newsmoney_format'] = $v['newsmoney']/100;
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 留言
	public function doPageSmk_guestbook()
	{
		global $_GPC, $_W;

		$user = $_GPC['user'];
		$tel = $_GPC['tel'];
		$msg = $_GPC['msg'];
		$formid = $_GPC['formid'];

		if (empty($user) || empty($tel)) {
			return $this->result(1, '输入项不能为空');
		}

		if (empty($msg)) {
			$msg = '用户留言为空';
		}

		$data = array();
		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $user,
			'contact' => $tel,
			'msg' => $msg,
			'addtime' => $_W['slwl']['datetime']['now']
		);
		$res = pdo_insert('slwl_fitment_guestbook', $data);

		include_once MODULE_ROOT . '/lib/smsfunctions.php';
		$rets = Aliyun\DySDKLite\Sms\send_sys_msg_admin($tel); // 发送管理员短信通知

		if ($res !== false) {
			return $this->result(0, '保存成功');
		} else {
			return $this->result(1, '操作失败');
		}
	}

	// 留言，配置
	public function doPageSmk_guestbook_config()
	{
		global $_GPC, $_W;

		if ($_W['slwl']['set']['guestbook_set_settings']) {
			$settings = $_W['slwl']['set']['guestbook_set_settings'];

			if ($settings) {
				// $settings['thumb_url'] = tomedia($settings['thumb']);
				$settings['thumb_post_url'] = tomedia($settings['thumb_post']);
			}
		}

		$res = array(
			'set'=>$settings,
		);

		return $this->result(0, 'ok', $res);
	}

	// 微信-创建用户
	public function doPageSmk_create_user()
	{
		global $_GPC, $_W;
		load()->func('communication');

		$code = $_GPC['code'];

		$account = uni_fetch($_W['uniacid']);

		$appid = $account['key'];
		$secret = $account['secret'];

		$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

		$resp = ihttp_request($url);
		$result = @json_decode($resp['content']);

		if (property_exists($result, 'openid')) {
			$openid = $result->openid;
			$session_key = $result->session_key;

			$rst = $this->save_session_key($session_key);
			if ($rst['return_code'] == '1') {
				return $this->result(1, '保存session_key出错');
			}

			$nickname = $_GPC['nicename'];
			if ($nickname) {
				$nickname = json_encode($nickname, JSON_UNESCAPED_UNICODE);
			}

			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $openid,
				'nicename' => $nickname,
				'data_ver' => '1',
				'avatar' => $_GPC['avatar'],
				'province' => $_GPC['province'],
				'city' => $_GPC['city'],
				'gender' => $_GPC['gender']
			);

			$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_users')
				. " where openid=:openid AND uniacid=:uniacid", array(":openid" => $openid, ":uniacid" => $_W['uniacid']));

			if (empty($one)) {
				$data['addtime'] = $_W['slwl']['datetime']['now'];
				pdo_insert('slwl_fitment_users', $data);
				$id = pdo_insertid();

				$data['id'] = $id;
				unset($data['openid']);

				$data_update = [
					'last_time'=>$_W['slwl']['datetime']['now'],
				];
				pdo_update('slwl_fitment_users', $data_update, ['id'=>$one['id']]); // 最后访问时间

				return $this->result(0, 'ok', $data);
			} else {
				$one['nicename'] = json_decode($one['nicename']);
				unset($one['openid']);

				$data_update = [
					'last_time'=>$_W['slwl']['datetime']['now'],
				];
				pdo_update('slwl_fitment_users', $data_update, ['id'=>$one['id']]); // 最后访问时间

				return $this->result(0, 'ok', $one);
			}
		} else {
			return $this->result(1, '操作失败1');
		}

	}

	// save_session_key
	private function save_session_key($session_key)
	{
		global $_GPC, $_W;

		if (!$session_key) {
			$rst = array(
				'return_code'=>'-999999',
				'return_msg'=>'session_key不存在',
			);
			return $rst;
		}

		$options = array(
			'session_key'=>$session_key,
		);

		$data = array();
		$data['setting_value'] = json_encode($options); // 压缩

		if ($_W['slwl']['set']['set_session_key']) {
			$where['uniacid'] = $_W['uniacid'];
			$where['setting_name'] = 'set_session_key';
			$rst = pdo_update('slwl_fitment_settings', $data, $where);
		} else {
			$data['uniacid'] = $_W['uniacid'];
			$data['setting_name'] = 'set_session_key';
			$data['addtime'] = $_W['slwl']['datetime']['now'];
			$rst = pdo_insert('slwl_fitment_settings', $data);
		}

		if ($rst !== false) {
			$data_bak = array(
				'return_code'=>'0',
				'return_msg'=>'ok',
			);
			return $data_bak;
		} else {
			$data_bak = array(
				'return_code'=>'1',
				'return_msg'=>'err',
			);
			return $data_bak;
		}
	}

	// order
	public function doPageSmk_order()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);
		$type = max(0, intval($_GPC['type']));

		$operation = $_GPC['op'] ? $_GPC['op'] : 'display';

		if ($operation == 'display') {
			$psize = 100;
			$condition = ' AND uniacid=:uniacid AND user=:user ';
			$params = array(':uniacid' => $_W['uniacid'], ':user'=>$uid);

			if ($type > 0) {
				$where = ' AND status = "{$type}" ';
			}

			$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_order') . ' WHERE 1 '
				. $condition . $where . '  ORDER BY id DESC limit 0,' . $psize, $params);

			foreach ($list as $k => $v) {
				// -1取消状态，0普通状态，1为已付款，2为已发货，3为成功
				if ($v['status'] == '1') {
					$list[$k]['status_cn'] = '已付款';
				} else if ($v['status'] == '2') {
					$list[$k]['status_cn'] = '已发货';
				} else if ($v['status'] == '3') {
					$list[$k]['status_cn'] = '已完成';
				} else if ($v['status'] == '-1') {
					$list[$k]['status_cn'] = '取消';
				} else {
					$list[$k]['status_cn'] = '未付款';
				}

				$list[$k]['money_format'] = $v['money'] / 100;
			}

			$res = array(
				'orders'=>$list,
				'type'=>$type
			);
			return $this->result(0, 'ok', $res);


		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);

			$condition = ' AND uniacid=:uniacid AND id=:id ';
			$params = array(':uniacid' => $_W['uniacid'], ':id'=>$id);
			$good = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 ' . $condition, $params);

			$data = array();
			$data = array(
				'uniacid' => $_W['uniacid'],
				'ordersn' => date('YmdHis') . random(4, 1),
				'user' => $uid,
				'good_id' => $id,
				'money' => $good['newsmoney'],
				'status' => '1',
				'addtime' => $_W['slwl']['datetime']['now']
			);
			$res = pdo_insert('slwl_fitment_order', $data);

			if ($res > 0) {
				return $this->result(0, 'ok');
			} else {
				return $this->result(1, '操作失败');
			}
		} else {
			return $this->result(1, 'err');
		}
	}

	// 首页 sp1
	public function doPageSmk_sp1()
	{
		global $_GPC, $_W;
		$termid = max(0, intval($_GPC['tid'])); // 分类ID

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(5, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$orderby = ' ORDER BY displayorder DESC, id DESC ';

		$fields = 'id,termid,name,isrecommand,money,money_hide,thumb,enabled,createtime,addtime';
		$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_page1') . ' WHERE 1 '
			. $condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取广告，只获取一条
	public function doPageSmk_adsp()
	{
		global $_GPC, $_W;
		$uid = $_GPC['i'];

		$psize = 1;
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_adsp') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取所有兄弟分类
	public function doPageSmk_list_nav()
	{
		global $_GPC, $_W;

		$tid = max(0, intval($_GPC['id'])); // 分类ID

		if ($tid == 0) {
			$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_term')
				. " where uniacid=:uniacid ORDER BY id ASC", array(":uniacid" => $_W['uniacid']));
		} else {
			$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_term')
				. " where id=:id AND uniacid=:uniacid", array(":id" => $tid, ":uniacid" => $_W['uniacid']));
		}

		$nav = array();
		if ($one) {
			$psize = 1000;

			if ($tid == 0) {
				$condition = " AND uniacid=:uniacid AND enabled='1' AND parentid<>'0' ";
				$params = array(':uniacid' => $_W['uniacid']);
			} else {
				$condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
				$params = array(':uniacid' => $_W['uniacid'], ':parentid'=>$one['parentid']);
			}

			$nav = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
				. $condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

			foreach ($nav as $k => $v) {
				$nav[$k]['thumb_url'] = tomedia($v['thumb']);
			}

			$list = array();
			if ($nav) {
				if ($tid == 0) {
					$list_psize = 10;
					$list_condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
					$list_params = array(':uniacid' => $_W['uniacid'], ':parentid'=>$nav[0]['id']);

					$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
						. $list_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $list_psize, $list_params);
				} else {
					$list_psize = 10;
					$list_condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
					$list_params = array(':uniacid' => $_W['uniacid'], ':parentid'=>$one['id']);

					$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
						. $list_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $list_psize, $list_params);
				}

				foreach ($list as $k => $v) {
					$list[$k]['thumb_url'] = tomedia($v['thumb']);
				}
			}
		}

		$rs = array(
			'nav'=>$nav,
			'list'=>$list,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 返回指定分类下的文章
	public function doPageSmk_list_list()
	{
		global $_GPC, $_W;

		$parentid = max(0, intval($_GPC['tid'])); // 分类ID

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$fields = 'id,parentid,name,subtitle,thumb,enabled,createtime,addtime';
		$orderby = ' ORDER BY displayorder DESC, id DESC ';
		if ($parentid>0) {
			$condition .= ' AND parentid=:parentid ';
			$params[':parentid'] = $parentid;
		}
		$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 '
			. $condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 攻略文章内容
	public function doPageSmk_act_one()
	{
		global $_GPC, $_W;

		$uid = $_GPC['i'];

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$id = intval($_GPC['aid']);
		$params = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_news') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
			$one['out_thumb_url'] = tomedia($one['out_thumb']);
		}

		$res = array(
			'one'=>$one
		);

		return $this->result(0, 'ok', $res);
	}

	// 组合广告
	public function doPageSmk_adgroup()
	{
		global $_GPC, $_W;

		$list = array();
		if ($_W['slwl']['set']['set_adgroup']) {
			$list = $_W['slwl']['set']['set_adgroup'];

			foreach ($list as $k => $v) {
				$list[$k]['attr_url'] = tomedia($v['attachment']);
			}
		}

		return $this->result(0, 'ok', $list);
	}

	// 广告文章内容
	public function doPageSmk_adact_one()
	{
		global $_GPC, $_W;

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$id = max(0, intval($_GPC['aid']));
		$params = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_adact') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
			$one['out_thumb_url'] = tomedia($one['out_thumb']);
		}

		$res = array(
			'one'=>$one
		);

		return $this->result(0, 'ok', $res);
	}

	// 获取 所有选项
	public function doPageSmk_pic_tag()
	{
		global $_GPC, $_W;

		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 1000;
		$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
			. $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
		$list_multi = pdo_fetchall($sql, $params);

		$condition_single = " AND uniacid=:uniacid AND enabled='1' ";
		$params_single = array(':uniacid' => $_W['uniacid']);
		$pindex_single = max(1, intval($_GPC['page']));
		$psize_single = 1000;
		$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
			. $condition_single . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex_single - 1) * $psize_single .',' .$psize_single;
		$list_single = pdo_fetchall($sql_single, $params_single);

		$rs = array(
			'multi'=>$list_multi,
			'single'=>$list_single,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 第一次获取图片
	public function doPageSmk_pic_list()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$condition_multi = " AND uniacid=:uniacid AND enabled='1' ";
		$params_multi = array(':uniacid' => $_W['uniacid']);
		$pindex_multi = max(1, intval($_GPC['page']));
		$psize_multi = 10;
		$sql_multi = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
			. $condition_multi . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_multi - 1) * $psize_multi .',' .$psize_multi;
		$list_multi = pdo_fetchall($sql_multi, $params_multi);

		$condition_single = " AND uniacid=:uniacid AND enabled='1' ";
		$params_single = array(':uniacid' => $_W['uniacid']);
		$pindex_single = max(1, intval($_GPC['page']));
		$psize_single = 10;
		$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 '
			. $condition_single . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_single - 1) * $psize_single .',' .$psize_single;
		$list_single = pdo_fetchall($sql_single, $params_single);

		foreach ($list_multi as $k => $v) {
			$list_multi[$k]['fav'] = '0';
			$list_multi[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		foreach ($list_single as $k => $v) {
			$list_single[$k]['fav'] = '0';
			$list_single[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		// 查收藏
		if ($list_multi) {
			$pic_ids_multi = '';
			foreach ($list_multi as $k=>$v) {
				$pic_ids_multi .= $v['id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND pic_id IN(' . $pic_ids_multi . ')';

			$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
			$condition_fav_multi .= $where_multi;
			$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 " . $condition_fav_multi, $params_fav_multi);

			foreach ($list_multi as $k => $v) {
				foreach ($ones_multi as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_multi[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		if ($list_single) {
			$pic_ids_single = '';
			foreach ($list_single as $k=>$v) {
				$pic_ids_single .= $v['id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND pic_id IN(' . $pic_ids_single . ')';

			$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
			$condition_fav_single .= $where_single;
			$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 " . $condition_fav_single, $params_fav_single);

			foreach ($list_single as $k => $v) {
				foreach ($ones_single as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_single[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		$rs = array(
			'multi'=>$list_multi,
			'single'=>$list_single,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 获取图片，套图
	public function doPageSmk_pic_play_multi()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']);
		$uid = intval($_GPC['uid']);

		$condition = ' AND uniacid=:uniacid AND id=:id ';
		$params = array(':uniacid' => $_W['uniacid'], ':id'=>$id);
		$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			$one['qrcode_url'] = tomedia($one['qrcode']);
			$smeta = json_decode($one['smeta'], true);

			foreach ($smeta as $k => $v) {
				$smeta[$k]['attr_url'] = tomedia($v['attachment']);
			}

			$one['smeta'] = $smeta;

			$condition_fav = " AND uniacid=:uniacid AND pic_id=:picid AND user=:user AND pic_type='0' ";
			$params_fav = array(':uniacid' => $_W['uniacid'], ':picid'=>$id, ':user'=>$uid);
			$one_fav = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . ' WHERE 1 ' . $condition_fav, $params_fav);

			if ($one_fav) {
				$one['fav'] = '1';
			} else {
				$one['fav'] = '0';
			}
		}

		pdo_query("UPDATE "  . tablename('slwl_fitment_pic_multi') . " set show_num=show_num+1 WHERE id=:id ", array(':id'=>$id));

		return $this->result(0, 'ok', $one);
	}

	// 获取图片，单图
	public function doPageSmk_pic_play_single()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']);
		$picsn = $_GPC['picsn'];
		$uid = intval($_GPC['uid']);

		if ($picsn) {
			$condition = ' AND uniacid=:uniacid AND picsn=:picsn ';
			$params = array(':uniacid' => $_W['uniacid'], ':picsn'=>$picsn);
			$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition, $params);
		} else {
			$condition = ' AND uniacid=:uniacid AND id=:id ';
			$params = array(':uniacid' => $_W['uniacid'], ':id'=>$id);
			$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition, $params);
		}

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
			$one['qrcode_url'] = tomedia($one['qrcode']);

			$condition_fav = " AND uniacid=:uniacid AND pic_id=:picid AND user=:user AND pic_type='1' ";
			$params_fav = array(':uniacid' => $_W['uniacid'], ':picid'=>$id, ':user'=>$uid);
			$one_fav = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . ' WHERE 1 ' . $condition_fav, $params_fav);

			if ($one_fav) {
				$one['fav'] = '1';
			} else {
				$one['fav'] = '0';
			}
		}

		pdo_query("UPDATE "  . tablename('slwl_fitment_pic_sole') . " set show_num=show_num+1 WHERE id=:id ", array(':id'=>$id));

		return $this->result(0, 'ok', $one);
	}

	// 获取套图，更多
	public function doPageSmk_pic_list_multi_more()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);
		$odr = max(0, intval($_GPC['odr'])); // 排序方式
		$options = $_GPC['ops']; // 选项

		$pindex = max(1, intval($_GPC['page']));
		$psize =  max(10, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$orderby = ' ORDER BY displayorder DESC, id DESC ';

		if ($odr == '1') {
			$orderby = ' ORDER BY fav_num DESC, id DESC ';
		} else if($odr == '2') {
			$orderby = ' ORDER BY show_num DESC, id DESC ';
		}

		if ($options) {
			$tmp_arr = explode(',', $options);
			foreach ($tmp_arr as $k => $v) {
				if ($v != '0') {
					$condition .= " AND OPTIONS LIKE :a{$k} ";
					$params[':a'.$k] = '%"'.$v.'"%';
				}
			}
		}

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_multi') . ' WHERE 1 '
			. $condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['fav'] = '0';
			$list[$k]['thumb_url'] = tomedia($v['thumb']);

			unset($list[$k]['options']);
			unset($list[$k]['smeta']);
		}

		// 查收藏
		if ($list) {
			$pic_ids_multi = '';
			foreach ($list as $k=>$v) {
				$pic_ids_multi .= $v['id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND pic_id IN(' . $pic_ids_multi . ')';

			$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
			$condition_fav_multi .= $where_multi;
			$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_multi, $params_fav_multi);

			foreach ($list as $k => $v) {
				foreach ($ones_multi as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取单图，更多
	public function doPageSmk_pic_list_single_more()
	{
		global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$odr = max(0, intval($_GPC['odr'])); // 排序方式
		$options = $_GPC['ops']; // 选项

		$pindex = max(1, intval($_GPC['page']));
		$psize =  max(10, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$orderby = ' ORDER BY displayorder DESC, id DESC ';

		if ($odr == '1') {
			$orderby = ' ORDER BY fav_num DESC, id DESC ';
		} else if($odr == '2') {
			$orderby = ' ORDER BY show_num DESC, id DESC ';
		}

		if ($options) {
			$tmp_arr = explode(',', $options);
			foreach ($tmp_arr as $k => $v) {
				if ($v != '0') {
					$condition .= " AND OPTIONS LIKE :a{$k} ";
					$params[':a'.$k] = '%"'.$v.'"%';
				}
			}
			// dump($condition);
			// dump($params);
		}

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_sole') . ' WHERE 1 '
			. $condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['fav'] = '0';
			$list[$k]['thumb_url'] = tomedia($v['thumb']);

			unset($list[$k]['options']);
			unset($list[$k]['smeta']);
		}

		// 查收藏
		if ($list) {
			$pic_ids_single = '';
			foreach ($list as $k=>$v) {
				$pic_ids_single .= $v['id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND pic_id IN(' . $pic_ids_single . ')';

			$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
			$condition_fav_single .= $where_single;
			$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_single, $params_fav_single);

			foreach ($list as $k => $v) {
				foreach ($ones_single as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		return $this->result(0, 'ok', $list);
	}

	// 获取图，通过PICSN
	public function doPageSmk_pic_play_picsn()
	{
		global $_GPC, $_W;

		$picsn = $_GPC['picsn'];
		$uid = intval($_GPC['uid']);

		if ($picsn) {
			$condition_sole = ' AND uniacid=:uniacid AND picsn=:picsn ';
			$params_sole = array(':uniacid' => $_W['uniacid'], ':picsn'=>$picsn);
			$one_sole = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition_sole, $params_sole);

			if (empty($one_sole)) {
				$condition_multi = ' AND uniacid=:uniacid AND picsn=:picsn ';
				$params_multi = array(':uniacid' => $_W['uniacid'], ':picsn'=>$picsn);
				$one_multi = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition_multi, $params_multi);
				$one = $one_multi;
				$one['pic_type'] = '0';

				$one['qrcode_url'] = tomedia($one['qrcode']);
				$smeta = json_decode($one['smeta'], true);

				if ($smeta) {
					foreach ($smeta as $k => $v) {
						$smeta[$k]['attr_url'] = tomedia($v['attachment']);
					}
				}

				$one['smeta'] = $smeta;
			} else {
				$one = $one_sole;
				$one['pic_type'] = '1';
				$one['thumb_url'] = tomedia($one['thumb']);
				$one['qrcode_url'] = tomedia($one['qrcode']);
			}

			$condition_fav = " AND uniacid=:uniacid AND pic_id=:picid AND user=:user AND pic_type='0' ";
			$params_fav = array(':uniacid' => $_W['uniacid'], ':picid'=>$one['id'], ':user'=>$uid);
			$one_fav = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . ' WHERE 1 ' . $condition_fav, $params_fav);

			if ($one_fav) {
				$one['fav'] = '1';
			} else {
				$one['fav'] = '0';
			}
		} else {
			return $this->result(1, '图片唯一ID不能为空');
		}

		if ($one) {
			return $this->result(0, 'ok', $one);
		} else {
			return $this->result(1, 'SN不存在');
		}
	}

	// 获取分享二维码
	public function doPageSmk_pic_build_qrcode()
	{
		global $_GPC, $_W;

		$picsn = $_GPC['picsn'];
		$uid = intval($_GPC['uid']);

		if ($picsn) {
			$condition_sole = ' AND uniacid=:uniacid AND picsn=:picsn ';
			$params_sole = array(':uniacid' => $_W['uniacid'], ':picsn'=>$picsn);
			$one_sole = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . ' WHERE 1 ' . $condition_sole, $params_sole);

			if (empty($one_sole)) {
				$condition_multi = ' AND uniacid=:uniacid AND picsn=:picsn ';
				$params_multi = array(':uniacid' => $_W['uniacid'], ':picsn'=>$picsn);
				$one_multi = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . ' WHERE 1 ' . $condition_multi, $params_multi);
				$one = $one_multi;
			} else {
				$one = $one_sole;
			}
		} else {
			return $this->result(1, '图片唯一ID不能为空');
		}

		if ($one) {
			if ($one['qrcode'] == '') {
				require_once MODULE_ROOT . "/lib/Common.class.php";
				$app = Common::get_app_info($_W['uniacid']);
				require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";
				$jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);
				$rets = $jssdk->qrcode_create($one['picsn']);

				if ($rets && $rets['errcode'] == 0) {
					if ($one_sole) {
						$rst_up_1 = pdo_update('slwl_fitment_pic_sole', array('qrcode' => $rets['data']), array('id' => $one_sole['id']));
					} else {
						$rst_up_2 = pdo_update('slwl_fitment_pic_multi', array('qrcode' => $rets['data']), array('id' => $one_multi['id']));
					}

					$data_bak = array(
						'qrcode'=>$rets['data'],
						'qrcode_url'=>tomedia($rets['data']),
					);
				} else {
					return $this->result(1, '生成失败');
				}
			}

			return $this->result(0, 'ok', $data_bak);
		} else {
			return $this->result(1, 'SN不存在');
		}
	}

	// defaut 页面，装修效果图
	public function doPageSmk_default_pcl()
	{
		global $_GPC, $_W;

		$condition = " AND uniacid=:uniacid AND enabled='1' AND parentid='0' AND isrecommand='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 3;
		$sql = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
			. $condition . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
		$list_multi = pdo_fetchall($sql, $params);

		$condition_single = " AND uniacid=:uniacid AND enabled='1' AND parentid=0 AND isrecommand='1' ";
		$params_single = array(':uniacid' => $_W['uniacid']);
		$pindex_single = max(1, intval($_GPC['page']));
		$psize_single = 3;
		$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
			. $condition_single . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex_single - 1) * $psize_single .',' .$psize_single;
		$list_single = pdo_fetchall($sql_single, $params_single);

		// 增加，套图=0 和 单图=1，标识
		foreach ($list_multi as $k => $v) {
			$list_multi[$k]['type'] = '0';
		}
		foreach ($list_single as $k => $v) {
			$list_single[$k]['type'] = '1';
		}

		$list = array();
		$l_num = 0;
		foreach ($list_multi as $k => $v) {
			if ($l_num >= 3) {
				break;
			}
			$list[] = $v;
			$l_num += 1;
		}
		foreach ($list_single as $k => $v) {
			if ($l_num >= 3) {
				break;
			}
			$list[] = $v;
			$l_num += 1;
		}

		if ($list) {
			$one_psize = 5;
			$one_condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' AND parentid=:parentid ";
			$one_params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$list[0]['id']);
			if ($list[0]['type'] == '0') {
				$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_multi_option') . ' WHERE 1 '
					. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);
			} else {
				$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_sole_option') . ' WHERE 1 '
					. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);
			}

			foreach ($ones as $k => $v) {
				$ones[$k]['thumb_url'] = tomedia($v['thumb']);

				if ($list[0]['type'] == '0') {
					$ones[$k]['type'] = '0';
				} else {
					$ones[$k]['type'] = '1';
				}
			}

			$list[0]['ones'] = $ones;
		}

		return $this->result(0, 'ok', $list);
	}
	// 获取指定 装修效果图ID,的子分类
	public function doPageSmk_default_pcl_child()
	{
		global $_GPC, $_W;
		$tid = intval($_GPC['tid']);
		$typeid = intval($_GPC['typeid']);

		$psize = 5;
		$condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' AND parentid=:parentid ";
		$params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$tid);
		if ($typeid == '0') {
			$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_multi_option') . ' WHERE 1 '
				. $condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);
		} else {
			$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_sole_option') . ' WHERE 1 '
				. $condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);
		}

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);

			if ($typeid == '0') {
				$list[$k]['type'] = '0';
			} else {
				$list[$k]['type'] = '1';
			}
		}

		return $this->result(0, 'ok', $list);
	}

	// 收藏, 添加或删除
	public function doPageSmk_set_fav()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);
		$picid = $_GPC['picid'];
		$typeid = max(-1, intval($_GPC['typeid']));

		if (empty($uid) || empty($picid) || $typeid<0) {
			return $this->result(1, '输入必需项为空');
		}

		$condition = " AND uniacid=:uniacid AND pic_type=:pic_type AND user=:user AND pic_id=:pic_id ";
		$params = array(':uniacid'=>$_W['uniacid'], ':pic_type'=>$typeid, ':user'=>$uid, ':pic_id'=>$picid);
		$one = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 " . $condition, $params);

		if (empty($one)) {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'user' => $uid,
				'pic_type' => $typeid,
				'pic_id' => $picid,
				'addtime' => $_W['slwl']['datetime']['now']
			);

			$res = pdo_insert('slwl_fitment_fav', $data);

			if ($typeid == '0') {
				pdo_query("UPDATE "  . tablename('slwl_fitment_pic_multi') . " set fav_num=fav_num+1 WHERE id=:id ", array(':id'=>$picid));
			} else {
				pdo_query("UPDATE "  . tablename('slwl_fitment_pic_sole') . " set fav_num=fav_num+1 WHERE id=:id ", array(':id' => $picid));
			}

			if ($res > 0) {
				return $this->result(0, 'ok', $res);
			} else {
				return $this->result(1, $_W['slwl']['lang']['tips_error']);
			}

		} else {
			$rst = pdo_delete('slwl_fitment_fav', array('id'=>$one['id']));
			if ($rst !== false) {
				return $this->result(0, '成功'); //删除收藏成功
			} else {
				return $this->result(1, $_W['slwl']['lang']['tips_not_exist_or_deleted']); //删除收藏成功
			}
		}
	}

	// 获取收藏列表
	public function doPageSmk_pic_list_fav()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
		$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
		$pindex_fav_multi = max(1, intval($_GPC['page']));
		$psize_fav_multi = 10;
		$sql_fav_multi = "SELECT * FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
			. $condition_fav_multi . " ORDER BY id DESC LIMIT " . ($pindex_fav_multi - 1) * $psize_fav_multi .',' .$psize_fav_multi;
		$list_fav_multi = pdo_fetchall($sql_fav_multi, $params_fav_multi);


		$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
		$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
		$pindex_fav_single = max(1, intval($_GPC['page']));
		$psize_fav_single = 10;
		$sql_fav_single = "SELECT * FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
			. $condition_fav_single . " ORDER BY id DESC LIMIT " . ($pindex_fav_single - 1) * $psize_fav_single .',' .$psize_fav_single;
		$list_fav_single = pdo_fetchall($sql_fav_single, $params_fav_single);

		// 查套图列表
		$list_multi = array();
		if ($list_fav_multi) {
			$pic_ids_multi = '';
			foreach ($list_fav_multi as $k=>$v) {
				$pic_ids_multi .= $v['pic_id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND id IN(' . $pic_ids_multi . ')';

			$condition_multi = " AND uniacid=:uniacid AND enabled='1' ";
			$condition_multi .= $where_multi;
			$params_multi = array(':uniacid'=>$_W['uniacid']);
			$list_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 "
				. $condition_multi, $params_multi);


			foreach ($list_multi as $k => $v) {
				$list_multi[$k]['fav'] = '1';
				$list_multi[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		$list_single = array();
		if ($list_fav_single) {
			$pic_ids_single = '';
			foreach ($list_fav_single as $k=>$v) {
				$pic_ids_single .= $v['pic_id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND id IN(' . $pic_ids_single . ')';

			$condition_single = " AND uniacid=:uniacid AND enabled='1' ";
			$condition_single .= $where_single;
			$params_single = array(':uniacid'=>$_W['uniacid']);
			$list_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . " where 1 "
				. $condition_single, $params_single);


			foreach ($list_single as $k => $v) {
				$list_single[$k]['fav'] = '1';
				$list_single[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		$rs = array(
			'multi'=>$list_multi,
			'single'=>$list_single,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 获取收藏列表, 套图 more
	public function doPageSmk_pic_list_fav_multi_more()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
		$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
		$pindex_fav_multi = max(1, intval($_GPC['page']));
		$psize_fav_multi = 10;
		$sql_fav_multi = "SELECT * FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
			. $condition_fav_multi . " ORDER BY id DESC LIMIT " . ($pindex_fav_multi - 1) * $psize_fav_multi .',' .$psize_fav_multi;
		$list_fav_multi = pdo_fetchall($sql_fav_multi, $params_fav_multi);

		// 查套图列表
		$list_multi = array();
		if ($list_fav_multi) {
			$pic_ids_multi = '';
			foreach ($list_fav_multi as $k=>$v) {
				$pic_ids_multi .= $v['pic_id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND id IN(' . $pic_ids_multi . ')';

			$condition_multi = " AND uniacid=:uniacid AND enabled='1' ";
			$condition_multi .= $where_multi;
			$params_multi = array(':uniacid'=>$_W['uniacid']);
			$list_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_multi') . " where 1 "
				. $condition_multi, $params_multi);


			foreach ($list_multi as $k => $v) {
				$list_multi[$k]['fav'] = '1';
				$list_multi[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		return $this->result(0, 'ok', $list_multi);
	}

	// 获取收藏列表, 套图 more
	public function doPageSmk_pic_list_fav_single_more()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
		$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
		$pindex_fav_single = max(1, intval($_GPC['page']));
		$psize_fav_single = 10;
		$sql_fav_single = "SELECT * FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 '
			. $condition_fav_single . " ORDER BY id DESC LIMIT " . ($pindex_fav_single - 1) * $psize_fav_single .',' .$psize_fav_single;
		$list_fav_single = pdo_fetchall($sql_fav_single, $params_fav_single);

		// 查套图列表
		$list_single = array();
		if ($list_fav_single) {
			$pic_ids_single = '';
			foreach ($list_fav_single as $k=>$v) {
				$pic_ids_single .= $v['pic_id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND id IN(' . $pic_ids_single . ')';

			$condition_single = " AND uniacid=:uniacid AND enabled='1' ";
			$condition_single .= $where_single;
			$params_single = array(':uniacid'=>$_W['uniacid']);
			$list_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_pic_sole') . " where 1 "
				. $condition_single, $params_single);

			foreach ($list_single as $k => $v) {
				$list_single[$k]['fav'] = '1';
				$list_single[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		return $this->result(0, 'ok', $list_single);
	}

	// 计算器,登记,step1
	public function doPageSmk_booking_step1()
	{
		global $_GPC, $_W;

		$user = $_GPC['uid'];
		$name = $_GPC['name'];
		$city = $_GPC['scity'];
		$area = $_GPC['sarea'];
		$housetype = $_GPC['shouse'];
		$tel = $_GPC['stel'];

		if (empty($user) || empty($city) || empty($area) || empty($housetype) || empty($tel)) {
			return $this->result(1, '输入项不能为空');
		}

		if ($_W['slwl']['set']['calc_set_settings']) {
			$settings = $_W['slwl']['set']['calc_set_settings'];

			if ($settings) {
				$min_price = 0; // 基础价格
				$square_price = 0; // 单价
				$style_cost_market = 0; // 设计费，现价
				$qt_cost_market = 0; // 质检费，现价

				if ($settings['min_price']) {
					$min_price = $settings['min_price'];
				}
				if ($settings['square_price']) {
					$square_price = $settings['square_price'];
				}
				if ($settings['style_cost_market']) {
					$style_cost_market = $settings['style_cost_market'];
				}
				if ($settings['qt_cost_market']) {
					$qt_cost_market = $settings['qt_cost_market'];
				}

				$sum = ($min_price + ($area * $square_price) + $style_cost_market + $qt_cost_market);
			}
		}

		$option1 = array(
			'city'=>$city,
			'area'=>$area,
			'housetype'=>$housetype,
		);

		$data = array(
			'uniacid' => $_W['uniacid'],
			'user' => $user,
			'name' => $name,
			'tel' => $tel,
			'money' => $sum,
			'option1' => json_encode($option1),
			'addtime' => $_W['slwl']['datetime']['now']
		);
		$res = pdo_insert('slwl_fitment_booking', $data);
		$id = pdo_insertid();

		$wan = round($sum / 10000, 1);

		include_once MODULE_ROOT . '/lib/smsfunctions.php';
		$rets = Aliyun\DySDKLite\Sms\send_sys_msg_admin($tel); // 发送管理员短信通知
		$rets_user = Aliyun\DySDKLite\Sms\send_user_msg($tel, $wan); // 发送用户短信通知

		if ($res > 0) {
			return $this->result(0, 'ok', $id);
		} else {
			return $this->result(1, '操作失败');
		}
	}

	// 计算器,登记,step1，再获取信息
	public function doPageSmk_booking_step1_get()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']);

		$settings = array();
		if ($_W['slwl']['set']['calc_set_settings']) {
			$settings = $_W['slwl']['set']['calc_set_settings'];

			if ($settings) {
				$settings['thumb_url'] = tomedia($settings['thumb']);

				if (empty($settings['stuff_cost_title'])) {
					$settings['stuff_cost_title'] = '材料费';
				}
				if (empty($settings['labour_cost_title'])) {
					$settings['labour_cost_title'] = '人工费';
				}
				if (empty($settings['style_cost_title'])) {
					$settings['style_cost_title'] = '设计费';
				}
				if (empty($settings['qt_cost_title'])) {
					$settings['qt_cost_title'] = '质检费';
				}
			}
		}

		$condition_one = ' AND uniacid=:uniacid AND id=:id ';
		$params_one = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT id,money FROM ' . tablename('slwl_fitment_booking') . ' WHERE 1 ' . $condition_one, $params_one);

		if ($one) {
			$one['money_format'] = round($one['money'] / 10000, 1);

			$settings['labour_cost_percent'] = round(($one['money'] * $settings['labour_cost']) / 100);
			$settings['stuff_cost_percent'] = round(($one['money'] * $settings['stuff_cost']) / 100);
		} else {
			$one = array();
		}

		$res = array(
			'set'=>$settings,
			'booking'=>$one,
		);

		return $this->result(0, 'ok', $res);
	}

	// 计算器,登记,step1
	public function doPageSmk_booking_step2()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']);
		$step2_ops = $_GPC['ops'];

		if (empty($id) || empty($step2_ops)) {
			return $this->result(1, '输入项不能为空');
		}

		$data = array(
			'option2' => $step2_ops,
		);
		$res = pdo_update('slwl_fitment_booking', $data, array('id' => $id));

		if ($res > 0) {
			return $this->result(0, 'ok', $res);
		} else {
			return $this->result(1, '操作失败');
		}
	}

	// calc，配置
	public function doPageSmk_calc_config()
	{
		global $_GPC, $_W;
		if ($_W['slwl']['set']['calc_set_settings']) {
			$settings = $_W['slwl']['set']['calc_set_settings'];

			if ($settings) {
				$settings['thumb_url'] = tomedia($settings['thumb']);
				// $settings['thumb_post_url'] = tomedia($settings['thumb_post']);

				if (empty($settings['stuff_cost_title'])) {
					$settings['stuff_cost_title'] = '材料费';
				}
				if (empty($settings['labour_cost_title'])) {
					$settings['labour_cost_title'] = '人工费';
				}
				if (empty($settings['style_cost_title'])) {
					$settings['style_cost_title'] = '设计费';
				}
				if (empty($settings['qt_cost_title'])) {
					$settings['qt_cost_title'] = '质检费';
				}
				if (empty($settings['unit'])) {
					$settings['unit'] = '平方';
				}
			}
		}

		$res = array(
			'set'=>$settings,
		);

		return $this->result(0, 'ok', $res);
	}

	// 找设计师
	public function doPageSmk_seek_designer()
	{
		global $_GPC, $_W;

		$psize = 10;
		$condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' ";
		$params = array(':uniacid' => $_W['uniacid']);

		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_designer') . ' WHERE 1 '
			. $condition . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 设计师列表，配置
	public function doPageSmk_stylist_config()
	{
		global $_GPC, $_W;

		if ($_W['slwl']['set']['designer_set_settings']) {
			$settings = $_W['slwl']['set']['designer_set_settings'];

			if ($settings) {
				$settings['thumb_post_url'] = tomedia($settings['thumb_post']);
			} else {
				$settings = array();
			}
		}

		$res = array(
			'set'=>$settings,
		);

		return $this->result(0, 'ok', $res);
	}

	// 设计师,列表
	public function doPageSmk_designer_list()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];

		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$sql = "SELECT * FROM " . tablename('slwl_fitment_designer') . ' WHERE 1 '
			. $condition . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
		$list = pdo_fetchall($sql, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 设计师,列表,单个
	public function doPageSmk_designer_one()
	{
		global $_GPC, $_W;

		$id = intval($_GPC['id']); // 设计师ID

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_designer') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
		}

		// 单图
		$condition_sole = " AND uniacid=:uniacid AND desid=:desid AND enabled='1' ";
		$params_sole = array(':uniacid' => $_W['uniacid'], ':desid' => $id);
		$sole_count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_sole') . " WHERE 1 "
			. $condition_sole, $params_sole);

		// 多图
		$condition_multi = " AND uniacid=:uniacid AND desid=:desid AND enabled='1' ";
		$params_multi = array(':uniacid' => $_W['uniacid'], ':desid' => $id);
		$multi_count = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_pic_multi') . " WHERE 1 "
			. $condition_multi, $params_multi);

		if (empty($sole_count)) {
			$sole_count = 0;
		}

		if (empty($multi_count)) {
			$multi_count = 0;
		}

		$sm_count = $sole_count + $multi_count;

		$res = array(
			'one'=>$one,
			'count'=>$sm_count,
		);

		return $this->result(0, 'ok', $res);
	}

	// 设计师作品列表
	public function doPageSmk_opus_list()
	{
		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$id = intval($_GPC['id']);

		if ($id == '0') {
			$id = '-1';
		}

		$condition_multi = " AND uniacid=:uniacid AND enabled='1' AND desid=:desid ";
		$params_multi = array(':uniacid' => $_W['uniacid'], ':desid'=>$id);
		$pindex_multi = max(1, intval($_GPC['page']));
		$psize_multi = 10;
		$sql_multi = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
			. $condition_multi . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_multi - 1) * $psize_multi .',' .$psize_multi;
		$list_multi = pdo_fetchall($sql_multi, $params_multi);

		$condition_single = " AND uniacid=:uniacid AND enabled='1' AND desid=:desid ";
		$params_single = array(':uniacid' => $_W['uniacid'], ':desid'=>$id);
		$pindex_single = max(1, intval($_GPC['page']));
		$psize_single = 10;
		$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 '
			. $condition_single . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_single - 1) * $psize_single .',' .$psize_single;
		$list_single = pdo_fetchall($sql_single, $params_single);

		if ($list_multi) {
			foreach ($list_multi as $k => $v) {
				$list_multi[$k]['fav'] = '0';
				$list_multi[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		if ($list_single) {
			foreach ($list_single as $k => $v) {
				$list_single[$k]['fav'] = '0';
				$list_single[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		// 属性
		$condition_single_option = " AND uniacid=:uniacid AND enabled='1' ";
		$params_single_option = array(':uniacid' => $_W['uniacid']);
		$pindex_single_option = max(1, intval($_GPC['page']));
		$psize_single_option = 1000;
		$sql_single_option = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
			. $condition_single_option . " ORDER BY displayorder DESC, id ASC LIMIT "
			. ($pindex_single_option - 1) * $psize_single_option .',' .$psize_single_option;
		$list_single_option = pdo_fetchall($sql_single_option, $params_single_option);

		$condition_multi_option = " AND uniacid=:uniacid AND enabled='1' ";
		$params_multi_option = array(':uniacid' => $_W['uniacid']);
		$pindex_multi_option = max(1, intval($_GPC['page']));
		$psize_multi_option = 1000;
		$sql_multi_option = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
			. $condition_multi_option . " ORDER BY displayorder DESC, id ASC LIMIT "
			. ($pindex_multi_option - 1) * $psize_multi_option .',' .$psize_multi_option;
		$list_multi_option = pdo_fetchall($sql_multi_option, $params_multi_option);

		if ($list_multi) {
			foreach ($list_multi as $k => $v) {
				$v_options = '';
				$v_options = json_decode($v['options'], true);

				if ($v_options['options']) {
					foreach ($v_options['options'] as $key => $value) {
						if ($list_multi_option) {
							foreach ($list_multi_option as $kk => $vv) {
								if ($value == $vv['id']) {
									$list_multi[$k]['options_cn'] .= $vv['name'] . '/';
									break;
								}
							}
						}
					}
				}

				$list_multi[$k]['options_cn'] = rtrim($list_multi[$k]['options_cn'], '/');
			}
		}

		if ($list_single) {
			foreach ($list_single as $k => $v) {
				$v_options = '';
				$v_options = json_decode($v['options'], true);

				if ($v_options['options']) {
					foreach ($v_options['options'] as $key => $value) {
						if ($list_single_option) {
							foreach ($list_single_option as $kk => $vv) {
								if ($value == $vv['id']) {
									$list_single[$k]['options_cn'] .= $vv['name'] . '/';
									break;
								}
							}
						}
					}
				}

				$list_single[$k]['options_cn'] = rtrim($list_single[$k]['options_cn'], '/');
			}
		}

		// 查收藏
		if ($list_multi) {
			$pic_ids_multi = '';
			foreach ($list_multi as $k=>$v) {
				$pic_ids_multi .= $v['id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND pic_id IN(' . $pic_ids_multi . ')';

			$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
			$condition_fav_multi .= $where_multi;
			$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_multi, $params_fav_multi);

			foreach ($list_multi as $k => $v) {
				foreach ($ones_multi as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_multi[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		if ($list_single) {
			$pic_ids_single = '';
			foreach ($list_single as $k=>$v) {
				$pic_ids_single .= $v['id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND pic_id IN(' . $pic_ids_single . ')';

			$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
			$condition_fav_single .= $where_single;
			$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_single, $params_fav_single);

			foreach ($list_single as $k => $v) {
				foreach ($ones_single as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_single[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		// 二合一
		$oplist = array();

		foreach ($list_multi as $k => $v) {
			$oplist[] = array(
				'id'=>$v['id'],
				'type'=>'0',
				'fav'=>$v['fav'],
				'fav_num'=>$v['fav_num'],
				'name'=>$v['name'],
				'thumb_url'=>$v['thumb_url'],
				'show_num'=>$v['show_num'],
				'options'=>$v['options'],
				'options_cn'=>$v['options_cn'],
			);
		}

		foreach ($list_single as $k => $v) {
			$oplist[] = array(
				'id'=>$v['id'],
				'type'=>'1',
				'fav'=>$v['fav'],
				'fav_num'=>$v['fav_num'],
				'name'=>$v['name'],
				'thumb_url'=>$v['thumb_url'],
				'show_num'=>$v['show_num'],
				'options'=>$v['options'],
				'options_cn'=>$v['options_cn'],
			);
		}

		$rs = array(
			'oplist'=>$oplist,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 在线预约，配置
	public function doPageSmk_reserve_config()
	{
		global $_GPC, $_W;

		if ($_W['slwl']['set']['reserve_set_settings']) {
			$settings = $_W['slwl']['set']['reserve_set_settings'];

			if ($settings) {
				if ($settings['reserve_money']) {
					$settings['reserve_money_format'] = $settings['reserve_money'] / 100;
				} else {
					$settings['reserve_money_format'] = 0;
				}
				$settings['thumb_post_url'] = tomedia($settings['thumb_post']);
			} else {
				$settings = array();
			}
		}

		$res = array(
			'set'=>$settings,
		);

		return $this->result(0, 'ok', $res);
	}

	// 在线预约
	public function doPageSmk_reserve_one()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];
		$user = $_GPC['user'];
		$tel = $_GPC['tel'];
		$msg = $_GPC['msg'];
		$retime = $_GPC['mydate'];
		$formid = $_GPC['formid'];

		if (empty($user) || empty($tel) || empty($msg)) {
			return $this->result(1, '输入项不能为空');
		}

		$reserver_money = 0;

		if ($_W['slwl']['set']['reserve_set_settings']) {
			$settings = $_W['slwl']['set']['reserve_set_settings'];

			if ($settings['reserve_money']) {
				$reserver_money = $settings['reserve_money'];
			}
		}

		$data = array();
		$data = array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid,
			'name' => $user,
			'tel' => $tel,
			'msg' => $msg,
			'retime' => $retime,
			'money' => $reserver_money,
			'addtime' => $_W['slwl']['datetime']['now']
		);
		$res = pdo_insert('slwl_fitment_reserve', $data);

		include_once MODULE_ROOT . '/lib/smsfunctions.php';
		$rets = Aliyun\DySDKLite\Sms\send_sys_msg_admin($tel); // 发送管理员短信通知


		require_once MODULE_ROOT . "/lib/Common.class.php";
		require_once MODULE_ROOT . "/lib/jssdk/jssdk.php";

		$app = Common::get_app_info($_W['uniacid']);
		$jssdk = new JSSDK($app['appid'], $app['secret'], 'token_name_'.$_W['uniacid']);

		$openid = get_openid($uid); // 获取 openid
		$template_id = get_template_id($uid); // 获取 openid
		$send_data = array(
			'openid'=>$openid,
			'template_id'=>$template_id,
			'form_id'=>$formid,
			'name'=>$user,
			'mobile'=>$tel,
		);
		if ($template_id != 'NONE') {
			$result = $jssdk->templates_send_client($send_data); // 发送客户小程序 模板消息

			$result['data'] = $send_data;
			@putlog('发送模板消息', $result);
		}

		if ($res > 0) {
			return $this->result(0, 'ok', $res);
		} else {
			return $this->result(1, '操作失败');
		}
	}

	// 支付
	public function doPageSmk_reserve_pay()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];

		require_once MODULE_ROOT . "/lib/Common.class.php";
		require_once MODULE_ROOT . "/lib/wxpay/MyPay.php";

		$app = Common::get_app_info($_W['uniacid']);

		$openid = get_openid($uid); // 获取 openid

		$reserver_money = 0;
		if ($_W['slwl']['set']['reserve_set_settings']) {
			$settings = $_W['slwl']['set']['reserve_set_settings'];

			if ($settings['reserve_money']) {
				$reserver_money = $settings['reserve_money'];
			}
		}

		define('MY_APPID', $app['appid']);
		define('MY_SECRET', $app['secret']);
		define('MY_MCHID', $app['mchid']);
		define('MY_SIGNKEY', $app['signkey']);

		// 统一下单
		$jsApiParameters = Common::run_pay($openid, '在线预约', $reserver_money);
		if ($jsApiParameters['return_code']=='SUCCESS') {
			return $this->result(0, 'ok', json_decode($jsApiParameters['return_msg']));
		} else {
			return $this->result(1, 'err-'.$jsApiParameters['return_msg']);
		}
	}

	// 小程序端，订单列表
	public function doPageSmk_relist()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];

		$condition = ' AND uniacid=:uniacid AND uid=:uid ';
		$params = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$sql = "SELECT * FROM " . tablename('slwl_fitment_reserve') . ' WHERE 1 '
			. $condition . " ORDER BY status ASC, id DESC LIMIT " . ($pindex - 1) * $psize .',' .$psize;
		$list = pdo_fetchall($sql, $params);

		foreach ($list as $k => $v) {
			if ($v['money'] > 0) {
				$list[$k]['money_format'] = $v['money'] / 100;
			} else {
				$list[$k]['money_format'] = 0;
			}

			if ($v['status']=='1') {
				$list[$k]['status_cn'] = '已处理';
			} else {
				$list[$k]['status_cn'] = '待处理';
			}
		}

		return $this->result(0, 'ok', $list);
	}

	// 首页，setPageData
	public function doPageSmk_IndexData()
	{
		global $_GPC, $_W;

		// adv
		if ($_W['slwl']['set']['default_set_settings']['banner_show']) {
			$psize_adv = 10;
			$condition_adv = " AND uniacid=:uniacid AND enabled='1' ";
			$params_adv = array(':uniacid' => $_W['uniacid']);

			$adv_list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_adv') . ' WHERE 1 '
				. $condition_adv . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize_adv, $params_adv);
			foreach ($adv_list as $k => $v) {
				$adv_list[$k]['thumb_url'] = tomedia($v['thumb']);
			}

			$res['adv'] = $adv_list;
		} else {
			$res['adv'] = array();
		}

		// nav
		if ($_W['slwl']['set']['set_buttons']) {
			$bs_list = $_W['slwl']['set']['set_buttons'];

			if ($bs_list['items']) {
				foreach ($bs_list['items'] as $k => $v) {
					$bs_list['items'][$k]['thumb_url'] = tomedia($v['attachment']);
				}
			}
			if (empty($bs_list['rownum'])) {
				$bs_list['rownum'] == '4';
			} else {
				if ($bs_list['rownum'] < 3 || $bs_list['rownum'] > 5) {
					$bs_list['rownum'] = '4';
				}
			}
		} else {
			$bs_list = array(
				'itmes' => array(),
				'rownum' => 4,
			);
		}
		$res['nav'] = $bs_list;


		// 组合广告
		if ($_W['slwl']['set']['default_set_settings']['adgroup_show']) {
			$adg_list = array();
			if ($_W['slwl']['set']['set_adgroup']) {
				$adg_list = $_W['slwl']['set']['set_adgroup'];

				foreach ($adg_list as $k => $v) {
					$adg_list[$k]['attr_url'] = tomedia($v['attachment']);
				}
			}
			$res['adgroup'] = $adg_list;
		} else {
			$res['adgroup'] = array();
		}


		// adsp,获取广告，获取 10 条
		if ($_W['slwl']['set']['default_set_settings']['adsp_show']) {
			$psize_adsp = 10;
			$condition_adsp = " AND uniacid=:uniacid AND enabled='1' ";
			$params_adsp = array(':uniacid' => $_W['uniacid']);

			$list_adsp = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_adsp') . ' WHERE 1 '
				. $condition_adsp . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize_adsp, $params_adsp);

			foreach ($list_adsp as $k => $v) {
				$list_adsp[$k]['thumb_url'] = tomedia($v['thumb']);
			}
			if ($list_adsp) {
				$res['adsp'] = $list_adsp;
			} else {
				$res['adsp'] = array();
			}
		} else {
			$res['adsp'] = array();
		}


		// 找设计师
		if ($_W['slwl']['set']['default_set_settings']['titledf3_show']) {
			$psize_seekdes = 10;
			$condition_seekdes = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' ";
			$params_seekdes = array(':uniacid' => $_W['uniacid']);

			$seekdes_list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_designer') . ' WHERE 1 '
				. $condition_seekdes . '  ORDER BY displayorder DESC, id DESC limit 0,' . $psize_seekdes, $params_seekdes);

			foreach ($seekdes_list as $k => $v) {
				$seekdes_list[$k]['thumb_url'] = tomedia($v['thumb']);
			}

			$res['seekdesinger'] = $seekdes_list;
		} else {
			$res['seekdesinger'] = array();
		}


		// 装修效果图
		if ($_W['slwl']['set']['default_set_settings']['titledf1_show']) {
			$condition_multi = " AND uniacid=:uniacid AND enabled='1' AND parentid='0' AND isrecommand='1' ";
			$params_multi = array(':uniacid' => $_W['uniacid']);
			$pindex_multi = max(1, intval($_GPC['page']));
			$psize_multi = 3;
			$sql_multi = "SELECT * FROM " . tablename('slwl_fitment_pic_multi_option'). ' WHERE 1 '
				. $condition_multi . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex_multi - 1) * $psize_multi .',' .$psize_multi;
			$list_multi = pdo_fetchall($sql_multi, $params_multi);

			$condition_single = " AND uniacid=:uniacid AND enabled='1' AND parentid='0' AND isrecommand='1' ";
			$params_single = array(':uniacid' => $_W['uniacid']);
			$pindex_single = max(1, intval($_GPC['page']));
			$psize_single = 3;
			$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole_option'). ' WHERE 1 '
				. $condition_single . " ORDER BY displayorder DESC, id ASC LIMIT " . ($pindex_single - 1) * $psize_single .',' .$psize_single;
			$list_single = pdo_fetchall($sql_single, $params_single);

			// 增加，套图=0 和 单图=1，标识
			foreach ($list_multi as $k => $v) {
				$list_multi[$k]['type'] = '0';
			}
			foreach ($list_single as $k => $v) {
				$list_single[$k]['type'] = '1';
			}

			$pcl_list = array();
			$l_num = 0;
			foreach ($list_multi as $k => $v) {
				if ($l_num >= 3) {
					break;
				}
				$pcl_list[] = $v;
				$l_num += 1;
			}
			foreach ($list_single as $k => $v) {
				if ($l_num >= 3) {
					break;
				}
				$pcl_list[] = $v;
				$l_num += 1;
			}

			if ($pcl_list) {
				$one_psize = 5;
				$one_condition = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' AND parentid=:parentid ";
				$one_params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$pcl_list[0]['id']);
				if ($pcl_list[0]['type'] == '0') {
					$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_multi_option') . ' WHERE 1 '
						. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);
				} else {
					$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_pic_sole_option') . ' WHERE 1 '
						. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);
				}

				foreach ($ones as $k => $v) {
					$ones[$k]['thumb_url'] = tomedia($v['thumb']);

					if ($pcl_list[0]['type'] == '0') {
						$ones[$k]['type'] = '0';
					} else {
						$ones[$k]['type'] = '1';
					}
				}

				$pcl_list[0]['ones'] = $ones;
			}
			$res['pcl'] = $pcl_list;
		} else {
			$res['pcl'] = [];
		}


		// 装修攻略
		if ($_W['slwl']['set']['default_set_settings']['titledf2_show']) {
			$psize_act = 8;
			$condition_act = " AND uniacid=:uniacid AND enabled='1' AND parentid=0 ";
			$params_act = array(':uniacid' => $_W['uniacid']);

			$act_list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
				. $condition_act . ' ORDER BY displayorder DESC, id ASC limit 0,' . $psize_act, $params_act);

			if ($act_list) {
				$one_psize = 100;
				$one_condition = " AND uniacid=:uniacid AND enabled='1' AND parentid=:parentid ";
				$one_params = array(':uniacid'=>$_W['uniacid'], ':parentid'=>$act_list[0]['id']);
				$ones = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_term') . ' WHERE 1 '
					. $one_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $one_psize, $one_params);

				foreach ($ones as $k => $v) {
					$ones[$k]['thumb_url'] = tomedia($v['thumb']);
				}

				$act_list[0]['ones'] = $ones;
			}
			$res['actlist'] = $act_list;
		} else {
			$res['actlist'] = array();
		}


		// 新闻列表-1
		if ($_W['slwl']['set']['default_set_settings']['actnews1_show']) {
			$psize_term_1 = 5;
			$condition_term_1 = " AND uniacid=:uniacid AND enabled='1' AND isrecommand='1' ";
			$params_term_1 = array(':uniacid' => $_W['uniacid']);
			$term_list_1 = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_act_term') . ' WHERE 1  '
				. $condition_term_1 . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize_term_1, $params_term_1);

			$list_1 = array();
			if ($term_list_1) {
				foreach ($term_list_1 as $k => $v) {
					$psize_alist_1 = 10;
					$condition_alist_1 = " AND uniacid=:uniacid AND termid=:termid AND enabled='1' ";
					$params_alist_1 = array(':uniacid' => $_W['uniacid'], 'termid' => $v['id']);

					$fields = 'id,termid,name,subtitle,thumb,createtime,displayorder';
					$news_alist_1 = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 '
						. $condition_alist_1 . '  ORDER BY displayorder DESC, id DESC limit 0, ' . $psize_alist_1, $params_alist_1);

					foreach ($news_alist_1 as $key => $value) {
						$news_alist_1[$key]['createtime_cn'] = date('Y-m-d H:i:s', $value['createtime']);
						$news_alist_1[$key]['thumb_url'] = tomedia($value['thumb']);
					}

					$term_list_1[$k]['list'] = $news_alist_1;
				}

				$list_1 = $term_list_1;
			}
			$res['newslist1'] = $list_1;
		} else {
			$res['newslist1'] = array();
		}


		// 新闻列表-2
		if ($_W['slwl']['set']['default_set_settings']['actnews2_show']) {
			// 查所有分类
			$fields = ['id','termname','thumb'];
			$where_list_2 = [
				'uniacid' => $_W['uniacid'],
				'enabled' => 1,
			];
			$term_list_2 = pdo_getall(sl_table_name('act_term'), $where_list_2, $fields);

			// 查文章
			if ($term_list_2) {
				$ids_term = sl_array_column($term_list_2, 'id');
				$where_act_2 = [
					'uniacid' => $_W['uniacid'],
					'enabled' => 1,
					'termid IN' => $ids_term,
				];
				$order_by = ['displayorder DESC, id DESC'];
				$limit = [10];
				$list_list_2 = pdo_getall(sl_table_name('act_news'), $where_act_2, '', '', $order_by, $limit);

				if ($list_list_2) {
					foreach ($list_list_2 as $key => $value) {
						foreach ($term_list_2 as $k => $v) {
							if ($value['termid'] == $v['id']) {
								$list_list_2[$key]['term_cn'] = $v['termname'];
								break;
							}
						}
						$list_list_2[$key]['thumb_url'] = tomedia($value['thumb']);
					}
				}
			}

			$term_style = $_W['slwl']['set']['default_set_settings']['term_style'];
			$tmp_term = array(
				'termname' => $_W['slwl']['set']['default_set_settings']['title_actnews2'],
				'term_style' => $term_style?$term_style:'0',
				'list' => $list_list_2,
			);
			$res['newslist2'] = $tmp_term;
		} else {
			$res['newslist2'] = array();
		}

		// 工地模块
		$res['site'] = array(
			'subdistrict' => array(),
			'worksite' => array(),
			'total' => '0',
		);

		if ($_W['slwl']['set']['default_set_settings']['site_show']) {

			// 工地模块-小区 3个subdistrict
			$condition_subdistrict = " AND uniacid=:uniacid AND isrecommand='1' ";
			$params_subdistrict = array(':uniacid' => $_W['uniacid']);
			$pindex_subdistrict = max(1, intval($_GPC['page']));
			$psize_subdistrict = 3;

			$select_child = "(SELECT COUNT(*) FROM ". tablename('slwl_fitment_site_list').
				" AS sfsl WHERE sfsl.site_id = sfsm.id ) AS site_total";

			$sql_subdistrict = "SELECT sfsm.*,". $select_child ." FROM " . tablename('slwl_fitment_site_manage'). " AS sfsm WHERE 1 "
				. $condition_subdistrict . " ORDER BY displayorder DESC, id DESC LIMIT "
				. ($pindex_subdistrict - 1) * $psize_subdistrict . ',' .$psize_subdistrict;
			$list_subdistrict = pdo_fetchall($sql_subdistrict, $params_subdistrict);

			if ($list_subdistrict) {
				foreach ($list_subdistrict as $k => $v) {
					$list_subdistrict[$k]['thumb_url'] = $v['thumb'];

					if ($v['coordinate']) {
						$tmp_coordinate = json_decode($v['coordinate'], true);
						$list_subdistrict[$k]['coordinate_format'] = $tmp_coordinate;
					}
				}
				$res['site']['subdistrict'] = $list_subdistrict;
			}


			// 工地模块-所有工地
			$condition_worksite = " AND uniacid=:uniacid AND enabled='1' ";
			$params_worksite = array(':uniacid' => $_W['uniacid']);
			$pindex_worksite = max(1, intval($_GPC['page']));
			$psize_worksite = 10;
			$sql_worksite = "SELECT * FROM " . tablename('slwl_fitment_site_list') . ' WHERE 1 '
				. $condition_worksite . " ORDER BY displayorder DESC, id DESC LIMIT "
				. ($pindex_worksite - 1) * $psize_worksite . ',' .$psize_worksite;
			$list_worksite = pdo_fetchall($sql_worksite, $params_worksite);
			$total_worksite = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('slwl_fitment_site_list'). ' WHERE 1 '
				. $condition_worksite, $params_worksite);

			if ($list_worksite) {
				foreach ($list_worksite as $k => $v) {
					$list_worksite[$k]['thumb_url'] = $v['thumb'];

					if ($v['coordinate']) {
						$tmp_coordinate = json_decode($v['coordinate'], true);
						$list_worksite[$k]['coordinate_format'] = $tmp_coordinate;
					}

					$list_worksite[$k]['options_format'] = array();
					if ($list_worksite[$k]['options']) {
						$tmp_options = json_decode($list_worksite[$k]['options'], true);
						if ($tmp_options) {
							$list_worksite[$k]['options_format'] = $tmp_options;
						}
					}
				}
				$res['site']['worksite'] = $list_worksite;
				$res['site']['total'] = $total_worksite;
			}
		}

		return $this->result(0, 'ok', $res);
	}

	// 普通文章内容
	public function doPageSmk_actnews_one()
	{
		global $_GPC, $_W;

		$uid = $_GPC['i'];

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$id = intval($_GPC['aid']);
		$params = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			$one['thumb_url'] = tomedia($one['thumb']);
			$one['out_thumb_url'] = tomedia($one['out_thumb']);
			$one['createtime_cn'] = date('Y-m-d H:i:s', $one['createtime']);
		}

		$res = array(
			'one'=>$one
		);

		return $this->result(0, 'ok', $res);
	}

	// 文章列表，传分类ID
	// 必需传 tid=value
	public function doPageSmk_act_news()
	{
		global $_GPC, $_W;

		$uid = $_GPC['i'];

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$termid = max(0, intval($_GPC['tid'])); // 分类ID
		$condition = " AND uniacid=:uniacid AND enabled='1' AND termid=:termid ";
		$params = array(':uniacid' => $_W['uniacid'], ':termid'=>$termid);

		$fields = 'id,termid,name,subtitle,thumb,createtime,displayorder';
		$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 '
			.$condition . '  ORDER BY displayorder DESC, id DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		$term = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_act_term')
			. " WHERE uniacid=:uniacid ORDER BY displayorder DESC, id DESC", array(":uniacid" => $_W['uniacid']));

		foreach ($list as $key => $value) {
			foreach ($term as $k => $v) {
				if ($value['termid'] == $v['id']) {
					$list[$key]['term_cn'] = $v['termname'];
					$list[$key]['term_url'] = tomedia($v['thumb']);
				}
			}
		}

		foreach ($list as $k => $v) {
			$list[$k]['createtime_cn'] = date('Y-m-d H:i:s', $v['createtime']);
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 新接口-文章列表，传分类ID
	// 必需传 tid=value
	public function doPageAct_news()
	{
		global $_GPC, $_W;

		$uid = $_GPC['i'];

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$termid = max(0, intval($_GPC['tid'])); // 分类ID
		$condition = " AND uniacid=:uniacid AND enabled='1' AND termid=:termid ";
		$params = array(':uniacid' => $_W['uniacid'], ':termid'=>$termid);

		$fields = 'id,termid,name,subtitle,thumb,createtime,displayorder';
		$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 '
			.$condition . '  ORDER BY displayorder DESC, id DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		$term = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_act_term')
			. " WHERE uniacid=:uniacid ORDER BY displayorder DESC, id DESC", array(":uniacid" => $_W['uniacid']));

		$top_term = array();
		foreach ($list as $key => $value) {
			if (empty($top_term)) {
				foreach ($term as $k => $v) {
					if ($value['termid'] == $v['id']) {
						$top_term = $v;

						$top_term['thumb_url'] = tomedia($top_term['thumb']);
						break;
					}
				}
			}
		}

		foreach ($list as $k => $v) {
			$list[$k]['term_cn'] = $top_term['termname'];
			$list[$k]['createtime_cn'] = date('Y-m-d H:i:s', $v['createtime']);
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		// 轮播图
		$condition_banner = " AND uniacid=:uniacid ";
		$params_banner = array(':uniacid' => $_W['uniacid']);
		$pindex_banner = max(1, intval($_GPC['page']));
		$psize_banner = 5;
		$sql_banner = "SELECT * FROM " . tablename('slwl_fitment_act_term_adv'). ' WHERE 1 '
			.$condition_banner . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_banner - 1) * $psize_banner .',' .$psize_banner;

		$list_banner = pdo_fetchall($sql_banner, $params_banner);

		foreach ($list_banner as $k => $v) {
			$list_banner[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		$data_bak = array(
			'list'=>$list,
			'term'=>$top_term,
			'banner'=>$list_banner,
		);

		return $this->result(0, 'ok', $data_bak);
	}

	// 获取所有兄弟分类
	public function doPageSmk_act_list_nav()
	{
		global $_GPC, $_W;

		$tid = max(0, intval($_GPC['id'])); // 分类ID

		$nav = array();
		$psize = 100;
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);

		$nav = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_act_term') . ' WHERE 1 '
			.$condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $psize, $params);

		foreach ($nav as $k => $v) {
			$nav[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		$list = array();
		if ($nav) {
			$array_array = count($nav) - 1;
			$list_psize = 10;
			$list_condition = " AND uniacid=:uniacid AND enabled='1' AND termid=:termid ";
			$list_params = array(':uniacid' => $_W['uniacid'], ':termid'=>$nav[$array_array]['id']);

			$fields = 'id,name,subtitle,thumb,enabled,createtime,addtime';
			$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 '
				.$list_condition . ' ORDER BY displayorder DESC, id DESC limit 0,' . $list_psize, $list_params);

			foreach ($list as $k => $v) {
				$list[$k]['thumb_url'] = tomedia($v['thumb']);
			}
		}

		$condition_banner = " AND uniacid=:uniacid ";
		$params_banner = array(':uniacid' => $_W['uniacid']);
		$pindex_banner = max(1, intval($_GPC['page']));
		$psize_banner = 5;
		$sql_banner = "SELECT * FROM " . tablename('slwl_fitment_act_term_adv'). ' WHERE 1 '
			.$condition_banner . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_banner - 1) * $psize_banner .',' .$psize_banner;

		$list_banner = pdo_fetchall($sql_banner, $params_banner);

		foreach ($list_banner as $k => $v) {
			$list_banner[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		$rs = array(
			'nav'=>$nav,
			'list'=>$list,
			'banner'=>$list_banner,
		);

		return $this->result(0, 'ok', $rs);
	}

	// 返回指定分类下的文章
	public function doPageSmk_act_list_list()
	{
		global $_GPC, $_W;

		$parentid = max(0, intval($_GPC['tid'])); // 分类ID

		$page = ((empty($_GPC['page']) ? '' : $_GPC['page']));
		$pindex = max(1, intval($page));
		$psize =  max(10, intval($_GPC['psize']));
		$condition = " AND uniacid=:uniacid AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid']);
		$fields = ' id,displayorder,uniacid,termid,name,subtitle,thumb,createtime,out_thumb,out_link,enabled,addtime ';
		$orderby = ' ORDER BY displayorder DESC, id DESC ';
		if ($parentid>0) {
			$condition .= ' AND termid=:termid ';
			$params[':termid'] = $parentid;
		}
		$list = pdo_fetchall('SELECT '. $fields .' FROM ' . tablename('slwl_fitment_act_news') . ' WHERE 1 '
			.$condition . $orderby . ' limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

		foreach ($list as $k => $v) {
			$list[$k]['thumb_url'] = tomedia($v['thumb']);
			unset($list[$k]['detail']);
		}

		return $this->result(0, 'ok', $list);
	}

	// 免费设计，配置
	public function doPageSmk_style_config()
	{
		global $_GPC, $_W;

		if ($_W['slwl']['set']['style_set_settings']) {
			$settings = $_W['slwl']['set']['style_set_settings'];

			if ($settings) {
				$settings['thumb_post_url'] = tomedia($settings['thumb_post']);
			}
		}

		$res = array(
			'set'=>$settings,
		);

		return $this->result(0, 'ok', $res);
	}

	// 免费设计
	public function doPageSmk_style_post()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);
		$user = $_GPC['user'];
		$tel = $_GPC['tel'];
		$formid = $_GPC['formid'];

		if (empty($user) || empty($tel)) {
			return $this->result(1, '输入项不能为空');
		}

		$data = array(
			'uniacid' => $_W['uniacid'],
			'name' => $user,
			'contact' => $tel,
			'addtime' => $_W['slwl']['datetime']['now']
		);
		$res = pdo_insert('slwl_fitment_style', $data);

		include_once MODULE_ROOT . '/lib/smsfunctions.php';
		$rets = Aliyun\DySDKLite\Sms\send_sys_msg_admin($tel); // 发送管理员短信通知

		if ($res > 0) {
			return $this->result(0, 'ok', $res);
		} else {
			return $this->result(1, '操作失败');
		}
	}

	// 获取adpop
	public function doPageSmk_get_adpop()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];
		$pflag = $_GPC['pflag'];

		// 用户信息
		$condition_user = " AND uniacid=:uniacid AND id=:id ";
		$params_user = array(':uniacid' => $_W['uniacid'], ':id' => $uid);
		$one_user = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition_user, $params_user);

		if ($one_user) {
			// 弹窗
			$settings['adpop_show'] = 0;

			if ($_W['slwl']['set']['adpop_set_settings']) {
				$res_tmp_adpop = $_W['slwl']['set']['adpop_set_settings'];

				if ($res_tmp_adpop) {
					$res_tmp_adpop['thumb_url'] = tomedia($res_tmp_adpop['thumb']);
				}

				// 弹窗频率
				$adpop_space = intval(24 / $res_tmp_adpop['adpop_often']);
				// 上一次弹窗
				$last_adpop_time = strtotime($one_user['last_adpop']);
				// 间隔
				$space_time = $last_adpop_time + ($adpop_space * 60 * 60);

				if ($space_time > time()) {
					// dump($space_time);
					// dump(time());
					$res_tmp_adpop['adpop_show'] = '0';
				} else {
					$data = array(
						'last_adpop'=>$_W['slwl']['datetime']['now'],
					);
					pdo_update('slwl_fitment_users', $data, array('id' => $uid));
				}

				$settings = $res_tmp_adpop;
			} else {
				$settings = array(
					'adpop_show' => '0',
				);
			}
		} else {
			$settings['adpop_show'] = '0';
		}

		return $this->result(0, 'ok', $settings);
	}

	// 搜索图库
	public function doPageSL_pic_search()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];
		$search_type = $_GPC['type']; // 搜索类型 0=单图,1=多图,2=工地,3=产品,4=全景

		if (empty($uid)) {
			return $this->result(1, '用户ID不能为空');
		}

		if (empty($search_type)) {
			$list_multi = $this->SL_pic_search_multi();
			$list_single = $this->SL_pic_search_single();
			$list_panorama = $this->SL_pic_search_panorama();
			$data_bak = array(
				'multi'=>$list_multi,
				'single'=>$list_single,
				'panorama'=>$list_panorama,
			);
		} else {
			if ($search_type == '0') {
				$list_single = $this->SL_pic_search_single();
				$data_bak = array(
					'single'=>$list_single,
				);
			} else if ($search_type == '1') {
				$list_multi = $this->SL_pic_search_multi();
				$data_bak = array(
					'multi'=>$list_multi,
				);
			} else if ($search_type == '4') {
				$list_panorama = $this->SL_pic_search_panorama();
				$data_bak = array(
					'panorama'=>$list_panorama,
				);
			} else {
				$data_bak = array();
			}
		}

		return $this->result(0, 'ok', $data_bak);
	}

	// 搜索图库-获取套图数据
	private function SL_pic_search_multi()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$keyword = $_GPC['key']; // 搜索关健字

		$condition_multi = " AND uniacid=:uniacid AND enabled='1' ";
		$params_multi = array(':uniacid' => $_W['uniacid']);

		if ($keyword != '') {
			$where = " AND `name` LIKE :keyword ";
			$condition_multi .= $where;
			$params_multi[':keyword'] = '%'.$keyword.'%';
		}

		$pindex_multi = max(1, intval($_GPC['page']));
		$psize_multi = 10;
		$sql_multi = "SELECT * FROM " . tablename('slwl_fitment_pic_multi'). ' WHERE 1 '
			. $condition_multi . " ORDER BY displayorder DESC, id DESC LIMIT " . ($pindex_multi - 1) * $psize_multi .',' .$psize_multi;
		$list_multi = pdo_fetchall($sql_multi, $params_multi);

		foreach ($list_multi as $k => $v) {
			$list_multi[$k]['fav'] = '0';
			$list_multi[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		// 查收藏
		if ($list_multi) {
			$pic_ids_multi = '';
			foreach ($list_multi as $k=>$v) {
				$pic_ids_multi .= $v['id'] . ',';
			}
			$pic_ids_multi = substr($pic_ids_multi, 0, strlen($pic_ids_multi)-1);
			$where_multi =' AND pic_id IN(' . $pic_ids_multi . ')';

			$condition_fav_multi = " AND uniacid=:uniacid AND pic_type='0' AND user=:user ";
			$condition_fav_multi .= $where_multi;
			$params_fav_multi = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_multi = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_multi, $params_fav_multi);

			foreach ($list_multi as $k => $v) {
				foreach ($ones_multi as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_multi[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		return $list_multi;
	}

	// 搜索图库-获取单图，更多
	private function SL_pic_search_single()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$keyword = $_GPC['key']; // 搜索关健字
		// $search_type = $_GPC['type']; // 搜索类型 0=套图，1=单图

		$condition_single = " AND uniacid=:uniacid AND enabled='1' ";
		$params_single = array(':uniacid' => $_W['uniacid']);

		if ($keyword != '') {
			$where = " AND `name` LIKE :keyword ";
			$condition_single .= $where;
			$params_single[':keyword'] = '%'.$keyword.'%';
		}

		$pindex_single = max(1, intval($_GPC['page']));
		$psize_single = 10;
		$sql_single = "SELECT * FROM " . tablename('slwl_fitment_pic_sole'). ' WHERE 1 '
			. $condition_single . " ORDER BY displayorder DESC, id DESC LIMIT "
			. ($pindex_single - 1) * $psize_single .',' .$psize_single;
		$list_single = pdo_fetchall($sql_single, $params_single);

		foreach ($list_single as $k => $v) {
			$list_single[$k]['fav'] = '0';
			$list_single[$k]['thumb_url'] = tomedia($v['thumb']);
		}

		// 查收藏
		if ($list_single) {
			$pic_ids_single = '';
			foreach ($list_single as $k=>$v) {
				$pic_ids_single .= $v['id'] . ',';
			}
			$pic_ids_single = substr($pic_ids_single, 0, strlen($pic_ids_single)-1);
			$where_single =' AND pic_id IN(' . $pic_ids_single . ')';

			$condition_fav_single = " AND uniacid=:uniacid AND pic_type='1' AND user=:user ";
			$condition_fav_single .= $where_single;
			$params_fav_single = array(':uniacid'=>$_W['uniacid'], ':user'=>$uid);
			$ones_single = pdo_fetchall("SELECT * FROM " . tablename('slwl_fitment_fav') . " where 1 "
				. $condition_fav_single, $params_fav_single);

			foreach ($list_single as $k => $v) {
				foreach ($ones_single as $key => $value) {
					if ($v['id'] == $value['pic_id']) {
						$list_single[$k]['fav'] = '1';
						break;
					}
				}
			}
		}

		return $list_single;
	}

	// 搜索图库-全景图，更多
	private function SL_pic_search_panorama()
	{
		global $_GPC, $_W;

		$uid = intval($_GPC['uid']);

		$keyword = $_GPC['key']; // 搜索关健字
		// $search_type = $_GPC['type']; // 搜索类型 0=套图，1=单图

		$condition_panorama = " AND uniacid=:uniacid AND enabled='1' ";
		$params_panorama = array(':uniacid' => $_W['uniacid']);

		if ($keyword != '') {
			$where = " AND `title` LIKE :keyword ";
			$condition_panorama .= $where;
			$params_panorama[':keyword'] = '%'.$keyword.'%';
		}

		$pindex_panorama = max(1, intval($_GPC['page']));
		$psize_panorama = 10;
		$sql_panorama = "SELECT * FROM " . tablename('slwl_fitment_panorama'). ' WHERE 1 '
			. $condition_panorama . " ORDER BY displayorder DESC, id DESC LIMIT "
			. ($pindex_panorama - 1) * $psize_panorama .',' .$psize_panorama;
		$list_panorama = pdo_fetchall($sql_panorama, $params_panorama);

		if ($list_panorama) {
			foreach ($list_panorama as $k => $v) {
				$list_panorama[$k]['fav'] = '0';
				$list_panorama[$k]['thumb_url'] = tomedia($v['thumb']);
			}

			// 查收藏
			if ($list_panorama) {
				$ids = '';
				foreach ($list_panorama as $k=>$v) {
					$ids .= $v['id'] . ',';
				}
				$ids = substr($ids, 0, strlen($ids)-1);
				$where =' AND id_resource IN(' . $ids . ')';

				$condition_collect = " AND uniacid=:uniacid AND user=:uid AND type_resource='4' ";
				$condition_collect .= $where;
				$params_collect = array(':uniacid' => $_W['uniacid'], ':uid'=>$uid);
				$sql_collect = "SELECT id_resource FROM " . tablename('slwl_fitment_fav'). ' WHERE 1 ' . $condition_collect;
				$list_collect = pdo_fetchall($sql_collect, $params_collect);

				if ($list_collect) {
					foreach ($list_panorama as $k => $v) {
						if ($list_collect) {
							foreach ($list_collect as $key => $value) {
								if ($v['id'] == $value['id_resource']) {
									$list_panorama[$k]['fav'] = '1';
									break;
								}
							}
						}
					}
				}
			}
		}

		return $list_panorama;
	}

	// 获取微信手机号
	public function doPageSmk_get_wx_phone()
	{
		global $_GPC, $_W;

		$uid = $_GPC['uid'];
		$iv = $_GPC['iv'];
		$enData = $_GPC['ed'];

		$code = $_GPC['code'];

		$rst = $this->dispose_get_wx_phone($uid, $code, $iv, $enData);

		if ($rst['return_code'] == 'SUCCESS') {
			return $this->result(0, 'ok', $rst['return_msg']);
		} else {
			return $this->result(1, $rst['return_code'].$rst['return_msg'], $rst['return_code']);
		}
	}
	private function dispose_get_wx_phone($uid, $code, $iv, $enData)
	{
		global $_GPC, $_W;

		$account = uni_fetch($_W['uniacid']);
		$appid = $account['key'];
		$session_key = '';

		if ($code) {
			$secret = $account['secret'];

			load()->func('communication');

			$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

			$resp = ihttp_request($url);
			$result = @json_decode($resp['content']);

			@putlog('获取用户手机号请求', $result);

			if (property_exists($result, 'openid')) {
				$openid = $result->openid;
				$session_key = $result->session_key;
			} else {
				$err_rst = array(
					'return_code'=>$result->errcode,
					'return_msg'=>$result->errmsg,
				);
				return $err_rst;
			}

			$rst = $this->save_session_key($session_key);
			if ($rst['return_code'] == '1') {
				$err_rst = array(
					'return_code'=>'1111',
					'return_msg'=>'保存session_key出错',
				);
				return $err_rst;
			}

		} else {
			$session_key = $_W['slwl']['set']['set_session_key']['session_key'];
		}

		if ($session_key != '') {
			require_once MODULE_ROOT . "/lib/wxphone/wxBizDataCrypt.php";
			$pc = new WXBizDataCrypt($appid, $session_key);
			$errCode = $pc->decryptData($enData, $iv, $redata);

			if ($errCode == 0) {
				$data_1 = json_decode($redata, true);
				$mobile = '';

				if ($data_1) {
					$mobile = @$data_1['phoneNumber'];

					// 更新手机号
					$data_user = [
						'mobile'=>$mobile,
					];
					pdo_update('slwl_fitment_users', $data_user, array('id' => $uid));

					$rst_ok = array(
						'return_code'=>'SUCCESS',
						'return_msg'=>$mobile,
					);
					return $rst_ok;
				} else {
					$rst_err = array(
						'return_code'=>'ERROR',
						'return_msg'=>'解密失败,请重试',
					);
					return $rst_err;
				}
			} else {
				$rst = array(
					'return_code'=>$errCode,
					'return_msg'=>'微信返回失败,请重试',
				);
				return $rst;
			}
		} else {
			$rst = array(
				'return_code'=>'-999999',
				'return_msg'=>'session_key不存在',
			);
			return $rst;
		}
	}

	// 操作-通用收集form_id
	public function doPageSL_save_form_id()
	{
		global $_GPC, $_W;
		$ver = $_GPC['ver'];
		$uid = $_GPC['uid'];

		$form_id_json = $_GPC['__input']; // 参数

		if ($form_id_json) {
			// formIDs
			$form_ids = isset($form_id_json['formIDs']) ? $form_id_json['formIDs'] : '';
		}
		if (empty($uid)) {
			@putlog('通用收集form_id-用户ID为空');
			sl_ajax(1, '用户ID为空');
		}
		if (empty($form_ids)) {
			@putlog('通用收集form_id-formID为空');
			sl_ajax(1, 'formID为空');
		}
		if (gettype($form_ids) != 'array') {
			@putlog('通用收集form_id-formID不是数组');
			sl_ajax(1, 'formID不是数组');
		}

		// 用户信息
		$condition_user = " AND uniacid=:uniacid AND id=:id ";
		$params_user = array(':uniacid' => $_W['uniacid'], ':id' => $uid);
		$one_user = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition_user, $params_user);

		if ($one_user) {
			$ids = 0;
			foreach ($form_ids as $k => $v) {
				if ($v != 'the formId is a mock one') {
					$formid_data = array(
						'uniacid' => $_W['uniacid'],
						'user_id' => $uid,
						'openid' => $one_user['openid'],
						'form_id' => $v,
						'op_code' => 'slwl-000000',
						'op_text' => '通用收取form_id',
						'addtime' => $_W['slwl']['datetime']['now'],
					);

					$res = pdo_insert('slwl_fitment_formid', $formid_data);

					$ids += $res;
				}
			}
			if ($ids >= 0) {
				@putlog('通用收集form_id', $formid_data);
				sl_ajax(0, 'ok');
			} else {
				@putlog('通用收集form_id-添加记录操作失败');
				sl_ajax(1, $_W['slwl']['lang']['tips_error']);
			}
		} else {
			@putlog('通用收集form_id-用户不存在');
			sl_ajax(1, $_W['slwl']['lang']['tips_not_exist_user']); // 用户不存在
		}
	}

	// 更新用户信息
	public function doPageSL_update_user()
	{
		global $_GPC, $_W;

		$post = $_GPC['__input']; // 参数

		if (empty($post)) {
			sl_ajax(1, '用户信息为空');
		}

		// 版本号
		if ($post['ver']) {
			$ver = $post['ver'];
		} else {
			sl_ajax(1, '版本不能为空');
		}
		// 用户ID
		if ($post['uid']) {
			$uid = $post['uid'];
		} else {
			sl_ajax(1, '用户ID不能为空');
		}

		// 用户信息
		$condition_user = " AND uniacid=:uniacid AND id=:id ";
		$params_user = array(':uniacid' => $_W['uniacid'], ':id' => $uid);
		$one_user = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition_user, $params_user);

		if (empty($one_user)) {
			@putlog('更新个信息');
			sl_ajax(1, '用户不存在'); //用户不存在
		}

		$data_user = array();
		// 昵称
		if ($post['nicename']) {
			$data_user['nicename'] = json_encode($post['nicename'], JSON_UNESCAPED_UNICODE);
			$one_user['nicename'] = $post['nicename'];
		}
		// 头像
		if ($post['avatar']) {
			$data_user['avatar'] = $post['avatar'];
			$one_user['avatar'] = $post['avatar'];
		}
		// 用户所在国家
		if ($post['country']) {
			$data_user['country'] = $post['country'];
			$one_user['country'] = $post['country'];
		}
		// 用户所在省份
		if ($post['province']) {
			$data_user['province'] = $post['province'];
			$one_user['province'] = $post['province'];
		}
		// 用户所在城市
		if ($post['city']) {
			$data_user['city'] = $post['city'];
			$one_user['city'] = $post['city'];
		}
		// 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
		if ($post['gender']) {
			$data_user['gender'] = $post['gender'];
			$one_user['gender'] = $post['gender'];
		}
		// 手机
		if ($post['mobile']) {
			$data_user['mobile'] = $post['mobile'];
			$one_user['mobile'] = $post['mobile'];
		}
		// 密码
		if ($post['password']) {
			$data_user['password'] = sha1(SLWL_MAIN_MODULE . md5($post['password']));
		}

		$rst = pdo_update('slwl_fitment_users', $data_user, array('id' => $uid));
		if ($rst !== false) {
			sl_ajax(0, $one_user);
		} else {
			sl_ajax(1, $rst);
		}
	}

	// 获取用户信息
	public function doPageSL_get_user()
	{
		global $_GPC, $_W;

		$post = $_GPC['__input']; // 参数

		if (empty($post)) {
			sl_ajax(1, '用户信息为空');
		}

		// 版本号
		if ($post['ver']) {
			$ver = $post['ver'];
		} else {
			sl_ajax(1, '版本不能为空');
		}
		// 用户ID
		if ($post['uid']) {
			$uid = $post['uid'];
		} else {
			sl_ajax(1, '用户ID不能为空');
		}

		// 用户信息
		$condition_user = " AND uniacid=:uniacid AND id=:id ";
		$params_user = array(':uniacid' => $_W['uniacid'], ':id' => $uid);
		$one_user = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition_user, $params_user);

		if ($one_user) {
			$one_user['nicename'] = json_decode($one_user['nicename']);

			unset($one_user['openid']);
			unset($one_user['unionid']);
			unset($one_user['password']);
			sl_ajax(0, $one_user);
		} else {
			sl_ajax(1, '用户不存在');
		}
	}


	// 工地模块.start
	// 模块首页
	public function doPageSite_index_data()
	{
		site_index_data();
	}
	// 工地-搜索
	public function doPageSite_search()
	{
		site_search();
	}
	// 工地-one
	public function doPageSite_one()
	{
		site_one();
	}
	// 工地-收藏
	public function doPageSite_collect()
	{
		site_collect();
	}
	// 工地模块.end


	// 百度小程序.start
	// 百度-创建用户
	public function doPageSL_baidu_create_user()
	{
		global $_GPC, $_W;

		$code = $_GPC['code'];

		load()->func('communication');
		// $account = uni_fetch($_W['uniacid']);
		// $appid = $account['key'];
		// $secret = $account['secret'];

		$app_key = 'jdumkZwUmkayZGwHvEUhaSqStAo3Fpoo';
		$app_secret = '2DRq2PwW3bkNgKD7hghPY9nltar5nPC6';

		$param = array();
		$param['code'] = $code;
		$param['client_id'] = $app_key;
		$param['sk'] = $app_secret;

		$url = "https://openapi.baidu.com/nalogin/getSessionKeyByCode";

		$resp = ihttp_request($url, $param);
		$result = @json_decode($resp['content']);

		if (property_exists($result, 'openid')) {

			$openid = $result->openid;
			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $openid,
				'nicename' => $_GPC['nicename'],
				'avatar' => $_GPC['avatar'],
				'gender' => $_GPC['gender'],
				'type_app'=>'baidu',
			);

			$condition = " AND uniacid=:uniacid AND openid=:openid ";
			$params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
			$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_users') . ' WHERE 1 ' . $condition, $params);

			if (empty($one)) {
				$data['addtime'] = $_W['slwl']['datetime']['now'];
				pdo_insert('slwl_fitment_users', $data);
				$id = pdo_insertid();

				$data['id'] = $id;
				unset($data['openid']);

				return $this->result(0, 'ok', $data);
			} else {
				unset($one['openid']);
				unset($one['unionid']);
				unset($one['password']);
				return $this->result(0, 'ok', $one);
			}
		} else {
			return $this->result(1, 'err');
		}

	}
	// 百度小程序.end


	// 新版商城.start
	// 测试方法
	public function doPageStore_test()
	{
		store_test();
	}
	// 新版商城-首页
	public function doPageStore_index_data()
	{
		store_index_data();
	}
	// 新版商城-商品详情
	public function doPageStore_good_detail()
	{
		store_good_detail();
	}
	// 新版商城-返回所有一级分类
	public function doPageStore_category_top()
	{
		store_category_top();
	}
	// 新版商城-返回指定分类下的子分类
	public function doPageStore_category_sub()
	{
		store_category_sub();
	}
	// 新版商城-获取指定分类下的商品
	public function doPageStore_goods_list()
	{
		store_goods_list();
	}
	// 新版商城-搜索
	public function doPageStore_search()
	{
		store_search();
	}
	// 新版商城-支付
	// public function doPageStore_pay()
	// {
	//     store_pay();
	// }
	// 新版商城-收藏
	public function doPageStore_collect()
	{
		store_collect();
	}
	// 新版商城-地址
	public function doPageStore_address()
	{
		store_address();
	}
	// 新版商城-优惠券，列表
	public function doPageStore_coupon()
	{
		store_coupon();
	}
	// 新版商城-我的优惠券，列表
	public function doPageStore_coupon_my()
	{
		store_coupon_my();
	}
	// 新版商城-我的购物车
	public function doPageStore_cart()
	{
		store_cart();
	}
	// 新版商城-品牌商
	public function doPageStore_brand()
	{
		store_brand();
	}
	// 新版商城-品牌商-只获取一条
	public function doPageStore_brand_one()
	{
		store_brand_one();
	}
	// 新版商城-获取指定品牌商的商品
	public function doPageStore_brand_one_goods()
	{
		store_brand_one_goods();
	}
	// 新版商城-获取商品规格-价格
	public function doPageStore_spec_price()
	{
		store_spec_price();
	}
	// 新版商城-订单详情
	public function doPageStore_order()
	{
		store_order();
	}
	// 新版商城.end


	// 产品.start
	// 产品-列表-配置
	public function doPageSL_pro_list_config()
	{
		pro_list_config();
	}

	// 产品-列表
	public function doPageSL_pro_list()
	{
		pro_index_data();
	}

	// 产品-文章内容
	public function doPageSL_pro_one()
	{
		pro_one();
	}
	// 产品-收藏
	public function doPageSL_pro_collect()
	{
		pro_collect();
	}
	// 产品.end


	// 全景.start
	// 全景-列表-配置
	public function doPageSL_panorama_config()
	{
		panorama_list_config();
	}
	// 全景-列表
	public function doPageSL_panorama()
	{
		panorama_index_data();
	}

	public function doPageSL_save_form_data(){
		global $_GPC, $_W;
		$form_data = $_GPC['__input']; // post参数
		$ver = $form_data['ver'];
		$uid = intval($form_data['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }
		$case_id = intval($form_data['case_id']);

		$data = array(
			'uniacid' => $_W['uniacid'],
			'case_id' => $form_data['case_id'],
			'options' => $form_data['options'],
			'case_name' => $form_data['case_name'],
			'addtime' => $_W['slwl']['datetime']['now'],
			'uptime' => $_W['slwl']['datetime']['now'],
			'ver' => $ver,
			'case_id' =>  $case_id
		);

		pdo_insert('slwl_fitment_form_wanneng', $data);
		return result(0, 'ok', 0);

	}

	public function doPageSL_get_form_data(){
		global $_GPC, $_W;
		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }
		$id = intval($_GPC['id']);
		$uniacid = $_W['uniacid'];
		$condition = " AND uniacid=:uniacid AND case_id=:id AND enabled=:enabled ORDER BY sort DESC ";
		$condition2 = " AND uniacid=$uniacid AND id=$id ";
		$params = array(':uniacid' => $uniacid, ':id' => $id, ':enabled'=>1);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_wn_list') . ' WHERE 1 ' . $condition, $params);
		$form_data = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_wn_case') . ' WHERE 1 ' . $condition2);
		$form_data['base_url'] = MODULE_URL.'img/pic/theme/';
		$form_banner = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_form_banner') . ' WHERE 1 ' . $condition, $params);
		for($i=0; $i<count($form_banner); $i++) {
			$form_banner[$i]['img'] = tomedia($form_banner[$i]['img']);
		}
		$data = array('list'=> $list, 'form_data'=>$form_data, 'form_banner'=> $form_banner);
		return result(0, 'ok', $data);
	}

	// 获取工地进度
	public function doPageSL_site_progress(){
		global $_GPC, $_W;
		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);

		if ($ver == '') {
			return result(1, $_W['slwl']['lang']['tips_not_exist_ver']);
		}

		if ($uid == 0) {
			return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']);
		}

		$uniacid = $_W['uniacid'];
		$condition = " AND uniacid=:uniacid ";
		$params = array(':uniacid' => $uniacid);
		$list = pdo_fetchall('SELECT * FROM ' . tablename('slwl_fitment_site_progress') . ' WHERE 1 ' . $condition, $params);
		return result(0, 'ok', $list);
	}

	public function doPageSL_up_img() {
		global $_GPC, $_W;
		load()->func('file');
		$f = $_FILES["file"];
		$fl = file_get_contents($f['tmp_name']);
		$filename = 'images/' . $_W['uniacid'] . '/' . date('Y/m/', $_W['slwl']['datetime']['timestamp']);
		$filename .= file_random_name($filename, 'jpeg');
		$rst = file_write($filename, $fl);
		$rst_remote = file_remote_upload($filename);
		$url = tomedia($filename);
		return result(0, 'ok', $url);
	}

	public function doPageSL_get_site_dy() {
		global $_GPC, $_W;
		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		$id = intval($_GPC['id']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }
		$condition = " AND uniacid=:uniacid AND site_id=:id ";
		$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_site_list_dy') . ' WHERE 1 ' . $condition, $params);
		return result(0, 'ok', $one);
	}


	public function doPageSL_up_site_list() {
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		$id_progress = $_GPC['id_progress'];
		$id_title = $_GPC['id_title'];
		$condition = " AND uniacid=:uniacid AND id=:id ";
		$params = array(':uniacid' => $uniacid, ':id' => $id);
		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_site_list') . ' WHERE 1 ' . $condition, $params);
		$options =  json_decode($one['options'], true);
		$options['site_progress'] = ('a'. $id_progress);
		$options = json_encode($options);
		pdo_update('slwl_fitment_site_list', ['options'=>$options], array('id' => $id));
		return result(0, 'ok', 0);
	}

	// 置顶某个工地
	public function doPageSL_site_zhiding () {
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];
		$id = $_GPC['id'];
		pdo_update('slwl_fitment_site_list', ['displayorder'=>$_W['slwl']['datetime']['timestamp']], array('id' => $id));
		return result(0, 'ok', 0);
	}

	public function doPageSL_up_site_progress () {
		global $_GPC, $_W;
		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		$id = intval($_GPC['id']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }
		$data = [
			'id_progress' => $_GPC['id_progress'],
			'id_title' => $_GPC['id_title']
		];
		pdo_update('slwl_fitment_site_list_dy', $data, array('site_id' => $id));
		return result(0, 'ok', 0);
	}

	public function doPageSL_up_site() {
		global $_GPC, $_W;
		$form_data = $_GPC['__input']; // post参数
		$ver = $form_data['ver'];
		$uid = intval($form_data['uid']);
		$id = intval($form_data['id']);
		$thumbs = explode(",", $form_data['thumbs']);
		$thumbs = json_encode($thumbs,JSON_UNESCAPED_UNICODE);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$data = array(
			'intro' => $form_data['intro'],
			'thumbs' => $thumbs,
			'id_progress' => $form_data['id_progress'],
			'id_title' => $form_data['id_title'],
			'title' => $form_data['title'],
			'uniacid'=> $_W['uniacid']
		);

		$condition = " AND uniacid=:uniacid AND site_id=:id ";
		$params = array(':uniacid' => $_W['uniacid'], ':id' => $id);
		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_site_list_dy') . ' WHERE 1 ' . $condition, $params);
		if (!$one) {
			$data['site_id'] = $id;
			$data['create_time'] = $_W['slwl']['datetime']['now'];
			$res = pdo_insert('slwl_fitment_site_list_dy', $data);
		}else{
			$res = pdo_update('slwl_fitment_site_list_dy', $data, array('site_id' => $id));
		}

		if ($res) {
			return result(0, 'ok', 0);
		} else {
			return result(1, 'no', false);
		}

	}


	// 全景-文章内容
	public function doPageSL_panorama_one()
	{
		// panorama_one();

		global $_GPC, $_W;

		$ver = $_GPC['ver'];
		$uid = intval($_GPC['uid']);
		if ($ver == '') { return result(1, $_W['slwl']['lang']['tips_not_exist_ver']); }
		if ($uid == 0) { return result(1, $_W['slwl']['lang']['tips_not_exist_user_id']); }

		$id = intval($_GPC['id']);

		$condition = " AND uniacid=:uniacid AND id=:id AND enabled='1' ";
		$params = array(':uniacid' => $_W['uniacid'], 'id' => $id);

		$one = pdo_fetch('SELECT * FROM ' . tablename('slwl_fitment_panorama') . ' WHERE 1 ' . $condition, $params);

		if ($one) {
			// 浏览数加 +1
			pdo_query("UPDATE " . tablename('slwl_fitment_panorama')
				. " set view_count=view_count+1 WHERE id=:id ", array(':id'=>$one['id']));

			$one['thumb_url'] = tomedia($one['thumb']);
			$one['pic_top_url'] = tomedia($one['pic_top']); // tile4url
			$one['pic_bottom_url'] = tomedia($one['pic_bottom']); // tile5url
			$one['pic_left_url'] = tomedia($one['pic_left']); // tile3url
			$one['pic_right_url'] = tomedia($one['pic_right']); // tile1url
			$one['pic_front_url'] = tomedia($one['pic_front']); // tile0url
			$one['pic_back_url'] = tomedia($one['pic_back']); // tile2url

			$str_xml = '<?xml version="1.0" encoding="UTF-8"?>'.
				'<panorama id="">'.
					'<view fovmode="0" pannorth="0">'.
						'<start pan="0" fov="70" tilt="0"/>'.
						'<min pan="0" fov="5" tilt="-90"/>'.
						'<max pan="360" fov="120" tilt="90"/>'.
					'</view>'.
					'<userdata title="" datetime="" description="" copyright="" tags="" author="" source="" comment="" info="" longitude="" latitude=""/>'.
					'<hotspots width="180" height="20" wordwrap="1">'.
						'<label width="180" backgroundalpha="1" enabled="1" height="20" backgroundcolor="0xffffff" bordercolor="0x000000" border="1" '.
							'textcolor="0x000000" background="1" borderalpha="1" borderradius="1" wordwrap="1" textalpha="1"/>'.
						'<polystyle mode="0" backgroundalpha="0.2509803921568627" backgroundcolor="0x0000ff" bordercolor="0x0000ff" borderalpha="1"/>'.
					'</hotspots>'.
					'<media/>'.
					'<input '.
						' tile0url="'.$one['pic_front_url'].'"'.
						' tile1url="'.$one['pic_right_url'].'"'.
						' tile2url="'.$one['pic_back_url'].'"'.
						' tile3url="'.$one['pic_left_url'].'"'.
						' tile4url="'.$one['pic_top_url'].'"'.
						' tile5url="'.$one['pic_bottom_url'].'"'.
						' tilesize="750"'.
						' tilescale="1.013333333333333" />'.
					'<control simulatemass="1" lockedmouse="0" lockedkeyboard="0" dblclickfullscreen="0" invertwheel="0" lockedwheel="0" '.
						'invertcontrol="1" speedwheel="1" sensitivity="8"/>'.
				'</panorama>';

			include $this->template('panorama/index');
		} else {
			// 操作不存在
			return result(1, $_W['slwl']['lang']['tips_not_exist_operation']);
		}
	}
	// 全景-收藏
	public function doPageSL_panorama_collect()
	{
		panorama_collect();
	}
	// 全景.end

}
?>
