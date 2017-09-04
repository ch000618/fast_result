<?php
ignore_user_abort(true);
set_time_limit(0);
//狀態3 將開獎結果新增到資料庫
/*
*檢查狀態
*如果狀態是3
*新增開獎結果
*狀態改成1
*/
function inst_lt_result($sGame,$date=''){
	$ret='';
	//檢查狀態
	$status=ser_get_result_status($sGame);
	//*如果狀態是3
	if($status=='3'){
			$sType='S';
			$sService="inst_lt_result_$sGame";
			log_set_service_freq($sService,$sType);
			$t1=uti_get_timestmp();
		  $aNow_draws=dws_get_now_draws_info($sGame);
			//*新增開獎結果
			$today=($date=='')?$aNow_draws['rpt_date']:date('Y-m-d');
			$rst_status=init_inst_lt_rst($sGame,$today);
			//*號碼有進資料庫狀態改成1
			if($rst_status===true){
				$status='1';
				ser_update_result_status($sGame,$status);	
			}
			$t2=uti_get_timestmp();
			$exe_time=round(($t2-$t1)*1000);
			$sType='O';
			log_set_service_freq($sService,$sType,$exe_time);
	}
}
//狀態2 將開獎結果新增到資料庫
/*
*20170407 因為改抓新版結果 只會兩種狀態
*檢查狀態
*如果狀態是2
*新增開獎結果
*狀態改成1
*/
function inst_lt_result_v2($sGame,$date=''){
	$ret='';
	//檢查狀態
	$status=ser_get_result_status($sGame);
	//*如果狀態是2
	if($status=='2'){
			$sType='S';
			$sService="inst_lt_result_$sGame";
			log_set_service_freq($sService,$sType);
			$t1=uti_get_timestmp();
		  $aNow_draws=dws_get_now_draws_info($sGame);
			//*新增開獎結果
			$today=($date=='')?$aNow_draws['rpt_date']:date('Y-m-d');
			$rst_status=init_inst_lt_rst_168new($sGame,$today);
			//*號碼有進資料庫狀態改成1
			if($rst_status==1){
				$status='1';
				ser_update_result_status($sGame,$status);	
			}
			$t2=uti_get_timestmp();
			$exe_time=round(($t2-$t1)*1000);
			$sType='O';
			log_set_service_freq($sService,$sType,$exe_time);
	}
}
//狀態2 將開獎結果新增到資料庫
/*
*20170407 因為改抓新版結果 只會兩種狀態
*檢查狀態
*如果狀態是2
*新增開獎結果
*狀態改成1
*/
function inst_lt_result_v3($sGame,$date=''){
	$ret='';
	//檢查狀態
	$status=ser_get_result_status($sGame);
	//*如果狀態是2
	if($status=='2'){
			$sType='S';
			$sService="inst_lt_result_$sGame";
			log_set_service_freq($sService,$sType);
			$t1=uti_get_timestmp();
		  //$aNow_draws=dws_get_now_draws_info($sGame);
			//*新增開獎結果
			//$today=($date=='')?$aNow_draws['rpt_date']:date('Y-m-d');
			$rst_status=init_inst_lt_rst_rank($sGame);
			//*號碼有進資料庫狀態改成1
			if($rst_status==1){
				$status='1';
				ser_update_result_status($sGame,$status);	
			}
			$t2=uti_get_timestmp();
			$exe_time=round(($t2-$t1)*1000);
			$sType='O';
			log_set_service_freq($sService,$sType,$exe_time);
	}
}
//手動開獎
/*
	*從獎號中心上抓目前這期的開獎結果
	*整理出各玩法 所需資料
	*依照玩法處理出結果
	*整理成資料庫的語法格式
		判斷格式有沒有錯誤
			錯誤跳過
		判斷是否重複
			重複跳過
	*新增開獎結果
*/
function inst_award_mo_rst($sGame,$date=''){
	$ret='';
	$today=$_POST['rpt_date'];
	$iGtype=dws_sel_gtype($sGame);
	// *如果今天休息就不用做了
	$bRest=dws_chk_game_rest($iGtype,$today);
	if($bRest===true){return $ret;}
	$bHas_result=inst_award_mo_result($sGame,$today);
}
//手動開獎 轉換站台結果
/*
	*從獎號中心上抓目前這期的開獎結果
	*整理出各玩法 所需資料
	*依照玩法處理出結果
	*整理成資料庫的語法格式
		判斷格式有沒有錯誤
			錯誤跳過
		判斷是否重複
			重複跳過
	*新增開獎結果
*/
function inst_award_chg_rst($sGame){
	$ret='';
	$today=$_POST['rpt_date'];
	$draws_num=$_POST['draws_num'];
	$site=$_POST['site'];
	$iGtype=dws_sel_gtype($sGame);
	// *如果今天休息就不用做了
	$bRest=dws_chk_game_rest($iGtype,$today);
	if($bRest===true){return $ret;}
	$bHas_result=inst_award_chg_result($sGame,$site,$draws_num);
}
//確認是否到開獎時間--每十秒檢查一次
/*
如果有就把狀態資料庫改成2
*/
function get_lt_time($sGame,$date=''){
	$ret='';
	$status=ser_get_result_status($sGame);
	if($status=='1'){
		$get_lt_time=ser_get_lt_time($sGame);
		if($get_lt_time==''){return false;}
		$lt_time=strtotime($get_lt_time);
		$now_time=time();
		if($now_time>$lt_time){
			$sType='S';
			$sService="chk_lt_time_$sGame";
			log_set_service_freq($sService,$sType);
			$t1=uti_get_timestmp();
			$status='2';
			ser_update_result_status($sGame,$status);
			$t2=uti_get_timestmp();
			$exe_time=round(($t2-$t1)*1000);
			$sType='O';
			log_set_service_freq($sService,$sType,$exe_time);
		}
	}
}
//狀態2 檢查開獎期數是否有變動
/*
有變動就把狀態改成3
*/
function chk_lt_num($sGame,$date=''){
	$ret='';
	$status=ser_get_result_status($sGame);
	if($status=='2'){
		$last_result=ser_get_last_result_name($sGame);
		$lt_num=ser_get_lt_num($sGame);
		if(count($lt_num)<1){return;}
		if($lt_num['c_t']!=$last_result){
			$sType='S';
			$sService="chk_lt_num_$sGame";
			log_set_service_freq($sService,$sType);
			$t1=uti_get_timestmp();
			//*狀態改成3
			$status='3';
			ser_update_result_status($sGame,$status);
			$t2=uti_get_timestmp();
			$exe_time=round(($t2-$t1)*1000);
			$sType='O';
			log_set_service_freq($sService,$sType,$exe_time);
		}
	}
}
//補生成期數
/*
	*檢查有沒有 生成過
	*如果今天休息就不用做了
	*從資料庫抓出期數設定
	*抓出前一天最後一期
	*用期數設定產生當天的期數資料陣列
	*新增期數
	*記錄當天最後一期期數名稱
*/
function ser_ins_lack_draws($sGame,$date=''){
	$ret='';
	$sRpt_date=($date=='')?date('Y-m-d'):$date;
	$iGtype=dws_sel_gtype($sGame);
	// *如果今天休息就不用做了
	$bRest=dws_chk_game_rest($iGtype,$sRpt_date);
	if($bRest===true){return $ret;}
	$dws_exist=dws_chk_draws($sGame,$sRpt_date);
	if($dws_exist==true){
		echo '期數已存在';
		return $ret;
	}
	// *從資料庫抓出期數設定
	$aDraws_def=dws_get_draws_inst_def($iGtype,$sRpt_date);
	// *抓出前一天最後一期
	$aLast_draws_data=dws_get_last_data($iGtype,$sRpt_date);
	$sLast_draws_num=$aLast_draws_data['draws_num'];
	// *用期數設定產生當天的期數資料陣列
	$aDraws_data=dws_mke_draws_data_list($sRpt_date,$aDraws_def,$sLast_draws_num);
	// *新增期數
	dws_ins_draws($sGame,$aDraws_data);
	// *記錄當天最後一期期數名稱
	$c=count($aDraws_data);
	$sLast_draws_num=$aDraws_data[($c-1)]['draws_num'];
	$aDate_set=array(
		array(
			 'rpt_date'=>$sRpt_date
			,'draws_num'=>$sLast_draws_num
		)
	);
	dws_ins_date_draws_num($iGtype,$aDate_set);
}
//新增期數
/*
	*如果今天休息就不用做了
	*從資料庫抓出期數設定
	*抓出前一天最後一期
	*用期數設定產生當天的期數資料陣列
	*新增期數
	*記錄當天最後一期期數名稱
*/
function ser_ins_draws($sGame,$date=''){
	$ret='';
	$sRpt_date=($date=='')?date('Y-m-d'):$date;
	$iGtype=dws_sel_gtype($sGame);
	// *如果今天休息就不用做了
	$bRest=dws_chk_game_rest($iGtype,$sRpt_date);
	if($bRest===true){return $ret;}
	// *從資料庫抓出期數設定
	$aDraws_def=dws_get_draws_inst_def($iGtype,$sRpt_date);
	// *抓出前一天最後一期
	$aLast_draws_data=dws_get_last_data($iGtype,$sRpt_date);
	$sLast_draws_num=$aLast_draws_data['draws_num'];
	// *用期數設定產生當天的期數資料陣列
	$aDraws_data=dws_mke_draws_data_list($sRpt_date,$aDraws_def,$sLast_draws_num);
	// *新增期數
	dws_del_draws($sGame,$sRpt_date);
	dws_ins_draws($sGame,$aDraws_data);
	// *記錄當天最後一期期數名稱
	$c=count($aDraws_data);
	$sLast_draws_num=$aDraws_data[($c-1)]['draws_num'];
	$aDate_set=array(
		array(
			 'rpt_date'=>$sRpt_date
			,'draws_num'=>$sLast_draws_num
		)
	);
	dws_ins_date_draws_num($iGtype,$aDate_set);
}
?>
