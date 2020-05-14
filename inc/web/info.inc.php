<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

defined('IN_IA') or exit('Access Denied');

global $_GPC, $_W;

//createMobileUrl
// var_dump($this->get_address($this->createMobileUrl('smk_adv')));
// var_dump($this->createWxappUrl('smk_adv', array('f'=>'eye')));


// 获取幻灯片
$smk_adv = $this->get_address($this->createWxappUrl('smk_adv', array()));
// (PC)获取幻灯片
$smk_adv_pc = $this->get_address($this->createWxappUrl('smk_adv', array('f'=>'eye')));


// 获取文章列表(传ID)，默认5条，可自定义条数
$smk_news = $this->get_address($this->createWxappUrl('smk_news', array('tid'=>'1')));
// (PC)获取文章列表(传ID)
$smk_news_pc = $this->get_address($this->createWxappUrl('smk_news', array('f'=>'eye','tid'=>'1')));


// 获取文章列表(传分类序号从1开始)，默认5条，可自定义条数
$smk_news_flag = $this->get_address($this->createWxappUrl('smk_news_flag', array('flag'=>'selectness_theme')));
// (PC)获取文章列表(传分类序号从1开始)
$smk_news_flag_pc = $this->get_address($this->createWxappUrl('smk_news_flag', array('f'=>'eye','flag'=>'selectness_theme')));


// 获取文章内容
$smk_act_one = $this->get_address($this->createWxappUrl('smk_act_one', array('aid'=>'1')));


// 获取分类下第一篇文章(传分类ID)
$smk_act_first = $this->get_address($this->createWxappUrl('smk_act_first', array('tid'=>'1')));
// (PC)获取分类下第一篇文章(传分类ID)
$smk_act_first_pc = $this->get_address($this->createWxappUrl('smk_act_first', array('f'=>'eye','tid'=>'1')));


// 获取分类
$smk_term = $this->get_address($this->createWxappUrl('smk_term', array()));
// (PC)获取分类
$smk_term_pc = $this->get_address($this->createWxappUrl('smk_term', array('f'=>'eye')));


// 获取系统设置
$smk_config = $this->get_address($this->createWxappUrl('smk_config', array()));
// (PC)获取系统设置
$smk_config_pc = $this->get_address($this->createWxappUrl('smk_config', array('f'=>'eye')));


// 获取推荐
$smk_news_recommand = $this->get_address($this->createWxappUrl('smk_news_recommand', array()));
// (PC)获取推荐
$smk_news_recommand_pc = $this->get_address($this->createWxappUrl('smk_news_recommand', array('f'=>'eye')));


// 获取新品
$smk_news_new = $this->get_address($this->createWxappUrl('smk_news_new', array()));
// (PC)获取新品
$smk_news_new_pc = $this->get_address($this->createWxappUrl('smk_news_new', array('f'=>'eye')));



include $this->template('web/info');

?>