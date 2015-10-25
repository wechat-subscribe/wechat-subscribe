<?php
/*
*投票模块
*writer : ly
*/ 

 
header ( 'content-type:text/json;charset=utf-8' );
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php'; 
session_start ();
$db = new DB (); // 实例化
 
$first="wx_"; 

$param=$_GET; 
if(!empty($param['id'])&&!empty($param['user'])&&!empty($param['voteId']))
{
	interact($param['user'],$param['voteId'],$param['id']);
}
if(!empty($param['voteSum'])){
	voteSum($param['id']);
}
if(!empty($param['voteProjectSum'])){
	voteProjectSum($param['id']);
}
//投票项统计
function voteSum($id){
	global $db;
	global $first;
	 $d=$db->execsql("select id from ".$first."vote_interact where optionId='".$id."';");
     echo	count($d); 
}
//投票工程统计
function voteProjectSum($id){
	global $db;
	global $first;
	 $d=$db->execsql("select id from ".$first."vote_interact where voteId='".$id."';");
     echo	count($d); 
}
//用户 投票函数
function interact($openId,$voteId,$optionId){
	global $db;
	global $first;
	 $d=$db->execsql("select * from ".$first."vote_option where   voteId='".$voteId."' and id='".$optionId."';"); //防止用户恶意选择不存在的投票项
	//print_r($d);
	if(!empty($d)){
		 //判断是否重复选
		 $d=$db->getrow("select * from ".$first."vote_interact where voteId='".$voteId."' and openId='".$openId."';"); 
		 if(!empty($d)){
			 echo 0;
		 }
		 else{
			 //投票成功插入记录 
			 $data['openId']=$openId;
			 $data['optionId']=$optionId;
			 $data['voteId']=$voteId;
			 $data['date']=date("Y-m-d H:i:s");
			 //$data['ip']="ip";
			 echo $db->insert($first."vote_interact",$data);
			   
		 }
	}
	
}

  
?>