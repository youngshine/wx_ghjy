<?php
/**
 * 根号数理化
 * 网页授权code取得openId
*/
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
    <title>根号学苑</title>
	<meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="format-detection" content="telephone=no" />	
	<style>
		* {
		  margin: 0px;
		  padding: 0px;
		}
		.toolbar {
		  -webkit-box-sizing: border-box;
		  background: #6D84A2 url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAArCAIAAAA2QHWOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEpJREFUeNpNjCEOgEAQA5v9/9eQaAQCd57L0WXTDSmimXZEse1HnNcIIINZYTPVv7Ac4/EWe7OTsC/ec+nDgcj/dpcH7EXt8up4AfRWcOjLIqWFAAAAAElFTkSuQmCC) repeat-x;
		  border-bottom: 1px solid #2D3642;
		  height: 45px;
		  padding: 10px;
		  position: relative;
		  text-align:center;
		}
		.content{
			max-width:480px;
			margin:0 auto;
			padding:10px;
			text-align:center;
		}
	</style>
  </head>

  <body>	
    <section id="menu">
        <div class="toolbar">
			<span style="color:#000;">我的推广二维码</span>
		</div>
		<div class="content">
			<h3 id="title">分享</h3>
			<p id="date" style="color:#888;"></p>	
			<p id="content" style="text-align:left;"></p>
			<p style="margin:10px 0;">
				<img id="photo" width="280"  />	
			</p>
		</div>
    </section>

    <script src="src/zepto.js"></script>
	<!-- js-sdk weixin 签名 -->
	<script type="text/javascript" src = "http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>	
	

      <script>
		
		// 会员openId，取得会员member_id
		MY_USER_ID =  '<?php echo $openid; ?>'
	
		// 获取sdk签名, main.js
		//wxSig();
		
		function wxSig(){
			$.ajax({
	            url: 'script/weixinJS/wx_sig.php?url=' + location.href.split('#')[0],
				dataType: "json",
				success: function(result){
					var signPackage = result
					wx.config({
						debug: true,
	                    appId: signPackage.appId,
	                    timestamp: signPackage.timestamp,
	                    nonceStr: signPackage.nonceStr,
	                    signature: signPackage.signature,
						jsApiList: [
						  'onMenuShareTimeline','onMenuShareAppMessage',
						  'getLocation','openLocation',
	                      'chooseImage','previewImage','uploadImage','downloadImage',
						  'hideOptionMenu'
						]
					});
			
				    wx.ready(function () {
						// 在这里调用 API，默认的
						wx.hideOptionMenu()
				    });
				}
			});
		}

		var imgUrl = location.search.substr(1);
		$('img').attr('src',imgUrl); //ticket取得二维码图片
		// 微信预览图片
		$('img').bind('click', function (e) {
			wxPreviewImg(e.target.src)
		})		
		
		// 分享给朋友
		$('#title').on('click',function(){
			wx.onMenuShareAppMessage({
			    title: '根号学苑', // 分享标题
			    desc: '互联网＋上门家教', // 分享描述
			    link: '', // 分享链接
			    imgUrl: '', // 分享图标
			    type: '', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			    success: function () { 
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () { 
			        // 用户取消分享后执行的回调函数
			    }
			});
  		})
		

  		//readData(params) 
		
  		function readData(obj){
  			$.ajax({
  			    url: 'http://fulindoor.sinaapp.com/script/readNewsNotify.php?data=' + JSON.stringify(obj),
  				dataType: "jsonp",
  				jsonp: 'callback',
  				success: function(result){
  					//App.load('home', result.data[0]);
  				  	populateData(result.data[0])
					console.log(result)
  				},
  			});
  		}	
		function populateData(item){
			$('#title').text(item.news_name);
			$('#date' ).text(item.news_date );
			$('#content' ).html(item.news_content );
			$('img').attr('src',item.news_photo); //新浪云路径
			// 微信预览图片
			$('img').bind('click', function (e) {
				wxPreviewImg(e.target.src)
			})
		}
		
		function wxPreviewImg(current){
			var pic_list = [
				current // 图片保存在新浪云，已经有绝对路径
			]; //图片列表
			WeixinJSBridge.invoke('imagePreview', {  
				'current' : current,  
				'urls' : pic_list  
			});
		}  
      </script>

  </body>
</html>
