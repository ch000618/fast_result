<?php
$_SERVER['HTTP_HOST']='console_c';
//服務的設定檔
include_once('sys_config.php');
$website_code = "fast";
$service_code = "fast_server";

$ver=explode('.', PHP_VERSION);
define("php_ver",$ver[0] * 10000 + $ver[1] * 100 + $ver[2]);

define("root_path", 	$web_cfg['path']);
define("conf_path", 	$web_cfg['path_conf']);
define("func_path", 	$web_cfg['path_lib']);

define("svice_path",	root_path."server/");
define("path_Monitor", svice_path."monitor/");
define("path_Trigger", svice_path."trigger/");

define('file_monitor', 'fst_rst_monitor.php');
define('file_trigger', 'fst_rst_crontrigger_v3.php');
define('file_trigger_slow', 'fst_rst_crontrigger_slow.php');
define('ser_runstart', 'start.php');
$ssh_set=array(
	'host'=>'127.0.0.1'
	,'user'=>'rdrd'
	,'pass'=>'rd01672321'
);

define('baseUrl',		  'http://result.sm2.xyz/server/service/');
define('php_cli','php70');
?>
