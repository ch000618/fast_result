<?php
//拆解歷史結果的陣列 取出 期數名稱 跟號碼
/*
傳入
	遊戲
	日期
回傳
	期數名稱
	號碼
*把文字檔的期數名稱跟號碼取出
*計算總和
*只有北京賽車是用前兩個的和
*/
function mke_hislist_num_list($game,$data,$rpt_date){
		//把文字檔的期數名稱跟號碼取出
		foreach($data as $key => $value){
			if($value['c_r']==''){continue;}
			$num=explode(',',$value['c_r']);
			$dw_n=array('draws_num'=>$value['c_t']);
			$draws_num=$value['c_t'];
			//開獎時間過濾掉特殊符號跟中文 
			$lottery_date=$rpt_date.$value['c_d'];
			//將文字檔的開獎時間轉成 資料庫的格式
			$Time_lottery=date('Y-m-d H:i:s',strtotime($lottery_date));
			UPDATE_Time_lottery_v2($game,$draws_num,$Time_lottery);
			$ret[$key]=array_merge($dw_n,$num);
			//計算總和
			$ret[$key]['total_sum']=array_sum($num);
			//只有北京賽車是用前兩個的和
			if($game=='pk'){$ret[$key]['total_sum']=$num[0]+$num[1];}
			//kb因為有快樂飛盤的關係 總和長這樣
			if($game=='kb'){
				$nums_kb=array(
					$num[0],$num[1],$num[2],$num[3],$num[4],$num[5],
					$num[6],$num[7],$num[8],$num[9],$num[10],$num[11],
					$num[12],$num[13],$num[14],$num[15],$num[16],$num[17],
					$num[18],$num[19]
				);
				$ret[$key]['total_sum']=array_sum($nums_kb);
			}
		}
		return $ret;
}
//拆解歷史結果的陣列 168api
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/
function mke_hislist_num_list_168old($sGame,$sRpt_date=''){
	$aRet=array();
	$lt=ser_get_hislist_result_168old($sGame,$sRpt_date);
	//$draws=ser_get_last_result_seq($sGame,$sRpt_date);
	if(count($lt)<1){return $aRet;}
	$data=$lt['list'];
	/*
	if(count($data)<$draws){return $aRet;}
	*/
	$i=0;
	foreach($data as $key => $value){
		if($value['c_r']==''){continue;}
		if($value['c_t']=='0'||$value['c_t']==''){continue;}
		$aNums=explode(',',$value['c_r']);
		$aDraws_num=array('draws_num'=>$value['c_t']);
		$sDraws_num=$value['c_t'];
		//開獎時間過濾掉特殊符號跟中文 
		$lottery_date=$sRpt_date.$value['c_d'];
		//將文字檔的開獎時間轉成 資料庫的格式
		$sTime_lottery=date('Y-m-d H:i:s',strtotime($lottery_date));
		$aNum=$aNums;
		if($sGame=='kb'){
			$aNum_kb=array(
				$aNums[0],$aNums[1],$aNums[2],$aNums[3],$aNums[4],$aNums[5],
				$aNums[6],$aNums[7],$aNums[8],$aNums[9],$aNums[10],$aNums[11],
				$aNums[12],$aNums[13],$aNums[14],$aNums[15],$aNum[16],$aNums[17],
				$aNums[18],$aNums[19]
			);
			asort($aNum_kb);
			$aNum=$aNum_kb;
			$aNum[20]=$aNums[20];
		}
		$aRet[$i]=array_merge($aDraws_num,$aNum);
		//計算總和
		$aRet[$i]['lottery_Time']=$sTime_lottery;
		$aRet[$i]['total_sum']=array_sum($aNum);
		//只有北京賽車是用前兩個的和
		if($sGame=='pk'){$aRet[$i]['total_sum']=$aNum[0]+$aNum[1];}
		//kb因為有快樂飛盤的關係 總和長這樣
		if($sGame=='kb'){
			$aNum_kb_sum=array(
				$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
				$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
				$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
				$aNum[18],$aNum[19]
			);
			$aRet[$i]['total_sum']=array_sum($aNum_kb_sum);
		}
		$aRet[$i]['site']='168old';
		$i++;
	}
	return $aRet;
}

//拆解歷史結果的陣列 彩之家
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/
function mke_hislist_num_list_91333($sGame,$sRpt_date=''){
	$aRet=array();
	$lt=ser_get_hislist_result_91333($sGame,$sRpt_date);
	if(count($lt)<1){return $aRet;}
	$data=$lt['list'];
	//print_r($lt['list']);
	$i=0;
	foreach($data as $key => $value){
		if($value['c_r']==''){continue;}
		if($value['c_t']=='0'||$value['c_t']==''){continue;}
		$aNums=explode(',',$value['c_r']);
		$sDraws_num=$value['c_t'];
		if($sGame=='nc' || $sGame=='klc'){
			$isn=(int)substr($sDraws_num,9,11);
			$sn=($isn<10)?'0'.$isn:$isn;
			$sDraws_num=substr($sDraws_num,0,8).$sn;
		}
		$aDraws_num=array('draws_num'=>$sDraws_num);
		//開獎時間過濾掉特殊符號跟中文 
		$sTime_lottery=$value['c_d'];
		//將文字檔的開獎時間轉成 資料庫的格式
		$aNum=$aNums;
		if($sGame=='kb'){
			$aNum_kb=array(
				$aNums[0],$aNums[1],$aNums[2],$aNums[3],$aNums[4],$aNums[5],
				$aNums[6],$aNums[7],$aNums[8],$aNums[9],$aNums[10],$aNums[11],
				$aNums[12],$aNums[13],$aNums[14],$aNums[15],$aNum[16],$aNums[17],
				$aNums[18],$aNums[19]
			);
			asort($aNum_kb);
			$aNum=$aNum_kb;
			$aNum[20]=$aNums[20];
		}
		$aRet[$i]=array_merge($aDraws_num,$aNum);
		//計算總和
		$aRet[$i]['lottery_Time']=$sTime_lottery;
		$aRet[$i]['total_sum']=array_sum($aNum);
		//只有北京賽車是用前兩個的和
		if($sGame=='pk'){$aRet[$i]['total_sum']=$aNum[0]+$aNum[1];}
		//kb因為有快樂飛盤的關係 總和長這樣
		if($sGame=='kb'){
			$aNum_kb_sum=array(
				$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
				$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
				$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
				$aNum[18],$aNum[19]
			);
			$aRet[$i]['total_sum']=array_sum($aNum_kb_sum);
		}
		$aRet[$i]['site']='91333';
		$i++;
	}
	return $aRet;
}

//拆解歷史結果的陣列 98007
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/
function mke_hislist_num_list_98007($sGame){
	$aRet=array();
	$lt=ser_get_result_98007($sGame);
	//print_r($lt);
	if(count($lt)<1){return $aRet;}
	$data=$lt['list'];
	//print_r($lt['list']);
	$i=0;
	foreach($data as $key => $value){
		if($value['c_r']==''){continue;}
		if($value['c_t']=='0'||$value['c_t']==''){continue;}
		$aNums=explode(',',$value['c_r']);
		$sDraws_num=$value['c_t'];
		if($sGame=='nc' || $sGame=='klc'){
			$isn=(int)substr($sDraws_num,9,11);
			$sn=($isn<10)?'0'.$isn:$isn;
			$sDraws_num=substr($sDraws_num,0,8).$sn;
		}
		$aDraws_num=array('draws_num'=>$sDraws_num);
		//開獎時間過濾掉特殊符號跟中文 
		$sTime_lottery=$value['c_d'];
		//將文字檔的開獎時間轉成 資料庫的格式
		$aNum=$aNums;
		if($sGame=='kb'){
			$aNum_kb=array(
				$aNums[0],$aNums[1],$aNums[2],$aNums[3],$aNums[4],$aNums[5],
				$aNums[6],$aNums[7],$aNums[8],$aNums[9],$aNums[10],$aNums[11],
				$aNums[12],$aNums[13],$aNums[14],$aNums[15],$aNum[16],$aNums[17],
				$aNums[18],$aNums[19]
			);
			asort($aNum_kb);
			$aNum=$aNum_kb;
			$aNum[20]=$aNums[20];
		}
		$aRet[$i]=array_merge($aDraws_num,$aNum);
		//計算總和
		$aRet[$i]['lottery_Time']=$sTime_lottery;
		$aRet[$i]['total_sum']=array_sum($aNum);
		//只有北京賽車是用前兩個的和
		if($sGame=='pk'){$aRet[$i]['total_sum']=$aNum[0]+$aNum[1];}
		//kb因為有快樂飛盤的關係 總和長這樣
		if($sGame=='kb'){
			$aNum_kb_sum=array(
				$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
				$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
				$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
				$aNum[18],$aNum[19]
			);
			$aRet[$i]['total_sum']=array_sum($aNum_kb_sum);
		}
		$aRet[$i]['site']='98007';
		$i++;
	}
	//print_r($aRet);
	return $aRet;
}
//拆解歷史結果的陣列 168api新
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/
function mke_hislist_num_list_168new($sGame,$sRpt_date=''){
	$aRet=array();
	$lt=ser_get_hislist_result_168new($sGame,$sRpt_date);
	/*
	echo '<xmp>';
	print_r($lt);
	echo '</xmp>';
	*/
	/*
	$draws=ser_get_last_result_seq($sGame,$sRpt_date);
	if(count($lt)<1){return $aRet;}
	*/
	$data=$lt['result']['data'];
	//if(count($data)<$draws){return $aRet;}
	foreach($data as $key => $value){
		if($value['preDrawCode']==''){continue;}
		$aNums=explode(',',$value['preDrawCode']);
		foreach($aNums as $k => $v){
			$num=(int)$v;
			$aNum[$k]=$num;
		}
		$preDrawIssue=$value['preDrawIssue'];
		if($sGame=='nc' && strlen($value['preDrawIssue'])>10){
			$isn=(int)substr($value['preDrawIssue'],9,11);
			$sn=($isn<10)?'0'.$isn:$isn;
			$preDrawIssue=substr($value['preDrawIssue'],0,8).$sn;
		}
		$aDraws_num=array('draws_num'=>$preDrawIssue);
		$sDraws_num=$preDrawIssue;
		//將文字檔的開獎時間轉成 資料庫的格式
		$sTime_lottery=$value['preDrawTime'];
		$aRet[$key]=array_merge($aDraws_num,$aNum);
		$aRet[$key]['lottery_Time']=$value['preDrawTime'];
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
		$aRet[$key]['site']='168new';
	}
	return $aRet;
}
//取得 連鉅 今天到目前為止 的所有結果
/*
	*取得	目前開獎的日期
	*取得 最後一次開獎的期數名稱
	*取得 最後一次開獎 以前的所有期數
	*取得 all_result_lianju 目前包含最後一次 以前所有結果
*/
function ser_get_today_result_lianju($sGame,$sRpt_date=''){
	$aRet=array();
	if($sRpt_date=='' || !isset($sRpt_date)){
		$dws=dws_get_now_lottery_info($sGame);
		$sRpt_date=$dws['rpt_date'];
	}
	$sTable='all_result_lianju';
	$draws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
	$draws=ser_get_now_draws($sGame,$draws_sn,$sRpt_date);
	$lt_times=dws_get_draws_lt_times($sGame,$draws);
	$ary=ser_get_lottery_table_list($sGame,$sTable,$draws,$lt_times);
	if(empty($ary)){return $aRet;}
	//$last_result=ser_get_last_result_seq($sGame,$sRpt_date);
	//if(count($ary)<$last_result){return $aRet;}
	$aRet=$ary;
	return $aRet;
}
//取得 連鉅 今天到目前為止 的所有結果
/*
	*取得	目前開獎的日期
	*取得 最後一次開獎的期數名稱
	*取得 最後一次開獎 以前的所有期數
	*取得 all_result_lianju 目前包含最後一次 以前所有結果
*/
function ser_get_today_result_un($sGame,$sRpt_date=''){
	$aRet=array();
	if($sRpt_date=='' || !isset($sRpt_date)){
		$dws=dws_get_now_lottery_info($sGame);
		$sRpt_date=$dws['rpt_date'];
	}
	$sTable='all_result_un';
	$draws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
	$draws=ser_get_now_draws($sGame,$draws_sn,$sRpt_date);
	$lt_times=dws_get_draws_lt_times($sGame,$draws);
	$ary=ser_get_lottery_table_list($sGame,$sTable,$draws,$lt_times);
	if(empty($ary)){return $aRet;}
	//$last_result=ser_get_last_result_seq($sGame,$sRpt_date);
	//if(count($ary)<$last_result){return $aRet;}
	$aRet=$ary;
	return $aRet;
}
/*
	*取得	目前開獎的日期
	*取得 最後一次開獎的期數名稱
	*取得 最後一次開獎 以前的所有期數
	*取得 all_result_lianju 目前包含最後一次 以前所有結果
*/
function ser_get_today_result_ju888($sGame,$sRpt_date=''){
	$aRet=array();
	if($sRpt_date=='' || !isset($sRpt_date)){
		$dws=dws_get_now_lottery_info($sGame);
		$sRpt_date=$dws['rpt_date'];
	}
	$sTable='all_result_ju888';
	$draws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
	$draws=ser_get_now_draws($sGame,$draws_sn,$sRpt_date);
	$lt_times=dws_get_draws_lt_times($sGame,$draws);
	$ary=ser_get_lottery_table_list($sGame,$sTable,$draws,$lt_times);
	if(empty($ary)){return $aRet;}
	//$last_result=ser_get_last_result_seq($sGame,$sRpt_date);
	//if(count($ary)<$last_result){return $aRet;}
	$aRet=$ary;
	return $aRet;
}

//拆解歷史結果的陣列 連鉅
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/

function mke_hislist_num_list_lianju($sGame){
	$lt=ser_get_today_result_lianju($sGame);
	if(count($lt)<1){return $aRet;}
	foreach((array)$lt as $key => $value){
		$aNums=explode(',',$value['draws_code']);
		foreach($aNums as $k => $v){
			$num=(int)$v;
			$num=($num<0)?0:$num;
			$aNum[$k]=$num;
		}
		$aDraws_num=array('draws_num'=>$value['draws_num']);
		//將文字檔的開獎時間轉成 資料庫的格式
		$sTime_lottery=$value['lottery_Time'];
		$aRet[$key]=array_merge($aDraws_num,$aNum);
		$aRet[$key]['lottery_Time']=$sTime_lottery;
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
		$aRet[$key]['site']='lianju';
	}
	return $aRet;
}
//拆解歷史結果的陣列 九州
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/

function mke_hislist_num_list_ju888($sGame){
	$lt=ser_get_today_result_ju888($sGame);
	if(count($lt)<1){return $aRet;}
	foreach((array)$lt as $key => $value){
		$aNums=explode(',',$value['draws_code']);
		foreach($aNums as $k => $v){
			$num=(int)$v;
			$num=($num<0)?0:$num;
			$aNum[$k]=$num;
		}
		$aDraws_num=array('draws_num'=>$value['draws_num']);
		//將文字檔的開獎時間轉成 資料庫的格式
		$sTime_lottery=$value['lottery_Time'];
		$aRet[$key]=array_merge($aDraws_num,$aNum);
		$aRet[$key]['lottery_Time']=$sTime_lottery;
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
		$aRet[$key]['site']='ju888';
	}
	return $aRet;
}
//拆解歷史結果的陣列 un
/*
傳入
	遊戲
	日期
回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
*170409 因為 之後可能會去抓各站的 歷史列表 所以抓出跟 整理格式 一起做會比較好處理
*curl 抓獎號列表
*只取期數 和號碼
*計算總和
*只有北京賽車是用前兩個的和
*快樂8 有快樂飛盤的關係 總和只算前20個號碼
*/

function mke_hislist_num_list_un($sGame){
	$aRet=array();
	$lt=ser_get_today_result_un($sGame);
	if(count($lt)<1){return $aRet;}
	foreach((array)$lt as $key => $value){
		$aNums=explode(',',$value['draws_code']);
		foreach($aNums as $k => $v){
			$num=(int)$v;
			$num=($num<0)?0:$num;
			$aNum[$k]=$num;
		}
		$aDraws_num=array('draws_num'=>$value['draws_num']);
		//將文字檔的開獎時間轉成 資料庫的格式
		$sTime_lottery=$value['lottery_Time'];
		$aRet[$key]=array_merge($aDraws_num,$aNum);
		$aRet[$key]['lottery_Time']=$sTime_lottery;
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
		$aRet[$key]['site']='un';
	}
	return $aRet;
}
//用期數名稱取出 對應的日期跟當日序號 只做漏開的期數
/*
傳入
	遊戲
	日期
回傳	
	[
		日期
		當日序號
		期數名稱
		號碼
	]
*呼叫號碼跟期數名稱的陣列
*取出期數名稱
*用期數名稱到資料庫找日期跟當日序號
*刪除已經有結果的期數 開獎結果的
*將查詢結果 跟 號碼跟期數名稱的陣列 做合併
*/
function mke_hislist_date_list($game,$ary,$aDraws_nums){
	$sDraws_nums=implode(',',$aDraws_nums);
	$data=ser_his_rpt_date($game,$sDraws_nums);
	$ret=array();
	$ret_o=array();
	foreach($aDraws_nums as $k1 => $v1){
		foreach($ary as $key => $value){
			$draws_num=$value['draws_num'];
			if($draws_num!=$v1){continue;}
			$sys_time=date('Y-m-d H:i:s');
			UPDATE_sys_time($game,$draws_num,$sys_time);
			$ret_o[]=$value;
		}
	}
	foreach($ret_o as $k => $v){
		$ret[]=array_merge($data[$k],$v);
	}
	return $ret;
}
//歷史開獎結果_168old
/*
資料來源是從history/hislist/這裡取得
是用傳遞get的方式取資料
傳id 可能是玩法 跟 date 日期 到這個網址
可以得到一個存著當天期數所有號碼跟結果的陣列
*/
function ser_get_hislist_result_168old($sGame,$sRpt_date=''){
	if($sRpt_date==''){
		$sRpt_date=date('Y-m-d');
	}
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$sGame];
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.1680api.com/History/HisList?id=$id&date=$sRpt_date";
	//echo $http;
	curl_setopt($ch,CURLOPT_URL, $http);
	curl_setopt($ch,CURLOPT_HEADER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,6000);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	//print_r($obj);
	return $obj;
}
//歷史開獎結果_91333
/*
資料來源是從history/hislist/這裡取得
是用傳遞get的方式取資料
傳id 可能是玩法 跟 date 日期 到這個網址
可以得到一個存著當天期數所有號碼跟結果的陣列
*/
function ser_get_hislist_result_91333($sGame,$sRpt_date=''){
	if($sRpt_date==''){
		$sRpt_date=date('Y-m-d');
	}
	$game_id=array();
	//新版網站 都放在不同的目錄
	$game_id['klc']='gdkl10';
	$game_id['ssc']='cqssc';
	$game_id['pk']='pk10';
	$game_id['nc']='xync';
	$game_id['kb']='kl8';
	$id=$game_id[$sGame];
	//產生0-1 之間的亂數
	$t = mt_rand() / mt_getrandmax();
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://www.91333.com/api/caipiao/get_lists?id=$id&date=$sRpt_date&_=$t";
	//echo $http;
	$aReferer=array();
	$aReferer[]="http://www.91333.com";
	$aReferer[]="/$id/";
	$sReferer=implode('',$aReferer);
	$cookie_txt=dirname(dirname(__FILE__))."/text/91333.txt";
	$aUser_agent=array();
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	$aHeader=array();
	$aHeader[]="Accept: application/json, text/javascript, */*; q=0.01";
	$aHeader[]="Accept-Encoding: gzip, deflate, sdch";
	$aHeader[]="Accept-Language: zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4";
	$aHeader[]="Connection: keep-alive";
	$aHeader[]="X-Requested-With: XMLHttpRequest";
	//echo $http;
	curl_setopt($ch,CURLOPT_URL, $http);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$aHeader);
	curl_setopt($ch,CURLINFO_HEADER_OUT,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,3000);
	//模擬 瀏覽器的User_agent
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	// 執行
	$str=curl_exec($ch);
	//$info = curl_getinfo($ch);
	//echo '<xmp>';
	//print_r($info['request_header']);
	//echo '</xmp>';
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	//print_r($obj);
	return $obj;
}
//歷史開獎結果_168new
/*
是用傳遞get的方式取資料
傳id 可能是玩法 跟 date 日期 到這個網址
可以得到一個存著當天期數所有號碼跟結果的陣列
*/
function ser_get_hislist_result_168new($sGame,$sRpt_date=''){
	$game_id=array();
	//新版網站 編號有改
	$game_id['klc']="10005";
	$game_id['ssc']="10002";
	$game_id['nc']="10009";
	$game_id['pk']="10001";
	$game_id['kb']="10014";
	$root_name=array();
	//新版網站 都放在不同的目錄
	$root_name['klc']='klsf';
	$root_name['ssc']='CQShiCai';
	$root_name['pk']='pks';
	$root_name['nc']='klsf';
	$root_name['kb']='LuckTwenty';
	//新版網站 每個遊戲有自己的開獎程式
	$do_name['klc']='getHistoryLotteryInfo';
	$do_name['ssc']='getBaseCQShiCaiList';
	$do_name['nc']='getHistoryLotteryInfo';
	$do_name['pk']='getPksHistoryList';
	$do_name['kb']='getBaseLuckTwentyList';
	$aReferer['klc']='http://www.1680210.com/html/klsf/klsf_kjhistory.html';
	$aReferer['ssc']='http://www.1680210.com/html/shishicai_cq/ssc_kjhistory.html';
	$aReferer['nc']='http://www.1680210.com/html/cqnc/klsf_kjhistory.html';
	$aReferer['pk']='http://www.1680210.com/html/PK10/pk10kai_history.html';
	$aReferer['kb']='http://www.1680210.com/html/beijinkl8/bjkl8_kjhistory.html';
	$id=$game_id[$sGame];
	$game_root=$root_name[$sGame];
	$game_do=$do_name[$sGame];
	$sReferer=$aReferer[$sGame];
	$aUser_agent=array();
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://api.1680210.com/$game_root/$game_do.do?date=$sRpt_date&lotCode=$id";
	//echo $http;
	curl_setopt($ch,CURLOPT_URL, $http);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,6000);
	//是否跟隨 重新導向
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	//echo $str;
	$obj=json_decode($str,true);
	//print_r($obj);
	return $obj;
}
//取某張站台表 某幾期的開獎結果 
/*
  $sGame=遊戲代碼
  回傳:
	$aRet:開獎號碼陣列
*/
function ser_get_lottery_table_list($sGame,$sTable,$aDraws_num,$lt_times){
  global $db_s;
  $aRet=array();
	switch($sGame){
		case 'klc':
			$column=array(
				 'num1'
				,'num2'
				,'num3'
				,'num4'
				,'num5'
				,'num6'
				,'num7'
				,'num8'
			);
			$max=8;
			$gtype='1';
			break;
		case 'ssc':
			$column=array(
				 'num1'
				,'num2'
				,'num3'
				,'num4'
				,'num5'
			);
			$gtype='2';
			$max=5;
			break;
		case 'pk':
			$column=array(
				 'num1'
				,'num2'
				,'num3'
				,'num4'
				,'num5'
				,'num6'
				,'num7'
				,'num8'
				,'num9'
				,'num10'
			);
			$gtype='3';
			$max=10;
			break;
		case 'nc':
			$column=array(
				 'num1' 
				,'num2'
				,'num3'
				,'num4'
				,'num5'
				,'num6'
				,'num7'
				,'num8'
			);
			$gtype='4';
			$max=8;
			break;
		case 'kb':
			$column=array(
				 'num1'
				,'num2'
				,'num3'
				,'num4'
				,'num5'
				,'num6'
				,'num7'
				,'num8'
				,'num9'
				,'num10'
				,'num11'
				,'num12'
				,'num13'
				,'num14'
				,'num15'
				,'num16'
				,'num17'
				,'num18'
				,'num19'
				,'num20'
				,'num21'
			);
			$gtype='5';
			$max=21;
			break;
	}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='gid as draws_num';
  $aSQL[]=',[column]';
  $aSQL[]='FROM [sTable]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND gtype="[gtype]"';
  $aSQL[]='AND gid IN("[draws_num]")';
  $aSQL[]='ORDER BY gid DESC';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[sTable]',$sTable,$sSQL);
  $sSQL=str_replace('[draws_num]',implode('","',$aDraws_num),$sSQL);
  $sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	$db_s->sql_query($sSQL);
	$i=0;
	if($db_s->numRows() < 1){ return $aRet;}
	$ary=array();
	$num=array();
	while($r = $db_s->nxt_row('ASSOC')){
		$ary[$i]['draws_num']=$r['draws_num'];
		for($j=1;$j<=$max;$j++){
			$num[$i][$j-1]=$r['num'.$j];
		}
		$ary[$i]['draws_code']=implode(',',$num[$i]);
		$ary[$i]['lottery_Time']=$lt_times[$r['draws_num']];
		$i++;
	}
	$aRet=$ary;
  return $aRet;
}
//用開獎資料期數名稱找到開獎日期
function ser_his_rpt_date($game,$draws_num){
	global $db_s;
	$db_s->fetch_type='ASSOC';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='rpt_date';
	$SQL[]=',date_sn';
	$SQL[]=',draws_num';
	$SQL[]='FROM draws_'.$game;
	$SQL[]='WHERE';
	$SQL[]='draws_num in('.$draws_num.')';
	$SQL[]='ORDER BY draws_num ASC';
	$sql=implode(' ',$SQL);
	//echo $sql;
	$q=$db_s->sql_query($sql);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret[]=$r;
	}
	return $ret;
}
//開獎列表 切換流程 哪個網站先抓到 就先列出
/*
	傳入
		$sGame=遊戲
		$sRpt_date=日期
	回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[0] => 1 //各球結果 會遊戲不同 而 有不同球數
				[1] => 2
				[2] => 3
				[3] => 4
				[4] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
	不論去哪個網站抓的結果
	格式 都要轉成 跟回傳的格式 一樣
	才能放進這個流程中 不然會出錯
*/
function ser_lottery_hislist_list_switch($sGame,$sRpt_date){
	$aRet=array();
	$hislist_list=mke_hislist_num_list_lianju($sGame);
	$aRet=$hislist_list;
	if(!empty($aRet)){
		//echo "lianju";
		//$aRet['site']="lianju";
		return $aRet;
	}
	$hislist_list=mke_hislist_num_list_168new($sGame,$sRpt_date);
	$aRet=$hislist_list;
	if(!empty($aRet)){
		echo "168new";
		//$aRet['site']="168new";
		return $aRet;
	}
	$hislist_list=mke_hislist_num_list_un($sGame);
	$aRet=$hislist_list;
	if(!empty($aRet)){
		echo "un";
		//$aRet['site']="un";
		return $aRet;
	}
	/*
	$hislist_list=mke_hislist_num_list_168old($sGame,$sRpt_date);
	$aRet=$hislist_list;
	$aRet['site']="168old";
	*/
	return $aRet;
}
//開獎列表 從站台表抓取
/*
	傳入
		$sGame=遊戲
		$aDraws=目前的所有期數
	回傳
	Array(
		[index]=>
			Array(
				[draws_num] => 期數名稱
				[ball_1] => 1 //各球結果 會遊戲不同 而 有不同球數 以ssc為例子
				[ball_2] => 2
				[ball_3] => 3
				[ball_4] => 4
				[ball_5] => 5
				[lottery_time] => 開獎時間
				[total_sum] => 15 //總和
		)
	)
	不論去哪個網站抓的結果
	格式 都要轉成 跟回傳的格式 一樣
	才能放進這個流程中 不然會出錯
	*把每個可以用於補開結果的 站台 存成一個陣列
	*跑迴圈檢查 哪一個是目前開出最多期站台 
	*符合條件 且優先度最高的(開出最多獎號的) 當成補開結果的依據
*/
function ser_hislist_list_site_table($sGame,$aDraws){
	//$debug=true;
	$aRet=array();
	$aHis_list=array();
	$aHis_tmp=array();
	$aSite=array(
		'un'
		,'168new'
		,'1399p'
		,'lianju'
		,'98007'
	);
	//* 已開獎 幾期
	$iDraws_cnt=count($aDraws);
	//* 允許漏一期
	$iDrop_cnt=1;
	//* 參考站台準則 已開獎 幾期-允許漏幾期
	$iRefer_norm=($iDraws_cnt-$iDrop_cnt);
	foreach($aSite as $key =>$sSite){
		$his_list=ser_get_hislist_list_site_table($sGame,$sSite,$aDraws);
		//*站台開獎列表 總次數
		$his_list_cnt=count($his_list);
		/*
		//* 要補開站台 所開的期數 如果大於或等於準則 就補開納入參考
		if( $his_list_cnt >= $iRefer_norm){
			$aHis_list[$key]['cnt']=$his_list_cnt;
			$aHis_list[$key]['list']=$his_list;
		}else{
			continue;
		}
		*/
		$aHis_site_cnt[$sSite]=$his_list_cnt;
		$aHis_tmp[$sSite]=$his_list;
	}
	//*依照 站台開獎次數 進行 排序
	arsort($aHis_site_cnt);
	//*用排序 的結果 來產生 結果列表順序
	foreach($aHis_site_cnt as $site =>$val){
		$aHis_list[]=$aHis_tmp[$site];
	}
	//*取陣列的第0個 結果列表 
	$aRet=$aHis_list[0];
	
	echo '<xmp>';
	print_r($aHis_site_cnt);
	echo '</xmp>';
	/*
	echo '<xmp>';
	print_r($aRet);
	echo '</xmp>';
	*/
	return $aRet;
}
//撈出區間內 該站台所有開獎結果
function ser_get_hislist_list_site_table($sGame,$sSite,$aDraws){
	global $db_s;
  $aRet=array();
	switch($sGame){
		case 'klc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'lottery_Time'
				,'total_sum'
				,'site'
			);
		break;
		case 'ssc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'lottery_Time'
				,'total_sum'
				,'site'
			);
			break;
		case 'pk':
			$column=array(
				'draws_num'
				,'rank_1'
				,'rank_2'
				,'rank_3'
				,'rank_4'
				,'rank_5'
				,'rank_6'
				,'rank_7'
				,'rank_8'
				,'rank_9'
				,'rank_10'
				,'lottery_Time'
				,'total_sum'
				,'site'
			);
			break;
		case 'nc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'lottery_Time'
				,'total_sum'
				,'site'
			);
		break;
		case 'kb':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'ball_9'
				,'ball_10'
				,'ball_11'
				,'ball_12'
				,'ball_13'
				,'ball_14'
				,'ball_15'
				,'ball_16'
				,'ball_17'
				,'ball_18'
				,'ball_19'
				,'ball_20'
				,'ball_fp'
				,'lottery_Time'
				,'total_sum'
				,'site'
			);
		break;
	}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='[column]';
  $aSQL[]='FROM site_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND site="[site]"';
  $aSQL[]='AND draws_num IN("[draws_num]")';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[site]',$sSite,$sSQL);
  $sSQL=str_replace('[draws_num]',implode('","',$aDraws),$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	$db_s->sql_query($sSQL);
	/*if($sGame=='kb'){
		echo $sSQL." \n";
	}*/
	if($db_s->numRows() < 1){ return $aRet;}
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r;
	}
  return $aRet;
}
//撈出區間內 該站台所有開獎結果
function ser_get_hislist_draws_result($sGame,$aDraws){
	global $db_s;
  $aRet=array();
	switch($sGame){
		case 'klc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'total_sum'
				,'site'
			);
		break;
		case 'ssc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'total_sum'
				,'site'
			);
			break;
		case 'pk':
			$column=array(
				'draws_num'
				,'rank_1'
				,'rank_2'
				,'rank_3'
				,'rank_4'
				,'rank_5'
				,'rank_6'
				,'rank_7'
				,'rank_8'
				,'rank_9'
				,'rank_10'
				,'total_12'
				,'site'
			);
			break;
		case 'nc':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'total_sum'
				,'site'
			);
		break;
		case 'kb':
			$column=array(
				'draws_num'
				,'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
				,'ball_9'
				,'ball_10'
				,'ball_11'
				,'ball_12'
				,'ball_13'
				,'ball_14'
				,'ball_15'
				,'ball_16'
				,'ball_17'
				,'ball_18'
				,'ball_19'
				,'ball_20'
				,'ball_fp'
				,'total_sum'
				,'site'
			);
		break;
	}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='[column]';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND draws_num IN("[draws_num]")';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[draws_num]',implode('","',$aDraws),$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	//echo $sSQL;
	$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $aRet;}
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r;
	}
  return $aRet;
}
//用期數名稱取出 對應的日期跟當日序號 只做漏開的期數
/*
傳入
	遊戲
	日期
回傳	
	[
		日期
		當日序號
		期數名稱
		號碼
	]
*呼叫號碼跟期數名稱的陣列
*取出期數名稱
*用期數名稱到資料庫找日期跟當日序號
*刪除已經有結果的期數 開獎結果的
*將查詢結果 跟 號碼跟期數名稱的陣列 做合併
*/
function mke_hislist_date_list_v2($game,$ary,$aDraws_nums){
	$sDraws_nums=implode(',',$aDraws_nums);
	$data=ser_his_rpt_date($game,$sDraws_nums);
	$ret=array();
	$ret_o=array();
	foreach($aDraws_nums as $k1 => $v1){
		foreach($ary as $key => $value){
			$draws_num=$value['draws_num'];
			$sLt_time=$value['lottery_Time'];
			if($draws_num!=$v1){continue;}	
			$sys_time=date('Y-m-d H:i:s');
			UPDATE_Time_lottery_v2($game,$draws_num,$sLt_time);
			UPDATE_sys_time($game,$draws_num,$sys_time);
			unset($value['lottery_Time']);
			$ret_o[]=$value;
		}
	}
	foreach($ret_o as $k => $v){
		$aRpt_date_sn=array(
			'rpt_date'=>$data[$k]['rpt_date']
			,'date_sn'=>$data[$k]['date_sn']
		);
		if($data[$k]['draws_num']!=$v['draws_num']){continue;}	
		$ret[]=array_merge($aRpt_date_sn,$v);
	}
	//print_r($ret);
	return $ret;
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
function ser_chg_drop_result($sGame,$sRpt_date){
	$ret=0;
	$sDraws_sn=ser_get_last_result_seq($sGame,$sRpt_date);
	if($sDraws_sn==''){
		return $ret;
	}
	$aDraws_nums=array();
	//1.抓出今天到現在為止的所有期數序號
	$aDraws=ser_get_now_draws($sGame,$sDraws_sn,$sRpt_date);
	//print_r($aDraws);
	//2.抓出今天到現在為止有開出獎號的期數序號
	$aResult=ser_get_now_result($sGame,$sDraws_sn,$sRpt_date);
	//print_r($aResult);
	//3.交叉1跟2找出不一樣的,就是需要重新要結果的期數序號,取得這些序號的期數名稱
	foreach((array)$aDraws as $key => $value){
		if(!in_array($value,$aResult)){
			$aDraws_nums[]=$value;
		}
	}
	//確認這些期數有沒有結果
	//如果沒有缺期數會是空陣列
	if(count($aDraws_nums)<1){
		return $ret;
	}
	//print_r($aDraws_nums);
	$hislist_num_list=ser_hislist_list_site_table($sGame,$aDraws);
	$hislist_date=mke_hislist_date_list_v2($sGame,$hislist_num_list,$aDraws_nums);
	$ret=inst_hislist_result($sGame,$hislist_date);
	return $ret;
}
//檢查昨天漏開結果 如果昨天的期數今天才開獎
/*
取得 昨天最後一期序號
取得 昨天最後一期名稱
取得 站台排名
依照站台的優先權 去跑迴圈 一個一個撈 有結果的塞進陣列
取出陣列第一個 因為已經是最優先的才會是在第一個位置
*/
function ser_chg_yesterday_result($sGame,$yesterday){
	$ret="";
	//取得 昨天最後一期序號
	$sDate_sn=ser_get_last_draws_seq($sGame,$yesterday);
	//取得 昨天最後一期名稱
	$sDraws_num=dws_get_draws_num($sGame,$yesterday,$sDate_sn);	
	//取得 站台排名
	$aSite=ser_get_error_cnt_rank($sGame);
	//*檢查 有沒有開過獎
	$chk_repeat=rst_sel_repeat_result($sGame,$sDraws_num);
	if($chk_repeat!=''|| !empty($chk_repeat)){return $ret;}
	//*依照站台的優先權 去跑迴圈 一個一個撈 有結果的塞進陣列
	foreach($aSite as $key =>$name){
		$ary_new=ser_get_lottery_site_list_exist($sGame,$name,$sDraws_num);
		if(count($ary_new)>1){
			$result[]=$ary_new;
		}
	}
	if(count($result)<1){
		return $ret;
	}
	//*取出陣列第一個 因為已經是最優先的才會是在第一個位置
	$aResult_list=$result[0];
	//*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	$lt_date=mke_lottery_date_list_v2($aResult_list,$sGame);
	//*新增開獎結果
	inst_lottery_result_v3($sGame,$lt_date);
	$sys_time=date("Y-m-d H:i:s");
	UPDATE_sys_time($sGame,$sDraws_num,$sys_time);
}
//檢查跨日盤漏開結果
/*
1.檢查那些期數有沒有少結果 
	*抓出今天到現在為止的所有期數序號
	*抓出今天到現在為止有開出獎號的期數序號
	*交叉1跟2找出不一樣的,就是需要重新要結果的期數序號,取得這些序號的期數名稱
2.如果少結果 到對方網站 抓歷史期數開獎結果
3.新增歷史期數開獎結果
*/
function ser_chg_night_result($sGame,$yesterday){
	$ret="";
	$draws_sn=ser_get_last_result_seq($sGame,$yesterday);
	if($draws_sn==''){
		return $ret;
	}
	$aDraws_nums=array();
	//1.抓出今天到現在為止的所有期數序號
	$aDraws=ser_get_now_draws($sGame,$draws_sn,$yesterday);
	//2.抓出今天到現在為止有開出獎號的期數序號
	$aResult=ser_get_now_result($sGame,$draws_sn,$yesterday);
	//3.交叉1跟2找出不一樣的,就是需要重新要結果的期數序號,取得這些序號的期數名稱
	foreach((array)$aDraws as $key => $value){
		if(!in_array($value,$aResult)){$aDraws_nums[]=$value;}
	}
	//確認這些期數有沒有結果
	//如果沒有缺期數會是空陣列
	if(count($aDraws_nums)<1){
		//如果有就退出程式
		return $ret;
	}
	//print_r($aDraws_nums);
	$hislist_num_list=ser_hislist_list_site_table($sGame,$aDraws);
	$hislist_date=mke_hislist_date_list_v2($sGame,$hislist_num_list,$aDraws_nums);
	inst_hislist_result($sGame,$hislist_date);
}
//新增歷史期數開獎結果
/*
傳入
	遊戲
	日期
*呼叫開獎結果
*把開獎結果拆解組裝成 sql語法 
*新增到資料庫
*/
function inst_hislist_result($game,$ary){
		global $db;
		switch($game){
		case 'klc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','total_sum','site'
			);
		break;
		case 'ssc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5','total_sum','site'
			);
		break;
		case 'pk':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'rank_1','rank_2','rank_3','rank_4','rank_5','rank_6'
				,'rank_7','rank_8','rank_9','rank_10','total_12','site'
			);
		break;
		case 'nc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','total_sum','site'
			);
		break;
		case 'kb':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','ball_9','ball_10','ball_11'
				,'ball_12','ball_13','ball_14','ball_15','ball_16','ball_17'
				,'ball_18','ball_19','ball_20'
				,'ball_fp','total_sum','site'
			);
		break;
	}
	$aSQL=array();
	$aSQL[]='INSERT INTO draws_[game]_result ([cols])';
	$aSQL[]='VALUES';
	$ret=0;
	$VALUES=array();
	foreach($ary as $key => $value){
		if(count($value)<count($col)){continue;}
		$sSite=$value['site'];
		inst_refer_cnt($game,$sSite);
		$v=$value;
		$draws=implode("','",$v);
		$VALUES[]="('$draws')";
	}
	if(empty($VALUES)){return $ret;}
	$aSQL[]='[VALUES]';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$sSQL=str_replace('[VALUES]',implode(',',$VALUES),$sSQL);
	//echo $sSQL." /n ";
	$q=$db->sql_query($sSQL);
	$ret=1;
	return $ret;
}
?>