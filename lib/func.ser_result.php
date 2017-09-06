<?php
include_once($web_cfg['path_lib'].'func.draws_result.php');
include_once($web_cfg['path_lib'].'func.draws.php');
//開獎服務相關函釋
/*整理出各玩法 所需資料
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
function mke_lottery_num_list_v2($game,$ary){
		$ret=array();
		$num=explode(',',$ary['c_r']);
		$dw_n=array('draws_num'=>$ary['c_t']);
		$draws_num=$ary['c_t'];
		//將文字檔的開獎時間轉成 資料庫的格式
		$Time_lottery=$ary['c_d'];
		UPDATE_Time_lottery_v2($game,$draws_num,$Time_lottery);
		$ret=array_merge($dw_n,$num);
		//計算總和
		$ret['total_sum']=array_sum($num);
		//只有北京賽車是用前兩個的和
		if($game=='pk'){$ret['total_sum']=$num[0]+$num[1];}
		//kb因為有快樂飛盤的關係 總和長這樣
		if($game=='kb'){
			$nums_kb=array(
				$num[0],$num[1],$num[2],$num[3],$num[4],$num[5],
				$num[6],$num[7],$num[8],$num[9],$num[10],$num[11],
				$num[12],$num[13],$num[14],$num[15],$num[16],$num[17],
				$num[18],$num[19]
			);
			$ret['total_sum']=array_sum($nums_kb);
		}
		return $ret;
}
//用期數名稱取出 對應的日期跟當日序號
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
function mke_lottery_date_list_v2($ary,$game){
	if(count($ary)<1){exit;}
	$draws_num=$ary['draws_num'];
	$sLt_time=$ary['lottery_Time'];
	if($ary['draws_num']==''){exit;}
	$data=sel_rpt_date_v2($game,$draws_num);
	UPDATE_Time_lottery_v2($game,$draws_num,$sLt_time);
	unset($ary['lottery_Time']);
	$ret[]=array_merge($data,$ary);
	return $ret;
}
//用curl抓取即時開獎結果
function ser_get_result_168old($game){
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$game];
	$user_agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36";
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.1680api.com/Open/CurrentOpenOne?code=$id";
	curl_setopt($ch,CURLOPT_URL ,$http);
	curl_setopt($ch,CURLOPT_HEADER ,false);
	curl_setopt($ch,CURLOPT_USERAGENT ,$user_agent);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,true);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,3000);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
}
//用curl抓取即時開獎結果 第二個資料站
function ser_get_result_648($game){
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$game];
	$user_agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36";
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.64848.com/Open/CurrentOpenOne?code=$id";
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
	return $obj;
}
//用curl抓取即時開獎結果 第三個資料站
function ser_get_result_TT($game){
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$game];
	$user_agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36";
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://www.ttkaicai.com/Open/CurrentOpenOne?code=$id";
	//echo $http;
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
	return $obj;
}
//歷史開獎結果
/*
資料來源是從history/hislist/這裡取得
是用傳遞get的方式取資料
傳id 可能是玩法 跟 date 日期 到這個網址
可以得到一個存著當天期數所有號碼跟結果的陣列
*/
function ser_get_hislist_result($date,$game){
	$game_id=array();
	$game_id['klc']="1008";
	$game_id['ssc']="10011";
	$game_id['nc']="10010";
	$game_id['pk']="10016";
	$game_id['kb']="10014";
	$id=$game_id[$game];
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://kj.1680api.com/History/HisList?id=$id&date=$date";
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
//用開獎資料期數名稱找到開獎日期
function sel_rpt_date_v2($game,$draws_num){
	global $db_s;
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='rpt_date';
	$SQL[]=',date_sn';
	$SQL[]='FROM draws_'.$game;
	$SQL[]='WHERE';
	$SQL[]='draws_num ="[draws_num]"';
	$sql=implode(' ',$SQL);
	$sql=str_replace('[draws_num]',$draws_num,$sql);
	$q=$db_s->sql_query($sql);
	$r=$db_s->nxt_row('ASSOC');
	$ret=$r;
	return $ret;
}
//用期數名稱尋找已有獎號 的結果 然後刪除 
function del_repeat_result_v2($game,$date){
	global $db;
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM draws_'.$game.'_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[date]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[date]',$date,$sSQL);
	$q=$db->sql_query($sSQL);
}
//用期數名稱尋找已有獎號 的結果 然後刪除 
function del_repeat_result_v3($sGame,$aDraws){
	global $db;
	if(count($aDraws)<1 || empty($aDraws)){
		echo 'DELETE ERROR!';
		return false;
	}
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM draws_[game]_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num IN("[draws]")';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[draws]',implode('","',$aDraws),$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$q=$db->sql_query($sSQL);
	echo 'DELETE OK!';
	return true;
}
//用抓到期數名稱尋找已有的結果  期數名稱
function rst_sel_repeat_result($game,$draws_num){
	global $db_s;
	if($draws_num==''|| !isset($draws_num)){return $ret;}
	$ret='';
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]_result';
	$aSQL[]='WHERE';
	$aSQL[]='draws_num="[draws_num]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[draws_num]',$draws_num,$sSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$q=$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $ret;}
  $r=$db_s->nxt_row('ASSOC');
  $ret=$r['draws_num'];
	return $ret;
}
//取得 某期開獎號碼 六合
/*
	傳入
		$sRpt_year=年分
		sRpt_year_sn=期次
		$sRpt_date=日期
	回傳
		號碼:1,2,3,4,5,6,7
	*因為如果是重開結果 要先檢查一不一樣
*/
function rst_get_exist_result_lh($sRpt_year,$sRpt_year_sn,$sRpt_date){
	global $db_s;
	$aRet=array();
	$ball_col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_sp');
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='[ball_col]';
	$aSQL[]='FROM draws_lh_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_year="[rpt_year]"';
	$aSQL[]='AND rpt_year_sn="[rpt_year_sn]"';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_year]',$sRpt_year,$sSQL);
	$sSQL=str_replace('[rpt_year_sn]',$sRpt_year_sn,$sSQL);
	$sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$sSQL=str_replace('[ball_col]',implode(",\n",$ball_col),$sSQL);
	$q=$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $aRet;}
  $r=$db_s->nxt_row('ASSOC');
  $aRet=$r;
	return $aRet;
}
//取得 所有開獎結果資料 六合
/*
	回傳
	{
		年分
		,期次
		,日期
		,各求號碼.....
	}
*/
function rst_get_all_result_lh(){
	global $db_s;
	$aRet=array();
	$ball_col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_sp');
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='rpt_year';
	$aSQL[]=',rpt_year_sn';
	$aSQL[]=',rpt_date';
	$aSQL[]=',[ball_col]';
	$aSQL[]='FROM draws_lh_result';
	$aSQL[]='WHERE 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[ball_col]',implode(",\n",$ball_col),$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	if($db_s->numRows() < 1){ return $aRet;}
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r;
	}
	return $aRet;
}
//刪除 某期開獎號碼 六合彩 
/*
	傳入
		$sRpt_year=年分
		sRpt_year_sn=期次
		$sRpt_date=日期
*/
function rst_del_exist_result_lh($sRpt_year,$sRpt_year_sn,$sRpt_date){
	global $db;
	$aRet=array();
	$aSQL=array();
	$aSQL[]='DELETE';
	$aSQL[]='FROM draws_lh_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_year="[rpt_year]"';
	$aSQL[]='AND rpt_year_sn="[rpt_year_sn]"';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_year]',$sRpt_year,$sSQL);
	$sSQL=str_replace('[rpt_year_sn]',$sRpt_year_sn,$sSQL);
	$sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$sSQL=str_replace('[ball_col]',implode(",\n",$ball_col),$sSQL);
	$sSQL=str_replace('[game]',$game,$sSQL);
	$q=$db->sql_query($sSQL);
}

//用遊戲代號找出玩法代號
function rst_sel_ptype($Gtype){
	global $db_s;
	$ret=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='ptype';
	$aSQL[]='FROM agents_conf_DEF';
	$aSQL[]='WHERE';
	$aSQL[]='gtype =[Gtype]';
	$sSQL=implode(' ',$sSQL);
	$sSQL=str_replace('[Gtype]',$Gtype,$sSQL);
	$q=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r['ptype'];
	}
	return $ret;
}
//從獎號中心抓結果回來 塞進資料庫
/*
  $game=遊戲代號,$date=日期
  回傳:
    true:有新增開獎號碼
    false:沒有新增開獎號碼
  ---
	*從獎號中心上抓目前這期的開獎結果
	*關閉這一期
	*整理出各玩法 所需資料
	*依照玩法處理出結果
	*整理成資料庫的語法格式
		判斷格式有沒有錯誤
			錯誤跳過
		判斷是否重複
			重複跳過
	*新增開獎結果
	*20170104  增加狀態機 1.重複開獎 2.沒有開獎  3.開獎錯誤 4.開獎成功
*/
function inst_award_mo_result($game,$date){
	$ret=1;
	$award_draws_num=$_POST['draws_num'];
	// *檢查這一期有沒有結果
	$chk_repeat=rst_sel_repeat_result($game,$award_draws_num);
	if(!empty($chk_repeat)){
		echo "本期已有結果";
		return $ret;
	}
	$Time_lottery=date("Y-m-d H:i:s");
	// *把目前有結果最後一期的開獎時間 更新到資料庫的期數資料
	// *用目前有結果最後一期的期數名稱 抓出獎號中心的獎號
	$json=$_POST['result'];
	$ary['lottery_Time']=$Time_lottery;
	$ary=json_decode($json,true);
	if(empty($ary)){
		$ret=2;
		return $ret;
	}
	// *把獎號中心的獎號處理成網站需要的格式
	// 算出 號碼的總額 存入結果陣列 因為api抓出來的只有號碼
	$step1=mke_lottery_num_list_v3($game,$ary,$award_draws_num);
	// 抓出 當日序號 報表日期 存入結果陣列
	$step2=mke_lottery_date_list_v2($step1,$game);
	// *把結果陣列 整理成sql與法一次新增
	$lt=inst_lottery_result_v2($game,$step2);
	$ret=$lt;
  return $ret;
}
/*整理出各玩法 所需資料
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
function mke_lottery_num_list_v3($game,$ary,$draws_num){
		$ret=array();
		//將文字檔的開獎時間轉成 資料庫的格式
		$dw_n=array('draws_num'=>$draws_num);
		$ret=array_merge($dw_n,$ary);
		//計算總和
		$ret['total_sum']=array_sum($ary);
		//只有北京賽車是用前兩個的和
		if($game=='pk'){$ret['total_sum']=$ary['result_1']+$ary['result_2'];}
		return $ret;
}
//從獎號中心抓結果回來 交換站台
/*
  $game=遊戲代號,
	$site=站台
	$draws_num=期數

  ---
	*到站台結果表 撈出指定站台 指定期數 的開獎結果
	*整理出各玩法 所需資料
	*依照玩法處理出結果
	*抓出 當日序號 報表日期 存入結果陣列
		判斷格式有沒有錯誤
			錯誤跳過
		判斷是否重複
			重複跳過
	*新增開獎結果
	*20170104  增加狀態機 1.重複開獎 2.沒有開獎  3.開獎錯誤 4.開獎成功
*/
function inst_award_chg_result($sGame,$site,$draws_num){
	$ret='';
	// *到站台結果表 撈出指定站台 指定期數 的開獎結果
	$step1=ser_get_lottery_site_list_exist($sGame,$site,$draws_num);
	if(count($step1)<1){
		echo "本期該站台還沒開獎 \n";
		return $ret;
	}
	// 抓出 當日序號 報表日期 存入結果陣列
	$step2=mke_lottery_date_list_v2($step1,$sGame);
	echo "您使用的的開獎結果為 \n";
	print_r($step2);
	// *把結果陣列 整理成sql與法一次新增
	$lt=inst_lottery_result_update($sGame,$step2);
}
//新增開獎號碼
function init_inst_lt_rst($game,$date){
	$ret=false;
	$data=array();
	$data=ser_get_result_168old($game);
	$lt=mke_lottery_num_list_v2($game,$data);
	$lt_date=mke_lottery_date_list_v2($lt,$game);
	$draws_num=$lt_date[0]['draws_num'];
	$chk_repeat=rst_sel_repeat_result($game,$draws_num);
	if($chk_repeat!=''){return $ret;}
	$sys_time=date("Y-m-d H:i:s");
	UPDATE_sys_time($game,$draws_num,$sys_time);
	inst_lottery_result_v2($game,$lt_date);
	$ret=true;
	return $ret;
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
function inst_lottery_result_v2($game,$lt_rst){
	global $db;
	$ret=0;
	switch($game){
		case 'klc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','total_sum'
			);
		break;
		case 'ssc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5','total_sum'
			);
		break;
		case 'pk':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'rank_1','rank_2','rank_3','rank_4','rank_5','rank_6'
				,'rank_7','rank_8','rank_9','rank_10','total_12'
			);
		break;
		case 'nc':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','total_sum'
			);
		break;
		case 'kb':
			$col=array(
				'rpt_date','date_sn','draws_num'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_7','ball_8','ball_9','ball_10','ball_11'
				,'ball_12','ball_13','ball_14','ball_15','ball_16','ball_17'
				,'ball_18','ball_19','ball_20'
				,'ball_fp','total_sum',
			);
		break;
		case 'lh':
			$col=array(
				'site','rpt_year','rpt_year_sn','rpt_date'
				,'ball_1','ball_2','ball_3','ball_4','ball_5'
				,'ball_6','ball_sp','lottery_Time'
			);
		break;
	}
	$SQL[]='INSERT INTO draws_[game]_result ([cols])';
	$SQL[]='VALUES';
	$ret=1;
	//print_r($lt_rst);
	foreach($lt_rst as $key => $value){
		//print_r($value);
		//print_r($col);
		if(count($value)<count($col)){continue;}
		$v=$value;
		$draws=implode("','",$v);
		$VALUES[]="('$draws')";
	}
	if(empty($VALUES)){return $ret;}
	$SQL[]=implode(",",$VALUES);
	$SQL=str_replace('[cols]',implode(',',$col),$SQL);
	$SQL=str_replace('[game]',$game,$SQL);
	$sql=implode(' ',$SQL);
	//echo $sql;
	$q=$db->sql_query($sql);
	$ret=2;
	return $ret;
}
//取某期 某站台這個期數 開獎時間
/*
	$sGame=遊戲代碼
	$sDraws_num=期數名稱
  回傳:
		$sRet:期數名稱
	*取最晚的 開獎時間
*/
function ser_get_lottery_time($sGame,$sDraws_num){
  global $db_s;
  $sRet='';
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='max(lottery_Time) as lottery_Time';
  $aSQL[]='FROM site_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND draws_num="[draws_num]"';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[draws_num]',$sDraws_num,$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $sRet=$r['lottery_Time'];
  return $sRet;
}
//取某期以前的期數資料
/*
  $sGame=遊戲代號
  $sDraws_sn=期數名稱
	$sRpt_date=報表日期
  回傳:{
		$aRet=[所有符合條件的期數]
  }
*/
function ser_get_now_draws($sGame,$sDraws_sn,$sRpt_date){
  global $db_s;
  $aSQL=array();
  $aRet=array();
	$yesterday_last_draws=ser_get_yesterday_last_draws($sGame,$sRpt_date);
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND date_sn <= "[date_sn]"';
  $aSQL[]='AND rpt_date = "[rpt_date]"';
	$aSQL[]='ORDER BY date_sn ASC';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[date_sn]',$sDraws_sn,$sSQL);
  $sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r['draws_num'];
	}
	if($sGame=='kb'){
		if($yesterday_last_draws!=''){
			array_unshift($aRet,$yesterday_last_draws);
		}
		/*
		else{
			$aRet[]=$yesterday_last_draws;
		}
		*/
	}
  return $aRet;
}
//取昨天最後一期期數資料
/*
  $sGame=遊戲代號
	$sRpt_date=報表日期
  回傳:{
		$sRet=該日期 前一天的期數
  }
*/
function ser_get_yesterday_last_draws($sGame,$sRpt_date){
  global $db_s;
	$yesterday=date('Y-m-d',strtotime($sRpt_date."-1 day"));
	$sRet='';
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date = "[rpt_date]"';
	$aSQL[]='ORDER BY date_sn DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[rpt_date]',$yesterday,$sSQL);
	$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	if($db_s->numRows() < 1){ return $sRet;}
	$sRet=$r['draws_num'];
  return $sRet;
}
//取昨天最後一期 開獎結果期數
/*
  $sGame=遊戲代號
	$sRpt_date=報表日期
  回傳:{
		$sRet=該日期 前一天的期數
  }
*/
function ser_get_yesterday_last_result($sGame,$yesterday_last_draws){
  global $db_s;
	$sRet='';
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]_result';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND draws_num = "[draws_num]"';
	$aSQL[]='ORDER BY date_sn DESC';
	$aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[draws_num]',$yesterday_last_draws,$sSQL);
	$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	if($db_s->numRows() < 1){ return $sRet;}
	$sRet=$r['draws_num'];
  return $sRet;
}
//取某期以前的開獎結果資料
/*
  $sGame=遊戲代號
  $sDraws_sn=期數名稱
	$sRpt_date=報表日期
  回傳:{
		$sRet=筆數
  }
*/
function ser_get_now_result($sGame,$sDraws_sn,$sRpt_date){
  global $db_s;
	$yesterday_last_draws=ser_get_yesterday_last_draws($sGame,$sRpt_date);
	$aSQL=array();
	$aRet=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND date_sn <= "[date_sn]"';
  $aSQL[]='AND rpt_date = "[rpt_date]"';
	$aSQL[]='ORDER BY date_sn ASC';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[date_sn]',$sDraws_sn,$sSQL);
  $sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r['draws_num'];
	}
	if($sGame=='kb'){
		if($yesterday_last_draws!=''){
			$yesterday_last_result=ser_get_yesterday_last_result($sGame,$yesterday_last_draws);
			if($yesterday_last_result!=''){
				array_unshift($aRet,$yesterday_last_result);
			}
		}
	}
  return $aRet;
}
//取某天最後一期有結果的期數序號
/*
  $sGame=遊戲代碼
  回傳:$sRet:期數名稱
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期開獎結果
*/
function ser_get_last_result_seq($sGame,$sRpt_date){
  global $db_s;
  $sRet='';
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='date_sn';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_sn=$r['date_sn'];
  $sRet=$draws_sn;
  return $sRet;
}
//取某天最後一期的期數序號
/*
  $sGame=遊戲代碼
  回傳:$sRet:期數序號
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期開獎結果
*/
function ser_get_last_draws_seq($sGame,$sRpt_date){
  global $db_s;
  $sRet='';
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='date_sn';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_sn=$r['date_sn'];
  $sRet=$draws_sn;
  return $sRet;
}
//取今天最後一期有結果的期數名稱
/*
  $sGame=遊戲代碼
  回傳:$sRet:期數名稱
  *先查出現在時間屬於哪個開獎日期
  *拿開獎日期去查出當天最後一期開獎結果
*/
function ser_get_last_result_name($sGame){
  global $db_s;
  $sRet='';
  // *先查出現在時間屬於哪個開獎日期
  $aNow_draws=dws_get_now_draws_info($sGame);
  $rpt_date=$aNow_draws['rpt_date'];
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='draws_num';
  $aSQL[]='FROM draws_[game]_result';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date="[rpt_date]"';
  $aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);  
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  $draws_sn=$r['draws_num'];
  $sRet=$draws_sn;
  return $sRet;
}
//用抓到期數名稱尋找已有的結果  期數名稱
function ser_sel_drop_rst($sGame,$sDraws_nums){
	global $db_s;
	$aRet=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]_result';
	$aSQL[]='WHERE';
	$aSQL[]='draws_num IN("[sDraws_nums]")';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[sDraws_nums]',$sDraws_nums,$sSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$info=$sSQL;
	$db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet[]=$r['draws_num'];
	}
	return $aRet;
}
//更新開獎時間
function UPDATE_Time_lottery_v2($game,$draws_num,$Time_lottery){
	global $db;
	if($Time_lottery==""){return ;}
	$SQL[] ='UPDATE draws_[game] ';
	$SQL[]='SET lottery_time="[Time_lottery]"';
	$SQL[]='WHERE ';
	$SQL[]='draws_num ="[draws_num]"';//需要更改開獎時間的期數名稱
	$sql=implode(' ',$SQL);
	$sql = str_replace('[game]',$game,$sql);
	$sql = str_replace('[draws_num]',$draws_num,$sql);	
	$sql = str_replace('[Time_lottery]',$Time_lottery,$sql);	
	$q=$db->sql_query($sql);
	//echo $sql;
}
//更新抓到結果的系統時間
function UPDATE_sys_time($game,$draws_num,$sys_time){
	global $db;
	$SQL[] ='UPDATE draws_[game] ';
	$SQL[]='SET sys_time="[sys_time]"';
	$SQL[]='WHERE ';
	$SQL[]='draws_num ="[draws_num]"';//需要更改開獎時間的期數名稱
	$sql=implode(' ',$SQL);
	$sql = str_replace('[game]',$game,$sql);
	$sql = str_replace('[draws_num]',$draws_num,$sql);	
	$sql = str_replace('[sys_time]',$sys_time,$sql);	
	//echo $sql;
	$q=$db->sql_query($sql);
}
//抓取下一期開獎時間
/*
$game=遊戲代號
回傳
下一期的開獎時間
*/
function ser_get_lt_time($sGame){
	global $db_s;
	$sRet='';
	$aNow_draws=dws_get_now_draws_info($sGame);
	if(empty($aNow_draws)){return $sRet;}
  $rpt_date=$aNow_draws['rpt_date'];
	$date_sn=ser_get_last_result_seq($sGame,$rpt_date);
	$aRet=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='lottery_time';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND date_sn="[date_sn]"';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$aSQL[]='ORDER BY date_sn DESC';
  $aSQL[]='LIMIT 1';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$sSQL=str_replace('[date_sn]',$date_sn+1,$sSQL);
	$sSQL=str_replace('[rpt_date]',$rpt_date,$sSQL);
	$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
	$sRet=$r['lottery_time'];
	return $sRet;
}
//抓取開獎服務狀態
/*
$game=遊戲代號
回傳
目前服務狀態
*/
function ser_get_result_status($sGame){
	global $db_s;
	$aRet=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='status';
	$aSQL[]='FROM result_ser_status';
	$aSQL[]='WHERE';
	$aSQL[]='game="[game]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[game]',$sGame,$sSQL);
	$info=$sSQL;
	$db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
		$aRet=$r['status'];
	}
	return $aRet;
}
//更改開獎服務狀態
/*
$game=遊戲代號
$status=開獎服務狀態 1=檢查是否到達開獎時間 2=檢查是否已開獎 3=抓取獎號
*/
function ser_update_result_status($game,$status){
	global $db;
	$aSQL[]='UPDATE result_ser_status';
	$aSQL[]='SET status="[status]"';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND game="[game]"';//需要更改開獎時間的期數名稱
	$sSQL=implode(' ',$aSQL);
	$sSQL = str_replace('[game]',$game,$sSQL);
	$sSQL = str_replace('[status]',$status,$sSQL);	
	$q=$db->sql_query($sSQL);
}
//刪除今日結果
function ser_del_result($game,$date){
	global $db;
	$SQL=array();
	$SQL[]='DELETE';
	$SQL[]='FROM draws_'.$game.'_result';
	$SQL[]='WHERE 1';
	$SQL[]='AND rpt_date = "[date]"';
	$SQL=str_replace('[date]',$date,$SQL);
	$sql=implode(' ',$SQL);
	$q=$db->sql_query($sql);
}
//取得今日所有期數 --重新生成結果用
/*
傳入
	遊戲代號
	帳務日期
回傳
	期數表列
	[
		期數名稱=>當日序號
	]
*/
function ser_get_draws_list($sGame,$sRpt_date){
  global $db_s;
	$aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='rpt_date';
  $aSQL[]=',date_sn';
  $aSQL[]=',draws_num';
  $aSQL[]='FROM draws_[game]';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND rpt_date = "[rpt_date]"';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[game]',$sGame,$sSQL);
  $sSQL=str_replace('[date_sn]',$sDraws_sn,$sSQL);
  $sSQL=str_replace('[rpt_date]',$sRpt_date,$sSQL);
  $db_s->sql_query($sSQL);
  while($r=$db_s->nxt_row('ASSOC')){
	$aRet[$r['draws_num']]=array($r['rpt_date'],$r['date_sn']);
	}
  return $aRet;
}
//產生今天所有期數 資料跟結果
function ser_lt_date_list($game,$ary,$date){
	$data=ser_get_draws_list($game,$date);
	foreach($ary as $key => $v){
		$draws_num=$v['draws_num'];
		if($data[$draws_num]==""){continue;}
		$ret[]=array_merge($data[$draws_num],$v);
	}
	return $ret;
}
//檢查 期數是否開獎 有就把 開獎結果 寫成資料庫格式
/*
	*期數是否開獎
	*修正我們的格式
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式
*/
function mke_lottery_num_list_168new($sGame){
	$aRet=array();
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	//echo $dws['draws_num']."\n";
	if($sGame=='nc'){
		$sDraws=substr($dws['draws_num'],0,8).'0'.substr($dws['draws_num'],8,10);
	}
	//echo $sDraws."\n";
	$lt=ser_get_result_168new($sDraws,$sGame);
	if(count($lt)<1){
		return $aRet;
	}
	$preDrawIssue=$lt['result']['data']['preDrawIssue'];
	if($sGame=='nc'){
		$isn=(int)substr($preDrawIssue,9,11);
		//echo $sn;
		$sn=($isn<10)?"0".$isn:$isn;
		$preDrawIssue=substr($preDrawIssue,0,8).$sn;
	}
	//echo $preDrawIssue."\n";
	$preDrawCode=$lt['result']['data']['preDrawCode'];
	$aNums=explode(',',$preDrawCode);
	$aRet['draws_num']=$preDrawIssue;
	foreach($aNums as $k => $v){
		$num=(int)$v;
		$aRet[]=$num;
		$aNum[]=$num;
	}
	$aRet['lottery_Time']=$lt['result']['data']['preDrawTime'];
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		$aRet['total_sum']=array_sum($aNum_kb);
	}
	return $aRet;
}
//檢查 期數是否開獎 有就把 彩之家 開獎結果 寫成資料庫格式
/*
	*期數是否開獎
	*修正我們的格式
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式
*/
function mke_lottery_num_list_91333($sGame){
	$aRet=array();
	$lt=ser_get_result_91333($sGame);
	/*
	echo '<xmp>';
	print_r($lt);
	echo '</xmp>';
	*/
	if(count($lt)<1){
		return $aRet;
	}
	$preDrawIssue=$lt['l_t'];
	if($sGame=='nc' || $sGame=='klc'){
		$isn=(int)substr($preDrawIssue,9,11);
		$sn=($isn<10)?'0'.$isn:$isn;
		$preDrawIssue=substr($preDrawIssue,0,8).$sn;
	}
	//echo $preDrawIssue."\n";
	$preDrawCode=$lt['l_r'];
	$aNums=explode(',',$preDrawCode);
	$aRet['draws_num']=$preDrawIssue;
	foreach($aNums as $k => $v){
		$num=(int)$v;
		$aRet[]=$num;
		$aNum[]=$num;
	}	
	$aRet['lottery_Time']=date('Y-m-d H:i:s',strtotime($lt['l_d']));
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		$aRet['total_sum']=array_sum($aNum_kb);
	}
	//print_r($aRet);
	return $aRet;
}
//檢查 期數是否開獎 有就把 98007 開獎結果 寫成資料庫格式
/*
	*期數是否開獎
	*修正我們的格式
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式
*/

function mke_lottery_num_list_98007($sGame){
	$aRet=array();
	$lt=ser_get_result_98007($sGame);
	/*
	echo '<xmp>';
	print_r($lt);
	echo '</xmp>';
	*/
	if(count($lt)<1){
		return $aRet;
	}
	$preDrawIssue=$lt['l_t'];
	if($sGame=='nc' || $sGame=='klc'){
		$isn=(int)substr($preDrawIssue,9,11);
		$sn=($isn<10)?'0'.$isn:$isn;
		$preDrawIssue=substr($preDrawIssue,0,8).$sn;
	}
	//echo $preDrawIssue."\n";
	$preDrawCode=$lt['l_r'];
	$aNums=explode(',',$preDrawCode);
	$aRet['draws_num']=$preDrawIssue;
	foreach($aNums as $k => $v){
		$num=(int)$v;
		$aRet[]=$num;
		$aNum[]=$num;
	}	
	$aRet['lottery_Time']=$lt['l_d'];
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		$aRet['total_sum']=array_sum($aNum_kb);
	}
	//print_r($aRet);
	return $aRet;
}

//168舊網站 當期開獎號碼 資料庫新增格式
/*
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式 
*/
function mke_lottery_num_list_168old($sGame){
	$aRet=array();
	$lt=ser_get_result_168old($sGame);
	if(count($lt)<1){
		return $aRet;
	}
	if($lt['c_t']=='0'){
		return $aRet;
	}
	$aDraws_num=array('draws_num'=>$lt['c_t']);
	$aNums=explode(',',$lt['c_r']);
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
	$aRet=array_merge($aDraws_num,$aNum);
	$aRet['lottery_Time']=date('Y-m-d H:i:s',strtotime($lt['c_d']));
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aNum_kb_sum=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		$aRet['total_sum']=array_sum($aNum_kb_sum);
	}
	return $aRet;
}
//1399p網站 當期開獎號碼 資料庫新增格式
/*
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式 
	*檢查 獎號是否 有抓到
	*檢查 陣列格式
	*檢查 是否開獎
*/
function mke_lottery_num_list_1399p($sGame){
	$aRet=array();
	$aRet=mke_lottery_num_list_1399p_v2($sGame);
	return $aRet;
	$lt=ser_get_result_1399p($sGame);
	//print_r($lt);
	//檢查 獎號是否 有抓到
	if(count($lt)<1){
		return $aRet;
	}
	//檢查 陣列格式
	switch($sGame){
		case 'nc':
			$periodDate=$lt['current']['period'];
			break;
		case 'kb':
			$periodDate=$lt['current']['periodNumber'];
			break;
		case 'pk':
			$periodDate=$lt['current']['periodNumber'];
			break;
		default:
			$periodDate=$lt['current']['periodDate'];
			break;
	}
	$awardNumbers=$lt['current']['awardNumbers'];
	$awardTime=$lt['current']['awardTime'];
	//檢查 抓回的獎號 是否為空結果
	if($periodDate=='0' || $periodDate==''){
		return $aRet;
	}
	$sDraws_num=$periodDate;
	if($sGame=='ssc'){
		$isn=(int)substr($periodDate,8,10);
		if($isn<100){
			$sn="0".$isn;
		}else if($isn<10){
			$sn="00".$isn;
		}else{
			$sn=$isn;
		}
		$sDraws_num=substr($periodDate,0,8).$sn;
	}
	if($sGame=='nc' || $sGame=='klc'){
		$isn=(int)substr($periodDate,8,10);
		if($isn<10){
			$sn="0".$isn;
		}else{
			$sn=$isn;
		}
		$sDraws_num=substr($periodDate,0,8).$sn;
		//echo $sDraws_num;
	}
	$aDraws_num=array('draws_num'=>$sDraws_num);
	$aNums=explode(',',$awardNumbers);
	foreach($aNums as $k => $v){
		$aNum[]=(int)$v;
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		$aNum[20]=$lt['current']['pan'];
	}
	$aRet=array_merge($aDraws_num,$aNum);
	$aRet['lottery_Time']=$awardTime;
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aRet['total_sum']=array_sum($aNum_kb);
	}
	return $aRet;
}
//1399p網站 當期開獎號碼 資料庫新增格式
/*
	傳入:
		遊戲編號
	回傳:
		資料庫需要格式 
	*檢查 獎號是否 有抓到
	*檢查 陣列格式
	*檢查 是否開獎
*/
function mke_lottery_num_list_1399p_v2($sGame){
	$debug=true;
	if($debug){
		echo "exec : mke_lottery_num_list_1399p_v2 \n";
	}
	$aRet=array();
	$lt=ser_get_result_1399p_v2($sGame);
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	//print_r($lt);
	//檢查 獎號是否 有抓到
	if(count($lt)<1){
		return $aRet;
	}
	//檢查 陣列格式
	switch($sGame){
		case 'klc':
			$sDraws_sn=(int)substr($sDraws,8,10);
			$sDraws_date=substr($sDraws,0,8);
			$periodDate=$lt['current']['period'];
			if($sDraws_sn==$lt['current']['period']){
				$periodDate=$sDraws;
			}else{
				return $aRet;
			}
			break;
		case 'nc':
			$sDraws_sn=(int)substr($sDraws,8,10);
			$sDraws_date=substr($sDraws,0,8);
			if($sDraws_sn==$lt['current']['period']){
				$periodDate=$sDraws;
			}else{
				return $aRet;
			}
			break;
		case 'ssc':
			$sDraws_sn=(int)substr($sDraws,8,10);
			$sDraws_date=substr($sDraws,0,8);
			if($sDraws_sn==$lt['current']['period']){
				$periodDate=$sDraws;
			}else{
				return $aRet;
			}
			break;
		default:
			$periodDate=$lt['current']['period'];
			break;
	}
	$awardNumbers=$lt['current']['result'];
	$awardNumbers=str_replace('#',',',$awardNumbers);
	$awardTime=$lt['current']['awardTime'];
	//檢查 抓回的獎號 是否為空結果
	if($periodDate=='0' || $periodDate==''){
		return $aRet;
	}
	$sDraws_num=$periodDate;
	/*
	if($sGame=='ssc'){
		$isn=(int)substr($periodDate,8,10);
		if($isn<100){
			$sn="0".$isn;
		}else if($isn<10){
			$sn="00".$isn;
		}else{
			$sn=$isn;
		}
		$sDraws_num=substr($periodDate,0,8).$sn;
	}
	if($sGame=='nc' || $sGame=='klc'){
		$isn=(int)substr($periodDate,8,10);
		if($isn<10){
			$sn="0".$isn;
		}else{
			$sn=$isn;
		}
		$sDraws_num=substr($periodDate,0,8).$sn;
		//echo $sDraws_num;
	}
	*/
	$aDraws_num=array('draws_num'=>$sDraws_num);
	$aNums=explode(',',$awardNumbers);
	foreach($aNums as $k => $v){
		$aNum[]=(int)$v;
	}
	if($sGame=='kb'){
		$aNum_kb=array(
			$aNum[0],$aNum[1],$aNum[2],$aNum[3],$aNum[4],$aNum[5],
			$aNum[6],$aNum[7],$aNum[8],$aNum[9],$aNum[10],$aNum[11],
			$aNum[12],$aNum[13],$aNum[14],$aNum[15],$aNum[16],$aNum[17],
			$aNum[18],$aNum[19]
		);
		//$aNum[20]=$lt['current']['pan'];
	}
	$aRet=array_merge($aDraws_num,$aNum);
	$aRet['lottery_Time']=$awardTime;
	$aRet['total_sum']=array_sum($aNum);
	if($sGame=='pk'){
		$aRet['total_sum']=$aNum[0]+$aNum[1];
	}
	if($sGame=='kb'){
		$aRet['total_sum']=array_sum($aNum_kb);
	}
	return $aRet;
}
//開獎資源 切換 流程 哪個網站先抓到 就先列出
/*
	傳入
		$sGame=遊戲
		$sRpt_date=日期
	回傳
	Array(
		[draws_num] => 期數名稱
		[0] => 1 //各球結果 會遊戲不同 而 有不同球數
		[1] => 2
		[2] => 3
		[3] => 4
		[4] => 5
		[total_sum] => 15 //總和
	)
	才能放進這個流程中 不然會出錯
*/
function ser_lottery_num_list_switch($sGame){
	$aRet=array();
	$lottery_num_list=mke_lottery_num_list_lianju($sGame);
	$aRet=$lottery_num_list;
	if(!empty($aRet)){echo "lianju"; return $aRet;}
	$lottery_num_list=mke_lottery_num_list_168new($sGame);
	$aRet=$lottery_num_list;
	if(!empty($aRet)){echo "168new"; return $aRet;}
	$lottery_num_list=mke_lottery_num_list_1399p($sGame);
	$aRet=$lottery_num_list;
	if(!empty($aRet)){echo "1399p"; return $aRet;}
	return $aRet;
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
function ser_lottery_num_list_switch_v2($sGame){
	$aRet=array();
	//*取得當前 開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$draws=$dws['draws_num'];
	//*取出這期 所有站台的 做開獎號碼 比較
	$sSite='168new';
	$ary_168new=ser_get_lottery_site_list_exist($sGame,$sSite,$draws);
	$sSite='1399p';
	$ary_1399p=ser_get_lottery_site_list_exist($sGame,$sSite,$draws);
	
	$aContrast=array();
	if(!empty($ary_168new)){
		$aContrast['168new']=implode('_',$ary_168new);
	}
	if(!empty($ary_1399p)){
		$aContrast['1399p']=implode('_',$ary_1399p);
	}
	//*因為要比對 至少要有兩組 才會開始比
	if(count($aContrast)<1){
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
//新增開獎結果 168新版
/*
	原本是 抓到後整理格式後 直接進資料庫
	因為 有碰到開獎號碼是錯的
	所以 要先把結果存到站台結果表
	然後 在讀出來 做開獎號碼 比較
	選用 出現最多次的號碼
	
	*取出所有站台 號碼進行比對 用大數法則 決定要用哪組號碼
	*補 日期 跟 當日序號 並更新開獎時間 和 紀錄寫入真正的結果表時間
	*檢查 有沒有開過獎
	*開獎完畢 把開獎失敗 改為成功
*/
function init_inst_lt_rst_168new($sGame){
	$ret=0;
	//*取出所有站台 號碼進行比對 用大數法則 決定要用哪組號碼
	$lt=ser_lottery_num_list_switch($sGame);
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
	if($inst_st==2){
		$ret=1;
	}
	return $ret;	
}
//抓開獎結果 168新版
/*
	多了目錄判斷
	跟 檔名判斷
	傳入:
		遊戲代碼
		要開獎的期數名稱
	回傳:
		當期結果陣列
*/
function ser_get_result_168new($draws,$sGame){
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
	$do_name=array();
	//新版網站 每個遊戲有自己的開獎程式
	$do_name['klc']='getLotteryInfo';
	$do_name['ssc']='getBaseCQShiCai';
	$do_name['nc']='getLotteryInfo';
	$do_name['pk']='getLotteryPksInfo';
	$do_name['kb']='getBaseLuckTewnty';
	$aReferer['klc']='http://www.1680210.com/html/klsf/klsf_index.html';
	$aReferer['ssc']='http://www.1680210.com/html/shishicai_cq/ssc_index.html';
	$aReferer['nc']='http://www.1680210.com/html/cqnc/index.html';
	$aReferer['pk']='http://1680210.com/html/PK10/pk10kai.html';
	$aReferer['kb']='http://www.1680210.com/html/beijinkl8/bjkl8_index.html';
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
	$http = "http://api.1680210.com/$game_root/$game_do.do?issue=$draws&lotCode=$id";
	//echo $http ;
	curl_setopt($ch,CURLOPT_URL,$http);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,true);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,3000);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1500);
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	//print_r($obj);
	return $obj;
}
//抓開獎結果 cp0668
/*
	多了目錄判斷
	跟 檔名判斷
	傳入:
		遊戲代碼
		要開獎的期數名稱
	回傳:
		當期結果陣列
*/
function ser_get_result_cp0668($sGame){
	$root_name=array();
	//新版網站 都放在不同的目錄
	$root_name['klc']='gdkl10';
	$root_name['ssc']='cqssc';
	$root_name['pk']='pk10';
	$root_name['nc']='xync';
	$root_name['kb']='kl8';
	$do_name=array();
	$sReferer_http='https://www.cp0668.com/api/kaijiang?lottery=pk10,cqssc,gdkl10,kl8,xync&';
	$aReferer['klc']=$sReferer_http.'set=gdkl10&bgcolor=ffffff&wgt=980&hgt=35&type=out';
	$aReferer['ssc']=$sReferer_http.'set=cqssc&bgcolor=ffffff&wgt=980&hgt=35&type=out';
	$aReferer['nc']=$sReferer_http.'set=xync&bgcolor=ffffff&wgt=980&hgt=35&type=out';
	$aReferer['pk']=$sReferer_http.'set=pk10&bgcolor=ffffff&wgt=980&hgt=35&type=out';
	$aReferer['kb']=$sReferer_http.'set=kl8&bgcolor=ffffff&wgt=980&hgt=35&type=out';
	$game_root=$root_name[$sGame];
	$sReferer=$aReferer[$sGame];
	$t = mt_rand() / mt_getrandmax();
	$cookie_txt=dirname(dirname(__FILE__))."/text/cp0668.txt";
	$aUser_agent=array();
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	// 建立CURL連線
	$ch = curl_init();
	$http = "https://www.cp0668.com/timeout/$game_root";
	$header=array(
		'Accept:application/json, text/plain, */*'
		,'Accept-Encoding:gzip, deflate, sdch, br'
		,'Connection:keep-alive'
		,'Host:www.cp0668.com'
	);
	curl_setopt($ch,CURLOPT_URL,$http);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_NOSIGNAL,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);  // 跳过证书检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);  // 从证书中检查SSL加密算法是否存在
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	//是否跟隨 重新導向
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,3000);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,1500);
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	// 執行
	$str=curl_exec($ch);
	//echo $str;
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	//print_r($obj);
	return $obj;
}
//抓開獎結果 1399p
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
function ser_get_result_1399p($sGame){
	$root_name=array();
	//新版網站 都放在不同的目錄
	$root_name['klc']='gdkl10';
	$root_name['ssc']='shishicai';
	$root_name['pk']='pk10';
	$root_name['nc']='xync';
	$root_name['kb']='kl8';
	$do_name=array();
	//1399p網站 每個遊戲有自己的get值
	$do_name['klc']='GetGdkl10AwardData';
	$do_name['ssc']='GetCqsscAwardData';
	$do_name['nc']='GetXyncAwardData';
	$do_name['pk']='GetPk10AwardData';
	$do_name['kb']='Getkl8AwardData';
	$game_root=$root_name[$sGame];
	$game_do=$do_name[$sGame];
	//產生0-1 之間的亂數
	$t = mt_rand() / mt_getrandmax();
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://www.1395p.com/$game_root/ajax?ajaxhandler=$game_do&t=$t";
	$aReferer[]="http://www.1395p.com/api";
	$aReferer[]="/kaijiang.html?lottery=pk10,cqssc,gdkl10,kl8,xync";
	$aReferer[]="&set=$game_root&bgcolor=e0e0e0&size=980&hgt=35";
	$sReferer=implode('',$aReferer);
	$cookie_txt=dirname(dirname(__FILE__))."/text/1395p.txt";
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	curl_setopt($ch,CURLOPT_URL,$http);
	//是否回傳檔頭
	curl_setopt($ch,CURLOPT_HEADER,0);
	//是否跟隨 重新導向
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	//是否將結果 以字串回傳
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	//是否啟用 毫秒級等待
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	//模擬 網頁調用 結果 給他 referer
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	//最長等待時間 
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,2000);
	//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1000);
	//模擬 瀏覽器的User_agent
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
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
function ser_get_result_1399p_v2($sGame){
	$root_name=array();
	//新版網站 都放在不同的目錄
	$root_name['klc']='gdkl10';
	$root_name['ssc']='shishicai';
	$root_name['pk']='pk10';
	$root_name['nc']='xync';
	$root_name['kb']='kl8';
	$do_name=array();
	//1399p網站 每個遊戲有自己的get值
	$do_name['klc']='GetGdkl10AwardData';
	$do_name['ssc']='GetCqsscAwardData';
	$do_name['nc']='GetXyncAwardData';
	$do_name['pk']='GetPk10AwardData';
	$do_name['kb']='Getkl8AwardData';
	$game_root=$root_name[$sGame];
	$game_do=$do_name[$sGame];
	//產生0-1 之間的亂數
	$t = mt_rand() / mt_getrandmax();
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://www.1399p.com/$game_root/getawarddata/?t=$t";
	//echo $http;
	$aReferer[]="http://www.1399p.com/api";
	$aReferer[]="/kaijiang.html?lottery=pk10,cqssc,gdkl10,kl8,xync";
	$aReferer[]="&set=$game_root&bgcolor=e0e0e0&size=980&hgt=35";
	$sReferer=implode('',$aReferer);
	$cookie_txt=dirname(dirname(__FILE__))."/text/1395p.txt";
	$aUser_agent[]="Mozilla/5.0";
	$aUser_agent[]="(Windows NT 10.0; WOW64)"; 
	$aUser_agent[]="AppleWebKit/537.36"; 
	$aUser_agent[]="(KHTML, like Gecko)"; 
	$aUser_agent[]="Chrome/53.0.2785.143 Safari/537.36";
	$sUser_agent=implode('',$aUser_agent);
	curl_setopt($ch,CURLOPT_URL,$http);
	//是否回傳檔頭
	curl_setopt($ch,CURLOPT_HEADER,0);
	//是否跟隨 重新導向
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	//是否將結果 以字串回傳
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	//是否啟用 毫秒級等待
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	//模擬 網頁調用 結果 給他 referer
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	//最長等待時間 
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,2000);
	//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1000);
	//模擬 瀏覽器的User_agent
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	// 執行
	$str=curl_exec($ch);
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
}
//抓開獎結果 彩之家
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
function ser_get_result_91333($sGame){
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
	$http = "http://www.91333.com/api/caipiao/get_lists?id=$id&_=$t";
	$aReferer[]="http://www.91333.com";
	$aReferer[]="/$id/";
	$sReferer=implode('',$aReferer);
	$cookie_txt=dirname(dirname(__FILE__))."/text/91333.txt";
	//echo $sReferer;
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
	curl_setopt($ch,CURLOPT_URL,$http);
	//是否回傳檔頭
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$aHeader);
	curl_setopt($ch,CURLINFO_HEADER_OUT,1);
	//是否跟隨 重新導向
	//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	//是否將結果 以字串回傳
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	//是否啟用 毫秒級等待
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	//模擬 網頁調用 結果 給他 referer
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	//最長等待時間 
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,2000);
	//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1000);
	//模擬 瀏覽器的User_agent
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	// 執行
	$str=curl_exec($ch);
	$info = curl_getinfo($ch);
	//echo '<xmp>';
	//print_r($info['request_header']);
	//echo '</xmp>';
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
}
//抓開獎結果 98007
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
function ser_get_result_98007($sGame){
	$aGame_id=array();
	$aCatid=array();
	$aChtime=array();
	$aModelid=array();
	$aCatid['klc']='605';
	$aCatid['ssc']='605';
	$aCatid['pk']='305';
	$aCatid['nc']='605';
	$aCatid['kb']='305';
	$aChtime['klc']='5';
	$aChtime['ssc']='3';
	$aChtime['pk']='2';
	$aChtime['nc']='8';
	$aChtime['kb']='6';
	$aModelid['klc']='12';
	$aModelid['ssc']='10';
	$aModelid['pk']='9';
	$aModelid['nc']='15';
	$aModelid['kb']='13';
	//新版網站 都放在不同的目錄
	$aGame_id['klc']='gdklsf';
	$aGame_id['ssc']='cqssc';
	$aGame_id['pk']='bjpk10';
	$aGame_id['nc']='cqklsf';
	$aGame_id['kb']='bjkl8';
	$cp=$aGame_id[$sGame];
	$catid=$aCatid[$sGame];
	$chtime=$aChtime[$sGame];
	$modelid=$aModelid[$sGame];
	//產生0-1 之間的亂數
	$uptime = time();
	// 建立CURL連線
	$ch = curl_init();
	$http = "http://www.98007.com/index.php?c=api&a=updateinfo&cp=$cp&uptime=$uptime&chtime=$chtime&catid=$catid&modelid=$modelid";
	$aReferer[]="http://www.98007.com";
	$aReferer[]="/list-$cp.html";
	$sReferer=implode('',$aReferer);
	$cookie_txt=dirname(dirname(__FILE__))."/text/98007.txt";
	//echo $sReferer;
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
	//echo $http."\n";
	curl_setopt($ch,CURLOPT_URL,$http);
	//是否回傳檔頭
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$aHeader);
	curl_setopt($ch,CURLINFO_HEADER_OUT,1);
	//是否跟隨 重新導向
	//curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	//是否將結果 以字串回傳
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	//是否啟用 毫秒級等待
	curl_setopt($ch,CURLOPT_NOSIGNAL,1);
	//模擬 網頁調用 結果 給他 referer
	curl_setopt($ch,CURLOPT_REFERER,$sReferer);
	curl_setopt($ch,CURLOPT_USERAGENT,$sUser_agent);
	//模擬 網頁調用 結果 存$cookie
	curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_txt);
	//模擬 網頁調用 結果 送$cookie
	curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_txt);
	curl_setopt($ch,CURLOPT_TIMEOUT_MS,3000);
	//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT_MS,1000);
	//模擬 瀏覽器的User_agent
	// 執行
	$str=curl_exec($ch);
	//echo gzdecode($str);
	$str=gzdecode($str);
	$info = curl_getinfo($ch);
	/*
	echo '<xmp>';
	print_r($info['request_header']);
	echo '</xmp>';
	*/
	// 關閉CURL連線
	curl_close($ch);
	$obj=json_decode($str,true);
	return $obj;
}
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
	)
	*取得開獎結果 整理成 共用得格式
	*存入 站台結果表
*/
function ser_ins_lottery_num_list($sGame){
	$lottery_num_list168new['168new']=mke_lottery_num_list_168new($sGame);
	$lottery_num_list1399p['1399p']=mke_lottery_num_list_1399p($sGame);
	if(!empty($lottery_num_list168new)){
		inst_lottery_site_list($sGame,$lottery_num_list168new);
	}
	if(!empty($lottery_num_list1399p)){
		inst_lottery_site_list($sGame,$lottery_num_list1399p);
	}
}
//檢查 期數是否開獎 修正新增結果 從資料庫抓取lianju
/*
		取得當前開獎時間的期數
		*期數是否開獎
		*修正我們的格式
		傳入:
			遊戲編號
		回傳:
			資料庫需要格式	
*/
function mke_lottery_num_list_lianju($sGame){
	$aRet=array();
	//*取得當前開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	$lottery_time=$dws['lottery_time'];
	$aTable='lianju';
	$lt=ser_get_all_result_data($sGame,$aTable,$sDraws,$lottery_time);
	return $lt;
}
//檢查 期數是否開獎 修正新增結果 從資料庫抓取un
/*
		取得當前開獎時間的期數
		*期數是否開獎
		*修正我們的格式
		傳入:
			遊戲編號
		回傳:
			資料庫需要格式	
*/
function mke_lottery_num_list_un($sGame){
	$aRet=array();
	//*取得當前開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	$lottery_time=$dws['lottery_time'];
	$aTable='un';
	
	$lt=ser_get_all_result_data($sGame,$aTable,$sDraws,$lottery_time);
	return $lt;
}
//檢查 期數是否開獎 修正新增結果 從資料庫抓取ju888
/*
		取得當前開獎時間的期數
		*期數是否開獎
		*修正我們的格式
		傳入:
			遊戲編號
		回傳:
			資料庫需要格式	
*/
function mke_lottery_num_list_ju888($sGame){
	$aRet=array();
	//*取得當前開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$sDraws=$dws['draws_num'];
	$lottery_time=$dws['lottery_time'];
	$aTable='ju888';
	$lt=ser_get_all_result_data($sGame,$aTable,$sDraws,$lottery_time);
	return $lt;
}
//抓開獎結果 
/*
	$aGame='遊戲代碼'
	$aTable='抓取資料庫的表'

*/
function ser_get_db($sGame,$aTable,$sDraws,$lottery_time){
	global $db_s;
	$aRet=array();
	switch($sGame){
		case 'klc':
			$gtype=1;
		break;
		case 'ssc':
			$gtype=2;
		break;
		case 'pk':
			$gtype=3;
		break;
		case 'nc':
			$gtype=4;
		break;
		case 'kb':
			$gtype=5;
		break;
	}
	$SQL[]='SELECT';
	$SQL[]='gid';
	$SQL[]=',num1';
	$SQL[]=',num2';
	$SQL[]=',num3';
	$SQL[]=',num4';
	$SQL[]=',num5';
	$SQL[]=',num6';
	$SQL[]=',num7';
	$SQL[]=',num8';
	$SQL[]=',num9';
	$SQL[]=',num10';
	$SQL[]=',num11';
	$SQL[]=',num12';
	$SQL[]=',num13';
	$SQL[]=',num14';
	$SQL[]=',num15';
	$SQL[]=',num16';
	$SQL[]=',num17';
	$SQL[]=',num18';
	$SQL[]=',num19';
	$SQL[]=',num20';
	$SQL[]=',num21';
	$SQL[]="FROM all_result_[@aTable@]";
	$SQL[]='WHERE 1';
	$SQL[]="AND `gid`='[@dws@]'";
	$SQL[]="AND `gtype`='[@gtype@]'";
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[@aTable@]',$aTable,$strSQL);
	$strSQL=str_replace('[@dws@]',$draws,$strSQL);
	$strSQL=str_replace('[@gtype@]',$gtype,$strSQL);
	$db_s->sql_query($strSQL);
	if($db_s->numRows() < 1){ return $aRet;}
	$ary=array();
	$ary_lt=array();
	while($row = $db_s->nxt_row('ASSOC')){
		$ary_lt['draws_num'] = $row['gid'];
		$ary['num0'] = $row['num1'];
		$ary['num1'] = $row['num2'];
		$ary['num2'] = $row['num3'];
		$ary['num3'] = $row['num4'];
		$ary['num4'] = $row['num5'];
		$ary['num5'] = $row['num6'];
		$ary['num6'] = $row['num7'];
		$ary['num7'] = $row['num8'];
		$ary['num8'] = $row['num9'];
		$ary['num9'] = $row['num10'];
		$ary['num10'] = $row['num11'];
		$ary['num11'] = $row['num12'];
		$ary['num12'] = $row['num13'];
		$ary['num13'] = $row['num14'];
		$ary['num14'] = $row['num15'];
		$ary['num15'] = $row['num16'];
		$ary['num16'] = $row['num17'];
		$ary['num17'] = $row['num18'];
		$ary['num18'] = $row['num19'];
		$ary['num19'] = $row['num20'];
		$ary['num20'] = $row['num21'];
	}
	foreach ((array) $ary as $key =>$val){
		if($ary[$key]>=0){
			$aNum[]=$val;
		}else{
			continue;	
		}	
	}
	if($sGame=='kb'){
		sort($aNum);
		$aNum['20']=0;
	}
		$time['lottery_Time']=$lottery_time;
	if($sGame=='pk'){
		$sum['total_sum']=$aNum[0]+$aNum[1];
	}else{
		$sum['total_sum']=array_sum($aNum);
	}
	$aRet = array_merge($ary_lt,$aNum,$time,$sum);
	//print_r($aRet);
	return $aRet;
}
//抓存放ap 抓回來的 結果表時 遊戲欄位切換
/*

*/
function sql_get_all_result_num_col($sGame){
	switch($sGame){
		case 'klc':
			$column=array(
				 'num1 as num0'
				,'num2 as num1'
				,'num3 as num2'
				,'num4 as num3'
				,'num5 as num4'
				,'num6 as num5'
				,'num7 as num6'
				,'num8 as num7'
			);
		break;
		case 'ssc':
			$column=array(
				 'num1 as num0'
				,'num2 as num1'
				,'num3 as num2'
				,'num4 as num3'
				,'num5 as num4'
			);
			break;
		case 'pk':
			$column=array(
				 'num1 as num0'
				,'num2 as num1'
				,'num3 as num2'
				,'num4 as num3'
				,'num5 as num4'
				,'num6 as num5'
				,'num7 as num6'
				,'num8 as num7'
				,'num9 as num8'
				,'num10 as num9'
			);
			break;
		case 'nc':
			$column=array(
				 'num1 as num0'
				,'num2 as num1'
				,'num3 as num2'
				,'num4 as num3'
				,'num5 as num4'
				,'num6 as num5'
				,'num7 as num6'
				,'num8 as num7'
			);
		break;
		case 'kb':
			$column=array(
				 'num1 as num0'
				,'num2 as num1'
				,'num3 as num2'
				,'num4 as num3'
				,'num5 as num4'
				,'num6 as num5'
				,'num7 as num6'
				,'num8 as num7'
				,'num9 as num8'
				,'num10	as num9'
				,'num11	as num10'
				,'num12	as num11'
				,'num13	as num12'
				,'num14	as num13'
				,'num15	as num14'
				,'num16	as num15'
				,'num17	as num16'
				,'num18	as num17'
				,'num19	as num18'
				,'num20	as num19'
				,'num21	as num20'
			);
		break;
	}
	return $column;
}
//抓開獎結果 
/*
	$aGame='遊戲代碼'
	$aTable='抓取資料庫的表'
	回傳
		期數名稱
		第一球 到 最後一球
		開獎時間
		總和
		

*/
function ser_get_all_result_data($sGame,$aTable,$sDraws,$lottery_time){
	global $db_s;
	$aRet=array();
	switch($sGame){
		case 'klc':
			$gtype=1;
		break;
		case 'ssc':
			$gtype=2;
		break;
		case 'pk':
			$gtype=3;
		break;
		case 'nc':
			$gtype=4;
		break;
		case 'kb':
			$gtype=5;
		break;
	}
	$column=sql_get_all_result_num_col($sGame);
	$SQL[]='SELECT';
	$SQL[]='gid';
	$SQL[]=',[column]';
	$SQL[]="FROM all_result_[@aTable@]";
	$SQL[]='WHERE 1';
	$SQL[]="AND `gid`='[@dws@]'";
	$SQL[]="AND `gtype`='[@gtype@]'";
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[@aTable@]',$aTable,$strSQL);
	$strSQL=str_replace('[@dws@]',$sDraws,$strSQL);
	$strSQL=str_replace('[@gtype@]',$gtype,$strSQL);
	$strSQL=str_replace('[column]',implode(',',$column),$strSQL);
	$db_s->sql_query($strSQL);
	if($db_s->numRows() < 1){ return $aRet;}
	$r=$db_s->nxt_row('ASSOC');
	if($r['gid']==''){ return $aRet;}
	$aNum=array();
	$ary_lt=array();
	$ary_lt['draws_num']=$r['gid'];
	foreach ((array) $r as $key =>$val){
		if($key=='gid'){
			continue;	
		}
		if($val==-1){
			$val=0;
		}
		$aNum[]=$val;
	}
	$time['lottery_Time']=$lottery_time;
	if($sGame=='pk'){
		$sum['total_sum']=$aNum[0]+$aNum[1];
	}else{
		$sum['total_sum']=array_sum($aNum);
	}
	$aRet=array_merge($ary_lt,$aNum,$time,$sum);
	return $aRet;
}
?>