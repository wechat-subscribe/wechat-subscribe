<?php
/*
*投票模块
*writer : ly
*/ 
$first="wx_"; 
 
header('Content-type:text/json'); 
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php';
require_once './vote_option.model.php';
require_once './request.php';
session_start ();
$db = new DB (); // 实例化
$VO = new vote_option(); 

//根据请求进行数据操作
switch( @$_GET['handle']){
	 
	
	case 'newItem': 
		if(!empty($_POST)){
			newItem();
		}
		else{
			echo false;
		}
	
	break;
	 
	case 'deleteItem'://工程项id
		 $param=$_POST;  
		 //deleteInteract(NULL,$param['id']);
		if($VO->delete("id=".$param['id'])){
			echo true;
		}
	
	break;
	 
	case 'showItem':  ;
       showItem();
	 
	break;
	 
	
	 
	default:;
	
	
}

 
//新建投票项 更新投票insert update
 function newItem(){
			global $db;
			global $VO;
			global $first;
       	    //$param=$_POST; 
			 $param['name']= $_POST['name'];
			 $param['picture']= $_POST['picture'];
			 $param['content']= $_POST['content']; 
			 $param['voteId']= $_POST['voteId']; 
			 unset($param["editorValue"]);
			 if(!empty($_POST["id"])){
				  $param['id']= $_POST['id'];
				 $condition="id=".$param["id"];
				 echo $VO->update($param,$condition);
				 
			 }
			 else if(!empty($param["voteId"])){
				  unset($param["id"]);
				$db->insert($first."vote_option",$param);
				echo true;	 
			 }
			 else{
				 echo false;
				 return false;
			 }
		 
 } 
 //展示投票项 get_one get_all
 function showItem(){
	 global $db;
	 global $first;
	 $param=$_POST; 
	   
	  if(!empty($param['id'])){
            $d=$db->getrow("select * from ".$first."vote_option where  id='".$param['id']."';"); 
		    echo json_encode($d);
		    //echo 111111;
		
		}
		else if(!empty($param['voteId'])){
       	
			$d=$db->execsql("select * from ".$first."vote_option where  voteId='".$param['voteId']."' ;"); 
		    echo json_encode($d);
		
		}
		else{
			echo "wrong  no  voteId";//删除
		}
 }
 //删除投票工程  删除投票项   后删除投票记录
//删除没用数据
function deleteInteract($voteId=NULL,$optionId=NULL){
	 global $db;
	 global $first;
	 if(!empty($voteId)){
		$db->delete($first."vote_interact","voteId=".$voteId);
	}
	if(!empty($optionId)){
	$db->delete($first."vote_interact","optionId=".$optionId);
		
	}
}
  
?>