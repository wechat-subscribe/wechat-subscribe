<?php
header ( "content-type:text/html;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once $path . '/common/php/dbaccess.php';
require_once $path . '/common/php/regexTool.class.php';
$db = new DB ();
$regex = new regexTool ();
$modify_id = $_POST ['id']; // 获取修改的招聘信息ID
	
	/***************获取从前端传来的数据**********************/
	$positionName = $_POST ['positionName'];
	$address = $_POST ['address'];
	if ($regex->isNumber ( $_POST['num'] )) {
		$num = $_POST['num'];
	} else {
		$error = 7; // 人数格式不正确
	}
	
	$date = date ( 'Y-m-d H-i-s', time () ); // 招聘时间
	if ($regex->isNumber ( $_POST['ageRequire'] )) {
		$ageRequire = $_POST['ageRequire'];
	} else {
		$error = 5; // 年龄格式不正确
	}
	
	$sexRequire = $_POST ['sexRequire'];
	$eduRequire = $_POST ['eduRequire'];
	$other = $_POST ['other'];
	if ($regex->isNumber ( $_POST['salary'] )) {   //判断是不是数字
		$salary = $_POST['salary'];
		
	} else {
		$error = 6; // 工资格式不正确
	}
	
	$content = $_POST ['content'];

   /************判断内容是否为空******************/
	if (empty ( $positionName ) || empty ( $address) || empty ( $num ) 
			 || empty ( $salary) || empty ( $ageRequire )) {
		$error = 2; // 职位，地址，人数不能为空；
	} else {
		$sql_update = "update wx_talent_recruit set positionName='{$positionName}',address='{$address}',
					ageRequire='{ageRequire}',sexRequire='{sexRequire}',eduRequire='{eduRequire}',
					other='{$other}',salary='{$salary}',content='{$content}',num='{$num}',
					date='{$date}' where id='{$modify_id}'";
		$res_update = $db->execsql ( $sql_update );
		$res = mysql_affected_rows ();
		if ($res) {
			 $error = 1; // 修改成功
		} else {
			 $error = 0; // 修改失败
		}
	}
	$file['error']=$error;
	echo json_encode($file);
		