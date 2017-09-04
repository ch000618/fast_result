<?php
/*新增遺漏表
	推算出球號遺漏表
		遺漏表{
			期數 10個號碼 範圍1~8 
			假設第一期 1,3,5,7 遺漏陣列[0,1,0,1,0,1,0,1]
			第二期開出 2,3,5,6 遺漏陣列[1,0,0,1,0,0,1,1]
			第三期開出 2,3,5,7 遺漏陣列[1,0,0,1,0,1,0,1]
			第四期開出 1,2,4,5
			第五期開出 3,5,7,8
			第六期開出 3,4,6,8
			    1 2 3 4 5 6 7 8
			001,0,1,0,1,0,1,1,1
			002,1,1,0,2,0,1,2,2
			003,2,1,0,3,0,2,2,3
			004,2,1,1,3,0,3,3,4
			005,4,3,1,4,0,4,2,4
			006,5,4,1,4,0,4,4,4
		}
		*用日期跟遊戲找出有開獎的當日序號
		*當天沒有開獎就不做遺漏表
		*檢查這期有無資料 有資料就跳離迴圈
		*切換遊戲
		*抓出上期遺漏表
		*抓這一期號碼
		產生這期的遺漏陣列       [有開獎的填0,沒開獎的填1]
		產生上期的遺漏陣列       [有開獎的填0,沒開獎的填1]
			如果上一期沒有遺漏表
				就新增這一期的遺漏表
			寫入球號遺漏表
			如果上一期有遺漏表
			*產生新的遺漏陣列
				確認遺漏的開獎號碼
					沒開的這期遺漏陣列的1 +上期的該號碼遺漏次數
					有開的替換成這期遺漏陣列的0
			*寫入球號遺漏表
*/
function run_inst_drop($game,$date){
	$debug=false;
	$ret='';
	switch($game){
		case 'klc':
			$num_min=1;//最小號碼
			$num_max=20;//最大號碼
			break;
		case 'nc':
			$num_min=1;//最小號碼
			$num_max=20;//最大號碼
			break;
		case 'ssc':
			$num_min=0;
			$num_max=9;
			$ball_min=1;
			$ball_max=5;
			break;			
	}
	//用日期跟遊戲找出有開獎的當日序號
	$get_result_sn=drop_get_result_sn($game,$date);
	//當天沒有開獎就不做遺漏表
	if(!isset($get_result_sn)){return $ret;}
	foreach($get_result_sn as $key => $date_sn){
		//檢查這期有無資料 有資料就跳到下一期
		$chk_repeat=drop_chk_repeat($game,$date,$date_sn);
		if(count($chk_repeat)>0){continue;}
		//抓出上期遺漏表
		$get_drop_old=drop_get_old_drop($game,$date,$date_sn-1);		
		//抓這一期號碼
		$rst_now=drop_get_now_result($game,$date,$date_sn);
		//抓上期號碼
		$rst_old=drop_get_now_result($game,$date,$date_sn-1);
		if($game != 'ssc'){
			//產生這期的遺漏陣列
			$drop_now=get_result_drop($rst_now,$num_min,$num_max);
			//產生上期的遺漏陣列
			$drop_old=get_result_drop($rst_old,$num_min,$num_max);
		}else{
			//產生這期的遺漏陣列
			$drop_now=get_result_drop_ssc($rst_now,$num_min,$num_max,$ball_min,$ball_max);
			//產生上期的遺漏陣列
			$drop_old=get_result_drop_ssc($rst_old,$num_min,$num_max,$ball_min,$ball_max);
			//print_r($drop_now);
		}
		$drop_now_times=$drop_now['draws_drop'];
		$drop_old_times=$drop_old['draws_drop'];
		$drop_now_sn=$drop_now['draws_sn'];
		$drop_now_num=$drop_now['draws_num'];
		//如果沒資料就將當期的遺漏陣列寫入資料庫
		if(!isset($get_drop_old)){
			inst_drop($game,$date,$drop_now);
		}else{
		if($debug){
			echo '<xmp>';
			print_r($rst_now);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_now_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($rst_old);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_old_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($get_drop_old);
			echo '</xmp>';
		}
		//如果這一期的號碼跟上一期的號碼 相同+1 不同為0
		$times=0;
		//產生新的遺漏陣列
		foreach($drop_now_times as $k => $v){
			//確認遺漏的開獎號碼
			//沒開的這期遺漏陣列的1 +上期的該號碼遺漏次數
			if($drop_now_times[$k]==$drop_old_times[$k]){
				$times=$get_drop_old[$k]+$v;
				$new_drop[$k]=$times;
			//有開的替換成這期遺漏陣列的0
			}else{
				$times=$v;
				$new_drop[$k]=$times;
			}
		}
		$tmp=array(
			'draws_sn' => $drop_now_sn
			,'draws_num'=> $drop_now_num
			,'draws_drop'=>$new_drop
		);
		//寫入球號遺漏表
		inst_drop($game,$date,$tmp);
		}
	}
}
/*新增遺漏表
	推算出球號遺漏表
		遺漏表{
			期數 10個號碼 範圍1~8 
			假設第一期 1,3,5,7 遺漏陣列[0,1,0,1,0,1,0,1]
			第二期開出 2,3,5,6 遺漏陣列[1,0,0,1,0,0,1,1]
			第三期開出 2,3,5,7 遺漏陣列[1,0,0,1,0,1,0,1]
			第四期開出 1,2,4,5
			第五期開出 3,5,7,8
			第六期開出 3,4,6,8
			    1 2 3 4 5 6 7 8
			001,0,1,0,1,0,1,1,1
			002,1,1,0,2,0,1,2,2
			003,2,1,0,3,0,2,2,3
			004,2,1,1,3,0,3,3,4
			005,4,3,1,4,0,4,2,4
			006,5,4,1,4,0,4,4,4
		}
		*用日期跟遊戲找出有開獎的當日序號
		*當天沒有開獎就不做遺漏表
		*檢查這期有無資料 有資料就跳離迴圈
		*切換遊戲
		*抓出上期遺漏表
		*抓這一期號碼
		產生這期的遺漏陣列       [有開獎的填0,沒開獎的填1]
		產生上期的遺漏陣列       [有開獎的填0,沒開獎的填1]
			如果上一期沒有遺漏表
				就新增這一期的遺漏表
			寫入球號遺漏表
			如果上一期有遺漏表
			*產生新的遺漏陣列
				確認遺漏的開獎號碼
					沒開的這期遺漏陣列的1 +上期的該號碼遺漏次數
					有開的替換成這期遺漏陣列的0
			*寫入球號遺漏表
*/
function run_inst_drop_test($game,$date){
	$debug=false;
	$ret='';
	switch($game){
		case 'klc':
			$num_min=1;//最小號碼
			$num_max=20;//最大號碼
			break;
		case 'nc':
			$num_min=1;//最小號碼
			$num_max=20;//最大號碼
			break;
		case 'ssc':
			$num_min=0;
			$num_max=9;
			$ball_min=1;
			$ball_max=5;
			break;			
	}
	//用日期跟遊戲找出有開獎的當日序號
	$get_result_sn=drop_get_result_sn($game,$date);
	//當天沒有開獎就不做遺漏表
	if(!isset($get_result_sn)){return $ret;}
	foreach($get_result_sn as $key => $date_sn){
		//檢查這期有無資料 有資料就跳到下一期
		$chk_repeat=drop_chk_repeat($game,$date,$date_sn);
		if(count($chk_repeat)>0){continue;}
		//抓出上期遺漏表
		$get_drop_old=drop_get_old_drop($game,$date,$date_sn-1);		
		//抓這一期號碼
		$rst_now=drop_get_now_result($game,$date,$date_sn);
		//抓上期號碼
		$rst_old=drop_get_now_result($game,$date,$date_sn-1);
		if($game != 'ssc'){
			//產生這期的遺漏陣列
			$drop_now=get_result_drop($rst_now,$num_min,$num_max);
			//產生上期的遺漏陣列
			$drop_old=get_result_drop($rst_old,$num_min,$num_max);
		}else{
			//產生這期的遺漏陣列
			$drop_now=get_result_drop_ssc($rst_now,$num_min,$num_max,$ball_min,$ball_max);
			//產生上期的遺漏陣列
			$drop_old=get_result_drop_ssc($rst_old,$num_min,$num_max,$ball_min,$ball_max);
			//print_r($drop_now);
		}
		$drop_now_times=$drop_now['draws_drop'];
		$drop_old_times=$drop_old['draws_drop'];
		$drop_now_sn=$drop_now['draws_sn'];
		$drop_now_num=$drop_now['draws_num'];
		//如果沒資料就將當期的遺漏陣列寫入資料庫
		if(!isset($get_drop_old)){
			inst_drop($game,$date,$drop_now);
		}else{
		if($debug){
			echo '<xmp>';
			print_r($rst_now);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_now_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($rst_old);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_old_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($get_drop_old);
			echo '</xmp>';
		}
		//如果這一期的號碼跟上一期的號碼 相同+1 不同為0
		$times=0;
		//產生新的遺漏陣列
		foreach($drop_now_times as $k => $v){
			//確認遺漏的開獎號碼
			//沒開的這期遺漏陣列的1 +上期的該號碼遺漏次數
			if($drop_now_times[$k]==$drop_old_times[$k]){
				$times=$get_drop_old[$k]+$v;
				$new_drop[$k]=$times;
			//有開的替換成這期遺漏陣列的0
			}else{
				$times=$v;
				$new_drop[$k]=$times;
			}
		}
		$tmp=array(
			'draws_sn' => $drop_now_sn
			,'draws_num'=> $drop_now_num
			,'draws_drop'=>$new_drop
		);
		//寫入球號遺漏表
		inst_drop($game,$date,$tmp);
		}
	}
	/*
	//原本的寫法
	foreach($get_result_sn as $key => $date_sn){
		//檢查這期有無資料 有資料就跳離迴圈
		$chk_repeat=drop_chk_repeat($game,$date,$date_sn);
		if(count($chk_repeat)>0){continue;}
		//切換遊戲
		switch($game){
			case 'klc':
				$num_min=1;//最小號碼
				$num_max=20;//最大號碼
				//抓出上期遺漏表
				$get_drop_old=drop_get_old_drop($game,$date,$date_sn-1);
				//抓這一期號碼
				$rst_now=drop_get_now_result($game,$date,$date_sn);
				//抓上期號碼
				$rst_old=drop_get_now_result($game,$date,$date_sn-1);
				//產生這期的遺漏陣列
				$drop_now=get_result_drop($rst_now,$num_min,$num_max);
				//產生上期的遺漏陣列
				$drop_old=get_result_drop($rst_old,$num_min,$num_max);
			break;
			case 'nc':
				$num_min=1;//最小號碼
				$num_max=20;//最大號碼
				//抓出上期遺漏表
				$get_drop_old=drop_get_old_drop($game,$date,$date_sn-1);
				//抓這一期號碼
				$rst_now=drop_get_now_result($game,$date,$date_sn);
				//抓上期號碼
				$rst_old=drop_get_now_result($game,$date,$date_sn-1);
				//產生這期的遺漏陣列
				$drop_now=get_result_drop($rst_now,$num_min,$num_max);
				//產生上期的遺漏陣列
				$drop_old=get_result_drop($rst_old,$num_min,$num_max);
			break;
			case 'ssc':
				$num_min=0;
				$num_max=9;
				$ball_min=1;
				$ball_max=5;
				//抓出上期遺漏表
				$get_drop_old=drop_get_old_drop($game,$date,$date_sn-1);
				//抓這一期號碼
				$rst_now=drop_get_now_result($game,$date,$date_sn);
				//抓上期號碼
				$rst_old=drop_get_now_result($game,$date,$date_sn-1);
				//產生這期的遺漏陣列
				$drop_now=get_result_drop_ssc($rst_now,$num_min,$num_max,$ball_min,$ball_max);
				//產生上期的遺漏陣列
				$drop_old=get_result_drop_ssc($rst_old,$num_min,$num_max,$ball_min,$ball_max);
				//print_r($drop_now);
			break;
		}
		$drop_now_times=$drop_now['draws_drop'];
		$drop_old_times=$drop_old['draws_drop'];
		$drop_now_sn=$drop_now['draws_sn'];
		$drop_now_num=$drop_now['draws_num'];
		//如果沒資料就將當期的遺漏陣列寫入資料庫
		if(!isset($get_drop_old)){
			inst_drop($game,$date,$drop_now);
		}else{
		if($debug){
			echo '<xmp>';
			print_r($rst_now);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_now_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($rst_old);
			echo '</xmp>';
			echo '<xmp>';
			print_r($drop_old_times);
			echo '</xmp>';
			echo '<xmp>';
			print_r($get_drop_old);
			echo '</xmp>';
		}
		//如果這一期的號碼跟上一期的號碼 相同+1 不同為0
		$times=0;
		//產生新的遺漏陣列
		foreach($drop_now_times as $k => $v){
			//確認遺漏的開獎號碼
			//沒開的這期遺漏陣列的1 +上期的該號碼遺漏次數
			if($drop_now_times[$k]==$drop_old_times[$k]){
				$times=$get_drop_old[$k]+$v;
				$new_drop[$k]=$times;
			//有開的替換成這期遺漏陣列的0
			}else{
				$times=$v;
				$new_drop[$k]=$times;
			}
		}
		$tmp=array(
			'draws_sn' => $drop_now_sn
			,'draws_num'=> $drop_now_num
			,'draws_drop'=>$new_drop
		);
		//寫入球號遺漏表
		inst_drop($game,$date,$tmp);
		}
	}
	*/
}

function inst_drop($game,$date,$drop){
	global $db;
	switch($game){
		case 'klc':
			$col=array(
					'rpt_date','date_sn','draws_num'
					,'num_01','num_02','num_03','num_04','num_05'
					,'num_06','num_07','num_08','num_09','num_10'
					,'num_11','num_12','num_13','num_14','num_15'
					,'num_16','num_17','num_18','num_19','num_20'
			);
		break;
			case 'nc':
			$col=array(
					'rpt_date','date_sn','draws_num'
					,'num_01','num_02','num_03','num_04','num_05'
					,'num_06','num_07','num_08','num_09','num_10'
					,'num_11','num_12','num_13','num_14','num_15'
					,'num_16','num_17','num_18','num_19','num_20'
			);
		break;
		case 'ssc':
			$col=array(
					'rpt_date','date_sn','draws_num'
					,'ball_1_num0','ball_1_num1','ball_1_num2','ball_1_num3','ball_1_num4'
					,'ball_1_num5','ball_1_num6','ball_1_num7','ball_1_num8','ball_1_num9'
					,'ball_2_num0','ball_2_num1','ball_2_num2','ball_2_num3','ball_2_num4'
					,'ball_2_num5','ball_2_num6','ball_2_num7','ball_2_num8','ball_2_num9'
					,'ball_3_num0','ball_3_num1','ball_3_num2','ball_3_num3','ball_3_num4'
					,'ball_3_num5','ball_3_num6','ball_3_num7','ball_3_num8','ball_3_num9'
					,'ball_4_num0','ball_4_num1','ball_4_num2','ball_4_num3','ball_4_num4'
					,'ball_4_num5','ball_4_num6','ball_4_num7','ball_4_num8','ball_4_num9'
					,'ball_5_num0','ball_5_num1','ball_5_num2','ball_5_num3','ball_5_num4'
					,'ball_5_num5','ball_5_num6','ball_5_num7','ball_5_num8','ball_5_num9'
			);
		break;
	}
	$SQL='INSERT INTO draws_[game]_drop ([cols])VALUES("[[cols2]]")';
	$SQL=str_replace('[cols]',implode(',',$col),$SQL);
	$SQL=str_replace('[cols2]',implode(']","[',$col),$SQL);
	$strSQL=$SQL;
	$strSQL=str_replace('[game]',$game,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	$strSQL=str_replace('[date_sn]',$drop['draws_sn'],$strSQL);
	$strSQL=str_replace('[draws_num]',$drop['draws_num'],$strSQL);
	$drop=$drop['draws_drop'];
		foreach($drop as $col => $drop_cnt){
			$strSQL=str_replace("[$col]",$drop_cnt,$strSQL);
		}
		//echo $strSQL;
	$q=$db->sql_query($strSQL);
}
//刪除球號分配表
//產生遺漏表
/*
	$result=開獎結果,$num_min=最小球號,$num_max=最大球號
	回傳[]:{
		draws_sn:當日序號
		draws_num:期數號碼
		draws_drop[球號]=次數
	}
*/
function get_result_drop($result,$num_min,$num_max){
	$ret=array();
	$drop=array();
	for($i=$num_min;$i<=$num_max;$i++){
		$strI=($i<10)?'0'.$i:$i;
		$drop['num_'.$strI]=0;
	}
	foreach($result as $k => $v){
		$draws_sn=$v['sn'];
		$draws_num=$v['draws_num'];
		$rst=$v['result'];
		for($i=$num_min;$i<=$num_max;$i++){
			$strI=($i<10)?'0'.$i:$i;
			if(in_array($i,$rst)){
				$drop['num_'.$strI]=0;
			}else{
				$drop['num_'.$strI]++;
			}
		}
		$tmp=array(
			 'draws_sn' => $draws_sn
			,'draws_num'=> $draws_num
			,'draws_drop'=>$drop
		);
		$ret=$tmp;		
	}
	return $ret;
}
//ssc ssc遺漏陣列
	/*$result=開獎結果,$num_min=最小球號,$num_max=最大球號
	$ball_min=最小球數,$ball_max=最大球數
	回傳[]:{
		draws_sn:當日序號
		draws_num:期數號碼
		draws_drop[第幾球_球號]=次數
	*/
function get_result_drop_ssc($result,$num_min,$num_max,$ball_min,$ball_max){
	//初始化每顆球的 0~9 的數量
	for($i=$ball_min;$i<=$ball_max;$i++){
		for($j=$num_min;$j<=$num_max;$j++){
			$strI=$i;
			$strJ=$j;
			$drop['ball_'.$strI.'_num'.$strJ]=0;
		}
	}
	//將開獎結果取出 
	foreach($result as $k => $v){
		$draws_sn=$v['sn'];
		$draws_num=$v['draws_num'];
		$rst=$v['result'];
		//將開獎的結果填入對應的球和對應的號碼
		//產生每一顆球的遺漏
		for($i=$ball_min;$i<=$ball_max;$i++){
			for($j=$num_min;$j<=$num_max;$j++){
				$strI=$i;
				$strJ=$j;
				//開獎結果陣列的key是從0開始 但球的編號是1~5 所以要-1
				$rst_key=$i-1;
				//開出的號碼為0
				if($j==$rst[$rst_key]){
						$drop['ball_'.$strI.'_num'.$strJ]=0;
				//沒有開出的為1
				}else{
						$drop['ball_'.$strI.'_num'.$strJ]++;
				}
			}
		}
		//當日序號期數名稱 遺漏 填入陣列
		$tmp=array(
		'draws_sn' => $draws_sn
		,'draws_num'=> $draws_num
		,'draws_drop'=>$drop
		);
		$ret=$tmp;	
	}
	return $ret;
}
//讀取結果
/*
$game=遊戲,小寫
回傳[]:{
	sn:當日序號
	draws_num:期數號碼
	result:[開獎結果]
}
*/
function drop_get_result($game,$date){
	global $db_s;
	//初始化	
	$ret=array();
	//切換遊戲
	switch($game){
		case 'klc':
			$table='draws_klc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_7','ball_8');
			break;
		case 'nc':
			$table='draws_nc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_7','ball_8');		
			break;
		case 'ssc':
			$table='draws_ssc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5');		
			break;
	}
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]=',draws_num';
	$SQL[]=',[col_ball]';
	$SQL[]='FROM [table]';
	$SQL[]='WHERE 1';
	$SQL[]='AND rpt_date="[rpt_date]"';
	$SQL[]='AND date_sn="[date_sn]"';
	$SQL[]='ORDER BY date_sn';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[table]',$table,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	$strSQL=str_replace('[col_ball]',implode(',',$col),$strSQL);
	$q=$db_s->sql_query($strSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$sn=$r['date_sn'];
		$draws_num=$r['draws_num'];
		$result=array();
		foreach($col as $k => $v){
			$result[]=$r[$v];
		}
		$ret[]=array(
			 'sn'=> $sn
			,'draws_num' => $draws_num
			,'result' => $result
		);
	}	
	return $ret;
}
//讀取結果 現在期數
/*
$game=遊戲,小寫
回傳[]:{
	sn:當日序號
	draws_num:期數號碼
	result:[開獎結果]
}
*/
function drop_get_now_result($game,$date,$date_sn){
	global $db_s;
	//初始化	
	$ret=array();
	//切換遊戲
	switch($game){
		case 'klc':
			$table='draws_klc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_7','ball_8');
			break;
		case 'nc':
			$table='draws_nc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_7','ball_8');		
			break;
		case 'ssc':
			$table='draws_ssc_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5');		
			break;
		case 'pk':
			$table='draws_pk_result';
			$col=array('rank_1','rank_2','rank_3','rank_4','rank_5','rank_6','rank_7','rank_8','rank_9','rank_10',);		
		break;
		case 'kb':
			$table='draws_kb_result';
			$col=array('ball_1','ball_2','ball_3','ball_4','ball_5','ball_6','ball_7','ball_8','ball_9','ball_10',
								'ball_11','ball_12','ball_13','ball_14','ball_15','ball_16','ball_17','ball_18','ball_19','ball_20'
			);		
		break;
	}
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]=',draws_num';
	$SQL[]=',[col_ball]';
	$SQL[]='FROM [table]';
	$SQL[]='WHERE 1';
	$SQL[]='AND rpt_date="[rpt_date]"';
	$SQL[]='AND date_sn="[date_sn]"';
	$SQL[]='ORDER BY date_sn';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[table]',$table,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	$strSQL=str_replace('[date_sn]',$date_sn,$strSQL);
	$strSQL=str_replace('[col_ball]',implode(',',$col),$strSQL);
	$q=$db_s->sql_query($strSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$sn=$r['date_sn'];
		$draws_num=$r['draws_num'];
		$result=array();
		foreach($col as $k => $v){
			$result[]=$r[$v];
		}
		$ret[]=array(
			 'sn'=> $sn
			,'draws_num' => $draws_num
			,'result' => $result
		);
	}	
	return $ret;
}
//讀取遺漏表
/*
$game=遊戲,小寫
回傳[]:{
	sn:當日序號
	draws_num:期數號碼
}
*/
function drop_get_old_drop($game,$date,$date_sn){
	global $db_s;
	//初始化	
	$ret=array();
	//切換遊戲
	switch($game){
		case 'klc':
			$table='draws_klc_drop';
			$col=array(
			'num_01','num_02','num_03','num_04','num_05','num_06','num_07','num_08'
			,'num_09','num_10','num_11','num_12','num_13','num_14','num_15','num_16'
			,'num_17','num_18','num_19','num_20'
			);
			break;
		case 'ssc':
			$table='draws_ssc_drop';
			$col=array(
			 'ball_1_num0','ball_1_num1','ball_1_num2','ball_1_num3','ball_1_num4'
			,'ball_1_num5','ball_1_num6','ball_1_num7','ball_1_num8','ball_1_num9'
			,'ball_2_num0','ball_2_num1','ball_2_num2','ball_2_num3','ball_2_num4'
			,'ball_2_num5','ball_2_num6','ball_2_num7','ball_2_num8','ball_2_num9'
			,'ball_3_num0','ball_3_num1','ball_3_num2','ball_3_num3','ball_3_num4'
			,'ball_3_num5','ball_3_num6','ball_3_num7','ball_3_num8','ball_3_num9'
			,'ball_4_num0','ball_4_num1','ball_4_num2','ball_4_num3','ball_4_num4'
			,'ball_4_num5','ball_4_num6','ball_4_num7','ball_4_num8','ball_4_num9'
			,'ball_5_num0','ball_5_num1','ball_5_num2','ball_5_num3','ball_5_num4'
			,'ball_5_num5','ball_5_num6','ball_5_num7','ball_5_num8','ball_5_num9'
			);		
			break;
		case 'nc':
			$table='draws_nc_drop';
			$col=array(
			 'num_01','num_02','num_03','num_04','num_05','num_06','num_07','num_08'
			,'num_09','num_10','num_11','num_12','num_13','num_14','num_15','num_16'
			,'num_17','num_18','num_19','num_20'
			);		
			break;
	}
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]=',draws_num';
	$SQL[]=',[col_ball]';
	$SQL[]='FROM [table]';
	$SQL[]='WHERE 1';
	$SQL[]='AND rpt_date="[rpt_date]"';
	$SQL[]='AND date_sn="[date_sn]"';
	$SQL[]='ORDER BY date_sn';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[table]',$table,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	$strSQL=str_replace('[date_sn]',$date_sn,$strSQL);
	$strSQL=str_replace('[col_ball]',implode(',',$col),$strSQL);
	$q=$db_s->sql_query($strSQL);
	//echo $strSQL;
	while($r=$db_s->nxt_row('ASSOC')){
		$result=array();
		foreach($col as $k => $v){
			$result[$v]=$r[$v];
		}
		return $result;
	}
}
//用遊戲取得當天有開獎的期數的當日序號
/*
$game=遊戲,小寫
回傳[]:{
	sn:當日序號
}
*/
function drop_get_result_sn($game,$date){
	global $db_s;
	//初始化	
	$ret=array();
	//切換遊戲
	switch($game){
		case 'klc':
			$table='draws_klc_result';
			break;
		case 'nc':
			$table='draws_nc_result';	
			break;
		case 'ssc':
			$table='draws_ssc_result';	
			break;
		case 'pk':
			$table='draws_pk_result';	
			break;
		case 'kb':
			$table='draws_kb_result';	
			break;
	}
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]='FROM [table]';
	$SQL[]='WHERE';
	$SQL[]='rpt_date="[rpt_date]"';
	$SQL[]='ORDER BY date_sn';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[table]',$table,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	//echo $strSQL;
	$q=$db_s->sql_query($strSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r['date_sn'];
	}
	return $ret;
}
//讀取有遺漏的期數
/*
$game=遊戲,小寫
回傳
	sn:當日序號
*/
function drop_chk_repeat($game,$date,$date_sn){
	global $db_s;
	//初始化	
	$ret=array();
	//切換遊戲
	switch($game){
		case 'klc':
			$table='draws_klc_drop';
			break;
		case 'ssc':
			$table='draws_ssc_drop';
			break;
		case 'nc':
			$table='draws_nc_drop';
			break;
	}
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]='FROM [table]';
	$SQL[]='WHERE 1';
	$SQL[]='AND rpt_date="[rpt_date]"';
	$SQL[]='AND date_sn="[date_sn]"';
	$SQL[]='ORDER BY date_sn';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[table]',$table,$strSQL);
	$strSQL=str_replace('[rpt_date]',$date,$strSQL);
	$strSQL=str_replace('[date_sn]',$date_sn,$strSQL);
	$q=$db_s->sql_query($strSQL);
	//echo $strSQL;
	$result=array();
	$r=$db_s->nxt_row('ASSOC');
	$result=$r['date_sn'];
	return $result;
}
?>