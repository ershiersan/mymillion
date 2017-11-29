$(function(){
	$("#add").click(function(){
		var form = $("<form action='index.php?a=price&post=1' method='post'></form>");
		$(form).append($("<td><input type='text' name='startgrid'/></td>"));
		$(form).append($("<td><input type='text' name='endgrid'/></td>"));
		$(form).append($("<td><input type='text' name='price'/></td>"));
		$(form).append($("<td><input type='text' name='description' class='description'/></td>"));
		$(form).append($("<td><input type='submit' class='submit' value='提交' /></td>"));
		var tr = $("<tr class='trbody'></tr>");
		$(tr).append($(form));
		$(".price table").append($(tr));
	});
	$(".del").click(function(){
		if(confirm("确认删除？")){
			$.post("index.php?a=ajax&r=delprice", {"id":$(this).attr("priceid")}, function(text, status){
				if (text == "success") {
					alert("删除成功");
				}else{
					alert("删除失败");
				}
				history.go(0);
			});
		}
	});
});