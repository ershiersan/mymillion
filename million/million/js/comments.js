$(function(){
	//鼠标移开规范一下
	$("#title,#content").blur(function(){
		$(this).val(trim($(this).val()));
	});
	//点击更新验证码
	$("#imgverify").click(function(){
		var d=new Date();
		$(this).attr("src", "index.php?a=code&t="+d.getTime());
	});
	//提交按钮事件
	$("#submit").click(function(){
		if(trim($("#title").val()).length <= 0){
			alert("留言标题不能为空！");
			$("#title").focus();
			return false;
		}
		if(trim($("#content").val()).length <= 0){
			alert("留言内容不能为空！");
			$("#content").focus();
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
	function trim(str){ //删除左右两端的空格
		return str.replace(/(^\s*)|(\s*$)/g, "");
	}
});