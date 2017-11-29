$.fn.jump = function(options) { 
	var defaults = {
		interval:3000,	//跳动间隔
		speed:1000,		//跳动速度
		times:2,		//每轮跳动几次
		height:100		//跳动的高度
	};

	var opts = $.extend(defaults, options);
	var thisdom = $(this);
	
	var times_cursor = 0;
	var perspeed = parseInt(opts.speed/opts.times/2);
	var initTop = $(thisdom).position().top;

	window.setTimeout(expand, 500);

	function expand(){
		$(thisdom).animate({"top":(initTop - opts.height)+"px"}, perspeed);
		window.setTimeout(reduce, perspeed);
	}
	function reduce(){
		$(thisdom).animate({"top":initTop+"px"}, perspeed);
		if(++times_cursor < opts.times){
			window.setTimeout(expand, perspeed);
		}else{
			times_cursor = 0;
			window.setTimeout(expand, opts.interval-opts.speed);
		}
	}

	/*var framecount = 10;
	
	setInterval(function(){
		var perheight = parseInt(opts.height/framecount);
		var perspeed = parseInt(opts.speed/opts.times/2/framecount);
		var perspeed = parseInt(opts.speed/opts.times/2);
		var times = 0;
		var initTop = $(thisdom).position().top;
		expand();

		function expand(){
			$(thisdom).animate({"top":(initTop - opts.height)+"px"}, perspeed);
			window.setTimeout(reduce, perspeed);
			
			if(parseInt($(thisdom).css("top")) > initTop - opts.height){
				$(thisdom).css("top", parseInt($(thisdom).css("top")) - perheight);
				window.setTimeout(expand, perspeed);
			}
			else{
				window.setTimeout(reduce, perspeed);
			}
		}

		function reduce(){
			$(thisdom).animate({"top":initTop+"px"}, perspeed);
			if(++times < opts.times)
				window.setTimeout(expand, perspeed);
		}
	}, opts.interval);*/
};