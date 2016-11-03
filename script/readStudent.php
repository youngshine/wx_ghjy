<?php
/**
 * 看看是否已经在内部系统
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$wxID = $_REQUEST['wxID'];

	$sql = " SELECT 1 from `ghjy_student` WHERE wxID='$wxID' LIMIT 1 ";
    
    $result = mysql_query($sql);

	if(mysql_num_rows($result)>0){
		//$row = mysql_fetch_assoc($result); 
		echo json_encode(array(
			"success" => true,
			"message" => '已经绑定内部系统'
		));
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => '尚未绑定内部系统'
		));		
	}

?>