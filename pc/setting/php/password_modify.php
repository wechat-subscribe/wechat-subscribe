<?php
header ( "content-type:text/json;charset=utf-8" );
// echo $path;
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/regexTool.class.php';

$db =new DB();
// $file=array();
$regex=new regexTool();
session_start ();

// $_SESSION['admin']['id']=1;

$sql_password="select pwd from wx_admin where id =".$_SESSION['admin']['id'];//获取 用户数据库中原来的密码
$res_password=$db->getrow($sql_password);

// var_dump($res_password);
$a=$res_password['pwd'];
// echo $a;
$past_password=$_POST['past_password'];             //获取用户输入的密码
// $past_password=2;
$passwordType=$_POST['pastpwdType'];

/***************判断用户密码是否正确**********************/
if($passwordType=="pastpwdType"){
	if($past_password!=$a){                                 	
		echo 0;               //密码输入错误
	}elseif($past_password==$a){
		echo 1;
		// $passwordType=="newpwdType"	;	
	}
}elseif($passwordType=="newpwdType"){
$now_password1=$_POST['now_password1'];                     //获取用户第一次的新密码
// $now_password1=3;
$now_password2=$_POST['now_password2'];							//获取用户第二次的新密码
// $now_password2=3;

/*************判断两次新密码输入是否相同***************/
if($now_password1==$now_password2){
	// 		echo 123;

	$sql_update = "update wx_admin set pwd='{$now_password1}' where id = ".$_SESSION['admin']['id'];  //  存入数据库
	$res_update = $db->execsql ( $sql_update );

	$res = mysql_affected_rows ();
	if ($res) {
		echo $error = 1; // 修改成功
	} else {
		echo $error = 0; // 修改失败
	}

}elseif($now_password1!=$now_password2){
	echo 0;                              //密码输入不一致
}
}















