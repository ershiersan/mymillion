<?php
	session_start();
	//unset($_SESSION["member"]);
	require_once("service/db.php");
	DB::init();
	require_once("config.php");
	
	$require_js = array();
	$require_css = array();
	$require_style = array();
	$require_script = array();
	$require_js[] = "jquery.min";
	$require_js[] = "jquery.pngfix";
	$require_js[] = $require_css[] = "common";
	$require_js[] = "marquee";
	$str_title = WEBSITENAME;
	if($_GET["a"] != ""){
	//	$str_title = ($arr_title[$_GET["a"]]?$arr_title[$_GET["a"]]." - ":"").$str_title;
		$str_title = ($arr_title[$_GET["a"]]?$arr_title[$_GET["a"]]:$str_title);
		if($_GET["a"] == "history"){//成长记录
			$require_style[] = ".home span{display:none;}";
			$require_js[] = "history";
			require_once("service/order.class.php");
			$objOrder = new Order();
			require_once("home.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "faq"){//faq
			require_once("faq.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "comments"){//留言版
			require_once("comments.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "reg"){//注册
			require_once("reg.php");
			require_once("common.php");
		}
		//20130408
		else if($_GET["a"] == "login"){//登陆
			require_once("login.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "mglist"){//我的格子列表
			require_once("mglist.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "invalid"){//作废我的格子
			require_once("service/order.class.php");
			$objOrder = new Order();
			if ($objOrder->invalidOrderByIds($_GET["id"])) {
				$common_area = "<script type='text/javascript'>alert('作废操作成功');history.go(-1);</script>";
			}else{
				$common_area = "<script type='text/javascript'>alert('作废操作失败');history.go(-1);</script>";
			}
			require_once("common.php");
		}
		else if($_GET["a"] == "pay"){//支付
			require_once("pay.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "sendgoods"){//确认发货
			require_once("sendgoods.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "paysuccess"){//支付完成页
			require_once("paysuccess.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "mgdetail"){//我的格子详细
			require_once("mgdetail.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "editpass"){//修改密码
			require_once("editpass.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "query"){//修改密码
			require_once("query.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "pre"){//两种预览页
			$require_js[] = "initpreview";
			$require_js[] = "jump";
			require_once("service/order.class.php");
			$objOrder = new Order();
			if ($_GET["id"] != "") {
			    $orderId = $_GET["id"];
		        $arrMyOrder = $objOrder->getOneOrderOnlyById($orderId, $_SESSION["member"]);

		        $startcol = (int)$arrMyOrder["startcol"];
				$startrow = (int)$arrMyOrder["startrow"];
				$colcount = (int)$arrMyOrder["colcount"];
				$rowcount = (int)$arrMyOrder["rowcount"];
			}else{
				$startcol = (int)$_GET["sc"];
				$startrow = (int)$_GET["sr"];
				$colcount = (int)$_GET["cc"];
				$rowcount = (int)$_GET["rc"];
			}

			$imagesrc = "/".PATH."gridimages/".htmlspecialchars($_GET["in"]);
			$href = htmlspecialchars((0 === strpos($_GET["hr"],"http"))?$_GET["hr"]:"http://".$_GET["hr"]);
			$introduction = str_replace("\n", "\\n", htmlspecialchars($_GET["idt"]));
			if ($startcol>0 &&
				$startrow>0 &&
				$colcount>0 &&
				$rowcount>0 &&
				$imagesrc!="") {
				$require_script[] = "\$(function(){var newspan = \$('.home').initPreview({
					gridposition: [".$startcol.",".$startrow.",".$colcount.",".$rowcount."], 
					imagesrc: \"".$imagesrc."\",
					href: \"".$href."\",
					introduction: \"".$introduction."\",
					GRIDWIDTH: ".GRIDWIDTH."
				});
				newspan.jump({
					interval:3000,
					speed:500,
					times:2,
					height:5
				});
				});";
			}
			require_once("home.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "select"){//加入我们链接页
			$require_css[] = "select";
			$require_js[] = "initaddbutton";
			$require_js[] = "jquery.cookie";
			$require_script[] = "\$(function(){\$('.home').initAddButton({
				APPLYCOUNT:".APPLYCOUNT.",
				gridwidth:".GRIDWIDTH.", 
				dictionary:'".QINIUPATH."'
			});});";
			require_once("service/order.class.php");
			$objOrder = new Order();
			require_once("home.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "apply"){
			require_once("apply.php");
			require_once("common.php");
		}


		else if($_GET["a"] == "admin" && $_GET["town"] == "guxian"){//管理员登陆
			require_once("admin.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "adlist"){//管理列表
			require_once("adlist.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "addetail"){//管理详细
			require_once("addetail.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "price"){//价格管理
			require_once("price.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "adfaq"){//FAQ管理
			require_once("adfaq.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "adcomm"){//留言管理
			require_once("adcomm.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "delimage"){//清除垃圾图片
			require_once("delimage.php");
			require_once("common.php");
		}
		else if($_GET["a"] == "download"){//下载网站数据
			require_once("download.php");
		}
		else if($_GET["a"] == "ajax"){//ajax
			require_once("ajax.php");
		}
		else if($_GET["a"] == "code"){//验证码
			require_once($_SERVER['DOCUMENT_ROOT']."/".PATH."code.php");
		}
		else if($_GET["a"] == "adexit"){//管理员退出
			unset($_SESSION["ADMUSER"]);
			header("Location:/");
		}
		else if($_GET["a"] == "mgexit"){//前台退出
			unset($_SESSION["member"]);
			header("Location:/index.php?a=login");
		}
		else{//无定义情况,跳转到首页
			header("Location:/");
		}
	}else{//首页
		require_once("service/order.class.php");
		$objOrder = new Order();
		require_once("home.php");
		require_once("common.php");
	}
?>
