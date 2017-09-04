<?php
ini_set('display_errors', 1); //顯示錯誤訊息
error_reporting(E_ALL); //錯誤回報
//date_default_timezone_set("Asia/Taipei");

include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
include_once($web_cfg['path_lib'].'func.api.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
init();
function init(){
	$aResult_Err=chk_all_game_result_yesterday();
	$aRET[]='<table  style="border:4px #0000ff solid;" cellpadding="3" border="0">';
	$aRET[]='<tr><th>昨日開獎狀態</th></tr>';
	foreach($aResult_Err as $k => $v){
		$aRET[]='<tr><td>'.$v.'</td></tr>';
	}
	$aRET[]='</table>';
	$sRET=implode(" \n",$aRET);
	echo $sRET;
}
function chk_all_game_result_yesterday(){
	$aRet=array();
	$date=date('Y-m-d');
	$aRET=array();
	$yesterdaytime=strtotime($date.' 00:00:00')-86400;
	$sRpt_date=date('Y-m-d',$yesterdaytime);
	//抓出所有遊戲
	$aGame=api_get_all_game();
	$aHis_site_list=array();
	$aHis_dws_result=array();
	$aHis_site_result=array();
	$aHis_dws_result_tmp=array();
	$aSite=array(
		 'un'
		,'168new'
		,'1399p'
		,'98007'
		,'91333'
	);
	foreach($aGame as $k => $sGame){
		//取得所有遊戲的 昨天的最後一期的 期數
		$sDraws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
		//抓出最後一期 以前的所有期數
		$aDraws=ser_get_now_draws($sGame,$sDraws_sn,$sRpt_date);
		//抓出 昨天開獎主表的所有開獎結果
		$aHis_dws_result_tmp=ser_get_hislist_draws_result($sGame,$aDraws);
		if(count($aHis_dws_result_tmp)<1){continue;}
		//整理主表比對用陣列
		foreach($aHis_dws_result_tmp as $index =>$aDws_result){
			//移除 不需要比對的欄位
			unset($aDws_result['lottery_Time']);
			unset($aDws_result['site']);
			unset($aDws_result['total_sum']);
			if($sGame=='pk'){
				unset($aDws_result['total_12']);
			}
			if($sGame=='kb'){
				unset($aDws_result['ball_fp']);
			}
			$aHis_dws_result[$sGame][$aDws_result['draws_num']]=$aDws_result;
		}
		//整理個站台的 結果比對陣列
		foreach($aSite as $key =>$sSite){
			//抓出每個站台 昨天的結果
			$aHis_site_list=ser_get_hislist_list_site_table($sGame,$sSite,$aDraws);
			if(count($aHis_site_list)<1){continue;}
			foreach($aHis_site_list as $index =>$aSite_list){
				//移除 不需要比對的欄位
				unset($aSite_list['lottery_Time']);
				unset($aSite_list['site']);
				unset($aSite_list['total_sum']);
				if($sGame=='kb'){
					unset($aSite_list['ball_fp']);
				}
				$aHis_site_result[$sGame][$sSite][$aSite_list['draws_num']]=$aSite_list;
			}
		}
	}
	if(count($aHis_site_result)<1){
		$aRet[0]="目前沒有結果";
		return $aRet;
	}
	//用主表 的結果跟站台結果表的 各個站台比對結果 有錯就回傳錯誤訊息
	foreach($aHis_site_result as $sGame => $aSite_data){
		foreach($aSite_data as $sSite =>$aData){
			foreach($aData as $sNum => $aBall){
				foreach($aBall as $sCol => $sBall){
					if(isset($aHis_dws_result[$sGame][$sNum][$sCol])){
						if($sBall!=$aHis_dws_result[$sGame][$sNum][$sCol]){
							$aRet[]="昨日".$sGame."_".$sSite."_".$sNum." 期 結果不同 ";
						}
					}
				}
			}
		}
	}
	if(count($aRet)<1){
		$aRet[0]='昨日結果一致';
		return $aRet;
	}
	return $aRet;
}
?>