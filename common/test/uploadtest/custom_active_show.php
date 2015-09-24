<?php
/*
 * 活动展示:选出获赞最多的用户
 */
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
// $moduleId=$_GET['moduleId'];//获取模块ID
$moduleId=1;//获取模块ID
$show_details=$_GET['show_details'];
$db=new DB();
$data=array();
    
	$sql_info_zan_="select a.infoId,a.num,b.userId from wx_zan as a inner join wx_info as b 
					where moduleId=".$moduleId . "order by zan on a.infoId = b.id";//获取点赞最多的用户文章id,用户id
																						
	$res_info_zan=$db->getrow($sql_num_zan_id);
	$sql_info_zan_userId=$res_info_zan['b.userId'];//用户id
	$sql_info_zan_num=$res_info_zan['a.num'];//点赞数量
	$sql_info_zan_id=$res_info_zan['a.infoId'];//获取用户文章id
	$sql_info_zan_user="select userName from wx_user where id=".$sql_info_zan_userId;//获取用户名
	$data['userName']=$sql_info_zan_user;
	$data['num']=$sql_info_zan_num;
	
if($show_content='1'){
	$sql_info="select content,picture,date from wx_info where id=".$sql_info_zan_id;
	$res_info=$db->execsql($sql_info);
	$info_content=$res_info['content'];
	$info_picture=$res_info['picture'];
	$info_date=$res_info['date'];
	if($info_content){
		$data['content']=$info_content;
	}else{
		 $data['error']=2;//内容不存在
	}
	if($info_picture){
		$data['picture']=$info_picture;
	}else{
		$data['error']=3;//图片不存在
	}
}



























