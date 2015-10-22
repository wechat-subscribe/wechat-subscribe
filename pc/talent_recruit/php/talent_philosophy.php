<?php
header ( "content-type:text/json;charset=utf-8" );
$path = dirname ( dirname ( dirname ( __FILE__ ) ) );
// echo $path;
require_once  '../../../common/php/dbaccess.php';
require_once  '../../../common/php/uploadFiles.php';
require_once '../../../common/php/regexTool.class.php';
session_start ();
$db = new DB ();
$regex=new regexTool();
$file=array();
$type=$_GET['type'];//获取type的值
//$type="modify_past";
 
if($type=="show"){
		$sql_show="select * from wx_talent_philosophy ";
		$res_show=$db->execsql($sql_show);
		// 	var_dump($res_show);
		if(empty($res_show)){
			$file['empty']=true;
		}else{
			$file['empty']=false;
			$file['show']=$res_show;
		}
		echo json_encode($file);
}elseif ($type == 'updateInfo') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“修改”按钮**********************
	 */
	$infoId=$_GET['infoId'];//获取显示具体内容的文章信息ID
	//$infoId = 5; // 获取显示具体内容的文章信息ID
	if ($regex->isNumber($infoId)){
		$sql_updateInfo="select title,content,picture from wx_info where id='{$infoId}'";
		$res_updateInfo=$db->getrow($sql_updateInfo);
		/* // 	将查询出的media的url根据“；”分开，单独存放
		 $updateInfo_media=explode(';', $res_updateInfo['media']);
		 foreach ($updateInfo_media as $val_updateInfo_media){
		 $updateInfo['media'][]=$val_updateInfo_media;
		} */
		$updateInfo['title']=$res_updateInfo['title'];
		$updateInfo['content']=$res_updateInfo['content'];
		$updateInfo['picture']=$res_updateInfo['picture'];
		//var_dump($updateInfo);
		echo json_encode($res_updateInfo);
	}
	
}elseif ($type == 'updateInfoOK') {
	/**
	 * *****************后台管理员编辑、修改文章信息的具体内容,点击“提交”按钮**********************
	 */
// 	echo '90';
	$subtype=$_GET['subtype'];
// 	$mediatype=$_GET['mediatype'];//0：图片；1：视频；2：缩略图
	if ($subtype=='picture'){
// 		echo '90';die;
		$dest=uploadmulti('thumbpic',2); 
		$_SESSION['picture']=$dest[0];
		echo $dest[0];
	}
	/* elseif($subtype=='media'){
		//获取上传图片的url
		$dest=array();
		$dest=uploadmulti(0);
		// var_dump($dest);
		$media_num=count($dest);
		$dest_db=$dest[0];
		for ($i=1;$i<$num;$i++){
			$dest_db.=';'.$dest[$i];
		}
		$media=$dest_db;
		echo $media;
	} */
	else{
		$infoId=$_POST['infoId'];//获取显示具体内容的文章信息ID
// 		$infoId = 5; // 获取显示具体内容的文章信息ID
		if ($regex->isNumber($infoId)){
			$title=$_POST['title'];
	// 		$title='修改1';
				$content=$_POST['content'];
	// 		$content='修改内容1';
// 				$is_leaveword=$_GET['is_leaveword'];
	// 		$is_leaveword=1;
// 				$is_zan=$_GET['is_zan'];
	// 		$is_zan=1;
// 			echo $infoId.";";
// 			echo $title.";";
// 			echo $content.";";
// 			echo $is_leaveword.";";
// 			echo $is_zan.";";
			if (empty($infoId)||empty($title)||empty($content)/* ||empty($is_leaveword)||empty($is_zan) */){
				echo 2;//请检查空值
			}else {
				if($_SESSION['picture'] != ''){
					$sql_update="update wx_talent_philosophy set title='{$title}',content='{$content}',picture='{$_SESSION['picture']}' where id='{$infoId}'";
				}else{
					$sql_update="update wx_talent_philosophy set title='{$title}',content='{$content}' where id='{$infoId}'";
				}
				//echo $sql_update;die;
				$res_update=$db->execsql($sql_update);
				$res=mysql_affected_rows();
				if ($res){
					$_SESSION['picture'] = '';
					echo 1;//修改成功
				}else {
					echo 0;//修改失败
				}
			}
		}else {
			echo 0;
		}
	}
		
}
	


















