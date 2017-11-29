<?php
$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
$url_this;
if (stripos($url_this,"baiwanshouye"/*"baiwanshouye.cn"*/)!==false) {//百万首页
	include_once('million/index.php');
}else if (stripos($url_this,"xiaohuidl.com")!==false){
	phpinfo();
}
?>