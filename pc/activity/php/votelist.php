<?php
/*
*投票模块
*writer : ly
*/ 
$first="wx_"; 
 
header('Content-type:text/json'); 
require_once '../../../common/php/dbaccess.php';
require_once '../../../common/php/uploadFiles.php';
require_once './vote_project.model.php'; 
session_start ();
$param=$_GET; 
$VP = new vote_project();  
//根据请求进行数据操作
switch( @$param['type']){ 
	case 'add'			:  		add_();	 		break;
	case 'updateOK'		:  		update_();	 	break; 
	case 'update'		:  		getOne();	    break; 
	case 'list'			:       list_(); 	    break; 
	case 'delete'		:	    delete_(); 	    break; 
	default:;  
}
function add_(){
	global $VP; 
	 global $param;  
		$data['title']=$param['title'];
		$data['content']=$param['content'];
		$data['review']=$param['review'];
		$data['valid']=$param['valid'];
		$data['picture']=$param['picture']; 
		$data['start']=$param['start']; 
		$data['end']=$param['end']; 
		echo $VP->add($data);
}

function update_(){
	global $VP; 
	global $param; 
	$id=$param['projectId'];
	if(!empty($id)){
		$condition="id =".$id;
		$data['title']=$param['title'];
		$data['content']=$param['content'];
		$data['review']=$param['review'];
		$data['valid']=$param['valid'];
		$data['picture']=$param['picture']; 
		$data['start']=$param['start']; 
		$data['end']=$param['end']; 
		echo $VP->update($data,$condition);
	}
}
function getone(){
	global $VP; 
	 global $param; 
	 $data=$VP->where(array("id"=>$param['projectId']))->get();
	 echo json_encode($data);
}
function list_(){ 
	 global $VP; 
	 global $param; 
	 $pagesize=10;  
		 if(!empty($param['page'])) {
			$page=$param['page']; 
			$d=$VP->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$VP->num();	
			
			$data['list']=$d;
			$data['PageNum']=ceil($n/$pagesize);
			echo json_encode($data);
		}
}

function showItem(){ 
	 global $VP; 
	 global $param; 
	 $pagesize=10;  
		 if(!empty($param['page'])) {
			$page=$param['page']; 
			$d=$VP->limit($pagesize*($page-1),$pagesize)->gets();
			$n=$VP->num();	
			$d['sum']=ceil($n/$pagesize);
			echo json_encode($d);
		}
}
function delete_(){
	global $VP; 
	global $param; 
	$id=$param['projectId'];
	if(!empty($id)){ 
		echo $VP->delete($data,$condition);
	}
	
}
  
?>