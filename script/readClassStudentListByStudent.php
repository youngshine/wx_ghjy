<?php
/*
 * 9-2 读取学生的报读课程班级
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）ent
//require_once('../../ghjy-consult-ext/script/db/database_connection.php');
require_once('db/database_connection_ent.php');

$studentID = $_REQUEST['studentID'];
/*
$sql = " SELECT a.*,b.timely_list,b.hour,d.title 
	From `ghjy_class_student` a 
	Join `ghjy_class` b On a.classID=b.classID 
	Join `ghjy_accnt_detail` c On a.accntdetailID=c.accntdetailID 
	Join `ghjy_kclist` d On c.kclistID=d.kclistID 
	WHERE a.studentID = $studentID ";   */
$sql = " SELECT a.*,b.timely_list,b.hour,b.title 
	From `ghjy_class_student` a 
	Join `ghjy_class` b On a.classID=b.classID 
	WHERE a.studentID = $studentID ";
$result = mysql_query($sql) 
	or die("Invalid query: readClassStudentList By student " . mysql_error());

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
	"message" => "读取报读课程班级列表成功",
	"data"	  => $query_array
));		

?>