<?php
/**
 * 缴费历史记录，可能一家有两个小孩，student_list
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）ent
//require_once('../../ghjy-consult-ext/script/db/database_connection.php');
require_once('db/database_connection_ent.php');

$wxID = addslashes($_REQUEST['wxID']);
if($wxID==''||$wxID==null) $wxID='开发测试模式';

$sql = " SELECT a.*,b.studentName   
	From `ghjy_accnt` a 
	Join `ghjy_student` b On a.studentID=b.studentID 
	Where b.wxID='$wxID'
	Order by a.created Desc";
    
$result = mysql_query($sql) 
	or die("Invalid query: readAccntList" . mysql_error());

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
	"message" => "企业号读取缴费流水列表成功",
	"data"	  => $query_array
));

?>