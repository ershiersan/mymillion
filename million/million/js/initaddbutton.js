$.fn.initAddButton = function(options){
	var defaults = { 
		APPLYCOUNT: 100,
		gridwidth: 12, 
		dictionary: "/million/"
	};
	var opts = $.extend(defaults, options);
	
	$(".home").css("cursor", "url('"+opts.dictionary+"images/cross.cur'),default");

	var usedGrid = new Array();//请求得到的被占用的格子
	$.post("index.php?a=ajax&r=usedgrid", {}, function(text, status){
		if (status = "success") {
			usedGrid = eval("("+text+")");//转换为json对象
		}
	});


	/*根据加入点的坐标和插入的宽高判断是否能够插入
	 */
	function checkAddableRecByPoint($startcol, $startrow,$x/*=1*/, $y/*=1*/){
		//只要一个到边就返回false
		COLCOUNT = ROWCOUNT = 100;

		if (COLCOUNT + 1 <= $startcol + $x - 1 || ROWCOUNT + 1 <= $startrow + $y - 1) {
			return false;
		}

		//如果和任意点有交集返回false
		var $gridNeedCheck = new Array();
		$gridNeedCheck["startcol"] = $startcol;
		$gridNeedCheck["startrow"] = $startrow;
		$gridNeedCheck["colcount"] = $x;
		$gridNeedCheck["rowcount"] = $y;

		for (var i = usedGrid.length - 1; i >= 0; i--) {
			if (checkIsIntersectAboutTwoPoint(usedGrid[i], $gridNeedCheck)) {//两区域重合
				return false;
			}
		}
		return true;
	}

	/*判断两个格子是否相交
	 *$Grid=array(startcol,startrow,colcount,rowcount)
	 */
	function checkIsIntersectAboutTwoPoint($Grid1, $Grid2){
		$Grid1["startcol"] = parseInt($Grid1["startcol"]);
		$Grid1["startrow"] = parseInt($Grid1["startrow"]);
		$Grid1["colcount"] = parseInt($Grid1["colcount"]);
		$Grid1["rowcount"] = parseInt($Grid1["rowcount"]);
		for ($col = $Grid1["startcol"] ; $col < $Grid1["startcol"] + $Grid1["colcount"] ; $col++) {
			for ($row = $Grid1["startrow"] ; $row < $Grid1["startrow"] + $Grid1["rowcount"] ; $row++) {
				if ($col >= $Grid2["startcol"]
					&& $col <= $Grid2["startcol"] + $Grid2["colcount"] - 1
					&& $row >= $Grid2["startrow"]
					&& $row <= $Grid2["startrow"] + $Grid2["rowcount"] - 1) {
					return true;
				}
			}
		}
		return false;
	}

	//var absolutespan = $("<span style='position:absolute;display:block;height:100px;width:150px;top:46%;left:46%;'>点击空白格子加入</span>");
	if ($.cookie('hasshowpro') != "true") {
		//var pro = "拖动框选要加入的区域<br>每次加入限"+opts.APPLYCOUNT+"格。";
		var pro = "记得Excel吗？<br>拖动框选空白区域。";
		var absolutespan = $('<div id="fade" class="black_overlay" onclick="CloseDiv(\'MyDiv\',\'fade\')"></div><div id="MyDiv" class="white_content"><div style="text-align: right; cursor: default; height: 40px;"><a id="aclose" onclick="CloseDiv(\'MyDiv\',\'fade\')">关闭</a></div>'+pro+'</div>');
		absolutespan.appendTo($("body"));
		ShowDiv('MyDiv','fade');
		setTimeout(function(){CloseDiv('MyDiv','fade');}, 4000);
		$.cookie('hasshowpro', "true");
	}

	var borderwidth = 2;
	var rectangle = $("<span><span></span></span>");
	var submitbutton = $("<span></span>");
	rectangle.css({
		width:(defaults.gridwidth-borderwidth*2)+"px",
		height:(defaults.gridwidth-borderwidth*2)+"px",
		display:"none",
		"z-index":1,
		border:"solid "+borderwidth+"px red"
	});

	submitbutton.css({
		display:"none",
		width:"100px",
		"z-index":101,
		"text-align":"left",
		"padding":"5px",
		"background":"pink",
		border:"solid 1px #119966"
	});
	var lrpre = $("<div id='lrpre'></div>");
	lrpre.css({
		display:"block",
		width:"100%",
		"margin-bottom":"3px",
		"font-size":"12px"
	});
	var buttonparent = $("<div id='buttonparent'></div>");
	buttonparent.css({
		display:"block"
	});

	var dombutton = $("<input type='button' value='继续'/>");
	buttonparent.append(dombutton);

	submitbutton.append(lrpre);
	submitbutton.append(buttonparent);

	/*按钮所在的层点击不触发框选相应*/
	var isDivMouseover = false;
	submitbutton.hover(function(){
		isDivMouseover = true;
	}, function(){
		isDivMouseover = false;
	});

	$(this).append(rectangle);
	$(this).append(submitbutton);

	var isDown = false;
	var beginX = 0;
	var beginY = 0;
	var endX = 0;
	var endY = 0;

	var lengthX = 0;
	var minX = 0;
	var lengthY = 0;
	var minY = 0;

	dombutton.click(function(){
		location.href = "index.php?a=apply&left="+minX+"&top="+minY+"&width="+lengthX+"&height="+lengthY;
	});

	$(this).mousedown(function(e){
		if (isDivMouseover) {
			return false;
		}
		if (isDown) {
			$(this).mouseup();
			return false;
		}

		/*初始化旋框的大小为1格子*/
		var Y = e.pageY-$(this).position().top;
		var X = e.pageX-$(this).position().left;
		beginX = parseInt(X/defaults.gridwidth)+1;
		beginY = parseInt(Y/defaults.gridwidth)+1;
		if (!checkAddableRecByPoint(beginX, beginY, 1, 1)) {
			//不许重叠
			return false;
		}
		rectangle.show();
		rectangle.css({
			"top":((beginY-1)*defaults.gridwidth)+"px", 
			"left":((beginX-1)*defaults.gridwidth)+"px",
			width:(defaults.gridwidth-borderwidth*2)+"px",
			height:(defaults.gridwidth-borderwidth*2)+"px"
		});

		submitbutton.hide();
		lrpre.html("选择 1 行 1 列<br>共 1 个格子");
		submitbutton.css({
			"top":((beginY+0.2)*defaults.gridwidth)+"px", 
			"left":((beginX+0.2)*defaults.gridwidth)+"px"
		});

		minX = beginX;
		minY = beginY;
		lengthX = 1;
		lengthY = 1;

		isDown = true;
	});
	$(this).mousemove(function(e){
		if (isDivMouseover) {
			return false;
		}
		/*鼠标按下的时候才有事件响应*/
		if (isDown) {
			var Y = e.pageY-$(this).position().top;
			var X = e.pageX-$(this).position().left;
			endX = parseInt(X/defaults.gridwidth)+1;
			endY = parseInt(Y/defaults.gridwidth)+1;

			var maxX = Math.max(beginX, endX);
			varminX = Math.min(beginX, endX);
			var maxY = Math.max(beginY, endY);
			varminY = Math.min(beginY, endY);

			varlengthX = maxX - varminX + 1;
			varlengthY = maxY - varminY + 1;

			if (varlengthY * varlengthX > opts.APPLYCOUNT /*||和其他重叠的情况*/) {
				return false;
			}
			if (!checkAddableRecByPoint(varminX, varminY, varlengthX, varlengthY)) {
				//有重叠部分不允许选中
				return false;
			}

			//通过后才赋值
			minX = varminX;
			minY = varminY;
			lengthX = varlengthX;
			lengthY = varlengthY;

			rectangle.css({
				"top":((minY-1)*defaults.gridwidth)+"px", 
				"left":((minX-1)*defaults.gridwidth)+"px", 
				"width":(lengthX*defaults.gridwidth-borderwidth*2)+"px", 
				"height":(lengthY*defaults.gridwidth-borderwidth*2)+"px"
			});

			lrpre.html("选择 "+lengthY+" 行 "+lengthX+" 列<br>共 "+(lengthY*lengthX)+" 个格子");
			submitbutton.css({
				"top":((minY-0.8+lengthY)*defaults.gridwidth)+"px", 
				"left":((minX-0.8+lengthX)*defaults.gridwidth)+"px"
			});

			window.document.execCommand("Unselect",null,null);
		}
	});
	$(this).mouseup(function(){
		if (isDivMouseover) {
			return false;
		}
		if (isDown) {
			submitbutton.show();
		}
		isDown = false;
	});
}

function ShowDiv(show_div,bg_div){
	$("#"+show_div).css("display", "block");
	$("#"+bg_div).css("display", "block");
	$("#"+bg_div).width($(window).width());
	$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
	$("#"+show_div).css("display", "none");
	$("#"+bg_div).css("display", "none");
};
