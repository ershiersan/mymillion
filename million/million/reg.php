<?php
if($_SESSION["member"] != ""){
	//已经登录的不需要注册
	$common_area = "<script type='text/javascript'>alert('您已登录成功');location.href='index.php?a=mglist';</script>";
}
else if($_GET["post"] == 1){
	//接收提交的数据
	$mumber_msg = $_POST;
	require_once("service/member.class.php");
	$objMember = new Member();
	$rsCount = $objMember->getMemberMsgByUsername($mumber_msg["username"]);
	if($mumber_msg["username"] == ""){
		$common_area = "<script type='text/javascript'>alert('用户名不能为空');history.go(-1);</script>";
	}else if(count($rsCount) > 0){
		$common_area = "<script type='text/javascript'>alert('用户名已存在');history.go(-1);</script>";
	}else if(strlen($mumber_msg["repassword"]) <= 5){
		$common_area = "<script type='text/javascript'>alert('密码不能少于6字符！');history.go(-1);</script>";
	}else if($mumber_msg["repassword"] != $mumber_msg["password"]){
		$common_area = "<script type='text/javascript'>alert('两次输入密码不一致！');history.go(-1);</script>";
	}else{
		$mumber_msg["password"] = md5($mumber_msg["password"]);
		unset($mumber_msg["repassword"]);
		unset($mumber_msg["verify"]);
		date_default_timezone_set('PRC');
		$mumber_msg["datetime"] = date('Y-m-d H:i:s');
		//$database->insert(DBPREFIX."member", $mumber_msg);
		$objMember->insertMemberByArray($mumber_msg);
		$_SESSION["member"] = $mumber_msg["username"];
		$common_area = "<script type='text/javascript'>alert('注册成功！');location.href='".($_GET["l"]?$_GET["l"]:"/index.php?a=login")."';</script>";
	}
}else{
	$require_js[] = $require_css[] = "reg";
	$lang["mumberreg"] = "用户注册";
	$lang["username"] = "用户名";
	$lang["password"] = "密码";
	$lang["repassword"] = "重复密码";
	$lang["nickname"] = "昵称";
	$lang["gender"] = "性别";
	$lang["gender1"] = "男";
	$lang["gender2"] = "女";
	$lang["email"] = "Email";
	$lang["mobile"] = "手机号";
	$lang["verify"] = "验证码";
	$lang["reg"] = "同意以下协议并注册";
	$lang["colon"] = "：";
	$required = '<span class="regtip">* </span>';
	require_once("secondnav.php");
	$common_area = $mgNavigator."<span class='mainright'>".'
	<span class="regarea">
		<span class="regtitle">'.$lang["mumberreg"].'</span>
		<form action="index.php?a=reg&post=1'.($_GET["l"]?"&l=".urlencode($_GET["l"]):"").'" method="post">
		<table class="regtable">
			<tr><td class="left">'.$required.$lang["username"].$lang["colon"].'</td><td><input type="text" name="username" id="username" maxlength="20" /><span class="regtip"></span></td></tr>
			<tr><td class="left">'.$required.$lang["password"].$lang["colon"].'</td><td><input type="password" name="password" id="password" maxlength="20" /><span class="regtip"></span></td></tr>
			<tr><td class="left">'.$required.$lang["repassword"].$lang["colon"].'</td><td><input type="password" name="repassword" id="repassword" maxlength="20" /><span class="regtip"></span></td></tr>
			<tr><td class="left">'.$lang["nickname"].$lang["colon"].'</td><td><input type="text" name="nickname" id="nickname" maxlength="20" /></td></tr>
			<tr><td class="left">'.$lang["gender"].$lang["colon"].'</td><td>
				<select name="gender" id="gender" >
					<option value="1">'.$lang["gender1"].'</option>
					<option value="2">'.$lang["gender2"].'</option>
				<select>
			</td></tr>
			<tr><td class="left">'.$required.$lang["email"].$lang["colon"].'</td><td><input type="text" name="email" id="email" maxlength="50" /><span class="regtip"></span></td></tr>
			<tr><td class="left">'.$lang["mobile"].$lang["colon"].'</td><td><input type="text" name="mobile" id="mobile" maxlength="15" /></td></tr>
			<tr><td class="left">'.$lang["verify"].$lang["colon"].'</td><td><input type="text" name="verify" id="verify" maxlength="5" /><img id="imgverify" title="看不清楚？换一张" src="index.php?a=code&t='.time().'"/></td></tr>
			<tr><td colspan="2" class="regbutton"><input type="submit" id="submit" value="'.$lang["reg"].'"/></td></tr>
		</table>
		</form>
		<textarea rows="20" cols="80" readonly="true" style="font-size:15px;">
'.file_get_contents(PATH."service/regtext.txt").'
    	</textarea>
	</span>
	</span>
	';
}
?>