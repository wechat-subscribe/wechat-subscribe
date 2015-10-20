<?php
/*
 * 文章信息展示:列表显示+内容显示
 * 
 * 根据传入的moduleId，进行某模块的文章信息展示
 * $module=1:文章信息
 * $module=2:故事集
 * $module=3:视频集
 * $module=4:公司新闻
 * $module=5:行业动态
 * 
 * 根据传入的$type确定执行的操作
 * $type='list': 显示文章信息列表
 * $type='details': 显示某篇文章信息的具体内容
 * $type='leaveword': 用户评论
 * $type='zan': 用户点赞
 * $type='deleteInfo': 后台管理员删除某篇文章
 * $type='updateInfo': 后台管理员编辑、修改文章信息的具体内容
 * $type='deleteLeaveword': 后台管理员删除某篇文章的评论
 * $type='updateLeaveword': 后台管理员编辑、修改文章信息的评论内容
 */
header ( "content-type:text/json;charset=utf-8" );
// echo $path;
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php';
require_once '../../../common/php/regexTool.class.php';
require_once '../../../common/php/leaveword.class.php';
require_once '../../../common/php/zan.class.php';
// $type=$_GET['type'];//list:列表显示；details:具体内容显示
// $type='list';
// $type='details';
// $type='leaveword';
// $type = 'zan';
// $type = 'deleteInfo';
//$type = 'updateInfo';
// $type = 'deleteLeaveword';
// $type = 'updateLeaveword';
// $type = 'updateLeavewordOK';
session_start ();
$db = new DB ();
$lwd=new LWD('wx_leaveword');
$zan=new ZAN('wx_zan');
$regex=new regexTool();
if ($type == 'list') {
	/**
	 * ************显示文章信息列表***************
	 */
	// echo "test";
	$page=$_GET['page'];
	$moduleId=$_GET['moduleId'];//获取模块ID
// 	$moduleId = 1; // 获取模块ID
// 	$page=3;
	$list = array ();
	$num=10;//每页显示10条
	$start=($page-1)*$num;//本页显示的起始位置
	// 从wx_info中查询出文章信息的基本文章信息
	$sql_info_num = "select id from wx_info where moduleId='{$moduleId}' ";
	$res_info_num=$db->execsql($sql_info_num);
	$list['PageNum']=ceil(count($res_info_num)/$num);
	$sql_info = "select id,title,thumb,date,is_leaveword,is_zan from wx_info  where moduleId='{$moduleId}' order by date desc limit ".$start.",".$num;
	// echo $sql_info;die;
	$res_info = $db->execsql ( $sql_info );
	foreach ( $res_info as $key_list => $val_list ) {
		/* // 根据userId在wx_user中查询出作者的姓名
		$sql_user_name = "select userName from wx_user where id='{$val_list ['userId']}'";
		$res_user_name = $db->getrow ( $sql_user_name ); */
		
		// 根据文章信息ID在wx_leaveword表中查询出该文章信息的评论次数，在wx_zan表中查询出该文章信息的点赞次数
		$list [$key_list] ['num_leaveword'] = $lwd->lwdNum($val_list ['id']);//评论次数
		$list [$key_list] ['num_zan']= $zan->zanNum($val_list ['id']);//点赞次数
		$list [$key_list] ['id'] = $val_list ['id'];
		$list [$key_list] ['thumb'] = $val_list ['thumb'];
		$list [$key_list] ['title'] = $val_list ['title'];
		$list [$key_list] ['date'] = $val_list ['date'];
// 		$list [$key_list] ['userName'] = $res_user_name ['userName'];
	}
// 	var_dump($list);
	echo json_encode ( $list );
} elseif ($type == 'details') {
	/**
	 * ************显示某篇文章信息的具体内容***************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$infoId = 1; // 获取显示具体内容的文章信息ID
	
	if ($regex->isNumber($infoId)){
		$sql_info_details = "select media,title,date,content,is_leaveword,is_zan from wx_info where id='{$infoId}'";
		$res_info_details = $db->getrow ( $sql_info_details );
		
		/* // 根据userId在wx_user中查询出作者的姓名
		 $sql_user_name = "select userName from wx_user where id='{$res_info_details ['userId']}'";
		$res_user_name = $db->getrow ( $sql_user_name ); */
		
		// 根据文章信息ID在wx_leaveword表中查询出该文章信息的评论次数和详情，在wx_zan表中查询出该文章信息的点赞次数
		$page=$_GET['page'];
// 		$page=1;
		$num=10;//每页显示10条评论
		$details=$lwd->showLwd($page, $num, $infoId);//分页显示具体的评论信息
		$details  ['num_zan']= $zan->zanNum($infoId);//点赞次数 
		//将图片的多个url分离
		$detail_media=explode(';', $res_info_details['media']);
		foreach ($detail_media as $val_detail_media){
			$details ['media'][] = $val_detail_media;
		}
		// 	$details ['userName'] = $res_user_name ['userName'];
		$details ['title'] = $res_info_details ['title'];
		$details ['date'] = $res_info_details ['date'];
		$details ['content'] = $res_info_details ['content'];
// 		var_dump($details);
		echo json_encode ( $details );
	}
	
} elseif ($type == 'leaveword') {
	/**
	 * *****************用户评论**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$infoId = 1; // 获取显示具体内容的文章信息ID
	$content=$_GET['content'];
// 	$content="封装测试";
// 	$_SESSION['user']['id']=1;
	echo $lwd->lwdAdd($infoId, $content);
} elseif ($type == 'zan') {
	/**
	 * *****************用户点赞**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$infoId = 1; // 获取显示具体内容的文章信息ID
// 	$_SESSION['user']['id']=1;
	echo $zan->zanAdd($infoId);
} elseif ($type == 'deleteInfo') {
	/**
	 * *****************后台管理员删除某篇文章，以及用户对其的评论和点赞**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$infoId = 2; // 获取显示具体内容的文章信息ID
	if ($regex->isNumber($infoId)){
		if (empty($infoId)){
			echo 0;//删除失败，请联系技术支持
		}else {
			$sql_pic="select media,thumb from wx_info where Id='{$infoId}'";
			$res_pic=$db->getrow($sql_pic);
		
// 			$sql_del = "delete from wx_info where Id='{$infoId}'";
			$sql_del="delete a,b,c from wx_info as a left join wx_leaveword as b on a.id=b.infoId
					                                 left join wx_zan as c on a.id=c.infoId
												     where a.id='{$infoId}'";
			$res_del = $db->execsql ( $sql_del );
			$res = mysql_affected_rows ();
			if ($res>0) {
				/* //将图片的多个url分离,并删除图片文件
					$deleteInfo_media=explode(';', $res_pic['media']);
					foreach ($deleteInfo_media as $val_deleteInfo_media){
					unlink($val_deleteInfo_media);
					} */
				unlink($res_pic['thumb']);
				echo 1; // 删除成功
			} else {
				echo 0; // 删除失败，请联系技术支持
			}
		}
	}else{
		echo 0;
	}
	
}elseif ($type == 'updateInfo') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“修改”按钮**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
	//$infoId = 5; // 获取显示具体内容的文章信息ID
	if ($regex->isNumber($infoId)){
		$sql_updateInfo="select title,content,is_leaveword,is_zan,thumb from wx_info where id='{$infoId}'";
		$res_updateInfo=$db->getrow($sql_updateInfo);
		/* // 	将查询出的media的url根据“；”分开，单独存放
		 $updateInfo_media=explode(';', $res_updateInfo['media']);
		 foreach ($updateInfo_media as $val_updateInfo_media){
		 $updateInfo['media'][]=$val_updateInfo_media;
		} */
		$updateInfo['title']=$res_updateInfo['title'];
		$updateInfo['content']=$res_updateInfo['content'];
		$updateInfo['is_leaveword']=$res_updateInfo['is_leaveword'];
		$updateInfo['is_zan']=$res_updateInfo['is_zan'];
		$updateInfo['thumb']=$res_updateInfo['thumb'];
		//var_dump($updateInfo);
		echo json_encode($res_updateInfo);
	}
	
}elseif ($type == 'updateInfoOK') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“提交”按钮**********************
	 */
	$subtype=$_GET['subtype'];
// 	$mediatype=$_GET['mediatype'];//0：图片；1：视频；2：缩略图
	if ($subtype=='thumb'){
		$dest=uploadmulti('thumbpic',2); 
		$_SESSION['thumb']=$dest[0];
		echo $dest[0];
	}
	/* elseif($subtype=='media'){
		//获取上传图片的url
		$dest=array();
		$dest=uploadmulti(0);
		// var_dump($dest);
		$media_num=count($dest);
		$dest_db=$dest[0];
		for ($i=1;$i<$num;$i++){
			$dest_db.=';'.$dest[$i];
		}
		$media=$dest_db;
		echo $media;
	} */
	else{
		$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 		$infoId = 5; // 获取显示具体内容的文章信息ID
		if ($regex->isNumber($infoId)){
			$title=$_GET['title'];
	// 		$title='修改1';
				$content=$_GET['content'];
	// 		$content='修改内容1';
				$is_leaveword=$_GET['is_leaveword'];
	// 		$is_leaveword=1;
				$is_zan=$_GET['is_zan'];
	// 		$is_zan=1;
			if (empty($infoId)||empty($title)||empty($content)||empty($is_leaveword)||empty($is_zan)){
				echo 2;//请检查空值
			}else {
				if($_SESSION['thumb'] != ''){
					$sql_update="update wx_info set title='{$title}',content='{$content}',thumb='{$_SESSION['thumb']}',is_leaveword='{$is_leaveword}',is_zan='{$is_zan}' where id='{$infoId}'";
				}else{
					$sql_update="update wx_info set title='{$title}',content='{$content}',is_leaveword='{$is_leaveword}',is_zan='{$is_zan}' where id='{$infoId}'";
				}
				// 	echo $sql_update;
				$res_update=$db->execsql($sql_update);
				$res=mysql_affected_rows();
				if ($res>0){
					$_SESSION['thumb'] = '';
					echo 1;//修改成功
				}else {
					echo 0;//修改失败
				}
			}
		}else {
			echo 0;
		}
	}
		
}elseif ($type == 'deleteLeaveword') {
	/**
	 * *****************后台管理员删除某篇文章的评论**********************
	 */ 
	$id=$_GET['id'];//评论ID
// 	$id=1;
	echo $lwd->delLwd($id);
}elseif ($type == 'updateLeaveword') {
	/**
	 * *****************后台管理员编辑、修改文章信息的评论内容,点击“修改”按钮**********************
	 */
	$id=$_GET['id'];//评论ID
// 	$id=1;
	$leave=$lwd->updateLwd($id);
// 	var_dump($leave);
	echo json_encode($leave);
}elseif ($type == 'updateLeavewordOK') {
	/**
	 * *****************后台管理员编辑、修改文章信息的评论内容,点击“提交”按钮**********************
	 */
	$id=$_GET['id'];//评论ID
// 	$id=1;
	$content=$_GET['content'];//修改后的评论内容
// 	$content='封装修改评论内容6666666666';
	echo $lwd->updateLwdOK($id,$content);
}