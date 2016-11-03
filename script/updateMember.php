<?php
/*log
*14-6-20 会员资料补充，带入字段名fieldname
endlog */

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

	require_once('db/database_connection.php');
    
    $wxID = $_REQUEST['wxID'];
    $fieldName = $_REQUEST['fieldName'];
    $fieldValue = addslashes($_REQUEST['fieldValue']);
 
	$sql = "UPDATE member SET $fieldName = '$fieldValue' WHERE wxID = '$wxID' "; //openID
	$result = mysql_query($sql);
	
	// 成功，接入内部系统wxID

	echo json_encode(array(
		"success" => 'true'
	));
?>
