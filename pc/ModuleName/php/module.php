<?php
header("content-type:text/html;charset=utf-8");
require_once '../../../common/php/dbaccess.php';
$db=new DB();
// $parent=$_GET['parent'];
$parent="articleList";
if ($parent=='articleList'){
	$sql_articlelist="select name,parentId from wx_articlelist_module";
	$res_articlelist=$db->execsql($sql_articlelist);
	var_dump($res_articlelist);
	//遍历获取一级模块名称
	foreach ($res_articlelist as $val_articlelist){
		if ($val_articlelist['parentId']==''){
			$resFirst[]=$val_articlelist['name'];
		}
	}
	//遍历获取二级模块名称
	foreach ($resFirst as $key_First=>$val_First){
		$sql_FirstId="select id from wx_articlelist_module where name='{$val_First}'";
		$res_FirstId=$db->getrow($sql_FirstId);
		$sql_second="select name from wx_articlelist_module where parentId='{$res_FirstId['id']}'";
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
	echo json_encode($res_articlelist);
}elseif ($parent=='activity'){
	$sql_activity="select name from wx_activity_module";
	$res_activity=$db->execsql($sql_activity);
// 	var_dump($res_activity);
	echo json_encode($res_activity);
}
