<!DOCTYPE html>
<html>
  <head>
    <title>课堂精彩瞬间</title>
	<meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="format-detection" content="telephone=no" />	
	<style>
		* {
		  margin: 0px;
		  padding: 0px;
		}
		.content{
			max-width:480px;
			margin:0 auto;
			padding:10px;
			text-align:center;
		}
		img {
			border-radius: 15px;
			-webkit-border-radius: 15px;
			-moz-border-radius: 15px;
		}
	</style>
  </head>

  <body>	
    <section id="menu">
		<div class="content">
			<h3 id="title"></h3>
			<p id="date" style="color:#888;"></p>	
			<p id="content" style="text-align:left;margin:10px 0;"></p>
			<div class="photos"></div>
			
			<p style="color:#888;font-size:0.9em;">根号教育服务平台｜400-6680-118</p>
		</div>
    </section>

    <script src="src/zepto.js"></script>
	
	<!-- js-sdk weixin 签名 -->
  <script type="text/javascript" src = "http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
  
  <script>
	/* var ID = <?php echo $_SERVER["QUERY_STRING"];?> */
	var ID = location.search.substr(1)
	ID = ID.split("&")[0]
	ID = ID.split("=")[1]
	  console.log(ID)

	wxSig()

	// js-sdk签名
	function wxSig(){
		$.ajax({
	        url: 'http://www.xzpt.org/wx_ghjy/script/weixinJS/wx_sig.php?url=' + location.href.split('#')[0] , // 没认证无法分享，借用认证公众号
			dataType: "json",
			success: function(result){
				var signPackage = result
				wx.config({
					debug: false,
	                  appId: signPackage.appId,
	                  timestamp: signPackage.timestamp,
	                  nonceStr: signPackage.nonceStr,
	                  signature: signPackage.signature,
					jsApiList: [
					  'onMenuShareTimeline',
					  'onMenuShareAppMessage',
	                  'chooseImage',
					  'previewImage',
					  'hideOptionMenu'
					]
				});

			    wx.ready(function () {
					// 在这里调用 API，默认的
					//wx.hideOptionMenu()
					wx.onMenuShareAppMessage({
			  		    title: '课堂精彩瞬间', // 分享标题
			  		    desc: '学生上课风采', // 分享描述
			  		    link: 'http://www.xzpt.org/wx_ghjy/classeseach_tplmsg.php?ID='+ID, // 分享链接
			  		    imgUrl: 'http://www.xzpt.org/wx_ghjy/assets/img/favicon.png', // 分享图标
			  		    type: '', // 分享类型,music、video或link，不填默认为link
			  		    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			  		    success: function () { 
			  		        // 用户确认分享后执行的回调函数
							console.log('ok')
			  		    },
			  		    cancel: function () { 
			  		        // 用户取消分享后执行的回调函数
							console.log('fail')
			  		    }
			  		});
				
					wx.onMenuShareTimeline({
					    title: '课堂精彩瞬间', // 分享标题
					    link: 'http://www.xzpt.org/wx_ghjy/classeseach_tplmsg.php?ID='+ID, // 分享链接
					    imgUrl: 'http://www.xzpt.org/wx_ghjy/assets/img/favicon.png', // 分享图标
					    success: function () { 
					        // 用户确认分享后执行的回调函数
					    },
					    cancel: function () { 
					        // 用户取消分享后执行的回调函数
					    }
					});
			
			    });
			}
		});
	}

  		var params = {
  			"ID": ID /* <?php echo $_SERVER["QUERY_STRING"];?> */
  		}
  		readData(params) 
		console.log(params)
  		function readData(obj){
  			$.ajax({
  			    url: 'script/readClassesEachByTplmsg.php',
				data: obj,
  				dataType: "json",
  				success: function(result){
  					//App.load('home', result.data[0]);
  				  	populateData(result.data[0])
					console.log(result)
  				},
  			});
  		}	
		function populateData(item){
			$('#title').text(item.title); //班级
			$('#date' ).text(item.created.substr(0,10) );
			$('#content' ).html(item.note ); //教师留言
			var divPhotos = $('.photos')
			var photos = item.photos.split(',');
			$.each(photos, function(i,photo){      
				console.log(photo)
				divPhoto = '<img src=' + photo + ' width=100% />'
				divPhotos.append(divPhoto)
			});
			// 微信预览图片
			$('img').bind('click', function (e) {
				wxPreviewImg(e.target.src, photos)
			})
		}
		
		function wxPreviewImg(current,photos){
			var url = location.href;
			url =  url.substring(0,url.lastIndexOf("/")+1); //.replace("//","/"); 
			console.log(url); //当前
			var pic_list = []
			$.each(photos, function(i,photo){      
				pic_list.push(url + photo)
			});
			console.log(pic_list)
			WeixinJSBridge.invoke('imagePreview', {  
				'current' : current,  
				'urls' : pic_list  
			});
		}
      </script>

  </body>
</html>
