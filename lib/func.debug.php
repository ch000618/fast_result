<?php 
//測試用的所有函式
function debug_html($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
//計算秒數用,回傳現在的精確秒數
function getNowTime(){
	$time = explode(" ", microtime());
	$usec = (double)$time[0];
	$sec = (double)$time[1];
	return $sec + $usec;
}
?>