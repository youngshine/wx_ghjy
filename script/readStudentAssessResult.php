<?php
/*
 * 读取学生的测评结果accessresult，
 */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统ent数据库（位于咨询端）
require_once('db/database_connection_ent.php');

$studentID = $_REQUEST['studentID'];

$sql = " SELECT a.studentName,a.studentID,a.assessResult,b.fullname AS schoolsub 
	From `ghjy_student` a 
	Join `ghjy_school_sub` b On a.schoolsubID=b.schoolsubID 
	WHERE a.studentID=$studentID LIMIT 1 ";
    
$result = mysql_query($sql);

$row = mysql_fetch_assoc($result); 
echo json_encode(array(
	"success" => true,
	"message" => '读取学生的测评结果assessResult成功',
	"data"    => $row
));

?>