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
$aUse_site=array();
$aUse_site[]='01';//潤達
$aUse_site[]='00';//139369
//-------- 載入設定檔
$aWebHost=array();
$i=0;
foreach($aUse_site as $key =>$v){
	include_once('site_config_'.$v.'.php');
	$aWebHost[$i]=$aSiteHost;
	$aWebHost[$i]['sn']=$v;
	$i++;
}
proFilter();
?>
