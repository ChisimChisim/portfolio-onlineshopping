<?php
  function es($data, $charset){
  	if (is_array($data)){
  		return array_map(__METHOD__, $data);
  	}else{
  		return htmlspecialchars($data, ENT_QUOTES, $charset);
  	}
  }
