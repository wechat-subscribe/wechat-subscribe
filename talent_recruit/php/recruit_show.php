<?php
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
$db=new DB();
// $type=$_GET['type'];
$sql_recruit="select id,positionName,address,ageRequire,sexRequire,eduRequire,other,salary,content,
		num,date from wx_talent_recruit order by date desc";
$res_recruit=$db->execsql($sql_recruit);

$data=array();
$i=0;
foreach($res_recruit as $key_recruit =>$val_recruit){
	$time_now=strtotime("-10 day");
	$time_date=strtotime($val_recruit['date']);
	/*********判断是否超过时限*****************/
	if($time_date<=$time_now){
// 		$error=4 ;//超过时限
		$sql="select id from wx_talent_recruit where date = ".$val_recruit['date'];
		$res_sql=$db->getrow($sql);
		$sql_del="delete from wx_talent_recruit where id=".$res_sql;//删除超时的招聘信息
		$res_del=$db->execsql($sql_del);
	}else{
		$data[$i]['id']=$val_recruit['id'];
		$data[$i]['positionName']=$val_recruit['positionName'];
		$data[$i]['address']=$val_recruit['address'];
		$data[$i]['ageRequire']=$val_recruit['ageRequire'];
		$data[$i]['sexRequire']=$val_recruit['sexRequire'];
		$data[$i]['eduRequire']=$val_recruit['eduRequire'];
		$data[$i]['other']=$val_recruit['other'];
		$data[$i]['salary']=$val_recruit['salary'];
		$data[$i]['content']=$val_recruit['content'];
		$data[$i]['num']=$val_recruit['num'];
		$data[$i]['date']=$val_recruit['date'];
		$i++;
	}
}

echo json_encode($data);












