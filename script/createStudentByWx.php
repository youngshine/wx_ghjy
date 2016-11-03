<?php
/*log 
 *16-4-6 公众号验证手机后，不存在的，全新注册一条记录：name,school,phone
 * 等待校长分配给咨询师
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');
	
	// 内部系统数据
	require_once('db/database_connection_ent.php');

	$studentName = $_REQUEST['studentName'];
	$phone = $_REQUEST['phone'];
	$wxID = addslashes($_REQUEST['wxID']);
	$schoolID = $_REQUEST['schoolID']; //选择报读学校
	$schoolsubID = $_REQUEST['schoolsubID']; //分校区
	
	$sql = "INSERT INTO `ghjy_student`
		(studentName,phone,schoolsubID,schoolID,wxID) 
		VALUES ('$studentName','$phone',$schoolsubID,$schoolID, '$wxID')";
	
	$result = mysql_query($sql);
	/*
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
	*/

	echo json_encode(array(
		"success" => 'true',
		"message" => '全新注册内部成功'
	)); 
?>
