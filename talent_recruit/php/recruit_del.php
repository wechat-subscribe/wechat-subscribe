<?php
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
require_once $path.'/common/php/regexTool.class.php';
$db=new DB();
$del_id = $_GET ['id'];
if (empty ( $del_id )) {
	ECHO 3; // 删除失败
} else {
	$sql_del = "delete from wx_talent_recruit where id =" . $del_id;
	$res_del = $db->execsql ( $sql_del );
	$res = mysql_affected_rows ();
	if ($res) {
		 echo "<script>alert('删除成功');window.location.href='../html/recruit_manage.php';</script>"; // 删除成功
	} else {
		 echo "<script>alert('删除失败');window.location.href='../html/recruit_manage.php';</script>"; // 删除失败
	}
}