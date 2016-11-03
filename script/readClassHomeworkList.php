<?php
// 家长查看作业（作业是发给全班的classID)
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('db/database_connection_ent.php');

$wxID = addslashes($_REQUEST['wxID']); //家长微信

$sql = " SELECT a.*,b.title   
	From `ghjy_class_homework` a 
	Join `ghjy_class` b On a.classID=b.classID 
	Where a.classID in 
		(SELECT cs.classID From `ghjy_class_student` cs 
		Join `ghjy_student` st On cs.studentID=st.studentID 
		Where st.wxID='$wxID')  
	Order by a.created Desc ";
 
$result = mysql_query($sql) 
	or die("Invalid query: readClasshomeworkList by student" . mysql_error());

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
	"message" => "读取教师上课内容作业成功",
	"data"	  => $query_array
));

?>