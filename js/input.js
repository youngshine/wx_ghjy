//单行文本
App.controller('input-text', function (page,request) {
	var me = this;
	
	btnOk = $(page).find('.ok')
	btnOk.css('display','none')
    var inputJs = page.querySelector('.app-input');
    // Updates the search parameter in web storage when a new character is added
    // to the search input
    inputJs.addEventListener('keyup', function () {
		//console.log(inputJs.value)
		//localStorage[INPUT_KEY] = input.value;
		if(inputJs.value.trim().length>0)
			btnOk.css('display','block')
    });
    // Updates the search parameter in web storage when the value of the search
    // input is changed
    inputJs.addEventListener('change', function () {
		console.log(inputJs.value)
		//localStorage[INPUT_KEY] = input.value;
		if(inputJs.value.trim().length>0)
			btnOk.css('display','block')
    });	
	
	// 动态标题
	$(page).find('.app-title').html(request.title)
	
	var input = $(page).find('input');
	input.val(request.value); // 传入值

	btnOk.on('click', function (e){
		input.blur(); // 关闭软键盘
		var obj = {
			"value": input.val().trim()
		}
		me.reply(obj); // app.pick
	})	
});

//多行文本
App.controller('input-textarea', function (page,request) {
	var me = this;
	
	// 动态标题
	$(page).find('.app-title').html(request.title)
	
	var input = $(page).find('textarea');
	input.val(request.value); // 传入值
	$(page).find('.ok').on('click', function (e){	
		input.blur(); // 关闭软键盘
		var obj = {
			"value": input.val().trim()
		}
		me.reply(obj); // app.pick
	})
});


//数字
App.controller('input-number', function (page,request) {
	var me = this;
	// 动态标题
	$(page).find('.app-title').html(request.title)
	
	var input = $(page).find('input');
	input.val(request.value); // 传入值

	$(page).find('.ok').on('click', function (e){
		input.blur(); // 关闭软键盘
		var obj = {
			"value": input.val()
		}
		me.reply(obj); // app.pick
	})	
});

//日期
App.controller('input-date', function (page,request) {
	var me = this;
	
	// 标题也是传入
	$(page).find('.app-title').html(request.title)

	var input = $(page).find('input');
		
	var value = request.value
	if(value == ''){
		value = new Date();
		var year = value.getFullYear().toString();
		var month = (value.getMonth()+1).toString();
		var day = value.getDay().toString()
		month = month.length=1 ? '0'+month:month
		day = day.length=1 ? '0'+day:month
		value = year + '-' + month + '-' + day //value.toLocaleString()
	}
		
	console.log(value)
	input.val(value); // 传入值

	$(page).find('.ok').on('click', function (e){
		var obj = {
			"value": input.val()
		}
		me.reply(obj); // app.pick
	})	
});



