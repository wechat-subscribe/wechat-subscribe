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
require_once '../../common/php/dbaccess.php';
require_once '../../common/php/uploadFiles.php';
$type=$_GET['type'];//list:列表显示；details:具体内容显示
// $type='list';
// $type='details';
// $type='leaveword';
// $type = 'zan';
// $type = 'deleteInfo';
//$type = 'updateInfo';
// $type = 'deleteLeaveword';
// $type = 'updateLeaveword';
session_start ();
$db = new DB ();
if ($type == 'list') {
	/**
	 * ************显示文章信息列表***************
	 */
	// echo "test";
	$page=$_GET['page'];
	$moduleId=$_GET['moduleId'];//获取模块ID
	//$moduleId = 1; // 获取模块ID
	//$page=2;
	$num=3;//每页显示10条
	$start=($page-1)*$num;//本页显示的起始位置
	// 从wx_info中查询出文章信息的基本文章信息
	$sql_info = "select id,title,thumb,date,is_leaveword,is_zan from wx_info where moduleId='{$moduleId}' limit ".$start.",".$num;
// 	echo $sql_info;die;
	$res_info = $db->execsql ( $sql_info );
	$list = array ();
	foreach ( $res_info as $key_list => $val_list ) {
		/* // 根据userId在wx_user中查询出作者的姓名
		$sql_user_name = "select userName from wx_user where id='{$val_list ['userId']}'";
		$res_user_name = $db->getrow ( $sql_user_name ); */
		
		// 根据文章信息ID在wx_leaveword表中查询出该文章信息的评论次数，在wx_zan表中查询出该文章信息的点赞次数
		if ($val_list ['is_leaveword'] == 1) {
			$sql_num_leaveword = "select id from wx_leaveword where infoId='{$val_list ['id']}'";
			$res_num_leaveword = $db->execsql ( $sql_num_leaveword );
			$num_leaveword = count ( $res_num_leaveword ); // 评论次数
			$list [$key_list] ['num_leaveword'] = $num_leaveword;
		}
		if ($val_list ['is_zan'] == 1) {
			$sql_num_zan = "select id from wx_zan where infoId='{ $val_list ['id']}'";
			$res_num_zan = $db->execsql ( $sql_num_zan );
			$num_zan = count ( $res_num_zan ); // 点赞次数
			$list [$key_list] ['num_zan'] = $num_zan;
		}
		
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
	//$infoId = 5; // 获取显示具体内容的文章信息ID
	$sql_info_details = "select media,title,date,content,is_leaveword,is_zan from wx_info where id='{$infoId}'";
	$res_info_details = $db->getrow ( $sql_info_details );
	
	/* // 根据userId在wx_user中查询出作者的姓名
	$sql_user_name = "select userName from wx_user where id='{$res_info_details ['userId']}'";
	$res_user_name = $db->getrow ( $sql_user_name ); */
	
	// 根据文章信息ID在wx_leaveword表中查询出该文章信息的评论次数和详情，在wx_zan表中查询出该文章信息的点赞次数
	if ($res_info_details ['is_leaveword'] == 1) {
		$sql_leaveword = "select id,userId,content,date from wx_leaveword where infoId='{$infoId}'";
		$res_leaveword = $db->execsql ( $sql_leaveword );
		// 评论次数
		$num_leaveword = count ( $res_leaveword );
		$details ['num_leaveword'] = $num_leaveword;
		if ($num_leaveword > 0) {
			foreach ( $res_leaveword as $key_leaveword => $val_leaveword ) {
				// 根据userId在wx_user中查询出作者的微信号和微信头像
				$sql_user_name = "select wechatName, header from wx_user where id='{$val_leaveword ['userId']}'";
				$res_user_name = $db->getrow ( $sql_user_name );
// 				echo $sql_user_name;
// 				var_dump($res_user_name);die;
				$details ['leaveword'][$key_leaveword] ['id'] = $val_leaveword ['id'];
				$details ['leaveword'][$key_leaveword] ['content'] = $val_leaveword ['content'];
				$details ['leaveword'][$key_leaveword] ['date'] = $val_leaveword ['date'];
				$details ['leaveword'][$key_leaveword] ['wechatName'] = $res_user_name ['wechatName'];
				$details ['leaveword'][$key_leaveword] ['header'] = $res_user_name ['header'];
			}
		}
	}
	if ($res_info_details ['is_zan'] == 1) {
		$sql_num_zan = "select id from wx_zan where infoId='{$infoId}'";
		$res_num_zan = $db->execsql ( $sql_num_zan );
		$num_zan = count ( $res_num_zan ); // 点赞次数
		$details  ['num_zan'] = $num_zan;
	}
	//将图片的多个url分离
	$detail_media=explode(';', $res_info_details['media']);
	foreach ($detail_media as $val_detail_media){
		$details ['media'][] = $val_detail_media;
	}
// 	$details ['userName'] = $res_user_name ['userName'];
	$details ['title'] = $res_info_details ['title'];
	$details ['date'] = $res_info_details ['date'];
	$details ['content'] = $res_info_details ['content'];
	
	///var_dump($details);
 	echo json_encode ( $details );
} elseif ($type == 'leaveword') {
	/**
	 * *****************用户评论**********************
	 */
	$leaveword['infoId']=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$leaveword ['infoId'] = 5; // 获取显示具体内容的文章信息ID
	$leaveword['content']=$_GET['content'];
// 	$leaveword ['content'] = '精彩！！！！！！';
	$leaveword['userId']=$_SESSION['user']['id'];
// 	$leaveword ['userId'] = 1;
	if (empty($leaveword['content'])){
		echo 2;//评论不能为空;
	}else{
		$leaveword ['date'] = date ( 'Y-m-d H:i:s', time () );
		$insert = $db->insert ( 'wx_leaveword', $leaveword );
		if ($insert) {
			echo 1; // 评论成功
		} else {
			echo 0; // 评论失败
		}
	}
} elseif ($type == 'zan') {
	/**
	 * *****************用户点赞**********************
	 */
	$zan['infoId']=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$zan ['infoId'] = 5; // 获取显示具体内容的文章信息ID
	$zan['userId']=$_SESSION['user']['id'];
// 	$zan ['userId'] = 1;
	$sql_is_zan = "select id from wx_zan where infoId='{$zan ['infoId'] }' and userId='{$zan ['userId']}'";
	$res_is_zan = $db->getrow ( $sql_is_zan );
	// echo $sql_is_zan;die;
	// echo empty($res_is_zan);die;
	if (! empty ( $res_is_zan )) {
		echo 2; // 你已赞过该文章信息
	} else {
		$zan ['date'] = date ( 'Y-m-d H:i:s', time () );
		$insert = $db->insert ( 'wx_zan', $zan );
		if ($insert) {
			echo 1; // 点赞成功
		} else {
			echo 0; // 点赞失败
		}
	}
} elseif ($type == 'deleteInfo') {
	/**
	 * *****************后台管理员删除某篇文章**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
// 	$infoId = 2; // 获取显示具体内容的文章信息ID
	if (empty($infoId)){
		echo 0;//删除失败，请联系技术支持
	}else {
		$sql_pic="select media,thumb from wx_info where Id='{$infoId}'";
		$res_pic=$db->getrow($sql_pic);
		
		$sql_del = "delete from wx_info where Id='{$infoId}'";
		$res_del = $db->execsql ( $sql_del );
		$res = mysql_affected_rows ();
		if ($res) {
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
}elseif ($type == 'updateInfo') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“修改”按钮**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
	//$infoId = 5; // 获取显示具体内容的文章信息ID
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
}elseif ($type == 'updateInfoOK') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“提交”按钮**********************
	 */
	$subtype=$_GET['subtype'];
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
			if ($res){
				$_SESSION['thumb'] = '';
				echo 1;//修改成功
			}else {
				echo 0;//修改失败
			}
		}
	}
		
}elseif ($type == 'deleteLeaveword') {
	/**
	 * *****************后台管理员删除某篇文章的评论**********************
	 */ 
	$id=$_GET['id'];//评论ID
// 	$id=1;
	if (empty($id)){
		echo 0;//删除失败，请联系技术支持
	}else {
		$sql_del_leaveword = "delete from wx_leaveword where Id=".$id;
		$res_del_leaveword = $db->execsql ( $sql_del_leaveword );
		$res = mysql_affected_rows ();
		if ($res) {
			echo 1; // 删除成功
		} else {
			echo 0; // 删除失败，请联系技术支持
		}
	}	
}elseif ($type == 'updateLeaveword') {
	/**
	 * *****************后台管理员编辑、修改文章信息的评论内容**********************
	 */
	$id=$_GET['id'];//评论ID
// 	$id=1;
	$content=$_GET['content'];
// 	$content='修改内容11111111';
	if (empty($content)){
		echo 2;//请检查空值
	}else {
		$sql_update_leaveword="update wx_leaveword set content='{$content}' where id='{$id}'";
		// 	echo $sql_update;
		$res_update_leaveword=$db->execsql($sql_update_leaveword);
		$res=mysql_affected_rows();
		if ($res){
			echo 1;//修改成功
		}else {
			echo 0;//修改失败
		}
	}
} 