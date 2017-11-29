<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        header("Location:/");
    }else{
    	require_once("service/order.class.php");
	    $objOrder = new Order();
        $arrMyOrder = $objOrder->getAllEffectiveImg();
        require_once("secondnav.php");
        $common_area = $adNavigator."<span class='mainright'>";
        foreach ($arrMyOrder as $keyMyOrder => $valueMyOrder) {
        	$myfile = $_SERVER['DOCUMENT_ROOT']."/".PATH."gridimages/".$valueMyOrder;
        	if (file_exists($myfile)) {
				if(unlink ($myfile)){
					$common_area .= "<div>".$valueMyOrder."删除成功</div>";
				}
			}
        }
        $common_area .= "<div>success</div>";
        $common_area .= "</span>";
    }
?>