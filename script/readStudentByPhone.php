<?php
/**
 * 尚未绑定微信，通过手机，看看是否已经在内部系统，
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	// 打通到内部系统（位于咨询端）
	require_once('db/database_connection_ent.php');

	$phone = addslashes($_REQUEST['phone']);

	$sql = " SELECT a.studentName,a.studentID,a.phone,a.gender,b.schoolName 
		From `ghjy_student` a 
		Join `ghjy_school` b On a.schoolID=b.schoolID 
		WHERE a.phone='$phone' LIMIT 1 ";
    
    $result = mysql_query($sql);

	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result); 
		echo json_encode(array(
			"success" => true,
			"message" => '内部系统已存在该人（手机号唯一）',
			"data"    => $row
		));
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => '内部系统不存在该人（手机号）'
		));		
	}

?>