<?php
//API的相關函式
//---公用變數---
$_aGame_col=array(
   'klc'=>array( 'col'=>'ball' ,'count'=>'8' )
  ,'ssc'=>array( 'col'=>'ball' ,'count'=>'5' )
  ,'pk' =>array( 'col'=>'rank' ,'count'=>'10')
  ,'nc' =>array( 'col'=>'ball' ,'count'=>'8' )
  ,'kb' =>array( 'col'=>'ball' ,'count'=>'20')
);
//---公用---
//從GET或者POST抓取資料
/*
	$method=G / P
	從GET或者POST抓值在裏頭綁定,方便
*/
function api_get_para($para,$method=''){
	$method=strtoupper(trim($method));
	$ary_mth=array(
		 'G'	=> '_GET'
		,'P'	=> '_POST'
		,'C'	=> '_COOKIE'
	);
	if(!isset($ary_mth[$method])){$method='G';}
	$src=$ary_mth[$method];
	$str='$ret=$'."$src"."['$para'];";
	eval($str);
	return $ret;
}
//偽裝成404結束
function api_fake_404(){
	header('HTTP/1.0 404 Not Found');
	exit;
}
//檢查參數是否有問題
/*
  $aPara=參數陣列,$aKey=必須要有的key
  回傳:
    true:該給的都有給
*/
function api_chk_para_key($aPara,$aKey){
  $aPKey=array_keys($aPara);
  if(count($aKey)<1){return true;}
  foreach($aKey as $k => $sKey){
    if(!in_array($sKey,$aKey)){return false;}
    return true;
  }
}
//檢查遊戲是否允許
function api_chk_game($sGame){
  global $_aEdit_Gtype;
  $aEdit_Gtype=$_aEdit_Gtype;
  $aEdit_Gtype[13]='pk';
  $aGame=array_values($aEdit_Gtype);
  if(!in_array($sGame,$aGame)){return false;}
  return true;
}
//---
//取今天最後一期名稱
/*
  $sGame=遊戲代碼
  回傳:[seq_name]:期數名稱
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期
*/
function api_get_last_seq($sGame){
  // echo "api_get_last_seq($sGame)\n<br>";
  global $db_s;
  global $web_cfg;  
  include_once($web_cfg['path_lib'].'func.draws.php');
  $aRet=array();
  // *先查出現在時間屬於哪個開獎日期
  $aNow_draws=dws_get_now_draws_info($sGame);
  $rpt_date=$aNow_draws['rpt_date'];
	if(!isset($rpt_date)){return $aRet;}
  // *拿開獎日期去查出當天最後一期
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_num=$r['draws_num'];
  $aRet['seq_name']=$draws_num;
  return $aRet;
}
//取今天最後一期有結果的期數名稱 和 當日序號
/*
  $sGame=遊戲代碼
  回傳:
	[seq_name]:期數名稱
	[date_sn]:當日序號
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期開獎結果
*/
function api_get_last_result_seq($sGame){
  // echo "api_get_last_result_seq($sGame)\n";
  global $db_s;
  global $web_cfg;  
  include_once($web_cfg['path_lib'].'func.draws.php');
  $aRet=array();
  // *先查出現在時間屬於哪個開獎日期
  $aNow_draws=dws_get_now_draws_info($sGame);
  $rpt_date=$aNow_draws['rpt_date'];
	if(!isset($rpt_date)){return $aRet;}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]=',date_sn';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
	//echo $sSQL;
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_num=$r['draws_num'];
  $date_sn=$r['date_sn'];
  $aRet['seq_name']=$draws_num;
  $aRet['date_sn']=$date_sn;
  return $aRet;
}
//取最後一期有結果的期數名稱 和當日序號
/*
  $sGame=遊戲代碼
  回傳:
	[seq_name]:期數名稱
	[date_sn]:當日序號
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期開獎結果
*/
function api_get_last_result($sGame){
  global $db_s;
  global $web_cfg;  
  $aRet=array();
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]=',date_sn';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND draws_num=(';
  $aSQL[]='SELECT';
	$aSQL[]='MAX(draws_num)';
	$aSQL[]='FROM draws_[game]_result';
  $aSQL[]=')';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_num=$r['draws_num'];
  $date_sn=$r['date_sn'];
  $aRet['seq_name']=$draws_num;
  $aRet['date_sn']=$date_sn;
  return $aRet;
}
//取某期的期數資料
/*
  $sGame=遊戲代號
  ,$sDraws_num=期數名稱
  回傳:{
    seq_name:期數名稱
    ,time_start:開始時間
    ,time_end:結束時間
    ,time_lottery:開獎時間
  }
  *160817:加上開獎時間
*/
function api_get_seq_data($sGame,$sDraws_num){
  global $db_s;
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]=' draws_num AS seq_name';
  $aSQL[]=',open_time AS time_start';
  $aSQL[]=',final_time AS time_end';
  $aSQL[]=',lottery_time AS time_lottery';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND draws_num="[draws_num]"';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');  
  $aRet=$r;
  return $aRet;
}
//取某期的開獎結果
/*
  $sGame=遊戲代碼,$sDraws_num=期數名稱
  回傳:{
  　result_1:開獎結果,
  　result_2:開獎結果,
  　result_3:開獎結果,
  　:
  }
  *各遊戲的欄位不盡相同,要做好轉換
  *資料庫內的欄位,除了,北京賽車是rank,其他都是ball
*/
function api_get_seq_result($sGame,$sDraws_num){
  global $db_s;
  global $_aGame_col;
  $aGame_col=$_aGame_col;
  //開獎結果的欄位  
  $aRst_col=array();
  $sCol=$aGame_col[$sGame]['col'];
  $iCount=$aGame_col[$sGame]['count'];
  for($i=1;$i<=$iCount;$i++){
    $aRst_col[]=$sCol.'_'.$i.' AS result_'.$i;
  }
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='[result_col]';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND draws_num="[draws_num]"';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
  $sSQL=str_replace('[result_col]',implode(',',$aRst_col),$sSQL);
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $aRet=$r;
  return $aRet;
}
//取某遊戲某天的期數列表
/*
  回傳:{
  　draws_list:[期數名稱,期數名稱,...]
  　draws_data:[
  　　{
  　　　seq_name:期數名稱
  　　　,time_start:開始時間
  　　　,time_end:結束時間　　　
  　　　,time_lettory:開獎時間　　　
  　　},
  　　：
  　]
  }
  *如果沒有限定日期,查出現在時間屬於哪個開獎日期
  *列出那一天的所有期數資料
  *160822:如果沒有限定日期,就抓出現在的帳務日期
*/
function api_get_day_draws($sGame,$sDate=''){
  // echo "api_get_day_draws($sGame)";
  global $db_s;
  global $web_cfg;  
  include_once($web_cfg['path_lib'].'func.draws.php');
  $aRet=array();
  $draws_list=array();
  $draws_data=array();
  // *如果沒有限定日期,查出現在時間屬於哪個開獎日期
  if(strtotime($sDate)!=false){
    $rpt_date=$sDate;
  }else{
    $aNow_draws=dws_get_now_draws_info($sGame);
    $rpt_date=$aNow_draws['rpt_date'];  
  }
  // *列出那一天的所有期數資料
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]=' draws_num AS seq_name';
  $aSQL[]=',open_time AS time_start';
  $aSQL[]=',final_time AS time_end';
  $aSQL[]=',lottery_time AS time_lottery';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn ';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
    $draws_list[]=$r['seq_name'];
    $draws_data[]=$r;
  }
  $aRet['draws_list']=$draws_list;
  $aRet['draws_data']=$draws_data;
  return $aRet;  
}
//取某遊戲某天的開獎結果
/*
  回傳:{
  　draws_data:[
  　　{
  　　　seq_name:期數名稱　
           result_1:開獎結果,
  　       result_2:開獎結果,
  　       result_3:開獎結果,
  　       :
  　　},
  　　：
  　]
  }
  *如果沒有限定日期,查出現在時間屬於哪個開獎日期
  *列出那一天的所有開獎結果
  *160822:如果沒有限定日期,就抓出現在的帳務日期
*/
function api_get_day_result($sGame,$sDate=''){
  // echo "api_get_day_draws($sGame)";
  global $db_s;
  global $web_cfg;  
  include_once($web_cfg['path_lib'].'func.draws.php');
  include_once($web_cfg['path_lib'].'func.ser_result.php');
  global $_aGame_col;
  $aGame_col=$_aGame_col;
  //開獎結果的欄位
  $aRst_col=array();
  $sCol=$aGame_col[$sGame]['col'];
  $iCount=$aGame_col[$sGame]['count'];
  for($i=1;$i<=$iCount;$i++){
    $aRst_col[]=$sCol.'_'.$i.' AS result_'.$i;
  }
  $aRet=array();
  $draws_list=array();
  $draws_data=array();
  // *如果沒有限定日期,查出現在時間屬於哪個開獎日期
  if(strtotime($sDate)!=false){
    $rpt_date=$sDate;
  }else{
    $aNow_draws=dws_get_now_draws_info($sGame);
    $rpt_date=$aNow_draws['rpt_date'];  
  }
	//昨天的最後一期
	$yesterday_last_draws=ser_get_yesterday_last_draws($sGame,$rpt_date);
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]=' draws_num AS seq_name';
  $aSQL[]=',[result_col]';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn ';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
  $sSQL=str_replace('[result_col]',implode(',',$aRst_col),$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
    $draws_data[]=$r;
  }
	if($sGame=='kb'){
		if($yesterday_last_draws!=''){
			$last_result=api_get_yesterday_last_result($sGame,$yesterday_last_draws);
			if(count($last_result)>1){
				array_unshift($draws_data,$last_result);
			}
		}
	}
  $aRet['draws_data']=$draws_data;
  return $aRet;  
}
//取昨天最後一期 開獎結果期數
/*
  $sGame=遊戲代號
	$sRpt_date=報表日期
  回傳:{
		$sRet=該日期 前一天的期數
  }
*/
function api_get_yesterday_last_result($sGame,$yesterday_last_draws){
  global $db_s;
	global $_aGame_col;
  $aGame_col=$_aGame_col;
	$aRst_col=array();
  $sCol=$aGame_col[$sGame]['col'];
  $iCount=$aGame_col[$sGame]['count'];
  for($i=1;$i<=$iCount;$i++){
    $aRst_col[]=$sCol.'_'.$i.' AS result_'.$i;
  }
	$aRet=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num AS seq_name';
	$aSQL[]=',[result_col]';
	$aSQL[]='FROM draws_[game]_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num = "[draws_num]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[draws_num]',$yesterday_last_draws,$sSQL);
	$sSQL=str_replace('[result_col]',implode(',',$aRst_col),$sSQL);
	$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $aRet;}
	$r=$db_s->nxt_row('ASSOC');
	$aRet=$r;
  return $aRet;
}
//取某遊戲某天的開獎結果
/*
	傳入:
		$sGame=遊戲
	回傳:
		已經排好次序的 站台
	//每個遊戲是分開計
*/
function api_get_error_cnt_rank($sGame){
  global $db_s;
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='site';
  $aSQL[]=',SUM(refer_cnt-error_cnt) AS cnt';
  $aSQL[]='FROM site_[game]_error_cnt';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND site!="0"';
  $aSQL[]='GROUP by site';
  $aSQL[]='ORDER BY cnt DESC';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
    $aRet[]=$r['site'];
  }
  return $aRet;  
}
//新增一筆 採用站台紀錄
/*
	傳入
		遊戲
		站台
	當站台被選用時 
	新增一筆紀錄 錯誤次數1 踩用次數-5 的紀錄 用於計算優先權
*/
function inst_error_cnt($sGame,$sSite){
	global $db;
	$aSQL=array();
	$col=array('site','error_cnt','refer_cnt');
	$value=array($sSite,'1','-5');
	$aSQL[]='INSERT INTO site_[game]_error_cnt ([cols])';
	$aSQL[]='VALUES';
	$aSQL[]='("[value]")';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[value]',implode('","',$value),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$q=$db->sql_query($sSQL);
}
function api_get_draws_num_last(){
	global $db_s;
	$aRet=array();
	$aSQL[]='SELECT';
	$aSQL[]='*';
	$aSQL[]='FROM `draws_num_last`';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_date]',date('Y-m-d'),$sSQL);
	$db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r;
	}
	return $aRet;
}
//取現在時間
function api_get_now_time(){
  global $db_s;
  $sSQL='SELECT NOW() AS now_time LIMIT 1';
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $aRet=$r;
  return $aRet;
}
//取現在時間戳
function api_get_now_timestamp(){
  global $db_s;
  $sSQL='SELECT UNIX_TIMESTAMP(NOW())  AS now_time LIMIT 1';
  $db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $aRet=$r;
  return $aRet;
}
//取得今日error_log
/*

*/
function api_view_daily_log($sPARA){
	global $web_cfg;
	$aRet=array();
	$sPARA=($sPARA=='')?'':'_'.$sPARA;
	$sDate=($sPARA=='')?'_'.date('Ymd'):'';
	$sFile=$web_cfg['path_text'].'sql_error_log'.$sPARA.$sDate.'.log';
	//echo $sFile;
	$str='';
	if(!file_exists($sFile)){return 'NO Error_msg';}
	$File=fopen($sFile,"r");
	while(!feof($File)){
		$str.=fgets($File);
	}
	fclose($File);
	$str=trim($str);
	/*
	echo '<xmp>';
	print_r($str);
	echo '</xmp>';
	*/
	return $str;
}
//將讀出來的資料作成表格
/*
	因為有些紀錄 
	可能需要轉成
	html 在eip呈現比較不會跑版
*/
function api_data_to_table($str){
	$aRET=array();
	$aRET[]='如果要查 不是今天的log 請在網址列後面&p=Ymd';
	if(!$str || $str=='NO Error_msg'){
		$aRET[]='</br> NO Error_msg';
		$sRET=implode(" \n",$aRET);
		return $sRET;
	}
	//$ary_td=array();
	$ary_td=explode("\n",$str);
	$aRET[]='<table  style="border:4px #0000ff solid;" cellpadding="3" border="0">';
	foreach($ary_td as $k => $col){
		$aRET[]='<tr>';
		$aRET[]="<td>".$col."</td>";		
		$aRET[]='</tr>';
	}
	$aRET[]='</table>';
	$sRET=implode(" \n",$aRET);
	return $sRET;
}
//取某期 某站台這個開獎結果 是否存在
/*
  $sGame=遊戲代碼
  回傳:
	$aRet:開獎號碼陣列
*/
function api_get_lottery_site_list($sGame,$sSite){
  global $db_s;
  $aRet=array();
	switch($sGame){
		case 'klc':
			$column=array(
				'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
			);
		break;
		case 'ssc':
			$column=array(
				'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
			);
			break;
		case 'pk':
			$column=array(
				'rank_1'
				,'rank_2'
				,'rank_3'
				,'rank_4'
				,'rank_5'
				,'rank_6'
				,'rank_7'
				,'rank_8'
				,'rank_9'
				,'rank_10'
			);
			break;
		case 'nc':
			$column=array(
				'ball_1'
				,'ball_2'
				,'ball_3'
				,'ball_4'
				,'ball_5'
				,'ball_6'
				,'ball_7'
				,'ball_8'
			);
		break;
		case 'kb':
			$column=array(
				'ball_1'
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
			);
		break;
	}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]=',lottery_Time';
  $aSQL[]=',[column]';
  $aSQL[]='FROM site_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND site="[site]"';
	$aSQL[]='ORDER BY `lottery_Time` DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[site]',$sSite,$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
	if(count($r)<1){return $aRet;}
	$aRet['game']=$sGame;
  $aRet['draws']=$r['draws_num'];
  $aRet['time']=$r['lottery_Time'];
  $aNum=array();
	foreach($column as $k => $col){
		$aNum[]=$r[$col];
	}
	$aRet['code']=implode(',',$aNum);
  return $aRet;
}
//取某期 某張站台表這個開獎結果 是否存在
/*
  $sGame=遊戲代碼
  回傳:
	$aRet:開獎號碼陣列
*/
function api_get_lottery_table_list($sGame,$sTable,$sDraws_num,$lottery_Time){
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
		break;
	}
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='gid';
  $aSQL[]=',[column]';
  $aSQL[]='FROM [sTable]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND gid="[draws_num]"';
  $aSQL[]='AND gtype="[gtype]"';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[sTable]',$sTable,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
  $sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
	if($db_s->affected_rows==0){return $aRet;}
  if(count($r)<1){return $aRet;}
	$aRet['game']=$sGame;
  $aRet['draws']=$r['gid'];
  $aRet['time']=$lottery_Time;
  $aNum=array();
	foreach($column as $k => $col){
		$aNum[]=$r[$col];
	}
	$aRet['code']=implode(',',$aNum);
  return $aRet;
}
//取現在獎號中心的所有遊戲
function api_get_all_game(){
  global $db_s;
  $sSQL='SELECT notice_eng FROM game_DEF';
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r['notice_eng'];
	}
  return $aRet;
}
//取現在獎號中心的背景執行狀態
function api_get_BG_status(){
	global $web_cfg;
	include_once($web_cfg['path_conf'].'ser_config.php');
	$cmd='ps -ef | grep [file_trigger] | awk \'{print $2 "," $7 "," $9}\'';
	$cmd=str_replace('[file_trigger]',path_Trigger.file_trigger,$cmd);
	//echo $cmd;
	$aRet=array();
	exec($cmd, $output, $return_var);
	if(count($output)<1){return;}
	foreach($output AS $key => $msg) {
		/*將$output 值轉成陣列*/
		$tmp=explode(',',$msg);
		/*把執行時間存進 $aruntime*/
		$aname[$key]=$tmp[2];
		$aruntime[$key]=$tmp[1];
		$aPID[$key]=$tmp[0];
	}
	foreach($output AS $key => $msg) {
		$iruntime=$aruntime[$key];
		/*如果 crontrigger 的執行時間 跟最大值行時間一樣 就是crontrigger 本體*/
		if($iruntime=='00:00:00'){continue;}
		if($iruntime==max($aruntime)){
			//得到本體的 pid
			$iPID=$aPID[$key];
			$sname=$aname[$key];
			$aRet['pid']=$iPID;
			$aRet['name']=$sname;	
			$aRet['exec_time']=$iruntime;	
		}
	}
	return $aRet;
}
//取現在獎號中心的背景執行狀態
function api_get_BG_slow_status(){
	global $web_cfg;
	include_once($web_cfg['path_conf'].'ser_config.php');
	$cmd='ps -ef | grep [file_trigger] | awk \'{print $2 "," $7 "," $9}\'';
	$cmd=str_replace('[file_trigger]',path_Trigger.file_trigger_slow,$cmd);
	//echo $cmd;
	$aRet=array();
	exec($cmd, $output, $return_var);
	if(count($output)<1){return;}
	foreach($output AS $key => $msg) {
		/*將$output 值轉成陣列*/
		$tmp=explode(',',$msg);
		/*把執行時間存進 $aruntime*/
		$aname[$key]=$tmp[2];
		$aruntime[$key]=$tmp[1];
		$aPID[$key]=$tmp[0];
	}
	foreach($output AS $key => $msg) {
		$iruntime=$aruntime[$key];
		/*如果 crontrigger 的執行時間 跟最大值行時間一樣 就是crontrigger 本體*/
		if($iruntime=='00:00:00'){continue;}
		if($iruntime==max($aruntime)){
			//得到本體的 pid
			$iPID=$aPID[$key];
			$sname=$aname[$key];
			$aRet['pid']=$iPID;
			$aRet['name']=$sname;	
			$aRet['exec_time']=$iruntime;	
		}
	}
	return $aRet;
}
//取所有遊戲是否有漏開
/*
 回傳
 {
	 遊戲{
		 漏開狀況:
		  0=沒有漏開
			1=漏開
			漏開幾期:次數
		 }
 }
*/
function api_chk_drop_draws_result(){
	include_once($web_cfg['path_lib'].'func.ser_result.php');
	include_once($web_cfg['path_lib'].'func.draws.php');
	$aRet=array();
	$aGame=api_get_all_game();
	foreach($aGame as $k => $sGame){
		$sToday=date('Y-m-d');
		$bRest=dws_chk_game_rest($k,$sToday);
		if($bRest===true){$aRet[$sGame]['rest']='OK';continue;}
		$aNow_draws=dws_get_now_draws_info($sGame);
		$sRpt_date=$aNow_draws['rpt_date'];
		$sDate_sn=$aNow_draws['date_sn'];
		$sDate_sn=($sDate_sn<=2)?$sDate_sn:$sDate_sn-2;;
		if(!isset($sRpt_date)){continue;}
		if(!isset($sDate_sn)){continue;}
		//1.抓出今天到現在為止的所有期數序號
		$draws=ser_get_now_draws($sGame,$sDate_sn,$sRpt_date);
		//2.抓出今天到現在為止有開出獎號的期數序號
		$result=ser_get_now_result($sGame,$sDate_sn,$sRpt_date);
		foreach($draws as $key => $value){
			//漏開期數陣列初始值
			$aRet[$sGame][$value]='ERROR';
			if(in_array($value,$result)){
				$aRet[$sGame][$value]='OK';
			}
			//前三期可能會誤判所以直接跳過
			if($key<3){
				$aRet[$sGame][$value]='OK';
			}
		}
	}
	return $aRet;
}
//先查詢 要刪除 開獎結果的站台名稱
/*
	$form_data:{
		 date=彩票日期
		,game=彩票類型
		,draws=彩票前台期數
	}
*/
function api_get_del_key_result($form_data){
	global $db_s;
	$sGame=$form_data['game'];
	$sRpt=$form_data['date'];
	$draws_num=$form_data['draws'];
	$sRet='';
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='IFNULL(site,0) AS sSite';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt]"';
	$aSQL[]='AND draws_num="[draws_num]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]','draws_'.$sGame.'_result',$sSQL);
	$sSQL=str_replace('[rpt]',$sRpt,$sSQL);
	$sSQL=str_replace('[draws_num]',$draws_num,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	if($db_s->affected_rows==0){return $sRet;}
	$sRet=$r['sSite'];
	return $sRet;
}
//刪除開獎結果
/*
	$form_data:{
		 date=彩票日期
		,game=彩票類型
		,draws=彩票前台期數
	}
*/
function api_del_key_result($form_data){
	global $db;
	$sGame=$form_data['game'];
	$sRpt=$form_data['date'];
	$draws_num=$form_data['draws'];
	$sRet='DELETE ERROR!';
	$sSite=api_get_del_key_result($form_data);
	if($sSite==''){return $sRet;}
	inst_error_cnt($sGame,$sSite);
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt]"';
	$aSQL[]='AND draws_num="[draws_num]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]','draws_'.$sGame.'_result',$sSQL);
	$sSQL=str_replace('[rpt]',$sRpt,$sSQL);
	$sSQL=str_replace('[draws_num]',$draws_num,$sSQL);
	$q=$db->sql_query($sSQL);
	$sRet='DELETE OK!';
	return $sRet;
}
//檢查每日期數是否正常生成
/*
	*取出所有彩票類型
	*檢查休息日
	*檢查今天有沒有期數
	回傳
	{
		遊戲
		{
			OK=正常 ,ERROR=異常
		}
	}
	---
*/
function api_chk_draws_daily(){
	include_once($web_cfg['path_lib'].'func.draws.php');
	global $_aEdit_Gtype;
	unset($_aEdit_Gtype[10]);//刪除共用
	$aRet=array();
	//取出所有彩票類型
	$_aEdit_Gtype[13]='pk';
	$aRet=array();
	$sDay_now=date('Y-m-d');
  $sDay_lst=date('Y-m-d',strtotime($sDay_now)-86400);
	$sTime='06:00:00';
  $sStmp_recover=strtotime($sDay_now.' '.$sTime);
	$now=time();
	if($now>$sStmp_recover){
		$rpt_date=$sDay_now;	
	}else{
		$rpt_date=$sDay_lst;	
	}
	foreach($_aEdit_Gtype as $sGameNum =>$sGame){
		//期數狀態的初始值
		$aRet[$sGame]='ERROR';
		//檢查休息日
		$bRest=dws_chk_game_rest($sGameNum,$rpt_date);
		//如果是休息日跳過
		if($bRest===true){$aRet[$sGame]='OK';continue;}
		//統計期數 如果期數>0 就是有生成
		$cnt_draws=dws_get_cnt_draws($sGame,$rpt_date);
		if($cnt_draws>0){
			$aRet[$sGame]='OK';
		}
	}
	return $aRet;
}
//補開所有遊戲的獎號
function api_remedy_drop_result(){
	include_once($web_cfg['path_lib'].'func.ser_result.php');
	include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
	include_once($web_cfg['path_lib'].'func.ser_result_site_table.php');
	include_once($web_cfg['path_lib'].'func.draws.php');
	$aRet=array();
	$aGame=api_get_all_game();
	foreach($aGame as $k => $sGame){
		$aNow_draws=dws_get_now_draws_info($sGame);
		$sRpt_date=$aNow_draws['rpt_date'];
		if(!isset($sRpt_date)){continue;}
		$drop_result=ser_chg_drop_result($sGame,$sRpt_date);
		if($drop_result==1){
			$aRet[]=$sGame;
		}
	}
	return $aRet;
}
//檢查抓開獎結果的來源 是否正常
/*
	傳入
		$sGame=遊戲
*/
function api_chk_curl_status($sGame){
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$sGame];
	$user_agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36"; //模擬成瀏覽器
	$aRet=array();
	$aRet['status']='OK';
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.1680api.com/Open/CurrentOpenOne?code=$id";
	curl_setopt($ch, CURLOPT_URL ,$http);
	curl_setopt($ch, CURLOPT_HEADER ,false);
	curl_setopt($ch, CURLOPT_USERAGENT ,$user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER ,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,true);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,3000);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);	
	if(count($obj)<1){
		$aRet['URL']=$http;
		$aRet['game']=$sGame;
		$aRet['status']='NG';
		$aRet['draws']='';
		$aRet['code']='';
		$aRet['time']='';
		return $aRet;
	}
	$aRet['URL']=$http;
	$aRet['game']=$sGame;
	$aRet['draws']=$obj['c_t'];
	$aRet['code']=$obj['c_r'];
	$aRet['time']=$obj['c_d'];
	return $aRet;
}

//取得DB狀態
function api_get_db_status(){
	global $aDB_arc;
	$aRet=array();
	$aDB_Status=array();
	//取得資料庫檢查位差秒差帳密
	$chk_user=$aDB_arc['db_login']['MS_chk']['user'];
	$chk_pass=$aDB_arc['db_login']['MS_chk']['pass'];
	//取得資料庫通用帳密
	$comm_user=$aDB_arc['db_login']['comm']['user'];
	$comm_pass=$aDB_arc['db_login']['comm']['pass'];
	$sql_NOW		= "SELECT NOW() AS db_time;";
	$sql_open_files= "SHOW STATUS LIKE  'Open_files';";	
	$status= "SHOW BINARY LOGS";		
	$host=$aDB_arc['list'][0];
	$db_do=api_try_mysql_connect($host, $chk_user, $chk_pass);	
	//取得db時間
	if($db_do==0){return $aRet;}
	$q=$db_do->sql_query($sql_NOW);
	$r=$db_do->nxt_row('ASSOC');
	$db_time=$r['db_time'];
	$aDate=explode(' ',$db_time);
	$aRet[$host]['db_date']=$aDate[0];
	$aRet[$host]['db_time']=$aDate[1];
	$no_signal=($db_time=='')?true:false;
	//取得開檔數
	$q=$db_do->sql_query($sql_open_files);
	$r=$db_do->nxt_row('ASSOC');
	$aRet[$host]['open']=$r['Value'];
	return $aRet;
}

//嘗試連接mysql
/*
	$db_host=機器, $db_user=帳號, $db_pass=密碼
	成功回傳1,失敗回傳0
*/
function api_try_mysql_connect($db_host, $db_user, $db_pass){
	global $web_cfg;
	//print_r($web_cfg);
	$db=1;
	$db  = new db_PDO();
	$db->change_db($db_host,$db_user,$db_pass,'information_schema');
	$db->path_root=$web_cfg['path'];
	if(!$db){return 0;}
	return $db;	
}
//檢查抓開獎結果的來源 是否正常 所有遊戲
function api_chk_result_source(){
	include_once($web_cfg['path_lib'].'func.draws.php');
	$aRet=array();
	$aGame=api_get_all_game();
	foreach($aGame as $k2 => $sGame){
		$aSite=api_get_error_cnt_rank($sGame);
		//print_r($aSite);
		foreach($aSite as $k1 => $sSite){
			$lottery_site_list=api_get_lottery_site_list($sGame,$sSite);
			$aRet[$sSite][$sGame]=$lottery_site_list;
		}
	}
	return $aRet;
}
//六合彩開獎結果 
/*
	傳入:
	$aPara{
		gsme:lh
		data:{
			site:"站台名稱"
			,rpt_year:"開獎年分"
			,rpt_year_sn:"開獎期次"
			,num:"第一球,第二球,第三球,第四球,第五球,第六球,特別號"
			,lottery_Time:"輸入時間"
		}
	}
	回傳:
	{
		Status:狀態 
			OK 				:正確 
			ERROR 		:錯誤
			RE_RESIlT:重新開獎
		Ecode:狀態碼 
			000 :OK
			001 :遊戲錯誤
			002 :號碼格式錯誤
			003 :重新開獎
	}
	*檢查遊戲是不是六合
	*檢查 號碼重複 跟號碼數量
	*檢查 其他資料格式
	*檢查 是不是重新開獎 重開獎要刪除 舊的 
	*如果都沒有問題 就會寫結果表
*/
function api_put_lh_site_result($aPara){
	include_once($web_cfg['path_lib'].'func.ser_result.php');
	$aRet=array(
		'Status'=>'ERROR'
		,'Ecode'=>'001'
	);
	$sGame=$aPara['game'];
	$sSite=$aPara['data']['site'];
	$sRpt_year=$aPara['data']['rpt_year'];
	$sRpt_year_sn=$aPara['data']['rpt_year_sn'];
	$sRpt_date=$aPara['data']['rpt_date'];
	$aNum=explode(',',$aPara['data']['num']);
	$sNum=$aPara['data']['num'];
	$sLottery_Time=$aPara['data']['lottery_Time'];
	//計算 各個號碼出現次數 
	//* 會回傳一個陣列 key 是號碼 值是 次數 如果每有重複 理論上應該要有七個元素
	$aNum_count_values=array_count_values($aNum);
	if($sGame!='lh'){
		return $aRet;
	}
	//* 當同樣號碼出現兩次 出現號碼出現次數陣列  的項目會減少 如果不是7個 或是 號碼數 不是7個 就是錯誤 	
	if(count($aNum)!=7 || count($aNum_count_values)!=7){
		$aRet['Ecode']='002';
		return $aRet;
	}
	$aRet=array(
		'Status'=>'OK'
		,'Ecode'=>'000'
	);
	$aLt_rst=array();
	$ball_col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_sp');
	$aLt_rst[0]['site']=$sSite;
	$aLt_rst[0]['rpt_year']=$sRpt_year;
	$aLt_rst[0]['rpt_year_sn']=$sRpt_year_sn;
	$aLt_rst[0]['rpt_date']=$sRpt_date;
	foreach($ball_col as $index => $ball){
		$aLt_rst[0][$ball]=$aNum[$index];
	}
	$aLt_rst[0]['lottery_Time']=$sLottery_Time;
	$aExist_result_lh=rst_get_exist_result_lh($sRpt_year,$sRpt_year_sn,$sRpt_date);
	if(count($aExist_result_lh)>1){
		$sExist_result_lh=implode(',',$aExist_result_lh);
		if($sExist_result_lh!=$sNum){
			rst_del_exist_result_lh($sRpt_year,$sRpt_year_sn,$sRpt_date);
			$aRet['Status']='RE_RESIlT';
			$aRet['Ecode']='003';
		}else{
			return $aRet;
		}
	}
	$sInst_result_status=inst_lottery_result_v2($sGame,$aLt_rst);
	if($sInst_result_status!=2){
		$aRet['Status']='ERROR';
		$aRet['Ecode']='002';
	}
	return $aRet;
}
//六合彩開獎結果  批量
/*
	傳入:
	$aPara{
		gsme:lh
		list:[
				{
				site:"站台名稱"
				,rpt_year:"開獎年分"
				,rpt_year_sn:"開獎期次"
				,num:"第一球,第二球,第三球,第四球,第五球,第六球,特別號"
				,lottery_Time:"輸入時間"
				}
		]
	}
	回傳:
	[
		{
			Status:狀態 
				OK 				:正確 
				ERROR 		:錯誤
			Ecode:狀態碼 
				000 :OK
				001 :遊戲錯誤
				002 :號碼格式錯誤
				003 :獎號已經存在
				004 :新增失敗
		}
	]
	*檢查遊戲是不是六合
	*檢查 號碼重複 跟號碼數量
	*檢查 其他資料格式
	*因為是批量 新增 所以已經開過的期數就跳過
	*如果都沒有問題 就會寫結果表
*/
function api_put_lh_site_result_batch($aPara){
	include_once($web_cfg['path_lib'].'func.ser_result.php');
	$aRet=array();
	$aRet[0]=array(
		'Status'=>'ERROR'
		,'Ecode'=>'001'
	);
	$aLt_rst=array();
	$sGame=$aPara['game'];
	$aList=$aPara['list'];
	if($sGame!='lh'){
		return $aRet;
	}
	$all_result=rst_get_all_result_lh();
	$all_draws=array();
	foreach($all_result as $k1 => $row1){
		$all_draws[]=$row1['rpt_year'].'_'.$row1['rpt_year_sn']; 
	}
	foreach($aList as $k => $row){
		$sSite=$row['site'];
		$sRpt_year=$row['rpt_year'];
		$sRpt_year_sn=$row['rpt_year_sn'];
		$sRpt_date=$row['rpt_date'];
		$aNum=explode(',',$row['num']);
		$sLottery_Time=$row['lottery_Time'];
		$sDraws_num=$sRpt_year.'_'.$sRpt_year_sn;
		//計算 各個號碼出現次數 
		//* 會回傳一個陣列 key 是號碼 值是 次數 如果每有重複 理論上應該要有七個元素
		$aNum_count_values=array_count_values($aNum);
		//* 當同樣號碼出現兩次 出現號碼出現次數陣列  的項目會減少 如果不是7個 或是 號碼數 不是7個 就是錯誤 	
		if(count($aNum)!=7 || count($aNum_count_values)!=7){
			$aRet[$k]['Status']='ERROR';
			$aRet[$k]['Ecode']='002';
			continue;
		}
		if(in_array($sDraws_num,$all_draws)){
			$aRet[$k]['Status']='ERROR';
			$aRet[$k]['Ecode']='003';
			continue;
		}
		$ball_col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_sp');
		$aLt_rst[$k]['site']=$sSite;
		$aLt_rst[$k]['rpt_year']=$sRpt_year;
		$aLt_rst[$k]['rpt_year_sn']=$sRpt_year_sn;
		$aLt_rst[$k]['rpt_date']=$sRpt_date;
		foreach($ball_col as $index => $ball){
			$aLt_rst[$k][$ball]=$aNum[$index];
		}
		$aLt_rst[$k]['lottery_Time']=$sLottery_Time;
		$aRet[$k]=array(
			'Status'=>'OK'
			,'Ecode'=>'000'
		);
	}
	$sInst_result_status=inst_lottery_result_v2($sGame,$aLt_rst);
	if($sInst_result_status!=2){
		$aInst_result_status['Status']='INST_FAIL';
		$aInst_result_status['Ecode']='004';
		array_push($aRet,$aInst_result_status);
	}
	return $aRet;
}
//取得 六合所有開獎結果
/*
	傳入:
		$aPara{
			gsme:lh
		}
	回傳:
	{
		Status:OK & ERROR
			Ecode:狀態碼 
			List:[
					 {開獎結果1}
					,{開獎結果2}
			]
	}
*/
function api_get_lh_result($aPara){
	include_once($web_cfg['path_lib'].'func.ser_result.php');
	$all_result=rst_get_all_result_lh();
	$aRet=array(
		'Status'=>'ERROR'
		,'Ecode'=>'001'
		,'List'=>array()
	);
	$aLt_rst=array();
	$sGame=$aPara['game'];
	if($sGame!='lh'){
		return $aRet;
	}
	$aRet=array(
		'Status'=>'OK'
		,'Ecode'=>'000'
		,'List'=>$all_result
	);
	/*
	echo '<xmp>';
	print_r($aRet);
	echo '</xmp>';
	*/
	return $aRet;
}
//執行服務重啟
/*

*/
function api_restart_service(){
	include_once('../config/connect.php');
	include_once('../config/ser_config.php');
	include_once($web_cfg['path_lib'].'class.ssh.php');
	$aRet=array();
	$aRet['Status']='ERROR';
	$svice_path='kill_crontrigger.php';
	$path=dirname(dirname(__FILE__));
	$ssh=new php_ssh($ssh_set['host'],$ssh_set['user'],$ssh_set['pass']);
	$cmd='php70 '.$path.'/config/'.$svice_path;
	$ssh->exec($cmd);
	$result=$ssh->get_result();
	if($result=='OK'){
		$aRet['Status']='OK';
		//$aRet['cmd']=$cmd;
	}
	return $aRet;
}
//執行服務重啟 檢查各站獎號用
/*

*/
function api_restart_service_slow(){
	include_once('../config/connect.php');
	include_once('../config/ser_config.php');
	include_once($web_cfg['path_lib'].'class.ssh.php');
	$aRet=array();
	$aRet['Status']='ERROR';
	$svice_path='kill_crontrigger_slow.php';
	$path=dirname(dirname(__FILE__));
	$ssh=new php_ssh($ssh_set['host'],$ssh_set['user'],$ssh_set['pass']);
	$cmd='php70 '.$path.'/config/'.$svice_path;
	$ssh->exec($cmd);
	$result=$ssh->get_result();
	if($result=='OK'){
		$aRet['Status']='OK';
	}
	return $aRet;
}
?>