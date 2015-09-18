<?php
/*
 * 实现多个单文件上传
 */
header('content-type:text/html;charset=utf-8');
require_once 'upload.class.php';
/**
 * 构建上传文件信息
 * @return unknown
 */
function getFiles(){
	$i=0;
	foreach($_FILES as $file){
		if(is_string($file['name'])){
			$files[$i]=$file;
			$i++;
		}elseif(is_array($file['name'])){
			foreach($file['name'] as $key=>$val){
				$files[$i]['name']=$file['name'][$key];
				$files[$i]['type']=$file['type'][$key];
				$files[$i]['tmp_name']=$file['tmp_name'][$key];
				$files[$i]['error']=$file['error'][$key];
				$files[$i]['size']=$file['size'][$key];
				$i++;
			}
		}
	}
	return $files;

}
/**
 * 上传单个或多个文件
 * @param int $type
 * $type为0：上传图片
 * $type为1：上传视频
 */
function uploadmulti($type){
// 	print_r($_FILES);die;
	$files=getFiles();
	$path=dirname(dirname(__FILE__));//获取upload_image的上层目录的绝对路径
	if ($type==0){
		$uploadPath=$path.'/upload_image';
	}else {
		$uploadPath=$path.'/upload_video';
	}
	
	foreach($files as $fileInfo){
		$upload=new upload($fileInfo,$uploadPath,false);
		$dest=$upload->uploadFile();
		$uploadFiles[]=$dest;
	}
	$uploadFiles=array_values(array_filter($uploadFiles));
	return $uploadFiles;
}
