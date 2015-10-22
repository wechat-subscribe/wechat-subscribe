<?php
/**
 * 图片互动的操作
 * $type='list';//分页显示图片互动活动列表
 * $type='add';//后台管理员新增图片互动的活动
 * $type='update';//后台管理员修改图片互动的活动,点击“修改”按钮的操作
 * $type='updateOK';//后台管理员修改图片互动的活动,点击“提交”按钮的操作
 * $type='join';//微信端用户参与某一个图片互动活动
 * $type='check';//查看某项活动的用户参与情况
 * $type='details';//查看某项活动的某个用户参与的具体信息，以及其他用户对其的评论和点赞
 * $type="delete";//删除某项活动，用户的参与，以及其他用户的评论
 * $type="deleteSomeone";//删除某人的参与内容以及其他用户对其的评论
 * $type = 'leaveword';//用户评论
 * $type = 'zan';//用户点赞
 * $type = 'deleteLeaveword';//后台管理员删除某篇文章的评论
 * $type = 'updateLeaveword';//后台管理员编辑、修改文章信息的评论内容,点击“修改”按钮
 * $type = 'updateLeavewordOK';//后台管理员编辑、修改文章信息的评论内容,点击“提交”按钮
 */


header("content-type:text/html;charset=utf-8");
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php';
require_once '../../../common/php/regexTool.class.php';
require_once '../../../common/php/leaveword.class.php';
require_once '../../../common/php/zan.class.php';
$db=new DB();
$lwd=new LWD('wx_activity_leaveword');
$zan=new ZAN('wx_activity_zan');


$regex=new regexTool();
// $type=$_GET['type'];
// $type='list';
$type='add';
// $type='update';
// $type='updateOK';
// $type='join';
// $type='check';
// $type='details';
// $type="delete"; 
// $type="deleteSomeone"; 
// $type = 'leaveword';
// $type = 'zan';
// $type = 'deleteLeaveword';
// $type = 'updateLeaveword';
// $type = 'updateLeavewordOK';
if ($type=='list'){
	/***************分页显示图片互动活动列表*******************/
	$menuId=$_GET['menuId'];
// 	$menuId='10';//图片互动的菜单ID
	if ($regex->isNumber($menuId)){
		$page=$_GET['page'];//获得当前页码
// 			$page='1';//获得当前页码
		$num=10;//每页显示的条数
		
		$sql_list1="select a.id from wx_activity_interact_project as a left join wx_activity_module as b on a.moduleId=b.id where b.menuId='{$menuId}'";
		$res_list1=$db->execsql($sql_list1);
// 			echo $sql_list1;die;
		$list['PageNum']=ceil(count($res_list1)/$num);//总页数
		$start=($page-1)*$num;
		$sql_list="select a.id,a.title,a.start,a.end from wx_activity_interact_project as a left join wx_activity_module as b on a.moduleId=b.id where b.menuId='{$menuId}' order by a.start desc limit ".$start.",".$num;
		$res_list=$db->execsql($sql_list);
// 			echo $sql_list;die;
		if (empty($res_list)){
			$list['error']=2;//当前页为空
		}else {
			$list['list']=$res_list;
		}
// 			var_dump($list);
		echo json_encode($list);
	}
}elseif ($type=='add'){
	/***************后台管理员新增图片互动的活动*******************/
	
	
	$starttime=time();
	/* $activity['title']=$_GET['title'];//活动标题
	$menuId=$_GET['menuId'];//图片互动的菜单ID
	$activity['content']=$_GET['content'];//活动描述
	$activity['num']=$_GET['num'];//允许一次性上传图片的最大数量
	$activity['type']=$_GET['activitytype'];//活动上传的多媒体文件类型为“图片”
	$activity['start']=date('Y-m-d H:i:s',$starttime);//活动发起的时间
	$activity['end']=$_GET['end'];//活动的截止时间 */
	$activity['title']='图片互动活动6';//活动标题
	$activity['content']='图片互动活动6的活动描述';//活动描述
	$activity['num']=5;//允许一次性上传图片的最大数量
	
	$activity['start']=date('Y-m-d H:i:s',$starttime);//活动发起的时间
	$activity['end']=$_GET['end'];//活动的截止时间
	$activity['type']=0;//活动上传的多媒体文件类型为“图片”
	$menuId='10';//图片互动的菜单ID
// 	echo date('Y-m-d H:i:s',strtotime('+6 month'));//距今半年的日期
	$activity['review']=0;//默认为不用于往期回顾
	if ($regex->isNumber ( $menuId )) {
		if (empty ( $activity ['title'] ) || empty ( $activity ['content'] ) || empty ( $activity ['num'] )) {
			echo 2; // 请检查空项
		} elseif (($starttime < strtotime ( $activity ['end'] )) && (strtotime ( $activity ['end'] ) <= strtotime ( '+6 month' ))) {
			/**
			 * ***************截止日期为当前日期之后，距今半年之前***************************
			 */
			$sql_activity_moduleId = "select id from wx_activity_module where menuId='{$menuId}'";
			// echo $sql_activity_moduleId;die;
			$res_activity_moduleId = $db->getrow ( $sql_activity_moduleId );
			$activity ['moduleId'] = $res_activity_moduleId ['id'];
			$insert_activity = $db->insert ( 'wx_activity_interact_project', $activity );
			if ($insert_activity) {
				echo 1; // 添加成功
			} else {
				echo 0; // 添加失败
			}
		} else {
			echo 3; // 截止日期为当前日期之后，距今半年之前
		}
	}
}elseif ($type=='delete'){
	/***************后台管理员删除图片互动的活动*******************/
	
	//尝试联合删除
// 	 $projectId=$_GET['projectId'];
	 $projectId='1';//活动项Id
	//查出参与该活动的所有记录中的多媒体文件url
	$sql_media="select multimediaFile from wx_activity_interact where projectId=".projectId;
	$res_media=$db->execsql($sql_media);
	
	//联合删除
	$sql_del="delete a,b,c,d from wx_activity_interact_project as a left join wx_activity_interact as b on a.id=b.projectId
																	left join wx_activity_leaveword as c on b.id=c.activityId
			                                                        left join wx_activity_zan as d on b.id=d.activityId
																    where a.id='{$projectId}'";
	$res_del=$db->execsql($sql_del);
	if (mysql_affected_rows()>0){
		//将参与该活动的所有的图片文件删除
		foreach ($res_media as $val_media){
			$media=explode(';', $val_media['multimediaFile']);
			foreach ($media as $val_one){
				unlink($val_one);
			}
		}
		echo 1;//删除成功
	}else {
		echo 0;//删除失败，请联系技术支持
	}
}elseif ($type=="deleteSomeone"){
	/****************后台管理员删除某人的参与内容以及其他用户对其的评论********************/
	$activityId=$_GET['activityId'];//该用户参与某活动的参与Id(wx_activity_interact)
// 	$activityId='1';//该用户参与某活动的参与Id(wx_activity_interact)
	$sql_deleteSomeone = "delete a,b,c from wx_activity_interact as a left join wx_activity_leaveword as b on a.id=b.activityId
																		left join wx_activity_zan as c on a.id=c.activityId
																		where a.id='{$activityId}'";
	$res_deleteSomeone = $db->execsql ( $sql_deleteSomeone );
	if (mysql_affected_rows () > 0) {
		echo 1; // 删除成功
	} else {
		echo  0; // 删除失败，请联系技术支持
	}
}elseif ($type=='update'){
	/***************后台管理员修改图片互动的活动,点击“修改”按钮的操作*******************/
// 	$projectId=$_GET['projectId'];
	$projectId='1';
	$sql_check_update="select title,content,num from wx_activity_interact_project where id=".$projectId;
	$res_check_update=$db->getrow($sql_check_update);
	var_dump($res_check_update);
// 	echo json_encode($res_check_update);
}elseif ($type=='updateOK'){
	/***************后台管理员修改图片互动的活动,点击“提交”按钮的操作*******************/
	/* $projectId=$_GET['projectId'];
	$title=$_GET['title'];//活动标题
	$content=$_GET['content'];//活动描述
	$num=$_GET['num'];//允许一次性上传图片的最大数量 */
	$projectId='1';
	$title='修改后的活动标题';//活动标题
	$content='修改内容';//活动描述
	$num='5';//允许一次性上传图片的最大数量
	if (empty($title)||empty($content)||empty($num)){
		echo 2;//请检查空项
	}else {
		$sql_update="update wx_activity_interact_project set title='{$title}',content='{$content}',num='{$num}' where id=".$projectId;
		$res_update=$db->execsql($sql_update);
		if (mysql_affected_rows()>0){
			echo 1;//更新成功
		}else {
			echo 0;//更新失败
		}
	}
}elseif ($type == 'deleteLeaveword') {
	/**
	 * *****************后台管理员删除某篇文章的评论**********************
	 */ 
	$lwdId=$_GET['lwdId'];//评论ID
// 	$lwdId=9;
	echo $lwd->delLwd($lwdId);
}elseif ($type == 'updateLeaveword') {
	/**
	 * *****************后台管理员编辑、修改文章信息的评论内容,点击“修改”按钮**********************
	 */
	$lwdId=$_GET['lwdId'];//评论ID
// 	$lwdId=10;
	$leave=$lwd->updateLwd($lwdId);
// 	var_dump($leave);
	echo json_encode($leave);
}elseif ($type == 'updateLeavewordOK') {
	/**
	 * *****************后台管理员编辑、修改文章信息的评论内容,点击“提交”按钮**********************
	 */
// 	$lwdId=$_GET['lwdId'];//评论ID
	$lwdId=10;
// 	$content=$_GET['content'];//修改后的评论内容
	$content='封装修改评论内容6666666666';
	echo $lwd->updateLwdOK($lwdId,$content);
}elseif ($type=='check'){
	/***************查看某项活动的用户参与情况 *******************/
// 	$projectId='1';
	$projectId=$_GET['projectId'];
	if ($regex->isNumber($id)){
		
		$page=$_GET['page'];//获得当前页码
// 		$page='1';//获得当前页码
		$num=10;//每页显示的条数
				
		$sql_check1="select id,userId,multimediaFile as media from wx_activity_interact where projectId=".$projectId ;
		$res_check1=$db->execsql($sql_check1);
		$res_check['PageNum']=ceil(count($res_check1)/$num);//总页数
		$start=($page-1)*$num;
		$sql_check="select id,userId,multimediaFile as media from wx_activity_interact where projectId=".$projectId." order by date desc limit ".$start.",".$num ;
		$res_check=$db->execsql($sql_check);
		
		foreach ($res_check as $key_check=>$val_check){
			// 根据userId在wx_user中查询出作者的微信号和微信头像
			$sql_user_name = "select wechatName, header from wx_user where id='{$val_check ['userId']}'";
			$res_user_name = $db->getrow ( $sql_user_name );
			$res_check[$key_check]['wechatName'] =$res_user_name['wechatName'];
			$res_check[$key_check]['header'] =$res_user_name['header'];
			// 	将查询出的每个media的url根据“；”分开，单独存放
			$check_media=explode(';', $val_check['media']);
			$res_check[$key_check]['media']=$check_media[0];
		}
// 		var_dump($res_check);
		echo json_encode($res_check);
	} 
}elseif ($type=='details'){
	/***************查看某项活动的某个用户参与的具体信息，以及其他用户对其的评论和点赞*******************/
// 	$activityId='1';//用户参与的wx_activity_interact中的id
	$activityId=$_GET['activityId'];
	if ($regex->isNumber($activityId)){
		$sql_details="select userId,multimediaFile as media,content,date from wx_activity_interact where id=".$activityId;
		$res_details=$db->getrow($sql_details); 
// 		var_dump($res_details);die;
		// 根据userId在wx_user中查询出作者的微信号和微信头像
		$sql_user_name = "select wechatName, header from wx_user where id='{$res_details ['userId']}'";
		$res_user_name = $db->getrow ( $sql_user_name );
		$res_details['wechatName'] =$res_user_name['wechatName'];
		$res_details['header'] =$res_user_name['header'];
		// 	将查询出的每个media的url根据“；”分开，单独存放
		$check_media=explode(';', $res_details['media']);
		$res_details['media']=$check_media;
		// 根据 ID查询出评论次数和详情以及点赞次数
		$page=$_GET['page'];
// 		$page=1;
	    $num=10;//每页显示10条评论
		$leaveword =$lwd->showLwd($page, $num, $activityId);//分页显示具体的评论信息
		foreach ($leaveword as $key_lwd=>$val_lwd){
			$res_details[$key_lwd]=$val_lwd;
		}
		$res_details['num_zan']= $zan->zanNum($activityId);//点赞次数
// 		var_dump($res_details);
		echo json_encode($res_check);
	}
}elseif ($type=='join'){
	/***************微信端用户参与某一个图片互动活动*******************/
	$projectId=$_GET['projectId'];//活动项的ID
// 	$projectId='1';//活动项的ID
	$join=array();//用于数据库的插入
	$join_pre=array();//用于往前台传数据
	//查询该活动项允许一次同时上传的图片数量最大值
	$sql_size="select num from wx_activity_interact_project where id=".$projectId;
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
/* 	$join_pre['num']=$res_size['num'];
	$join['projectId']=$projectId;
	$join['userId']='1';
	$join['multimediaFile']='../upload_thumb/f3f92a36004aa8a9511a35fcfc9f0293.jpg';
	$join['content']='测试用户参与';
	$join['date']=date('Y-m-d H:i:s',time()); */
	$join_pre['num']=$res_size['num'];
	$join['projectId']=$projectId;
	$join['userId']=$_SESSION['user']['id'];
	$join['media']=$dest_db;
	$join['content']=$_GET['content'];
	$join['date']=date('Y-m-d H:i:s',time());
	
	if ($media_num>$res_size['num']){
		$join_pre['error']=3;//上传的图片数量不能超过$join_pre['num']个
	}elseif (empty($join['multimediaFile'])) {
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
}elseif ($type == 'leaveword') {
	/**
	 * *****************用户评论**********************
	 */
	$activityId=$_GET['activityId'];//获取评论对象的ID
// 	$activityId = 1; // //获取评论对象的ID
	$content=$_GET['content'];
// 	$content="封装测试3";
// 	$_SESSION['user']['id']=1; 
	echo $lwd->lwdAdd($activityId, $content);
} elseif ($type == 'zan') {
	/**
	 * *****************用户点赞**********************
	 */
    $activityId=$_GET['activityId'];//获取评论对象的ID
// 	$activityId = 1; // //获取评论对象的ID
// 	$_SESSION['user']['id']=2;
	echo $zan->zanAdd($activityId);
}