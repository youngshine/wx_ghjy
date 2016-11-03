<?php
/**
 * 根号数理化公众号－某个学生报读一对一课程内容（知识点）
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('db/database_connection_ent.php');

$studentID = $_REQUEST['studentID'];

$sql = " SELECT a.timely_list,a.times As hour,b.zsdName As title  
	FROM `ghjy_student-study` a 
	JOIN `ghjy_zsd` b on a.zsdID=b.zsdID And a.subjectID=b.subjectID  
	WHERE a.studentID=$studentID ";

$result = mysql_query($sql) 
	or die("Invalid query: readStudentstudyList by student" . mysql_error());

	
$query_array = array();
$i = 0;
//Iterate all Select
while($row = mysql_fetch_array($result))
{
	array_push($query_array,$row);
	$i++;
}

echo json_encode(array(
	"success" => true,
	"message" => "读取报读一对一课程内容知识点列表成功",
	"data"	  => $query_array
));	

?>