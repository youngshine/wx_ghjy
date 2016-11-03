<?php
/*log 公众号验证手机后，绑定到企业内部系统
*16-4-6 phone,wxID
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');
	
// 内部系统数据
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$phone = $_REQUEST['phone'];
	$wxID = addslashes($_REQUEST['wxID']);
	$consultID = 0; //必须有咨询师
	
	$sql = "INSERT INTO `ghjy_student`(phone,wxID) VALUES ('$phone', '$wxID')";
	$result = mysql_query($sql);
	if(!$result){
		//$id = mysql_insert_id(); 	
		// 电话重复，该学生已经存在内部系统，更新wxID
		$sql = "UPDATE `ghjy_student` SET wxID = '$wxID',updated = now('ymd') 
			WHERE phone = '$phone' ";
		$result = mysql_query($sql);
	}
	
	// 返回当前会员记录
	$sql = "SELECT * FROM `ghjy_student` WHERE wxID='$wxID' limit 1";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
		/*
	echo json_encode(array(
		"success" => 'true',
		"message" => '绑定内部系统'
	)); */
	echo json_encode($row);
?>
