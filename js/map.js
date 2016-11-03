/**
 * Created by jfengjiang on 2015/9/11.
 */

$(function () {
    // page stack
    var stack = [];
    var $container = $('.js_container');

	var data_info = [
		[118.370000,24.540000,"福建省泉州市鲤城区文庙分校"],
		[118.406605,23.921585,"福建省厦门市思明区海滨分校"],
		[108.540000,34.160000,"陕西省西安市大雁塔分校"],
		[117.160000,31.510000,"安徽省合肥市科大分校"],
		[113.420000,34.440000,"河南省郑州市二七塔分校"],
		[116.412222,39.912345,"北京市通州万科城分校"]
	];
	var opts = {
		width : 250,     // 信息窗口宽度
		height: 80,     // 信息窗口高度
		title : "信息窗口" , // 信息窗口标题
		enableMessage:true//设置允许信息窗发送短息
	};
 	
	// 这样才能显示地图 appShow ?
	//$(page).on('appReady', function () {
		var map = new BMap.Map('productmap'); // 用id 不是requestId
		map.centerAndZoom(new BMap.Point(108.540000,34.160000), 5);
		//var point = new BMap.Point(118.222,24.555);
		//console.log(point)
		//map.centerAndZoom(point, 9);  
		//var marker = new BMap.Marker(point); 
		//map.addOverlay(marker); 
		for(var i=0;i<data_info.length;i++){
			var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
			var content = data_info[i][2];
			map.addOverlay(marker);               // 将标注添加到地图中
			addClickHandler(content,marker);
		}
		function addClickHandler(content,marker){
			marker.addEventListener("click",function(e){
				openInfo(content,e)}
			);
		}
		function openInfo(content,e){
			var p = e.target;
			var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
			var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
			map.openInfoWindow(infoWindow,point); //开启信息窗口
		}
	//});
});