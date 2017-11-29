<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
		header("Location:/");
    }
    else{
    	if ($_GET["post"] == 1) {
    		$id = $_GET["id"];
    		if($id != ""){	//update
	    		$arrNewArray["startgrid"] = $_POST["startgrid"];
	    		$arrNewArray["endgrid"] = $_POST["endgrid"];
	    		$arrNewArray["price"] = $_POST["price"];
	    		$arrNewArray["description"] = $_POST["description"];
	    		require_once("service/price.class.php");
			   	$objPrice = new Price();
			   	if ($objPrice->updatePriceById($id, $arrNewArray)) {
			   		$common_area = "<script type='text/javascript'>alert('更新成功');location.href='index.php?a=price';</script>";
			   	}else{
			   		$common_area = "<script type='text/javascript'>alert('更新失败');location.href='index.php?a=price';</script>";
			   	}
    		}else{		//insert
				$arrNewArray["startgrid"] = $_POST["startgrid"];
    			$arrNewArray["endgrid"] = $_POST["endgrid"];
    			$arrNewArray["price"] = $_POST["price"];
    			$arrNewArray["description"] = $_POST["description"];
    			require_once("service/price.class.php");
		   		$objPrice = new Price();
		   		if ($objPrice->insertPrice($arrNewArray)) {
		   			$common_area = "<script type='text/javascript'>alert('插入成功');location.href='index.php?a=price';</script>";
		   		}else{
		   			$common_area = "<script type='text/javascript'>alert('插入失败');location.href='index.php?a=price';</script>";
		   		}
    		}
    	}else{
    		$require_css[] = "price";
    		$require_js[] = "price";
    		require_once("service/price.class.php");
		    $objPrice = new Price();
		    $arrPrice = $objPrice->getAllRecord();

        	require_once("secondnav.php");
		    $common_area = $adNavigator."<span class='mainright'>";
			$common_area .= "<span class='price'><table>";
			$common_area .= "<tr class='trhead'>
					<td>起始格子</td>
					<td>结束格子</td>
					<td>价格</td>
					<td>描述</td>
					<td><img id='add' src='".QINIUPATH."images/button_add.png' class='imagebutton'/></td>
				</tr>
				";

		    if (is_array($arrPrice) && count($arrPrice) > 0) {
		    	foreach ($arrPrice as $keyPrice => $valuePrice) {
		    	$common_area .= "<tr class='trbody'>
		    	<form action='index.php?a=price&post=1&id=".$valuePrice["id"]."' method='post'>
					<td><input type='text' name='startgrid' value='".htmlspecialchars($valuePrice["startgrid"])."'/></td>
					<td><input type='text' name='endgrid' value='".htmlspecialchars($valuePrice["endgrid"])."'/></td>
					<td><input type='text' name='price' value='".htmlspecialchars($valuePrice["price"])."'/></td>
					<td><input type='text' name='description' class='description' value='".htmlspecialchars($valuePrice["description"])."'/></td>
					<td><img priceid='".$valuePrice["id"]."' src='".QINIUPATH."images/button_close.png' class='imagebutton del'/> <input type='submit' class='submit' value='提交' /></td>
				</form>
				</tr>
				";
		    	}
		    }else{

		    }

			$common_area .= "</table></span></span>";
    	}
    }
?>
