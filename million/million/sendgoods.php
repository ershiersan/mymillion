<?php
$orderId = (int)$_GET["id"];
if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        header("Location:/");
}
else if (!$orderId) {
	$common_area = "<script type='text/javascript'>alert('参数错误！');history.go(-1);</script>";
}else{
	require_once("service/order.class.php");
        include_once("config.php");
	$objOrder = new Order();
	$arrOrder = $objOrder->getOneOrderOnlyById($orderId);
	if (!isset($arrOrder["username"])) {//没有记录
		$common_area = "<script type='text/javascript'>alert('无效的订单！');history.go(-1);</script>";
	}else{
                require_once("alipay/sendgoods/alipay.config.php");
                require_once("alipay/sendgoods/lib/alipay_submit.class.php");

                /**************************请求参数**************************/

                        //支付宝交易号
                        $trade_no = $arrOrder['alipayid'];
                        //必填

                        //物流公司名称
                        $logistics_name = "虚拟商品，无需配送";
                        //必填

                        //物流发货单号

                        $invoice_no = "";
                        //物流运输类型
                        $transport_type = "POST";
                        //三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）


                /************************************************************/

                //构造要请求的参数数组，无需改动
                $parameter = array(
                                "service" => "send_goods_confirm_by_platform",
                                "partner" => trim($alipay_config['partner']),
                                "trade_no"      => $trade_no,
                                "logistics_name"        => $logistics_name,
                                "invoice_no"    => $invoice_no,
                                "transport_type"        => $transport_type,
                                "_input_charset"        => trim(strtolower($alipay_config['input_charset']))
                );

                //建立请求
                $alipaySubmit = new AlipaySubmit($alipay_config);

                $html_text = $alipaySubmit->buildRequestHttp($parameter);
                //解析XML
                //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
                $doc = new DOMDocument();
                $doc->loadXML($html_text);

                //请在这里加上商户的业务逻辑程序代码

                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

                //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

                //解析XML
                if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
                        $alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
                        $common_area = $alipay;
                }
	}
}
?>