<?php

ini_set('display_errors', 1); //顯示錯誤訊息
error_reporting(E_ALL); //錯誤回報

include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.api.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
init();
//*取結果相關 測試用執行檔
function init(){
	$test=$_GET['c'];
	switch($test){
		case 1:
			break;
		case 2:
			break;
		case 3:
			$sGame=$_GET['g'];
			$ary=mke_lottery_num_list_cp908($sGame);
			//$ary2=mke_lottery_num_list_168new($sGame);
			
			echo '<xmp>';
			print_r($ary);
			echo '</xmp>';
			/*
			echo '<xmp>';
			print_r($ary2);
			echo '</xmp>';
			*/
			break;
		default:
			echo "命令列 \n";
			echo '$_GET[c]=命令'."\n";
			$ary=array(
				'1'=>'測試開獎流程'
			);
			print_r($ary);
			break;
	}
}
?>