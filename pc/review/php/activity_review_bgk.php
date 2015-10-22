<?php
header ( "content-type:text/json;charset=utf-8" );
// echo $path;
require_once '../../../common/php/dbaccess.php';
$db =new DB();
$file=array();
$type=$_POST['type'];              //获取要求
$type="list";
$review_id=$_POST['id'];             //获取要显示的活动类型的ID
// $review_id=2;
/*
 * 往期回顾的列表显示
 */
if($type=="list"){	
	
	$sql_reviewName="select reviewTable from wx_activity_module where id =".$review_id; //选择往期回顾表
	$res_reviewName=$db->getrow($sql_reviewName);
// 	var_dump($res_reviewName);
	$reviewName=$res_reviewName['reviewTable'];
// 	echo $reviewName;die;
	$a= 1;
// 	$page=$_POST['page'];    //获取页码
		$page=1;
	$num=2;                //每页的容量
	$start=($page-1)*$num;
	
	$sql="select * from ".$reviewName." where review = ".$a;
// 	echo $sql;die;
	$result = mysql_query($sql);
// 	var_dump($result);
//     echo mysql_num_rows($result);

	$sql_list="select id,title,review from ".$reviewName." where review = 
	'{$a}' order by date desc limit ".$start.",".$num ;//选择能够往期回顾的信息
// 	echo $sql_list;
	$res_list=$db->execsql($sql_list);
// 	var_dump($res_list);	
	$file['details_dataBase']=$reviewName;
	$file['list']=$res_list;
	$file['num']=mysql_num_rows($result);
	echo json_encode($file);
	
	/*
	 * 往期回顾的具体显示
	 */
}elseif($type=="details"){
	$details_id=$_POST['id'];       //获取要显示信息的ID
	$details_id=1;
	$details_dataBase=$_POST['details_dataBase'];       //获取要显示信息的数据库表
	$details_dataBase="wx_activity_interact_project";

	/*
	 * 投票活动的往期回顾
	 */
	
	if($details_dataBase=="wx_vote_project"){
// 		echo 1;die;

		$sql_details="select * from ".$details_dataBase." where id =".$details_id;    //选择投票工程的具体信息
		$res_details=$db->getrow($sql_details);
		$file['details']=$res_details;
// 		var_dump($file['details']['id']) ;

		
		$sql_details_voteOption="select name from wx_vote_option where voteId ="   //选择投票项名称
				.$file['details']['id'];
		$res_details_voteOption = $db->execsql($sql_details_voteOption);
// 		var_dump($res_details_voteOption);
		$i = 1;
		foreach($res_details_voteOption as $val_details_voteOption){
// 			var_dump($val_details_voteOption);die;
// 			echo $i;
			$file['details']['name'][$i]=$val_details_voteOption;
			$i++;
		}
		
		/**************获取投票用户的具体信息*******************/
		$sql_details_voteInteract="select userId,date from wx_vote_interact where voteId ="
				.$file['details']['id'];
// 		echo $sql_details_voteInteract;
		$res_details_voteInteract = $db->execsql($sql_details_voteInteract);
// 		var_dump($res_details_voteInteract);
		
		$j=1;
		foreach($res_details_voteInteract as $val_details_voteInteract){
			
			$sql_user="select userName,wechatName from wx_user where openId=".$val_details_voteInteract['userId'];
			$res_user=$db->getrow($sql_user);
// 			var_dump($res_user);
			$file['details']['userName'][$j]=$res_user['userName'];
			$file['details']['wechatName'][$j]=$res_user['wechatName'];
			$file['details']['interact_date'][$j]=$val_details_voteInteract['date'];
			$j++;
			}
		echo json_encode($file);
		
		
	/*
	 * 活动的往期回顾
	 */
		
	}elseif($details_dataBase=="wx_activity_interact_project"){
// 		echo 1;
	
		$sql_details="select * from ".$details_dataBase." where id =".$details_id;   //获取活动互动项表的具体内容
// 		echo $sql_details;
		$res_details=$db->getrow($sql_details);
// 		var_dump($res_details);
		$file['details']=$res_details;
		
		/*
		 * 活动留言，点赞
		 */
		
		         /*************获取留言用户的信息*******************/
		
		$page=$_POST['page'];    //获取页码
		$page=1;
		$num=1;                //每页的容量
		$start=($page-1)*$num;
		
		$sql_leaveword="select userId,content,date from wx_review_leaveword where activityId =
				".$res_details['id'] ." order by date desc limit ".$start.",".$num ;         //分页显示ID 留言内容 时间
		$sql="select * from wx_review_leaveword where activityId = ".$res_details['id'];     
		$result = mysql_query($sql);														//获取要显示的留言数量
// 		echo mysql_num_rows($result);

		$res_leaveword=$db->execsql($sql_leaveword);
// 		var_dump($res_leaveword);
		$j=1;
		foreach($res_leaveword as $val_leaveword){
			$sql_user="select userName,wechatName from wx_user where openId=".$val_leaveword['userId'];
			$res_user=$db->getrow($sql_user);
// 			var_dump($res_user);
			$file['details']['leaveword_info']['userName'][$j]=$res_user['userName'];
			$file['details']['leaveword_info']['wechatName'][$j]=$res_user['wechatName'];
			$file['details']['leaveword_date'][$j]=$val_leaveword['date'];
			$file['details']['leaveword_content'][$j]=$val_leaveword['content'];
			$file['details']['leaveword_num']=mysql_num_rows($result);
			$j++;
		}
		
		/*************获取点赞用户的信息*******************/
		$sql_zan="select userId,date from wx_review_zan where activityId =".$res_details['id'];
		$result= mysql_query($sql_zan);
		
		$res_zan=$db->execsql($sql_zan);
// 		var_dump($res_zan);
		$k=1;
		foreach($res_zan as $val_zan){
			$sql_user="select userName,wechatName from wx_user where openId=".$val_zan['userId'];
			$res_user=$db->getrow($sql_user);
			// 			var_dump($res_user);
			$file['details']['zan_info']['userName'][$k]=$res_user['userName'];
			$file['details']['zan_info']['wechatName'][$k]=$res_user['wechatName'];
			$file['details']['zan_date'][$k]=$val_zan['date'];
			$file['details']['zan_num']=mysql_num_rows($result);
			$k++;
		}
		
		/*****************获取活动用户的具体信息*********************/
// 		echo $res_details['id'];
		$sql_activityInteract="select id,userId,multimediaFile,content,date from wx_activity_interact where projectId ="
				.$res_details['id'];

		$res_activityInteract=$db->execsql($sql_activityInteract);
// 		var_dump($res_activityInteract);
		$i=1;
// 		var_dump($res_activityInteract);
		foreach($res_activityInteract as $val_activityInteract){
			$sql_user="select userName,wechatName from wx_user where openId=".$val_activityInteract['userId'];
			$res_user=$db->getrow($sql_user);
// 			var_dump($res_user);
			$file['details']['userName'][$i]=$res_user['userName'];
			$file['details']['wechatName'][$i]=$res_user['wechatName'];
			$file['details']['interact_date'][$i]=$val_activityInteract['date'];
			
// 			$file['details']['userId'][$i]=$val_activityInteract['userId'];
// 			var_dump($val_activityInteract['multimediaFile']);
			$val_activityInteract_media=explode(';',$val_activityInteract['multimediaFile']);
// 			var_dump($val_activityInteract_media);
			$file['details']['multimediaFile'][$i]=$val_activityInteract_media;
			$file['details']['content'][$i]=$val_activityInteract['content'];			
			$i++;
		}
		
		echo json_encode($file);
	}
	
}



















