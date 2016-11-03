<?php
/**
 * 当前微信id是否已经在内部系统Ent（咨询系统）绑定
*/

header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Origin: *'); // 跨域问题
//header('Access-Control-Allow-Headers: X-Requested-With');

// 打通到内部系统（位于咨询端）ent
//require_once('../../ghjy-consult-ext/script/db/database_connection.php');
require_once('db/database_connection_ent.php');

$wxID = addslashes($_REQUEST['wxID']);
if($wxID==''||$wxID==null) $wxID='开发测试模式';
/*
$sql = " SELECT a.*,b.schoolName 
	From  `ghjy_student` a 
	Join `ghjy_school` b On a.schoolID=b.schoolID 
	WHERE a.wxID='$wxID' LIMIT 1 ";   */
$sql = " SELECT a.*,b.fullname 
	From `ghjy_student` a 
	Join `ghjy_school_sub` b On a.schoolsubID=b.schoolsubID And a.schoolID=b.schoolID 
	WHERE a.wxID='$wxID' "; 
$result = mysql_query($sql);

if(mysql_num_rows($result)>0){
	//$row = mysql_fetch_assoc($result); 
	$arrStudent = array();
	$i = 0;
	//Iterate all Select
	while($row = mysql_fetch_array($result))
	{
		array_push($arrStudent,$row);
		$i++;
	}
	echo json_encode(array(
		"success" => true,
		"message" => '已经绑定内部系统',
		"data"    => $arrStudent, //可能有两个
		//"coupon"  => $row['coupon'] //代金券
	));
}else{
	echo json_encode(array(
		"success" => false,
		"message" => '尚未绑定内部系统'
	));		
}

?>