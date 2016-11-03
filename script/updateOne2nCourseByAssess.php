<?php
/* 
 * 一对多课后评价，如果已经存在内容assessTag_list，则不能再评价
 * courseID不能重复，避免重复评价
 */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 根号教育的数据库
//require_once('db/database_connection.php');
// 内部系统数据
require_once('db/database_connection_ent.php');

$courseNo = $_REQUEST['courseNo']; //courseNo+studentID = unique
$studentID = $_REQUEST['studentID'];
//$now = date('ymdhis');
//$ratingEtq = $_REQUEST['ratingEtq'];
//$ratingLevel = $_REQUEST['ratingLevel'];
$assess = addslashes($_REQUEST['assess']);
$note = addslashes($_REQUEST['note']);

$query = "SELECT * FROM `ghjy_one2n_course` 
	WHERE studentID = $studentID And courseNo='$courseNo' limit 1";
$result = mysql_query($query) Or die("Invalid query: read" . mysql_error());
$row = mysql_fetch_array($result) Or die("Invalid query: row id" . mysql_error());

$one2ncourseID = $row['one2ncourseID'];
$assessTags = $row['assessTag_list'];

// 空白，表示尚未评价
if($assessTags == '' Or $assessTags == null){
	$query = "UPDATE `ghjy_one2n_course` SET 
		assessTag_list = '$assess', assessNote = '$note' 
		WHERE one2ncourseID = $one2ncourseID ";
	$result = mysql_query($query);
	//var_dump($result);
    echo json_encode(array(
        "success" => true,
        "message" => "课后评价成功",
		"data"    => $assess 	
    ));
}else{	
	echo json_encode(array(
		"success" => false,
		"message" => "已经评价，不能重复",
		"data"    => $assessTags
	));
}  
  
?>
