<?php
/*
 * activitylist
 */
$first="wx_"; 
header ( 'content-type:text/json;charset=utf-8' );
require_once '../../../common/php/dbaccess.php'; 
require_once './activity_interact.model.php';
require_once './activity_interact_project.model.php';
require_once './request.php';//删除测试日志文件
require_once '../../../common/php/uploadFiles.php';
session_start (); 
$AIP = new activity_interact_project();
$AI = new activity_interact(); 
///echo $n['COUNT(*)'];
	//echo $n=$AI->where(array("type"=>24))->condition("group by userId")->num(); 
switch( @$_GET['handle']){
	 
	case 'activitylist':  
	   activitylist();
	 
	
	break; 
	case 'interactlist':  
	
	 
	    interactlist();
	 
	
	break; 
	 case 'deleteinteract':  
	
	 
	    deleteinteract();
	 
	
	break; 
	 
	case 'deleteactivity'://工程id
	    $param=$_POST;  
		if($AI->delete("id=".$param['id'])){
			echo true;
		}
		else{
			echo false;
		}
	 
	
	break;
	 
	 
	
	default:;
	
	
}


 
  
 function activitylist(){
	 global $AIP; 
	 $pagesize=10;
	 $param=$_POST; 
	   
	  if(!empty($param['id'])){
             $d=$AIP->where(array("id"=>$param['id']))->get(); 
		    echo json_encode($d);
		
		}
		 else{
			 echo false;
		 }
 }
   
 function interactlist(){
	 global $AI;  
	 $param=$_POST; 
	 $begin=$param['num'];
	 $size=$param['size'];
	 $type=$param['projectId'];
	  if(!empty($type)){
		   $n=$AI->where(array("projectId"=>$type))->condition("group by userId")->num(); 
		   $d=$AI->limit($begin,$size)->where(array("projectId"=>$type))->condition("group by userId order by date desc ")->gets(); 
		   $d['sum']=$n;
		    echo json_encode($d);
             //$d=$AI->limit($begin,$size)->gets(); 
		    //echo json_encode($d);
			
		
		}
		 else{
			 echo "wrong";
		 }
 } 
 function deleteinteract(){
	 global $AI;  
	 $param=$_POST;  
	 $id=$param['id'];
	  if(!empty($id)){
		  echo   $AI->delete("id=".$id);  
		}
		 else{
			 echo "wrong";
		 }
 }
   
  
?>