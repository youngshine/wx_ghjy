<?php
	$code = $_GET["code"]; 
	$appid = "wx4f3ffca94662ce40"; //公众号code直接获得id，不需要access_token，企业号则需要
	$secret = "9998a307f7f99e9445d84439d6182355"; 

//通过code换取的是一个特殊的网页授权access_token,与基础支持中的access_token（该access_token用于调用其他接口）不同	
	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url); 
	curl_setopt($ch,CURLOPT_HEADER,0); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
	$res = curl_exec($ch); 
	curl_close($ch); 
	$json_obj = json_decode($res,true); //获得access_token & openid
	//根据openid和access_token查询用户信息 
	//$access_token = $json_obj['access_token']; 
	$openid = $json_obj['openid']; 
?>

<!DOCTYPE html>
<html>
  <head>
    <title>根号学苑－学习轨迹</title>
	<meta charset="utf-8" />
	<meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">	  
	<link rel="stylesheet" href="src/app.min.css">
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
          <div class="app-title">学习轨迹</div>
      </div>
      <div class="app-content">
		 <ul class="app-list">
			<li>
				<span class="zsdName"></span>
				<span class="ID" style="display:none;"></span>
				<span class="subjectID" style="display:none;"></span>
				<span class="subjectName" style="display:none;"></span>
				<span class="teacherName" style="display:none;"></span>
				<span class="weekday" style="display:none;"></span>
				<span class="timespan" style="display:none;"></span>
				<span class="created" style="display:none;"></span>
				<span style="float:right;">〉</span>
			</li>
		 </ul>
		
	  </div>	  
    </div>
	
	<div class="app-page" data-page="detail">
		<div class="app-topbar">
	        <div class="app-button left" data-back data-autotitle></div>
			<div class="app-title">教学详情</div>
			<div class="app-button right photos">照片资料</div>
		</div>
		<div class="app-content">
			<div style="margin:10px 10px 0;padding:10px;background:#fff;">
				<div class="title" style="word-wrap:break-word;text-align:center;"></div>
			</div> 
			<div style="margin:1px 10px 0;padding:10px;background:#fff;">
							
				<div style="color:#888;text-align:center;"><span class="created"></span>｜报读</div>
				<div>学科：<span class="subject"></span></div>
				<div>教师：<span class="teacher"></span></div>
				<div>上课：<span class="daytime"></span></div>

			</div>

			<!-- 驳回的批示 -->
			<div style="margin:1px 10px;padding:10px;background:#fff;">
				<div style="color:#888;text-align:center;">教学练习题目</div>
				<div class="topicteach" style="color:#888;">
					
				</div>
			</div>
			
			<!-- 驳回的批示 
			<div style="margin:1px 10px;padding:10px;background:#fff;">
				<div style="color:#888;text-align:center;">教学照片</div>
				<div class="studyphotos">
					
				</div>
			</div>
				-->
		</div>	
	</div>
	
	<div class="app-page" data-page="photos">
		<div class="app-topbar">
	        <div class="app-button left" data-back data-autotitle></div>
			<div class="app-title">教学笔记</div>
		</div>
		<div class="app-content">
			
			<!-- 驳回的批示 -->
			<div style="margin:1px 10px;padding:10px;background:#fff;">
				<!-- 
				<div style="color:#888;text-align:center;">教学照片</div>
					-->
				<div class="studyphotos">
					
				</div>
			</div>
				
		</div>	
	</div>
	

    <script src="src/zepto.js"></script>
    <script src="src/app.min.js"></script>
	
	<script src="js/main.js"></script>		
	<script src="js/pg-study.js"></script>
	
	<script>
		gUserID =  '<?php echo $openid; ?>'
		
		showPrompt('加载中...');
		// 判断内如系统ent是否存在当前微信id，在咨询系统
		$.ajax({
		    url: 'http://www.xzpt.org/wx_ghjy/script/readStudentFrEnt.php',
		    data: { wxID: MY_USER_ID },
			dataType: "json",
		    success: function(result){
				console.log(result)
				if(result.success && MY_USER_ID != ''){
					hidePrompt()
					App.load('home')
				}else{
					showPrompt('尚未验证账号');
				}
				//bindMember2Ent(phone,myUserId); 
		    },
		});	
		//App.load('home');
		
		/* android长按，滑动失灵？但是无法list下拉
		document.addEventListener('touchmove', function (event) {
		   event.preventDefault();
		}, false); */
	</script>
  </body>
</html>
