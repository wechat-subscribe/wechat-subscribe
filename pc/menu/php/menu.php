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
		if ($menuFirstType=='articlelist'){
			/*
			 * 文章列表类型
			 */
			$sql_table="update wx_wechat_module set table='wx_article_module;wx_info;wx_leaveword;wx_zan' where id='{$menuId}'";
			$res_table=$db1->execsql($sql_table);
			if (mysql_affected_rows()){
				$ferror=1;
			}else {
				$ferror=0;
			}
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
			$sql_insert_second="insert into wx_article_module (name,type) values ('{$menuName}','{$mediaType}')";
			$res_insert_second=$db1->execsql($sql_insert_second);
			if (mysql_affected_rows()){
				$serror=1;
			}else {
				$serror=0;
			}
			if ($ferror && $serror){
				return true;
			}else {
				return false;
			}
				
		}elseif ($menuFirstType=='activity'){
			/*
			 * 活动类型
			 */
			
			switch ($menusecondType){
				case 'vote':
					$reviewTable='wx_vote_project';
					$table='wx_activity_module;wx_vote_project;wx_vote_option;wx_vote_interact';
					break;
				case 'interact':
					$reviewTable='wx_activity_interact_project';
					$table='wx_activity_module;wx_activity_interact_project;wx_activity_interact;wx_activity_leaveword;wx_activity_zan';
					break;
				default:
					break;
			}
			$sql_insert_second="insert into wx_activity_module (name,reviewTable) values ('{$menuName}','{$reviewTable}')";
			$res_insert_second=$db1->execsql($sql_insert_second);
			if (mysql_affected_rows()){
				$serror=1;
			}else {
				$serror=0;
			}
			$sql_table="update wx_wechat_module set table='{$table}' where id='{$menuId}'";
			$res_table=$db1->execsql($sql_table);
			if (mysql_affected_rows()){
				$ferror=1;
			}else {
				$ferror=0;
			}
			if ($ferror && $serror){
				return true;
			}else {
				return false;
			}
		}
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
$parent=$_GET['parent'];
// $type=$_GET['type'];
//  $type="showmenu";
//  $type="showculture";
//  $type="addmenu";
//  $type="updatemenu";
//  $type="updateculture";
//  $type="deletemenu";
//  $type="deleteculture";
if ($type=="showmenu"){
	$sql_menu="select name,parentId from wx_wechat_module";
	$res_menu=$db->execsql($sql_menu);
	//遍历获取一级模块名称
	foreach ($res_menu as $val_menu){
		if ($val_menu['parentId']==''){
			$resFirst[]=$val_menu['name'];
		}
	}
	//遍历获取二级模块名称
	foreach ($resFirst as $key_First=>$val_First){
		$sql_FirstId="select id from wx_wechat_module where name='{$val_First}'";
		$res_FirstId=$db->getrow($sql_FirstId);
		$sql_second="select name from wx_wechat_module where parentId='{$res_FirstId['id']}'";
		$res_second=$db->execsql($sql_second);
		// 		var_dump($res_second);
		if (!empty($res_second)){
			foreach ($res_second as $key_second=>$val_second){
				$resFirst[$val_First][$key_second]=$val_second['name'];
				unset($resFirst[$key_First]);
			}
		}
	}
// 	var_dump($resFirst);
	echo json_encode($resFirst);
}elseif ($type=="showculture"){
	$parentName=$_GET['parentName'];//父类按钮的名称（与数据库中的一致）
// 	$parentName="企业文化";
	$sql_parentid="select id from wx_articlelist_module where name='{$parentName}'";
	$res_parentid=$db->getrow($sql_parentid);
// 	echo $res_parentid['id'];
	$sql_culturelist="select name from wx_articlelist_module where parentId='{$res_parentid['id']}'";
	$res_culturelist=$db->execsql($sql_culturelist);
// 	var_dump($res_culturelist);
	echo json_encode($res_culturelist);
}/* elseif ($type=="showactivity"){
	$sql_activity="select name from wx_activity_module";
	$res_activity=$db->execsql($sql_activity);
// 	var_dump($res_activity);
	echo json_encode($res_activity);
} */elseif ($type=="addmenu"){
	$menuName=$_GET['menuName'];//所添加菜单的名称
	$menuFirstType=$_GET['menuFirstType'];//选择的菜单一级类型。"articlelist":文章列表；"activity":活动
	$menusecondType=$_GET['menusecondType'];//选择的菜单二级类型。若一级为文章列表，则"picture":图文；"video":视频；"voice":音频
	                                       //若一级为活动，则"vote":投票；"interact":图片互动
	$menuParent=$_GET['parentName'];//所添加菜单的父类菜单名称，若为空，则表示该菜单为一级菜单
	if (empty($menuParent)){
		/*
		 * 添加一级菜单
		 */
		$sql_firstmenu="select id,parentId from wx_wechat_module ";
		$res_firstmenu=$db->execsql($sql_firstmenu);
// 		echo $firstmenuNum=count($res_firstmenu);
		foreach ($res_firstmenu as $val_firstmenu){
			if ($val_firstmenu['parentId']==''){
				$res_firstid[]=$val_firstmenu;
			}
		}
		$firstnum=count($res_firstid);
		if ($firstnum<3){
			$sql_insert_first="insert into wx_wechat_module (name) values ('{$menuName}')";
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
	$sql_delete_table;
}elseif ($type=='deleteculture'){
    	
}
















