<?php
ini_set('display_errors', 1); //顯示錯誤訊息
error_reporting(E_ALL); //錯誤回報
$_SERVER['HTTP_HOST']='console_c';
include('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.write_result_txt.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
//產生文字檔主流成
if($_POST['n']==1){init();}
if($_POST['n']==2){init2();}
//把開獎歷史抓回來寫成文字檔 --某段時間
function init2(){
	global $web_cfg;
	$game=$_POST['g'];
	$date=$_POST['d'];
	if($date==''){echo '請輸入日期'; return;}
	$iGap=15;
	$aDate=array();
	for($i=$iGap;$i>=0;$i--){
		$aDate[]=date('Y-m-d',strtotime($date."-$i day"));
	}
	foreach($aDate as $k => $sDate){
		$txt=mke_hislist_num_list_168new($game,$sDate);
		if(count($txt)<1){return;}
		$fp = fopen($web_cfg['path_text']."result_$game"."/$game"."_$sDate.txt", 'w');
		fwrite($fp, json_encode($txt));
		@chmod($web_cfg['path_text']."result_$game/$game"."_$sDate.txt",0777);
		echo "$sDate 文字檔已產生 \n";
	}
}
//把開獎歷史抓回來寫成文字檔 某一天
function init(){
	global $web_cfg;
	$game=$_POST['g'];
	$date=$_POST['d'];
	if($date==''){echo '請輸入日期'; return;}
	$txt=mke_hislist_num_list_168new($game,$date);
	if(count($txt)<1){return;}
	$fp = fopen($web_cfg['path_text']."result_$game"."/$game"."_$date.txt", 'w');
	fwrite($fp, json_encode($txt));
	@chmod($web_cfg['path_text']."result_$game/$game"."_$date.txt",0777);
	echo "$date 文字檔已產生 \n";
}
?>