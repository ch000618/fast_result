<?php
include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'func.pub.var.php');
include_once($web_cfg['path_lib'].'func.draws_result.php');
include_once($web_cfg['path_lib'].'func.draws.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
init2();
function init(){
	$game='ssc';
	$lottery_array=get_lottery_array_v3($game);
	$lottery_num=ser_get_lt_num($game);
	print_r($lottery_array);
	print_r($lottery_num);
}
function init2(){
	$game='ssc';
	$date='2017-03-06';
	init_inst_lt_rst($game,$date);
}
?>