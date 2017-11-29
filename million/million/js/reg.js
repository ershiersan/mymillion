$(function(){
	//鼠标移开检查用户名的唯一性
	$("#username").blur(function(){
		$(this).val(trim($(this).val()));
		switch(checkUsername($(this).val())){
			case "failure":
				$(this).next("span").text("用户名不能为空！");
				break;
			case "exist":
				$(this).next("span").text("用户名已存在！");
				break;
			case "success":
				$(this).next("span").text("");
				break;
		}
	});
	//鼠标移开检查密码长度
	$("#password").blur(function(){
		switch(checkPassword($(this).val())){
			case "failure":
				$(this).next("span").text("密码不能少于6字符！");
				break;
			case "success":
				$(this).next("span").text("");
				break;
		}
		if($(this).val() == $("#repassword").val()){
			//密码input离开,如果一致,不一致提示取消
			$("#repassword").next("span").text("");
		}
		if($(this).val() != $("#repassword").val() && $("#repassword").val() != ""){
			//密码input离开,如果不一致,且第二遍密码不为空
			$("#repassword").next("span").text("两次输入密码不一致！");
		}
	});
	//鼠标移开检查两遍密码是否一致
	$("#repassword").blur(function(){
		if($(this).val() != $("#password").val()){
			$(this).next("span").text("两次输入密码不一致！");
		}else{
			$(this).next("span").text("");
		}
	});
	//鼠标移开验证邮箱格式
	$("#email").blur(function(){
		switch(checkMail($(this).val())){
			case "failure":
				$(this).next("span").text("邮箱格式不正确！");
				break;
			case "success":
				$(this).next("span").text("");
				break;
		}
	});
	//只能输入数字
	$("#mobile").keydown(function(e){
		if ($.browser.msie) {  // 判断浏览器
			if ( ((event.keyCode > 47) && (event.keyCode < 58)) || ((event.keyCode > 95) && (event.keyCode < 106)) || (event.keyCode == 8) ) { 　// 判断键值 
				 return true; 
			} else { 
				return false; 
			}
		} else { 
			if ( ((e.which > 47) && (e.which < 58)) || ((e.which > 95) && (e.which < 106)) || (e.which == 8) || (event.keyCode == 17) ) { 
				return true; 
			} else { 
				return false; 
		} 
	}}).focus(function() {
		this.style.imeMode='disabled';   // 禁用输入法,禁止输入中文字符
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
			case "exist":
				alert("用户名已存在！");
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
		if($("#repassword").val() != $("#password").val()){
			alert("两次输入密码不一致！");
			$("#repassword").focus();
			return false;
		}
		switch(checkMail($("#email").val())){
			case "failure":
				alert("邮箱格式不正确！");
				$("#email").focus();
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
		var strReturn;
		$.ajax({
			"async":false,
			"type": "POST",
			"url":"index.php?a=ajax&r=useruniq",
			"data":{"username":username},
			"success":function(text){
				if(text == "0"){
					strReturn = "success";
				}else{
					strReturn = "exist";
				}
			}
		});
		return strReturn;
	}
	function checkPassword(password){
		if(password.length <= 5){
			return "failure";
		}
		return "success";
	}
	function checkMail(mail) {
		var filter  =  /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if (filter.test(mail))
			return "success";
		else {
			return "failure";
		}
	}
	function trim(str){ //删除左右两端的空格
		return str.replace(/(^\s*)|(\s*$)/g, "");
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
});