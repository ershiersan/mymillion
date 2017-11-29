<?php
if (is_array($_FILES) && count($_FILES) > 0) {
	$arrImageType = array(/*"image/gif", */"image/jpeg", "image/bmp", "image/pjpeg", "image/x-png", "image/png");
	if (!in_array($_FILES["fileimg"]["type"], $arrImageType)) {
		echo json_encode(array('file_status'=>'fault', 'file_infor'=>'请选择图片类型的文件'));
	}else if ($_FILES["fileimg"]["size"] > 102400) {
		echo json_encode(array('file_status'=>'fault', 'file_infor'=>'图片大小不能超过100K'));
	}else{
		$arrFileName = explode(".",$_FILES["fileimg"]["name"]);
		//file_put_contents("file.txt", print_r($arrFileName, true));
		$addName = $arrFileName[count($arrFileName)-1];
		$timestamp = time();
		$fileFullName = md5($arrFileName[0].$timestamp).".".$addName;
		$ok = @move_uploaded_file($_FILES['fileimg']['tmp_name'], "gridimages/".$fileFullName);
		if ($ok) {
			echo json_encode(array('file_status'=>'success', 'file_infor'=>'上传成功', 'file_name'=>$fileFullName));
		}else{
			echo json_encode(array('file_status'=>'fault', 'file_infor'=>'上传失败'));
		}
	}
}else{
	echo json_encode(array('file_status'=>'fault', 'file_infor'=>'上传失败'));
}
?>