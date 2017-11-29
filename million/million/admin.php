<?php
if($_SESSION["ADMUSER"] != ""){
	//已经登录的不需要登录
	$common_area = "<script type='text/javascript'>location.href='index.php?a=adlist';</script>";
}
else if($_GET["post"] == 1){
	//接收提交的数据
	$mumber_msg = $_POST;
	if (ADMUSER != md5($mumber_msg["username"]) && ADMPASS != md5($mumber_msg["password"])) {
		$common_area = "<script type='text/javascript'>alert('登陆失败');history.go(-1);</script>";
	}else{
		$_SESSION["ADMUSER"] = ADMUSER;
		$common_area = "<script type='text/javascript'>alert('登录成功');location.href='".($_GET["l"]?$_GET["l"]:'index.php?a=adlist')."';</script>";
	}
}else{
	$require_js[] = "login";
	$require_css[] = "reg";	//和reg页面可共用样式文件

	$lang["login"] = "用户登录";
	$lang["username"] = "用户名";
	$lang["password"] = "密码";
	$lang["verify"] = "验证码";
	$lang["submit"] = "登录";
	$lang["reg"] = "注册";
	$lang["colon"] = "：";
	$required = '<span class="regtip">*</span>';
	$_GET["l"] && $lastUrl = "&l=".urlencode($_GET["l"]);
	require_once("secondnav.php");
	$common_area = '
	'.$adNavigator."<span class='mainright'>".'
	<span class="regarea">
		<span class="regtitle">'.$lang["login"].'</span>
		<form action="index.php?a=admin&town=guxian&post=1'.$lastUrl.'" method="post">
		<table class="regtable">
			<tr><td class="left">'.$lang["username"].$lang["colon"].'</td><td><input type="text" name="username" id="username" maxlength="20" />'.$required.'</span></td></tr>
			<tr><td class="left">'.$lang["password"].$lang["colon"].'</td><td><input type="password" name="password" id="password" maxlength="20" />'.$required.'</span></td></tr>
			<tr><td class="left">'.$lang["verify"].$lang["colon"].'</td><td><input type="text" name="verify" id="verify" maxlength="5" /><img id="imgverify" title="看不清楚？换一张" src="index.php?a=code&t='.time().'"/></td></tr>
			<tr><td colspan="2" class="loginbutton">
				<input type="submit" id="submit" value="'.$lang["submit"].'"/>
				<span class="linkreg"><a href="index.php?a=reg'.($_GET["l"]?"&l=".urlencode($_GET["l"]):"").'">'.$lang["reg"].'</span>
			</td></tr>
		</table>
		</form>
	</span>
	</span>';
}
?>