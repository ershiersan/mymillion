window.onload = (function(){
	var jqueryspan = $(".home span");
	var speed = 25;
	setTimeout(function(){
		showSpan(0);
	}, speed);
	function showSpan(i){
		var spancount = $(jqueryspan).length;
		if (i < spancount) {
			$($(jqueryspan).get(i)).show();
			setTimeout(function(){
				showSpan(i+1);
			}, speed);
		}
	}
});