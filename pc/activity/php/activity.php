<?php
/*
 * activitylist
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
$A=new activity();
///$n=$AIP->num();
///echo $n['COUNT(*)'];
	
switch( @$_GET['handle']){
	 
	case 'activitycate': 
		if(!empty($_POST)){
			activitycate();
		}
		else{
			echo false;
		}
	break;
	case 'activitylist':  
	   activitylist();
	 
	
	break; 
	 
	case 'deleteactivity'://工程id
	    $param=$_POST;  
		if($AIP->delete("id=".$param['id'])){
			echo true;
		}
	 
	
	break;
	 
	 
	
	default:;
	
	
}


 function activitycate(){
			global $A; 
       	    $param=$_POST;     
			if(!empty($param['id'])){
				$Model=$A->where(array("id"=>$param['id']));
				$d=$Model->get();			
				echo json_encode($d); 
			}
			else{ 
				$d=$A->gets(); 
			    echo json_encode($d);
			
			}
		 
 }
  
 function activitylist(){
	 global $AIP; 
	 $pagesize=10;
	 $param=$_POST; 
	   //var_dump($param);die;
	  if(!empty($param['id'])){
             $d=$AIP->where(array("id"=>$param['id']))->get(); 
		    echo json_encode($d);
		
		}
		else  if(!empty($param['type'])){
            $page=$param['page']; 
			$d=$AIP->where(array("type"=>$param['type']))->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$AIP->where(array("type"=>$param['type']))->num();	
			$d['sum']=ceil($n/$pagesize) ;
			$d['page']=$page;
			echo json_encode($d);
		
		}
		else{
			$page=@$param['page']; 
			$d=$AIP->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$AIP->num();	
			$d['sum']=ceil($n/$pagesize);
			$d['page']=$page;
			echo json_encode($d);
		}
 }
   
  
?>