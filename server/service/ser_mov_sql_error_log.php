<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.operation_record.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
//每天一次-將sql_error_log.log搬成sql_error_log_YYMMDD.log
/*
  *確認有沒有log檔
    沒有log就結束了
    已經有當日的log就結束了
  *複製檔案
  *更改檔案權限
  *刪除檔案
*/
$sService="mov_sql_error_log";
$sType='S';
log_set_service($sService,$sType);
$t1=uti_get_timestmp();
init_service();
$t2=uti_get_timestmp();
$exe_time=round(($t2-$t1)*1000);
$sType='O';
log_set_service($sService,$sType,$exe_time);
function init_service(){
  global $web_cfg;
  $sDate=date('Ymd');
  $sPath=$web_cfg['path'].'text/';
  $sLogFile='sql_error_log.log';
  $sLogFile_daily='sql_error_log_[date].log';
  $sLogFile_daily=str_replace('[date]',$sDate,$sLogFile_daily);
  $sFile=$sPath.$sLogFile;
  $sFile_daily=$sPath.$sLogFile_daily;
  // *確認有沒有log檔
  if(!file_exists($sFile)){return ;}
  if(file_exists($sFile_daily)){return ;}
  // *複製檔案
  $fp=copy($sFile,$sFile_daily);
  if(!$fp){return ;}  
  // *更改檔案權限
  @chmod($sFile_daily,0777);
  // *刪除檔案
  @unlink($sFile);
}
?>