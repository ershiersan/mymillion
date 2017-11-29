$.fn.initPreview = function(options){
	var defaults = { 
		gridposition: [1,1,1,1], 
		imagesrc: "",
		href: "",
		introduction: "",
		GRIDWIDTH: 12
	};
	var opts = $.extend(defaults, options);
	opts.startcol = opts.gridposition[0];
	opts.startrow = opts.gridposition[1];
	opts.colcount = opts.gridposition[2];
	opts.rowcount = opts.gridposition[3];


	var newspan = $("<span>"+
		(opts.href?"<a target='_blank' href='"+opts.href+"'>":"")+
		"<img src='"+opts.imagesrc+"' title='"+opts.introduction+"'/>"+
		(opts.href?"</a>":"")+
		"</span>");

	$(newspan).css({
		"left":((opts.startcol-1)*opts.GRIDWIDTH+$(this).position().left),
		"top":((opts.startrow-1)*opts.GRIDWIDTH+$(this).position().top),
		"width":(opts.colcount*opts.GRIDWIDTH),
		"height":(opts.rowcount*opts.GRIDWIDTH),
		"position":"absolute"
	}).find("a").css({
		"width":"100%",
		"height":"100%",
		"border":0
	}).end().find("img").css({
		"width":"100%",
		"height":"100%",
		"border":0
	});

	$(this).css({"filter":"alpha(opacity=20)", "-moz-opacity":"0.2", "opacity":"0.2"});
	$("body").append($(newspan));
	return $(newspan);
}