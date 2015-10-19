<?php
/*
 * 实现信息上传
 */
 $first="wx_"; 
header ( 'content-type:text/json;charset=utf-8' );
require_once '../../../common/php/dbaccess.php'; 
require_once './activity.model.php';
require_once './activity_interact_project.model.php';
require_once './request.php';//删除测试日志文件
require_once '../../../common/php/uploadFiles.php';
session_start (); 
$AIP = new activity_interact_project();

/**
		 * ***缩略图上传功能***
		 */
if (@$_GET['subtype']=='thumb'){ 
		$dest=uploadmulti('thumbpic',2); 
		echo $dest[0];die;
}
	

	
switch( @$_GET['handle']){
	 
	case 'newProject': 
		if(!empty($_POST)){
			newProject();
		}
		else{
			echo false;
		}
	break;
	 
	 
	case 'deleteProject'://工程id
	    $param=$_GET;  
		if($Ma->delete("id=".$param['id'])){
			echo true;
		}
	 
	
	break;
	 
	 
	case 'showProject':  
	   showProject();
	 
	
	break; 
	default:;
	
	
}


 function newProject(){
			global $AIP; 
       	    $param=$_POST;    
			unset($param["editorValue"]);
			
			 if(!empty($param["id"])){
				 $condition="id=".$param["id"];
				 $AIP->update($param,$condition);
				echo true;
			 }
			 else{ 
				echo $AIP->add($param);  	 
					 
			 }
		 
 }
  
 function showProject(){
	 global $AIP; 
	 $pagesize=10;
	 $param=$_POST; 
	   
	  if(!empty($param['id'])){
            //$d=$Ma->getrow("select * from ".$first."activity_interact_project where  id='".$param['id']."';"); 
            $d=$Ma->where(array("id"=>$param['id']))->get(); 
		    echo json_encode($d);
		
		}
		else{
			$page=$param['page'];
			//$d=$Ma->execsql("select * from ".$first."activity_interact_project where  1 order by date desc limit ".$page*$pagesize.",".$pagesize.";"); 
			$d=$Ma->limit($page,10)->gets(); 
		echo json_encode($d);
		
		}
 }
   
  
?>