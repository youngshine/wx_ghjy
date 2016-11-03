<?php
/**
 * 根号数理化公众号－某个学生购买课程套餐的内容（知识点）
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$prepaidID = $_REQUEST['prepaidId'];

	$sql = " SELECT a.*,b.zsdName,c.subjectName,d.teacherName    
		FROM `ghjy_student-study` a 
		JOIN `ghjy_zsd` b on a.zsdID=b.zsdID And a.subjectID=b.subjectID  
		JOIN `ghjy_subject` c on a.subjectID=c.subjectID 
		LEFT JOIN `ghjy_teacher` d on a.teacherID=d.teacherID 
		WHERE a.prepaidID=$prepaidID ";
	
    $result = mysql_query($sql) 
		or die("Invalid query: readStudentstudyList by prepaid" . mysql_error());

		
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