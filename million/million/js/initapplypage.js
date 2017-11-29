$.fn.initApplyPage = function(options){
	var defaults = { 
		authority: "free",	//special/free/charge
		freeperiod: 1,		//0/1
		current: 0.00,		//currentprice
		next: 10.00,		//nextprice
		FREEAPPLYCOUNT: 25,
		APPLYCOUNT:100,
		GRIDWIDTH:12
	};
	var opts = $.extend(defaults, options);
	
	$("#rowcount,#colcount").change(function(){
		var rowcount = parseInt($("#rowcount").val());
		var colcount = parseInt($("#colcount").val());

		$('#imagelogo')
			.width(parseInt(colcount)*defaults.GRIDWIDTH)
			.height(parseInt(rowcount)*defaults.GRIDWIDTH)

		var advanceReturn1 = false;
		var advanceReturn2 = false;
		var count = rowcount*colcount;
		if (count > defaults.APPLYCOUNT) {
			advanceReturn1 = true;
		}

		$.ajax({
			"async":false,
			"type": "POST",
			"url":"index.php?a=ajax&r=checkaddable",
			"data":{
				"startrow":$("#startrow").val(), 
				"startcol":$("#startcol").val(), 
				"rowcount":rowcount, 
				"colcount":colcount
			},
			"success":function(text){
				if(text == "0"){
					advanceReturn2 = true;
				}
			}
		});


		var oldval = parseInt($(this).attr("oldval"));
		if (!(oldval > 0)) {
			oldval = 1;
		}
		var newval = parseInt(defaults.APPLYCOUNT*parseInt($(this).val())/count);

		if (advanceReturn1 && advanceReturn2) {
			/*if (oldval ==newval) {
				$(this).val(oldval);
			}else */if (oldval >newval) {
				alert("每单选择格子限"+defaults.APPLYCOUNT+"格");
				$(this).val(newval);
				$(this).change();
			}else{
				alert("请不要申请已占用的格子");
				$(this).val(oldval);
				if(oldval !=newval)
					$(this).change();
			}
			return false;
		}else if(advanceReturn1){
			alert("每单选择格子限"+defaults.APPLYCOUNT+"格");
			$(this).val(newval);
			$(this).change();
			return false;
			
		}else if(advanceReturn2){
			alert("请不要申请已占用的格子");
			$(this).val(oldval);
			$(this).change();
			return false;
		}

		$("#count").text(count);

		$(this).attr("oldval", $(this).val());
		
			switch(defaults.authority){
				case "special":
					//随便选，总价不变
					break;
				case "free":
					if(defaults.freeperiod){
						//免费期间
						if (count > defaults.FREEAPPLYCOUNT){
							//超出了免费的范围
							var exceedCount = count - defaults.FREEAPPLYCOUNT;
							$("#chargecount").text(exceedCount);
							$("#price").text(parseFloat(defaults.next).toFixed(2));
							$("#amount").text(parseFloat(exceedCount*defaults.next).toFixed(2));
							$(".charge").show();
						}else{
							$("#amount").text(count*defaults.current);
							$(".charge").hide();
						}
					}else{
						//非免费期间，按照正常处理，使用current价格
						$("#amount").text(parseFloat(count*defaults.current).toFixed(2));
					}
					break;
				case "charge":
					if(defaults.freeperiod){
						//免费期间，不能享受免费，使用next价格
						$("#amount").text(parseFloat(count*defaults.next).toFixed(2));
					}else{
						//非免费期间，按照正常处理，使用current价格
						$("#amount").text(parseFloat(count*defaults.current).toFixed(2));
					}
					break;
			}
	});

	$("#rowcount,#colcount").change();

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

	$("#fileimg").change(function(){
		ajaxfileupload();
	});

	$("#preview,#submit").click(function(){
		//验证三项是否输入完整
		if ($("#hasimg").val() != "true") {
			alert("请上传logo图片");
			return false;
		}
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
				var rowcount = parseInt($("#rowcount").val());
				var colcount = parseInt($("#colcount").val());
				var startrow = parseInt($("#startrow").val());
				var startcol = parseInt($("#startcol").val());
				var imgname = encodeURIComponent($("#imgname").val());
				var href = encodeURIComponent($("#href").val());
				var introduction = encodeURIComponent($("#introduction").val());
				var parameter = "&sc="+startcol;
				parameter += "&sr="+startrow;
				parameter += "&cc="+colcount;
				parameter += "&rc="+rowcount;
				parameter += "&in="+imgname;
				parameter += "&hr="+href;
				parameter += "&idt="+introduction;
				/*alert(parameter)*/
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
}