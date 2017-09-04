<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.operation_record.php');
//新增昨天的開獎結果-快樂8
/*
	*從網站上抓目前這期的開獎結果
	*整理出各玩法 所需資料
	*依照玩法處理出解果
	*整理成資料庫的語法格式
		判斷格式有沒有錯誤
			錯誤跳過
		判斷是否重複
			重複跳過
	*新增開獎結果
	*產生目前球路
	*產生目前遺漏
*/
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$sGame='kb';
$sService="ins_result_$sGame".'_last';
$sType='S';
log_set_service($sService,$sType);
$t1=uti_get_timestmp();
init_service($sGame);
$t2=uti_get_timestmp();
$exe_time=round(($t2-$t1)*1000);
$sType='O';
log_set_service($sService,$sType,$exe_time);
function init_service($sGame,$date=''){
	$ret='';
	$yesterday=($date=='')?date('Y-m-d',strtotime("-1 day")):$date;
	ser_chg_yesterday_result($sGame,$yesterday);
}
?>