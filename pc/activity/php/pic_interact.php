<?php
header("content-type:text/json;charset=utf-8");
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php';
$db=new DB();
$type=$_GET['type'];
if ($type=='list'){
	/***************分页显示图片互动活动列表*******************/
	$page=$_GET['page'];//获得当前页码
	$num=10;//每页显示的条数
	$start=($page-1)*$num;
	$sql_list="select id,title from wx_activity_interact_project where activityID=2 limit ".$start.",".$num;
	$res_list=$db->execsql($sql_list);
	if (empty($res_list)){
		$list['error']=2;//最后一页或第一页
	}else {
		$list['list']=$res_list;
	}
	echo json_encode($list);
}elseif ($type=='add'){
	/***************后台管理员新增图片互动的活动*******************/
	/* $activity['title']=$_GET['title'];//活动标题
	$activity['content']=$_GET['content'];//活动描述
	$activity['num']=$_GET['num'];//允许一次性上传图片的最大数量
	$activity['date']=date('Y-m-d H:i:s',time());//活动发起的时间 */
	$activity['title']='';//活动标题
	$activity['content']=$_GET['content'];//活动描述
	$activity['num']=$_GET['num'];//允许一次性上传图片的最大数量
	$activity['date']=date('Y-m-d H:i:s',time());//活动发起的时间
	$activity['type']=0;//活动上传的多媒体文件类型为“图片”
	$activity['activityID']=2;//在wx_activity表中的ID号
	$activity['review']=0;//默认为不用于往期回顾
	if (empty($activity['title'])||empty($activity['content'])||empty($activity['num'])){
		echo 2;//请检查空项
	}else {
		$insert_activity=$db->insert('wx_activity_interact_project', $activity);
		if ($insert_activity){
			echo 1;//添加成功
		}else {
			echo 0;//添加失败
		}
	}
}elseif ($type=='delete'){
	/***************后台管理员删除图片互动的活动*******************/
	$id=$_GET['id'];
	//查出参与该活动的所有记录中的多媒体文件url
	$sql_media="select media from wx_activity_interact where projectId=".$id;
	$res_media=$db->execsql($sql_media);
	//从工程表中删除该活动项
	$sql_del="delete from wx_activity_interact_project where id=".$id;
	$res_del=$db->execsql($sql_del);
	if (mysql_affected_rows()){
		//将参与该活动的所有的图片文件删除
		foreach ($res_media as $val_media){
			$media=explode(';', $res_media);
			foreach ($media as $val_one){
				unlink($val_one);
			}
		}
		//将用户互动表中所有关于该活动项的记录删除
		$sql_del_interact="delete from wx_activity_interact where projectId=".$id;
		$res_del_interact=$db->execsql($sql_del_interact);
		if (mysql_affected_rows()){
			echo 1;//删除成功
		}else {
			echo 0;//删除失败，请联系技术支持
		}
	}else {
		echo 0;//删除失败，请联系技术支持
	}
}elseif ($type=='update'){
	/***************后台管理员修改图片互动的活动,点击“修改”按钮的操作*******************/
	$id=$_GET['id'];
	$sql_check_update="select title,content,num from wx_activity_interact_project where id=".$id;
	$res_check_update=$db->getrow($sql_check_update);
	echo json_encode($res_check_update);
}elseif ($type=='updateOK'){
	/***************后台管理员修改图片互动的活动,点击“提交”按钮的操作*******************/
	$id=$_GET['id'];
	$title=$_GET['title'];//活动标题
	$content=$_GET['content'];//活动描述
	$num=$_GET['num'];//允许一次性上传图片的最大数量
	if (empty($title)||empty($content)||empty($num)){
		echo 2;//请检查空项
	}else {
		$sql_update="update wx_activity_interact_project set title='{$title}',content='{$content}',num='{$num}' where id=".$id;
		$res_update=$db->execsql($sql_update);
		if (mysql_affected_rows()){
			echo 1;//更新成功
		}else {
			echo 0;//更新失败
		}
	}
}elseif ($type=='check'){
	/***************查看某项活动的用户参与情况*******************/
	$id=$_GET['id'];
	$sql_check="select userId,media,content,date from wx_activity_interact where projectId=".$id;
	$res_check=$db->execsql($sql_check);
// 	将查询出的每个media的url根据“；”分开，单独存放
	foreach ($res_check as $key_check=>$val_check){
		$check_media=explode(';', $val_check['media']);
		foreach ($check_media as $val_check_media){
			$res_check[$key_check]['media'][]=$val_check_media;
		}	
	}
	echo json_encode($res_check);
}elseif ($type=='join'){
	/***************微信端用户参与某一个图片互动活动*******************/
	$id=$_GET['id'];//活动项的ID
	$join=array();//用于数据库的插入
	$join_pre=array();//用于往前台传数据
	//查询该活动项允许一次同时上传的图片数量最大值
	$sql_size="select num from wx_activity_interact_project where id=".$id;
	$res_size=$db->getrow($sql_size);
	/*****获取上传图片的url****/
	$dest=array();
	$dest=uploadmulti(0);
	// var_dump($dest);
	$media_num=count($dest);
	$dest_db=$dest[0];
	for ($i=1;$i<$num;$i++){
		$dest_db.=';'.$dest[$i];
	}
	$join_pre['num']=$res_size['num'];
	$join['projectId']=2;
	$join['userId']=$_SESSION['user']['id'];
	$join['media']=$dest_db;
	$join['content']=$_GET['content'];
	$join['date']=date('Y-m-d H:i:s',time());
	
	if ($media_num>$res_size['num']){
		$join_pre['error']=3;//上传的图片数量不能超过$res_size['num']的值
	}elseif (empty($join['media'])) {
		$join_pre['error']=4;//请上传图片
	}else{
		$insert_join=$db->insert('wx_activity_interact', $join);
		if ($insert_join){
			$join_pre['error']=1;//参与成功
		}else {
			$join_pre['error']=0;//参与失败,请联系技术支持
		}
	}
	echo json_encode($join_pre);
}


















