<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.operation_record.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$sGame='pk';
$sService="ser_chk_updatr_site_table_$sGame";
$sType='S';
log_set_service_freq($sService,$sType);
$t1=uti_get_timestmp();
init_service($sGame);
$t2=uti_get_timestmp();
$exe_time=round(($t2-$t1)*1000);
$sType='O';
log_set_service_freq($sService,$sType,$exe_time);
//將各個站這期結果 塞入站台結果表中  用於決定要開哪一家結果
function init_service($sGame,$date=''){
	$ret='';
	ser_chk_update_site_result_table($sGame);
}
?>