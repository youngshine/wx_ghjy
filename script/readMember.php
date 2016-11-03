<?php
/**
 * 公众号关注人的信息 
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

require_once('db/database_connection.php');

	$wxID = $_REQUEST['wxID'];
    $sql = "SELECT * FROM member WHERE wxID = '$wxID' limit 1";
    //$query = "select * from member where member_id=3";
    $result = mysql_query($sql) or 
        die("Invalid query: readMember" . mysql_error());
	$row = mysql_fetch_array($result) or 
        die("Invalid query: readMember2" . mysql_error());
	//print_r($row);
	
echo json_encode($row);

?>