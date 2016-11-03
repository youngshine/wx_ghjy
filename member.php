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
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
	<meta name="kik-transparent-statusbar" content="true" charset="utf-8">	
	<link rel="stylesheet" href="src/app.min.css">
	<!-- 
	<link rel="stylesheet" href="assets/css/docs-exam.css"> -->
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
		
		.cell {
			margin: 0 10px;
			padding: 0 10px;
			background: #fff;
			line-height: 40px;
			border-bottom: 1px solid #eee;
		}
		.spacer {
			height:10px;
		}
		
		.arrow-r{
		  position: relative;
		}
		.arrow-r:after, .arrow-r:before {
		  border: 10px solid transparent;
		  border-left: 10px solid #fff;
		  width: 0;
		  height: 0;
		  position: absolute;
		  top: 0; 
		  right: -20px;
		  content: ' '
		}
		.arrow-r:before {
		  border-left-color: #888;
		  right: -21px;
		}
		
		.list {
			margin:0 0;
		}
		.list .listItem {
			background: #fcfcfc;
			padding: 10px 15px;
			border: 1px solid #eee;
			margin: 0px;
			border-radius:0px;
			-moz-border-radius:0px; /* Old Firefox */
		}
	</style>
	</head>
	<body>		
		<div class="app-page" data-page="account">
			<div class="app-topbar">
				<div class="app-title"></span>我的账号</div>
			</div>

			<div class="app-content">
			
				<div style="margin:15px;">	
					<div style="margin-bottom:10px;">
						<div class="label" style="color:#888;">基本信息</div>
						<div class="phone" style="background:#fff;height:40px;line-height:40px;padding-left:10px;border-bottom:1px solid #eee;"></div>
						<div class="name" style="background:#fff;height:40px;line-height:40px;padding-left:10px;border-bottom:1px solid #eee;"></div>
						<div class="grade" style="background:#fff;height:40px;line-height:40px;padding-left:10px;border-bottom:1px solid #eee;"></div>
						<div class="school" style="background:#fff;height:40px;line-height:40px;padding-left:10px;border-bottom:1px solid #eee;"></div>
						<div class="coupon" style="background:#fff;height:40px;line-height:40px;padding-left:10px;"></div>
					</div>
					
					<div style="margin-bottom:10px;">
						<div class="label" style="color:#888;">已购课程</div>
				   		 <ul class="app-list">
				   			<li>
				   				<span class="created" style="float:right;color:#888;"></span>
								<span class="taocan"></span>
								<span class="prepaidID" style="display:none"></span>
				   			</li>
				   		 </ul>
					</div>	

				</div>	
				
			</div>		
		</div>
		
		<div class="app-page" data-page="prepaid-more">
			<div class="app-topbar">
		        <div class="app-button left" data-back data-autotitle></div>
				<div class="app-title">课程内容</div>
			</div>
			<div class="app-content">
				 <ul class="app-list">
					<label></label>
					<li></li>
				 </ul>
			</div>	
		</div>
		
		<div class="app-page" data-page="phone-confirm">
			<div class="app-topbar">
				<div class="app-title">账号验证</div>
			</div>	
			<div class="app-content">
				<form style="margin:15px;">
					<input class="app-input" type="tel" name="phone" placeholder="手机号" maxlength="11">
					
					<div style="border-top:10px solid #eee;border-bottom:1px solid #eee;">
						<div style="float:left;width:50%;background:#fff;margin-right:10px;">
							<input class="app-input" type="tel" name="code" placeholder="验证码" maxlength="4">
						</div>	
						<div class="app-button blue getCode">获取验证码</div>	
						<input class="app-input" name="randomCode" placeholder="生成的" style="display:none;">
					</div>	
					
					<div class="form-button" style="clear:both;margin-top:15px;">
						<div class="app-button green ok" style="display:none;">提交</div>	
					</div>
					
				</form>
				
				<div style="clear:both;"></div>
				<div class="co" style="text-align:center;color:green;margin:15px;">
					服务电话：400-6680-118
				</div>
			</div>	  
		</div>
		
		<div class="app-page" data-page="bindEnt">
			<div class="app-topbar">
				<div class="app-title">账号注册</div>
			</div>	
			<div class="app-content">
				<form style="margin:15px;">
					<input class="app-input" type="tel" name="phone" placeholder="手机号" maxlength="15">
					<div style="clear:both;margin-top:10px;">
						<div class="app-button green next">下一步</div>	
					</div>
				</form>
				
				<div style="clear:both;"></div>
				<div class="note" style="color:#888;margin:10px;text-align:center;">
					你的微信尚未绑定系统<br>请输入你的手机号进行系统绑定验证
				</div>
			</div>	  
		</div>
		
		<div class="app-page" data-page="bindEnt-hasphone">
			<div class="app-topbar">
				<div class="app-button left" data-back data-autotitle></div>
				<div class="app-title">手机验证</div>
			</div>	
			<div class="app-content">
				<div class="spacer"></div>
				<div class="cell">
					<span>姓名</span><span class="name" style="float:right;color:#888;"></span>
				</div>
				<div class="cell">
					<span>学校</span><span class="school" style="float:right;color:#888;"></span>
				</div>
				<div class="cell">
					<span>手机</span><span class="phone" style="float:right;color:#888;"></span>
				</div>
				
				<div style="border-top:10px solid #eee;border-bottom:1px solid #eee;margin:0 10px;">
					<div style="float:left;width:50%;background:#fff;margin-right:10px;">
						<input class="app-input" type="tel" name="code" placeholder="验证码" maxlength="4">
					</div>	
					<div class="app-button blue getCode">获取验证码</div>	
					<input class="app-input" name="randomCode" placeholder="生成的" style="display:none;">
				</div>
				<div class="form-button" style="clear:both;margin:10px;">
					<div class="app-button green ok" style="display:none;">提交</div>	
				</div>
				
				
			</div>	  
		</div>
		
		<div class="app-page" data-page="bindEnt-nophone">
			<div class="app-topbar">
				<div class="app-button left" data-back data-autotitle></div>
				<div class="app-title">填写基本信息</div>
			</div>	
			<div class="app-content">
				<div class="spacer"></div>
				<div class="cell">
					<span>姓名</span>			
					<span style="float:right;margin:10px;" class="arrow-r"></span>
					<span class="name" style="float:right;color:#888;">未填写</span>
				</div>
				<div class="cell">
					<span>学校</span>
					<span style="float:right;margin:10px;" class="arrow-r"></span>
					<span class="school" style="float:right;color:#888;">未选择</span>
				</div>
				
				<div class="form-button" style="clear:both;margin:10px;">
					<div class="app-button green next">下一步</div>	
				</div>

				<div class="note" style="color:#888;margin:10px;text-align:center;">
					选择你所在地区的联盟学校
				</div>				
			</div>	  
		</div>
		
		<div class="app-page" data-page="bindEnt-nophone-confirm">
			<div class="app-topbar">
				<div class="app-button left" data-back data-autotitle></div>
				<div class="app-title">手机验证</div>
			</div>	
			<div class="app-content">
				<div style="margin:10px 10px 0px;background:#fff;line-height:40px;padding:0px 10px;">
					<span>姓名</span><span class="name" style="float:right;color:#888;"></span>
				</div>
				<div style="margin:0px 10px 0px;background:#fff;line-height:40px;padding:0px 10px;">
					<span>学校</span><span class="school" style="float:right;color:#888;"></span>
				</div>
				<div style="margin:0px 10px 0px;background:#fff;line-height:40px;padding:0px 10px;">
					<span>手机</span><span class="phone" style="float:right;color:#888;"></span>
				</div>
				
				<div style="border-top:10px solid #eee;border-bottom:1px solid #eee;margin:0 10px;">
					<div style="float:left;width:50%;background:#fff;margin-right:10px;">
						<input class="app-input" type="tel" name="code" placeholder="验证码" maxlength="4">
					</div>	
					<div class="app-button blue getCode">获取验证码</div>	
				</div>
				<div class="form-button" style="clear:both;margin:10px;">
					<div class="app-button green ok" style="display:none;">提交</div>	
				</div>
			</div>	  
		</div>
		
		<!-- 数字、文本及多行文本输入，公用-->
		<div class="app-page" data-page="input-text">
			<div class="app-topbar">
				<div class="app-button left" data-back data-autotitle></div>
				<div class="app-title">姓名</div>
				<div class="app-button right done">完成</div>
			</div>
			<div class="app-content">
				<div style="margin:10px;">
					<input class="app-input" maxlength="30">	
				</div>
			</div>	  
		</div>
		<!-- 选择省／市二级后，列表加盟学校共选中-->
	    <div class="app-page" data-page="select-cities">
	      <div class="app-topbar">
			  <div class="app-button left" data-back data-autotitle></div>
		      <div class="app-title">已开通城市</div>
	      </div>
		  <!-- 搜索 -->
	      <div class="app-content">
			  <form action="">
			    <input class="app-input no-icon-android" type="search" placeholder="Search...">
			    <input type="submit" style="display:none">
			  </form>
			 <ul class="app-list">
				<li>
					<span class="name"></span>
					<span class='province' style="display:none;"></span>
				</li>
			 </ul>
	 	  </div>
	    </div>
		
	    <div class="app-page" data-page="select-province">
	      <div class="app-topbar">
			  <div class="app-button left" data-back data-autotitle></div>
		      <div class="app-title">选择省份</div>
	      </div>
	      <div class="app-content">
			 <ul class="app-list">
				<li>
					<span class="name"></span>
					<span class='cities' style="display:none;"></span>
				</li>
			 </ul>
	 	  </div>
	    </div>	
	    <div class="app-page" data-page="select-city">
	      <div class="app-topbar">
			  <div class="app-button left" data-back data-autotitle></div>
	          <div class="app-title">选择地区</div>
	      </div>
	      <div class="app-content">
			 <ul class="app-list">
				<li></li>
			 </ul>
	 	  </div>
	    </div>
	    <div class="app-page" data-page="select-school">
	      <div class="app-topbar">
			  <div class="app-button left" data-back data-autotitle></div>
	          <div class="app-title">选择学校</div>
	      </div>	  
	      <div class="app-content">
			<div class="list">
				<div class="listItem">
					<div class="name"></div>
					<div class="addr" style="color:#888;font-size:0.8em;"></div>
					<!-- hidden -->
					<span class='schoolID' style="display:none;"></span>
					<span class='id' style="display:none;"></span>
				</div>	
			</div>
	 	  </div>
	    </div>
		
		<!-- Not necessary, but will make our lives a little easier 
		<script src="src/zepto.min.js"          ></script>
		-->
	    <script src="src/zepto.js"></script>
	    <script src="src/app.min.js"></script>
			
		<script src="js/main.js"></script>			
		<script src="js/member.js"></script>

		<!-- 
		<script src="js/select-province.js"></script>
		<script src="js/select-city.js"></script>
		-->
		<script>
			// 用户id，全局变量(大写)
			gUserID = '<?php echo $openid;?>'; 
			//MY_USER_ID = 'oMEqkuMuSli-xohZnAUO4v43cKj4'
			gDataUrl   = 'http://www.xzpt.org/wx_ghjy/script/';
			console.log(gUserID); 
			//showPrompt('加载中...');
			// 判断内如系统ent是否存在当前微信id，在咨询系统
			$.ajax({
			    url: gDataUrl + 'readStudentByWx.php',
			    data: { wxID: gUserID },
				dataType: "json",
			    success: function(result){
					console.log(result)
					if(result.success){
						App.load('account',result.data)
					}else{
						App.load('bindEnt')
					}
					//bindMember2Ent(phone,myUserId); 
			    },
			});	
			//App.load('home')
		</script>

		<!-- some kik goodness for demos 
		<script src="//cdn.kik.com/kik/1.0.9/kik.js"></script>  -->
	</body>
</html>
