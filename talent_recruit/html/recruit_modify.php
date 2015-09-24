 <?php
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
require_once $path.'/common/php/regexTool.class.php';
$db=new DB();
$id = $_GET['id'];
// $id=1;
$sql = "select * from wx_talent_recruit where id=".$id;
$data=$db->getrow($sql);
// 	echo $id;
// 	var_dump($data); 
?>
<!DOCTYPE html >
<html >
<head>
<meta content="text/html; charset=utf-8" />
<title>Insert title here</title>

</head>

<body>
<form action="../php/recruit_modify_handle.php" method="post" >
<input type="hidden" name="id" value="<?php echo $id?>" />

<p>职位</p>
<input type="text" name="positionName" id="postionName" value="<?php echo $data['positionName']?>" />
<p>地址</p>
<input type="text" name="address" id="address" value="<?php echo $data['address']?>"/>
<p>年龄</p>
<input type="text" name="ageRequire" id="ageRequire" value="<?php echo $data['ageRequire']?>"/>
<p>性别</p>
<input type="text" name="sexRequire" id="sexRequire" value="<?php echo $data['sexRequire']?>"/>
<p>教育</p>
<input type="text" name="eduRequire" id="eduRequire" value="<?php echo $data['eduRequire']?>"/>
<p>薪水</p>
<input type="text" name="salary" id="salary" value="<?php echo $data['salary']?>"/>
<p>其他</p>
<input type="text" name="other" id="other" value="<?php echo $data['other']?>"/>
<p>人数</p>
<input type="text" name="num" id="num" value="<?php echo $data['num']?>"/>
<p>时间</p>
<input type="text" name="date" id="date" value="<?php echo $data['date']?>"/>
<p>内容</p>
<textarea name="content" cols="60" rows="15" id="content"><?php echo $data['content']?></textarea>
<input type="submit" value="提交" />
</form>
</body>

</html>
