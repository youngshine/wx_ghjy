App.controller('account', function (page,request) {
	$(page).find('.phone').text('电话：'+request.phone)
	$(page).find('.name').text('姓名：'+request.studentName)
	$(page).find('.grade').text('年级：'+request.grade)
	$(page).find('.district').text('地区：'+request.district)
	$(page).find('.coupon').text('代金券：'+request.coupon + '元')

 	var	$list     = $(page).find('.app-list'),
 		$listItem = $(page).find('.app-list li').remove()

	var params = {
		"wxID": MY_USER_ID
	}
	readData(function(data){
		populateData(data)	
		handleData( $list, data )
	}, params );	
	// 已购课程
	function readData(callback, obj){
		showPrompt('加载中...');		
		$.ajax({
	    	//url: DATA_URL + 'readExamList.php?data=' + JSON.stringify(obj),
			url: DATA_URL + 'readPrepaidList.php',
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
		items.forEach(function (item) {
			var $node = $listItem.clone(true);
			
			$node.find('.taocan').text(item.taocan);
			$node.find('.created').text(item.created.substr(2,8));
			$node.find('.prepaidID').text(item.prepaidID); //hidden

			$list.append($node);
		});
	}
	function handleData(list,items){
		list.find('li').bind({
			click: function(e){
				var obj = {
					prepaidID : $(this).find('.prepaidID').text(), 
					taocan    : $(this).find('.taocan').text(),
				}
				App.load('prepaid-more', obj);
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
});

// 课程学习的详情
App.controller('prepaid-more', function (page,request) {
	console.log(request)
 	var	$list     = $(page).find('.app-list'),
 		$listItem = $(page).find('.app-list li').remove()
	
	
	$(page).find('ul label' ).text(request.taocan);

	var params = {
		"prepaidID": request.prepaidID, //购买课程
	}
	readData(function(data){
		populateData(data)	
		//handleData( $list, data )
	}, params );

	function readData(callback, obj){
		showPrompt('加载中...');		
		$.ajax({
			url: DATA_URL + 'readStudentstudyListByPrepaid.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				hidePrompt()
				console.log(result)
			    //populateData(result.data)
				callback(result)
			},
			error: function(xhr, type){
				showPrompt('课程内容出错');	
			}
		});
	}
	function populateData(items){
		items.forEach(function (item) {
			var $node = $listItem.clone(true);		
			$node.text(item.zsdName);
			$list.append($node);
		});
	}
		
	$(page).swipeRight(function () {
		App.back()
	})	
});


// 手机验证账号
App.controller('phone-confirm', function (page,request) {
	var me = this;
	console.log(request)
	var btnOk = $(page).find('.ok'),
		btnGetCode = $(page).find('.getCode'),
		$phone = $(page).find('.app-input[name=phone]'),
		$code = $(page).find('.app-input[name=code]'),
		$randomCode = $(page).find('.app-input[name=randomCode]') //hidden

	var phone,code,randomCode
	
	btnGetCode.on('click',function(e){
		if (btnGetCode.text() != '获取验证码') return
			
		$(page).find('.app-input').blur(); // 关闭软键盘
		phone = $phone.val().trim(); //电话号码
		
		if( phone.length != 11 ||isNaN(phone) ){
			showPrompt('手机号不正确')
			setTimeout(function () { hidePrompt() }, 1500);
			//btnOk.css('display','none')
			return;	
		}
		
		//获得验证码，提交前，不能再修改手机
		$phone.attr('disabled','disabled') 
		btnOk.css('display','block') //提交
	 	/*
	 	   存在的话，这里生成随机码4位 Math.floor(Math.random() * (max - min + 1)) + min;
	 	    服务端发送随机码到手机短信或邮箱。
	 	   用户收到随机码，输入的和客户端生成的一致，则点下一步到密码重置页面		*/
	 	randomCode = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;		
	 	$randomCode.val(randomCode);
		
		// 发送验证码到 手机号
		var obj = {
			phone: phone,
			code: randomCode
		}	
		$.ajax({  
			url: 'script/SUBMAIL/demo/message_xsend.php',
			data: obj,
			success: function (data, status) { 
				
			}
		});
		
		//editPhone.attr('readOnly',true)
		
		//验证码发送成功30秒内不能再次发送
		var sec = 60;
		function count(){
			sec = sec - 1;
			if(sec>0){
				btnGetCode.text(sec + '秒后重获验证');
				btnGetCode.css('background','gray')
				s = setTimeout(function(){count();},1000);
			}else{
				btnGetCode.text('获取验证码');
				btnGetCode.css('background','blue') //'#32CD32')
				$phone.removeAttr("disabled")
			}
		}
		count();
		
	})

	btnOk.on('click', function (e){
		$(page).find('.app-input').blur(); // 关闭软键盘
		
		if( $randomCode.val() != $code.val() ){
			showPrompt('输入验证码错误')
			setTimeout(function () { hidePrompt() }, 1500);
			return;	
		}
		
		//showPrompt('手机验证成功')
		//setTimeout(function () { hidePrompt() }, 3000);
		
		//bindMember2Ent(phone,MY_USER_ID)
		//绑定到企业内部系统，phone作为唯一标识
		showPrompt('绑定中...');	
		$.ajax({
		    url: DATA_URL +'bindMember2Ent.php',
		    data: { phone: phone, wxID: MY_USER_ID },
			dataType: "json",
		    success: function(result){
				hidePrompt()
				/* 返回账号，电话以外信息暂时空白
				var obj = {
					"phone"   : phone,
					"name"    : "",
					"grade"   : "",
					"district": ""
				} */
				App.load('account',result) //??
		    },
		});	

	})
});	

