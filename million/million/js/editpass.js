$(function(){
	//点击更新验证码
	$("#imgverify").click(function(){
		var d=new Date();
		$(this).attr("src", "index.php?a=code&t="+d.getTime());
	});
	//提交按钮事件
	$("#submit").click(function(){
		if ($("#oldpassword").val() == "") {
			alert("原密码不能为空！");
			return false;
		}
		if ($("#newpassword").val() == "") {
			alert("新密码不能为空！");
			return false;
		}
		if ($("#checkpassword").val() == "") {
			alert("确认密码不能为空！");
			return false;
		}
		if ($("#checkpassword").val() != $("#newpassword").val()) {
			alert("两遍新密码不一致！");
			return false;
		}
		switch(checkCode($("#verify").val())){
			case "failure":
				alert("验证码不正确！");
				$("#verify").focus();
				return false;
				break;
		}
	});

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
});