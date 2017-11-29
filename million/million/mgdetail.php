<?php
    if ($_SESSION["member"] == "") {//没登录，先去登录
        $common_area = "<script type='text/javascript'>location.href='index.php?a=login&l=".urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."';</script>";
    }
    else{
    	if ($_GET["post"] == 1) {
    		require_once("service/order.class.php");
		    $objOrder = new Order();
		    $orderId = $_GET["id"];
	        $arrMyOrder = $objOrder->getOneOrderById($orderId, $_SESSION["member"]);
	        if (is_array($arrMyOrder) && count($arrMyOrder) > 0) {
	        	/*if ($_POST["href"] == "") {
	        		$common_area = "<script type='text/javascript'>alert('您提交的信息有误');history.go(-1);</script>";
	        	}else*/{
		        	if ($arrMyOrder["imageguid"] != $_POST["imgname"]) {
		        		//更换了图片，删除原图
		        		$delfile =  $_SERVER['DOCUMENT_ROOT']."/".PATH."gridimages/".$arrMyOrder["imageguid"];
		        		if (file_exists($delfile)) {
							$rt = unlink ($delfile);
						}
		        	}
		        	$arrNewArray["imageguid"] = $_POST["imgname"];
		        	$arrNewArray["href"] = $_POST["href"];
		        	$arrNewArray["title"] = $_POST["title"];
		        	$arrNewArray["introduction"] = $_POST["introduction"];
		        	$arrNewArray["status"] = 1;
		        	date_default_timezone_set('PRC');
		        	$arrNewArray["editdate"] = date('Y-m-d H:i:s');
		        	$rsUpdate = $objOrder->updateOrderById($orderId, $arrNewArray);
		        	if ($rsUpdate) {
		        		$common_area = "<script type='text/javascript'>alert('维护成功！');location.href='index.php?a=mglist';</script>";
		        	}else{
		        		$common_area = "<script type='text/javascript'>alert('维护失败！');location.href='index.php?a=mglist';</script>";
		        	}
	        	}
	        }else{
	        	$common_area = "<script type='text/javascript'>alert('订单不存在或不可维护');location.href='index.php?a=mglist';</script>";
	        }
    	}else{
	    	require_once("service/order.class.php");
		    $objOrder = new Order();
		    $orderId = $_GET["id"];
	        $arrMyOrder = $objOrder->getOneOrderById($orderId, $_SESSION["member"]);
	        if (is_array($arrMyOrder) && count($arrMyOrder) > 0) {
	    		$require_css[] = "apply";
		        $require_js[] = "ajaxfileupload";
		        $require_js[] = "mgdetail";
				$require_script[] = "
		function ajaxfileupload(){
			if($('#fileimg').val() == ''){
				alert('请选择图片类型的文件');
				return false;
			}
			$.ajaxFileUpload
			(
				{
					url:'/".PATH."fileupload.php',
					secureuri:false,
					fileElementId:'fileimg',
					dataType: 'json',
					success: function (data)
					{
						if (data.file_status == 'success') {
							$('#imagelogo')
								.attr('src','/".PATH."gridimages/'+data.file_name);
							$('#imgname').val(data.file_name);
							//alert(data.file_infor);
						}else if (data.file_status == 'fault') {
							alert(data.file_infor);
						}
						$('#fileimg').change(function(){
							ajaxfileupload();
						});
					}
				}
			)
			return false;
		}
		$(function(){
			$('#fileimg').change(function(){
				ajaxfileupload();
			});
		});
		";
	        	$lang["apply"] = "维护我的格子";
	        	$lang["colon"] = "：";
	        	$lang["startat"] = "起始位置";
	        	$lang["row"] = " 行 ";
	        	$lang["col"] = " 列 ";
	        	$lang["rowcount"] = "格子行数";
	        	$lang["colcount"] = "格子列数";
		        $lang["gridcount"] = "格子总数";
	        	$lang["selectpic"] = "选择logo图片";
	        	$lang["href"] = "链接网址";
		        $lang["title"] = "网站标题";
	        	$lang["introduction"] = "网站简介";
	        	$lang["preview"] = "预览";
	        	$lang["submit"] = "提交";
	        	$lang["upload"] = "上传";
	        	$lang["prompt"] = "维护提交后需要重新审核，请谨慎维护。";
	        	$lang["prompt1"] = "维护提交后会进入重新审核流程。";

	        	require_once("secondnav.php");
	        	$common_area = $mgNavigator."<span class='mainright'>".'
	<span class="applyarea">
		<span class="applytitle">'.$lang["apply"].'</span>
		'.(/*未审核的状态不出提示*/$arrMyOrder["status"]==1?'':'<span class="reminder">'.
			($arrMyOrder["status"]==2?$lang["prompt1"]:$lang["prompt"])
		.'</span>
		').'<form action="index.php?a=mgdetail&post=1&id='.$orderId.'" method="post">
		<table class="table">
			<tr>
				<td class="left">'.$lang["startat"].$lang["colon"].'</td>
				<td>
					'.$arrMyOrder["startrow"].$lang["row"].$arrMyOrder["startcol"].$lang["col"].'
				</td>
			</tr>
			<tr><td class="left">'.$lang["rowcount"].$lang["colon"].'</td>
				<td>
					<select name="rowcount" id="rowcount" disabled="disabled">
						<option>'.$arrMyOrder["rowcount"].'</option>
					</select>
				</td>
			</tr>
			<tr><td class="left">'.$lang["colcount"].$lang["colon"].'</td>
				<td>
					<select name="colcount" id="colcount" disabled="disabled">
						<option>'.$arrMyOrder["colcount"].'</option>
					</select>
				</td>
			</tr>
			<tr><td class="left">'.$lang["gridcount"].$lang["colon"].'</td>
				<td>
					'.$arrMyOrder["rowcount"]*$arrMyOrder["colcount"].'
				</td>
			</tr>
			<tr><td class="left">'.$lang["selectpic"].$lang["colon"].'</td>
				<td>
					<input type="hidden" name="imgname" id="imgname" value="'.$arrMyOrder["imageguid"].'"/>
					<input id="fileimg" type="file" name="fileimg"/>　文件格式限jpeg，bmp，jpg，png
					<!-- <input id="ajaxupload" type="button" value="'.$lang["upload"].'" onclick="ajaxfileupload()"/> -->
					<div><img id="imagelogo" style="display:inline-block;width:'.($arrMyOrder["colcount"]*GRIDWIDTH).'px;height:'.($arrMyOrder["rowcount"]*GRIDWIDTH).'px;" src="/'.PATH.'gridimages/'.$arrMyOrder["imageguid"].'" /></div>
				</td>
			</tr>
			<tr><td class="left">'.$lang["href"].$lang["colon"].'</td>
				<td>
					<input name="href" id="href" maxlength="100" type="text" value="'.htmlspecialchars($arrMyOrder["href"]).'"/>
				</td>
			</tr>
			<tr><td class="left">'.$lang["title"].$lang["colon"].'</td>
				<td>
					<input name="title" id="title" maxlength="100" type="text" value="'.htmlspecialchars($arrMyOrder["title"]).'"/>
				</td>
			</tr>
			<tr><td class="left">'.$lang["introduction"].$lang["colon"].'</td>
				<td>
					<textarea name="introduction" id="introduction" rows="5" cols="40" maxlength="200">'.htmlspecialchars($arrMyOrder["introduction"]).'</textarea>
				</td>
			</tr>

			<tr><td colspan="2" class="applybutton">
				<input type="hidden" id="orderid" value="'.$orderId.'"/>
				<input type="button" id="preview" value="'.$lang["preview"].'"/>
				<input type="submit" id="submit" value="'.$lang["submit"].'"/>
			</td></tr>
		</table>
		</form>
	</span></span>';


	        }else{
	        	$common_area = "<script type='text/javascript'>alert('订单不存在或不可维护');location.href='index.php?a=mglist';</script>";
	        }
	    }
    }
?>