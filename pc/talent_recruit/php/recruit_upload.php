<?php
/*
 * 招聘信息上传
 */
header ( "content-type:text/json;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once  '../../../common/php/dbaccess.php';
require_once  '../../../common/php/regexTool.class.php';
$db = new DB ();
$regex = new regexTool ();
 $type=$_POST['type'];
//  $type="details";
$file=array();
/*
 * ***********招聘信息标题列表显示*********************
 */
if($type=="list"){
	
	$page=$_POST['page'];    //获取页码
// 	$page=1;
	$num=10;                //每页的容量
	$start=($page-1)*$num;
	$sql = "SELECT * FROM wx_talent_recruit";
	$result = mysql_query($sql);
	// $sql_list="select * from wx_talent_recruit order by date desc limit ".$start.",".$num;
	$sql_list="select title,date,id from wx_talent_recruit order by date desc limit ".$start.",".$num;
	// echo $sql_list;die;
	$res_list=$db->execsql($sql_list);
	$file['list']=$res_list;
	$file['num']=mysql_num_rows($result);
// 	var_dump($res_list);die;

	echo json_encode($file);
	/*
	 * **********招聘信息的详细展示******************
	 */
}elseif($type=="details"){
// 	echo "234";
	$details_id=$_POST['id'];//获取要查看的招聘信息ID
// 	$details_id=24;
	$sql_details="select * from wx_talent_recruit where id=".$details_id;
	$res_details=$db->getrow($sql_details);
	$file['details']=$res_details;

	 $sql_other="select other from wx_talent_recruit where id =".$details_id;
	 $res_other=$db->getrow($sql_other);
// 	 echo $sql_other;
	 $res=explode('|',$res_other['other']);
	 
	 $file['details']['other']=$res;

	 $sql_content="select content from wx_talent_recruit where id =".$details_id;
	 $res_content=$db->getrow($sql_content);
// 	 echo $sql_other;
	 $res=explode('|',$res_content['content']);
	 
	 $file['details']['content']=$res;
	 	
	 
	
	 echo json_encode($file);
	
	
	/*
	 * ************添加招聘信息*********************
	 */
}elseif($type == 'add') {
	$data = array ();
	/*****************获取前端传来的信息********************/
	$positionName = $_POST ['positionName'];	 
	$address = $_POST ['address'];
	$num = $_POST ['num'];
	$date = date ( 'Y-m-d H-i-s', time () ); // 招聘时间
// 	$ageRequire = $_POST ['ageRequire'];
	$sexRequire = $_POST ['sexRequire'];
// 	$eduRequire = $_POST ['eduRequire'];
	$other = $_POST ['other'];
	$salary = $_POST ['salary'];
	$content = $_POST ['content'];
	$title = $_POST ['positionName'];
	/*
	 * 判断职位，地址，人数，时间 是否为空
	 */
	if (empty ( $positionName ) || empty ( $address ) || empty ( $num ) ) {
		$error = 2; // 职位，地址，人数不能为空；
	} else {
		/*
		 * 构造wx_talent_recruit数据库表的数据结构
		 */
		$data ['title'] = $title;
		$data ['positionName'] = $positionName;
		$data ['address'] = $address;
// 		if ($regex->isNumber ( $ageRequire )) {
// 			$data ['ageRequire'] = $ageRequire;
// 		} else {
// 			$error = 4; // 年龄格式不正确
// 		}
		
		$data ['sexRequire'] = $sexRequire;
// 		$data ['eduRequire'] = $eduRequire;
		$data ['other'] = $other;
		
		$data ['salary'] = $salary;
		
		$data ['content'] = $content;
		if ($regex->isNumber ( $num )) {
			$data ['num'] = $num;
		} else {
			$error = 6; // 人数格式不正确
		}
		
		$data ['date'] = $date;
		// var_dump($data);
		
		/*
		 * *******存入wx_talent_recruit数据库********
		 */
		
		if (empty ( $data ['num'] )) {
			$error = 2; // 人数不能为空或者格式不正确；
		} else {
			$insert = $db->insert ( 'wx_talent_recruit', $data );
			if ($insert) {
				$error = 1; // 保存成功
			} else {
				$error = 0; // 保存失败
			}
			
		}
	    $file['error']=$error;
	    echo json_encode($file['error']);
	}
	
	
/*
 * *************删除选定的招聘信息*******************
 */

} elseif ($type == 'delete') {
	$del_id = $_POST ['id'];//获取要删除的ID
// 	$del_id=26;
	if (empty ( $del_id )) {
		$error = 0; // 删除失败
	} else {
		$sql_del = "delete from wx_talent_recruit where id =" . $del_id;
		$res_del = $db->execsql ( $sql_del );
		$res = mysql_affected_rows ();
		if ($res) {
			$error = 1; // 删除成功
		} else {
			$error = 0; // 删除失败
		}		
	}
	$file['error']=$error;
	echo json_encode($file);
/*
 * ******************更新选定的招聘信息************************
 * ****************获取原来的值*********************
 */

} elseif($type=="modify_past"){
	$pastModify_id = $_POST['id']; // 获取原来的招聘信息ID
// 	$pastModify_id=25;
	$sql_past = "select * from wx_talent_recruit where id=".$pastModify_id;
	$res_past=$db->getrow($sql_past);
	$file['modify_past']=$res_past;
	$sql_other="select other from wx_talent_recruit where id =".$details_id;
	$res_other=$db->getrow($sql_other);
	$res=explode('|',$res_other);
	
	$file['modify_past']['other']=$res;
	
	echo json_encode($file);
	
	/*
	 * ******************更新选定的招聘信息************************
	 * ****************获取修改的值*********************
	 */
}elseif ($type == 'modify') {
	$modify_id = $_POST ['id']; // 获取修改的招聘信息ID
	
	/***************获取从前端传来的数据**********************/
	$positionName = $_POST ['positionName'];
	$address = $_POST ['address'];
	if ($regex->isNumber ( $_POST['num'] )) {
		$num = $_POST['num'];
	} else {
		$error = 4; // 人数格式不正确
	}
	
	$date = date ( 'Y-m-d H-i-s', time () ); // 招聘时间
// 	if ($regex->isNumber ( $_POST['ageRequire'] )) {
// 		$ageRequire = $_POST['ageRequire'];
// 	} else {
// 		$error = 5; // 年龄格式不正确
// 	}
	
	$sexRequire = $_POST ['sexRequire'];
// 	$eduRequire = $_POST ['eduRequire'];
	$other = $_POST ['other'];
	
	$salary = $_POST['salary'];
		
	
	
	$content = $_POST ['content'];

   /************判断内容是否为空******************/
	if (empty ( $positionName ) || empty ( $address) || empty ( $num ) 
			 || empty ( $salary) ) {
		$error = 3; // 职位，地址，人数，工资不能为空或者格式不正确；
	} else {
		/**********更新数据表*****************/
		$sql_update = "update wx_talent_recruit set positionName='{$positionName}',address='{$address}',
					sexRequire='{$sexRequire}',other='{$other}',salary='{$salary}',content='{$content}',num='{$num}',
					date='{$date}' where id='{$modify_id}'";
		$res_update = $db->execsql ( $sql_update );
		$res = mysql_affected_rows ();
		if ($res) {
			 $error = 1; // 修改成功
		} else {
			 $error = 0; // 修改失败
		}
	}
	$file['error']=$error;
	echo json_encode($file);
}













