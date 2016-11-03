<?php
//根号家园 token 
// https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx0f4d66295227df32&secret=190712c4f685a00d05cb49eb13c711ff 

//根号数理化token 
// https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx4f3ffca94662ce40&secret=9998a307f7f99e9445d84439d6182355 


header("Content-type: text/html; charset=utf-8");
define("ACCESS_TOKEN", "ySpCnf30-pTE_5O-NuCaMHkn3-xwo2vsuVGU3e2JKLVb9o2I5ymsO7TwocQPw8MN2G9_F3DZ2uorPIFdrznw9JhQaoxymHDsIJ9-2QLYEhmuHZyXeTbggmEFcYHIEavjQRDdADAGHT");

//创建菜单
function createMenu($data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
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


$data = '{
    "button": [
        {
            "name": "预约体验", 
            "type": "click", 
			"key": "experience"
        }, 	
        {
            "name": "产品服务", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "产品介绍", 
                    "url": "http://www.xzpt.org/wx_ghjy/product.html"
                }, 
                {
                    "type": "view", 
                    "name": "公司简介", 
                    "url": "http://www.xzpt.org/wx_ghjy/profile.html"
                }, 
                {
                    "type": "view", 
                    "name": "校区分布", 
                    "url": "http://www.xzpt.org/wx_ghjy/map.html"
                }
            ]
        },         
        {
            "name": "我的", 
            "sub_button": [

               {
	                "type": "view", 
	                "name": "推广二维码", 
	                "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=qrcode"
	            },       
                {
                    "type": "view", 
                    "name": "购买课程", 
                    "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=prepaid"
                }, 
                {
                    "type": "view", 
                    "name": "学习轨迹", 
                    "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=study"
                },
                {
                    "type": "view", 
                    "name": "账号", 
                    "url": "http://www.xzpt.org/wx_ghjy/script/weixinJS/oAuth2.php?menuitem=member"
                },
            ]
        }
    ]
}';




echo createMenu($data);

?>
