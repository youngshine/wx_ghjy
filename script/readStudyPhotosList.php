<?php
// 当前报读知识点的教学题目（与考试题目paired?)

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$studentstudyID = $_REQUEST['studentstudyID']; //学生报读知识点：student+zsd
	//$subjectID = $_REQUEST['subjectID'];
	

	$sql = " SELECT * From `ghjy_student-study-photos` 
		where studentstudyID=$studentstudyID   
		Order by created Desc ";
 //$sql = "select * from `$table` limit 1";   
    $result = mysql_query($sql) 
		or die("Invalid query: readStudyPhotosList" . mysql_error());

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