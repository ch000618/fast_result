<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.draws.php');
include_once($web_cfg['path_lib'].'func.operation_record.php');
include_once($web_cfg['path_lib'].'func.ser_init.php');
//新增期數-快8
/*
	*如果今天休息就不用做了
	*從資料庫抓出期數設定
	*抓出前一天最後一期
	*用期數設定產生當天的期數資料陣列
	*新增期數
	*記錄當天最後一期期數名稱
*/
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$sGame='kb';
$sService="ins_draws_$sGame";
$sType='S';
log_set_service($sService,$sType);
$t1=uti_get_timestmp();
ser_ins_draws($sGame);
$t2=uti_get_timestmp();
$exe_time=round(($t2-$t1)*1000);
$sType='O';
log_set_service($sService,$sType,$exe_time);
?>