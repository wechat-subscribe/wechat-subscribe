<?php
/*
 * 招聘信息上传
 */
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
require_once $path.'/common/php/regexTool.class.php';
$db=new DB();
$regex=new regexTool();
$data=array();
/*
 * 判断职位，地址，人数，时间 是否为空。
 */
$positionName=$_POST['positionName'];
$address=$_POST['address'];
$num=$_POST['num'];
$date=date('Y-m-d H-i-s',time());//招聘时间
$ageRequire=$_POST['ageRequire'];
$sexRequire=$_POST['sexRequire'];
$eduRequire=$_POST['eduRequire'];
$other=$_POST['other'];
$salary=$_POST['salary'];
$content=$_POST['content'];
// $type=$_POST['type'];
// $type="edit";
if($type=='add'){
if (empty( $positionName ) || empty ( $address)|| empty($num)|| empty($date)) {
	$error=2;//职位，地址，人数，发布时间不能为空；
}else{
	/*
	 *构造wx_talent_recruit数据库表的数据结构
	 */
	$data['positionName']=$positionName;
	$data['address']=$address;
	if($regex->isNumber($ageRequire)){
		$data['ageRequire']=$ageRequire;
	}else {
		$error=5;//年龄格式不正确
	}
	echo $error;
	$data['sexRequire']=$sexRequire;
	$data['eduRequire']=$eduRequire;
	$data['other']=$other;
	if($regex->isNumber($salary)){
		$data['salary']=$salary;
// 		echo abd;die;
	}else {
		$error=6;//工资格式不正确
	}
	echo $error;
	$data['content']=$content;
	if($regex->isNumber($num)){
		$data['num']=$num;
	}else {
		$error=7;//人数格式不正确
	}
	echo $error;
	$data['date']=$date;
// 	var_dump($data);
	/*
	 * *******存入wx_talent_recruit数据库********
	 */
	if (empty( $data['salary'] ) || empty ( $data['ageRequire'])|| empty($data['num'])) {
	$error=2;//职位，地址，人数，发布时间不能为空或者格式不正确；
}else{
	$insert =$db->insert('wx_talent_recruit',$data);
	if($insert){
		$error = 1;//保存成功
	}else {
		$error =0;//保存失败
	}
	echo $error;
	}
	
}

echo $error;
/***************删除选定的招聘信息********************/
}elseif($type=='delete'){
	$del_id=$_POST['id'];
	if(empty($del_id)){
		$error=3;//删除失败
	}else{
		$sql_del="delete from wx_talent_recruit where id =".$del_id;
		$res_del=$db->execsql($sql_del);
		$res=mysql_affected_rows();
		if($res){
			$error=1;//删除成功
		}else {
			$error=0;//删除失败
		}
		echo $error;
	}
// 	/*******************更新选定的招聘信息*************************/

}elseif($type=='update'){
	$update_id=$_POST['id'];//获取要修改的招聘信息ID
	$positionName=$_POST['positionName'];
	$address=$_POST['address'];
	if($regex->isNumber($num)){
		$data['num']=$num;
	}else {
		$error=7;//人数格式不正确
	}
	echo $error;
	$date=date('Y-m-d H-i-s',time());//招聘时间
	if($regex->isNumber($ageRequire)){
		$data['ageRequire']=$ageRequire;
	}else {
		$error=5;//年龄格式不正确
	}
	echo $error;
	$sexRequire=$_POST['sexRequire'];
	$eduRequire=$_POST['eduRequire'];
	$other=$_POST['other'];
	if($regex->isNumber($salary)){
		$data['salary']=$salary;
		echo abd;die;
	}else {
		$error=6;//工资格式不正确
	}
	echo $error;
	$content=$_POST['content'];
	$type=$_POST['type'];
	if (empty( $data['positionName'] ) || empty ( $data['address'])|| empty($data['num'])|| empty($data['date'])) {
		$error=2;//职位，地址，人数，发布时间不能为空；
	}else{
		$sql_update="update wx_talent_recruit set positionName='{$postionName}',address='{$address}',
					ageRequire='{ageRequire}',sexRequire='{sexRequire}',eduRequire='{eduRequire}',
					other='{$other}',salary='{$salary}',content='{$content}',num='{$num}',
					date='{$date}' where id='{$update_id}'";
		$res_update=$db->execsql($sql_update);
		$res=mysql_affected_rows();
		if($res){
			$error=1;//修改成功
		}else{
			$error=0;//修改失败
		}
		echo $error;
	}
}















