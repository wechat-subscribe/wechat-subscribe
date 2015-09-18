<?php
/*
 * 信息展示:列表显示+内容显示
 */
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
// $moduleId=$_GET['moduleId'];//获取模块ID
$moduleId=1;//获取模块ID
// $type=$_GET['type'];//list:列表显示；details:具体内容显示
// $type='list';
$type='details';
$db=new DB();
//从wx_info中查询出文章的基本信息
$sql_info="select id,userId,title,content,picture,date,is_leaveword,is_zan from wx_info where moduleId=".$moduleId;
$res_info=$db->execsql($sql_info);
// echo $sql_info.'</br>';
// print_r($res_info);die;
if ($type=='list'){
// 	echo "test";
	$list=array();
	foreach ($res_info as $key_list=>$val_list){
		//根据userId在wx_user中查询出作者的姓名
		$sql_user_name="select userName from wx_user where id=".$val_list['userId'];
		$res_user_name=$db->getrow($sql_user_name);
		//根据文章ID在wx_leaveword表中查询出该文章的评论次数，在wx_zan表中查询出该文章的点赞次数
		if ($val_list['is_leaveword']==1){
			$sql_num_leaveword="select id from wx_leaveword where infoId=".$val_list['id'];
			$res_num_leaveword=$db->execsql($sql_num_leaveword);
			$num_leaveword=count($res_num_leaveword);//评论次数
			$list[$key_list]['num_leaveword']=$num_leaveword;
		}
		if ($val_list['is_zan']==1){
			$sql_num_zan="select id from wx_zan where infoId=".$val_list['id'];
			$res_num_zan=$db->execsql($sql_num_zan);
			$num_zan=count($res_num_zan);//点赞次数
			$list[$key_list]['num_zan']=$num_zan;
		}
		$list[$key_list]['picture']=$val_list['picture'];
		$list[$key_list]['title']=$val_list['title'];
		$list[$key_list]['userName']=$res_user_name['userName'];
	}
// 	var_dump($list);
}elseif ($type=='details') {
// 	 $infoId=$_GET['infoId'];//获取显示具体内容的文章ID
	 $infoId=1;//获取显示具体内容的文章ID
	 $sql_info_details="select userId,title,date,content,is_leaveword,is_zan from wx_info where id=".$infoId;
	 $res_info_details=$db->getrow($sql_info_details);
	 //根据userId在wx_user中查询出作者的姓名
	 $sql_user_name="select userName from wx_user where id=".$res_info_details['userId'];
	 $res_user_name=$db->getrow($sql_user_name);
	 //根据文章ID在wx_leaveword表中查询出该文章的评论次数和详情，在wx_zan表中查询出该文章的点赞次数
	 if ($res_info_details['is_leaveword']==1){
	 	$sql_leaveword="select userId,content,date from wx_leaveword where infoId=".$infoId;
	 	$res_leaveword=$db->execsql($sql_leaveword);
	 	//评论次数
	 	$num_leaveword=count($res_leaveword);
	 	$details[$key_list]['num_leaveword']=$num_leaveword;
	 	if ($num_leaveword>0){
	 		foreach ($res_leaveword as $key_leaveword=>$val_leaveword){
	 			//根据userId在wx_user中查询出作者的姓名
	 			$sql_user_name="select userName from wx_user where id=".$val_leaveword['userId'];
	 			$res_user_name=$db->getrow($sql_user_name);
	 			$details[$key_leaveword]['content']=$val_leaveword['content'];
	 			$details[$key_leaveword]['date']=$val_leaveword['date'];
	 			$details[$key_leaveword]['userName']=$res_user_name['userName'];
	 		}
	 	}
	 	
	 	
	 }
	 if ($res_info_details['is_zan']==1){
	 	$sql_num_zan="select id from wx_zan where infoId=".$infoId;
	 	$res_num_zan=$db->execsql($sql_num_zan);
	 	$num_zan=count($res_num_zan);//点赞次数
	 	$details[$key_list]['num_zan']=$num_zan;
	 }
	 $details['userName']=$res_user_name['userName'];
	 $details['title']=$res_info_details['title'];
	 $details['date']=$res_info_details['date'];
	 $details['content']=$res_info_details['content'];
	 var_dump($details);
}