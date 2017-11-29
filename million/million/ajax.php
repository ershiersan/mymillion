<?php
	//根据r(request)区分需求

	/*请求用户名唯一性
	 *返回用户名个数
	 */
	if($_GET["r"] == "useruniq"){
		$username = $_POST["username"];
		require_once("service/member.class.php");
		$objMember = new Member();
		$rsCount = $objMember->getMemberMsgByUsername($username);
		echo count($rsCount);
	}
	/*请求验证码是否正确
	 *返回0或1
	 */
	if($_GET["r"] == "checkcode"){
		$sessioncode = $_SESSION['code'];
		$postcode = $_POST["code"];
		if(strtolower($sessioncode) == strtolower($postcode)){
			echo "1";
		}else{
			echo "0";
		}
	}
	/*请求是否允许加入
	 *返回0或1
	 */
	if($_GET["r"] == "checkaddable"){
		require_once("service/order.class.php");
	    $objOrder = new Order();
		$startrow = (int)$_POST["startrow"];
    	$startcol = (int)$_POST["startcol"];
    	$rowcount = (int)$_POST["rowcount"];
    	$colcount = (int)$_POST["colcount"];
		if($objOrder->checkAddableRecByPoint($startcol, $startrow, $colcount, $rowcount)){
			echo "1";
		}else{
			echo "0";
		}
	}

	/*请求删除price记录
	 *返回success或default
	 */
	if($_GET["r"] == "delprice"){
		$id = $_POST["id"];
    	require_once("service/price.class.php");
		$objPrice = new Price();
		if ($objPrice->deletePriceById($id)) {
			die("success");
		}else{
			die("default");
		}
	}

	/*请求删除faq记录
	 *返回success或default
	 */
	if($_GET["r"] == "delfaq"){
		$id = $_POST["id"];
    	require_once("service/faq.class.php");
		$objFAQ = new FAQ();
		if ($objFAQ->deleteFAQById($id)) {
			die("success");
		}else{
			die("default");
		}
	}

	/*请求删除留言记录
	 *返回success或default
	 */
	if($_GET["r"] == "delcomm"){
		$id = $_POST["id"];
    	require_once("service/comments.class.php");
		$objComments = new Comments();
		if ($objComments->deleteCommentsById($id)) {
			die("success");
		}else{
			die("default");
		}
	}

	/*请求被占用格子的信息
	 *返回json数组
	 */
	if($_GET["r"] == "usedgrid"){
		require_once("service/order.class.php");
	    $objOrder = new Order();
		echo json_encode($objOrder->getAllUsedGrid());
	}
?>