<?php
	$rsEffectiveOrder = $objOrder->getAllEffectiveOrders();
	$common_area = '<span class="home">';
	if(count($rsEffectiveOrder) > 0){
		foreach($rsEffectiveOrder as $keyOrder=>$valueOrder){
			if ($valueOrder["showstatus"]) {
				//正常显示的格子
				$common_area .= "<span style='background-color:white;width:".($valueOrder["colcount"]*GRIDWIDTH)."px;height:".($valueOrder["rowcount"]*GRIDWIDTH)."px;left:".(($valueOrder["startcol"]-1)*GRIDWIDTH)."px;top:".(($valueOrder["startrow"]-1)*GRIDWIDTH)."px;'>".
				($valueOrder["href"]?"<a target='_blank' href='".htmlspecialchars((0 === strpos($valueOrder["href"],"http"))?$valueOrder["href"]:"http://".$valueOrder["href"])."'>":"").
				"<img src='".QINIUPATH."gridimages/".$valueOrder["imageguid"]."' title=\"".htmlspecialchars($valueOrder["introduction"])."\"/>".
				($valueOrder["href"]?"</a>":"").
				"</span>";
			}else{
				//显示锁定的格子
				$common_area .= "<span title='正在通过审核' style='width:".($valueOrder["colcount"]*GRIDWIDTH)."px;height:".($valueOrder["rowcount"]*GRIDWIDTH)."px;left:".(($valueOrder["startcol"]-1)*GRIDWIDTH)."px;top:".(($valueOrder["startrow"]-1)*GRIDWIDTH)."px;background-image:url(".QINIUPATH."images/lock.png)'><span></span></span>";
			}
			
		}
	}
	$common_area .= '</span>';
?>
