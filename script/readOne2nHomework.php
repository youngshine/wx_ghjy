<?php
// 某个一对N作业，教师推送某次学生精彩瞬间，推送给家长
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

//require_once('db/database_connection.php');
require_once('db/database_connection_ent.php'); //内部系统

$ID = $_REQUEST['ID']; // 

$sql = " SELECT a.*,b.teacherName     
	From `ghjy_one2n_homework` a 
	Join `ghjy_teacher` b On a.userId=b.userId  
	Where a.one2nhomeworkID = $ID LIMIT 1";

$result = mysql_query($sql) 
	or die("Invalid query: readOne2nHomework by tpl msg" . mysql_error());

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
	"message" => "读取本次推送一对N作业成功",
	"data"	  => $query_array
));

?>