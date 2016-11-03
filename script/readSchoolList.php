<?php
/**
 * 加盟校区列表
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	// 打通到内部系统（位于咨询端）ent
	//require_once('../../ghjy-consult-ext/script/db/database_connection.php');
	require_once('db/database_connection_ent.php');

	//$province = $_REQUEST['province'];
	$city = $_REQUEST['city'];
	$sql = " SELECT * From `ghjy_school` 
		WHERE city = '$city' ";   
    $result = mysql_query($sql);

	if(mysql_num_rows($result)>0){
		$arr = array();
		$i = 0;
		while($row = mysql_fetch_array($result))
		{
			array_push($arr,$row);
			$i++;
		} 
		echo json_encode(array(
			"success" => true,
			"message" => '该地区有加盟学校',
			"data"    => $arr
		));
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => '该地区尚未加盟学校'
		));		
	}

?>