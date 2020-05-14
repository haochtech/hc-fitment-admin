<?php
/**
 * 深蓝网络 Copyright (c) www.zhshenlan.com
 */

namespace Aliyun\DySDKLite\Sms;
use Aliyun\DySDKLite\SignatureHelper;

if (!defined('IN_IA')) {
    exit('Access Denied');
}

if (!function_exists('sendsms')) {
    function sendsms($mobile,$content=array(),$mobanid='',$msg='') {
        global $_W;
        if(empty($mobile) || empty($content)){
            return;
        }

        include_once MODULE_ROOT . '/lib/Alidayu/TopSdk.php';

        $appkey = $msg['appkey'];
        $content = json_encode($content);
        $secret = $msg['secret'];
        $c = new TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123454");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($msg['sign']);
        $req->setSmsParam($content);
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode($mobanid);
        $resp = $c->execute($req);
        // return $resp;
        return 'SUCCESS';
    }
}

// 新版阿里云通信
if (!function_exists('sendsms_new')) {
    function sendsms_new($data) {
        global $_W;
        if(empty($data)){
            return;
        }

        include_once MODULE_ROOT . '/lib/Alidysmslite/SignatureHelper.php';

        $params = array ();

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = $data['appkey'];
        $accessKeySecret = $data['secret'];

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $data['mobile'];

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $data['sign'];

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $data['sms_id'];

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "time" => $data['curr_date'],
            "phone" => $data['phone'],
            "money" => $data['money'],
        );

        // fixme 可选: 设置发送短信流水号
        $params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params['SmsUpExtendCode'] = "1234567";

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = @$helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );

        return $content;
        // return 'SUCCESS';
    }
}

if (!function_exists('send_sys_msg_admin')) {
    // $type = 1,测试
    function send_sys_msg_admin($phone, $type = 0) {
        global $_W;

        $res = true;

        if ($type == 0) {
            $res = check_send_time(); // 是否超过 15 分钟
        }

        if ($res) {

            $uniacid = $_W['uniacid'];
            $condition = ' and uniacid=:uniacid and setting_name=:setting_name';
            $params = array(':uniacid' => $uniacid, ':setting_name'=>'msg_settings');
            $msg = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 ' . $condition, $params);

            if (!(empty($msg))) {
                $messages = json_decode($msg['setting_value'], true);

                if ($messages['type']=='Alidayu') {
                    $appkey = $messages['Alidayu']['appkey'];
                    $secret = $messages['Alidayu']['secret'];
                    $mobile = $messages['Alidayu']['mobile']; // 手机号
                    $sign = $messages['Alidayu']['sign']; // 签名
                    $sms_id = $messages['Alidayu']['sms_id']; // 短信模板ID
                    $curr_date = date('Y-m-d H:i:s', time()); // 变量最长只能15个字符
                    $res = sendsms($mobile, array('time'=>$curr_date,'phone'=>$phone), $sms_id, array('appkey'=>$appkey, 'secret'=>$secret, 'sign'=>$sign));
                    // echo $res;
                } else if ($messages['type']=='Alidysmslite') {
                    $ds = array(
                        'appkey' => $messages['Alidayu']['appkey'],
                        'secret' => $messages['Alidayu']['secret'],
                        'mobile' => $messages['Alidayu']['mobile'],
                        'phone' => $phone,
                        'money' => '0',
                        'sign' => $messages['Alidayu']['sign'],
                        'sms_id' => $messages['Alidayu']['sms_id'],
                        'curr_date' => date('Y-m-d H:i:s', time()),
                    );
                    $res = sendsms_new($ds);
                }
            }
        }

        return $res;
    }
}

// 发送用户通知短信
if (!function_exists('send_user_msg')) {
    function send_user_msg($mobile, $money) {
        global $_W;

        $uniacid = $_W['uniacid'];
        $condition = ' and uniacid=:uniacid and setting_name=:setting_name';
        $params = array(':uniacid' => $uniacid, ':setting_name'=>'msg_user_settings');
        $msg = pdo_fetch("SELECT * FROM " . tablename('slwl_fitment_settings') . ' WHERE 1 ' . $condition, $params);

        if (!(empty($msg))) {
            $messages = json_decode($msg['setting_value'], true);

            if ($messages['type']=='Alidayu') {
                $appkey = $messages['Alidayu']['appkey'];
                $secret = $messages['Alidayu']['secret'];
                $sign = $messages['Alidayu']['sign']; // 签名
                $sms_id = $messages['Alidayu']['sms_id']; // 短信模板ID
                $curr_date = date('m-d H:i:s', time()); // 变量最长只能15个字符
                $res = sendsms($mobile, array('time'=>$curr_date, 'money'=>$money), $sms_id, array('appkey'=>$appkey, 'secret'=>$secret, 'sign'=>$sign));
                // echo $res;
            } else if ($messages['type']=='Alidysmslite') {
                $ds = array(
                    'appkey' => $messages['Alidayu']['appkey'],
                    'secret' => $messages['Alidayu']['secret'],
                    'mobile' => $mobile,
                    'money' => $money,
                    'sign' => $messages['Alidayu']['sign'],
                    'sms_id' => $messages['Alidayu']['sms_id'],
                    'curr_date' => date('m-d H:i:s', time()),
                );
                $res = sendsms_new($ds);
            }
        }

        return $res;
    }
}
