<?php
/*
 * 实现信息上传
 */
 $first="wx_"; 
header ( 'content-type:text/json;charset=utf-8' );
require_once '../../../common/php/dbaccess.php';  
require_once '../../../common/php/uploadFiles.php';
session_start ();  

/**
		 * ***缩略图上传功能***
		 */
if (@$_GET['subtype']=='thumb'){ 
		$dest=uploadmulti('thumbpic',2); 
		echo $dest[0];die;
}
	

 
 
 
  
?>