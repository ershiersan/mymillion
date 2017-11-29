$(function(){
	//鼠标移开规范用户名
	$("#username").blur(function(){
		$(this).val(trim($(this).val()));
	});
	//点击更新验证码
	$("#imgverify").click(function(){
		var d=new Date();
		$(this).attr("src", "index.php?a=code&t="+d.getTime());
	});
	//提交按钮事件
	$("#submit").click(function(){
		switch(checkUsername($("#username").val())){
			case "failure":
				alert("用户名不能为空！");
				$("#username").focus();
				return false;
				break;
		}
		switch(checkPassword($("#password").val())){
			case "failure":
				alert("密码不能少于6字符！");
				$("#password").focus();
				return false;
				break;
		}
		switch(checkCode($("#verify").val())){
			case "failure":
				alert("验证码不正确！");
				$("#verify").focus();
				return false;
				break;
		}
	});

	function checkUsername(username){
		if(username.length <= 0){
			return "failure";
		}
		return "success";
	}
	function checkPassword(password){
		if(password.length <= 5){
			return "failure";
		}
		return "success";
	}
	function checkCode(code){
		if(code.length <= 0){
			return "failure";
		}
		var strReturn;
		$.ajax({
			"async":false,
			"type": "POST",
			"url":"index.php?a=ajax&r=checkcode",
			"data":{"code":code},
			"success":function(text){
				if(text == "1"){
					strReturn = "success";
				}else{
					$("#imgverify").click();
					strReturn = "failure";
				}
			}
		});
		return strReturn;
	}
	function trim(str){ //删除左右两端的空格
		return str.replace(/(^\s*)|(\s*$)/g, "");
	}
});