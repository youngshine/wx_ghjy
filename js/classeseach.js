// 自家小孩（学生，可能多个）某次精彩瞬间
App.controller('home', function (page,request) {
	var $list = $(page).find('.list'),
		$listItem = $(page).find('.listItem').remove()	
	
	var params = {
		"wxID": gUserID		
	}
	readData(params)
	console.log(params)
	
	function readData(obj){		
		$.ajax({
	    	url: gDataUrl + 'readClassesEach.php',
			data: obj,
			dataType: "json",
			success: function(result){
				//hidePrompt()
				console.log(result.data)
				populateData(result.data)
			},
			error: function(){
				console.log('error')
			}
		});
	}	
	
	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		items.forEach(function (item) {
			var $node = $listItem.clone(true);
			$node.find('.classTitle').text(item.title);
			$node.find('.time').text(item.created.substr(2,8));
			//display:none
			$node.find('.id').text(item.homeworkID);
			$node.find('.keypoint').text(item.keypoint);		
			
			var $photos = $node.find('.photos')
			// 图片列表：尚未下载到自己服务器的 mediaIds
			var photos = item.photos.split(',');
			/*
			if(photos==''){
				photos = item.mediaIds.split(','); 
			} */
			$.each(photos, function(i,photo){      
				console.log(photo)
				//var img = 'http://www.xzpt.org/wxqy/ghjy/' + photo
				$img = '<img src='+photo+' style="width:100px;height:100px;padding:2px;" />'
				$photos.append($img)
			});	
					
			$list.append($node);
		});
		
		$list.find('.listItem').find('img').bind({
			click: function(e){
				//console.log(e.target)
				var imgs = $(this).parent().children();
				var urls = [];
				imgs.forEach(function (img) {
					urls.push(img.src)
				})
				console.log(urls);
				WeixinJSBridge.invoke('imagePreview', {  
					'current' : e.target.src,  
					'urls' : urls  
				});
			}
		})	
	}
}); // ends controller



