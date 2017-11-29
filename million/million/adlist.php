<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        header("Location:/");
    }else{
    	$require_css[] = "page";
    	$require_css[] = "mglist";
    	require_once("service/order.class.php");
	    $objOrder = new Order();
        $arrMyOrder = $objOrder->getAllOrder($_GET["notaudit"]);

        require_once("secondnav.php");
		$common_area = $adNavigator."<span class='mainright'>";
		$common_area .= "<span class='mylist'><table>";
		$common_area .= "<tr class='trhead'>
				<td>申请时间</td>
				<td>用户名</td>
				<td>申请信息</td><!--起始行列，行列数-->
				<td>收费格子数</td>
				<td>收费单价</td>
				<td>付款金额</td>
				<td>申请状态<br><span style='display:inline-block;width:100px;height:20px;'><form id='formnotaudit' action='/index.php'><input type='hidden' name='a' value='adlist'><input type='checkbox' id='notaudit' name='notaudit' ".($_GET["notaudit"]?"checked='checked'":"")." onclick='formnotaudit.submit()'/><label for='notaudit'>未审核</label></form></span></td><!--等待付款<付款按钮>/正审核/拒绝/通过审核/作废-->
				<td>操作</td><!--维护格子，继续购买-->
			</tr>
			";

		/*翻页参数*/
		$current_page = (int)$_GET["page"];
		!isset($current_page) && $current_page = 0;
		$page_rows = 10;
		/*翻页参数*/

		if (count($arrMyOrder) > 0) {
			foreach ($arrMyOrder as $keyMyOrder => $valueMyOrder) {
				/*翻页控制*/
				if (!($current_page*$page_rows<=$keyMyOrder && ($current_page+1)*$page_rows>$keyMyOrder)) {
					continue;
				}
				/*翻页控制*/
				$statusOperation = "<a href='#' onclick='window.open(\"index.php?a=addetail&id=".$valueMyOrder["id"]."\");'>操作</a>";
				if ($valueMyOrder["payment"] == $valueMyOrder["amount"] || $valueMyOrder["status"] == 0) {
					switch ($valueMyOrder["status"]) {
						case '1':
							$statusPrompt = "正审核";
							break;
						case '2':
							$statusPrompt = "审核拒绝";
							break;
						case '3':
							$statusPrompt = "通过审核";
							break;
						case '0':
							$statusPrompt = "作废";
							break;
					}
				}else{
					$statusPrompt = "等待付款";
				}
				$common_area .= "<tr class='trbody'>
				<td><span class='date'>".$valueMyOrder["postdate"]."</span></td>
				<td><span class='date'>".htmlspecialchars($valueMyOrder["username"])."</span></td>
				<td><span class='date'>起始位置：".$valueMyOrder["startrow"]."行".$valueMyOrder["startcol"]."列<br>行列数：".$valueMyOrder["rowcount"]."行".$valueMyOrder["colcount"]."列</span></td><!--起始行列，行列数-->
				<td><span class='date'>".$valueMyOrder["chargecount"]."</span></td>
				<td><span class='date'>￥".$valueMyOrder["price"]."</span></td>
				<td><span class='date'>￥".$valueMyOrder["amount"]."</span></td>
				<td><span class='date'>"./*<!--等待付款<付款按钮>/正审核/拒绝/通过审核/作废-->*/
					$statusPrompt
				."</span></td>
				<td><span class='date'>"./*<!--维护格子，继续申请-->*/
					$statusOperation.
				"</span></td>
			</tr>
			";
			}
		}else{
			$common_area .= "<tr class='trprompt'><td colspan='8'>暂无申请记录</td></tr>";
		}

		$common_area .= "</table>";
		/*翻页显示*/
		require_once("service/page.php");
		count($arrMyOrder) > 0 && $common_area .= ShowPageGuide($current_page, $page_rows, count($arrMyOrder), "/index.php?a=adlist");
		/*翻页显示*/
		$common_area .= "</span></span>";
    }
?>