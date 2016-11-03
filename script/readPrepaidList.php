<?php
/**
 * 根号数理化公众号－已钩课程（套餐）
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$wxID = $_REQUEST['wxID'];

	$query = " SELECT * From `ghjy_student-prepaid`  
		WHERE wxID = '$wxID' Order by created Desc ";
    
    $result = mysql_query($query) 
		or die("Invalid query: readPrepaidList by student" . mysql_error());

		
	$query_array = array();
	$i = 0;
	//Iterate all Select
	while($row = mysql_fetch_array($result))
	{
		array_push($query_array,$row);
		$i++;
	}
	
	echo json_encode($query_array);

?>