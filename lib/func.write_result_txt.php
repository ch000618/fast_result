<?php
//歷史開獎結果
/*
資料來源是從history/hislist/這裡取得
是用傳遞get的方式取資料
傳id 可能是玩法 跟 date 日期 到這個網址
可以得到一個存著當天期數所有號碼跟結果的陣列
*/
function get_result_txt($date,$game){
	global $web_cfg;
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$game];
	//$proxy_server="124.88.67.17:843";
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.1680api.com/History/HisList?id=$id&date=$date";
	curl_setopt($ch, CURLOPT_URL, $http);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	if(count($obj)<1){return " ";}
	$fp = fopen($web_cfg['path_text']."result_$game"."/$game"."_$date.txt", 'w');
	fwrite($fp, json_encode($obj));
	fclose($fp);
}
//拆解歷史結果的陣列 取出 期數名稱 跟號碼
/*
傳入
	遊戲
	日期
回傳
[
	[
		[期數名稱]=>
		,[號碼1]=>
		,[號碼2]=>
		,.....[號碼n]=>
		,[總和]=>
	]
	,
	[
		[期數名稱]=>
		,[號碼1]=>
		,[號碼2]=>
		,.....[號碼n]=>
		,[總和]=>
	]
]
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/
function mke_hislist_num_list_txt($sGame,$sRpt_date){
		$aRet=array();
		$lt=mke_hislist_num_list_168new($sGame,$sRpt_date);
		if(count($lt)<1){return $aRet;}
		$data=$lt['list'];
		foreach($data as $key => $value){
			if($value['c_r']==''){continue;}
			$aNum=explode(',',$value['c_r']);
			$aDraws_num=array('draws_num'=>$value['c_t']);
			$sDraws_num=$value['c_t'];
			//開獎時間過濾掉特殊符號跟中文 
			$lottery_date=$sRpt_date.$value['c_d'];
			//將文字檔的開獎時間轉成 資料庫的格式
			$aRet[$key]=array_merge($aDraws_num,$aNum);
			//計算總和
			$aRet[$key]['total_sum']=array_sum($aNum);
			//只有北京賽車是用前兩個的和
			if($sGame=='pk'){$aRet[$key]['total_sum']=$aNum[0]+$aNum[1];}
			//kb因為有快樂飛盤的關係 總和長這樣
			if($sGame=='kb'){
				$nums_kb=array(
					$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
					$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
					$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
					$aNum[18],$aNum[19]
				);
				$aRet[$key]['total_sum']=array_sum($nums_kb);
			}
		}
		return $aRet;
}
function txt_mke_ser($sGame,$date=''){
	global $web_cfg;
	$ret='';
	$today=date('Y-m-d');
	$sRpt_date=($date=='')?date('Y-m-d',strtotime("-1 day")):$date;
	$txt=mke_hislist_num_list_168new($sGame,$sRpt_date);
	if(count($txt)<1){return;}
	$fp = fopen($web_cfg['path_text']."result_$sGame"."/$sGame"."_$sRpt_date.txt", 'w');
	fwrite($fp, json_encode($txt));
	@chmod($web_cfg['path_text']."result_$sGame/$sGame"."_$sRpt_date.txt",0777);
}
?>