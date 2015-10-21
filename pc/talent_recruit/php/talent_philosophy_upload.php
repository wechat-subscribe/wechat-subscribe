<?php
header ( "content-type:text/json;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once  '../../../common/php/dbaccess.php';
require_once $path . '../../../common/php/uploadFiles.php';
$db = new DB ();
$file=array();
// $type=$_POST['type'];//获取type的值
$type="delete";
if($type=="list"){
// 	$page=$_GET['page'];    //获取页码
	$page=1;
	$num=3;                //每页的容量
	$start=($page-1)*$num;
	$sql_list="select * from wx_talent_philosophy  limit ".$start.",".$num;
	$res_list=$db->execsql($sql_list);
// 	var_dump($res_list);
	$file['list']=$res_list;
	echo json_encode($file);
	/*
	 * 添加员工的图集和内容
	 */
}elseif($type=="add"){
	/**
	 * ***获取上传图片的url***
	 */
	$dest = array ();
	$dest = uploadmulti ( 0 );
	// var_dump($dest);
	$num = count ( $dest );
	$picture = $dest [0];
	for($i = 1; $i < $num; $i ++) {
		$picture .= ';' . $dest [$i];
	}
	
	$content=$_POST['content'];
	
	
	if(empty($picture)||empty($content)){
		$error=2;//内容和图片不能为空；
	}else{
		/**
		 * ***构造wx_talent_philosophy数据库表的数据结构***
		 */
		$data=array();
		$data['picture']=$picture;
		$data['content']=$content;
		/**
		 * *****存入wx_talent_philosophy数据库***********
		 */
	
		$insert = $db->insert ( 'wx_talent_philosophy', $data ); // 插入语句
	
		if ( $insert ) {
			$error = 1; // 保存成功
		} else {
			$error = 0; // 保存失败
		}
	}
	
	$file['error']=$error;
	echo json_encode($file);
	/*
	 * 显示员工的具体内容
	 */
}elseif($type=="details"){
// 	$details_id=$_POST['id'];//获取要查看的招聘信息ID
	$details_id=1;
	$sql_details="select * from wx_talent_philosophy where id=".$details_id;
	$res_details=$db->getrow($sql_details);
	$file['details']=$res_details;
	echo json_encode($file);
	/*
	 * 修改员工的图集和内容
	 * 
	 * 获取将要修改的员工信息
	 */
}elseif($type=="modify_past"){
// 	$pastModify_id = $_POST['id']; // 获取原来的信息ID
	$pastModify_id=1;
	$sql_past = "select * from wx_talent_philosophy where id=".$pastModify_id;
	$res_past=$db->getrow($sql_past);
	$file['modify_past']=$res_past;
	echo json_encode($file);
	/*
	 * 修改员工信息
	 */
}elseif($type=="modify"){
	$modify_id = $_POST ['id']; // 获取修改的招聘信息ID
	/***************获取从前端传来的数据**********************/
	
	/**
	 * ***获取上传图片的url***
	 */
	$dest = array ();
	$dest = uploadmulti ( 0 );
	// var_dump($dest);
	$num = count ( $dest );
	$picture = $dest [0];
	for($i = 1; $i < $num; $i ++) {
		$picture .= ';' . $dest [$i];
	}
	
	$content=$_POST['content'];
	
	
	if(empty($picture)||empty($content)){
		$error=2;//内容和图片不能为空；
	}else{
		
		/**************更新数据表*********************/
		$sql_update = "update wx_talent_philosophy set picture='{$picture}',content='{$content}'
		 where id='{$modify_id}'";
		$res_update = $db->execsql ( $sql_update );
		$res = mysql_affected_rows ();
		if ($res) {
			$error = 1; // 修改成功
		} else {
			$error = 0; // 修改失败
		}
	}
	$file['error']=$error;
	echo json_encode($file);
	/*
	 *删除员工的信息
	 */
}elseif ($type == 'delete') {
// 	$del_id = $_POST ['id'];//获取要删除的ID
	$del_id=3;
	if (empty ( $del_id )) {
		$error = 3; // 删除失败
	} else {
		$sql_del = "delete from wx_talent_philosophy where id =".$del_id;
		$res_del = $db->execsql ( $sql_del );
		$res = mysql_affected_rows ();
		if ($res) {
			$error = 1; // 删除成功
		} else {
			$error = 0; // 删除失败
		}		
	}
	$file['error']=$error;
	echo json_encode($file);
}



