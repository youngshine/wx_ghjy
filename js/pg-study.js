// 学习轨迹
App.controller('home', function (page) {	
	
 	var	$list     = $(page).find('.app-list'),
 		$listItem = $(page).find('.app-list li').remove()
		//search = $(page).find('input[type=search]');	

	var params = {
		"wxID": gUserID
	}
	
	readData(function(data){
		populateData(data)	
		handleData( $list, data )
	}, params );

	function readData(callback, obj){
		showPrompt('加载中...');		
		$.ajax({
	    	//url: dataUrl + 'readExamList.php?data=' + JSON.stringify(obj),
			url: gDataUrl + 'readStudyList.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				hidePrompt()
				console.log(result)
			    //populateData(result.data)
				callback(result)
			},
			error: function(xhr, type){
				showPrompt('出错');	
			}
		});
	}

	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		
		var paid = ''; // 已经付款结束
		items.forEach(function (item) {
			if(item.paid != paid){
				paid = item.paid;
				var checkedStatus = item.paid==0?'学习中':'已结束'
				$list.append('<div style="padding:5px 15px;background:#d8e4e5;color:#888;">' + 
					checkedStatus + '</div>');
			}
			var $node = $listItem.clone(true);
			
			$node.find('.zsdName').text(item.zsdName);
			$node.find('.ID').text(item.studentstudyID); // hidden
			$node.find('.created').text(item.created.substr(2,8));
			$node.find('.subjectID').text(item.subjectID); 
			$node.find('.subjectName').text(item.subjectName); 
			$node.find('.teacherName').text(item.teacherName); // hidden
			$node.find('.weekday').text(item.teach_weekday);
			$node.find('.timespan').text(item.teach_timespan);

			$list.append($node);
		});
	}	
	function handleData(list,items){
		list.find('li').bind({
			click: function(e){
				var obj = {
					ID		: $(this).find('.ID').text(), //studentstudyID
					zsd     : $(this).find('.zsdName').text(),
					subjectID : $(this).find('.subjectID').text(),
					subject : $(this).find('.subjectName').text(),
					teacher : $(this).find('.teacherName').text(),
					weekday : $(this).find('.weekday').text(),
					timespan : $(this).find('.timespan').text(),
					created : $(this).find('.created').text()
				}
				App.load('detail', obj);
				/*
				// 利用下标查找数组元素
				var selected = []
				for(var i in items){
					if(items[i].studentstudyID == $(this).find('.ID').text()){
						selected = items[i] 
						break; 
					}
				} 
				console.log(selected)
				App.load('detail', selected);
				*/
			},
		})		
	}	
}); // 学习轨迹－课程列表

// 课程学习的详情
App.controller('detail', function (page,request) {
	console.log(request)
	$(page).find('.title' ).text(request.zsd);
	$(page).find('.created').text(request.created.substr(0,10));
	$(page).find('.subject').text(request.subject);
	$(page).find('.teacher').text(request.teacher);
	$(page).find('.daytime').text(request.weekday+request.timespan);
	
	// 教学图片资料
	var btnPhotos= $(page).find('.photos')
	
	btnPhotos.on('click',function(e){
		App.load('photos',params)
	})

	var params = {
		"studentstudyID": request.ID, //报读课程
		"subjectID": request.subjectID
	}
	readData(function(data){
		//records = data;
		populateData(data)	
		//handleData( $list, data )
	}, params );

	function readData(callback, obj){
		showPrompt('加载教学题...');		
		$.ajax({
			url: gDataUrl + 'readStudyTopicList.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				hidePrompt()
				console.log(result)
			    //populateData(result.data)
				callback(result)
			},
			error: function(xhr, type){
				showPrompt('出错');	
			}
		});
	}
	function populateData(items){
		items.forEach(function(item){
			var fullDone = '未做题'; // =0
			switch(item.done){
				case '1': fullDone = '做错';break;	
				case '2': fullDone = '解释后做对';break;
				case '3': fullDone = '做对';break;	
			}
			
			var listItem = '<div>' + 
				item.content + '</div>' +
				'<div style="color:orangered;">'+fullDone + '</div><br>'
			$('.topicteach').append(listItem)
		})
		// 题目中图片不是img标签，是base64
		$('img').on('click',function(e){
			WeixinJSBridge.invoke('imagePreview', {  
				'current' : e.target.src,  
				'urls' : [e.target.src]  
			});
		})
	}
	
	/*
	// 加载教学照片
	readPhotos(function(data){
		populatePhotos(data)	
	}, params );

	function readPhotos(callback, obj){
		showPrompt('加载教学照片...');
		$.ajax({
			url: DATA_URL + 'readStudyPhotosList.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				hidePrompt()
				console.log(result)
				callback(result)
			},
			error: function(xhr, type){
				showPrompt('出错');	
			}
		});
	}
	function populatePhotos(items){
		items.forEach(function(item){
			var listItem = 
				'<div style="color:orange;">'+ item.created.substr(2,8) + '</div>' +
				'<div><img width=100% src=' + 'http://www.xzpt.org/app/teacher/'+
				item.photo + ' /></div><br>'
			$('.studyphotos').append(listItem)
		})
		// 题目中图片不是img标签，是base64
		$('img').on('click',function(e){
			WeixinJSBridge.invoke('imagePreview', {  
				'current' : e.target.src,  
				'urls' : [e.target.src]  
			});
		})
	} */
		
	$(page).swipeRight(function () {
		App.back()
	})	
});

// 课程教学照片
App.controller('photos', function (page,request) {
	
	// 加载教学照片
	readPhotos(function(data){
		populatePhotos(data)	
	}, request );

	function readPhotos(callback, obj){
		showPrompt('加载教学照片...');
		$.ajax({
			url: gDataUrl + 'readStudyPhotosList.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				hidePrompt()
				console.log(result)
				callback(result)
			},
			error: function(xhr, type){
				showPrompt('出错');	
			}
		});
	}
	function populatePhotos(items){
		items.forEach(function(item){
			var listItem = 
				'<div style="color:orange;">'+ item.created.substr(2,8) + '</div>' +
				'<div><img width=100% src=' + 'http://www.xzpt.org/app/teacher/'+
				item.photo + ' /></div><br>'
			$('.studyphotos').append(listItem)
		})
		// 题目中图片不是img标签，是base64
		$('img').on('click',function(e){
			WeixinJSBridge.invoke('imagePreview', {  
				'current' : e.target.src,  
				'urls' : [e.target.src]  
			});
		})
	}
		
	$(page).swipeRight(function () {
		App.back()
	})	
});




