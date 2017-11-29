$(function(){
	$("#add").click(function(){
		var form = $("<form action='index.php?a=adfaq&post=1' method='post'></form>");
		$(form).append($("<td><input type='text' name='intorder' class='intorder'/></td>"));
		$(form).append($("<td><textarea name='question' rows='3' class='question'></textarea></td>"));
		$(form).append($("<td><textarea name='answer' rows='3' class='answer'></textarea></td>"));
		$(form).append($("<td><input type='submit' class='submit' value='提交' /></td>"));
		var tr = $("<tr class='trbody'></tr>");
		$(tr).append($(form));
		$(".adfaq table").append($(tr));
	});
	$(".del").click(function(){
		if(confirm("确认删除？")){
			$.post("index.php?a=ajax&r=delfaq", {"id":$(this).attr("faqid")}, function(text, status){
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