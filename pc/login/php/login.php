<?php
header("content-type:text/json;charset=utf-8");
require_once '../../../common/php/dbaccess.php';
$db=new DB();
session_start();

if(strtolower($_SESSION['captchaCode'])  != strtolower($_GET['captchaCode'])){
	$login['error']=2;//验证码错误
}else{
	$number=$_GET['number'];
	$pwd=$_GET['pwd'];
	$sql_user="select id,name from wx_admin where number='{$number}' and pwd='{$pwd}'";
	$res_user=$db->getrow($sql_user);

	$login['error']=-1;

	if (empty($res_user)){
		$login['error']=0;//登录失败
	}else {
		$_SESSION['admin']['id']=$res_user['id'];
		$_SESSION['admin']['name']=$res_user['name'];
		$login['adminName']=$res_user['name'];
		$login['error']=1;//登录成功
	}
}

echo json_encode($login);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            