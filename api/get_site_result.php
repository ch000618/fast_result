<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_init.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
include_once($web_cfg['path_lib'].'func.api.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
init();
function init(){
	$ret='';
	$sGame=$_POST['game'];
	$today=$_POST['rpt_date'];
	$draws_num=$_POST['draws_num'];
	$site=$_POST['site'];
	$url=$_POST['url'];
	$aSite_Result=ser_get_lottery_site_list_exist($sGame,$site,$draws_num);
	switch($sGame){
		case 'klc':
			$column=array(
				 'ball_1'=>'result_1'
				,'ball_2'=>'result_2'
				,'ball_3'=>'result_3'
				,'ball_4'=>'result_4'
				,'ball_5'=>'result_5'
				,'ball_6'=>'result_6'
				,'ball_7'=>'result_7'
				,'ball_8'=>'result_8'
			);
		break;
		case 'ssc':
			$column=array(
				 'ball_1'=>'result_1'
				,'ball_2'=>'result_2'
				,'ball_3'=>'result_3'
				,'ball_4'=>'result_4'
				,'ball_5'=>'result_5'
			);
			break;
		case 'pk':
			$column=array(
				 'rank_1'=>'result_1'
				,'rank_2'=>'result_2'
				,'rank_3'=>'result_3'
				,'rank_4'=>'result_4'
				,'rank_5'=>'result_5'
				,'rank_6'=>'result_6'
				,'rank_7'=>'result_7'
				,'rank_8'=>'result_8'
				,'rank_9'=>'result_9'
				,'rank_10'=>'result_10'
			);
			break;
		case 'nc':
			$column=array(
				 'ball_1'=>'result_1'
				,'ball_2'=>'result_2'
				,'ball_3'=>'result_3'
				,'ball_4'=>'result_4'
				,'ball_5'=>'result_5'
				,'ball_6'=>'result_6'
				,'ball_7'=>'result_7'
				,'ball_8'=>'result_8'
			);
		break;
		case 'kb':
			$column=array(
				 'ball_1'=>'result_1'
				,'ball_2'=>'result_2'
				,'ball_3'=>'result_3'
				,'ball_4'=>'result_4'
				,'ball_5'=>'result_5'
				,'ball_6'=>'result_6'
				,'ball_7'=>'result_7'
				,'ball_8'=>'result_8'
				,'ball_9'=>'result_9'
				,'ball_10'=>'result_10'
				,'ball_11'=>'result_11'
				,'ball_12'=>'result_12'
				,'ball_13'=>'result_13'
				,'ball_14'=>'result_14'
				,'ball_15'=>'result_15'
				,'ball_16'=>'result_16'
				,'ball_17'=>'result_17'
				,'ball_18'=>'result_18'
				,'ball_19'=>'result_19'
				,'ball_20'=>'result_20'
			);
		break;
	}
	if(count($aSite_Result)<1){
		echo "本期該站台還沒開獎 \n";
		return $ret;
	}
	//echo "已將 $site 結果發送到$url \n";
	unset($aSite_Result['site']);
	unset($aSite_Result['total_sum']);
	unset($aSite_Result['lottery_Time']);
	unset($aSite_Result['draws_num']);
	if($sGame=='kb'){
		unset($step1['ball_fp']);
	}
	$aResult=array();
	foreach($aSite_Result as $k =>$v){
		$aResult[$column[$k]]=$aSite_Result[$k];
	}
	$form_data=array();
	$form_data['game']=$sGame;
	$form_data['rpt_date']=$today;
	$form_data['draws_num']=$draws_num;
	$form_data['result']=json_encode($aResult);
	$ret=curl_sand_seq_award($url,$form_data);
	echo $ret;
	//exit;
	
}
//用curl 送開獎結果到 站台api 讓站台把這筆結果寫進資料庫
/*
	$url=網址
	$form_data=post資料
*/
function curl_sand_seq_award($url,$form_data){
	// 建立CURL連線
	$ch = curl_init();
	//$draws_num="2016081725";
	$http = "$url/server/service/mo.ins_award.php";
	//echo $http;
	curl_setopt($ch, CURLOPT_URL ,$http);
	curl_setopt($ch, CURLOPT_HEADER ,false);
	curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form_data)); 
	curl_setopt($ch, CURLOPT_USERAGENT ,$user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	return $str;
}
?>