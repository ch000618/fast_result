<?php
date_default_timezone_set('Asia/Taipei');
$this_domain=$_SERVER['HTTP_HOST'];
$this_domain=str_replace('http://','',$this_domain);
$this_domain=str_replace('https://','',$this_domain);
//-------- WEB目錄
$web_cfg= array();
$web_cfg['path']=dirname(dirname(__FILE__)).'/';
$web_cfg['path_conf']=$web_cfg['path'].'config/';//設定檔
$web_cfg['path_text']=$web_cfg['path'].'text/';//設定檔
$web_cfg['path_lib']=$web_cfg['path'].'lib/';//函式
//測試用的函式
include_once($web_cfg['path_lib'].'func.debug.php');
//過濾SQL字串
include_once($web_cfg['path_lib'].'func.filter.php');
proFilter();
?>
