<?php
/*
 * 信息展示:列表显示+内容显示
 * 根据传入的moduleId，进行某模块的信息展示
 * $module=1:文章
 * $module=2:故事集
 * $module=3:视频集
 * $module=4:公司新闻
 * $module=5:行业动态
 */
header ( "content-type:text/html;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once $path . '/common/php/dbaccess.php';
// $moduleId=$_GET['moduleId'];//获取模块ID
$moduleId = 1; // 获取模块ID
// $type=$_GET['type'];//list:列表显示；details:具体内容显示
// $type='list';
// $type='details';
// $type='leaveword';
// $type = 'zan';
// $type = 'delete';
// $type = 'update';
session_start ();
$db = new DB ();
// 从wx_info中查询出文章的基本信息
$sql_info = "select id,userId,title,content,picture,date,is_leaveword,is_zan from wx_info where moduleId='{$moduleId}'";
$res_info = $db->execsql ( $sql_info );
if ($type == 'list') {
	/**
	 * ************显示文章列表***************
	 */
	// echo "test";
	$list = array ();
	foreach ( $res_info as $key_list => $val_list ) {
		// 根据userId在wx_user中查询出作者的姓名
		$sql_user_name = "select userName from wx_user where id='{$val_list ['userId']}'";
		$res_user_name = $db->getrow ( $sql_user_name );
		// 根据文章ID在wx_leaveword表中查询出该文章的评论次数，在wx_zan表中查询出该文章的点赞次数
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
		$list [$key_list] ['picture'] = $val_list ['picture'];
		$list [$key_list] ['title'] = $val_list ['title'];
		$list [$key_list] ['userName'] = $res_user_name ['userName'];
	}
	// var_dump($list);
	echo json_encode ( $list );
} elseif ($type == 'details') {
	/**
	 * ************显示某篇文章的具体内容***************
	 */
	// $infoId=$_GET['infoId'];//获取显示具体内容的文章ID
	$infoId = 1; // 获取显示具体内容的文章ID
	$sql_info_details = "select userId,title,date,content,is_leaveword,is_zan from wx_info where id='{$infoId}'";
	$res_info_details = $db->getrow ( $sql_info_details );
	// 根据userId在wx_user中查询出作者的姓名
	$sql_user_name = "select userName from wx_user where id='{$res_info_details ['userId']}'";
	$res_user_name = $db->getrow ( $sql_user_name );
	// 根据文章ID在wx_leaveword表中查询出该文章的评论次数和详情，在wx_zan表中查询出该文章的点赞次数
	if ($res_info_details ['is_leaveword'] == 1) {
		$sql_leaveword = "select userId,content,date from wx_leaveword where infoId='{$infoId}'";
		$res_leaveword = $db->execsql ( $sql_leaveword );
		// 评论次数
		$num_leaveword = count ( $res_leaveword );
		$details [$key_list] ['num_leaveword'] = $num_leaveword;
		if ($num_leaveword > 0) {
			foreach ( $res_leaveword as $key_leaveword => $val_leaveword ) {
				// 根据userId在wx_user中查询出作者的姓名
				$sql_user_name = "select userName from wx_user where id='{$val_leaveword ['userId']}'";
				$res_user_name = $db->getrow ( $sql_user_name );
				$details [$key_leaveword] ['content'] = $val_leaveword ['content'];
				$details [$key_leaveword] ['date'] = $val_leaveword ['date'];
				$details [$key_leaveword] ['userName'] = $res_user_name ['userName'];
			}
		}
	}
	if ($res_info_details ['is_zan'] == 1) {
		$sql_num_zan = "select id from wx_zan where infoId='{$infoId}'";
		$res_num_zan = $db->execsql ( $sql_num_zan );
		$num_zan = count ( $res_num_zan ); // 点赞次数
		$details [$key_list] ['num_zan'] = $num_zan;
	}
	$details ['userName'] = $res_user_name ['userName'];
	$details ['title'] = $res_info_details ['title'];
	$details ['date'] = $res_info_details ['date'];
	$details ['content'] = $res_info_details ['content'];
	// var_dump($details);
	echo json_encode ( $details );
} elseif ($type == 'leaveword') {
	/**
	 * *****************用户评论**********************
	 */
	// $leaveword['infoId']=$_GET['infoId'];//获取显示具体内容的文章ID
	$leaveword ['infoId'] = 1; // 获取显示具体内容的文章ID
	// $leaveword['content']=$_GET['content'];
	$leaveword ['content'] = '精彩！！！！！！';
	// $leaveword['userId']=$_SESSION['user']['id'];
	$leaveword ['userId'] = 1;
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
	// $zan['infoId']=$_GET['infoId'];//获取显示具体内容的文章ID
	$zan ['infoId'] = 2; // 获取显示具体内容的文章ID
	// $zan['userId']=$_SESSION['user']['id'];
	$zan ['userId'] = 1;
	$sql_is_zan = "select id from wx_zan where infoId='{$zan ['infoId'] }' and userId='{$zan ['userId']}'";
	$res_is_zan = $db->getrow ( $sql_is_zan );
	// echo $sql_is_zan;die;
	// echo empty($res_is_zan);die;
	if (! empty ( $res_is_zan )) {
		echo 2; // 你已赞过该文章
	} else {
		$zan ['date'] = date ( 'Y-m-d H:i:s', time () );
		$insert = $db->insert ( 'wx_zan', $zan );
		if ($insert) {
			echo 1; // 评论成功
		} else {
			echo 0; // 评论失败
		}
	}
} elseif ($type == 'delete') {
	/**
	 * *****************后台管理员删除**********************
	 */
	// $infoId=$_GET['infoId'];//获取显示具体内容的文章ID
	$infoId = 2; // 获取显示具体内容的文章ID
	if (empty($infoId)){
		echo de0;//删除失败，请联系技术支持
	}else {
		$sql_pic="select picture from wx_info where Id='{$infoId}'";
		$res_pic=$db->getrow($sql_pic);
		$sql_del = "delete from wx_info where Id='{$infoId}'";
		$res_del = $db->execsql ( $sql_del );
		$res = mysql_affected_rows ();
		if ($res) {
			unlink($res_pic['picture']);
			echo del1; // 删除成功
		} else {
			echo del0; // 删除失败，请联系技术支持
		}
	}	
} elseif ($type = 'update') {
	/**
	 * *****************后台管理员编辑、修改**********************
	 */
	// $infoId=$_GET['infoId'];//获取显示具体内容的文章ID
	$infoId = 1; // 获取显示具体内容的文章ID
// 	$title=$_GET['title'];
	$title='修改1';
// 	$content=$_GET['content'];
	$content='修改内容1';
// 	$is_leaveword=$_GET['is_leaveword'];
	$is_leaveword=1;
// 	$is_zan=$_GET['is_zan'];
	$is_zan=1;
	$picture=$_GET['picture'];
	if (empty($infoId)||empty($title)||empty($content)||empty($is_leaveword)||empty($is_zan)||empty($picture)){
		echo up2;//请检查空值
	}else {
		$sql_update="update wx_info set title='{$title}',content='{$content}',picture='{$picture}',is_leaveword='{$is_leaveword}',is_zan='{$is_zan}' where id='{$infoId}'";
		// 	echo $sql_update;
		$res_update=$db->execsql($sql_update);
		$res=mysql_affected_rows();
		if ($res){
			echo up1;//修改成功
		}else {
			echo up0;//修改失败
		}
	}
	
}