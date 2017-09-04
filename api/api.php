<?php
include_once('../config/connect.php');
include_once('../config/arc_config.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.pub.util.php');
include_once($web_cfg['path_lib'].'func.pub.var.php');
include_once($web_cfg['path_lib'].'func.api.php');
header("Access-Control-Allow-Origin: *");
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
//API的主流程
init_api();
//API主流程
/*
  *從GET[c]抓取命令
  *從POST抓取參數
    [key]=值
  *根據命令執行功能
  *如果參數有缺,就會偽造404回傳
*/
function init_api(){
  $cmd=api_get_para('c','GET');
  $cmd=strtolower($cmd);
  $aPara=$_POST;
	$sPARA=api_get_para('p','GET');
	$sPARA=trim($sPARA);
  $aRet=array();
  // echo "cmd=$cmd";
  // print_r($aPara);
  switch($cmd){
    case 'get_last_seq'://取今天最後一期名稱
      $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $aRet=api_get_last_seq($sGame);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_last_result_seq'://取今天最後一期有結果的期數名稱
      $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $aRet=api_get_last_result_seq($sGame);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_seq_data'://取某期的期數資料
      $aKey=array('game','seq_no');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $sDraws_num=$aPara['seq_name'];
      $aRet=api_get_seq_data($sGame,$sDraws_num);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_seq_result'://取某期的開獎結果
      $aKey=array('game','seq_name');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $sDraws_num=$aPara['seq_name'];
      $aRet=api_get_seq_result($sGame,$sDraws_num);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_day_draws'://取某遊戲今天的期數列表
      $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $aRet=api_get_day_draws($sGame);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_day_result'://取某遊戲今天的期數列表
      $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $aRet=api_get_day_result($sGame);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_someday_draws'://取某遊戲某天的期數列表
      $aKey=array('game','date');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      if(!strtotime($aPara['date'])){api_fake_404();}      
      $sDate=$aPara['date'];
      $aRet=api_get_day_draws($sGame,$sDate);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_someday_result'://取某遊戲某天的期數列表
      $aKey=array('game','date');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      if(!strtotime($aPara['date'])){api_fake_404();}
      $sDate=$aPara['date'];      
      $aRet=api_get_day_result($sGame,$sDate);
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_last_result'://取某遊戲最後一期
      $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $sGame=strtolower($aPara['game']);
      if(!api_chk_game($aPara['game'])){api_fake_404();}
      $aRet=api_get_last_result($sGame);
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_draws_num_last'://取某遊戲最後一期
      $aRet=api_get_draws_num_last();
			$sRET=json_encode($aRet,true);
      break;
		case 'api_chk_drop_draws_result'://取所有遊戲是否漏開
      $aRet=api_chk_drop_draws_result();
			if(!$aRet){$aRet=array('error_msg'=>'NO_DATA'); }
			$sRET=json_encode($aRet,true);
      break;
		case 'api_remedy_drop_result'://補開所有遊戲的獎號
      $aRet=api_remedy_drop_result();
			if(!$aRet){$aRet=array('error_msg'=>'NO_DATA'); }
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_all_game'://獎號中心有抓結果的遊戲
      $aRet=api_get_all_game();
			$sRET=json_encode($aRet,true);
      break;
		case 'api_chk_draws_daily'://檢查每日期數是否正常生成
			$aRet=api_chk_draws_daily();
			$sRET=json_encode($aRet,true);
      break;
		case 'api_chk_result_source'://獎號中心 資訊來源的狀態
      $aRet=api_chk_result_source();
			$sRET=json_encode($aRet,true);
      break;
		case 'del_result'://刪除開獎結果
			$sRET=api_del_key_result($aPara);
      break;
		case 'api_view_daily_log'://刪除開獎結果
			$ret=api_view_daily_log($sPARA);
			if(!$ret){ 
				$ret=array('error_msg'=>'NO_DATA');
				$sRET=json_encode($ret,true);
			}else{
				$sRET=$ret;
			}
      break;
		case 'api_view_daily_log_table'://取得今日error_log 轉成表格
			$ret=api_view_daily_log($sPARA);	
			$sRET=api_data_to_table($ret);
      break;
		 case 'api_put_lh_site_result':
		  $aKey=array('game','data');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $aRet=api_put_lh_site_result($aPara);
			$sRET=json_encode($aRet,true);
      break;
		case 'api_put_lh_site_result_batch':
		  $aKey=array('game','list');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $aRet=api_put_lh_site_result_batch($aPara);
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_lh_result':
		  $aKey=array('game');
      if(!api_chk_para_key($aPara,$aKey)){api_fake_404();}
      $aRet=api_get_lh_result($aPara);
			$sRET=json_encode($aRet,true);
      break;
    case 'get_now_time':
      $aRet=api_get_now_time();
			$sRET=json_encode($aRet,true);
      break;
    case 'get_now_timestamp':
      $aRet=api_get_now_timestamp();
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_bg_status':
      $aRet[]=api_get_BG_status();
			$aRet[]=api_get_BG_slow_status();
			$sRET=json_encode($aRet,true);
      break;
		case 'api_get_db_status_no_m_s'://取得DB狀態 非主從
			$aRet=api_get_db_status();
			if(!$aRet){ $aRet=array('error_msg'=>'NO_DATA'); }
			$sRET=json_encode($aRet,true);
      break;
		case 'api_restart_service'://重啟服務
			$aRet=api_restart_service();
			$sRET=json_encode($aRet,true);
			break;
		case 'api_restart_service_slow'://重啟服務 檢查各站獎號用
			$aRet=api_restart_service_slow();
			$sRET=json_encode($aRet,true);
			break;
    default:
      api_fake_404();
  }
  echo $sRET;
}
?>