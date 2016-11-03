<?php
/*log
 * 内部系统已经注册的，尚未绑定微信，无法获取模版消息等服务
 * 16-4-7 会员手机验证后，微信号wxID在西贡中保存记录
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	require_once('db/database_connection_ent.php');
    
    $wxID = $_REQUEST['wxID'];
    $studentID = $_REQUEST['studentID'];
 
	$sql = "UPDATE `ghjy_student` SET wxID = '$wxID' WHERE studentID = $studentID ";
	$result = mysql_query($sql);
	
	// 成功，接入内部系统wxID
	echo json_encode(array(
		"success" => 'true',
		"message" => '微信号绑定成功',
	));
?>
