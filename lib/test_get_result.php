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
//抓開獎結果 1399p_v2
/*
	*目錄 判斷
	*get值 判斷
	*各種偽裝瀏覽器
	*寫cookie
	*讀cookie
	*允許重新導向
	傳入:
		遊戲代碼
		要開獎的期數名稱
	回傳:
		當期結果陣列
*/
function ser_set_cookie_1399p(){
	//產生0-1 之間的亂數
	$t = mt_rand() / mt_getrandmax();
	// 建立CURL連線
	$ch = curl_init();
	$http = "https://www.1399p.com/";
	$header[]="Accept:application/json, text/javascript, */*; q=0.01";
	$header[]="Accept-Encoding:gzip, deflate, br";
	$header[]="Accept-Language:zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4";
	$header[]="Connection:keep-alive";
	$header[]="X-Requested-With:XMLHttpRequest";
	$cookie_txt=dirname(dirname(__FILE__))."/text/1399p_test.txt";
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	curl_setopt($ch, CURLOPT_URL,$http);
	// 跳过证书检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
	// 从证书中检查SSL加密算法是否存在
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); 
	//是否回傳檔頭
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_ENCODING , "gzip");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	//是否跟隨 重新導向
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	//是否將結果 以字串回傳
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	//是否啟用 毫秒級等待
	curl_setopt($ch, CURLOPT_NOSIGNAL,1);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	//curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie_txt);
	//最長等待時間 
	curl_setopt($ch, CURLOPT_TIMEOUT_MS,2000);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	//模擬 瀏覽器的User_agent
	curl_setopt($ch, CURLOPT_USERAGENT,$sUser_agent);
	// 執行
	$str=curl_exec($ch);
	$info = curl_getinfo($ch);
	//echo $info." \n ";
	/*
	if($info['http_code']!=200){
		
		echo '<pre>';
		print_r($info);
		echo '</pre>';
		
		echo curl_error($ch);
	}
	*/
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
}
?>