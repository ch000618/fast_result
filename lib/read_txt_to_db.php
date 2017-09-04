<?php
$_SERVER['HTTP_HOST']='console_c';
include('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func_result_drop_v2.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$sGame=$_POST['g'];
$sRpt_date=$_POST['d'];
if($sRpt_date==''){echo '請輸入日期'; return;}
//讀文字檔 並寫入db
//對一段時間的文字檔作新增到資料庫的動作
//init();
function init(){
	$sDate_e=date('Y-m-d');
	$iStmp_s=strtotime($sDate_s.' 00:00:00');
	$iStmp_e=strtotime($sDate_e.' 00:00:00');
	$iStmp=$iStmp_s;
	while($iStmp<$iStmp_e){
		$date=date('Y-m-d',$iStmp);
		txt_chg_drop_result($game,$date);
		$iStmp+=86400;
		//sleep(3);
	}
}
exec_read_result_txt_inst_db($sGame,$sRpt_date);
function exec_read_result_txt_inst_db($sGame,$sRpt_date){
	//echo '1234';
	txt_chg_drop_result_v2($sGame,$sRpt_date);
	//$yesterdaytime=strtotime($sRpt_date.' 00:00:00')-86400;
	//$yesterday=date('Y-m-d',$yesterdaytime);
	//ser_chg_yesterday_result($sGame,$sRpt_date,$yesterday);
}
//開獎紀錄文字檔讀出來 轉成陣列
function file_to_array($game,$date){
	global $web_cfg;
	$fp = fopen($web_cfg['path_text']."result_$game/$game"."_$date.txt", 'r');
	//將整個檔案讀出 由於不知道這個檔案有多大 先給他1000k
	$contents=fread($fp,(1024*1000));
	//文字檔內容的是json 將json轉成陣列
	$obj=json_decode($contents,true);
	//echo $contents;
	fclose($fp);
	return $obj;
}
//檢查漏開結果 補救方法
/*
1.檢查那些期數有沒有少結果 
	*抓出今天到現在為止的所有期數序號
	*抓出今天到現在為止有開出獎號的期數序號
	*交叉1跟2找出不一樣的,就是需要重新要結果的期數序號,取得這些序號的期數名稱
2.如果少結果 到對方網站 抓歷史期數開獎結果
3.新增歷史期數開獎結果
*/
function txt_chg_drop_result($sGame,$sRpt_date){
	$ret="";
	$draws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
	echo "目前有結果最後一期是第".$draws_sn."期 <br>";
	$aDraws_nums=array();
	//1.抓出今天到現在為止的所有期數序號
	$draws=ser_get_now_draws($sGame,$draws_sn,$sRpt_date);
	//2.抓出今天到現在為止有開出獎號的期數序號
	$result=ser_get_now_result($sGame,$draws_sn,$sRpt_date);
	//3.交叉1跟2找出不一樣的,就是需要重新要結果的期數序號,取得這些序號的期數名稱
	foreach($draws as $key => $value){
		if(!in_array($value,$result)){$aDraws_nums[]=$value;}
	}
	$data=file_to_array($sGame,$sRpt_date);
	$hislist_date=mke_hislist_date_list_v2($sGame,$data,$aDraws_nums);
	inst_hislist_result($sGame,$hislist_date);
}
//檢查漏開結果 補救方法
/*
1.刪除 重複期數獎號 
2.新增 文字檔的開獎結果
*/
function txt_chg_drop_result_v2($sGame,$sRpt_date){
	$ret="";
	$draws_sn=ser_get_last_draws_seq($sGame,$sRpt_date);
	//echo "目前有結果最後一期是第".$draws_sn."期 <br>";
	//1.抓出今天到現在為止的所有期數序號
	$aDraws=ser_get_now_draws($sGame,$draws_sn,$sRpt_date);
	$del_st=del_repeat_result_v3($sGame,$aDraws);
	if($del_st!=true){ 
		echo "獎號刪除 失敗 無法使用文字檔開獎 \n";
		return;
	}
	//確認這些期數有沒有結果
	//如果沒有缺期數會是空陣列
	$data=file_to_array($sGame,$sRpt_date);
	//print_r($data);
	$hislist_date=mke_hislist_date_list_v2($sGame,$data,$aDraws);
	//print_r($hislist_date);
	$ret=inst_hislist_result($sGame,$hislist_date);
	if($ret!=1){ 
		echo "獎號文字檔新增 失敗 \n";
	}
	echo "獎號文字檔新增 成功 \n";
}
?>