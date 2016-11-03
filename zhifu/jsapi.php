<?php 
//header('content-type:application:json;charset=utf8'); 
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin: *');  
// 响应类型  
//header('Access-Control-Allow-Methods:POST');  
// 响应头设置  
//header('Access-Control-Allow-Headers:x-requested-with,content-type'); 

ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
//require_once 'log.php';

//0. 获取我自己传递的参数
$OrderID = addslashes($_REQUEST["OrderID"]);
$taocan = addslashes($_REQUEST["taocan"]);
//$times = $_REQUEST["times"];
//$amt = $_REQUEST["amt"];
//$coupon = $_REQUEST["coupon"];
$amount = $_REQUEST["amount"];//*100; //分钱
$openId = $_REQUEST["openId"];

//初始化日志
//$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

/*
//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}
*/

//①、获取用户openid
$tools = new JsApiPay();
//$openId = $tools->GetOpenid(); //跨域问题

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("购买课程");
$input->SetAttach("test");
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetOut_trade_no($OrderID); //传入参数
$input->SetTotal_fee($amount*100);

$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($taocan);
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

echo $jsApiParameters //json_encode($jsApiParameters);

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
