<?php
//一些公用的小函式
/*
	uti_動詞_名詞()
	*跟資料庫無關
*/
//取得 client ip
function uti_get_ip() {
  $ip='';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}
	if(isset($_SERVER['HTTP_CLIENT_IP'])){$ip=$_SERVER['HTTP_CLIENT_IP'];}
  if($ip== ''){$ip=$_SERVER['REMOTE_ADDR'];}
  $ip=str_replace(' ','',$ip);
	return $ip;
}
//回傳現在時間
//計算秒數用,回傳現在的精確秒數
function uti_get_timestmp(){
	$time = explode(" ", microtime());
	$usec = (double)$time[0];
	$sec = (double)$time[1];
	return $sec + $usec;
}
//回傳秒以下的時間
function uti_get_microsec(){
	$time = explode(" ", microtime());
	$usec = (double)$time[0];
  return $usec;
}
?>