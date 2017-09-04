<?php
ini_set('display_errors', 1); //顯示錯誤訊息
ini_set('log_errors', 1); //錯誤log 檔開啟
$ssh_set=array(
	'host'=>'127.0.0.1'
	,'user'=>'rdrd'
	,'pass'=>'rd01672321'
);
/*
$db_set=array(
	 'db01'=>array()
	,'db02'=>array()
	,'db03'=>array()
	,'db05'=>array()
	,'db11'=>array()
	,'db12'=>array()
	// ,'61.216.38.153'=>array()
	// ,'54.199.248.67'=>array()
);
*/
/*
foreach($db_set as $k => $v){
	$tmp_set=$ssh_set;
	$tmp_set['host']=$k;
	$db_set[$k]=$tmp_set;
}
*/
include('class.ssh.php');
//測試ssh
init();
function init(){
	//$t1=getNowTime();
	// init0();
	init1();
	//init2();
	//$t2=getNowTime();
	//echo phpinfo();
	//echo "[".round(($t2-$t1),4)."]";
}
//測試連線
function init0(){
	global $ssh_set;
	print_r($ssh_set);
	$ssh=new php_ssh($ssh_set['host'],$ssh_set['user'],$ssh_set['pass']);
	print_r($ssh);
}
//測試下命令
function init1(){
	global $ssh_set;
	// print_r($ssh_set);
	$ssh=new php_ssh($ssh_set['host'],$ssh_set['user'],$ssh_set['pass']);
	$cmd='top -b -n 1 |grep mysqld';
	// print_r($ssh);	
	$ssh->exec($cmd);
	$ret=$ssh->get_result();
	echo $ret;
	//$top_msg=arrange_top($ret);
	//$cpu=get_mysql_CPU($top_msg);
	//echo $cpu;
}
//測試抓所有DB的CPU
function init2(){
	global $db_set;
	$ret=array();
	$sshs=array();
	$cmd='top -b -n 1 |grep mysqld';
	foreach($db_set as $k => $ssh_set){
		$sshs[$k]=new php_ssh($ssh_set['host'],$ssh_set['user'],$ssh_set['pass']);
		$sshs[$k]->exec($cmd);
		$return=$sshs[$k]->get_result();
		$top_msg=arrange_top($return);
		$cpu=get_mysql_CPU($top_msg);		
		$ret[$k]=$cpu;
	}
	echo '<xmp>';
	print_r($ret);
	echo '</xmp>';
}
//回傳mysql的 CPU LOAD
/*
	回傳:CPU%
*/
function get_mysql_CPU($ary){
	foreach($ary as $k => $v){
		if($v[1]!='mysql'){continue;}
		return $v[8];
	}
}
?>