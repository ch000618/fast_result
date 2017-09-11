<?php
/*
ini_set('display_errors', 1); //顯示錯誤訊息
error_reporting(E_ALL); //錯誤回報
*/
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
			$sGame=$_GET['g'];
			init_inst_lt_rst_test($sGame);
		break;
		case 2:
			$sGame=$_GET['g'];
			$sRpt_date=date('Y-m-d');
			$sDraws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
			$aDraws=ser_get_now_draws($sGame,$sDraws_sn,$sRpt_date);
			$hislist_list=ser_hislist_list_site_table($sGame,$aDraws);
			echo '<xmp>';
			print_r($hislist_list);
			echo '</xmp>';
		break;
		case 3:
			$sGame=$_GET['g'];
			$ary=ser_get_result_1399p_v2($sGame);
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
		case 4:
			api_view_daily_log();
		break;
		default:
			echo "命令列 \n";
			echo '$_GET[c]=命令'."\n";
			$ary=array(
				'1'=>'測試開獎流程'
				,'2'=>'連鉅 今日所有結果'
			);
			print_r($ary);
		break;
	}
}
//新增開獎結果 168新版
/*
	原本是 抓到後整理格式後 直接進資料庫
	因為 有碰到開獎號碼是錯的
	所以 要先把結果存到站台結果表
	然後 在讀出來 做開獎號碼 比較
	選用 出現最多次的號碼
	
	*整理格式 並且 塞入站台結果表
	*取出所有站台 號碼進行比對 用大數法則 決定要用哪組號碼
	*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	*檢查 有沒有開過獎
	*開獎完畢 把開獎失敗 改為成功
*/
function init_inst_lt_rst_test($sGame){
	$ret=0;
	//*整理格式 並且 塞入站台結果表
	ser_ins_lottery_num_list($sGame);
	//*取出所有站台 號碼進行比對 用大數法則 決定要用哪組號碼
	$lt=ser_lottery_num_list_switch_test($sGame);
	//print_r($lt);
	if(count($lt)<1){return $ret;}
	//*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	$lt_date=mke_lottery_date_list_v2($lt,$sGame);
	$draws_num=$lt_date[0]['draws_num'];
	//*檢查 有沒有開過獎
	$chk_repeat=rst_sel_repeat_result($sGame,$draws_num);
	if($chk_repeat!=''){return $ret;}
	//*開獎完畢 把開獎失敗 改為成功
	$inst_st=inst_lottery_result_v2($sGame,$lt_date);
	$sys_time=date("Y-m-d H:i:s");
	UPDATE_sys_time($sGame,$draws_num,$sys_time);
	//echo $inst_st;
	if($inst_st==2){
		$ret=1;
	}
	return $ret;	
}
//比對開獎結果 
/*
	原本是 抓到後整理格式後 直接進資料庫
	因為 有碰到開獎號碼是錯的
	*取得當前 開獎時間的期數
	*取出這期 所有站台的 做開獎號碼 
	*只要 一個站台撈不到 就不進行比對
	*比對 各站號碼出現次數 
	*選用 出現最多次的號碼
	*補上期數名稱 開獎時間
*/
function ser_lottery_num_list_switch_test($sGame){
	$aRet=array();
	//*取得當前 開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$draws=$dws['draws_num'];
	//*取出這期 所有站台的 做開獎號碼 比較
	$sSite='168new';
	$ary_168new=ser_get_lottery_site_list_exist($sGame,$sSite,$draws);
	
	$sSite='1399p';
	$ary_1399p=ser_get_lottery_site_list_exist($sGame,$sSite,$draws);
	$ary_lianju=mke_lottery_num_list_lianju($sGame);
	$aContrast=array();
	if(!empty($ary_168new)){
		$aContrast['168new']=implode('_',$ary_168new);
	}
	
	if(!empty($ary_1399p)){
		$aContrast['1399p']=implode('_',$ary_1399p);
	}
	if(!empty($ary_lianju)){
		$aContrast['lianju']=implode('_',$ary_lianju);
	}
	print_r($aContrast);
	//*因為要比對 至少要有兩組 才會開始比
	if(count($aContrast)<2){
		return $aRet;
	}
	//*選用 出現最多次的號碼
	$aANS=array_count_values($aContrast);
	$aANS =array_flip($aANS);
	krsort($aANS);
	$sANS=current($aANS);
	$new_array=array();
	//*補上期數名稱 開獎時間
	$lottery_time=ser_get_lottery_time($sGame,$draws);
	$new_array_data['draws_num']=$draws;
	$new_array_num=explode('_',$sANS);
	$new_array_data['lottery_Time']=$lottery_time;
	$new_array_num['total_sum']=array_sum($new_array_num);
	if($sGame=='pk'){
		$new_array_num['total_sum']=$new_array_num[0]+$new_array_num[1];
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$new_array_num[0],$new_array_num[1],$new_array_num[2],$new_array_num[3],$new_array_num[4],$new_array_num[5],
			$new_array_num[6],$new_array_num[7],$new_array_num[8],$new_array_num[9],$new_array_num[10],$new_array_num[11],
			$new_array_num[12],$new_array_num[13],$new_array_num[14],$new_array_num[15],$new_array_num[16],$new_array_num[17],
			$new_array_num[18],$new_array_num[19]
		);
		$new_array_num['total_sum']=array_sum($aNum_kb);
	}
	$new_array=array_merge($new_array_data,$new_array_num);
	$aRet=$new_array;
	return $aRet;
}
?>