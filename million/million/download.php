<?php
	if ($_SESSION["ADMUSER"] != ADMUSER) {//没登录，跳到首页，保密地址
        $common_area = "<script type='text/javascript'>location.href='index.php';</script>";
    }else{
    	require_once("service/order.class.php");
	    $objOrder = new Order();
        //$arrMyOrder = $objOrder->getAllEffectiveImg();
        $arrMyOrder = $objOrder->getAllImg();

		require_once("service/zipfile.class.php");
		$dfile =  tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件
		$zip = new zipfile();
		//----------------------
		date_default_timezone_set('PRC');
		$date = date('Y-m-d');
		$filename = 'backup-'.$date.'.zip'; //下载的默认文件名

		$image = array();

		/*图片*/
		if (count($arrMyOrder) > 0) {
			foreach ($arrMyOrder as $keyMyOrder => $valueMyOrder) {
				$image[] = array('image_src' => $_SERVER['DOCUMENT_ROOT']."/".PATH."gridimages/".$valueMyOrder, 'image_name' => $valueMyOrder);
			}
		}

		/*数据库*/
		$arrTableNames = array("member", "order", "price", "faq", "comments");
		foreach ($arrTableNames as $keyTableNames => $valueTableNames) {
			$arrAllRecords = $objOrder->getAllRecords($valueTableNames);
			$zip->add_file(arrToData($arrAllRecords), "table_".$valueTableNames.".txt");
		}
		
		if (count($image) > 0) {
			foreach($image as $v){
			    $zip->add_file(file_get_contents($v['image_src']),  $v['image_name']);
			    // 添加打包的图片，第一个参数是图片内容，第二个参数是压缩包里面的显示的名称, 可包含路径
			    // 或是想打包整个目录 用 $zip->add_path($image_path);
			}
		}

		//----------------------
		$zip->output($dfile);

		// 下载文件
		ob_clean();
		header('Pragma: public');
		header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control:no-store, no-cache, must-revalidate');
		header('Cache-Control:pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding:binary');
		header('Content-Encoding:none');
		header('Content-type:multipart/form-data');
		header('Content-Disposition:attachment; filename="'.$filename.'"'); //设置下载的默认文件名
		header('Content-length:'. filesize($dfile));
		$fp = fopen($dfile, 'r');
		while(connection_status() == 0 && $buf = @fread($fp, 8192)){
		    echo $buf;
		}
		fclose($fp);
		@unlink($dfile);
		@flush();
		@ob_flush();
		exit();
	}

	function arrToData($arrAllRecords){
		$return = "";
		$seperator = "|*|";
		foreach ($arrAllRecords as $keyAllRecords => $valueAllRecords) {
			foreach ($valueAllRecords as $keyvalueAllRecords => $valuevalueAllRecords) {
				$return .= $valuevalueAllRecords.$seperator;
			}
			$return = rtrim($return, $seperator);
			$return .= "\r\n";
		}
		return $return;
	}
?>
