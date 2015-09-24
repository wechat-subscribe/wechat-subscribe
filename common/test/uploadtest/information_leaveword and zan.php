<?php
header ( 'content-type:text/html;charset=utf-8' );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) ); // 获取总工程的绝对路径
require_once $path . '/common/php/dbaccess.php';
$info_id=$_GET['id'];//获取要留言的信息的id；
session_start();
$data=array();
$leaveword_content=$_GET['content'];//获取评论内容
$date=date ( 'Y-m-d H-i-s', time () );//评论时间
$userId=$_SESSION['user']['id'];//评论人id
$sql_num_zan="select id from wx_zan where infoId=".$info_id;
$res_num_zan=$db->execsql($sql_num_zan);
$num_zan=count($res_num_zan);//点赞次数


/******构造wx_leaveword的数据结构********/
$data['infoid']=$info_id;
$data['userid']=$userId;
$data['date']=$date;
$data['content']=$leaveword_content;



/******存入wx_leaveword数据库*********/
$insert_leaveword=$db->insert(wx_leaveword, $data);
if(insert){
	$error= 0;//保存成功
}else{
	$error=1;//保存失败
}


/******构造wx_zan的数据结构********/
$data['infoid']=$info_id;
$data['userid']=$userId;
$data['date']=$date;
$data['content']=$leaveword_content;
$data['num']=$num_zan;

/******存入wx_zan数据库********/
$insert_zan=$db->insert(wx_zan, $data);
if($insert){
	$error=0;//保存成功
}else{
	$error=1;//保存失败
}

