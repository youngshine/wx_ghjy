<?php 
// 502 bad gateway nginx 1.10
/*
if(isset($_SESSION['user'])){ 
	print_r($_SESSION['user']);
	exit;
}
*/
$url = 'http://www.xzpt.org/wx_ghjy/'; //redirect根号数理化
$menuitem = $_GET["menuitem"];
switch($menuitem){
	case "prepaid":
		$url = $url . "prepaid.php";
		break;
	case "study":
		$url = $url . "study.php";
		break;
	case "homework":
		$url = $url . "homework.php"; // 直接在页面获得openid
		break;
	case "member":
		$url = $url . "account.php"; //"member.php";
		break;
	case "qrcode":
		$url = $url . "script/weixinJS/wx_qrcode.php";
		break;
}

$APPID = 'wx4f3ffca94662ce40';
//$REDIRECT_URI = 'http://www.yiqizo.com/weixin/sjfx/script/weixinJS/callback.php';
$REDIRECT_URI = $url; // 获取用户授权的网页
$scope = 'snsapi_base'; // silent
$state = '9';
//$scope='snsapi_userinfo';//需要授权
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';

header("Location:".$url);

?>