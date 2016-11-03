<?php
// 当前报读知识点的教学题目（与考试题目paired?)

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）
require_once('../../ghjy-consult-ext/script/db/database_connection.php');

	$studentstudyID = $_REQUEST['studentstudyID']; //学生报读知识点：student+zsd
	$subjectID = $_REQUEST['subjectID'];
	
	if($subjectID==1){
		$table = 'sx_xiaochu_exam_question';
	}elseif($subjectID==2){
		$table = 'wl_chu_exam_question';
	}else{
		$table = 'hx_chu_exam_question';
	} 

	$sql = " SELECT a.*,b.level,b.content,b.answer   
		From `ghjy_topic-teach` a 
		Join `$table` b On a.gid=b.gid 
		where a.studentstudyID=$studentstudyID   
		order by a.created Desc ";
 //$sql = "select * from `$table` limit 1";   
    $result = mysql_query($sql) 
		or die("Invalid query: readTopicteachList" . mysql_error());

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