<?php
//HTML output Secirity class
class p{
  public static $encoding = 'UTF-8';

  public static function h($str)
  {
    echo htmlspecialchars($str, ENT_QUOTES, static::$encoding);

  }

}
?>