	function IsURL(str_url){
        var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
        + "|" // 允许IP和DOMAIN（域名）
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名
        + "[a-z]{2,6})" // first level domain- .com or .museum
        + "(:[0-9]{1,4})?" // 端口- :80
        + "((/?)|" // a slash isn't required if there is no file name
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";
        var re=new RegExp(strRegex);
        //re.test()
        if (re.test(str_url)){
            return (true);
        }else{
            return (false);
        }
	}

	function trim(str){ //删除左右两端的空格
		return str.replace(/(^\s*)|(\s*$)/g, "");
	}
$(function(){
	$("#preview,#submit").click(function(){
		$("#href").val(trim($("#href").val()));
		/*if ($("#href").val() == "") {
			alert("链接网址不能为空");
			$("#href").focus();
			return false;
		}*/
		if ($("#href").val() != "" && !IsURL($("#href").val())) {
			alert("链接网址不合法");
			$("#href").focus();
			return false;
		}
		
		$("#title").val(trim($("#title").val()));
		/*if ($("#title").val() == "") {
			alert("网站标题不能为空");
			$("#title").focus();
			return false;
		}*/
		
		$("#introduction").val(trim($("#introduction").val()));
		//简介允许为空
		switch($(this).attr("id")){
			case "preview":
				//去预览
				var imgname = encodeURIComponent($("#imgname").val());
				var href = encodeURIComponent($("#href").val());
				var introduction = encodeURIComponent($("#introduction").val());
				var parameter = "&id="+$("#orderid").val();
				parameter += "&in="+imgname;
				parameter += "&hr="+href;
				parameter += "&idt="+introduction;
				//alert(parameter)
				window.open("/index.php?a=pre"+parameter);
				break;
			case "submit":
				//确认是否提交
				if (!confirm("确认提交？")) {
					return false;
				}
				break;
		}
	});
});