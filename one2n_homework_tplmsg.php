<!DOCTYPE html>
<html>
  <head>
    <title>一对N上课内容作业</title>
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
	</style>
  </head>

  <body>	
    <section id="menu">
		<div class="content">
			<h3 id="title"></h3>
			<div style="color:#888;">
				<span id="teacher"></span> ｜ <span id="date"></span>	
			</div>	
			<div id="content" style="text-align:left;margin:10px 0;"></div>
			<div class="photos"></div>
		</div>
    </section>

    <script src="src/zepto.js"></script>
      <script>
	/*
  		var params = {
  			"ID": <?php echo $_SERVER["QUERY_STRING"];?> 
  		} */
	
		var ID = location.search.substr(1)
		ID = ID.split("&")[0]
		ID = ID.split("=")[1]
		console.log(ID)
	
		var params = {
			"ID": ID
		}
		console.log(params)
	
  		readData(params) 
		
  		function readData(obj){
  			$.ajax({
  			    url: 'script/readOne2nHomework.php',
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
			$('#title').text('一对N课程要点作业');
			$('#teacher').text(item.teacherName+'老师'); //班级
			$('#date' ).text(item.created.substr(0,16) );
			$('#content' ).html('上课要点：'+item.keypoint ); //教师留言
			var divPhotos = $('.photos')
			var photos = item.photos.split(',');
			$.each(photos, function(i,photo){      
				console.log(photo)
				divPhoto = '<img src=' + photo + ' width=100% />'
				divPhotos.append(divPhoto)
			});
			// 微信预览图片
			$('img').bind('singleTap', function (e) {
				//wxPreviewImg(e.target.src, photos)
				WeixinJSBridge.invoke('imagePreview', {  
					'current' : e.target.src,  
					'urls' : [e.target.src] 
				});
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
