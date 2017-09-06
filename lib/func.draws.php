<?php
//遊戲的期數相關函式
//產生期數用的陣列
//依照遊戲規則--對應期數時間
function init_draws($game){
	$gtype=dws_sel_gtype($game);
	$game_draws_DEF=sel_game_draws_DEF($gtype);
	//開始時間
	$time_start=$game_draws_DEF['time_start'];
	//結束時間
	$time_over=$game_draws_DEF['time_over'];
	//每盤間隔時間
	$interval_sec=$game_draws_DEF['interval_sec'];
	//可下注時間
	$betting_sec=$game_draws_DEF['betting_sec'];
	//第一盤的開盤時間
	$first_optime=strtotime(date("Y/m/d".$time_start));
	//第一盤關盤時間
	$first_fntime=$first_optime+$betting_sec;
	//最後一盤關盤時間
	$Last_fntime=strtotime(date("Y/m/d".$time_over));
	//第一盤開盤時間到最後一盤關盤時間的時間差
	$first_Last_time_gap=$Last_fntime-$first_optime;
	//取得當天期數
	//期數=第一盤開盤時間到最後一盤關盤時間的時間差-每盤間隔時間
	$max=round($first_Last_time_gap/$interval_sec);
	$ary=dws_draws_ary($game,$max,$first_fntime,$first_optime,$interval_sec);
	return $ary;
}
//產生每個期數所需要的資料 並存入陣列
/*
回傳
ary[當日序號]=[日期,當日序號,開盤時間,關盤時間]
*/
function dws_draws_ary($game,$max,$first_fntime,$first_optime,$interval_sec){
	//產生每個期數所需要的資料
	for ($j=1;$j<=$max;$j++){
		//$j=當日序號;
		//日期
		$date=date('Y-m-d');
		//期號
		//在第10盤以前 +0
		$dn=date('Ymd').'0'.$j;
		if($j>=10){$dn=date('Ymd').$j;}
		//每期關盤時間(秒)
		$draws_fntime=$first_fntime+$interval_sec*($j-1);
		//每期開盤時間(秒)
		$draws_optime=$first_optime+$interval_sec*($j-1);
		//轉換時間格式
		$fntime=date('Y-m-d H:i:s',$draws_fntime); 
		$optime=date('Y-m-d H:i:s',$draws_optime); 
		//每期的資訊 塞陣列 塞到最大期數為止
		//日期 期數編號 期數名稱 開盤時間 關盤時間
		$ary[$j]=array($date,$j,$dn,$optime,$fntime);
	}
	//print_r($ary);
	return $ary;
}
//用遊戲名稱到資料庫找出 gtype
function dws_sel_gtype($game){
	global $db_s;
	$SQL=array();
	$SQL[]='SELECT gtype FROM game_DEF';
	$SQL[]='WHERE notice_eng="[game]"';
	$sql=implode(' ',$SQL);	
	$sql=str_replace('[game]',$game,$sql);
	$q=$db_s->sql_query($sql);
	$r=$db_s->nxt_row('ASSOC');
	$ret=$r['gtype'];
	return $ret;
} 
//用gtype到資料庫找出 休息日
/*
回傳
[休息日開始日,休息日結束日,休息日中文名稱]
*/
function sel_game_rest_days($gtype){
	global $db_s;
	$SQL[]='SELECT rest_day_s,rest_day_e,message FROM game_rest_days';
	$SQL[]="WHERE gtype='[gtype]'";
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$r=$db_s->nxt_row();
	$ret=$r;
	return $ret;
} 
//用gtype到資料庫找出 遊戲規則
/*
回傳
[設定生效日,初始期數,開始時間,結束時間,期數名稱格式,每期間隔秒數,每期下注秒數]
*/
function sel_game_draws_DEF($gtype){
	global $db_s;
	$db_s->fetch_type='ASSOC';
	$SQL[]='SELECT';
	$SQL[]='define_date';
	$SQL[]=',init_num';
	$SQL[]=',time_start';
	$SQL[]=',time_over';
	$SQL[]=',num_format';
	$SQL[]=',interval_sec';
	$SQL[]=',betting_sec';
	$SQL[]='FROM game_draws_DEF';
	$SQL[]="WHERE gtype='[gtype]'";
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$r=$db_s->nxt_row();
	$ret=$r;
	return $ret;
} 
//資料庫值與陣列的值的對照表
function dws_state_ary(){
	$array=array();
	$array['010000']="2";
	$array['100000']="1";
	$array['000100']="3";
	$array['000000']="0";
	return $array;
}
//每頁要顯示的資料的日期
/*
	傳入{
		每頁資料最大筆數
		資料起始筆數
	}
	回傳{
		日期
	}
*/
function dws_get_rpt_date($page_rec,$page_start,$game){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='rpt_date';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE date_sn=1';
	$SQL[]='ORDER BY rpt_date DESC';
	$SQL[]='limit [page_start],[page_rec]';
	$SQL = str_replace('[game]',$game,$SQL);
	$SQL = str_replace('[page_rec]',$page_rec,$SQL);
	$SQL = str_replace('[page_start]',$page_start,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret[]=$r[0];
	}
	return $ret;
}
//每頁要顯示的資料的日期
/*
	傳入{
		每頁資料最大筆數
		資料起始筆數
	}
	回傳{
		日期
	}
*/
function dws_get_open_final_time($game,$rpt_date,$date_sn_max,$date_sn_min){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='MIN(open_time)';
	$SQL[]=',MAX(final_time)';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE date_sn IN([date_sn_min],[date_sn_max])';
	$SQL[]='AND rpt_date="[rpt_date]"';
	$SQL[]='ORDER BY open_time DESC';
	$SQL = str_replace('[game]',$game,$SQL);
	$SQL = str_replace('[rpt_date]',$rpt_date,$SQL);
	$SQL = str_replace('[date_sn_max]',$date_sn_max,$SQL);
	$SQL = str_replace('[date_sn_min]',$date_sn_min,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	$r=$db_s->nxt_row();
	$ret=$r;
	return $ret;
}
//計算有開獎的天數---總共有幾筆
/*	
	條件
		期數 第1期 
*/
function dws_get_total_row($game){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='count(rpt_date)';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE date_sn=1';
	$SQL = str_replace('[game]',$game,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret=$r[0];
	}
	return $ret;
}
//取得現在期數的資料
/*
  回傳:{
    rpt_date:報表日期
    date_sn:當日序號
    game_open:已開盤
    game_over:已關盤
    game_resulting:算帳中
    game_result:已算帳
  }
*/
function dws_get_now_draws_info($game){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' rpt_date';
	$aSQL[]=',date_sn';
	$aSQL[]=',draws_num';
	$aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
	$aSQL[]='AND open_time <= NOW()';
	$aSQL[]='ORDER BY rpt_date DESC,date_sn DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[game]',$game,$sSQL);
  $q=$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  return $r;
}
//取得現在期數的資料
/*
  回傳:{
    rpt_date:報表日期
    date_sn:當日序號
    game_open:已開盤
    game_over:已關盤
    game_resulting:算帳中
    game_result:已算帳
  }
*/
function dws_get_now_lottery_info($game){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' rpt_date';
	$aSQL[]=',date_sn';
	$aSQL[]=',draws_num';
	$aSQL[]=',lottery_time';
	$aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
	$aSQL[]='AND lottery_time <= NOW()';
	$aSQL[]='ORDER BY rpt_date DESC,date_sn DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[game]',$game,$sSQL);
  $q=$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  return $r;
}
//取當天最新一期開盤狀態
/*
回傳
  [期數]
  當天最新一期開盤狀態
*/
function dws_final_state($game,$rpt_date){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='game_open';
	$aSQL[]=',game_over';
	$aSQL[]=',game_resulting';
	$aSQL[]=',game_result';
	$aSQL[]=',game_posting';
	$aSQL[]=',game_posted';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE';
	$aSQL[]='rpt_date="[rpt_date]"';
	$aSQL[]='AND open_time <= NOW()';
	//$aSQL[]='AND final_time <=NOW()';
	$aSQL[]='ORDER BY date_sn DESC';
	$aSQL[]='LIMIT 1';
	$sql=implode(' ',$aSQL);	
	$sql=str_replace('[game]',$game,$sql);
	$sql=str_replace('[rpt_date]',$rpt_date,$sql);
	$q=$db_s->sql_query($sql);
	$ret=array();
	$r=$db_s->nxt_row();
	$ret=$r[0].$r[1].$r[2].$r[3].$r[4].$r[5];
	return $ret;
}
//取得現在的報表日期
/*
	$game=遊戲名稱
	回傳:
	現在沒有期數: false
	有期數: 期數的報表日期
*/
function dwa_get_now_rpt_date($game){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='rpt_date';
	$aSQL[]='FROM `draws_[game]`';
	$aSQL[]='WHERE NOW() BETWEEN open_time AND final_time';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$q=$db_s->sql_query($sSQL);
	if($db_s->numRows()<1){return false;}
	$r=$db_s->nxt_row('ASSOC');
	return $r['rpt_date'];
}
//取得該玩法當日最大期數
/*
回傳
  最大期數
*/
function dws_date_sn_max($game,$rpt_date){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='max(date_sn)';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE rpt_date="[rpt_date]"';
	$SQL = str_replace('[game]',$game,$SQL);
	$SQL = str_replace('[rpt_date]',$rpt_date,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	$r=$db_s->nxt_row();
	$ret=$r[0];
	return $ret;
}
//取的該玩法當日最小期數
/*
回傳
  最小期數
*/
function dws_date_sn_min($game,$rpt_date){
	global $db_s;
	$db_s->fetch_type='NUM';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='min(date_sn)';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE rpt_date="[rpt_date]"';
	$SQL = str_replace('[game]',$game,$SQL);
	$SQL = str_replace('[rpt_date]',$rpt_date,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	$r=$db_s->nxt_row();
	$ret=$r[0];
	return $ret;
}
//---服務:新增一日期數
//檢查某個遊戲某一天是否休息
/*
	$gtype=遊戲編號,$date=日期
	回傳:
		工作=false
		休息=true
*/
function dws_chk_game_rest($iGtype,$date){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='COUNT(sn) AS cnt';
	$aSQL[]='FROM game_rest_days';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND gtype="[gtype]"';
	$aSQL[]='AND "[date]" BETWEEN rest_day_s AND rest_day_e';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[gtype]',$iGtype,$sSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	$ret=($r['cnt']>0)?true:false;
	return $ret;
}
//抓取某個遊戲的期數新增設定
/*
	$gtype=遊戲編號,$date=設定日
	回傳[]={
		time_s:開始時間
		time_e:結束時間
		forward_sec:開關盤提前秒數
		int_sec:開盤時間的間隔
		bet_sec:可下注的時間
		num_format:期數的格式
    draws_set:日夜場次
	}
	*抓取距離現在最近的生效日
	*根據生效日抓取期數新增設定
	*160617:增加提前秒數
  *160802:增加日夜場次
*/
function dws_get_draws_inst_def($gtype,$date){
	global $db_s;
	$ret=array();
	$aDate=explode('-',$date);
	$iDate='1'.$aDate[1].$aDate[2];//判斷開始月日以及結束月日用的
	// *抓取距離現在最近的生效日
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='define_date';
	$aSQL[]='FROM game_draws_DEF';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND gtype="[gtype]"';
	$aSQL[]='AND define_date <="[date]"';
	$aSQL[]='ORDER BY define_date DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	$sDate=$r['define_date'];
	if($sDate==''){return $ret;}
	// *根據生效日抓取期數新增設定
	$aSQL=array();	
	$aSQL[]='SELECT';
	$aSQL[]=' time_start AS time_s';
	$aSQL[]=',time_over AS time_e';
	$aSQL[]=',forward_sec';
	$aSQL[]=',cross_day';
	$aSQL[]=',interval_sec AS int_sec';
	$aSQL[]=',betting_sec AS bet_sec';
	$aSQL[]=',num_format';
	$aSQL[]=',draws_set';
	$aSQL[]='FROM game_draws_DEF';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND gtype="[gtype]"';
	$aSQL[]='AND define_date="[define_date]"';
	$aSQL[]='AND "[iDate]" BETWEEN day_begin AND day_over';
	$aSQL[]='ORDER BY define_sn ASC';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[gtype]',$gtype,$sSQL);	
	$sSQL=str_replace('[define_date]',$sDate,$sSQL);	
	$sSQL=str_replace('[iDate]',$iDate,$sSQL);	
	$q=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r;
	}
	return $ret;
}
//產生期數資料陣列
/*
	$rpt_date=報表日期
	$draws_def=期數設定
	期數設定[]={
		time_s:開始時間
		time_e:結束時間
		forward_sec:開關盤提前秒數
		cross_day:是否為報表日期的隔一日
		int_sec:開盤時間的間隔
		bet_sec:可下注的時間
		num_format:期數的格式
    draws_set:日夜場次    
	}
	$sLast_draws_num=前一天最後一期的期數名稱
	*抓出前一天最後一期編號
	*依序處理每條設定{
		*設定開始與結束的時戳
		*迴圈:時戳沒超過結束時戳就繼續
			*開盤時間
			*關盤時間
			*期數名稱:先把代碼替換掉再做算術運算
	}
	回傳[]:{
		rpt_date:報表日期
		date_sn:當日序號
		draws_set:日夜場次
		draws_num:期數名稱
		open_time:開盤時間
		final_time:關盤時間
		lottery_time:開獎時間
	}
	*160617:加上提前秒數的機制
  *160802:增加日夜場次  
*/
function dws_mke_draws_data_list($rpt_date,$draws_def,$sLast_draws_num){
	$iSn=1;	
	$iThis_day_Stmp=strtotime($rpt_date.' 00:00:00');//當天的起始時戳
	$iNext_day_Stmp=$iThis_day_Stmp+86400;//隔天的起始時戳
	$rpt_date_next=date('Y-m-d',$iNext_day_Stmp);
	$iThis_day_Ymd=date('Ymd',$iThis_day_Stmp);
	$iNext_day_Ymd=date('Ymd',$iNext_day_Stmp);
	$ret=array();
	// *依序處理每條設定
	foreach($draws_def as $k => $def){
		$cross_day=$def['cross_day'];
		$time_s=$def['time_s'];
		$time_e=$def['time_e'];
		$forward_sec=$def['forward_sec'];
		$draws_set=$def['draws_set'];
		$sDate=($cross_day=='Y')?$rpt_date_next:$rpt_date;
		// *設定開始與結束的時戳
		$iStmp_s=strtotime($sDate.' '.$time_s);
		$iStmp_e=strtotime($sDate.' '.$time_e);
		$iStmp_open=$iStmp_s;
		// *提前秒數的機制
		// $iStmp_open-=$forward_sec;
		// *迴圈:時戳沒超過結束時戳就繼續
		$iLoop_sn=1;
		while($iStmp_open<$iStmp_e){
			$iStmp_close=$iStmp_open+$def['bet_sec'];
			$iStmp_lottery=$iStmp_open+$def['int_sec'];
			// *開關盤時間要扣掉提前秒數
			$sTime_open=date('Y-m-d H:i:s',$iStmp_open-$forward_sec);//開盤時間
			$sTime_close=date('Y-m-d H:i:s',$iStmp_close-$forward_sec);//關盤時間
			$sTime_lottery=date('Y-m-d H:i:s',$iStmp_lottery);//關盤時間
			// *期數名稱:先把代碼替換掉再做算術運算
			$draws_num=$def['num_format'];
			$draws_num=str_replace('[rpt_date_Ymd]',$iThis_day_Ymd,$draws_num);//報表日期
			$draws_num=str_replace('[rpt_date+1_Ymd]',$iNext_day_Ymd,$draws_num);//報表日期隔天
			$draws_num=str_replace('[day_sn]',$iSn,$draws_num);//當天序號
			$draws_num=str_replace('[2sn]',str_pad($iSn,2,'0',STR_PAD_LEFT),$draws_num);//2位數序號
			$draws_num=str_replace('[3sn]',str_pad($iSn,3,'0',STR_PAD_LEFT),$draws_num);//3位數序號
			$draws_num=str_replace('[2loop_sn]',str_pad($iLoop_sn,2,'0',STR_PAD_LEFT),$draws_num);//2位數迴圈次數
			$draws_num=str_replace('[3loop_sn]',str_pad($iLoop_sn,3,'0',STR_PAD_LEFT),$draws_num);//3位數迴圈次數
			$draws_num=str_replace('[last_num]',$sLast_draws_num,$draws_num);//前一天最後一期
			if(strpos($draws_num,'+')!=false){
				//算術運算
				$aTmp=explode('+',$draws_num);
				$draws_num=$aTmp[0]+$aTmp[1];
			}
			// echo "[$iSn,$draws_num,$sTime_open,$sTime_close]\n";
			$aTmp=array(
				 'rpt_date'  =>$rpt_date
				,'date_sn'   =>$iSn
        ,'draws_set' =>$draws_set
				,'draws_num' =>$draws_num
				,'open_time' =>$sTime_open
				,'final_time'=>$sTime_close
				,'lottery_time'=>$sTime_lottery
			);
			$ret[]=$aTmp;
			//---
			$iSn++;
			$iLoop_sn++;
			$iStmp_open+=$def['int_sec'];
		}
	}
	return $ret;
}
//取得所有 已經到了開獎時間的期數
/*
	 $sGame			=	彩票類型
	,$rpt_date	=	開獎日期
*/
function dws_get_all_lottery_draws($sGame,$rpt_date){
	global $db_s;
	$ret=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM';
	$aSQL[]='draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$aSQL[]='AND lottery_time <= NOW()';
	$aSQL[]='ORDER BY rpt_date DESC,date_sn DESC';
	$sSQL=implode(' ',$aSQL);
	$sSQL= str_replace('[game]',$sGame,$sSQL);
	$sSQL= str_replace('[rpt_date]',$rpt_date,$sSQL);
	$q=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r['draws_num'];
	}
	return $ret;
}
//新增期數
/*
	$game=遊戲代碼
	$draws_data=期數資料
	*160616:加上開獎時間
  *160802:增加日夜場次
*/
function dws_ins_draws($game,$draws_data){
	global $db;
	$cols=array( 'rpt_date','date_sn','draws_set','draws_num','open_time','final_time','lottery_time' );
	$sInsSQL='INSERT INTO draws_[game] (`[cols]`)VALUES';
	$sInsSQL=str_replace('[game]',$game,$sInsSQL);
	$sInsSQL=str_replace('[cols]',implode('`,`',$cols),$sInsSQL);
	$sValSQL='("[[cols]]")';
	$sValSQL=str_replace('[cols]',implode(']","[',$cols),$sValSQL);
	$aValSQL=array();
	foreach($draws_data as $k => $v){
		$sSQL=$sValSQL;
		foreach($cols as $sn => $col){
			$sSQL=str_replace("[$col]",$v[$col],$sSQL);
		}
		$aValSQL[]=$sSQL;
	}
	$sSQL=$sInsSQL.implode(',',$aValSQL);
	$q=$db->sql_query($sSQL);
}
//刪除某一天,某個遊戲的期數
/*
	*如果已經有期數,就砍了
*/
function dws_del_draws($game,$date){
	global $db;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='COUNT(id) AS cnt';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db->sql_query($sSQL);	
	$r=$db->nxt_row('ASSOC');
	if($r['cnt']<1){return;}
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db->sql_query($sSQL);	
}
//抓取某一天的最後一筆期數名稱
/*
	$date=日期
	回傳:{
		rpt_date=那天日期
		draws_num=最後編號
	}
*/
function dws_get_last_data($gtype,$date){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' rpt_date';
	$aSQL[]=',draws_num';
	$aSQL[]='FROM draws_num_last';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND gtype = "[gtype]"';
	$aSQL[]='AND rpt_date < "[date]"';
	$aSQL[]='ORDER BY rpt_date DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	return $r;
}
//新增日期與期號的對照表
/*
	$sGame=遊戲,$aDate_set=資料
	資料[]={
		rpt_date:日期
		draws_num:期數編號
	}
	*160616:已經有資料就更新
*/
function dws_ins_date_draws_num($iGtype,$aDate_set){
	global $db;
	$aSQL=array();
	$sInsSQL='INSERT INTO draws_num_last(gtype,rpt_date,draws_num) VALUES';
	$sValSQL='("[gtype]","[rpt_date]","[draws_num]") ON DUPLICATE KEY UPDATE draws_num = "[draws_num]"';
	$aValSQL=array();
	foreach($aDate_set as $k => $v){
		$sSQL=$sValSQL;
		$sSQL=str_replace('[gtype]',$iGtype,$sSQL);
		$sSQL=str_replace('[rpt_date]',$v['rpt_date'],$sSQL);
		$sSQL=str_replace('[draws_num]',$v['draws_num'],$sSQL);
		$aValSQL[]=$sSQL;
	}
	$sSQL=$sInsSQL.implode(',',$aValSQL);
	$q=$db->sql_query($sSQL);
}
//---服務:自動開關盤---
//抓取某遊戲要開盤的流水號
/*
傳入 
	$game=遊戲
回傳
	[流水號,流水號,...]
*/
function dws_get_draws_on($game){
	global $db_s;
	$db_s->fetch_type='ASSOC';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='id';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE';
	$SQL[]='open_time <= NOW() ';
	$SQL[]='AND final_time >=NOW() ';
	$SQL[]='AND game_over=0';
	$SQL[]='AND game_open=0';
	$SQL[]='AND game_resulting=0';
	$SQL[]='AND game_result=0';
	$SQL = str_replace('[game]',$game,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret[]=$r['id'];
	}
	return $ret;
}
//抓取某遊戲要關盤的流水號
/*
	$game=遊戲
回傳
		[流水號,流水號,...]
*/
function dws_get_draws_off($game){
	global $db_s;
	$db_s->fetch_type='ASSOC';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='id';
	$SQL[]='FROM draws_[game]';
	$SQL[]='WHERE';
	$SQL[]='final_time <= NOW() ';
	$SQL[]='AND (game_open=1 OR game_over=0)';
	$SQL[]='AND game_resulting=0';
	$SQL[]='AND game_result=0';
	$SQL = str_replace('[game]',$game,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db_s->sql_query($sql);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret[]=$r['id'];
	}
	return $ret;
}
//設定開盤
/*
  開盤條件:
  *到達開盤時間
  *未達關盤時間
  *沒有強制關盤
  *還沒算帳
*/
function dws_set_draws_on($game,$draws_ids){
	global $db;
	$ids=implode(',',$draws_ids);
	$sql ='UPDATE draws_[game] ';
	$sql.='SET game_open="1" ,game_over="0"';
	$sql.='WHERE ';
	$sql.='id IN ([id])';//需要開盤的流水號
	$sql = str_replace('[game]',$game,$sql);
	$sql = str_replace('[id]',$ids,$sql);	
	$q=$db->sql_query($sql);
}
//取得某天有幾期
/*
	 $sGame			=	彩票類型
	,$rpt_date	=	開獎日期
*/
function dws_get_cnt_draws($sGame,$rpt_date){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='count(date_sn) AS cnt_sn';
	$aSQL[]='FROM';
	$aSQL[]='draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL= str_replace('[game]',$sGame,$sSQL);
	$sSQL= str_replace('[rpt_date]',$rpt_date,$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	$ret=$r['cnt_sn'];
	return $ret;
}
//檢查某天某個遊戲 是否有期數
/*
	傳入:
		$sGame
		$sRpt_date
	回傳:
		true:有期數 
		false:沒有
*/
function dws_chk_draws($sGame,$sRpt_date){
	global $db;
	$sRet=false;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='COUNT(id) AS cnt';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[rpt_date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$q=$db->sql_query($sSQL);
	$r=$db->nxt_row('ASSOC');
	if($r['cnt']<1){return $sRet;}
	$sRet=true;
	return $sRet;
}
//檢查某天某個遊戲 某個序號的期數名稱
/*
	傳入:
		$sGame
		$sRpt_date
		$sDate_sn
	回傳:
		期數名稱
*/
function dws_get_draws_num($sGame,$sRpt_date,$sDate_sn){
	global $db_s;
	$sRet=false;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[rpt_date]"';
	$aSQL[]='AND date_sn = "[date_sn]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$sSQL=str_replace('[date_sn]',$sDate_sn,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	$sRet=$r['draws_num'];
	return $sRet;
}
//檢查某遊戲 某些期數的預估開獎時間
/*
	傳入:
		$sGame
		$sDraws_num
	回傳:
*/
function dws_get_draws_lt_times($sGame,$aDraws_num){
	global $db_s;
	$aRet=array();
	$aSQL=array();
	if(count($aDraws_num)<1){return $aRet;}
	$aSQL[]='SELECT';
	$aSQL[]='lottery_Time';
	$aSQL[]=',draws_num';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num IN("[draws_num]")';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[draws_num]',implode('","',$aDraws_num),$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	if($db_s->affected_rows == 0){ return $aRet;}
	while($r = $db_s->nxt_row('ASSOC')){
		$sDraws_num=$r['draws_num'];
		$aRet[$sDraws_num]=$r['lottery_Time'];
	}
	return $aRet;
}
//設定關盤
/*
  關盤條件:
  *未達開盤時間
  *超過關盤時間
  *已有結果
*/
function dws_set_draws_off($game,$draws_ids){
	global $db;
	$sql ='UPDATE draws_[game] ';
	$sql.='SET game_open="0" ,game_over="1"';
	$sql.='WHERE ';
	$sql.='id IN ([id])';//需要開盤的流水號
	$sql = str_replace('[game]',$game,$sql);
	$sql = str_replace('[id]',implode(',',$draws_ids),$sql);	
	$q=$db->sql_query($sql);
}
?>