<?php
//過濾所有從client傳送過來的東西
function proFilter(){
  proFilterAry($_GET);
  proFilterAry($_POST);
  proFilterAry($_COOKIE);
}
//過濾陣列
function proFilterAry(&$ary){
  foreach($ary as $k => $v){
    if(!is_array($v)){
      $ary[$k]=proFilterStr($v);
    }else{
      proFilterAry($v);
    }
  }
}
//過濾字串
function proFilterStr($str){
	$str=trim($str);
	$regstr = addslashes($str);
	$hasCC = preg_match("/[xA1-xFE]/",$str); //判斷是否有中文字
	if(!$hasCC){$str=strtolower($str);}
	//符號
	$str = str_replace("'","",$str);
	//json的原因 註解
	//$str = str_replace('"',"",$str);
	$str = str_replace('#',"",$str);
	$str = str_replace('`',"",$str);
	$str = str_replace('=',"",$str);
	//關鍵字
	$str = preg_replace("/\s?or\s+/i","",$str);
	$str = preg_replace("/\s?drop\s+/i","",$str);
	$str = preg_replace("/\s?insert\s+/i","",$str);
	$str = preg_replace("/\s?select\s+/i","",$str);
	$str = preg_replace("/\s?delete\s+/i","",$str);
	$str = preg_replace("/\s?update\s+/i","",$str);
	$str = preg_replace("/\s?database\s+/i","",$str);
	$str = preg_replace("/\s?truncate\s+/i","",$str);
	return $str;
}
?>