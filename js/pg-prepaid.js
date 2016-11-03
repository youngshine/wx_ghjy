// 购买课程，参数coupon代金券
App.controller('home', function (page,request) {	
	var $list = $(page).find('.list'),
		$listItem = $(page).find('.listItem')
	
	var btnSubmit = $(page).find('.submit'),
		btnHist= $(page).find('.prepaid-hist')
	
	btnHist.on('click',function(e){
		App.load('hist')
	})
	
	// 购买
	$list.find('.listItem').bind('click', function (e) {
		var taocan = $(this).find('.title').text(),
			times  = $(this).find('.times').text(),
			amt    = $(this).find('.amt').text()
		console.log(e.target.className)
		console.log(times)
		if(e.target.className == 'prepaid'){
			var obj = {
				taocan: taocan,
				times : times,
				amt   : amt,
				coupon: request.coupon,
				amount: amt-request.coupon,
				openId: gUserID,
				studentID: request.studentID // id，不是openId
			}
			console.log(obj)
			App.load('wxpay',obj)
			//window.location = "http://www.xzpt.org/wx_ghjy/zhifu_demo/example/jsapi.php"
		}
	})

}); // 学习轨迹－课程列表

// 历史记录
App.controller('wxpay', function (page,request) {
	var jsApiParameters // 支付订单参数
	
	$(page).find('.taocan').text(request.taocan)
	$(page).find('.times').text(request.times)
	$(page).find('.amt').text(request.amt)
	$(page).find('.coupon').text(request.coupon)
	$(page).find('.amount').text(request.amount)
	
	// 1、支付，2、保存到数据库
	var btnSubmit = $(page).find('.submit')
	btnSubmit.on('click',function(){
		//callpay(request)
		// 获取统一下单信息
		request.OrderID = new Date().getTime(); //交易单号：日期＋秒＋随机3位数
		console.log(request)
		
		$.ajax({
            url: 'zhifu/jsapi.php',
			data: request,
			dataType: "json", // 返回数据格式
			success: function(result){
				jsApiParameters = result
				console.log(jsApiParameters)
				callpay()
			}
		});
	})
	
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',jsApiParameters,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				// 成功，插入数据库或者是在notify后插入数据库
				if(res.err_msg == 'ok'){
					$.ajax({
						//OrderID //
			            url: 'script/createPrepaid.php',
						data: jsApiParameters, //request, out_trade_no = OrderID
						dataType: "json", // 返回数据格式
						success: function(result){
							//扯入购买记录，包括交易号，对应商户后台
						}
					});
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
});	

// 历史记录
App.controller('hist', function (page) {
 	var	$list     = $(page).find('.app-list'),
 		$listItem = $(page).find('.app-list li').remove()

	var params = {
		"wxID": gUserID
	}
	readData(function(data){
		populateData(data)	
		//handleData( $list, data )
	}, params );	
	// 已购课程
	function readData(callback, obj){
		showPrompt('加载中...');		
		$.ajax({
	    	//url: dataUrl + 'readExamList.php?data=' + JSON.stringify(obj),
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
	function populateData(items){
		if($list.children().length != 0){
			$list.empty(); //清除旧的列表项 if any
		}
		items.forEach(function (item) {
			var $node = $listItem.clone(true);
			
			$node.find('.taocan').text(item.taocan);
			$node.find('.created').text(item.created.substr(2,8));

			$list.append($node);
		});
	}	
});




