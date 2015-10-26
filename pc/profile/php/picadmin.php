<?php
header ( 'content-type:text/html;charset=utf-8' );
require '../../../common/php/dbaccess.php';
define('PIC_DIR','../uploadfile/');
define('SHOW_DIR','uploadfile/');
$db = new DB ();
$act=$_REQUEST['act'];
if(isset($_POST['upload'])){
	//echo "upload";
	
	switch($_FILES['file0']['error']){
		
		case UPLOAD_ERR_INI_SIZE:
		  echo "<script>alert('文件大小超出服务器限制');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_FORM_SIZE:
		  echo "<script>alert('文件大小超出浏览器限制');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_PARTIAL:
		  echo "<script>alert('只有部分文件被上传');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_NO_FILE:
		  echo "<script>alert('没有文件被上传，请先选择文件');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_NO_TMP_DIR:
		  echo "<script>alert('找不到临时文件夹');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_CANT_WRITE:
		  echo "<script>alert('文件写入失败');window.location='../picadmin.html'</script>";
		  break;
		case UPLOAD_ERR_OK:
		  $upload_dir = PIC_DIR.$_FILES['file0']['name'];
		  //echo $upload_dir;
		  if(file_exists($upload_dir)){
			 echo "<script>alert('存在同名文件，请修改文件名称');window.location='../picadmin.html'</script>"; 
		  }else{
			  if(move_uploaded_file($_FILES['file0']['tmp_name'],$upload_dir)){
				  //写入数据库
				$sql_count = "select count(*) as rsum from wx_profile";
				$res = $db->getRow ( $sql_count );
				if(!$res){
					$index = 1;
				}else{
					$index = $res['rsum'] + 1;
				}
				$sql_insert = "insert into wx_profile (picture,seq) values('".$_FILES['file0']['name']."',".$index.")";
				$res = $db->execsql($sql_insert);
				if($res){
					echo "<script>alert('文件上传成功');window.location='../picadmin.html'</script>";
				}else{
					echo "<script>alert('文件写入数据库失败');window.location='../picadmin.html'</script>";
				}
				
			  }
			  else
				echo "<script>alert('文件上传失败');window.location='../picadmin.html'</script>"; 
		  }
		  break;
		
	}
	
	
	
}

if($act == "save"){ //保存图片设置
	$images = $_GET['images'];
	$separator = ":";
    $images_array = explode($separator,$images);
    $images_count = count($images_array);  //图片数量
	$sql_del = "delete from wx_profile";
	$res = $db->execsql($sql_del);
	if($res){
		$index = 1;
		for($i=0;$i<$images_count;$i++){
			
			$sql_insert = "insert into wx_profile (picture,seq) values('".$images_array[$i]."',".$index.")";
			$index++;
			$res = $db->execsql($sql_insert);
			if(!$res){
				echo -2;//插入记录失败
				die();
			}
		}
		echo 1; //成功
		
	}else{
		echo -1;//删除操作失败
	}
}

if($act == "del"){ //删除图片
	$image = $_GET['image'];
	$image_path = PIC_DIR.$image;
	$sql_del = "delete from wx_profile where picture = '".$image."'";
	$res = $db->execsql($sql_del);
	if($res){
		//删除文件
		if(file_exists($image_path)){
			if(unlink($image_path))
				echo 1; //成功
			else
				echo -2;//文件删除失败
		}else
			echo -3; //文件不存在
	}else{
		echo -1;//删除数据库记录失败
	}
	
	
}

if($act == "get"){ //获取图片，管理
	$sql_query = "select * from wx_profile order by seq asc";
	$show_html = "<ul>";
	$rows=$db->execsql($sql_query);
	foreach ($rows as $v){
		$pic = $v['picture'];
		$pic_path = SHOW_DIR.$pic;
		$str = "<li><div class='row'>
                <div id='content' class='col-md-6 col-md-offset-3'>
		
			    <header>
				   <a class='btn btn-danger'>删除</a>
				
			    </header>
			    <figure class='post-image'> 
				  <img src='".$pic_path."' width='100%' /> 
			    </figure>
                </div>
                </div>
                </li>";
        $show_html .= $str;
	}
	$show_html .= "</ul>";
	echo $show_html;
	
	
}

if($act == "getpics"){ //获取图片，显示
	$sql_query = "select * from wx_profile order by seq asc";
	$show_html = "";
	$rows=$db->execsql($sql_query);
	$index = 1;
	foreach ($rows as $v){
		$pic = $v['picture'];
		$pic_path = SHOW_DIR.$pic;
		
		
		$str = "<li id='focusIndex".$index."' >
			<div class='focus'><img src='".$pic_path."' width='100%' height='100%' /></div>
			
		</li>";
        $show_html .= $str;
		$index++;
	}
	//$show_html .= "</ul>";
	echo $show_html;
	
	
}






?>