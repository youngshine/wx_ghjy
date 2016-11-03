<?php
/**
 * 看看当前微信id是否已经在内部系统Ent（咨询系统）
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	// 打通到内部系统（位于咨询端）ent
	//require_once('../../ghjy-consult-ext/script/db/database_connection.php');
	require_once('db/database_connection_ent.php');

	$wxID = $_REQUEST['wxID'];

	$sql = " SELECT studentID,coupon from `ghjy_student` 
		WHERE wxID='$wxID' LIMIT 1 ";   
    $result = mysql_query($sql);

	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result); 
		echo json_encode(array(
			"success" => true,
			"message" => '已经绑定内部系统',
			"coupon"  => $row['coupon'] //代金券
		));
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => '尚未绑定内部系统'
		));		
	}

?>