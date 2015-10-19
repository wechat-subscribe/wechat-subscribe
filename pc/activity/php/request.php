<?php 
$file=fopen("./a.....aasas.d.a.sa.as.txt","a+");

foreach($_POST as $k=>$v){
	fwrite($file,$k."=>".$v."______");
}
 

?>