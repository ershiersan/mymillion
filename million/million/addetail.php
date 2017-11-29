<?php
    if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        //die($_SESSION["ADMUSER"].":".ADMUSER);
        header("Location:/");
    }else{
    	require_once("service/order.class.php");
	    $objOrder = new Order();
	    $orderid = $_GET["id"];
        $arrMyOrder = $objOrder->getOneOrderOnlyById($orderid);

        if ($arrMyOrder["id"] > 0) {
        	if($_GET["post"] == 1){
        		$arrNewArray["status"] = $_POST["status"];
        		$arrNewArray["refusereason"] = $_POST["refusereason"];
		        date_default_timezone_set('PRC');
		        $arrNewArray["editdate"] = date('Y-m-d H:i:s');
		        $rsUpdate = $objOrder->updateOrderById($orderid, $arrNewArray);
		        if ($rsUpdate) {
		        	$common_area = "<script type='text/javascript'>alert('操作成功！');/*window.opener=null;window.open('','_self');*/window.close();window.opener.location.reload();</script>";
		        }else{
		        	$common_area = "<script type='text/javascript'>alert('操作失败！');window.opener=null;window.open('','_self');window.close()/*location.href='index.php?a=adlist'*/;</script>";
		        }
        	}else{
        	$require_css[] = "apply";
        	$require_script[] = '
			function trim(str){ //删除左右两端的空格
				return str.replace(/(^\s*)|(\s*$)/g, "");
			}
        	$(function(){
        		$("#submit").click(function(){
        			$("#refusereason").val(trim($("#refusereason").val()));
        			if($("input[name=status]:checked").val() == "2" && $("#refusereason").val()==""){
        				alert("请输入拒绝理由")
        				return false;
        			}else{
        				if(!confirm("确定提交？")){
        					return false;
        				}
        			}
        		});
        	});
        	';
		    $lang["selectpic"] = "logo图片";
		    $lang["href"] = "链接网址";
		    $lang["title"] = "网站标题";
		    $lang["introduction"] = "网站简介";
		    $lang["payment"] = "付款金额";
		    $lang["status"] = "状态";
		    $lang["refusereason"] = "拒绝理由";
			$lang["colon"] = "：";
			$lang["status1"] = "未审核";
			$lang["status2"] = "拒绝";
			$lang["status3"] = "通过";
			$lang["status0"] = "作废";
			$lang["submit"] = "提交";
			require_once("secondnav.php");
        	$common_area = $adNavigator."<span class='mainright'>".'
			<span class="applyarea">
			<form action="index.php?a=addetail&post=1&id='.$orderid.'" method="post">
				';
			$common_area .= '<table class="table">
					<tr><td class="left">'.$lang["selectpic"].$lang["colon"].'</td>
						<td>
							<div><a href="/'.PATH.'gridimages/'.$arrMyOrder["imageguid"].'" target="_blank"><img id="imagelogo" style="border:0px;display:inline-block;width:'.($arrMyOrder["colcount"]*GRIDWIDTH).'px;height:'.($arrMyOrder["rowcount"]*GRIDWIDTH).'px;" src="/'.PATH.'gridimages/'.$arrMyOrder["imageguid"].'" /></a></div>
						</td>
					</tr>
					<tr><td class="left">'.$lang["href"].$lang["colon"].'</td>
						<td>
							'.htmlspecialchars($arrMyOrder["href"]).'
						</td>
					</tr>
					<tr><td class="left">'.$lang["title"].$lang["colon"].'</td>
						<td>
							'.htmlspecialchars($arrMyOrder["title"]).'
						</td>
					</tr>
					<tr><td class="left">'.$lang["introduction"].$lang["colon"].'</td>
						<td>
							'.htmlspecialchars($arrMyOrder["introduction"]).'
						</td>
					</tr>


					<tr><td class="left">'.$lang["payment"].$lang["colon"].'</td>
						<td>
							<!--input name="payment" id="payment" maxlength="10" style="width:50px" type="text" value="'.$arrMyOrder["payment"].'"/-->￥'.$arrMyOrder["payment"].'/￥'.$arrMyOrder["amount"].'
							'.(/*需要付款且已经付款的*/((int)$arrMyOrder["payment"])?"<a href='index.php?a=sendgoods&id=".$orderid."'>确认发货</a>":"").'
						</td>
					</tr>
					<tr><td class="left">'.$lang["status"].$lang["colon"].'</td>
						<td>
							<label><input type="radio" name="status" value="1" '.($arrMyOrder["status"]=="1"?'checked="checked"':'').'/>'.$lang["status1"].'</label>
							<label><input type="radio" name="status" value="3" '.($arrMyOrder["status"]=="3"?'checked="checked"':'').'/>'.$lang["status3"].'</label>
							<label><input type="radio" name="status" value="2" '.($arrMyOrder["status"]=="2"?'checked="checked"':'').'/>'.$lang["status2"].'</label>
							<label><input type="radio" name="status" value="0" '.($arrMyOrder["status"]=="0"?'checked="checked"':'').'/>'.$lang["status0"].'</label>
						</td>
					</tr>
					<tr><td class="left">'.$lang["refusereason"].$lang["colon"].'</td>
						<td>
							<textarea name="refusereason" id="refusereason" rows="4" cols="30" maxlength="200">'.$arrMyOrder["refusereason"].'</textarea>
						</td>
					</tr>
					<tr><td colspan="2" class="applybutton">
						<input type="submit" id="submit" value="'.$lang["submit"].'"/>
					</td></tr>

				</table>
			</form>
			</span>
			</span>
			';
			}
        }else{
        	$common_area = "<script type='text/javascript'>alert('订单不存在');history.go(-1);</script>";
        }
    }
?>