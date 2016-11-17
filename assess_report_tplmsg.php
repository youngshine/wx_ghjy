<!DOCTYPE html>
<html>
  <head>
    <title>课程测评报告</title>
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
	</style> 
  </head>

  <body>

    <div class="app-page" data-page="home">

      <div class="app-content">
		<div style="padding:10px;text-align:center;">
			<div class="subject"></div>
			<div class="time" style="color:#888;font-size:0.8em;"></div>
		</div>
		<div id="canvas-holder" style="text-align:center;margin:10px 0;">
  			<canvas id="chartBar" width="300" height="300" />
  		</div>
		<!-- legend -->
		<div style="padding:10px;">
			<div class="note"></div>
			<div class="schoolsub" style="color:#888;text-align:center;"></div>
		</div>
	  </div>	
    </div>

    <script src="src/zepto.js"></script>
    <script src="src/app.min.js"></script>

	<script src="js/Chart.js"></script>
	
    <script>
		var ID = location.search.substr(1)
		ID = ID.split("&")[0]
		ID = ID.split("=")[1]
		console.log(ID)
	
		var params = {
			"studentID": ID
		}
		$.ajax({
		    url: 'script/readStudentAssessResult.php',
			data: params,
			dataType: "json",
			success: function(result){
				App.load('home', result.data);
				console.log(result.data)
			},
		});
		
		// bar chart
		App.controller('home', function (page,request) {
			var me = this;	

			$(page).find('.schoolsub').html(request.schoolsub)

			var jsonObj = JSON.parse(request.assessResult)
			console.log(jsonObj.result)
			
			$(page).find('.subject').html(jsonObj.subject)	
			
			//var timestamp3 = 1403058804;
			var newDate = new Date();
			newDate.setTime(jsonObj.time) //(timestamp3 * 1000);
			console.log(newDate.toLocaleDateString());
			$(page).find('.time').html(newDate.toLocaleDateString())	
			
			var arrResult = jsonObj.result
			
			// 整数据
			var arrLabels = [],
				arrData1 = [],
				arrData2 = [],
				html = ''

			arrResult.forEach(function(item){
				arrLabels.push(item.name);
				arrData1.push(item.value1)
				arrData2.push(item.value2)	
				
				html += '<div>'+item.name+'：'+item.value1+'／'+item.value2+'</div>'
				if(item.value1 < item.value2){
					html += '<div style="color:red;">有错题，建议补习</div><br>'
				}else{
					html += '<div style="color:green;">干的不错，保持</div><br>'
				}
			})
		
			var data = {
				labels : arrLabels,
				datasets : [{
					fillColor : "rgba(0,255,0,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : arrData1
				},{	
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : arrData2
				}]
			}
			console.log(data)

			var canvas = $(page).find('#chartBar');
			var ctx = canvas.get(0).getContext("2d");
			//var ctx = document.getElementById("chart-area").getContext("2d");
			//console.log(document.getElementById("chart-area"))
			//var ctx = $("#chart-area").get(0).getContext("2d");
			$(page).on('appShow',function(){
				window.myPie = new Chart(ctx).Bar(data);
			})	
			
			$(page).find('.note').html(html)

	return
			var strWeak = '',
				strLearn = ''
			console.log(barData.datasets[0].data)

			for(var i=0;i<barData.labels.length;i++){
				if(barData.datasets[0].data[i]>=2){
					strWeak += barData.labels[i] + '；'
				}
				if(barData.datasets[0].data[i]>=3){
					strLearn += barData.labels[i] + '；'
				}
			}	
			strWeak = strWeak=='' ? '无' : strWeak;
			strLearn = strLearn=='' ? '无' : strLearn; 
			$(page).find('.note').html('说两句')
		}); 
	
     </script>

  </body>
</html>
