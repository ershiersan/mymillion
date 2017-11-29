<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $str_title; ?></title>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo $description; ?>" />
<?php
	foreach($require_css as $key_css => $value_css){
		echo '	<link rel="stylesheet" type="text/css" href="/'.PATH.'css/'.$value_css.'.css" />'."\r\n";
	}
?><?php if(count($require_style)){ ?><style type="text/css"><?php //动态style
	foreach($require_style as $key_style => $value_style){
		echo $value_style."\r\n";
	}
?></style><?php } ?><?php
	foreach($require_js as $key_js => $value_js){
		echo '	<script type="text/javascript" src="/'.PATH.'js/'.$value_js.'.js"></script>'."\r\n";
	}
?><?php if(count($require_script)){ ?>	<script type="text/javascript"><?php //动态style
	foreach($require_script as $key_script => $value_script){
		echo $value_script."\r\n";
	}
?>	</script>
<?php } ?>
</head>
<body><?php 
	require_once("service/order.class.php");
	$objOrder = new Order();
	$gridCount = $objOrder->getUsefulGridCount();
	$PriceItem = $objOrder->getPriceItem();
	?>
	<span class="title"><span class="marspan"></span></span>
	<span class="hat">
		<span class="navmargin"></span>
		<span class="navigation">
			<span class="navitem1">
				<a href="/"><span>首页</span></a>
			</span><span class="navitem2">
				<a href="index.php?a=select"><span>加入格子</span></a>
			</span><span class="navitem3">
				<a href="index.php?a=history"><span>成长记录</span></a>
			</span><span class="navitem4">
				<a href="index.php?a=faq"><span>FAQ</span></a>
			</span><span class="navitem5">
				<a href="index.php?a=comments"><span>留言板</span></a>
			</span><span class="navitem6">
				<a href="index.php?a=mglist"><span>管理格子</span></a>
			</span>
		</span>
	</span>
	<span class="body">
		<span class="head">
			<span class="heightbox"></span>
			<span class="logo">
			</span><span class="headmiddle">
				<span class="summary">
					<span>每个格子价值￥100</span>
					<span class="point"><img src="<?php echo PATH?>images/point.png"/></span>
					<span>10000个格子供您选择</span>
					<span class="point"><img src="<?php echo PATH?>images/point.png"/></span>
					<span>DIY您的广告创意</span>
				</span>
				<span class="price">
					<marquee scrollamount=5><span><?php print_r($PriceItem["description"]); ?></span></marquee>
				</span>
			</span><span class="headright">
				<span class="firstrow">已售出<span><?php print_r($gridCount?$gridCount:"0"); ?></span>个</span>
				<span class="secondrow"><span><?php print_r(10000-$gridCount); ?></span>个可用</span>
			</span>
		</span>
		<span class="main">
		<?php
			echo $common_area;
		?></span><span class="tail">
		<span class="mailto">如有任何问题，请在留言板留言，或联系我：<a href="mailto:baiwanshouye@163.com">baiwanshouye@163.com</a></span>
		<span class="tongji"><script src="http://s25.cnzz.com/stat.php?id=5483552&web_id=5483552&show=pic" language="JavaScript"></script>　冀ICP备13011743号-1</span>
	</span>
	</span><!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=slide&amp;img=5&amp;pos=right&amp;uid=6746450" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
var bds_config={"bdTop":100};
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<!-- Baidu Button END --></body>
</html>