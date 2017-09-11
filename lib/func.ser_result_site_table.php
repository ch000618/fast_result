<?php
//將所有開獎 資源寫進站台結果表資料表
/*
	傳入
		$sGame=遊戲
	寫進去的 格式
	Array(
		[draws_num] => 期數名稱
		[0] => 1 //各球結果 會遊戲不同 而 有不同球數
		[1] => 2
		[2] => 3
		[3] => 4
		[4] => 5
		[開獎時間]=>
		[total_sum] => 15 //總和
		[site]=>站台 
	)
	*取得開獎結果 整理成 共用得格式
	*存入 站台結果表
*/
function ser_ins_lottery_num_list_v2($sGame){
	global $db;
	$lottery_num_list168new['168new']=mke_lottery_num_list_168new($sGame);
	
	$lottery_num_list1399p['1399p']=mke_lottery_num_list_1399p_v2($sGame);
	
	$lottery_num_listlianju['lianju']=mke_lottery_num_list_lianju($sGame);
	
	$lottery_num_list91333['91333']=mke_lottery_num_list_91333($sGame);
	
	$lottery_num_list98007['98007']=mke_lottery_num_list_98007($sGame);
	
	$lottery_num_listun['un']=mke_lottery_num_list_un($sGame);
	
	$db->beginTransaction();
	
	if(!empty($lottery_num_list168new['168new'])){
		inst_lottery_site_list($sGame,$lottery_num_list168new);
	}
	
	if(!empty($lottery_num_list1399p['1399p'])){
		inst_lottery_site_list($sGame,$lottery_num_list1399p);
	}
		
	if(!empty($lottery_num_listlianju['lianju'])){
		inst_lottery_site_list($sGame,$lottery_num_listlianju);
	}
	
	if(!empty($lottery_num_list91333['91333'])){
		inst_lottery_site_list($sGame,$lottery_num_list91333);
	}
	
	if(!empty($lottery_num_list98007['98007'])){
		inst_lottery_site_list($sGame,$lottery_num_list98007);
	}
	
	if(!empty($lottery_num_listun['un'])){
		inst_lottery_site_list($sGame,$lottery_num_listun);
	}
	
	$db->commit();
	
}
//補開 漏開獎號 在站台結果表內 並檢查已開的獎號是否有更動 有就更新
/*
	撈出各站 開獎列表 有寫列表的才執行
	去掃描每一期有沒有值 沒有值寫一筆 有的話就更新
*/
function ser_chk_update_site_result_table($sGame){
	global $db;
	$hislist_list_lianju=mke_hislist_num_list_lianju($sGame);
	$hislist_list_168new=mke_hislist_num_list_168new($sGame);
	$hislist_list_91333=mke_hislist_num_list_91333($sGame);
	$hislist_list_98007=mke_hislist_num_list_98007($sGame);
	$hislist_list_un=mke_hislist_num_list_un($sGame);
	//$hislist_list_ju888=mke_hislist_num_list_ju888($sGame);
	$db->beginTransaction();//交易機制開始
	if(count($hislist_list_lianju)>1){
		foreach($hislist_list_lianju as $key => $value){
			inst_hislist_site_result_table($sGame,$value);
		}
	}
	if(count($hislist_list_168new)>1){
		foreach($hislist_list_168new as $key => $value){
			inst_hislist_site_result_table($sGame,$value);
		}
	}
	
	if(count($hislist_list_91333)>1){
		foreach($hislist_list_91333 as $key => $value){
			inst_hislist_site_result_table($sGame,$value);
		}
	}
	
	if(count($hislist_list_98007)>1){
		foreach($hislist_list_98007 as $key => $value){
			inst_hislist_site_result_table($sGame,$value);
		}
	}
	
	if(count($hislist_list_un)>1){
		foreach($hislist_list_un as $key => $value){
			inst_hislist_site_result_table($sGame,$value);
		}
	}
	$db->commit();
}
//取某遊戲某天的開獎結果
/*
	傳入:
		$sGame=遊戲
	回傳:
		已經排好次序的 站台
	//每個遊戲是分開計
*/
function ser_get_error_cnt_rank($sGame){
  global $db_s;
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='site';
  $aSQL[]=',SUM(refer_cnt-error_cnt) AS cnt';
  $aSQL[]='FROM site_[game]_error_cnt';
  $aSQL[]='WHERE 1';
  $aSQL[]='GROUP by site';
  $aSQL[]='ORDER BY cnt DESC';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $db_s->sql_query($sSQL);
	//echo $sSQL;
  while($r=$db_s->nxt_row('ASSOC')){
    $aRet[]=$r['site'];
  }
  return $aRet;  
}
//檢查停用站台
/*
	傳入:
		$sGame=遊戲
	回傳:
		回傳:這個遊戲停用站台
*/
function ser_get_close_site($sGame){
  global $db_s;
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='Site';
  $aSQL[]='FROM site_result_enable';
  $aSQL[]='WHERE 1';
	$aSQL[]='AND Game="[game]"';
  $aSQL[]='AND Enable="0"';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $db_s->sql_query($sSQL);
	//echo $sSQL;
  while($r=$db_s->nxt_row('ASSOC')){
    $aRet[]=$r['Site'];
  }
  return $aRet;  
}
//選擇開獎結果
/*	
*取得當前 開獎時間的期數
*取出這個遊戲 所有站台的 依照優先權排出來的陣列 優先權算法 為 參考-錯誤 越高分的月優先 
	新增結果是 會在站台優先權寫記一次 參考次數
	當錯誤時 刪除獎號 會增加一筆 錯誤次數
*依照站台的優先權 去跑迴圈 一個一個撈 有結果的塞進陣列
*取出陣列第一個 因為已經是最優先的才會在第一個位置	
*/
function ser_lottery_num_list_switch_v3($sGame){
	$debug=false;
	$aRet=array();
	//*取得當前 開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	if($debug){
		echo $draws;
	}
	//*取出這期 所有站台的 做開獎號碼 比較
	//被強制關閉的站台 
	$close_site=ser_get_close_site($sGame);
	if($debug){
		print_r($close_site);
	}
	$aSite=ser_get_error_cnt_rank($sGame);
	$ary_new=array();
	$ary_new2=array();
	//*依照站台的優先權 去跑迴圈 一個一個撈 有結果的塞進陣列
	foreach($aSite as $key =>$name){
		if(in_array($name,$close_site)){
			continue;
		}
		$ary_new=ser_get_lottery_site_list_exist($sGame,$name,$sDraws);
		if(count($ary_new)>1){
			$ary_new2[]=$ary_new;
		}
	}
	if(count($ary_new2)<1){
		return $aRet;
	}
	//*取出陣列第一個 因為已經是最優先的才會
	$aRet=$ary_new2[0];
	if($debug){
		print_r($aRet);
	}
	return $aRet;
}
//新增一筆 採用站台紀錄
/*
	傳入
		遊戲
		站台
	當站台被選用時 
	新增一筆紀錄 採用次數是一的紀錄 用於計算優先權
*/
function inst_refer_cnt($sGame,$sSite){
	global $db;
	$aSQL=array();
	$col=array('site','error_cnt','refer_cnt');
	$value=array($sSite,'0','1');
	$aSQL[]='INSERT INTO site_[game]_error_cnt ([cols])';
	$aSQL[]='VALUES';
	$aSQL[]='("[value]")';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[value]',implode('","',$value),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$q=$db->sql_query($sSQL);
}
//將開獎結果存入資料庫 加寫站台欄位
/*
傳入
	遊戲
	完整開獎結果陣列
	[]
		[日期]
		[序號]
		[球號]
		[總和]
		[站台]
*/
function inst_lottery_result_v3($sGame,$lt_rst){
	global $db;
	$ret=0;
	switch($sGame){
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
	$ret=1;
	foreach($lt_rst as $key => $value){
		if(count($value)<count($col)){continue;}
		$sSite=$value['site'];
		inst_refer_cnt($sGame,$sSite);
		$v=$value;
		$draws=implode("','",$v);
		$VALUES[]="('$draws')";
	}
	if(empty($VALUES)){return $ret;}
	$aSQL[]='[VALUES]';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[VALUES]',implode(",",$VALUES),$sSQL);
	//echo $sql;
	$q=$db->sql_query($sSQL);
	$ret=2;
	return $ret;
}
//將開獎結果存入資料庫 加寫站台欄位
/*
傳入
	遊戲
	完整開獎結果陣列
	[]
		[日期]
		[序號]
		[球號]
		[總和]
		[站台]
*/
function inst_lottery_result_update($sGame,$lt_rst){
	global $db;
	$ret=0;
	switch($sGame){
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
	$ret=1;
	foreach($lt_rst as $key => $value){
		if(count($value)<count($col)){continue;}
		$sSite=$value['site'];
		$sDraws_num=$value['draws_num'];
		$sRpt_date=$value['rpt_date'];
		ser_del_error_result($sGame,$sRpt_date,$sDraws_num);
		inst_refer_cnt($sGame,$sSite);
		$v=$value;
		$draws=implode("','",$v);
		$VALUES[]="('$draws')";
	}
	if(empty($VALUES)){return $ret;}
	$aSQL[]='[VALUES]';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[VALUES]',implode(" ",$VALUES),$sSQL);
	//echo $sSQL;
	$q=$db->sql_query($sSQL);
	$ret=2;
	return $ret;
}

//刪除開獎結果
/*
	$form_data:{
		 date=彩票日期
		,game=彩票類型
		,draws=彩票前台期數
	}
*/
function ser_del_error_result($sGame,$sRpt_date,$sDraws_num){
	global $db;
	$sSite=ser_get_del_error_result($sGame,$sRpt_date,$sDraws_num);
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
	$sSQL=str_replace('[rpt]',$sRpt_date,$sSQL);
	$sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$q=$db->sql_query($sSQL);
}

//先查詢 要刪除 開獎結果的站台名稱
/*
	$form_data:{
		 date=彩票日期
		,game=彩票類型
		,draws=彩票前台期數
	}
*/

function ser_get_del_error_result($sGame,$sRpt_date,$sDraws_num){
	global $db_s;
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
	$sSQL=str_replace('[rpt]',$sRpt_date,$sSQL);
	$sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	if($db_s->numRows() <1){return $sRet;}
	$sRet=$r['sSite'];
	return $sRet;
}

//刪除某個站台 某一期的開獎結果 
/*
	$form_data:{
		 sDraws_num=期數名稱
		,sGame=彩票類型
		,sSite=站台
	}
*/
function ser_del_error_result_site_table($sGame,$sDraws_num,$sSite){
	global $db;
	$sRet='';
	$sSite=ser_get_del_error_result_site_table($sGame,$sDraws_num,$sSite);
	if($sSite==''){return $sRet;}
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num="[draws_num]"';
	$aSQL[]='AND site="[site]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]','site_'.$sGame.'_result',$sSQL);
	$sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$sSQL=str_replace('[site]',$sSite,$sSQL);
	//echo $sSQL;
	$q=$db->sql_query($sSQL);
}

//先查詢 要刪除 站台結果的站台名稱
/*
	$form_data:{
		 date=彩票日期
		,game=彩票類型
		,draws=彩票前台期數
	}
*/

function ser_get_del_error_result_site_table($sGame,$sDraws_num,$sSite){
	global $db_s;
	$sRet='';
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='IFNULL(site,0) AS sSite';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num="[draws_num]"';
	$aSQL[]='AND site="[site]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]','site_'.$sGame.'_result',$sSQL);
	$sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$sSQL=str_replace('[site]',$sSite,$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	if($db_s->numRows() <1){return $sRet;}
	$sRet=$r['sSite'];
	return $sRet;
}
//將開獎結果存入資料庫
/*
傳入
	遊戲
	日期
*呼叫開獎結果
*把開獎結果拆解組裝成 sql語法 
*新增到資料庫
*/
function inst_lottery_site_list($sGame,$lt_rst){
	global $db;
	$ret="";
	switch($sGame){
		case 'klc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','lottery_Time','total_sum','site'
			);
		break;
		case 'ssc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5','lottery_Time','total_sum','site'
			);
		break;
		case 'pk':
			$col=array(
				'draws_num'
				,'rank_1','rank_2','rank_3','rank_4','rank_5','rank_6'
				,'rank_7','rank_8','rank_9','rank_10','lottery_Time','total_sum','site'
			);
		break;
		case 'nc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','lottery_Time','total_sum','site'
			);
		break;
		case 'kb':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','ball_9','ball_10','ball_11'
				,'ball_12','ball_13','ball_14','ball_15','ball_16','ball_17'
				,'ball_18','ball_19','ball_20','ball_fp','lottery_Time','total_sum','site'
			);
		break;
	}
	$aSQL=array();
	$aSQL[]='INSERT INTO site_[game]_result ([cols])';
	$aSQL[]='VALUES';
	foreach($lt_rst as $key => $value){
		$value['site']=$key;
		$draws_num=$value['draws_num'];
		$chk_repeat=ser_get_lottery_site_list_repeat($sGame,$key,$draws_num);
		if($chk_repeat!='' || $chk_repeat==$draws_num){continue;}
		if(count($value)<count($col)){continue;}
		$v=$value;
		$draws=implode("','",$v);
		$VALUES[]="('$draws')";
	}
	if(empty($VALUES)){return $ret;}
	$aSQL[]='[VALUES]';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[VALUES]',implode(",",$VALUES),$sSQL);
	//echo $sSQL;
	$q=$db->sql_query($sSQL);
}
//取某期 某站台這個期數 是否存在 
/*
	$sGame=遊戲代碼
	$sSite=站台名稱
	$sDraws_num=期數名稱
  回傳:
		$sRet:期數名稱
	*存在會回傳 期數名稱
	
*/
function ser_get_lottery_site_list_repeat($sGame,$sSite,$sDraws_num){
  global $db_s;
  $sRet='';
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]='FROM site_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND site="[site]"';
  $aSQL[]='AND draws_num="[draws_num]"';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[site]',$sSite,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $sRet;}
	$r=$db_s->nxt_row('ASSOC');
  $draws_num=$r['draws_num'];
  $sRet=$draws_num;
  return $sRet;
}
//取某期 某站台這個開獎結果 是否存在
/*
  $sGame=遊戲代碼
  回傳:
	$aRet:開獎號碼陣列
*/
function ser_get_lottery_site_list_exist($sGame,$sSite,$sDraws_num){
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
  $aSQL[]='AND draws_num="[draws_num]"';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[site]',$sSite,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	//echo $sSQL;
	$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $aRet;}
  $r=$db_s->nxt_row('ASSOC');
  $aRet=$r;
  return $aRet;
}
//新增歷史期數開獎結果 站台結果表
/*
傳入
	遊戲
	漏開期數 開獎結果陣列
	{
	[]
		[日期]
		[序號]
		[球號]
		[總和]
		[站台]
	,.....
	}
*把開獎結果拆解組裝成 sql語法 
*新增到資料庫
*/
function inst_hislist_site_result_table($game,$ary){
		global $db;
		switch($game){
		case 'klc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','lottery_Time','total_sum','site'
			);
		break;
		case 'ssc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5','lottery_Time','total_sum','site'
			);
		break;
		case 'pk':
			$col=array(
				'draws_num'
				,'rank_1','rank_2','rank_3','rank_4','rank_5','rank_6'
				,'rank_7','rank_8','rank_9','rank_10','lottery_Time','total_sum','site'
			);
		break;
		case 'nc':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','lottery_Time','total_sum','site'
			);
		break;
		case 'kb':
			$col=array(
				'draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','ball_9','ball_10','ball_11'
				,'ball_12','ball_13','ball_14','ball_15','ball_16','ball_17'
				,'ball_18','ball_19','ball_20'
				,'ball_fp','lottery_Time','total_sum','site'
			);
		break;
	}
	$aSQL=array();
	$ret=0;
	$VALUES=array();
	if(count($ary)<count($col)){return $ret;}
	$draws_num=$ary['draws_num'];
	$site=$ary['site'];
	ser_del_error_result_site_table($game,$draws_num,$site);
	$aSQL[]='INSERT INTO site_[game]_result ([cols])';
	$aSQL[]='VALUES';
	$draws=implode("','",$ary);
	$aSQL[]="('$draws')";
	/*
	$aSQL[]='ON DUPLICATE KEY UPDATE';
	$aSQL[]='draws_num="'.$draws_num.'"';
	$aSQL[]=',site ="'.$site.'"';
	*/
	$sSQL=implode(" ",$aSQL);
	$sSQL=str_replace('[cols]',implode(',',$col),$sSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$q=$db->sql_query($sSQL);
	$ret=1;
	return $ret;
}
//新增開獎結果 採取優先權方式取結果
/*
	原本是 抓到後整理格式後 直接進資料庫
	因為 有碰到開獎號碼是錯的
	會先到 各遊戲的站台評分表 選出分數最高的為優先 
	在依照優先順序 去站台結果表 讀結果 
	選用 最先開的號碼且優先權較高的站台 結果
	
	*整理格式 並且 塞入站台結果表
	*取出所有站台 號碼進行比對 用大數法則 決定要用哪組號碼
	*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	*檢查 有沒有開過獎
	*開獎完畢 把開獎失敗 改為成功
*/
function init_inst_lt_rst_rank($sGame){
	$ret=0;
	//*先到 各遊戲的站台評分表 選出分數最高的為優先
	$lt=ser_lottery_num_list_switch_v3($sGame);
	if(count($lt)<1){return $ret;}
	//*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	$lt_date=mke_lottery_date_list_v2($lt,$sGame);
	$draws_num=$lt_date[0]['draws_num'];
	//*檢查 有沒有開過獎
	$chk_repeat=rst_sel_repeat_result($sGame,$draws_num);
	if($chk_repeat!=''|| !empty($chk_repeat)){return $ret;}
	//*開獎完畢 把開獎失敗 改為成功
	$inst_st=inst_lottery_result_v3($sGame,$lt_date);
	$sys_time=date("Y-m-d H:i:s");
	UPDATE_sys_time($sGame,$draws_num,$sys_time);
	if($inst_st==2){
		$ret=1;
	}
	return $ret;	
}
?>