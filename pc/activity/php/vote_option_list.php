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
session_start ();
$param=$_GET; 
$VO = new vote_option(); 

//根据请求进行数据操作
switch( @$param['type']){ 
	case 'add'			:  		add_();	 		break;
	case 'updateOK'		:  		update_();	 	break; 
	case 'update'		:  		getOne();	    break; 
	case 'getFather'		:  	getFather();	    break; 
	case 'list'			:       list_(); 	    break; 
	default:;  
}
function add_(){
	 global $VO; 
	 global $param;  
		$data['title']=$param['title'];
		$data['content']=$param['content']; 
		$data['picture']=$param['picture']; 
		$data['voteId']=$param['voteId']; 
		 
		 
		echo $VO->add($data);
}

function update_(){
	global $VO; 
	global $param; 
	$id=$param['projectId'];
	if(!empty($id)){
		$condition="id =".$id;
		$data['title']=$param['title'];
		 
		$data['picture']=$param['picture']; 
		 $data['voteId']=$param['voteId']; 
		echo $VO->update($data,$condition);
	}
}

function getFather(){
	$db=new DB();
	global $param; 
	global $first; 
	$id=$param['projectId'];
	if(!empty($id)){ 
	    $data=$db->getrow("select * from ".$first."vote_project where id=".$id.""); 
		 echo json_encode($data);
	}
}
function getone(){
	global $VO; 
	 global $param; 
	 $data=$VO->where(array("id"=>$param['projectId']))->get();
	 echo json_encode($data);
}
function list_(){ 
	 global $VO; 
	 global $param; 
	 $pagesize=10;  
		 if(!empty($param['page'])) {
			$page=$param['page']; 
			$d=$VO->where(array("voteId"=>$param['voteId']))->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$VO->num();	
			
			$data['list']=$d;
			$data['PageNum']=ceil($n/$pagesize);
			echo json_encode($data);
		}
}

function showItem(){ 
	 global $VO; 
	 global $param; 
	 $pagesize=10;  
		 if(!empty($param['page'])) {
			$page=$param['page']; 
			$d=$VO->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$VO->num();	
			$d['sum']=ceil($n/$pagesize);
			echo json_encode($d);
		}
}
  
?>