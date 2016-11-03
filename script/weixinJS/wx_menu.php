<?php
/** 
* 根号数理化get_token 
* https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4f3ffca94662ce40&secret=9998a307f7f99e9445d84439d6182355 
*/

header("Content-type: text/html; charset=utf-8");

/* 获得 token
require_once "jssdk-token.php";
$corpid = "wx4f3ffca94662ce40";
$corpsecret = "9998a307f7f99e9445d84439d6182355";
$jssdk = new JSSDK($corpid, $corpsecret);
$access_token = $jssdk->getAccessToken();
// -- end token
*/
// 新浪云kvdb保存根号教育公众号token
$ret = file_get_contents("http://xyzs.sinaapp.com/wx/kvdb.php");
$ret = json_decode($ret); 
$access_token = $ret->access_token;

echo $access_token;

$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$data = '{
    "button": [
        {
            "name": "根号教育", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "公司简介", 
                    "url": "http://www.xzpt.org/wx_ghjy/profile.html"
                }, 
                {
                    "type": "view", 
                    "name": "联盟学校", 
                    "url": "http://www.xzpt.org/wx_ghjy/map.html"
                }
            ]
        },         
        {
            "name": "我的", 
            "sub_button": [
            {
                 "type": "view", 
                 "name": "上课内容作业", 
                 "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=homework"
             },
               {
                    "type": "view", 
                    "name": "账号中心", 
                    "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=member"
                },
            ]
        }
    ]
}';

echo createMenu($url,$data);

//创建菜单,参数api url & menu data 方法post
function createMenu($url,$data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$tmpInfo = curl_exec($ch);
	if (curl_errno($ch)) {
	  return curl_error($ch);
	}

	curl_close($ch);
	return $tmpInfo;
}
/*
//获取菜单
function getMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}

//删除菜单
function deleteMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}
*/
?>
