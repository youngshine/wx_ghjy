App.controller('account', function (page,request) {
	console.log(request.many[1])
	$(page).find('.addr').text('我的地址：'+request.many[0].addr)
	$(page).find('.phone').text('我的电话：'+request.many[0].phone)
	$(page).find('.schoolsub').text('报读校区：'+request.many[0].fullname)
	//$(page).find('.school').text('学校：'+request.fullname) //分校区，不是choolName 
	//$(page).find('.coupon').text('代金券：'+request.coupon + '元')
	
	var btnAccnt = $(page).find('.accnt')
	btnAccnt.on('click',function(){
		App.load('accnt',{"wxID":gUserID} )
	})

 	var	$list     = $(page).find('.app-list'),
 		$listItem = $(page).find('.app-list li').remove()

/*
	var params = {
		"wxID": gUserID //MY_USER_ID
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
			url: gDataUrl + 'readPrepaidList.php',
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
*/
	populateData(request.many)	
	handleData( $list )
		
	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		items.forEach(function (item) {
			var $node = $listItem.clone(true);
			
			$node.find('.studentName').text(item.studentName);
			$node.find('.grade').text(item.grade);
			$node.find('.id').text(item.studentID); //hidden

			$list.append($node);
		});
	}
	function handleData(list,items){
		list.find('li').bind({
			click: function(e){
				var obj = {
					studentID : $(this).find('.id').text(), 
				}
				App.load('kclist', obj);
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

// 缴费流水
App.controller('accnt', function (page,request) {
	var $list = $(page).find('.list'),
		$listItem = $(page).find('.listItem').remove()	
/*
	var student_list = []
	$.each(request,function(index,item){
		console.log(item.studentID)
		student_list.push(item.studentID)
	})
	console.log(student_list)
*/	
	var params = { 
		//"student_list": student_list 
		"wxID": request.wxID
	}
	
	readData(function(data){
		populateData(data)	
		//handleData( $list )
		//records = data;
	}, params );

	function readData(callback, obj){
		showPrompt('加载中...');		
		$.ajax({
	    	url: gDataUrl + 'readAccntList.php',
			data: obj,
			dataType: "json",
			success: function(result){
				hidePrompt()
				console.log(result)
				callback(result.data)
			},
		});
	}

	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		var grp = ''
		items.forEach(function (item) {
			if(item.accntDate != grp){
				grp = item.accntDate
				$list.append('<label style="padding:10px;color:#888;font-size:0.8em;">' + 
					grp + '</label>')
			}
			var $node = $listItem.clone(true);
			$node.find('.accntType').text(item.accntType); 
			$node.find('.amount').text(item.amount+'元');
			$node.find('.student').text(item.studentName); 
			//$node.find('.time').text(item.accntDate);
			$node.find('.id').text(item.accntID);			
			$list.append($node);
		});
	}	
}); // ends controller

// 课程及其内容
App.controller('kclist', function (page,request) {
	console.log(request)
 	var	$list     = $(page).find('.list'),
 		$listItem = $(page).find('.listItem').remove()

	var tab1 = $(page).find('.class'),
		tab2 = $(page).find('.one2one')

	var params = {
		"studentID": request.studentID, //购买课程
	}
	// 默认，读取报读课程班级
	readData()
		
	tab1.on('click',function(e){
		$(this).css('background','#fff');
		tab2.css('background','#eee')
		
		// 默认，读取报读课程班级
		readData()
	})
	tab2.on('click',function(e){
		$(this).css('background','#fff');
		tab1.css('background','#eee')

		// 读取拔都一对一课程内容
		$.ajax({
			url: gDataUrl + 'readStudentstudyListByStudent.php',
			data: params,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				console.log(result)
			    populateData(result.data)
			},
		});
	})

	function readData(){
		$.ajax({
			url: gDataUrl + 'readClassStudentListByStudent.php',
			data: params,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				console.log(result)
			    populateData(result.data)
			},
		});
	}
	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		
		items.forEach(function (item) {
			var $node = $listItem.clone(true);		
			$node.find('.title').text(item.title);
			$node.find('.hour').text(item.hour);
			$node.find('.timely').text(item.timely_list);
			$list.append($node);
		});
	}
		
	$(page).swipeRight(function () {
		App.back()
	})	
});


// 2 未验证过，绑定账号
App.controller('bindEnt', function (page,request) {
	var me = this;
	console.log(request)
	var btnNext = $(page).find('.next'),
		$phone = $(page).find('.app-input[name=phone]'),
		phone = '';
	
	btnNext.on('click',function(e){	
		$(page).find('.app-input').blur(); // 关闭软键盘
		phone = $phone.val().trim(); //电话号码
		
		if( phone.length != 11 ||isNaN(phone) ){
			toast('手机号格式错误');return;	
		}
		var obj = {
			phone: phone,
			wxID: gUserID
		}
		
		// 判断手机已经存在
		readData(obj)
		function readData(obj){
			showPrompt('加载中...');		
			$.ajax({
				url: gDataUrl + 'readStudentByPhone.php',
				data: obj,
				dataType: "json", //jsonp: 'callback',
				success: function(result){
					hidePrompt()
					console.log(result)
					if(result.success){					
						App.load('bindEnt-hasphone',result.data) //已存在，未绑定微信
					}else{
						App.load('bindEnt-nophone',obj) //不存在，注册
					}
				},
				error: function(xhr, type){
					showPrompt('出错，请退出');	
				}
			});
		}			
	})
});	

// 1.存在电话号码，线下注册过，未绑定微信
App.controller('bindEnt-hasphone', function (page,request) {
	var me = this;
	console.log(request)	
	$(page).find('.name').text(request.studentName)
	$(page).find('.school').text(request.schoolName)
	$(page).find('.phone').text(request.phone)

	var phone,code,randomCode
	var $code = $(page).find('.app-input[name=code]') //填写
	var btnGetCode = $(page).find('.getCode');
	var btnOk = $(page).find('.ok');
	
	btnGetCode.on('click',function(e){
		if (btnGetCode.text() != '获取验证码') return
		$(page).find('.app-input').blur(); // 关闭软键盘
		/*
		App.dialog({
			title	     : '发送手机验证码？', 
			okButton     : '确定',
			cancelButton : '取消'
		}, function (choice) {
			if(choice){
				sendCode()
				btnOk.css('display','block') //提交
			}
		}); */
		sendCode()
		btnOk.css('display','block') //提交
		// 生成、发送验证短信
		function sendCode(){
	 	 	/* 发送到手机或邮箱	*/
	 	 	randomCode = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;		
	 		var obj = {
	 			phone: request.phone,
	 			code: randomCode
	 		}	
	 		$.ajax({  
	 			url: 'script/SUBMAIL/demo/message_xsend.php',
	 			data: obj,
	 			success: function (data, status) { }
	 		});
			
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
					//$phone.removeAttr("disabled")
				}
			}
			count();
		}		
	})
	
	btnOk.on('click', function (e){
		$(page).find('.app-input').blur(); // 关闭软键盘
		
		if( $code.val() != randomCode ){
			toast('验证码不匹配')
		}
		
		var obj = { 
			wxID: gUserID,
			//phone: request.phone, // 7-24 手机号不能唯一
			studentID: request.studentID 
		}
		console.log(obj);
		//bindMember2Ent(phone,MY_USER_ID)
		// 已经存在studentID，绑定微信号
		showPrompt('绑定中...');	
		$.ajax({
		    url: gDataUrl +'updateStudentByWx.php',
		    data: obj,
			dataType: "json",
		    success: function(result){
				hidePrompt()
				//App.load('account',result) //??
				App.dialog({
					title     : '绑定成功' ,
					text  : '你已经绑定微信账号，可以通过手机购买课程、检查教学进程、接收上下课提醒等。'  ,
					okButton     : '关闭' ,
				}, function (choice) {
					if(choice){
						WeixinJSBridge.call('closeWindow');//wx.closeWindow();
					}
				});
		    },
		});	
	})

	// 滑动返回 android don't work?
	$(page).swipeRight(function () {
		App.back()
	})	
});	

//2－1. 不存在电话，全新注册记录
App.controller('bindEnt-nophone', function (page,request) {
	var me = this;
	console.log(request)	
	$(page).find('.phone').text(request.phone)
	
	var $username = $(page).find('.name'),
		$school = $(page).find('.school')
	
	var btnNext = $(page).find('.next');
	
	var username = $username.text().trim(),
		school = $school.text().trim(),
		schoolID = 0,
		schoolsubID = 0
	if(username=='未填写') username=''
		
	$username.parent().on('click', function () {	
		App.pick('input-text', {'value':username,'title':'姓名'}, function (data) {
			if(data){ // 取消返回
				username = data.value;
				$username.text(username);
			}
		});
	})
	$school.parent().on('click', function () {	
		App.pick('select-cities', {'value':''}, function (data) {
			if(data){ // 取消返回
				school = data.school; //分校,不是schoolName
				schoolsubID = data.schoolsubID;
				schoolID = data.schoolID;
				$school.text(school);
			}
		});
	})
	
	btnNext.on('click',function(e){	
		username = $username.text().trim(); //姓名
		school = $school.text().trim(); //学校
/*		if( username == '未填写' ){
			toast('请填写姓名');return;	
		} */
		if( school == '未选择' ){
			toast('请选择报读学校');return;	
		}
		var obj = {
			phone: request.phone,
			username: username,
			school: school,
			schoolID: schoolID,
			schoolsubID: schoolsubID //分校区
		}
		console.log(obj)
		App.load('bindEnt-nophone-confirm',obj)				
	})
});	
// 2-2 全新注册学生：insert 姓名＋电话＋报读学校＋微信号 
App.controller('bindEnt-nophone-confirm', function (page,request) {
	var me = this;
	console.log(request)	
	$(page).find('.name').text(request.username)
	$(page).find('.school').text(request.school)
	$(page).find('.phone').text(request.phone)

	var randomCode
	var $code = $(page).find('.app-input[name=code]') //填写
	var btnGetCode = $(page).find('.getCode');
	var btnOk = $(page).find('.ok');
	
	btnGetCode.on('click',function(e){
		if (btnGetCode.text() != '获取验证码') return			
		$(page).find('.app-input').blur(); // 关闭软键盘
		/*
		App.dialog({
			title	     : '发送手机验证码？', 
			okButton     : '确定',
			cancelButton : '取消'
		}, function (choice) {
			if(choice){
				sendCode()
				btnOk.css('display','block') //提交
			}
		}); */
		sendCode()
		btnOk.css('display','block') //提交
		
		// 生成、发送短信验证码
		function sendCode(){
	 	 	randomCode = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;		
	 		var obj = {
	 			phone: request.phone,
	 			code: randomCode
	 		}	

	 		$.ajax({  
	 			url: 'script/SUBMAIL/demo/message_xsend.php',
	 			data: obj,
	 			success: function (data, status) { }
	 		});

	 		//验证码发送成功30秒内不能再次发送
	 		var sec = 60;
	 		function count(){
	 			sec = sec - 1;
	 			if(sec > 0){
	 				btnGetCode.text(sec + '秒后重新获取');
	 				btnGetCode.css('background','gray')
	 				setTimeout(function(){count();},1000);
	 			}else{
	 				btnGetCode.text('获取验证码');
	 				btnGetCode.css('background','blue') //'#32CD32')
	 			}
	 		}
	 		count();
		}		
	})
	
	btnOk.on('click', function (e){
		$(page).find('.app-input').blur(); // 关闭软键盘
		
		if( $code.val() != randomCode ){
			toast('验证码错误');return;	
		}
		
		var obj = {
			schoolsubID: request.schoolsubID,
			schoolID: request.schoolID,
			studentName: request.username,
			phone: request.phone,
			wxID: gUserID
		}
		console.log(obj)
		
		showPrompt('正在注册...');	
		$.ajax({
		    url: gDataUrl +'createStudentByWx.php',
		    data: obj,
			dataType: "json",
		    success: function(result){
				hidePrompt()
				//App.load('account',result) //??
				App.dialog({
					title     : '注册成功' ,
					text  : '你已经注册绑定微信，可以通过手机购买课程、检查教学进程、接收上下课提醒等。'  ,
					okButton     : '关闭' ,
				}, function (choice) {
					if(choice){
						WeixinJSBridge.call('closeWindow');//wx.closeWindow();
					}
				});
		    },
		});	
	})
});	

// 文本框输入
App.controller('input-text', function (page,request) {
	var me = this;
	var value = request.value
	var btnDone = $(page).find('.done'),
		input = $(page).find('input')
	input.val(value); // 传入值给文本框
	$(page).find('.app-title').text(request.title)	
	btnDone.on('click', function (e){
		input.blur(); // 关闭软键盘
		value = input.val().trim()
		if(value == ''){
			toast('不能空白');return false
		}
		me.reply({
			"value": value
		}); // app.pick
	})	
});

// 选择所在城市
App.controller('select-cities', function (page,request) {
	var me = this;
	var $list = $(page).find('.app-list'),
		$listItem = $(page).find('.app-list li').remove()
	var city_list = [] //全部记录,for search
	
	$.getJSON("assets/data/city.json",function(data){
		console.log(data)
		//var city_list = []
		$.each(data,function(index,item){ 
			city_list = city_list.concat(item.cities)
		})
		console.log(city_list)
		$.each(city_list,function(index,item){ 
			var $node = $listItem.clone(true); console.log(item)
			$node.find('.name').text(item)
			//$node.find('.cities').text(item.cities); //hidden
			$list.append($node);
		})
		
		$list.find('li').on({
			click: function (e) {
				var city = $(this).find('.name').text();	
				App.pick('select-school', {'city':city}, function (data) {
					if(data){ // 取消返回
						me.reply(data); 
					}
				});
			},
		})
	}) 
	
	// search
    // Get HTML elements
    var form = page.querySelector('form');
    var input = page.querySelector('form .app-input');
    // Updates the search parameter in web storage when a new character is added
    // to the search input
    input.addEventListener('keyup', function () {
		console.log(input.value)
		//localStorage[INPUT_KEY] = input.value;
    });
    // Updates the search parameter in web storage when the value of the search
    // input is changed
    input.addEventListener('change', function () {
		console.log(input.value)
		//localStorage[INPUT_KEY] = input.value;
    });
    // Performs search when the search input is submitted
    form.addEventListener('submit', function (e) {
		e.preventDefault();
		doSearch(input.value);
    });

    function doSearch (query) {
        // Clean up spaces from the search query
        query = query.trim();
  	    // Unfocus search input
  	    input.blur();
  	    form.blur(); 
		$list.empty(); // 晴空旧的列表
		
		var filter = city_list.filter(function(ele,pos){
		    return ele.indexOf(query) >= 0  ;
		});
		console.log(filter)
		//populateData(filter)
		$list.empty()
		$.each(filter,function(index,item){ 
			var $node = $listItem.clone(true); console.log(item)
			$node.find('.name').text(item)
			//$node.find('.cities').text(item.cities); //hidden
			$list.append($node);
		})
		$list.find('li').on({
			click: function (e) {
				var city = $(this).find('.name').text();	
				App.pick('select-school', {'city':city}, function (data) {
					if(data){ // 取消返回
						me.reply(data); 
					}
				});
			},
		})
	} 	
});

// 选择省、市、加盟学校
App.controller('select-province', function (page,request) {
	var me = this;
	var $list = $(page).find('.app-list'),
		$listItem = $(page).find('.app-list li').remove()
	
	$.getJSON("assets/data/cities.json",function(data){
		$.each(data,function(index,item){ 
			var $node = $listItem.clone(true); 
			$node.find('.name').text(item.name)
			$node.find('.cities').text(item.cities); //hidden
			$list.append($node);
		})
		
		$list.find('li').on({
			click: function (e) {
				var province = $(this).find('.name').text();	
				var cities = $(this).find('.cities').text();
				console.log(cities);
				/*
				App.load('select-city', {
					"province": province,
					"cities": cities
				}); */
				App.pick('select-city', {'province':province,'cities':cities}, function (data) {
					if(data){ // 取消返回
						console.log(data)
						me.reply(data); 
					}
				});
			},
		})
	}) 	
});
//
App.controller('select-city', function (page,request) {
	var me = this;
	var $list = $(page).find('.app-list'),
		$listItem = $(page).find('.app-list li').remove()
	
	console.log(request.province)
	// 标题也是传入
	$(page).find('.app-title').html(request.province)
	var items = request.cities.split(",")
	items.forEach(function (item) {
		var $node = $listItem.clone(true);
		$node.text(item)
		$list.append($node);
	});
	$list.find('li').on({
		click: function (e) {
			var province = request.province	
			var city = $(this).text();
			console.log(city); 
			/*
			App.load('select-school', {
				"province": province,
				"city": city
			}); */
			App.pick('select-school', {'province':province,'city':city}, function (data) {
				if(data){ // 取消返回
					console.log(data)
					me.reply(data); 
				}
			});
		},
	})
});

// 选择某个城市加盟学校（分校区）
App.controller('select-school', function (page,request) {
	console.log(request);
	var me = this
	var $list = $(page).find('.list'),
		$listItem = $(page).find('.listItem').remove()

	// 该地区的加盟学校
	readData(request)
	function readData(obj){
		$.ajax({
			//url: gDataUrl + 'readSchoolList.php',
			url: gDataUrl + 'readSchoolsubListByCity.php',
			data: obj,
			dataType: "json", //jsonp: 'callback',
			success: function(result){
				console.log(result)
				if(result.success){					
					populateData(result.data)
				}else{
					toast(result.message)
				}
			},
		});
	}
	
	// 列表
	function populateData(items){
		items.forEach(function (item) {
			var $node = $listItem.clone(true);
			$node.find('.name').text(item.fullname) //分校sub的名称，不是学校schoolName
			$node.find('.addr').text(item.addr+'｜'+item.phone)
			$node.find('.id').text(item.schoolsubID); // hidden隐藏，分校id,主校id俩个
			$node.find('.schoolID').text(item.schoolID);
			$list.append($node);
		});
		
		$list.find('.listItem').on({
			click: function (e) {
				var obj = {
					'school': $(this).find('.name').text(), //分校，不是schoolName
					'schoolID': $(this).find('.schoolID').text(),
					'schoolsubID': $(this).find('.id').text(),
				}
				//App.load('bindEnt-nophone',obj)
				me.reply(obj); // app.pick无法用于嵌套nested list
				console.log(obj)
			},
		})
	}
});


