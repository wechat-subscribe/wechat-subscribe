<?php
/*
 * 实现信息上传
 */
header ( 'content-type:text/html;charset=utf-8' );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) ); // 获取总工程的绝对路径
                                                      // echo $path;
require_once $path . '/common/php/uploadFiles.php';
require_once $path . '/common/php/dbaccess.php';
session_start ();
$db = new DB (); // 实例化

/**
 * **判断标题内容是否为空****
 */
$title= $_POST ['title'];
$content= $_POST ['content'];
if (  empty( $title ) || empty ( $content)) {
// 	echo "<script>alert('标题和内容不能为空');window.location.href='upload.html';</script>";
	$error = 2; // 标题和内容不能为空
} else {
	
	/**
	 * ***获取上传图片的url***
	 */
	$dest = array ();
	$dest = uploadmulti ( 0 );
	// var_dump($dest);
	$num = count ( $dest );
	$dest_db = $dest [0];
	for($i = 1; $i < $num; $i ++) {
		$dest_db .= ';' . $dest [$i];
	}
	// echo $dest_db;
	
	/**
	 * ***判断是否评论*****
	 */
	// $leaveword=$_POST ['leaveword'];
	$leaveword = 1;
	if ($leaveword) {
		$is_leaveWord = 1;
	} else {
		$is_leaveWord = 0;
	}
	
	/**
	 * ***判断是够点赞******
	 */
	// $zan=$_POST ['zan'];
	$zan = 1;
	if ($zan) {
		$is_zan = 1;
	} else {
		$is_zan = 0;
	}
	/**
	 * ***构造wx_info数据库表的数据结构***
	 */
	// $data ['userId'] = $_SESSION ['user'] ['id']; // 用户ID
	$data ['userId'] = 1; // 用户ID
	$data ['picture'] = $dest_db; // 图片地址
	// $data ['moduleId'] = $_POST ['moduleId']; // 模块信息
	$data ['moduleId'] = 1; // 模块信息
	$data ['date'] = date ( 'Y-m-d H-i-s', time () ); // 日期
	$data ['title'] = $title; // 标题
	$data ['content'] = $content; // 内容
	$data ['is_leaveWord'] = $is_leaveWord; // 是否评论
	$data ['is_zan'] = $is_zan; // 是否赞
	
	/**
	 * *****存入wx_info数据库***********
	 */
	$insert = $db->insert ( 'wx_info', $data ); // 插入语句
	
	if ( $insert ) {
		$error = 0; // 保存成功
	} else {
		$error = 1; // 保存失败
	}
}
echo $error;
echo '<br/>';
var_dump($dest);









