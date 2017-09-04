<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.operation_record.php');
//漏開結果 補救方法--時時彩
/*
	*檢查那些期數有沒有少結果
	*如果少結果 到對方網站 抓歷史期數開獎結果
	*新增歷史期數開獎結果
*/
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$sGame='ssc';
$sService="ser_inst_drop_result_$sGame";
$sType='S';
log_set_service_freq($sService,$sType);
$t1=uti_get_timestmp();
init_service($sGame);
$t2=uti_get_timestmp();
$exe_time=round(($t2-$t1)*1000);
$sType='O';
log_set_service_freq($sService,$sType,$exe_time);
function init_service($sGame,$date=''){
	$ret='';
	$yesterdaytime=strtotime($date.' 00:00:00')-86400;
	$yesterday=date('Y-m-d',$yesterdaytime);
	ser_chg_night_result($sGame,$yesterday);
}
?>