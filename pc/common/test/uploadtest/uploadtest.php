<?php
/*
 * 实现信息上传
 */
header('content-type:text/html;charset=utf-8');
$path=dirname(dirname(dirname(__FILE__)));//获取总工程的绝对路径
require_once $path.'/php/uploadFiles.php';
// print_r(uploadmulti(1)) ;
// echo dirname(dirname(__FILE__));
/*****获取上传图片的url****/
$dest=array();
$dest=uploadmulti(0);
// var_dump($dest);
$num=count($dest);
$dest_db=$dest[0];
for ($i=1;$i<$num;$i++){
	$dest_db.=';'.$dest[$i];
}
echo $dest_db;

/*****构造*数据库表的数据结构****/
// $data['picture']=$dest_db;
