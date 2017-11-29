$(function(){
	$(".marspan").initMarquee();
});
$.fn.initMarquee = function(options){
	var defaults = { 
		speed: 120	//速度数值越大速度越慢
	};
	var opts = $.extend(defaults, options);

	var demo1 = $(this);
	var demo = demo1.parent();
	var demo2 = demo1.clone();
	demo.append(demo2);
	Marquee();
	function Marquee(){
		if(parseInt($(demo2).width())-$(demo).scrollLeft()<=0){
			$(demo).scrollLeft($(demo).scrollLeft()-parseInt($(demo1).width()));
		}
		else{
			$(demo).scrollLeft($(demo).scrollLeft()+1);
		}
		setTimeout(Marquee, opts.speed);
	}
}