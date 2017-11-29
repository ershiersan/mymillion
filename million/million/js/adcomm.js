$(function(){
	$(".del").click(function(){
		if(confirm("确认删除？")){
			$.post("index.php?a=ajax&r=delcomm", {"id":$(this).attr("faqid")}, function(text, status){
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