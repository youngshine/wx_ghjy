<?php
//http://www.jb51.net/callback.php
$code = $_GET["code"]; 
$appid = "wx4f3ffca94662ce40"; //公众号code直接获得id，不需要access_token，企业号则需要
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
//根据openid和access_token查询用户信息 
//$access_token = $json_obj['access_token']; 
$openid = $json_obj['openid']; 
//$openid = 'o2wBFuPB9cVcAa2Xf4JnL2Hhu1og';
//echo $code;
//echo $openid;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>根号教育</title>
	<meta charset="utf-8" />
	<meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">	  
	<link rel="stylesheet" href="//cdn.kik.com/app/2.0.1/app.min.css">
	<style>
		.app-topbar {background:#FF6600;}
		
		/*  msg box */
		#m {
			filter: alpha(opacity=30);
			-moz-opacity: 0.3; 
			opacity: 0.3;
			position:absolute;
			z-index:10000;
			background-color:none;
		}
		#lo {
			position:absolute;
			width:150px;
			height:90px;
			line-height:90px;
			background-color:#000;
			color:#fff;
			text-align:center;
			z-index:9999;
	
			filter: alpha(opacity=70);
			-moz-opacity: 0.7; 
			opacity: 0.7;
			
			border-radius:10px;
			-moz-border-radius:10px; /* Old Firefox */
		}
		/* ends - msg box */
		
		.list .listItem {
			background: #fff;
			padding: 10px 15px;
			border-bottom: 1px solid #ddd;
		}
		.list .listItem .title {
			 display:block;
			 text-overflow:ellipsis;
			 overflow:hidden;
			 white-space:nowrap;
		}
		.list .listItem .subtitle{
			 color: #888;
			 font-size:0.9em;
		}
	</style> 
  </head>

  <body>
	  
    <div class="app-page" data-page="home">
      <div class="app-topbar">
          <div class="app-title">上课内容作业</div>
      </div>
      <div class="app-content">
  		<!-- 
		<div style="margin-bottom:10px;">
			<input class="app-input" type="search" placeholder="search..." readOnly=true>
		</div>	
		-->
	    <div class="list">
			<div class="listItem">		
				<div class="title" style="line-height:30px;">			
					<span class="time" style="float:right;"></span>
					<span class="classTitle"></span>
					<span class="id" style="display:none;"></span>	
				</div>
				
				<div style="color:#888;">上课内容：</div>
				<div class="keypoint"></div>
				<div style="color:#888;">课后作业：</div>
				<div class="photos"></div>
				</div>
			</div>
	    </div>
		
	  </div>	  
    </div>

    <script src="src/zepto.js"></script>
    <script src="src/app.min.js"></script>
	
	<script src="js/main.js"></script>		
	<script src="js/homework.js"></script>
	
	<script>
		gUserID =  '<?php echo $openid; ?>'
		//alert(myUserId)
		App.load('home');
		
		/* android长按，滑动失灵？但是无法list下拉
		document.addEventListener('touchmove', function (event) {
		   event.preventDefault();
		}, false); */
	</script>
  </body>
</html>
