<?php
/**
 * 根号数理化公众号－学习轨迹
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$wxID = $_REQUEST['wxID'];

	$query = " SELECT a.*,b.zsdName,b.grade,c.teacherName,d.wxID,e.subjectName     
		FROM `ghjy_student-study` a 
		JOIN `ghjy_zsd` b on a.zsdID=b.zsdID 
		LEFT JOIN `ghjy_teacher` c on a.teacherID=c.teacherID 
		JOIN `ghjy_student` d ON a.studentID=d.studentID 
		JOIN `ghjy_subject` e on b.subjectID=e.subjectID 
		WHERE d.wxID = '$wxID' Order by a.paid ";
    
    $result = mysql_query($query) 
		or die("Invalid query: readZsdList by student" . mysql_error());

		
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