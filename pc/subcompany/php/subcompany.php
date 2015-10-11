<?php
/*
 * 子公司信息导航模块
 * 根据$type的值确定执行的操作
 * $type='add':后台管理员新增子公司信息
 * $type='del':后台管理员删除子公司信息
 * $type='update':后台管理员更新子公司信息
 * $type='checkPage':分页查询公司信息
 * $type='checkFuzzy':模糊查询公司名称
 * $type='checkone':用户输入通过模糊查询输入完整的子公司名称后，显示该公司具体的信息
 */
header("content-type:text/json;charset=utf-8");
require_once '../../common/php/dbaccess.php';
require_once '../../common/php/regexTool.class.php';//正则表达式匹配的类文件
$db=new DB();
$regex = new regexTool();
// $type=$_GET['type'];
// $type='add';
// $type='del';
// $type='update';
// $type='checkPage';
// $type='checkFuzzy';
   $type='checkOne';
if ($type=='add'){
	/**********************后台管理员新增子公司信息**************************/
	$subcom=array();
	/* $subcom['companyName']=$_GET['companyName'];
	$subcom['address']=$_GET['address'];
	$subcom['phone']=$_GET['phone'];
	$subcom['email']=$_GET['email'];
	$subcom['coordinate']=$_GET['coordinate']; */
	$subcom['companyName']='子公司名称32';
	$subcom['address']='山东省青岛市黄岛区32号';
	$subcom['phone']='13897789090';
	$subcom['email']='324328904@qq.com';
	$subcom['coordinate']='89.5341,87.145646';
	if (empty($subcom['companyName']) ||empty($subcom['address'])||empty($subcom['phone'])||empty($subcom['email'])){
		echo 2;//请检查空值
	}else {
		if (empty($subcom['coordinate'])){
			echo 3;//坐标值获取失败
		}elseif (!$regex->isEmail($subcom['email'])){
			echo 4;//email格式错误
		}elseif (!$regex->isMobile($subcom['phone'])){
			echo 5;//手机号格式错误
		}else{
			//判断同一子公司名称是否重复添加
			$sql_repeat="select id from wx_map where companyName='{$subcom['companyName']}'";
			$res_repeat=$db->getrow($sql_repeat);
			if (!empty($res_repeat)){
				echo 6;//该子公司信息已存在，不能重复添加
			}else {
				$insert=$db->insert('wx_map', $subcom);
				if ($insert){
					echo 1;//添加成功
				}else {
					echo 0;//添加失败，请联系技术支持
				}
			}
		}
	}
}elseif ($type=='del'){
	/**********************后台管理员删除子公司信息**************************/
// 	$id=$_POST['id'];
	$id=5;
	$sql_del="delete from wx_map where id='{$id}'";
	$res_del=$db->execsql($sql_del);
	if (mysql_affected_rows()){
		echo 1;//删除成功
	}else {
		echo 0;//删除失败，请联系技术支持
	}
}elseif ($type=='update'){
	/**********************后台管理员更新子公司信息**************************/
	/* $id=$_POST['id'];
	$companyName=$_GET['companyName'];
	$address=$_GET['address'];
	$phone=$_GET['phone'];
	$email=$_GET['email'];
	$coordinate=$_GET['coordinate']; */
	$id=4;
	$companyName='子公司名称5';
	$address='山东省青岛市黄岛区5号';
	$phone='18897789090';
	$email='824328904@qq.com';
	$coordinate='88.5341,88.145646';
	if (empty($companyName) ||empty($address)||empty($phone)||empty($email)){
		echo 2;//请检查空值
	}else {
		if (empty($coordinate)){
			echo 3;//坐标值获取失败
		}elseif (!$regex->isEmail($email)){
			echo 4;//email格式错误
		}elseif (!$regex->isMobile($phone)){
			echo 5;//手机号格式错误
		}else{
			$sql_update="update wx_map set companyName='{$companyName}',address='{$address}',phone='{$phone}',email='{$email}',coordinate='{$coordinate}' where id='{$id}'";
			$res_update=$db->execsql($sql_update);
// 			echo $sql_update;die;
			if (mysql_affected_rows()){
				echo 1;//更新成功
			}else {
				echo 0;//更新失败，请联系技术支持
			}
		}
	}
}elseif ($type=='checkPage'){
	/**********************分页查询公司信息**************************/
// 	$page=$_GET['page'];
	$page=5;
	$num=10;//每页显示10条
	$start=($page-1)*$num;//本页显示的起始位置	
	$sql_check="select * from wx_map limit ".$start.",".$num;
	$res_check=$db->execsql($sql_check);
// 	echo $sql_check;die;
	$check_data=array();
	$num_res=count($res_check);
	if ($num_res==0){
		$check_data['error']=0;//最后一页或第一页
	}else {
		$check_data['check']=$res_check;
		$check_data['error']=1;//可以向下或向上翻页
	}
// 	var_dump($check_data);
	echo json_encode($res_check);
}elseif ($type=='checkFuzzy'){
	/**********************模糊查询公司名称**************************/
// 	$keyword=$_GET['keyword'];
	$keyword='昌乐';
	$sql_checkFuzzy="select companyName from wx_map where companyName like '%".$keyword."%'";
	$res_checkFuzzy=$db->execsql($sql_checkFuzzy);
// 	echo $sql_checkFuzzy;die;
// 	var_dump($res_checkFuzzy);
	echo json_encode($res_checkFuzzy);
}elseif ($type=='checkOne'){
	/*********用户输入通过模糊查询输入完整的子公司名称后，显示该公司具体的信息*************/
// 	$companyName=$_GET['companyName'];
	$companyName='子公司名称5';
	$sql_company="select * from wx_map";
	$res_company=$db->getrow($sql_company);
	var_dump($res_company);
// 	echo json_encode($res_company);
}
