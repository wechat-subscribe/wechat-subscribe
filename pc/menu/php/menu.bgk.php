<?php
/**
 * $type="showmenu":用于显示微信端的菜单
 * $type="showculture":用于显示企业文化的类别菜单
 * $type="addmenu":用于增加微信端的菜单
 * $type="updatemenu":用于更新微信端的菜单名称
 * $type="updateculture":用于更新企业文化的类别菜单名称
 * $type="deletemenu":用于删除微信端的菜单
 * $type="deleteculture":用于删除企业文化的类别菜单
 */
header("content-type:text/html;charset=utf-8");
require_once '../../../common/php/dbaccess.php';
$db=new DB();
/*
 * 根据选择的菜单类型将数据添加到数据库
 * @$menuName:添加的菜单名称
 * @$menuFirstType：选择的菜单一级类型。"articlelist":文章列表；"activity":活动
 * @$menusecondType：选择的菜单二级类型。若一级为文章列表，则"picture":图文；"video":视频；"voice":音频
 *                                若一级为活动，则"vote":投票；"interact":图片互动
 * @$menuId:添加菜单的ID
 */
function addmenu($menuName,$menuFirstType,$menusecondType,$menuId){
	$db1=new DB();
	if((!empty($menuFirstType))&&(!empty($menusecondType))){
		/*
		 * 创建菜单后增加功能
		 */
		if ($menuFirstType=='articlelist'){
			/*
			 * 文章列表类型
			 */
			
			switch ($menusecondType){
				case 'picture':
					$mediaType=0;
					break;
				case 'video':
					$mediaType=1;
					break;
				case 'voice':
					$mediaType=2;
					break;
				default:
					break;
			}
			$sql_insert_second="insert into wx_article_module (name,type,menuId,parentId) values ('{$menuName}','{$mediaType}','{$menuId}',0)";
			$res_insert_second=$db1->execsql($sql_insert_second);
			if (mysql_affected_rows()){
				return true;//操作成功
			}else {
				return false;//操作失败
			}				
		}elseif ($menuFirstType=='activity'){
			/*
			 * 活动类型:互动
			 */
			switch ($menusecondType){
				case 'vote':
					$reviewTable='wx_vote_project';
					break;
				case 'interact':
					$reviewTable='wx_activity_interact_project';
					break;
				case 'review':
					$reviewTable='wx_activity_interact_project';
					break;
				default:
					break;
			}
		    $sql_insert_second="insert into wx_activity_module (name,reviewTable,menuId) values ('{$menuName}','{$reviewTable}','{$menuId}')";
			$res_insert_second=$db1->execsql($sql_insert_second);
			if (mysql_affected_rows()){
				return true;//操作成功
			}else {
				return false;//操作失败
			}
		}
	}else {
		/*
		 * 创建菜单后，不增加功能
		 */
		return true;
	}
}
/*
 * 删除带数据库表的菜单
 */
function delmenu(){
	
}

/*
 * 菜单编辑
 */
// $parent=$_GET['parent'];
 $type=$_GET['type'];
//  $type="showmenu";
//  $type="showculture";
//  $type="addmenu";
//$type="updatemenu";
//  $type="updateculture";
//  $type="deletemenu";
//  $type="deleteculture";
if ($type=="showmenu"){
	/*
	 * 将菜单按级别输出名字和ID
	 */
	//获取一级模块名称
	$sql_menu="select id,name from wx_wechat_module where parentId=0";
	$res_menu=$db->execsql($sql_menu);
	//遍历获取二级模块名称
	foreach ($res_menu as $key_First=>$val_First){
		//自身连接查询
		$sql_second="select s.id,s.name from wx_wechat_module as s left join wx_wechat_module as p
				on s.parentId=p.id where p.id='{$val_First['id']}'";
		$res_second=$db->execsql($sql_second);
		$res_menu[$key_First]['sub']=$res_second;
	}
// 	print_r($res_menu);
	echo json_encode($res_menu);
}elseif ($type=="showculture"){
	$parentId=$_GET['parentId'];//父类按钮的菜单ID
	$sql_culturelist="select s.id,s.name from wx_articlelist_module as s left join wx_articlelist_module as p 
			on s.parentId=p.id where p.id='{$parentId}'";
	$res_culturelist=$db->execsql($sql_culturelist);
// 	var_dump($res_culturelist);
	echo json_encode($res_culturelist);
}elseif ($type=="addmenu"){
	$menuName=$_GET['menuName'];//所添加菜单的名称
	$menuFirstType=$_GET['menuFirstType'];//选择的菜单一级类型。"articlelist":文章列表；"activity":活动
	$menusecondType=$_GET['menusecondType'];//选择的菜单二级类型。若一级为文章列表，则"picture":图文；"video":视频；"voice":音频
	                                       //若一级为活动，则"interact":图片互动;"vote":投票;"review";"往期回顾"
	$menuParentId=$_GET['parentId'];//所添加菜单的父类菜单名称，若为0，则表示该菜单为一级菜单
	$menuParentId=0;
	if ($menuParent==0){
		/*
		 * 添加一级菜单
		 */
		//判断目前一级菜单的个数
		$sql_firstId="select id from wx_wechat_module where parentId=0";
		$res_firstId=$db->execsql($sql_firstId);
		$firstMenuNum=count($res_firstId);
		//若一级菜单的个数不足3个，则添加菜单；若已存在3个，则报错
		if ($firstMenuNum<3){
			$sql_insert_first="insert into wx_wechat_module (name,parentId) values ('{$menuName}',0)";
			$res_insert_first=$db->execsql($sql_insert_first);
			if (mysql_affected_rows()){
				$menuId=mysql_insert_id();//添加的菜单的moduleId
				$firstError=true;//添加成功
			}else{
				$firstError=false;//添加失败
			}
			$secondeError=true;
			$secondeError=addmenu($menuName, $menuFirstType, $menusecondType,$menuId);
			if ($firstError && $secondeError){
				$add['id']=$menuId;//添加的菜单的moduleId
				$add['error']=1;//添加成功
			}else {
				$add['error']=0;//添加失败
			}
		}else {
			$add['error']=2;//一级菜单最多只能有三个
		}
	}else {
		/*
		 * 添加二级菜单
		 */
		$sql_menuParentId="select id from wx_wechat_module where name='{$menuParent}'";
		$res_menuParentId=$db->getrow($sql_menuParentId);
		$sql_secondMenu="select id,parentId from wx_wechat_module where parentId='{$res_menuParentId['id']}'";
		$res_secondMenu=$db->execsql($sql_secondMenu);
		$secondMenuNum=count($res_secondMenu);
		if ($secondMenuNum<5){
			$sql_insert_first="insert into wx_wechat_module (name,parentId) values ('{$menuName}','{$res_menuParentId['id']}')";
			$res_insert_first=$db->execsql($sql_insert_first);
			$menuId=mysql_insert_id();//添加的菜单的moduleId
			if (mysql_affected_rows()){
				$firstError=true;//添加成功
			}else{
				$firstError=false;//添加失败
			}
			$secondeError=addmenu($menuName, $menuFirstType, $menusecondType,$menuId);
			if ($firstError && $secondeError){
				$add['id']=$menuId;//添加的菜单的moduleId
				$add['error']=1;//添加成功
			}else {
				$add['error']=0;//添加失败
			}
		}else {
			$add['error']=2;//二级菜单最多只能有5个
		}
	}
	echo json_encode($add);
}elseif ($type=="updatemenu"){
	$menuId=$_GET['menuId'];//要修改的菜单moduleId
	$menuNewName=$_GET['menuNewName'];//修改之后的菜单名称
	$sql_update="update wx_wechat_module set name='{$menuNewName}' where id='{$menuId}'";
	$res_update=$db->execsql($sql_update);
	if (mysql_affected_rows()){
		echo 1;//更新成功
	}else {
		echo 0;//更新失败
	}
}elseif ($type=='updateculture'){
	$moduleId=$_GET['moduleId'];//要修改的moduleId
	$menuNewName=$_GET['menuNewName'];//修改之后的名称
	$sql_update="update wx_article_module set name='{$menuNewName}' where id='{$moduleId}'";
	$res_update=$db->execsql($sql_update);
	if (mysql_affected_rows()){
		echo 1;//更新成功
	}else {
		echo 0;//更新失败
	}
}elseif ($type=='deletemenu'){
	$menuId=$_GET['menuId'];//要删除的菜单的moduleId
	$menuName=$_GET['menuName'];//要删除的菜单的名称
	$menuType=$_GET['menuType'];//菜单的类型。文章列表为articlelist”;活动为"activity",其他为“other”
	//查找该菜单的子菜单是否存在
	$sql_subMenu="select id from wx_wechat_module where parentId='{$menuId}'";
	$res_subMenu=$db->execsql($sql_subMenu);
	if (empty($res_subMenu)){
		/*
		 * 没有子菜单，只删除目前菜单的数据库
		 */
		//查出与该菜单相关的数据库表
		$sql_table="select table from wx_wechat_module where id='{$menuName}'";
		$res_table=$db->getrow($sql_table);
		$table=array();
		$table=explode(';', $res_table);
		if (empty($table)){
			/*
			 * 没有要删除的相关数据库表
			 */
		}else {
			/*
			 * 删除相关的数据库表中的数据
			 */
			if ($menuType=='articlelist'){
					
			}elseif ($menuType=="activity"){
					
			}else{
				foreach ($table as $val_table){
					$sql_drop="drop table '{$val_table}'";
					$res_drop=$db->execsql($sql_drop);
				}	
			}
		}
		
	}
}elseif ($type=='deleteculture'){
    	
}
















