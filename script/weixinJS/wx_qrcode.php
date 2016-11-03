<?php
/**
* 16-05-16 生成带参数二维码，个人推广场景
* 先通过oAuth2获得openId->member_id，作为scene_id
* 然后1: token->ticket 2: ticket->qrcode img ->download 3: scan_id
* https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe7253a6972bd2d4b&secret=c5c604c56402baac2c7ccd98b35ef2f2 
*/
header("Content-type: text/html; charset=utf-8");

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

require_once('../db/database_connection.php');

//第一步：通过oAuth2 code获得openId
$code = $_GET["code"]; 
$appid = "wx4f3ffca94662ce40"; 
$secret = "9998a307f7f99e9445d84439d6182355"; 
$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url); 
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
$res = curl_exec($ch); 
curl_close($ch); 
$json_obj = json_decode($res,true); 
$openid = $json_obj['openid']; 
// 通过openId获得member_id
$sql = "SELECT memberID from member where wxID = '$openid' limit 1";
$result = mysql_query($sql) or  die("Invalid query: readMember" . mysql_error());
$row = mysql_fetch_array($result);
$memberID = $row["memberID"];

/* 第二步：memberId作为scene_id，通过ticket取得二维码图片
require_once "jssdk-token.php";
$corpid = "wx4f3ffca94662ce40";
$corpsecret = "9998a307f7f99e9445d84439d6182355";
$jssdk = new JSSDK($corpid, $corpsecret);
$access_token = $jssdk->getAccessToken();
*/
// 新浪云kvdb保存根号教育公众号token
$ret = file_get_contents("http://xyzs.sinaapp.com/wx/kvdb.php");
$ret = json_decode($ret); 
$access_token = $ret->access_token;

$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
// scene_id采用推荐者的openId???? 临时最长30天2592000
$data = '{
	"expire_seconds": 2592000, 
	"action_name": "QR_SCENE", 
	"action_info": {
		"scene": {
			"scene_id": ' . $memberID . '
		}
	}
}';

$result = httpPost($url,$data);
$jsonInfo = json_decode($result,true);
$ticket = $jsonInfo['ticket'];

// 获得二维码图片
//$ticket = urlencode($ticket);
$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);

// 直接跳转页面,参数：图片文件地址url
echo "<script>location.href='../../qrcode.html?$url';</script>";

//$result = httpGet($url);
//echo $result;

//$file = file_get_contents($url); //保存文件，如何只是显示转发？
//echo $file;

/*下载图片到当前目录
$imageInfo = downImgFromWx($url);
$fileName = 'qrcode.jpg';
$localFile = fopen($fileName,'w');
if(localFile !== false){
	if(fwrite($localFile,$imageInfo['body']) !== false){
		fclose($localFile);
	}
} */

/*
  // 合并图片，人物底图+装备
  // 人物图片
  $path_1 = "./images/people.gif";
  // 装备图片
  $path_2 = "./images/weapon.gif";
  // 将人物和装备图片分别取到两个画布中
  $image_1 = imagecreatefromgif($path_1); //imagecreatefromjpeg
  $image_2 = imagecreatefromgif($path_2);
  // 创建一个和人物图片一样大小的真彩色画布
  //（ps：只有这样才能保证后面copy装备图片的时候不会失真）
  $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
  // 为真彩色画布创建白色背景，再设置为透明
  $color = imagecolorallocate($image_3, 255, 255, 255);
  imagefill($image_3, 0, 0, $color);
  imageColorTransparent($image_3, $color);
  // 首先将人物画布采样copy到真彩色画布中，不会失真   
imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),imagesy($image_1),imagesx($image_1),imagesy($image_1));
  // 再将装备图片copy到已经具有人物图像的真彩色画布中，同样也不会失真
  imagecopymerge($image_3,$image_2, 0,320,0,0,imagesx($image_2),imagesy($image_2), 100);
  // 将画布保存到指定的gif文件, 大功告成
  imagegif($image_3, "/images/update/hero_gam.gif");
*/

//取得ticket, post instead of get 参数wxid..
function httpPost($url,$data = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$res = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $res;
}
// 获得二维码图片
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}
//从微信下载图片
function downImgFromWx($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);	//只取body头
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
	curl_close($ch);
	$imageAll = array_merge(array('header'=>$httpinfo),array('body'=>$package));
	return $imageAll;
}

?>

