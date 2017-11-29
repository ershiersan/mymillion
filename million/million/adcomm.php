<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
		header("Location:/");
    }
    else{
    	if ($_GET["post"] == 1) {
    		$id = $_GET["id"];
    		if($id != ""){	//update
	    		$arrNewArray["status"] = ($_POST["status"]?"1":"0");
	    		$arrNewArray["replay"] = $_POST["replay"];
	    		$arrNewArray["rdatetime"] = date('Y-m-d H:i:s', time());
		    	require_once("service/comments.class.php");
				$objComments = new Comments();
			   	if ($objComments->updateCommentsById($id, $arrNewArray)) {
			   		$common_area = "<script type='text/javascript'>alert('更新成功');history.go(-1);</script>";
			   	}else{
			   		$common_area = "<script type='text/javascript'>alert('更新失败');history.go(-1);</script>";
			   	}
    		}
    	}else{
    		$require_css[] = "adcomm";
    		$require_css[] = "page";
    		$require_js[] = "adcomm";
    		require_once("service/comments.class.php");
		    $objComments = new Comments();
		    $arrComments = $objComments->getAllRecord("all");

			/*翻页参数*/
			$current_page = (int)$_GET["page"];
			!isset($current_page) && $current_page = 0;
			$page_rows = 10;
			/*翻页参数*/

        	require_once("secondnav.php");
		    $common_area = $adNavigator."<span class='mainright'>";
			$common_area .= "<span class='adcomm'><table>";
			$common_area .= "<tr class='trhead'>
					<td>留言标题</td>
					<td>留言内容</td>
					<td>留言时间</td>
					<td>状态</td>
					<td>回复</td>
					<td></td>
				</tr>
				";

		    if (is_array($arrComments) && count($arrComments) > 0) {
		    	foreach ($arrComments as $keyComments => $valueComments) {
				/*翻页控制*/
				if (!($current_page*$page_rows<=$keyComments && ($current_page+1)*$page_rows>$keyComments)) {
					continue;
				}
				/*翻页控制*/
		    	$common_area .= "<tr class='trbody'>
		    	<form action='index.php?a=adcomm&post=1&id=".$valueComments["id"]."' method='post'>
					<td><span class='ctitle'>".htmlspecialchars($valueComments["title"])."</span></td>
					<td><span class='ccontent'>".htmlspecialchars($valueComments["content"])."</span></td>
					<td><span class='datetime'>".htmlspecialchars($valueComments["datetime"])."</span></td>
					<td><span class='status'><input type='checkbox' id='status".$valueComments["id"]."' name='status'".($valueComments["status"]?" checked='checked'":"")."/> <label for='status".$valueComments["id"]."'>通过</label></span></td>
					<td><textarea name=replay rows='3'>".htmlspecialchars($valueComments["replay"])."</textarea></td>
					<td><img faqid='".$valueComments["id"]."' src='".QINIUPATH."images/button_close.png' class='imagebutton del'/> <input type='submit' class='submit' value='提交' /></td>
				</form>
				</tr>
				";
		    	}
		    }

			$common_area .= "</table>";

			/*翻页显示*/
			require_once("service/page.php");
			count($arrComments) > 0 && $common_area .= '<div class="divpage">'.ShowPageGuide($current_page, $page_rows, count($arrComments), "/index.php?a=adcomm")."</div>";
			/*翻页显示*/

			$common_area .= "</span></span>";
    	}
    }
?>
