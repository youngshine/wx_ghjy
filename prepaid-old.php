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
    <title>根号学苑－购买课程</title>
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
          <div class="app-title">购买课程</div>
		  <div class="app-button right prepaid-hist">历史记录</div>
      </div>
      <div class="app-content">
		  <div class="list">
  		    <div class="listItem">
  				<div class="title">体验课：2小时100元</div>
  				<div class="subtitle">
  					<div>
  						<span class="unitprice">原价150元，代金券抵扣50元</span>
  						<span class="prepaid" style="float:right;color:orangered;">购买</span>
  					</div>
  				</div>
  			</div>
			
  			<br>
			  
		    <div class="listItem">
				<div class="title">小学10小时，价格800元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：80元/小时、原价：140元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>	
		    <div class="listItem">
				<div class="title">小学30小时，价格2250元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：75元/小时、原价：140元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>
		    <div class="listItem">
				<div class="title">小学60小时，价格4200元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：70元/小时、原价：140元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>	
		    <div class="listItem">
				<div class="title">小学100小时，价格6500元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：65元/小时、140元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>
			
			<br>
			
		    <div class="listItem">
				<div class="title">初中10小时，价格950元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：95元/小时、原价：165元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>	
		    <div class="listItem">
				<div class="title">初中30小时，价格2700元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：90元/小时、原价：165元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>
		    <div class="listItem">
				<div class="title">初中60小时，价格5100元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：85元/小时、原价：165元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>	
		    <div class="listItem">
				<div class="title">初中100小时，价格7500元</div>
				<div class="subtitle">
					<div>
						<span class="unitprice">现价：75元/小时、原价165元/小时</span>
						<span class="prepaid" style="float:right;color:orangered;">购买</span>
					</div>
				</div>
			</div>
		  </div>
		  
		<div style="clear:both;"></div>
		<div class="co" style="text-align:center;color:green;margin:15px;">
			服务电话：400-6680-118
		</div>
			<!-- 
			<form style="background:#fff;margin:15px;">
				<select class="app-input" id="selCheckname"  style="background:#fff;border-bottom:1px solid #ddd;" >
				<option value="">请选择课程套餐...</option> 
				<option value="">小学10小时800元（原价1400元）</option> 
				<option value="">小学30小时2250元（原价4200元）</option> 
				<option value="">小学60小时4200元（原价8400元）</option> 
				<option value="">小学100小时6500元（原价14000元）</option> 
				<option value="">初中10小时950元（原价1650元）</option> 
				<option value="">初中30小时2700元（原价4950元）</option> 
				<option value="">初中60小时5100元（原价9900元）</option> 
				<option value="">初中100小时7500元（原价16500元）</option>
				</select>	
			</form>
			<div class="app-button green app-submit" style="margin:0px 15px;">提交</div>
		
		<div class="co" style="text-align:center;color:green;margin:15px;">
			服务电话：400-6680-118
		</div>
		-->
	  </div>	  
    </div>
	
	<div class="app-page" data-page="hist">
		<div class="app-topbar">
			<div class="left app-button" data-back data-autotitle></div>  
			<div class="app-title"></span>已购课程</div>
		</div>

		<div class="app-content">
		
			<div style="margin:15px;">	

				
				<div style="margin-bottom:10px;">
			   		 <ul class="app-list">
			   			<li>
			   				<span class="created" style="float:right;color:#888;"></span>
							<span class="taocan"></span>
			   				
			   			</li>
			   		 </ul>
				</div>	

			</div>	
		</div>		
	</div>
	

    <script src="src/zepto.js"></script>
    <script src="src/app.min.js"></script>
	
	<script src="js/main.js"></script>		
	<script src="js/pg-prepaid.js"></script>
	
	<script>
		MY_USER_ID =  '<?php echo $openid; ?>'
		
		showPrompt('加载中...');
		// 判断内如系统ent是否存在当前微信id，在咨询系统
		$.ajax({
		    url: 'http://www.xzpt.org/wx_ghjy/script/readStudentFrEnt.php',
		    data: { wxID: MY_USER_ID },
			dataType: "json",
		    success: function(result){
				console.log(result)
				if(result.success){
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
