<?php
/* 
 * 新增课后评价 course_assess
 * courseID不能重复，避免重复评价
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 根号教育的数据库
//require_once('db/database_connection.php');
// 内部系统数据
require_once('db/database_connection_ent.php');

$courseID = $_REQUEST['courseID'];
$wxID = $_REQUEST['wxID'];
//$now = date('ymdhis');
//$ratingEtq = $_REQUEST['ratingEtq'];
//$ratingLevel = $_REQUEST['ratingLevel'];
$assess = addslashes($_REQUEST['assess']);
$note = addslashes($_REQUEST['note']);

$query = "INSERT INTO `ghjy_teacher_course_assess` 
	(courseID,wxID,assess,note) 
	VALUES ($courseID,'$wxID','$assess','$note' ) ";
$result = mysql_query($query);
//$query = "SELECT * FROM `ghjy_teacher_course_assess`";

if($result){
    echo json_encode(array(
        "success" => true,
        "message" => "家长课后评价成功"
    ));
}else{
    echo json_encode(array(
        "success" => false,
        "message" => "不能重复评价"
    ));
}
    
  
?>
