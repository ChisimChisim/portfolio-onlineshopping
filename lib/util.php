<?php
  function es($data, $charset){
  	if (is_array($data)){
  		return array_map(__METHOD__, $data);
  	}else{
  		return htmlspecialchars($data, ENT_QUOTES, $charset);
  	}
  }


//Close $_SESSION
  function killSession(){
	$_SESSION=[];
	if (isset($_COOKIE[session_name()])){
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time()-36000, $params['path']);
	}

	session_destroy();
}
  ?>