<?php
if($_GET["post"] == 1){
	require_once("service/comments.class.php");
	$objComments = new Comments();
	if ($_SESSION['code'] != $_POST['code']) {
		$common_area = "<script type='text/javascript'>alert('验证码错误');location.href='index.php?a=comments';</script>";
	}else if (trim($_POST['title']) == "") {
		$common_area = "<script type='text/javascript'>alert('标题不能为空');location.href='index.php?a=comments';</script>";
	}else if (trim($_POST['content']) == "") {
		$common_area = "<script type='text/javascript'>alert('内容不能为空');location.href='index.php?a=comments';</script>";
	}else{//可正常提交
		$arrNewArray["title"] = $_POST["title"];
	    $arrNewArray["content"] = $_POST["content"];
	    $arrNewArray["username"] = $_SESSION["member"];
	    date_default_timezone_set('PRC');
		$arrNewArray["datetime"] = date('Y-m-d H:i:s');
		$idComments = $objComments->insertComments($arrNewArray);
		if ($idComments) {
			$common_area = "<script type='text/javascript'>alert('提交成功，审核通过后方可显示');location.href='index.php?a=comments';</script>";
		}else{
			$common_area = "<script type='text/javascript'>alert('提交失败');location.href='index.php?a=comments';</script>";
		}
	}
	unset($_SESSION['code']);	//避免重复提交
}else{
	$require_css[] = $require_js[] = "comments";
	$require_css[] = "page";
	$common_area = '<span class="comments">';
	$common_area .= '<h2>留言板</h2>';
	require_once("service/comments.class.php");
	$objComments = new Comments();
	$arrComments = $objComments->getAllRecord();
	//print_r($arrComments);

	/*翻页参数*/
	$current_page = (int)$_GET["page"];
	!isset($current_page) && $current_page = 0;
	$page_rows = 10;
	/*翻页参数*/

	$common_area .= '<div class="listcomments">';
	if (is_array($arrComments) && count($arrComments) > 0) {
		foreach ($arrComments as $keyComments => $valueComments) {
			/*翻页控制*/
			if (!($current_page*$page_rows<=$keyComments && ($current_page+1)*$page_rows>$keyComments)) {
				continue;
			}
			/*翻页控制*/
			$common_area .= '<div class="listtitle">';
			$common_area .= htmlspecialchars($valueComments["title"]);
			$common_area .= '</div>';
			$common_area .= '<div class="listcontent">';
			$common_area .= '<span>'.($valueComments["username"]?htmlspecialchars($valueComments["username"]):"匿名")."</span>";
			$common_area .= ' @ '.$valueComments["datetime"]."<br>";
			$common_area .= htmlspecialchars($valueComments["content"]);
			$common_area .= '</div>';
			if (trim($valueComments["replay"]) != "") {
				$common_area .= '<div class="listreplay">';
				$common_area .= '<span>管理员</span>';
				$common_area .= ' REPLY@ '.$valueComments["rdatetime"]."<br>";
				$common_area .= htmlspecialchars($valueComments["replay"]);
				$common_area .= '</div>';
			}
		}
	}else {
		$common_area .= '暂无留言';
	}
	/*翻页显示*/
	require_once("service/page.php");
	count($arrComments) > 0 && $common_area .= '<div class="divpage">'.ShowPageGuide($current_page, $page_rows, count($arrComments), "/index.php?a=comments")."</div>";
	/*翻页显示*/
	$common_area .= '</div>';

	$common_area .= '<div class="newcomments">';
	$common_area .= '<div class="newctitle">';
	$common_area .= '发表留言';
	$common_area .= '</div>';
	$common_area .= '<div class="newcbody">';
	$common_area .= '<form action="index.php?a=comments&post=1" method="post"><table>';
	$common_area .= '<tr><td class="left">标题：</td><td><input type="text" name="title" id="title"/></td></tr>';
	$common_area .= '<tr><td class="left">内容：</td><td><textarea rows="7" name="content" id="content"></textarea></td></tr>';
	$common_area .= '<tr><td class="left">验证码：</td><td><input type="text" name="code" id="verify"/><img title="看不清楚？换一张" id="imgverify" src="index.php?a=code&t='.time().'" /></td></tr>';
	$common_area .= '<tr><td class="left"></td><td><input id="submit" type="submit" value="提交"/></td></tr>';
	$common_area .= '</table></form>';
	$common_area .= '</div>';

	$common_area .= '</div>';

	$common_area .= '</span>';
}
?>