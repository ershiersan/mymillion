<?php
    if ($_SESSION["member"] == "") {//没登录，先去登录
        $common_area = "<script type='text/javascript'>location.href='index.php?a=login&l=".urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."';</script>";
    }else{
    	$require_css[] = "page";
    	$require_css[] = "mglist";
    	$require_script[] = "\$(function(){\$('#invalid').click(function(){if(!confirm('确认作废该申请？'))return false;});});";
    	require_once("service/order.class.php");
	    $objOrder = new Order();
        $arrMyOrder = $objOrder->getAllOrderByUsername($_SESSION["member"]);

        require_once("secondnav.php");
		$common_area = $mgNavigator."<span class='mainright'>"."<span class='mylist'><table cellspacing='0' border='0'>";
		$common_area .= "<tr class='trhead'>
				<td width='13%'>申请时间</td>
				<td width='18%'>申请信息</td><!--起始行列，行列数-->
				<td width='10%'>网站标题</td>
				<td width='10%'>格子总数</td>
				<td width='10%'>收费格子数</td>
				<td width='10%'>收费单价</td>
				<td width='10%'>付款金额</td>
				<td width='10%'>申请状态</td><!--等待付款<付款按钮>/正审核/拒绝/通过审核/作废-->
				<td width='10%'>操作</td><!--维护格子，继续购买-->
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
				$statusOperation = "<a href='index.php?a=mgdetail&id=".$valueMyOrder["id"]."'>维护格子</a><br>";
				$statusOperation .= "<a href='index.php?a=select'>继续申请</a>";

				switch ($valueMyOrder["status"]) {
					//先看状态
					case '1':
						if ($valueMyOrder["payment"] == $valueMyOrder["amount"]) {
							$statusPrompt = "正审核";
						}else{
							// $statusPrompt = "等待付款<br><a target='_blank' href='index.php?a=pay&id=".$valueMyOrder["id"]."'>支付</a> <a id='invalid' href='index.php?a=invalid&id=".$valueMyOrder["id"]."'>作废</a>";
							// 取消支付动作
							$statusPrompt = "等待付款<br><a id='invalid' href='index.php?a=invalid&id=".$valueMyOrder["id"]."'>作废</a>";
						}
						break;
					case '2':
						$statusPrompt = "<span";
						$valueMyOrder["refusereason"] != "" && $statusPrompt .= " title='拒绝理由：\r\n".htmlspecialchars($valueMyOrder["refusereason"])."'";
						$statusPrompt .= ">审核拒绝";
						$statusPrompt .= "</span>";
						break;
					case '3':
						$statusPrompt = "通过审核";
						break;
					case '0':
						$statusPrompt = "作废";
						//作废的订单就别看详情了
						$statusOperation = "<a href='index.php?a=select'>继续申请</a>";
						break;
				}

				$common_area .= "<tr class='trbody'>
				<td><span class='date'>".$valueMyOrder["postdate"]."</span></td>
				<td><span class='date'>起始位置：".$valueMyOrder["startrow"]."行".$valueMyOrder["startcol"]."列<br>行列数：".$valueMyOrder["rowcount"]."行".$valueMyOrder["colcount"]."列</span></td><!--起始行列，行列数-->
				<td><span class='date'><a target='_blank' title=\"".htmlspecialchars($valueMyOrder["introduction"])."\" href=\"".htmlspecialchars($valueMyOrder["href"])."\">".htmlspecialchars($valueMyOrder["title"])."</a></span></td>
				<td><span class='date'>".$valueMyOrder["rowcount"]*$valueMyOrder["colcount"]."</span></td>
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
			$common_area .= "<tr class='trprompt'><td colspan='9'>您暂无申请记录，前去<a href='index.php?a=select'>申请</td></tr>";
		}

		$common_area .= "</table>";
		/*翻页显示*/
		require_once("service/page.php");
		count($arrMyOrder) > 0 && $common_area .= ShowPageGuide($current_page, $page_rows, count($arrMyOrder), "/index.php?a=mglist");
		/*翻页显示*/
		$common_area .= "</span></span>";
    }
?>
