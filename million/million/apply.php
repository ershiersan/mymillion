<?php
	function getIP() { 
		if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
		else if (@$_SERVER["HTTP_CLIENT_IP"]) $ip = $_SERVER["HTTP_CLIENT_IP"]; 
		else if (@$_SERVER["REMOTE_ADDR"]) $ip = $_SERVER["REMOTE_ADDR"]; 
		else if (@getenv("HTTP_X_FORWARDED_FOR"))$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (@getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
		else if (@getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
		else $ip = "Unknown"; 
		return $ip; 
	}
	/*if ($_SESSION["member"] == "") {//没登录，先去登录
        $common_area = "<script type='text/javascript'>location.href='index.php?a=login&l=".urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."';</script>";
    }else */if($_GET["post"] == 1){
    	$startrow = (int)$_POST["startrow"];
    	$startcol = (int)$_POST["startcol"];
    	$rowcount = (int)$_POST["rowcount"];
    	$colcount = (int)$_POST["colcount"];
    	if ((!$startrow > 0) || (!$startcol > 0) || (!$rowcount > 0) || (!$colcount > 0)) {
    		$common_area = "<script type='text/javascript'>alert('参数异常，请重新选择');history.go(-1);</script>";//location.href='index.php?a=select';
    	}else{
    		require_once("service/order.class.php");
	    	$objOrder = new Order();
	        if($objOrder->checkAddableRecByPoint($startcol, $startrow, $colcount, $rowcount)){
	        	//允许加入！！
	        	$arrSituation = $objOrder->checkOrderSituation($_SESSION["member"], getIP());
	        	if ($arrSituation["issoldout"]) {
	        		//售完
	        		$common_area = "<script type='text/javascript'>alert('抱歉，已售完');location.href='/';</script>";
	        	}else{
	        		$arrOrderForUpdate = array();
	        		$arrOrderForUpdate["username"] = $_SESSION["member"];
	        		$arrOrderForUpdate["startrow"] = $startrow;
	        		$arrOrderForUpdate["startcol"] = $startcol;
	        		$arrOrderForUpdate["rowcount"] = $rowcount;
	        		$arrOrderForUpdate["colcount"] = $colcount;
	        		$arrOrderForUpdate["postip"] = getIP();
	        		$arrOrderForUpdate["imageguid"] = $_POST["imgname"];
	        		$arrOrderForUpdate["href"] = $_POST["href"];
	        		$arrOrderForUpdate["introduction"] = $_POST["introduction"];
	        		$arrOrderForUpdate["title"] = $_POST["title"];
	        		$arrOrderForUpdate["status"] = 1;
	        		date_default_timezone_set('PRC');
					$arrOrderForUpdate["postdate"] = date('Y-m-d H:i:s');


	        		switch ($arrSituation["authority"]) {
	        			case 'special':	//特殊会员，一直免费
	        				$arrOrderForUpdate["hasfreegrid"] = 1;
	        				$arrOrderForUpdate["chargecount"] = 0;
	        				$arrOrderForUpdate["price"] = 0;

	        				break;
	        			case 'free':	//免费会员，限FREEAPPLYCOUNT个免费
	        				if ($arrSituation["freeperiod"]) {	//还在免费期间
	        					$arrOrderForUpdate["hasfreegrid"] = 1;
	        					if ($rowcount*$colcount > FREEAPPLYCOUNT) {
		        					//超出免费的个数
		        					$arrOrderForUpdate["chargecount"] = $rowcount*$colcount-FREEAPPLYCOUNT;
			        				$arrOrderForUpdate["price"] = $arrSituation["price"]["next"];
		        				}else{
			        				$arrOrderForUpdate["chargecount"] = 0;
			        				$arrOrderForUpdate["price"] = 0;
			        			}
	        				}else{
	        					//按正常收费
	        					$arrOrderForUpdate["hasfreegrid"] = 0;
	        					$arrOrderForUpdate["chargecount"] = $rowcount*$colcount;
	        					$arrOrderForUpdate["price"] = $arrSituation["price"]["current"];
	        				}
	        				
	        				break;
	        			case 'charge':	//正常收费
	        				$arrOrderForUpdate["hasfreegrid"] = 0;
	        				if ($arrSituation["freeperiod"]) {	//还在免费期间
	        					$arrOrderForUpdate["chargecount"] = $rowcount*$colcount;
	        					$arrOrderForUpdate["price"] = $arrSituation["price"]["next"];
	        				}else{
	        					$arrOrderForUpdate["chargecount"] = $rowcount*$colcount;
	        					$arrOrderForUpdate["price"] = $arrSituation["price"]["current"];
	        				}
	        				break;
	        		}
	        		$arrOrderForUpdate["amount"] = $arrOrderForUpdate["chargecount"]*$arrOrderForUpdate["price"];
	        		$idReturn = $objOrder->insertOrderByArray($arrOrderForUpdate);
	        		require_once("secondnav.php");
	        		if ($_SESSION["member"] == "") {
	        			$common_area = $mgNavigator."<span class='mainright' style='font-size:12px;'>"."下单完成";
		        		// $arrOrderForUpdate["amount"] && $common_area .= "，<a target='_blank' href='/index.php?a=pay&id=".$idReturn."'>去支付</a>";
					// 去掉支付
		        		$common_area .= "</span>";
	        		} else {
		        		$common_area = $mgNavigator."<span class='mainright' style='font-size:12px;'>"."下单完成，管理<a href='/index.php?a=mglist' style='color:#3b59ff;text-decoration:underline;'>我的格子</a>";
		        		// $arrOrderForUpdate["amount"] && $common_area .= "，或者<a target='_blank' href='/index.php?a=pay&id=".$idReturn."'>去支付</a>";
					// 去掉支付
		        		$common_area .= "</span>";
		        	}
                                require_once("service/sendmail.class.php");
                                $strMail = "Count: ".($rowcount*$colcount).", Rows: {$rowcount}, Cols: {$colcount}, Time: ".(date('Y-m-d H:i:s', time())).".";
                                $mail = new MySendMail();
                                // $mail->setServer("smtp@126.com", "XXXXX@126.com", "XXXXX"); //设置smtp服务器，普通连接方式
                                $mail->setServer("smtp.qq.com", "dingyalei22@qq.com", "jvnbahaxkocqcacg", 465, true); //设置smtp服务器，到服务器的SSL连接
                                $mail->setFrom("dingyalei22@qq.com"); //设置发件人
                                $mail->setReceiver("dingyalei22@126.com"); //设置收件人，多个收件人，调用多次
                                // $mail->setCc("XXXX"); // 设置抄送，多个抄送，调用多次
                                // $mail->setBcc("XXXXX"); // 设置秘密抄送，多个秘密抄送，调用多次
                                // $mail->addAttachment("XXXX"); // 添加附件，多个附件，调用多次
                                $mail->setMail("Grids apply comming!!!", $strMail); // 设置邮件主题、内容
                                ob_start();
                                $mail->sendMail(); //发送
                                ob_end_clean();
                    // exec("echo 'Count: ".($rowcount*$colcount).", Rows: {$rowcount}, Cols: {$colcount}, Time: ".(date('Y-m-d H:i:s', time())).".' | mail -s 'Grids apply comming!!!' dingyalei &> /dev/null &");

	        	}

	        }else{
	        	$common_area = "<script type='text/javascript'>alert('不能从已被占用的格子加入');location.href='index.php?a=select';</script>";
	        }
    	}
    }else{
    	$_GET["left"] = (int)$_GET["left"];
    	$_GET["top"] = (int)$_GET["top"];
    	$_GET["width"] = (int)$_GET["width"];
    	$_GET["height"] = (int)$_GET["height"];
    	if (!($_GET["left"] > 0) || !($_GET["top"] > 0) || !($_GET["width"] > 0) || !($_GET["height"] > 0)) {
    		//参数不全就调回选择页
    		$common_area = "<script type='text/javascript'>alert('参数异常，请重新选择');location.href='index.php?a=select';</script>";
    	}else{
	    	require_once("service/order.class.php");
	    	$objOrder = new Order();
	        if($objOrder->checkAddableRecByPoint($_GET["left"], $_GET["top"])){
	        	/*进来后首先判断会员的资格和价格、免费情况
	        	 *是否免费期间
	        	 *不免费的价格
	        	 *会员类型（特殊权限，可免费，不可免费）
	        	 */
	        	$arrSituation = $objOrder->checkOrderSituation($_SESSION["member"], getIP());

	        	if ($arrSituation["issoldout"]) {
	        		//售完
	        		$common_area = "<script type='text/javascript'>alert('抱歉，已售完');location.href='/';</script>";
	        		
	        	}else{
	        		/*echo $_SESSION["member"];
	        		print_r($arrSituation);*/
	        		$require_css[] = "apply";
	        		$require_js[] = "initapplypage";
	        		$require_js[] = "ajaxfileupload";
					$require_script[] = "
		\$(function(){\$('.applyarea').initApplyPage({".(isset($arrSituation["freeperiod"])?"
			freeperiod:".$arrSituation["freeperiod"].",":"").(isset($arrSituation["price"]["current"])?"
			current:".$arrSituation["price"]["current"].",":"").(isset($arrSituation["price"]["next"])?"
			next:".$arrSituation["price"]["next"].",
			FREEAPPLYCOUNT:".FREEAPPLYCOUNT.",":"")."
			APPLYCOUNT:".APPLYCOUNT.",
			GRIDWIDTH:".GRIDWIDTH.",
			authority:'".$arrSituation["authority"]."'
		});});
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
								.attr('src','/".PATH."gridimages/'+data.file_name)
								.width(parseInt($('#colcount').val())*".GRIDWIDTH.")
								.height(parseInt($('#rowcount').val())*".GRIDWIDTH.")
								.show();
							$('#imgname').val(data.file_name);
							$('#hasimg').val('true');
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
		}";

		        	$lang["apply"] = "申请我的格子";
		        	$lang["colon"] = "：";
		        	$lang["startat"] = "起始位置";
		        	$lang["row"] = " 行 ";
		        	$lang["col"] = " 列 ";
		        	$lang["price"] = "单价";
		        	$lang["rowcount"] = "格子行数";
		        	$lang["colcount"] = "格子列数";
		        	$lang["gridcount"] = "格子总数";
		        	$lang["chargegridcount"] = "收费格子数";
		        	$lang["chargeprice"] = "收费单价";
		        	$lang["amount"] = "总价";
		        	$lang["paymentmethod"] = "支付方式";
		        	$lang["selectpic"] = "选择logo图片";
		        	$lang["href"] = "链接网址";
		        	$lang["title"] = "网站标题<br/>（只是给站长看滴）";
		        	$lang["introduction"] = "网站简介<br/>（鼠标指向的时候会显示哦）";
		        	$lang["preview"] = "预览";
		        	$lang["submit"] = "提交";
		        	$lang["yuan"] = "￥";
		        	$lang["upload"] = "上传";
		        	$lang["chargeprompt"] = "最多为您免费".FREEAPPLYCOUNT."个格子，其余每格按(￥1)收费";
		        	$lang["reminderfree"] = "温馨提示：每ip地址和会员只能免费申请一次，请谨慎选择";
		        	$lang["remindercharge"] = "温馨提示：您的ip地址或会员已有申请记录，不能再享受免费申请";
		        	$lang["reminderlogin"] = "<br>温馨提示：为维护方便，建议<a href='/index.php?a=login' target='_blank'>登录</a>后继续操作";


		        	$selectOptions1 = "";
		        	for ($i=1; $i <= 100; $i++) { 
		        		$selectOptions1 .= '<option value="'.$i.($_GET["height"]==$i?'" selected="selected':"").'">'.$i.'</option>';
		        	}
		        	$selectOptions2 = "";
		        	for ($i=1; $i <= 100; $i++) { 
		        		$selectOptions2 .= '<option value="'.$i.($_GET["width"]==$i?'" selected="selected':"").'">'.$i.'</option>';
		        	}

		        	require_once("secondnav.php");
		        	$common_area = $mgNavigator."<span class='mainright'>".'
	<span class="applyarea">
		<span class="applytitle">'.$lang["apply"].'</span>
		<span class="reminder" '.($arrSituation["freeperiod"]?"":'style="display:none;"').'>'.
			($arrSituation["freeperiod"] && $arrSituation["authority"] == "charge"?$lang["remindercharge"]:"").
			($arrSituation["freeperiod"] && $arrSituation["authority"] == "free"?$lang["reminderfree"]:"").
			($_SESSION["member"] == ""?$lang["reminderlogin"]:"")
		.'</span>
		<form action="index.php?a=apply&post=1" method="post">
		<table class="table">
			<tr>
				<td class="left">'.$lang["startat"].$lang["colon"].'</td>
				<td>
					'.$_GET["top"].$lang["row"].$_GET["left"].$lang["col"].'
					<input type="hidden" name="startrow" id="startrow" value="'.$_GET["top"].'"/>
					<input type="hidden" name="startcol" id="startcol" value="'.$_GET["left"].'"/>
				</td>
			</tr>
			<tr><td class="left">'.$lang["price"].$lang["colon"].'</td><td>
				'.$lang["yuan"].
				($arrSituation["freeperiod"] && $arrSituation["authority"] == "charge"?//免费期间，并且已经有单，直接使用收费的价格
				(isset($arrSituation["price"]["next"])?$arrSituation["price"]["next"]:'0.00'):
				(isset($arrSituation["price"]["current"])?$arrSituation["price"]["current"]:'0.00')
				)
				.'
			</td></tr>
			<tr><td class="left">'.$lang["rowcount"].$lang["colon"].'</td>
				<td>
					<select name="rowcount" id="rowcount">
						'.$selectOptions1.'
					</select>
				</td>
			</tr>
			<tr><td class="left">'.$lang["colcount"].$lang["colon"].'</td>
				<td>
					<select name="colcount" id="colcount">
						'.$selectOptions2.'
					</select>
				</td>
			</tr>
			<tr><td class="left">'.$lang["gridcount"].$lang["colon"].'</td>
				<td><span id="count">1</span></td>
			</tr>
			<tr class="charge chargeprompt"><td colspan="2" class="prompt">'.$lang["chargeprompt"].'</td></tr>
			<tr class="charge"><td class="left">'.$lang["chargegridcount"].$lang["colon"].'</td>
				<td>
					<span id="chargecount"></span>
				</td>
			</tr>
			<tr class="charge"><td class="left">'.$lang["chargeprice"].$lang["colon"].'</td>
				<td>
					'.$lang["yuan"].'<span id="price"></span>
				</td>
			</tr>
			<tr><td class="left">'.$lang["amount"].$lang["colon"].'</td>
				<td>'.$lang["yuan"].'<span id="amount">'.(
					$arrSituation["freeperiod"] && $arrSituation["authority"] == "charge"?//免费期间，并且已经有单，直接使用收费的价格
					(isset($arrSituation["price"]["next"])?$arrSituation["price"]["next"]:'0.00'):
					(isset($arrSituation["price"]["current"])?$arrSituation["price"]["current"]:'0.00')
					).'</span></td>
			</tr>'./*((//价格为0就不显示
					$arrSituation["freeperiod"] && $arrSituation["authority"] == "charge"?//免费期间，并且已经有单，直接使用收费的价格
					(isset($arrSituation["price"]["next"])?$arrSituation["price"]["next"]:'0'):
					(isset($arrSituation["price"]["current"])?$arrSituation["price"]["current"]:'0')
					)?*/'
			<!--<tr><td class="left">'.$lang["paymentmethod"].$lang["colon"].'</td>
				<td>'.'<span><img src="/'.PATH.'alipay/payment/images/alipay.gif"></span></td>
			</tr>-->'/*:'')*/.'
			<tr><td class="left">'.$lang["selectpic"].$lang["colon"].'</td>
				<td>
					<input type="hidden" name="hasimg" id="hasimg"/>
					<input type="hidden" name="imgname" id="imgname"/>
					<input id="fileimg" type="file" name="fileimg"/>　文件格式限jpeg，bmp，jpg，png
					<!-- <input id="ajaxupload" type="button" value="'.$lang["upload"].'" onclick="ajaxfileupload()"/> -->
					<div><img id="imagelogo" /></div>
				</td>
			</tr>
			<tr><td class="left">'.$lang["href"].$lang["colon"].'</td>
				<td>
					<input name="href" id="href" maxlength="100" type="text"/>
				</td>
			</tr>
			<tr><td class="left">'.$lang["title"].$lang["colon"].'</td>
				<td>
					<input name="title" id="title" maxlength="200" type="text"/>
				</td>
			</tr>
			<tr><td class="left">'.$lang["introduction"].$lang["colon"].'</td>
				<td>
					<textarea name="introduction" id="introduction" rows="5" cols="40" maxlength="200"></textarea>
				</td>
			</tr>

			<tr><td colspan="2" class="applybutton">
				<input type="button" id="preview" value="'.$lang["preview"].'"/>
				<input type="submit" id="submit" value="'.$lang["submit"].'"/>
			</td></tr>
		</table>
		</form>
	</span>
	</span>
	        	';
	        	}
	        }else{
	        	$common_area = "<script type='text/javascript'>alert('不能从已被占用的格子加入');location.href='index.php?a=select';</script>";
    		}
		}
    }
?>
