<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
		header("Location:/");
    }
    else{
    	if ($_GET["post"] == 1) {
    		$id = $_GET["id"];
    		if($id != ""){	//update
	    		$arrNewArray["intorder"] = $_POST["intorder"];
	    		$arrNewArray["question"] = $_POST["question"];
	    		$arrNewArray["answer"] = $_POST["answer"];
	    		require_once("service/faq.class.php");
			   	$objFAQ = new FAQ();
			   	if ($objFAQ->updateFAQById($id, $arrNewArray)) {
			   		$common_area = "<script type='text/javascript'>alert('更新成功');history.go(-1);</script>";
			   	}else{
			   		$common_area = "<script type='text/javascript'>alert('更新失败');history.go(-1);</script>";
			   	}
    		}else{		//insert
				$arrNewArray["intorder"] = $_POST["intorder"];
    			$arrNewArray["question"] = $_POST["question"];
    			$arrNewArray["answer"] = $_POST["answer"];
    			require_once("service/faq.class.php");
			   	$objFAQ = new FAQ();
		   		if ($objFAQ->insertFAQ($arrNewArray)) {
		   			$common_area = "<script type='text/javascript'>alert('插入成功');location.href='index.php?a=adfaq';</script>";
		   		}else{
		   			$common_area = "<script type='text/javascript'>alert('插入失败');location.href='index.php?a=adfaq';</script>";
		   		}
    		}
    	}else{
    		$require_css[] = "adfaq";
    		$require_js[] = "adfaq";
    		require_once("service/faq.class.php");
		    $objFAQ = new FAQ();
		    $arrFAQ = $objFAQ->getAllRecord();

        	require_once("secondnav.php");
		    $common_area = $adNavigator."<span class='mainright'>";
			$common_area .= "<span class='adfaq'><table>";
			$common_area .= "<tr class='trhead'>
					<td>排序</td>
					<td>问题</td>
					<td>答案</td>
					<td><img id='add' src='".QINIUPATH."images/button_add.png' class='imagebutton'/></td>
				</tr>
				";

		    if (is_array($arrFAQ) && count($arrFAQ) > 0) {
		    	foreach ($arrFAQ as $keyFAQ => $valueFAQ) {
		    	$common_area .= "<tr class='trbody'>
		    	<form action='index.php?a=adfaq&post=1&id=".$valueFAQ["id"]."' method='post'>
					<td><input type='text' name='intorder' class='intorder' value='".htmlspecialchars($valueFAQ["intorder"])."'/></td>
					<td><textarea name='question' rows='3' class='question'>".htmlspecialchars($valueFAQ["question"])."</textarea></td>
					<td><textarea name='answer' rows='3' class='answer'>".htmlspecialchars($valueFAQ["answer"])."</textarea></td>
					<td><img faqid='".$valueFAQ["id"]."' src='".QINIUPATH."images/button_close.png' class='imagebutton del'/> <input type='submit' class='submit' value='提交' /></td>
				</form>
				</tr>
				";
		    	}
		    }

			$common_area .= "</table></span></span>";
    	}
    }
?>
