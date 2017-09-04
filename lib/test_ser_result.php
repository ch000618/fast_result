<?php 
ini_set('display_errors', 1); //顯示錯誤訊息
error_reporting(E_ALL); //錯誤回報

include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);

ini();
function ini($sGame='nc'){
		$test=mke_hislist_num_list_168new($sGame);
		/*
		echo '<xmp>';
		print_r($test);
		echo '</xmp>';
		*/
	}
/*
function mke_lottery_num_list_un($aGame){
	$aRet=array();
	//*取得當前開獎時間的期數
	$dws=dws_get_now_lottery_info($aGame);
	$draws=$dws['draws_num'];
	$lottery_time=$dws['lottery_time'];
	$aTable='un';
	$lt=ser_get_un($aGame,$aTable,$draws,$lottery_time);
	
	return $lt;
}

//抓開獎結果 資料庫
/*
	$aGame='遊戲代碼'
	$aTable='抓取資料庫的表'

*/
/*
function ser_get_un($aGame,$aTable,$draws,$lottery_time){
	global $db_s;
	
	switch($aGame){
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
	echo '<br>';
	echo $strSQL;
	$db_s->sql_query($strSQL);
	if($db_s->affected_rows == 0){ return;}
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
		if($ary[$key]>0){
			$test[]=$val;
		}else{
			continue;	
		}	
	}
	if($aGame=='kb'){
		sort($test);
		$test['20']=0;
	}
		$time['lottery_Time']=$lottery_time;
	if($aGame=='pk'){
		$sum['total_sum']=$test[0]+$test[1];
	}else{
		$sum['total_sum']=array_sum($test);
	}
	$ary_lt = array_merge($ary_lt,$test,$time,$sum);
	return $ary_lt;
}

function mke_lottery_num_list_ju888($aGame){
	$aRet=array();
	//*取得當前開獎時間的期數
	$dws=dws_get_now_lottery_info($aGame);
	$draws=$dws['draws_num'];
	$lottery_time=$dws['lottery_time'];
	$aTable='ju888';
	$lt=ser_get_ju888($aGame,$aTable,$draws,$lottery_time);
	
	return $lt;
}

//抓開獎結果 資料庫
/*
	$aGame='遊戲代碼'
	$aTable='抓取資料庫的表'

*/
/*
function ser_get_ju888($aGame,$aTable,$draws,$lottery_time){
	global $db_s;
	
	switch($aGame){
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
	echo '<br>';
	echo $strSQL;
	$db_s->sql_query($strSQL);
	if($db_s->affected_rows == 0){ return;}
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
		if($ary[$key]>0){
			$test[]=$val;
		}else{
			continue;	
		}	
	}
	if($aGame=='kb'){
		sort($test);
		$test['20']=0;
	}
		$time['lottery_Time']=$lottery_time;
	if($aGame=='pk'){
		$sum['total_sum']=$test[0]+$test[1];
	}else{
		$sum['total_sum']=array_sum($test);
	}
	$ary_lt = array_merge($ary_lt,$test,$time,$sum);
	return $ary_lt;
}
*/
//比錯誤資訊的機率
/*
	傳入:
			$sGame=遊戲代碼
	傳回:
			site=站台名稱
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
  while($r=$db_s->nxt_row('ASSOC')){
    $aRet[]=$r['site'];
  }
  return $aRet;  
}
//
function ser_lottery_num_list_switch_v3($sGame){
	//*取得當前 開獎時間的期數
	$dws=dws_get_now_lottery_info($sGame);
	$draws=$dws['draws_num'];
	//*取出這期 所有站台的 做開獎號碼 比較
	$sSite=ser_get_error_cnt_rank($sGame);
	
	print_r($sSite);
	foreach($sSite as $name =>$value){
		$ary_new=test_ser_get_lottery_site_list_exist($sGame,$sSite[$name],$draws);
		if(count($ary_new)<1){
			continue;
		}else{
			$ary_new2[]=$ary_new;
		}
	}
	$ret=$ary_new2[0];
	return $ret;
}
//去抓取最新的資訊
function test_ser_get_lottery_site_list_exist($sGame,$name,$draws){
	global $db_s;
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
		$aSQL[]='SELECT';
		$aSQL[]='draws_num';
		$aSQL[]=',[column]';
		$aSQL[]=',lottery_Time';
		$aSQL[]=',total_sum';
		$aSQL[]=',site';
		$aSQL[]='FROM site_[game]_result';
		$aSQL[]='WHERE 1';
		$aSQL[]='AND site="[site]"';
		$aSQL[]='AND draws_num="[draws_num]"';
		$aSQL[]='LIMIT 1';
		$sSQL=implode(' ',$aSQL);  
		$sSQL=str_replace('[game]',$sGame,$sSQL);
		$sSQL=str_replace('[site]',$name,$sSQL);
		$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
		$sSQL=str_replace('[draws_num]',$draws,$sSQL);
		$db_s->sql_query($sSQL);
		$aRet=$db_s->nxt_row('ASSOC');
  return $aRet;
}
?>