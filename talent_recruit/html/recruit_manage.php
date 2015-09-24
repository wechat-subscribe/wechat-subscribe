<?php
header("content-type:text/html;charset=utf-8");
$path=dirname(dirname(dirname(__FILE__)));
// echo $path;
require_once $path.'/common/php/dbaccess.php';
require_once $path.'/common/php/regexTool.class.php';
$db=new DB();
$sql="select * from wx_talent_recruit order by date desc";
$res=$db->execsql($sql);
$data=array();
if($res){
	foreach($res as $key =>$val){
		$data[]=$val;
// 		var_dump($data);
	}
}else{
	$error=2;//没有招聘信息
}

?>


<!DOCTYPE html >
<html >
<head>
<meta content="text/html; charset=utf-8" />
<title>Insert title here</title>

</head>
<body>
<table width="100%" height="520" border="0" cellpadding="8" cellspacing="1" bgcolor="#000000">
  
  <tr>
    <td width="156" height="287" align="left" valign="top" bgcolor="#FFFF99">
    <p><a href="recruit_upload.html">发布招聘信息</a></p>
    <p><a href="recruit_manage.php">管理招聘信息</a></p></td>
    <td width="837" valign="top" bgcolor="#FFFFFF"><table width="743" border="0" cellpadding="8" cellspacing="1" bgcolor="#000000">
      <tr>
        <td colspan="6" align="center" bgcolor="#FFFFFF">招聘信息管理列表</td>
        </tr>
      <tr>
        <td width="40" bgcolor="#FFFFFF">ID</td>
        <td width="150" bgcolor="#FFFFFF">职位</td>
        <td width="150" bgcolor="#FFFFFF">地址</td>
        <td width="150" bgcolor="#FFFFFF">人数</td>
        <td width="150" bgcolor="#FFFFFF">日期</td>
        <td width="82" bgcolor="#FFFFFF">操作</td>
      </tr>
	<?php 
		if(!empty($data)){
			foreach($data as $value){
	?>
      <tr>
        <td bgcolor="#FFFFFF">&nbsp;<?php echo $value['id']?></td>
        <td bgcolor="#FFFFFF">&nbsp;<?php echo $value['positionName']?></td>
        <td bgcolor="#FFFFFF">&nbsp;<?php echo $value['address']?></td>
        <td bgcolor="#FFFFFF">&nbsp;<?php echo $value['num']?></td>
        <td bgcolor="#FFFFFF">&nbsp;<?php echo $value['date']?></td>
        <td bgcolor="#FFFFFF">    
        <a href="../php/recruit_del.php?id=<?php echo $value['id']?>">删除</a>
         <a href="recruit_modify.php?id=<?php echo $value['id']?>">修改</a></td>
      </tr>
        <?php
        		}
		}
        ?>
    </table></td>
  
</table>
</body>
</html>
























