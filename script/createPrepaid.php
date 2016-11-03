<?php
/*log 公众号上用户微信支付购买课程
*16-4-6 phone,wxID
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');
	
// 内部系统数据
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$studentID = $_REQUEST['studentID'];	
	$times = $_REQUEST['times'];
	$amt = $_REQUEST['amt'];
	$coupon = $_REQUEST['coupon'];
	$amount = $_REQUEST['amount'];
	$sectionID = 0; //学段，目前未知？？
	$taocan = $_REQUEST['taocan'];
	$payment = '微信'; //方式：现金、刷卡、微信、扫吗
	$OrderID = $_REQUEST['OrderID'];
	//$OrderID = addslashes($arr->OrderID); //刷卡交易单
		
	$query = "INSERT INTO `ghjy_student-prepaid` 
		(studentID,taocan,amt,times,coupon,amount,payment,OrderID) 
		VALUES ($studentID,'$taocan',$amt,$times,$coupon,$amount,'$payment','$OrderID')";

	$result = mysql_query($query) 
		or die("Invalid query: createPrepaid" . mysql_error());

	// 返回最新插入记录id
	$id = mysql_insert_id(); 
	
	echo json_encode(array(
		"success" => true,
		"message" => '购买课程套餐prepaid成功'
	));	
	
	/*
	// 2 更新student-study记录状态 prepaid,prepaidID
	//$study_list = explode(',',$study_list);
	$sql = "UPDATE `ghjy_student-study` SET prepaid=1,prepaidID=$id 
		WHERE studentstudyID IN ($study_list) ";
	$result = mysql_query($sql) 
		or die("Invalid query: update study by prepaid" . mysql_error());
	*/	

?>