<?php 
    $require_css[] = "secondnav";
	$adNavigator = '<span class="mainleft"><span class="secondnav">
	<div class="tishi">'.($_SESSION["ADMUSER"] == ""?"未登录":"<span class='welcome'>欢迎欢迎：</span><span class='member'>管理员</span>").'</div>
	<div class="navitem"><a href="/index.php?a=adlist">管理列表</a></div>
	<div class="navitem"><a href="/index.php?a=price">价格管理</a></div>
	<div class="navitem"><a href="/index.php?a=adfaq">FAQ管理</a></div>
	<div class="navitem"><a href="/index.php?a=adcomm">留言管理</a></div>
	<div class="navitem"><a href="/index.php?a=download">下载备份</a></div>
	<div class="navitem"><a href="/index.php?a=query">执行sql</a></div>
	<div class="navitem"><a href="/index.php?a=delimage" onclick="if(!confirm(\'危险动作，是否执行？\'))return false;">清理垃圾图片</a></div>
	<div class="navitem"><a href="/index.php?a=adexit">安全退出</a></div>
	</span></span>';
	$mgNavigator = '<span class="mainleft"><span class="secondnav">
	<div class="tishi">'.($_SESSION["member"] == ""?"未登录":"<span class='welcome'>欢迎您：</span><span class='member'>".$_SESSION["member"]."</span>").'</div>
	<div class="navitem"><a href="/mglist.htm">管理格子</a></div>
	<div class="navitem"><a href="/editpass.htm">修改密码</a></div>
	<div class="navitem"><a href="/mgexit.htm">安全退出</a></div>
	</span></span>';
?>