<?php
header ( "content-type:text/html;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once $path . '/common/php/dbaccess.php';
require_once $path . '/common/php/regexTool.class.php';
$db = new DB ();
$regex = new regexTool ();
$data = array ();
	/*****************获取前端传来的信息********************/
	$positionName = $_POST ['positionName'];
	$address = $_POST ['address'];
	$num = $_POST ['num'];
	$date = date ( 'Y-m-d H-i-s', time () ); // 招聘时间
	$ageRequire = $_POST ['ageRequire'];
	$sexRequire = $_POST ['sexRequire'];
	$eduRequire = $_POST ['eduRequire'];
	$other = $_POST ['other'];
	$salary = $_POST ['salary'];
	$content = $_POST ['content'];
	/*
	 * 判断职位，地址，人数，时间 是否为空
	 */
	if (empty ( $positionName ) || empty ( $address ) || empty ( $num ) ) {
		$error = 2; // 职位，地址，人数不能为空；
	} else {
		/*
		 * 构造wx_talent_recruit数据库表的数据结构
		 */
		$data ['positionName'] = $positionName;
		$data ['address'] = $address;
		if ($regex->isNumber ( $ageRequire )) {
			$data ['ageRequire'] = $ageRequire;
		} else {
			$error = 5; // 年龄格式不正确
		}
		
		$data ['sexRequire'] = $sexRequire;
		$data ['eduRequire'] = $eduRequire;
		$data ['other'] = $other;
		if ($regex->isNumber ( $salary )) {
			$data ['salary'] = $salary;
			// echo abd;die;
		} else {
			$error = 6; // 工资格式不正确
		}
		
		$data ['content'] = $content;
		if ($regex->isNumber ( $num )) {
			$data ['num'] = $num;
		} else {
			$error = 7; // 人数格式不正确
		}
		
		$data ['date'] = $date;
		// var_dump($data);
		
		/*
		 * *******存入wx_talent_recruit数据库********
		 */
		
		if (empty ( $data ['salary'] ) || empty ( $data ['ageRequire'] ) || empty ( $data ['num'] )) {
			$error = 2; // 工资，年龄，人数不能为空或者格式不正确；
		} else {
			$insert = $db->insert ( 'wx_talent_recruit', $data );
			if ($insert) {
				$error = 1; // 保存成功
			} else {
				$error = 0; // 保存失败
			}
			
		}
	    $file['error']=$error;
	    
	    echo json_encode($file);
	    
	}	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    