<?php
/*log
*16-4-7 会员手机验证后，关联到企业内部系统wxID
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	require_once('db/database_connection.php');
    
    $wxID = $_REQUEST['wxID'];
    $phone = $_REQUEST['phone'];
 
	$sql = "UPDATE member SET phone = '$phone',checked=1 WHERE wxID = '$wxID' ";
	$result = mysql_query($sql);
	
	// 成功，接入内部系统wxID

	echo json_encode(array(
		"success" => 'true'
	));
?>
