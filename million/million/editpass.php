<?php
if ($_SESSION["member"] == "") {//没登录，先去登录
    $common_area = "<script type='text/javascript'>location.href='index.php?a=login&l=".urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."';</script>";
}
else if($_GET["post"] == 1){
	//接收提交的数据
	$mumber_msg = $_POST;
	require_once("service/member.class.php");
	$objMember = new Member();
	$rsUser = $objMember->getMemberMsgByUsername($_SESSION["member"]);

	if (md5($_POST["oldpassword"]) != $rsUser[0]["password"]) {
		$common_area = "<script type='text/javascript'>alert(\"原密码不正确！\");history.go(-1);</script>";
	}else if ($_POST["newpassword"] != $_POST["checkpassword"]) {
		$common_area = "<script type='text/javascript'>alert('两遍新密码不一致！');history.go(-1);</script>";
	}else{//ok,更新密码
		if ($objMember->updatePassword($_SESSION["member"], $_POST["newpassword"])) {
			$common_area = "<script type='text/javascript'>alert('修改成功！');location.href='index.php?a=mglist';</script>";
		}else{
			$common_area = "<script type='text/javascript'>alert('修改失败！');history.go(-1);</script>";
		}
	}
}else{
	$require_js[] = "editpass";
	$require_css[] = "reg";	//和reg页面可共用样式文件

	$lang["login"] = "修改密码";
	$lang["oldpassword"] = "原密码";
	$lang["newpassword"] = "新密码";
	$lang["checkpassword"] = "确认密码";
	$lang["colon"] = "：";
	$lang["submit"] = "提交";
	$lang["verify"] = "验证码";
	$required = /*'<span class="regtip">*</span>'*/"";
	
	require_once("secondnav.php");
	$common_area = '
	'.$mgNavigator."<span class='mainright'>".'
	<span class="regarea">
		<span class="regtitle">'.$lang["login"].'</span>
		<form action="index.php?a=editpass&post=1" method="post">
		<table class="regtable">
			<tr><td class="left">'.$lang["oldpassword"].$lang["colon"].'</td><td><input type="password" name="oldpassword" id="oldpassword" maxlength="20" />'.$required.'</span></td></tr>
			<tr><td class="left">'.$lang["newpassword"].$lang["colon"].'</td><td><input type="password" name="newpassword" id="newpassword" maxlength="20" />'.$required.'</span></td></tr>
			<tr><td class="left">'.$lang["checkpassword"].$lang["colon"].'</td><td><input type="password" name="checkpassword" id="checkpassword" maxlength="20" />'.$required.'</span></td></tr>
			<tr><td class="left">'.$lang["verify"].$lang["colon"].'</td><td><input type="text" name="verify" id="verify" maxlength="5" /><img id="imgverify" title="看不清楚？换一张" src="index.php?a=code&t='.time().'"/></td></tr>
			<tr><td colspan="2" class="loginbutton">
				<input type="submit" id="submit" value="'.$lang["submit"].'"/>
				<span class="linkreg"><a href="index.php?a=reg'.($_GET["l"]?"&l=".urlencode($_GET["l"]):"").'">'.$lang["reg"].'</span>
			</td></tr>
		</table>
		</form>
	</span>
	</span>
	';
}
?>