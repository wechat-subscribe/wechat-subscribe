<?php
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
require_once $path.'/common/php/regexTool.class.php';
$db=new DB();
$update_id = $_POST ['id']; // 获取要修改的招聘信息ID
$positionName = $_POST ['positionName'];
$address = $_POST ['address'];
$num = $_POST ['num'];
$date = date ( 'Y-m-d H-i-s', time () ); // 招聘时间
$ageRequire = $_POST ['ageRequire'];
$sexRequire = $_POST ['sexRequire'];
$eduRequire = $_POST ['eduRequire'];
$other = $_POST ['other'];
$salary = $_POST ['salary'];
$content = $_POST ['content'];
// $type = $_POST ['type'];
if (empty ( $positionName ) || empty ( $address ) || empty ( $num ) || empty ( $date )) {
	$error = 2; // 职位，地址，人数，发布时间不能为空；
} else {
	$sql_update = "update wx_talent_recruit set positionName='{$positionName}',address='{$address}',
		ageRequire='{ageRequire}',sexRequire='{sexRequire}',eduRequire='{eduRequire}',
		other='{$other}',salary='{$salary}',content='{$content}',num='{$num}',
		date='{$date}' where id='{$update_id}'";
	$res_update = $db->execsql ( $sql_update );
	$res = mysql_affected_rows ();
	if ($res) {
		 $error=1; // 修改成功
	} else {
		$error=5; // 修改失败
// 		echo error();
	}
}
echo $error;
		