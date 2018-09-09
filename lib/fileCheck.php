<?php
//Uploaded file check
Class fileCheck{
    
/****File size check for post_max_size(php.ini)****/
	function checkPostMaxSize(){
		$max_size = ini_get('post_max_size');
		$mutiple = 1;
		$unit = substr($max_size, -1);
		if($unit =='M'){
			$mutiple = 1024*1024;
		} elseif ($unit == 'K') {
			$mutiple = 1024;
		}elseif ($unit == 'G') {
			$mutiple = 1024*1024*1024;
		}
		$max_size = substr($max_size, 0, strlen($max_size)-1) * $mutiple;

		if($_SERVER['REQUEST_METHOD'] == 'POST' &&
	          $_SERVER['CONTENT_LENGTH'] > $max_size){
			return false;
		}else{
			return true;
		}
	}

/****Upload file check ****/	
function checkFile($file, $max_size){
	$error_msg = array();
	$ext = '';

	$size = $file['size'];
	$error = $file['error'];
	$img_type = $file['type'];
	$tmp_type = $file['tmp_name'];

	if($error != UPLOAD_ERR_OK){
		if($error == UPLOAD_ERR_NO_FILE){
		}elseif ($error == UPLOAD_ERR_INI_SIZE || 
			     $error == UPLOAD_ERR_FROM_SIZE) {
			$error_msg[] = 'File size is too large';
		} else {
			$error_msg[] = 'File upload error';
		}
		return array(false, $ext, $error_msg);
	}else{
		//GET MIME file type from sent file info
		if($img_type == 'image/gif'){
			$ext = 'gif';
		}elseif ($img_type == 'image/jpeg' || $img_type == 'image/pjpeg') {
			$ext = 'jpg';
		}elseif ($img_type == 'image/png' || $img_type == 'image/x-png') {
			$ext = 'png';
		}

        //GET MIME file type form image file
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$finfoType = $finfo->file($tmp_type);

		//file size check
		if($size == 0){
			$error_msg[] = 'File is empty or not exit';
		}elseif ($size > $max_size) {
			$error_msg[] = 'File is too large';
		}elseif($img_type != $finfoType){
			$error_msg[] = 'MIME type error';
		}elseif ($ext != 'jpg') {
			$error_msg[] = "File type is not 'jpg'";
		}else{
			return array(true, $ext, $error_msg);
		}
	}

	return array(false, $ext, $error_msg);


}

}

?>