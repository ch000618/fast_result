<?php
	include_once($web_cfg['path_lib'].'func.ips.php'); //包含测试数组$test_ips，包含1760个需要查询所在地的IP地址
//操作紀錄的相關函式
//遊戲跟代號的對照表
function tran_game_Num_data(){
	$ary=array(
			 'klc'=>'11'
			,'ssc'=>'12'
			,'pk'=>'13'
			,'nc'=>'14'
			,'kb'=>'15'
	);
	return $ary;
}
//操作類別對照表
function tran_sysconfig_num_data($data_name){
	//資料庫欄位對照表
	$array=array(
			 'basic'=>'基本設定'
			,'water'=>'水位設置'
			,'autoDiving'=>'自動跳水設定'
			,'bettingLimit'=>'投注限制'
		);
	return $array[$data_name];
}
//遊戲玩法代號對照表
function get_play_chart($game){
	//遊戲玩法代號對照表
	$ary=array(
		 '11'=>array(
				 '00'=>'第一球'
				,'01'=>'第二球'
				,'02'=>'第三球'
				,'03'=>'第四球'
				,'04'=>'第五球'
				,'05'=>'第六球'
				,'06'=>'第七球'
				,'07'=>'第八球'
				,'08'=>'1~8單雙'
				,'09'=>'1~8大小'				
				,'10'=>'1~8尾大小'
				,'11'=>'1~8和數單雙'				
				,'12'=>'總和單雙'			
				,'13'=>'總和大小'
				,'14'=>'總和尾大小'				
				,'15'=>'1~8中發白'			
				,'16'=>'1~8方位'			
				,'17'=>'1~4龍虎'	
				,'18'=>'任選二'	
				,'20'=>'選二聯組'				
				,'21'=>'任選三'		
				,'23'=>'選三前組'
				,'24'=>'任選四'
				,'25'=>'任選五'				
				,'29'=>'正碼'
				)
		,'12'=>array(
				 '00'=>'單碼'
				,'01'=>'兩面'
				,'02'=>'龍虎和'
				,'04'=>'豹子'
				,'05'=>'順子'
				,'06'=>'對子'
				,'07'=>'半順'
				,'08'=>'雜六'
				)
		,'13'=>array(
				 '00'=>'冠軍'
				,'01'=>'亞軍'
				,'02'=>'第三名'
				,'03'=>'第四名'
				,'04'=>'第五名'
				,'05'=>'第六名'
				,'06'=>'第七名'
				,'07'=>'第八名'
				,'08'=>'第九名'
				,'09'=>'第十名'
				,'10'=>'1~10大小'
				,'11'=>'1~10單雙'
				,'12'=>'1~5龍虎'
				,'13'=>'冠亞大小'
				,'14'=>'冠亞單雙'
				,'15'=>'冠亞和'					
				)
		,'14'=>array(
				 '00'=>'第一球'
				,'01'=>'第二球'
				,'02'=>'第三球'
				,'03'=>'第四球'
				,'04'=>'第五球'
				,'05'=>'第六球'
				,'06'=>'第七球'
				,'07'=>'第八球'
				,'29'=>'正碼'
				,'08'=>'1~8單雙'
				,'09'=>'1~8大小'
				,'10'=>'1~8尾大小'
				,'11'=>'1~8和數單雙'
				,'12'=>'總和單雙'
				,'13'=>'總和大小'
				,'14'=>'總和尾大小'
				,'15'=>'1~8中發白'
				,'16'=>'1~8東南西北'
				,'17'=>'1~4龍虎'
				,'20'=>'任選二'
				,'22'=>'選二連直'
				,'21'=>'選二連組'
				,'23'=>'任選三'
				,'48'=>'選三前組'
				,'26'=>'任選四'
				,'27'=>'任選五'			
				)
		,'15'=>array(
				 '00'=>'正碼'
				,'01'=>'總和大小'
				,'02'=>'總和單雙'
				,'03'=>'總和和局'
				,'04'=>'總和過關'
				,'05'=>'前後和'
				,'06'=>'單雙和'
				,'07'=>'五行'			
				)
				
	);
	return $ary[$game];
}
//基本設定中文對照表
function get_basic_chart($data_name){
	//資料庫欄位對照表
	$array=array(
			 'credit_recover_mode'=>'信用额度恢復模式'
			,'game_over_early'=>'封盘時間提前'
			,'del_lower_user'=>'中間商刪除下級賬號'
			,'report_day'=>'中間商可查看報表天數'
			,'game_over_early_d'=>'封盘時間提前(日場)'
			,'game_over_early_n'=>'封盘時間提前(夜場)'
			,'game_open_d'=>'開關盘設置（優先於快速開關盘）正常盘'
			,'game_open_n'=>'開關盘設置（優先於快速開關盘）午夜盘'
		);
	return $array[$data_name];
}
//水位設置中文對照表
/*
*/
function get_water_chart(){
	//資料庫欄位對照表
	$array=array(
			 'odds_a'		=>'A盤賠率'
			,'water_a'	=>'A盤退水'
			,'odds_b'		=>'B盤差分'
			,'water_b'	=>'B盤退水'
			,'odds_c'		=>'C盤差分'
			,'water_c'	=>'C盤退水'
			,'odds_max'	=>'最高賠率'
	);
	return $array;
}
//自動跳水設定中文對照表
function get_autoDiving_chart(){
	//資料庫欄位對照表
	$array=array(
			 'occupy_amt'					=>'纍計占成'
			,'odds_down'					=>'下調步階'
			,'odds_min'						=>'最低赔率'
			,'2way_relate_enable'	=>'两面聯動同路號碼跳水'
			,'2way_relate_rate'		=>' 两面聯動同路號碼跳水比例'
			,'0'									=>' 停用'
			,'1'									=>' 啟用'
	);	
	return $array;
}
//投注限制中文對照表
function get_bettingLimit_chart(){
	//資料庫欄位對照表
	$array=array(
			 'order_min'				=>'单注最低'
			,'order_max'				=>'单注最高'
			,'concede_max'			=>'单项最高'
			,'order_max_tmp'		=>'臨時单注最高'
			,'concede_max_tmp'	=>' 臨時单项最高'
	);	
	return $array;	
}
//補貨設定中文對照表
function get_replenishment_chart(){
	//資料庫欄位對照表
	$array=array(
			 'replenish_amt'		=>'設置金額'
			,'replenish_anale'	=>'設置類型'
			,'0'								=>'手動'
			,'1'								=>'自動'
	);	
	return $array;	
}
//投注限制-srtype中文對照表
/*
	 $gtype			=	遊戲玩法{廣東快樂、重慶時時彩、...}
	,$op_srtype		=	水位設置srtype
	
	回傳:$op_srtype中文名稱{1~8單碼}
*/
function get_bettingLimit_detail_chart($gtype,$op_srtype){
	$array=array(
		 '11'=>array(
				 '00'=>'1~8單碼'
				,'29'=>'正碼'
				,'08'=>'1~8兩面'
				,'12'=>'總和兩面'
				,'15'=>'1~8中發白'
				,'16'=>'1~8方位'			
				,'17'=>'1~4龍虎'	
				,'18'=>'任選二'	
				,'20'=>'選二聯組'				
				,'21'=>'任選三'		
				,'23'=>'選三前組'
				,'24'=>'任選四'
				,'25'=>'任選五'					
		 )
		,'12'=>array(
				 '01'=>'單碼'
				,'02'=>'兩面'
				,'03'=>'龍虎'
				,'04'=>'和'
				,'05'=>'豹子'
				,'06'=>'順子'
				,'07'=>'對子'
				,'08'=>'半順'
				,'09'=>'雜六'
		)
		,'13'=>array(
				 '00'=>'冠亞，3~10單碼'
				,'10'=>'1~10兩面'
				,'12'=>'1~5龍虎'
				,'13'=>'冠亞大小'
				,'14'=>'冠亞單雙'
				,'15'=>'冠亞和'				
		)
		,'14'=>array(
				 '00'=>'1~8單碼'
				,'29'=>'正碼'
				,'08'=>'1~8兩面'
				,'12'=>'總和兩面'	
				,'15'=>'1~8中發白'
				,'16'=>'1~8東南西北'
				,'17'=>'1~4龍虎'
				,'20'=>'任選二'
				,'22'=>'選二連直'
				,'21'=>'選二連組'
				,'23'=>'任選三'
				,'48'=>'選三前組'
				,'26'=>'任選四'
				,'27'=>'任選五'				
		)
		,'15'=>array(
				 '00'=>'正碼'
				,'01'=>'總和大小'
				,'02'=>'總和單雙'
				,'03'=>'總和和局'
				,'04'=>'總和過關'
				,'05'=>'前後和'
				,'06'=>'單雙和'
				,'07'=>'五行'			
		)
	);
	return $array[$gtype][$op_srtype];
}
//水位設置-srtype中文對照表
/*
  	$gtype			=	遊戲玩法{廣東快樂、重慶時時彩、...}
	,$ptype				=	水位設置ptype
	,$op_srtype		=	水位設置srtype
	
	回傳:$op_srtype中文名稱{1~8中發、1~8白}
*/
function get_water_detail_chart($gtype,$ptype,$op_srtype){
	$array=array(
		 //廣東快樂十分
		 '11'=>array(
				 '00' =>array('00'=>'1~8單碼')
				,'01' =>array('01'=>'1~8單碼')
				,'02' =>array('02'=>'1~8單碼')
				,'03' =>array('03'=>'1~8單碼')		
				,'04' =>array('04'=>'1~8單碼')		
				,'05' =>array('05'=>'1~8單碼')
				,'06' =>array('06'=>'1~8單碼')
				,'07' =>array('07'=>'1~8單碼')						
				,'29' =>array('29'=>'正碼')
				,'08' =>array('08'=>'1~8兩面')
				,'09' =>array('09'=>'1~8兩面')
				,'10' =>array('10'=>'1~8兩面')		
				,'11' =>array('11'=>'1~8兩面')						
				,'31' =>array('13'=>'總和小')
				,'13' =>array('13'=>'總和大')						
				,'12' =>array('12'=>'總和單')
				,'30' =>array('12'=>'總和雙')
				,'14' =>array('14'=>'總和尾大')
				,'32' =>array('14'=>'總和尾小')						
				,'15' =>array('15'=>'1~8中發')
				,'26' =>array('15'=>'1~8白')						
				,'16' =>array('16'=>'1~8方位')
				,'17' =>array('17'=>'1~4龍虎')
				,'18' =>array('18'=>'任選二')
				,'20' =>array('20'=>'選二聯組')
				,'21' =>array('21'=>'任選三')
				,'23' =>array('23'=>'選三前組')
				,'24' =>array('24'=>'任選四')
				,'25' =>array('25'=>'任選五')
		 )
		//重慶時時彩
		,'12'=>array(
				 '01'=>array('00'=>'單碼')
				,'02'=>array('01'=>'兩面')		
				,'03'=>array('02'=>'龍虎和-龍虎')		
				,'04'=>array('02'=>'龍虎和-和')			
				,'05'=>array('04'=>'豹子')		
				,'06'=>array('05'=>'順子')				
				,'07'=>array('06'=>'對子')			
				,'08'=>array('07'=>'半順')		
				,'09'=>array('08'=>'雜六')
		)
		//北京賽車
		,'13'=>array(
				 '00' =>array('00'=>'單碼')
				,'01' =>array('01'=>'單碼')	
				,'02' =>array('02'=>'單碼')	
				,'03' =>array('03'=>'單碼')	
				,'04' =>array('04'=>'單碼')	
				,'05' =>array('05'=>'單碼')	
				,'06' =>array('06'=>'單碼')	
				,'07' =>array('07'=>'單碼')	
				,'08' =>array('08'=>'單碼')	
				,'09' =>array('09'=>'單碼')							
				,'10' =>array('10'=>'1~10兩面')
				,'11' =>array('11'=>'1~10兩面')						
				,'12' =>array('12'=>'1~5龍虎')
				,'20' =>array('13'=>'冠亞小'	)
				,'13' =>array('13'=>'冠亞大'	)						
				,'14' =>array('14'=>'冠亞單')
				,'21' =>array('14'=>'冠亞雙')						
				,'15' =>array('15'=>'冠亞和(1)')
				,'22' =>array('15'=>'冠亞和(2)')
				,'23' =>array('15'=>'冠亞和(3)')
				,'24' =>array('15'=>'冠亞和(4)')
				,'25' =>array('15'=>'冠亞和(5)')
		)
		//幸運農場
		,'14'=>array(
				 '00' =>array('00'=>'1~8單碼')
				,'01' =>array('01'=>'1~8單碼')
				,'02' =>array('02'=>'1~8單碼')
				,'03' =>array('03'=>'1~8單碼')		
				,'04' =>array('04'=>'1~8單碼')		
				,'05' =>array('05'=>'1~8單碼')
				,'06' =>array('06'=>'1~8單碼')
				,'07' =>array('07'=>'1~8單碼')						
				,'29' =>array('29'=>'正碼')
				,'08' =>array('08'=>'1~8兩面')
				,'09' =>array('09'=>'1~8兩面')
				,'10' =>array('10'=>'1~8兩面')		
				,'11' =>array('11'=>'1~8兩面')						
				,'31' =>array('13'=>'總和小')
				,'13' =>array('13'=>'總和大')						
				,'12' =>array('12'=>'總和單')
				,'30' =>array('12'=>'總和雙')
				,'14' =>array('14'=>'總和尾大')
				,'32' =>array('14'=>'總和尾小')						
				,'15' =>array('15'=>'1~8中發')
				,'28' =>array('15'=>'1~8白')						
				,'16' =>array('16'=>'1~8方位')
				,'17' =>array('17'=>'1~4龍虎')
				,'20' =>array('20'=>'任選二')
				,'22' =>array('22'=>'選二連直')						
				,'21' =>array('21'=>'選二聯組')
				,'23' =>array('23'=>'任選三')
				,'48' =>array('48'=>'選三前組')
				,'26' =>array('26'=>'任選四')
				,'27' =>array('27'=>'任選五')
		 )	
		//快樂8
		,'15'=>array(
				 '00' =>array('00'=>'正碼')
				,'01' =>array('01'=>'總和大小-大')							
				,'08' =>array('01'=>'總和大小-小')	
				,'02' =>array('02'=>'總和單雙-單')	
				,'09' =>array('02'=>'總和單雙-雙')							
				,'03' =>array('03'=>'總和和局')	
				,'04' =>array('04'=>'總和過關')	
				,'05' =>array('05'=>'前後和-前後')	
				,'10' =>array('05'=>'前後和-和')							
				,'06' =>array('06'=>'單雙和-單雙')
				,'11' =>array('06'=>'單雙和-和')	
				,'07' =>array('07'=>'五行(金)')		
				,'12' =>array('07'=>'五行(木)')							
				,'13' =>array('07'=>'五行(水)')	
				,'14' =>array('07'=>'五行(火)')	
				,'15' =>array('07'=>'五行(土)')	
		)
	);
	return $array[$gtype][$op_srtype][$ptype];
}
//取得帳號管理的操盤紀錄
/*
	 $mm_id			=公司編號
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
		回傳:[時間,修改者帳號,修改者IP,被修改者帳號,被修改者層級,修改內容,修改前值,修改後值]	
		*取新增帳號資料	
		*取帳號修改資料
		*合併兩個陣列並且由時間新到舊排序
		*陣列轉換成中文
		回傳
*/
function get_record_account($mm_id,$dateName){
	$ret=array();
	//取新增帳號資料
	$ary=get_record_account_insert($mm_id,$dateName);
	//取帳號修改資料
	$ary_2=get_record_account_upd($mm_id,$dateName);
	//合併兩個陣列並且由時間新到舊排序
	$array=get_merge_account($ary,$ary_2);
	if(empty($array)){return $ret;}
	//陣列轉換成中文
	foreach($array as $key =>$value){
		$level_tmp=$value['account_level'];
		$level=tran_leveal_chart($level_tmp);
		$ins_or_up=$value['op_type'];
		if($ins_or_up=='I'){
			$ret[$key][0]=$value['op_point'];
			$ret[$key][1]=$value['op_account'];
			$ret[$key][2]=$value['op_ip'];
			$ret[$key][3]=$value['account'];
			$ret[$key][4]=$level;
			$ret[$key][5]='新增会员';
			$ret[$key][6]='-';
			$ret[$key][7]='-';
		}elseif($ins_or_up=='U'){
			$chg_tmp=$value['chg_item'];
			$bef_tmp=$value['value_bef'];
			$aft_tmp=$value['value_aft'];
			$chg=get_user_chinese_agent_chart($chg_tmp);
			$chang_value=get_user_chinese_play_subchart($chg_tmp,$bef_tmp,$aft_tmp);
			$bef=($chang_value[$bef_tmp]==NULL)?'':$chang_value[$bef_tmp];
			$aft=($chang_value[$aft_tmp]==NULL)?'':$chang_value[$aft_tmp];
			$ret[$key][0]=$value['op_point'];
			$ret[$key][1]=$value['op_account'];
			$ret[$key][2]=$value['op_ip'];
			$ret[$key][3]=$value['account'];
			$ret[$key][4]=$level;
			$ret[$key][5]=$chg;
			$ret[$key][6]=$bef;
			$ret[$key][7]=$aft;
		}
	}
	//取要顯示的內容
	$final=$now_pager*$pagers;
	$first=$final-15;
	$count_final=15;	
	for($i=$first;$i<=$final;$i++){
		$array[]=$ret[$i];
	}
	return $ret;
}
//帳號層級轉換表
function tran_leveal_chart($level){	
	$array=array(
		 'BM'=>'管理员'
		,'MM'=>'公司'
		,'SC'=>'分公司'
		,'CO'=>'股东'
		,'SA'=>'总代理'
		,'AG'=>'代理'
		,'MEM'=>'会员'
	);
	return $array[$level];
}
//合併帳號管理操盤紀錄
/*
			$ary	=新增帳號操盤紀錄
			ary:{
				account:被新增的帳號名稱
				op_point:新增時間
				op_account:新增這個帳號的名稱
				op_ip:新增這個帳號的IP
				account_level:被新增的帳號層級
		}
		,$ary_2	=修改帳號操盤紀錄
		 $ary_2:{
				account:被新增的帳號名稱
				op_point:新增時間
				op_account:新增這個帳號的名稱
				op_ip:新增這個帳號的IP
				account_level:被新增的帳號層級
				op_type:對這個帳號的操作(新增、修改)
				chg_item:被修改的內容
				value_bef:被修改前值
				value_aft:被修改後值
		 }
		回傳:{
				account:被新增的帳號名稱
				op_point:新增時間
				op_account:新增這個帳號的名稱
				op_ip:新增這個帳號的IP
				account_level:被新增的帳號層級
				op_type:對這個帳號的操作(新增、修改)
				chg_item:被修改的內容
				value_bef:被修改前值
				value_aft:被修改後值
		}
*/
function get_merge_account($ary,$ary_2){
	$array=array();
	if(empty($ary) && empty($ary_2)){return $array;}
	if(empty($ary)){return $ary_2;}
	$array = array_merge($ary, $ary_2);
	//氣泡排序
	$num = count($array);
  for($i=0;$i<$num;$i++){
	//從最後一個數字往上比較，如果比較小就交換
   for($j=$num-1;$j>$k;$j--){
		$aTime=strtotime($array[$j]['op_point']);
		$bTime=strtotime($array[$j-1]['op_point']);
     if($aTime > $bTime){
			list($array[$j] , $array[$j-1]) = array($array[$j-1] , $array[$j]);
     }
    }
  }
	return $array;
}
//取得帳號管理修改帳號的操盤紀錄
/*
	 $mm_id			=公司編號
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	回傳:{
		account:被新增的帳號名稱
		op_point:新增時間
		op_account:新增這個帳號的名稱
		op_ip:新增這個帳號的IP
		account_level:被新增的帳號層級
		op_type:對這個帳號的操作(新增、修改)
		chg_item:被修改的內容
		value_bef:被修改前值
		value_aft:被修改後值
	}
*/
function get_record_account_upd($mm_id,$dateName){
	global $db_s;
	$ret=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='op_point';
	$aSQL[]=',op_account';
	$aSQL[]=',op_type';
	$aSQL[]=',op_ip';
	$aSQL[]=',account';
	$aSQL[]=',account_level';
	$aSQL[]=',chg_item';
	$aSQL[]=',value_bef';
	$aSQL[]=',value_aft';
	$aSQL[]='FROM';
	$aSQL[]='log_useredit';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND mm_id=[mm_id]';
	$aSQL[]='AND op_date="[op_date]"';
	$aSQL[]='AND op_type!="[op_type]"';
	$aSQL[]='AND op_gtype IS NULl';
	$aSQL[]='AND op_ptype IS NULl';
	$aSQL[]=' ORDER BY `op_point` DESC';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[op_date]',$dateName,$sSQL);
	$sSQL=str_replace('[op_type]','I',$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r;
	}
	/*
	echo '<xmp>';
	print_r($ret);
	echo '</xmp>';
	*/
	return $ret;
}
//取得帳號管理新增帳號的操盤紀錄
/*
	 $mm_id			=公司編號
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	回傳:{
		account:被新增的帳號名稱
		op_point:新增時間
		op_account:新增這個帳號的名稱
		op_ip:新增這個帳號的IP
		account_level:被新增的帳號層級
		op_type:對這個帳號的操作(新增、修改)
	}
*/
function get_record_account_insert($mm_id,$dateName){
	global $db_s;
	$ret=array();
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='DISTINCT account';
	$aSQL[]=',op_point';
	$aSQL[]=',op_type';
	$aSQL[]=',op_account';
	$aSQL[]=',op_ip';
	$aSQL[]=',account_level';
	//$aSQL[]=',op_type';
	$aSQL[]='FROM';
	$aSQL[]='log_useredit';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND mm_id=[mm_id]';
	$aSQL[]='AND op_date="[op_date]"';
	$aSQL[]='AND op_type="[op_type]"';
	$aSQL[]='AND op_gtype IS NULl';
	$aSQL[]='AND op_ptype IS NULl';
	$aSQL[]=' ORDER BY `op_point` DESC';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[op_date]',$dateName,$sSQL);
	$sSQL=str_replace('[op_type]','I',$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret[]=$r;
	}
	return $ret;
}
//取得帳號管理筆數
/*
	 $game			=	遊戲名稱{klc、ssc}		
	,$mm_id			=	公司代號	
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	,$pagers		=	每頁筆數(15筆)
	
	回傳:總共頁數(1	or	2	or	3...)
*/
function get_record_account_count($game,$mm_id,$dateName,$pagers){
	global $db_s;
	$tmp=0;
	$ret=array();
	//抓log_useredit這張表的操作紀錄數量
	//新增會員要分開做，在操盤紀錄上面只會顯示一筆，新增會員
	//抓非新增會員的比數
	$aSQL=array();
	$aSQL[]='SELECT COUNT(sn) AS count';
	$aSQL[]='FROM `log_useredit` ';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `mm_id`=[mm_id]';
	$aSQL[]='AND `op_date`="[op_date]"';
	$aSQL[]='AND `op_type`!="[op_type]"';
	$aSQL[]='AND `op_gtype` IS NULL';
	$aSQL[]='AND `op_ptype` IS NULL';
	$aSQL[]='UNION';
	$aSQL[]='SELECT COUNT(DISTINCT account) AS count';
	$aSQL[]='FROM `log_useredit` ';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `mm_id`=[mm_id]';
	$aSQL[]='AND `op_date`="[op_date]"';
	$aSQL[]='AND `op_type`="[op_type]"';
	$aSQL[]='AND `op_gtype` IS NULL';
	$aSQL[]='AND `op_ptype` IS NULL';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[op_date]',$dateName,$sSQL);
	$sSQL=str_replace('[op_type]','I',$sSQL);
	//echo $sSQL;
	$result = $db_s->sql_query($sSQL);	
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp=$tmp+$r['count'];
	}
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;
	return $ret;
}
//取得系統設定筆數
/*
	 $game			=	遊戲名稱{klc、ssc}		
	,$mm_id			=	公司代號	
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	,$pagers		=	每頁筆數(15筆)
	
	回傳:總共頁數(1	or	2	or	3...)
	
*/
function get_record_sye_count($game,$mm_id,$form_data,$pagers){
	global $db_s;
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;
	$str=(isset($selectPlay))?"AND `op_ptype` IN('[op_ptype]')":"";
	$ret=array();
	//抓log_syssetting這張表的操作紀錄數量
	$SQL ="SELECT COUNT(`sn`) ";
	$SQL.="FROM `log_syssetting` ";
	$SQL.="WHERE `mm_id`='[mm_id]' AND `op_gtype`='[gtype]' AND `op_class`='[op_class]' ";
	$SQL.="AND `op_date`='[op_date]' [str] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[str]',$str,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$SQL=str_replace('[op_class]',$form_data['sysOPClass'],$SQL);
	//echo $SQL;
	$result = $db_s->sql_query($SQL);	
	$r=$db_s->nxt_row('ASSOC');
	$tmp=$r['COUNT(`sn`)'];
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;
	return $ret;
}
//取得系統設定開獎結果比數
/*
	$game			=	遊戲名稱{klc、ssc}		
	$date  		= 日期
	,$pagers	=	每頁筆數20
	
	回傳:總共頁數(1	or	2	or	3...)
*/
function get_record_lt_rst_count($game,$date,$now_pager,$pagers){
	global $db_s;
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$ret=array();
	$aSQL=array();
	//抓log_lottery_results這張表的操作紀錄
	$aSQL[]="SELECT";
	$aSQL[]="count(sn) AS count ";
	$aSQL[]="FROM `log_lottery_results` ";
	$aSQL[]="WHERE 1";
	$aSQL[]="AND `op_gtype`='[gtype]'";
	$aSQL[]="AND `rpt_date`='[op_date]'";
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[op_date]',$date,$sSQL);
	$result = $db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	$tmp=$r['count'];
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;
	return $ret;
}
//取得補貨設定筆數
/*
	 $game			=	遊戲名稱{klc、ssc}		
	,$mm_id			=	公司代號	
	$form_data  = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	,$pagers		=	每頁筆數(15筆)
	
	回傳:總共頁數(1	or	2	or	3...)
*/
function get_record_replenishment_count($game,$mm_id,$form_data,$pagers){
	global $db_s;
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;
	$str=(isset($selectPlay))?"AND `op_ptype` IN('[op_ptype]')":"";
	$ret=array();
	//抓log_syssetting這張表的操作紀錄數量
	$SQL ="SELECT COUNT(`sn`) ";
	$SQL.="FROM `log_replenishment` ";
	$SQL.="WHERE `account_id`='[mm_id]' AND `op_gtype`='[gtype]' ";
	$SQL.="AND `op_date`='[op_date]' [str] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[str]',$str,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$result = $db_s->sql_query($SQL);	
	$r=$db_s->nxt_row('ASSOC');
	$tmp=$r['COUNT(`sn`)'];
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;
	return $ret;

}
//系統設定-投注限制讀取
/*
	$game=遊戲名稱{klc、ssc}		
	$mm_id=公司代號	
	$form_data = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	$now_pager	=	現在頁數
	$pagers			=	一頁幾筆資料				
	*抓log_syssetting這張表的操作紀錄
	*將資料庫的資訊轉換成中文讀取資訊
	*將重複的紀錄刪除
	回傳:
	$array[項目]=>array(操作時間,操作名稱,IP,操作類別,玩法類別,變動前值,變動後值)
*/
function get_record_bettingLimit($game,$mm_id,$form_data,$now_pager,$pagers){
	global $db_s;	
	$ret=array();
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;	
	$final=$now_pager*$pagers;
	$first=$final-15;	
	$count_final=15;	
	//抓log_syssetting這張表的操作紀錄
	$SQL ="SELECT `op_date`,`op_time`,`op_username`,`op_ip`,";
	$SQL.="`op_class`,`op_item`,`chg_item`,`op_ptype`,`op_srtype`,`value_bef`,`value_aft` ";
	$SQL.="FROM `log_syssetting` ";
	$SQL.="WHERE `mm_id`='[mm_id]' AND `op_gtype`='[gtype]' AND `op_class`='[op_class]' ";
	$SQL.="AND `op_date`='[op_date]' AND `op_ptype` IN('[op_ptype]') ORDER BY op_time DESC ";
	$SQL.="LIMIT [first],[final] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$SQL=str_replace('[op_class]',$form_data['sysOPClass'],$SQL);
	$SQL=str_replace('[first]',$first,$SQL);
	$SQL=str_replace('[final]',$count_final,$SQL);
	$result = $db_s->sql_query($SQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	if(empty($tmp)){return ;}
	foreach($tmp as $key => $value){
		//將資料庫的資訊轉換成中文讀取資訊
		//變動值欄位名稱
		$tran_name=$value['chg_item'];
		$tran_tmp=get_bettingLimit_chart();
		$chinese_name=$tran_tmp[$tran_name];
		//玩法類別
		$tran_play_name=$value['op_ptype'];
		$play_tmp=get_play_chart($gtype);
		$chinese_play_name=$play_tmp[$tran_play_name];
		//操作類別名稱
		$tran_sys_config=$value['op_class'];
		$op_class=tran_sysconfig_num_data($tran_sys_config);
		//操作類別詳細名稱
		$op_srtype=$value['op_srtype'];
		$chinese_play_detail_name=get_bettingLimit_detail_chart($gtype,$op_srtype);
		$ret[$key]['0']=$value['op_time'];
		$ret[$key]['1']=$value['op_username'];
		$ret[$key]['2']=$value['op_ip'];
		$ret[$key]['3']=$op_class;
		$ret[$key]['4']=$chinese_play_name;
		$ret[$key]['5']=$chinese_play_detail_name.$chinese_name."更改前為:".$value['value_bef'];
		$ret[$key]['6']=$chinese_play_detail_name.$chinese_name."更改後為:".$value['value_aft'];
	}
	return $ret;	
}
//系統設定-自動跳水設定讀取
/*
	$game=遊戲名稱{klc、ssc}		
	$mm_id=公司代號	
	$form_data = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	$now_pager	=	現在頁數
	$pagers			=	一頁幾筆資料			
	*抓log_syssetting這張表的操作紀錄
	*將資料庫的資訊轉換成中文讀取資訊
	*將重複的紀錄刪除
	回傳:
		$array[項目]=>array(操作時間,操作名稱,IP,操作類別,玩法類別,變動前值,變動後值)
*/
function get_record_autoDiving($game,$mm_id,$form_data,$now_pager,$pagers){
	global $db_s;	
	$ret=array();
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;	
	$final=$now_pager*$pagers;
	$first=$final-15;	
	$count_final=15;	
	//抓log_syssetting這張表的操作紀錄
	$SQL ="SELECT `op_date`,`op_time`,`op_username`,`op_ip`,";
	$SQL.="`op_class`,`op_item`,`chg_item`,`op_ptype`,`op_srtype`,`value_bef`,`value_aft` ";
	$SQL.="FROM `log_syssetting` ";
	$SQL.="WHERE `mm_id`='[mm_id]'  ";
	$SQL.="AND `op_gtype`='[gtype]' ";
	$SQL.="AND `op_class`='[op_class]' ";
	$SQL.="AND `op_date`='[op_date]' ";
	$SQL.="AND (`op_ptype` IN('[op_ptype]') ";
	if($selectPlay_data=='allplay'){
		$SQL.="OR `op_ptype`=''";
	}
	$SQL.=") ORDER BY op_time DESC ";
	$SQL.="LIMIT [first],[final] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$SQL=str_replace('[op_class]',$form_data['sysOPClass'],$SQL);
	$SQL=str_replace('[first]',$first,$SQL);
	$SQL=str_replace('[final]',$count_final,$SQL);
	$result = $db_s->sql_query($SQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	if(empty($tmp)){return ;}
	foreach($tmp as $key => $value){
		//將資料庫的資訊轉換成中文讀取資訊
		//變動值欄位名稱
		$tran_name=$value['chg_item'];
		$tran_tmp=get_autoDiving_chart();
		$chinese_name=$tran_tmp[$tran_name];
		//玩法類別
		$tran_play_name=$value['op_ptype'];
		$play_tmp=get_play_chart($gtype);
		$chinese_play_name=$play_tmp[$tran_play_name];
		$chinese_play_name=($chinese_play_name==null)?'':$chinese_play_name;
		//操作類別名稱
		$tran_sys_config=$value['op_class'];
		$op_class=tran_sysconfig_num_data($tran_sys_config);
		//兩面連動轉換
		if($value['chg_item']=='2way_relate_enable'){
			$before=$value['value_bef'];
			$after=$value['value_aft'];
			$value['value_bef']=$tran_tmp[$before];
			$value['value_aft']=$tran_tmp[$after];
		}
		//只有重慶時時彩自動跳水設定跟操盤紀錄的選單不一樣，其他都一樣就沒有寫對照表了，只有用if判斷式區別他的詳細內容
		//重慶時時彩的srtype
		$srtype=$value['op_srtype'];
		$chinese_detail_name=($gtype=='12' && $tran_play_name=='02' && $srtype=='03')?'龍虎':$chinese_play_name;
		$chinese_detail_name=($gtype=='12' && $tran_play_name=='02' && $srtype=='04')?'和':$chinese_detail_name;
		$ret[$key]['0']=$value['op_time'];
		$ret[$key]['1']=$value['op_username'];
		$ret[$key]['2']=$value['op_ip'];
		$ret[$key]['3']=$op_class;
		$ret[$key]['4']=$chinese_play_name;
		$ret[$key]['5']=$chinese_detail_name.$chinese_name."更改前為:".$value['value_bef'];
		$ret[$key]['6']=$chinese_detail_name.$chinese_name."更改後為:".$value['value_aft'];
	}
	return $ret;	
}
//系統設定-水位設置讀取
/*
	$game=遊戲名稱{klc、ssc}		
	$mm_id=公司代號	
	$form_data = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	$now_pager	=	現在頁數
	$pagers			=	一頁幾筆資料
	*抓log_syssetting這張表的操作紀錄
	*將資料庫的資訊轉換成中文讀取資訊
	*將重複的紀錄刪除
	回傳:
		$array[項目]=>array(操作時間,操作名稱,IP,操作類別,玩法類別,變動前值,變動後值)
*/
function get_record_water($game,$mm_id,$form_data,$now_pager,$pagers){
	global $db_s;
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;
	$ret=array();
	$final=$now_pager*$pagers;
	$first=$final-15;
	$count_final=15;
	//抓log_syssetting這張表的操作紀錄
	$SQL ="SELECT `op_date`,`op_time`,`op_username`,`op_ip`, ";
	$SQL.="`op_class`,`op_item`,`chg_item`,`op_ptype`,`op_srtype`,`value_bef`,`value_aft` ";
	$SQL.="FROM `log_syssetting` ";
	$SQL.="WHERE `mm_id`='[mm_id]' AND `op_gtype`='[gtype]' AND `op_class`='[op_class]' ";
	$SQL.="AND `op_date`='[op_date]' AND `op_ptype` IN('[op_ptype]') ORDER BY op_time DESC ";
	$SQL.="LIMIT [first],[final] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$SQL=str_replace('[op_class]',$form_data['sysOPClass'],$SQL);
	$SQL=str_replace('[first]',$first,$SQL);
	$SQL=str_replace('[final]',$count_final,$SQL);
	$result = $db_s->sql_query($SQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	if(empty($tmp)){return ;}
	foreach($tmp as $key => $value){
		//將資料庫的資訊轉換成中文讀取資訊
		//變動值欄位名稱
		$tran_name=$value['chg_item'];
		$tran_tmp=get_water_chart();
		$chinese_name=$tran_tmp[$tran_name];
		//玩法類別
		$tran_play_name=$value['op_ptype'];
		$play_tmp=get_play_chart($gtype);
		$chinese_play_name=$play_tmp[$tran_play_name];
		//操作類別名稱
		$tran_sys_config=$value['op_class'];
		$op_class=tran_sysconfig_num_data($tran_sys_config);
		//操作項目-詳細名稱,參照畫面上
		$op_srtype=$value['op_srtype'];
		$op_srtype_chinese=get_water_detail_chart($gtype,$tran_play_name,$op_srtype);
		$ret[$key]['0']=$value['op_time'];
		$ret[$key]['1']=$value['op_username'];
		$ret[$key]['2']=$value['op_ip'];
		$ret[$key]['3']=$op_class;
		$ret[$key]['4']=$chinese_play_name;
		$ret[$key]['5']=$op_srtype_chinese.$chinese_name."更改前為:".$value['value_bef'];
		$ret[$key]['6']=$op_srtype_chinese.$chinese_name."更改後為:".$value['value_aft'];
	}
	return $ret;
}
//系統設定-開獎結果
/*
	$game=遊戲名稱{klc、ssc}		
	$date =日期
	$now_pager	=	現在頁數
	$pagers			=	一頁幾筆資料
	*抓og_lottery_results這張表的操作紀錄
	*將資料庫的資訊轉換成中文讀取資訊
	回傳:
		$array[項目]=>array(操作時間,操作名稱,IP,操作類別,玩法類別,變動前值,變動後值)
*/
function get_record_lt_rst($game,$date,$now_pager,$pagers){
	global $db_s;
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$ret=array();
	$aSQL=array();
	$final=$now_pager*$pagers;
	$first=$final-20;
	$count_final=20;
	//抓log_lottery_results這張表的操作紀錄
	$aSQL[]="SELECT";
	$aSQL[]="mm_id";
	$aSQL[]=",rpt_date";
	$aSQL[]=",date_sn";
	$aSQL[]=",op_time,";
	$aSQL[]="op_account,op_level,op_ip,op_type,op_gtype,value_bef,value_aft";
	$aSQL[]="FROM `log_lottery_results` ";
	$aSQL[]="WHERE 1 AND `op_gtype`='[gtype]'";
	$aSQL[]="AND `rpt_date`='[op_date]' ORDER BY date_sn DESC ";
	$aSQL[]="LIMIT [first],[final]";
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[op_date]',$date,$sSQL);
	$sSQL=str_replace('[first]',$first,$sSQL);
	$sSQL=str_replace('[final]',$count_final,$sSQL);
	$result = $db_s->sql_query($sSQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	if(empty($tmp)){return ;}
	//這一頁有幾期就要跑幾次
	foreach($tmp as $key => $value){
		$op_type=$value['op_type'];
		//操作項目-詳細名稱,參照畫面上
		$op_type_chinese=array('L'=>'開獎','R'=>'重新開獎');
		//第一層key是迴圈跑到第幾次 
		//第二層則是當期的第幾個欄位的資料
		$ret[$key]['0']=$value['op_time'];
		$ret[$key]['1']=$value['date_sn'];
		$ret[$key]['2']=$value['op_account'];
		$ret[$key]['3']=$value['op_ip'];
		$ret[$key]['4']=$op_type_chinese[$op_type];
		$ret[$key]['5']=(is_null($value['value_bef']))?"":$value['value_bef'];
		$ret[$key]['6']=$value['value_aft'];
	}
	return $ret;
}
//系統設定-基本設至讀取	
/*
	$game=遊戲名稱{klc、ssc}		
	$mm_id=公司代號	
	$form_data = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	$now_pager	= 現在頁數
	$pagers			= 筆數 15筆
	*抓log_syssetting這張表的操作紀錄
	回傳:
		$ret[項目]=>array(操作時間,操作名稱,IP,操作類別,變動前值,變動後值)
*/
function get_record_basic($game,$mm_id,$form_data,$now_pager,$pagers){
	global $db_s;	
	$ret=array();
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$op_class=$form_data['sysOPClass'];
	$final=$now_pager*$pagers;
	$first=$final-15;	
	$count_final=15;
	$SQL ="SELECT `op_date`,`op_time`,`op_username`,`op_ip`,";
	$SQL.="`op_class`,`op_item`,`chg_item`,`value_bef`,`value_aft` ";
	$SQL.="FROM `log_syssetting` ";
	$SQL.="WHERE `mm_id`='[mm_id]' AND `op_gtype`='[gtype]' AND ";
	$SQL.="`op_class`='[op_class]' AND `op_date`='[op_date]' ORDER BY op_time DESC ";
	$SQL.="LIMIT [first],[final] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[op_class]',$op_class,$SQL);
	$SQL=str_replace('[final]',$count_final,$SQL);
	$SQL=str_replace('[first]',$first,$SQL);
	$result = $db_s->sql_query($SQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	if(empty($tmp)){return ;}	
	foreach($tmp as $key => $value){
		//變動值欄位名稱
		$tran_name=$value['chg_item'];
		$chinese_name=get_basic_chart($tran_name);
		//操作類別名稱
		$tran_sys_config=$value['op_class'];
		$op_class=tran_sysconfig_num_data($tran_sys_config);
		$ret[$key]['0']=$value['op_time'];
		$ret[$key]['1']=$value['op_username'];
		$ret[$key]['2']=$value['op_ip'];
		$ret[$key]['3']=$op_class;
		$ret[$key]['4']=$chinese_name."更改前為:".$value['value_bef'];
		$ret[$key]['5']=$chinese_name."更改後為:".$value['value_aft'];
	}
	return $ret;
}
//系統設定-補貨設定讀取
/*
   $game				=遊戲名稱{klc、ssc}
	,$mm_id				=公司代號	
	$form_data = array(
			 'sysOPClass'	=>操作類型 {基本設定、水位設置}
			,'dateName'		=>日期 {2016-06-04}
			,'selectType'	=>操盤紀錄的類型 {syssetting}
			,'selectPlay'	=>所有遊戲 {allplay}
			)
	$now_pager	= 現在頁數
	$pagers			= 筆數 15筆
*/
function get_record_replenishment($game,$mm_id,$form_data,$now_pager,$pagers){
	global $db_s;	
	$ret=array();
	$ary=tran_game_Num_data();
	$gtype=$ary[$game];
	$dateName=$form_data['dateName'];
	$selectPlay_data=$form_data['selectPlay'];
	$select=get_play_chart($gtype);
	$tran_select=array_flip($select);
	// echo '<xmp>';
	// print_r($tran_select);
	// echo '</xmp>';
	$selectPlay=($selectPlay_data=='allplay')?implode("','",$tran_select):$selectPlay_data;	
	$final=$now_pager*$pagers;
	$first=$final-15;
	$count_final=15;
	//抓log_replenishment這張表的操作紀錄
	$SQL ="SELECT `op_date`,`op_point`,`op_account`,`op_ip`,";
	$SQL.="`chg_item`,`op_ptype`,`value_bef`,`value_aft` ";
	$SQL.="FROM `log_replenishment` ";
	$SQL.="WHERE `account_id`='[mm_id]' AND `op_gtype`='[gtype]' ";
	$SQL.="AND `op_date`='[op_date]' AND `op_ptype` IN('[op_ptype]') ORDER BY op_point DESC ";
	$SQL.="LIMIT [first],[final] ";
	$SQL=str_replace('[mm_id]',$mm_id,$SQL);
	$SQL=str_replace('[gtype]',$gtype,$SQL);
	$SQL=str_replace('[op_date]',$dateName,$SQL);
	$SQL=str_replace('[op_ptype]',$selectPlay,$SQL);
	$SQL=str_replace('[first]',$first,$SQL);
	$SQL=str_replace('[final]',$count_final,$SQL);
	// echo $SQL;
	$result = $db_s->sql_query($SQL);
	//取資料,key作轉換
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[] =$r;
	}
	// echo '<xmp>';
	// print_r($tmp);
	// echo '</xmp>';
	if(empty($tmp)){return ;}
	foreach($tmp as $key => $value){
		//將資料庫的資訊轉換成中文讀取資訊
		//變動值欄位名稱
		$tran_name=$value['chg_item'];
		$tran_tmp=get_replenishment_chart();
		$chinese_name=$tran_tmp[$tran_name];
		$tran_tmp_bef=$value['value_bef'];
		$tran_tmp_aft=$value['value_aft'];
		$chinese_bef=($value['chg_item']=='replenish_enable')?$tran_tmp[$tran_tmp_bef]:$value['value_bef'];
		$chinese_aft=($value['chg_item']=='replenish_enable')?$tran_tmp[$tran_tmp_aft]:$value['value_aft'];
		//玩法類別
		$tran_play_name=$value['op_ptype'];
		$play_tmp=get_play_chart($gtype);
		$chinese_play_name=$play_tmp[$tran_play_name];
		//操作類別名稱
		$op_class='補貨設定';
		//操作類別詳細名稱
		$ret[$key]['0']=$value['op_point'];
		$ret[$key]['1']=$value['op_account'];
		$ret[$key]['2']=$value['op_ip'];
		$ret[$key]['3']=$op_class;
		$ret[$key]['4']=$chinese_play_name;
		$ret[$key]['5']=$chinese_name."更改前為:".$chinese_bef;
		$ret[$key]['6']=$chinese_name."更改後為:".$chinese_aft;
	}
	
	return $ret;		
}
//寫操作紀錄,最後要搬到func.operation_record.php裡
/*
	 $sOp_lv=操作者層級(OS/BM/MM/SC/CO/SA/AG/MEM/SUB)
	,$iOp_id=操作者編號
	,$sOp_type=操作類型(I/U/D)
	,$sAlv=被操作者層級(OS/BM/MM/SC/CO/SA/AG/MEM/SUB)
	,$iAid=被操作者編號
	,$aModify=修改的內容
	修改的內容[項目]={
		bf:修改前
		af:修改後
		gtype:遊戲編號
		ptype:玩法編號
	}
	*每個項目一條紀錄
	要找到操作者跟被操作者的編號
	*子帳號的資料跟一般帳號的資料分開
	*如果沒有gtype,ptype,就都給null
*/
function put_user_edit_op_rec($sOp_lv,$iOp_id,$sOp_type,$sAlv,$iAid,$aModify){
	global $db;
	$aUpId=get_op_all_up_id($sAlv,$iAid);
	$aValue=array();
	//資料表
	$table=($sAlv=='SUB')?'log_useredit_sub':'log_useredit';
	//欄位的值
	$aValue['account']=get_op_rec_account($sAlv,$iAid);
	$aValue['account_level']=$sAlv;
	$aValue['account_id']=$iAid;
	foreach($aUpId as $lv =>$uid){
		$aValue[$lv.'_id']=$uid;
	}
	$aValue['op_date']=date('Y-m-d');
	$aValue['op_point']=date('Y-m-d H:i:s');
	$aValue['op_type']=$sOp_type;
	$aValue['op_account']=get_op_rec_account($sOp_lv,$iOp_id);
	$aValue['op_level']=$sOp_lv;
	$aValue['op_ip']=uti_get_ip();
	//SQL
	$aCol=array(
		 'account','account_level','account_id'
		,'bm_id','mm_id','sc_id','co_id','sa_id','ag_id'
		,'op_date','op_point','op_type'
		,'op_account','op_level'
		,'op_ip','chg_item','value_bef','value_aft'
	);
	if($table == 'log_useredit'){
		$aCol[]='op_gtype';
		$aCol[]='op_ptype';
	}
	//欄位
	$aSQL=array();
	$aSQL[]='INSERT DELAYED INTO [table](`[cols]`)';
	$aSQL[]='VALUES';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[cols]',implode('`,`',$aCol),$sSQL);
	//值
	$sVAL='("[[cols]]")';
	$sVAL=str_replace('[cols]',implode(']","[',$aCol),$sVAL);
	$aVAL=array();
	foreach($aModify as $k => $v){
		$aValue['chg_item']='';
		$aValue['value_bef']='';
		$aValue['value_aft']='';
		//---
		if(!strpos($k,'.')){
			$chg_item=$k;
		}else{
			$tmp=explode('.',$k);
			$chg_item=$tmp[2];
		}
		$aValue['chg_item']=$chg_item;
		$aValue['op_gtype']=(!isset($v['gtype']))?'null':$v['gtype'];
		$aValue['op_ptype']=(!isset($v['ptype']))?'null':$v['ptype'];
		$aValue['value_bef']=($sOp_type=='U')?$v['bf']:'';
		$aValue['value_aft']=$v['af'];
		$sValue=$sVAL;
		foreach($aCol as $k => $col){
			$val=$aValue[$col];		
			if($val == 'null'){
				$sValue=str_replace('"['.$col.']"',$val,$sValue);
			}else{
				$sValue=str_replace("[$col]",$val,$sValue);
			}
		}
		$aVAL[]=$sValue;
	}
	$sOP_SQL=$sSQL.implode(',',$aVAL);
	$q=$db->sql_query($sOP_SQL);
}
//抓取某個層級某個編號的帳號
/*
	 $sLV=層級(OS/BM/MM/SC/CO/SA/AG/MEM/SUB)
	,$iID=編號
	回傳 : 帳號
	*OS就直接回傳'system'

*/
function get_op_rec_account($sLV,$iID){
	global $db_s;
	switch($sLV){
		case 'OS':
			return 'system';
			break;
		case 'MEM':
			$table='member';
			break;
		case 'SUB':
			$table='agents_sub';
			break;
		default:
			$table='agents';
	}
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='username';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND id="[id]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[id]',$iID,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	return $r['username'];
}
//抓取某個層級某個編號的整條組織線
/*
	 $sLV=層級(OS/BM/MM/SC/CO/SA/AG/MEM/SUB)
	 回傳:{
		 bm:系統管理員
		 mm:公司
		 sc:分公司
		 co:股東
		 sa:總代理
		 ag:代理
	 }
	 *OS:全部都是0
	 *SUB:找到他所屬,然後替換上
	 *MEM:所有上層
*/
function get_op_all_up_id($sLV,$iID){
	global $db_s;	
	$sLV=strtoupper($sLV);
	$ret=array(
		 'bm'=>0
		,'mm'=>0
		,'sc'=>0
		,'co'=>0
		,'sa'=>0
		,'ag'=>0
	);
	switch($sLV){
		case 'OS':
			return $ret;
			break;
		case 'MEM':
			$aSQL=array();
			$aSQL[]='SELECT';
			$aSQL[]=' bm_id AS bm';
			$aSQL[]=',mm_id AS mm';
			$aSQL[]=',sc_id AS sc';
			$aSQL[]=',co_id AS co';
			$aSQL[]=',sa_id AS sa';
			$aSQL[]=',ag_id AS ag';
			$aSQL[]=',"MEM" AS lv';
			$aSQL[]=',id AS org';
			$aSQL[]='FROM member';
			$aSQL[]='WHERE 1';
			$aSQL[]='AND id="[id]"';
			break;
		case 'SUB':
			$aSQL=array();
			$aSQL[]='SELECT';
			$aSQL[]=' a.bm_id AS bm';
			$aSQL[]=',a.mm_id AS mm';
			$aSQL[]=',a.sc_id AS sc';
			$aSQL[]=',a.co_id AS co';
			$aSQL[]=',a.sa_id AS sa';
			$aSQL[]=',master_id AS org';			
			$aSQL[]=',"0" AS ag';
			$aSQL[]=',a.account_level AS lv';
			$aSQL[]='FROM agents AS a,agents_sub AS b';
			$aSQL[]='WHERE 1';
			$aSQL[]='AND a.id=b.master_id';
			$aSQL[]='AND b.id="[id]"';
			break;
		default:
			$aSQL=array();
			$aSQL[]='SELECT';
			$aSQL[]=' a.bm_id AS bm';
			$aSQL[]=',a.mm_id AS mm';
			$aSQL[]=',a.sc_id AS sc';
			$aSQL[]=',a.co_id AS co';
			$aSQL[]=',a.sa_id AS sa';
			$aSQL[]=',"0" AS ag';
			$aSQL[]=',id AS org';			
			$aSQL[]=',a.account_level AS lv';			
			$aSQL[]='FROM agents AS a';
			$aSQL[]='WHERE 1';
			$aSQL[]='AND a.id="[id]"';
	}
	$aSQL[]='LIMIT 1';	
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[id]',$iID,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$r=$db_s->nxt_row('ASSOC');
	foreach($ret as $lv => $v){
		$ret[$lv]=$r[$lv];
	}
	if($sLV != 'MEM'){
		$lv=strtolower($r['lv']);
		$ret[$lv]=$r['org'];
	}
	return $ret;
}
//操作紀錄新增-水位設置
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$ptypes		=遊戲的清單名稱{第一球、第二球...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	
		給定目前所在的位置 {水位設置}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_water($game,$data_key,$str,$mm_id,$old_SQL,$form_post){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='water';
	$op_item='sysconfig';
	$op_username=$_UserData['username'];
	if($game=='11' && $chg_item=='odds_max' && ($str=='15'||$str=='26') ){
		insert_op_record_backwater($game,$data_key,$str,$mm_id,$old_SQL,$form_post);
		return;
		}
	if($game=='13' && $chg_item=='odds_max' && ($str=='13'||$str=='20'||$str=='14'||$str=='21') ){
		insert_op_record_backwater($game,$data_key,$str,$mm_id,$old_SQL,$form_post);
		return;
		}
	if($game=='14' && $chg_item=='odds_max' && ($str=='15'||$str=='26') ){
		insert_op_record_backwater($game,$data_key,$str,$mm_id,$old_SQL,$form_post);
		return;
		}			
	$tmp=get_record_water_chart($game);
	$ptypes=$tmp[$str];
	foreach($ptypes as $key => $value){
		$ptype=$key;
		$srtype=$value;
		operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$ptype,$srtype);
	}
}
//操作紀錄新增-水位設置-退水
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$ptypes		=遊戲的清單名稱{第一球、第二球...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	
		給定目前所在的位置 {水位設置}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_backwater($game,$data_key,$str,$mm_id,$old_SQL,$form_post){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='water';
	$op_item='sysconfig';
	$op_username=$_UserData['username'];
	$tmp=get_record_water_srtype_chart($game);

	$ptypes=$tmp[$str];
	/*
	echo $str;
	echo '<xmp>';
	print_r($ptypes);
	echo '</xmp>';
	*/
	if(empty($ptypes)){return ;}
	foreach($ptypes as $key => $value){
		$ptype=$value;
		$srtype=$key;
		operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$ptype,$srtype);
	}
}
//160623:從func.sys_conf.php搬過來
//操作紀錄新增-基本設定
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$ptypes		=遊戲的清單名稱{第一球、第二球...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	
		給定目前所在的位置 {基本設定}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_basic($game,$data_key,$ptypes,$mm_id,$old_SQL,$form_post){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='basic';
	$op_item='sysconfig';
	$ptype=null;
	$srtype=null;
	$op_username=$_UserData['username'];
	operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$ptype,$srtype);
}
//操作紀錄新增-自動跳水設定
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$ptypes		=遊戲的清單名稱{第一球、第二球...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	
		給定目前所在的位置 {自動跳水設定}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_autoDiving($game,$data_key,$str,$mm_id,$old_SQL,$form_post){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='autoDiving';
	$op_item='sysconfig';
	$op_username=$_UserData['username'];
	if($str==null){
		$srtype=null;
		operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$str,$srtype);
	}else{
		$ptypes=get_record_autoDiving_chart($game,$str);
		foreach($ptypes as $key => $value){
			operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$value,$str);
		}
	}
}
//操作紀錄新增-投注限制
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$ptypes		=遊戲的清單名稱{第一球、第二球...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	
		給定目前所在的位置 {投注限制}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_bettingLimit($game,$data_key,$str,$mm_id,$old_SQL,$form_post){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='bettingLimit';
	$op_item='sysconfig';
	$op_username=$_UserData['username'];
	$ptypes=get_record_bettingLimit_chart($game,$str);
	foreach($ptypes as $key => $value){
			operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$value,$str);
		}
}
//操作紀錄新增-自降賠率
/*
	 $game			=遊戲的玩法
	,$data_key	=操作項目{封盤時間提前、信用額度恢復模式...}
	,$mm_id			=公司分公司編號
	,$old_SQL		=資料庫原本的值
	,$form_post	=POST要更改的值
	,$ptype			=連出(1)、沒出(2)、遺漏(3)
	,$srtype		=第幾期
	
		給定目前所在的位置 {自降賠率}
		給定目前操盤畫面{系統設置}
	*新增到操作紀錄
*/
function insert_op_record_AutoTwoUpdate($game,$data_key,$mm_id,$old_SQL,$form_post,$ptype,$srtype){
	global $_UserData;
	//更改項目
	$chg_item=$data_key;
	$class='AutoTwoUpdate';
	$op_item='sysconfig';
	$op_username=$_UserData['username'];
	operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_item,$old_SQL,$form_post,$ptype,$srtype);
}
//操作紀錄新增-補貨設定
/*
	 $gtype			=	遊戲名稱(klc、ssc... 傳 11.12.13..)
	,$data_key	=	變動的資料庫欄位名稱
	,$mm_id			=	公司編號
	,$old_data	= 原本的資料庫內容值
	,$new_data	=	POST要更改的值
	,$ptypes		=	遊戲玩法
*/
function insert_op_record_Replenishment($gtype,$data_key,$mm_id,$old_data,$new_data,$ptype){
	global $_UserData;
	$ulv=$_UserData['ulv'];
	$uid=$_UserData['uid'];
	$op_level_tmp=$_UserData['is_sub'];
	//級別處理
	$all_up_id= get_op_all_up_id($ulv,$uid);
	//操作者帳號
	$op_account=$_UserData['username'];
	//操作者級別
	$op_level=($op_level_tmp=='Y')?'SUB':$ulv;
	//被修改者級別
	$account_level=$ulv;
	//被修改者帳號
	$account=get_op_rec_account($ulv,$uid);
	//被修改者編號
	$account_id=$uid;
	//玩法、項目
	$op_ptype=$ptype;
	$op_gtype=$gtype;
	//變動項目
	$chg_item=$data_key;
	//變動值
	$value_bef=$old_data;
	$value_aft=$new_data;
	operationRecord_Replenishment($account,$account_level,$account_id,$all_up_id,$op_account,$op_level,$op_gtype,$op_ptype,$chg_item,$value_bef,$value_aft);
}
//操作紀錄-水位設置-退水對照表
/*
	$game	=	 遊戲玩法
	回傳:array[POST進來的值]=>array([資料庫的srtype]=>資料庫的ptype)
*/
function get_record_water_srtype_chart($game){
	$array=array(
		 '11'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//第一球
						,'01'=>'01'//第二球
						,'02'=>'02'//第三球
						,'03'=>'03'//第四球
						,'04'=>'04'//第五球
						,'05'=>'05'//第六球
						,'06'=>'06'//第七球
						,'07'=>'07'//第八球
						)
				,'29'=>array('29'=>'29')//正碼	
				//1~8兩面		
				,'08'=>array(
						 '08'=>'08'//1~8大小
						,'09'=>'09'//1~8單雙
						,'10'=>'10'//1~8尾大尾小
						,'11'=>'11'//1~8和數單雙
						)	
				,'13'=>array(
						 '13'=>'13'//總和大小-大
						,'31'=>'13'//總和大小-小
						,'14'=>'14'//總和單雙-單
						,'32'=>'14'//總和單雙-雙
						,'12'=>'12'//總和尾大尾小-尾大
						,'30'=>'12'//總和偉大尾小-尾小
						)
				,'15'=>array(
						 '15'=>'15'//1~8中發白-中發
						,'26'=>'15'//1~8中發白-白
						)
				,'16'=>array('16'=>'16')//1~8方位	
				,'17'=>array('17'=>'17')//1~4龍虎	
				,'18'=>array('18'=>'18')//任選二
				,'20'=>array('20'=>'20')//選二聯組
				,'21'=>array('21'=>'21')//任選三
				,'23'=>array('23'=>'23')//選三前組		
				,'24'=>array('24'=>'24')//任選四
				,'25'=>array('25'=>'25')//任選五										
		)
		,'12'=>array(
			  '01'=>array('01'=>'00')//單碼
			 ,'02'=>array('02'=>'01')//兩面
			 ,'03'=>array('03'=>'02')//龍虎
			 ,'04'=>array('04'=>'02')//和
			 ,'05'=>array('05'=>'04')//豹子	
			 ,'06'=>array('06'=>'05')//順子
			 ,'07'=>array('07'=>'06')//對子
			 ,'08'=>array('08'=>'07')//半順
			 ,'09'=>array('09'=>'08')//雜六
		)	
		,'13'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//冠軍
						,'01'=>'01'//亞軍
						,'02'=>'02'//第三名
						,'02'=>'02'//第三名
						,'03'=>'03'//第四名
						,'03'=>'03'//第四名
						,'04'=>'04'//第五名
						,'04'=>'04'//第五名
						,'05'=>'05'//第六名
						,'05'=>'05'//第六名
						,'06'=>'06'//第七名
						,'06'=>'06'//第七名
						,'07'=>'07'//第八名
						,'08'=>'08'//第九名
						,'09'=>'09'//第十名
						)	
				//1~10兩面		
				,'10'=>array(
						 '10'=>'10'//1~10大小
						,'11'=>'11'//1~10單雙
						)	
				,'12'=>array('12'=>'12')//1~5龍虎		
				,'13'=>array(
						  '13'=>'13'//冠亞大小-大
						 ,'20'=>'13'//冠亞大小-小
						)		
				,'14'=>array(
						 '14'=>'14'//冠亞單雙-單
						,'21'=>'14'//冠亞單雙-雙
						)		
				,'15'=>array(
						  '15'=>'15'//冠亞和-和一
						 ,'22'=>'15'//冠亞和-和二
						 ,'23'=>'15'//冠亞和-和三
						 ,'24'=>'15'//冠亞和-和四
						 ,'25'=>'15'//冠亞和-和五
						)										
		)
		,'14'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//第一球
						,'01'=>'01'//第二球
						,'02'=>'02'//第三球
						,'03'=>'03'//第四球
						,'04'=>'04'//第五球
						,'05'=>'05'//第六球
						,'06'=>'06'//第七球
						,'07'=>'07'//第八球
						)	
				,'29'=>array('29'=>'29')//正碼	
				//1~8兩面		
				,'08'=>array(
						 '08'=>'08'//1~8大小
						,'09'=>'09'//1~8單雙
						,'10'=>'10'//1~8尾大尾小
						,'11'=>'11'//1~8和數單雙
						)	
				,'13'=>array(
						 '13'=>'13'//總和大小-大
						,'31'=>'13'//總和大小-小
						,'14'=>'14'//總和單雙-單
						,'32'=>'14'//總和單雙-雙
						,'12'=>'12'//總和尾大尾小-尾大
						,'30'=>'12'//總和偉大尾小-尾小
						)	
				//1~8中發白
				,'15'=>array(
						 '15'=>'15'//1~8中發
						,'28'=>'15'//1~8白
						)	
				,'16'=>array('16'=>'16')//1~8東南西北		
				,'17'=>array('17'=>'17')//1~4龍虎	
				,'20'=>array('20'=>'20')//任選二
				,'21'=>array('21'=>'21')//選二聯組	
				,'22'=>array('22'=>'22')//選二聯直							
				,'23'=>array('23'=>'23')//任選三	
				,'48'=>array('48'=>'48')//選三前組	
				,'26'=>array('26'=>'26')//任選四			
				,'27'=>array('27'=>'27')//任選五								
		)
		,'15'=>array(
				 '00'=>array('00'=>'00')//正碼
				,'01'=>array(
					 '01'=>'01'//總和大小-大
					,'08'=>'01'//總和大小-小
					)
				,'02'=>array(
					 '02'=>'02'//總和單雙-單
					,'09'=>'02'//總和單雙-雙
					)			
				,'03'=>array('03'=>'03')//總和和局
				,'04'=>array('04'=>'04')//總和過關
				,'05'=>array(
					 '05'=>'05'//前後和-前後
					,'10'=>'05'//前後和-和
					)			
				,'06'=>array(
					 '06'=>'06'//單雙和-單雙
					,'11'=>'06'//單雙和-和	
					)			
				,'07'=>array(
					 '07'=>'07'//五行-金
					,'12'=>'07'//五行-木
					,'13'=>'07'//五行-水
					,'14'=>'07'//五行-火
					,'15'=>'07'//五行-土
					)		
		)		
	);	
	return $array[$game];
}
//操作紀錄-水位設置-賠率對照表
/*
	$game	=	 遊戲玩法
	回傳:array[POST進來的值]=>array([資料庫的ptype]=>資料庫的srtype)
*/
function get_record_water_chart($game){
	//操作紀錄遊戲玩法代號對照表
	$ary=array(
		 '11'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//第一球
						,'01'=>'01'//第二球
						,'02'=>'02'//第三球
						,'03'=>'03'//第四球
						,'04'=>'04'//第五球
						,'05'=>'05'//第六球
						,'06'=>'06'//第七球
						,'07'=>'07'//第八球
						)
				//正碼	
				,'29'=>array(
						'29'=>'29'
						)
				//1~8兩面		
				,'08'=>array(
						 '08'=>'08'//1~8大小
						,'09'=>'09'//1~8單雙
						,'10'=>'10'//1~8尾大尾小
						,'11'=>'11'//1~8和數單雙
						)	
				,'13'=>array(
						 '13'=>'13'//總和大小-大
						)
				,'14'=>array(
						 '14'=>'14'//總和單雙-單
						)
				,'12'=>array(
						 '12'=>'12'//總和尾大尾小-尾大
						)						
				//1~8中發白
				,'15'=>array(
						 '15'=>'15'//1~8中發
						)
				//1~8方位		
				,'16'=>array(
						 '16'=>'16'
						)
				//1~4龍虎		
				,'17'=>array(
						 '17'=>'17'
						)
				//任選二
				,'18'=>array(
						 '18'=>'18'
						)
				//選二聯組
				,'20'=>array(
						 '20'=>'20'
						)	
				//任選三
				,'21'=>array(
						 '21'=>'21'
						)	
				//選三前組	
				,'23'=>array(
						 '23'=>'23'
						)	
				//任選四
				,'24'=>array(
						 '24'=>'24'
						)	
				//任選五		
				,'25'=>array(
						 '25'=>'25'
						)		
				//1~8中發白-白
				,'26'=>array(
						'15'=>'26'
						)	
				//總和雙
				,'30'=>array(
						'12'=>'30'
						)
				//總和小
				,'31'=>array(
						'13'=>'31'
						)
				//總和尾小
				,'32'=>array(
						'14'=>'32'
						)					
		)
		,'12'=>array(
			  '01'=>array(
					'00'=>'01'//單碼
					)
			 ,'02'=>array(
					'01'=>'02'//兩面
					)		
			 ,'03'=>array(
					'02'=>'03'//龍虎
					)
			 ,'04'=>array(
					'02'=>'04'//和
					)
			 ,'05'=>array(
					'04'=>'05'//豹子
					)		
			 ,'06'=>array(
					'05'=>'06'//順子
					)
			 ,'07'=>array(
					'06'=>'07'//對子
					)	
			 ,'08'=>array(
					'07'=>'08'//半順
					)			
			 ,'09'=>array(
					'08'=>'09'//雜六
					)	
		)				
		,'13'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//冠軍
						,'01'=>'01'//亞軍
						,'02'=>'02'//第三名
						,'02'=>'02'//第三名
						,'03'=>'03'//第四名
						,'03'=>'03'//第四名
						,'04'=>'04'//第五名
						,'04'=>'04'//第五名
						,'05'=>'05'//第六名
						,'05'=>'05'//第六名
						,'06'=>'06'//第七名
						,'06'=>'06'//第七名
						,'07'=>'07'//第八名
						,'08'=>'08'//第九名
						,'09'=>'09'//第十名
						)	
				//1~10兩面		
				,'10'=>array(
						 '10'=>'10'//1~10大小
						,'11'=>'11'//1~10單雙
						)	
				//1~5龍虎		
				,'12'=>array(
						 '12'=>'12'
						)		
				//冠亞大小		
				,'13'=>array(//大
						 '13'=>'13'
						)		
				//冠亞單雙		
				,'14'=>array(//單
						 '14'=>'14'
						)	
				//冠亞和		
				,'15'=>array(//和一
						 '15'=>'15'
						)		
				//冠亞大小		
				,'20'=>array(//小
						 '13'=>'20'
						)						
				//冠亞單雙		
				,'21'=>array(//雙
						 '14'=>'21'
						)	
				//冠亞和		
				,'22'=>array(//和二
						 '15'=>'22'
						)
				//冠亞和		
				,'23'=>array(//和三
						 '15'=>'23'
						)
				//冠亞和		
				,'24'=>array(//和四
						 '15'=>'24'
						)
				//冠亞和		
				,'25'=>array(//和五
						 '15'=>'25'
						)						
		)
		,'14'=>array(
				//1~8單碼
				'00'=>array(
						 '00'=>'00'//第一球
						,'01'=>'01'//第二球
						,'02'=>'02'//第三球
						,'03'=>'03'//第四球
						,'04'=>'04'//第五球
						,'05'=>'05'//第六球
						,'06'=>'06'//第七球
						,'07'=>'07'//第八球
						)	
				//正碼	
				,'29'=>array(
						'29'=>'29'
						)
				//1~8兩面		
				,'08'=>array(
						 '08'=>'08'//1~8大小
						,'09'=>'09'//1~8單雙
						,'10'=>'10'//1~8尾大尾小
						,'11'=>'11'//1~8和數單雙
						)	
				//總和大小		
				,'13'=>array(
						 '13'=>'13'//總和大小-大
						)
				//總和大小
				,'31'=>array(
						 '13'=>'31'//總和大小-小	
						 )
				//總和單雙
				,'14'=>array(
						 '14'=>'14'//總和單雙-單	
						 )	
				//總和單雙
				,'32'=>array(
						 '14'=>'32'//總和單雙-雙	
						 )						 
				//總和尾大尾小
				,'12'=>array(
						 '12'=>'12'//總和尾大尾小-尾大	
						 )	
				//總和尾大尾小
				,'30'=>array(
						 '12'=>'30'//總和尾大尾小-尾小
						 )							 
				//1~8中發白
				,'15'=>array(
						 '15'=>'15'//1~8中發
						)	
				//1~8中發白
				,'28'=>array(
						 '15'=>'28'//1~8白
						)							
				//1~8東南西北		
				,'16'=>array(
						 '16'=>'16'
						)	
				//1~4龍虎		
				,'17'=>array(
						 '17'=>'17'
						)	
				//任選二
				,'20'=>array(
						 '20'=>'20'
						)
				//選二聯組
				,'21'=>array(
						 '21'=>'21'
						)	
				//選二聯直
				,'22'=>array(
						 '22'=>'22'
						)							
				//任選三
				,'23'=>array(
						 '23'=>'23'
						)	
				//選三前組	
				,'48'=>array(
						 '48'=>'48'
						)	
				//任選四
				,'26'=>array(
						 '26'=>'26'
						)	
				//任選五		
				,'27'=>array(
						 '27'=>'27'
						)	
		)				
		,'15'=>array(
				 '00'=>array(
					'00'=>'00'//正碼
					)
				,'01'=>array(
					'01'=>'01'//總和大小-大
					)
				,'08'=>array(
					'01'=>'08'//總和大小-小
					)		
				,'02'=>array(
					'02'=>'02'//總和單雙-單
					)	
				,'09'=>array(
					'02'=>'09'//總和單雙-雙
					)			
				,'03'=>array(
					'03'=>'03'//總和和局
					)
				,'04'=>array(
					'04'=>'04'//總和過關
					)
				,'05'=>array(
					'05'=>'05'//前後和-前後
					)		
				,'10'=>array(
					'05'=>'10'//前後和-和
					)			
				,'06'=>array(
					'06'=>'06'//單雙和-單雙
					)			
				,'11'=>array(
					'06'=>'11'//單雙和-和
					)
				,'07'=>array(
					'07'=>'07'//五行-金
					)		
				,'12'=>array(
					'07'=>'12'//五行-木
					)			
				,'13'=>array(
					'07'=>'13'//五行-水
					)		
				,'14'=>array(
					'07'=>'14'//五行-火
					)		
				,'15'=>array(
					'07'=>'15'//五行-土
					)							
		)
	);	
	return $ary[$game];
}
//操作紀錄-自動跳水設定對照表
/*
	 $game			=	遊戲代號(11,12,13)
	,$water_key	=	水位設置前台編號
	
	回傳:array[項目]=>(操作紀錄編號)
*/
function get_record_autoDiving_chart($game,$water_key){
	$array=array(
		'11'=>array(
			//1~8球
			 '00'=>array('00')
			,'01'=>array('01')
			,'02'=>array('02')
			,'03'=>array('03')
			,'04'=>array('04')
			,'05'=>array('05')
			,'06'=>array('06')
			,'07'=>array('07')
			//1~8單雙
			,'08'=>array('08')
			//1~8大小
			,'09'=>array('09')
			//1~8尾大尾小
			,'10'=>array('10')
			//1~8合數單雙
			,'11'=>array('11')
			//總和單雙		
			,'12'=>array('12')
			//總和大小		
			,'13'=>array('13')
			//總和尾大尾小
			,'14'=>array('14')
			//1~8中發白
			,'15'=>array('15')
			//1~8方位
			,'16'=>array('16')
			//1~8龍虎
			,'17'=>array('17')
			//任選二
			,'18'=>array('18')
			//選二聯組
			,'20'=>array('20')
			//任選三
			,'21'=>array('21')
			//選三前組
			,'23'=>array('23')
			//任選四
			,'24'=>array('24')
			//任選五
			,'25'=>array('25')
			//正碼
			,'29'=>array('29')		
			)
	,'12'=>array(
			 '01'=>array('00')//單碼
			,'02'=>array('01')//兩面
			,'03'=>array('02')//龍虎
			,'04'=>array('02')//和
			,'05'=>array('04')//豹子
			,'06'=>array('05')//順子
			,'07'=>array('06')//對子
			,'08'=>array('07')//半順
			,'09'=>array('08')//雜六
	)	
	,'13'=>array(
		 '00' => array('00')//冠軍
		,'01' => array('01')//亞軍
		,'02' => array('02')//第三名
		,'03' => array('03')//第四名
		,'04' => array('04')//第五名
		,'05' => array('05')//第六名
		,'06' => array('06')//第七名
		,'07' => array('07')//第八名
		,'08' => array('08')//第九名			
		,'09' => array('09')//第十名			
		,'10' => array('10')//1~10大小			
		,'11' => array('11')//1~10單雙		
		,'12' => array('12')//1~5龍虎
		,'13' => array('13')//冠亞大小
		,'14' => array('14')//冠亞單雙
		,'15' => array('15')//冠亞和		
	)	
	,'14'=>array(
		 '00'=>array('00')//第一球
		,'01'=>array('01')//第二球
		,'02'=>array('02')//第三球
		,'03'=>array('03')//第四球
		,'04'=>array('04')//第五球
		,'05'=>array('05')//第六球
		,'06'=>array('06')//第七球
		,'07'=>array('07')//第八球
		,'29'=>array('29')//正碼
		,'08'=>array('08')//1~8單雙
		,'09'=>array('09')//1~8大小
		,'10'=>array('10')//1~8尾大尾小
		,'11'=>array('11')//~18~合數單雙
		,'12'=>array('12')//總和單雙
		,'13'=>array('13')//總和大小
		,'14'=>array('14')//總和尾大尾小
		,'15'=>array('15')//1~8中發白
		,'16'=>array('16')//1~8東南西北
		,'17'=>array('17')//1~8龍虎
		,'20'=>array('20')//任選二
		,'22'=>array('22')//任選二直
		,'21'=>array('21')//選二聯組
		,'23'=>array('23')//任選三
		,'30'=>array('30')//選三前組
		,'26'=>array('26')//任選四
		,'27'=>array('27')//任選五	
	)	
	,'15'=>array(
		 '00' => array('00')//正碼
		,'01' => array('01')//總和大小
		,'02' => array('02')//總和單雙
		,'03' => array('03')//總和和局
		,'04' => array('04')//總和過關
		,'05' => array('05')//前後合
		,'06' => array('06')//單雙和
		,'07' => array('07')//五行	
	)
	);
	return $array[$game][$water_key];
}
//操作紀錄-投注限制對照表
/*
	 $game			=	遊戲代號(11,12,13)
	,$water_key	=	水位設置前台編號
	
	回傳:array[項目]=>(操作紀錄編號)
*/
function get_record_bettingLimit_chart($game,$water_key){
	$array=array(
		'11'=>array(
			//1~8球
			 '00'=>array('00','01','02','03','04','05','06','07')
			//1~8兩面
			,'08'=>array('08','09','10','11')
			//總和兩面		
			,'12'=>array('12','13','14')
			//1~8中發白
			,'15'=>array('15')
			//1~8方位
			,'16'=>array('16')
			//1~8龍虎
			,'17'=>array('17')
			//任選二
			,'18'=>array('18')
			//選二聯組
			,'20'=>array('20')
			//任選三
			,'21'=>array('21')
			//選三前組
			,'23'=>array('23')
			//任選四
			,'24'=>array('24')
			//任選五
			,'25'=>array('25')
			//正碼
			,'29'=>array('29')		
			)
	,'12'=>array(
			 '01'=>array('00')//單碼
			,'02'=>array('01')//兩面
			,'03'=>array('02')//龍虎
			,'04'=>array('02')//和
			,'05'=>array('04')//豹子
			,'06'=>array('05')//順子
			,'07'=>array('06')//對子
			,'08'=>array('07')//半順
			,'09'=>array('08')//雜六
	)	
	,'13'=>array(
		 '00' => array('00','01','02','03','04','05','06','07','08','09')//1~10名		
		,'10' => array('10','11')//1~10兩面	
		,'12' => array('12')//1~10兩面	
		,'13' => array('13')//冠亞大小
		,'14' => array('14')//冠亞單雙
		,'15' => array('15')//冠亞和		
	)	
	,'14'=>array(
		 '00'=>array('00','01','02','03','04','05','06','07')//1~8單碼
		,'29'=>array('29')//正碼
		,'08'=>array('08','09','10','11')//1~8兩面
		,'12'=>array('12','13','14')//總和兩面
		,'15'=>array('15')//1~8中發白
		,'16'=>array('16')//1~8東南西北
		,'17'=>array('17')//1~8龍虎
		,'20'=>array('20')//任選二
		,'22'=>array('22')//任選二直
		,'21'=>array('21')//選二聯組
		,'23'=>array('23')//任選三
		,'30'=>array('30')//選三前組
		,'26'=>array('26')//任選四
		,'27'=>array('27')//任選五	
	)	
	,'15'=>array(
		 '00' => array('00')//正碼
		,'01' => array('01')//總和大小
		,'02' => array('02')//總和單雙
		,'03' => array('03')//總和和局
		,'04' => array('04')//總和過關
		,'05' => array('05')//前後合
		,'06' => array('06')//單雙和
		,'07' => array('07')//五行	
	)
	);
	return $array[$game][$water_key];
}
//操盤紀錄-系統設定
/*
	$op_username=	公司帳號
	$mm_id			=	公司分公司編號
	item				=	操作項目{封盤時間提前、信用額度恢復模式...}
	$class			=	什麼項目{基本、退水、自降賠率...}
	$game 			=	遊戲的玩法
	$op_item 		=	操作項目{系統設置}
	$chg_type 	=	操作項目中文名稱{封盤時間提前、信用額度恢復模式...}
	$ptype			=	遊戲項目的更改的名稱{A盤退水...}
	$old_SQL		=	資料庫原本的值
	$form_post	=	POST要更改的值
	$op_srtype	=	變動項目的詳細名稱-參照畫面上(總和大、總和小)
	
	*取得目前IP 
	*取得目前日期	
	*取得目前時間

*/
function operationRecord_sysconfig($op_username,$mm_id,$class,$game,$op_item,$chg_type,$old_SQL,$form_post,$ptype,$op_srtype){
	global $db;
	//取得目前IP
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
  {$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	$ip=$_SERVER['REMOTE_ADDR'];}
	//取得目前日期
	$op_date=date("Y-m-d");
	//取得目前時間
	$op_time=date("Y-m-d H:i:s");
	$op_ip=$ip;
	$op_class=$class;
	$op_gtype=$game;
	$op_ptype=$ptype;
	$value_bef=$old_SQL;
	$value_aft=$form_post;
	//執行mySQL語法
	$SQL ="INSERT DELAYED INTO `log_syssetting` (`mm_id`,`op_date`,`op_time`, `op_username`, `op_ip`, `op_class`,";
	$SQL.="`op_item`, `chg_item`,`op_gtype`, `op_ptype`,`op_srtype`, `value_bef`, `value_aft`) VALUES";
	$SQL.="('[mm_id]','[op_date]','[op_time]', '[op_username]', '[op_ip]', '[op_class]',";	
	$SQL.="'[op_item]','[chg_item]','[op_gtype]', '[op_ptype]','[op_srtype]', '[value_bef]', '[value_aft]')";	
	$strSQL=$SQL;
	$strSQL=str_replace('[mm_id]',$mm_id,$strSQL);
	$strSQL=str_replace('[op_date]',$op_date,$strSQL);
	$strSQL=str_replace('[op_time]',$op_time,$strSQL);
	$strSQL=str_replace('[op_username]',$op_username,$strSQL);
	$strSQL=str_replace('[op_ip]',$op_ip,$strSQL);
	$strSQL=str_replace('[op_class]',$op_class,$strSQL);
	$strSQL=str_replace('[op_item]',$op_item,$strSQL);
	$strSQL=str_replace('[chg_item]',$chg_type,$strSQL);
	$strSQL=str_replace('[op_gtype]',$op_gtype,$strSQL);
	$strSQL=str_replace('[op_ptype]',$op_ptype,$strSQL);
	$strSQL=str_replace('[op_srtype]',$op_srtype,$strSQL);
	$strSQL=str_replace('[value_bef]',$value_bef,$strSQL);
	$strSQL=str_replace('[value_aft]',$value_aft,$strSQL);
	$q=$db->sql_query($strSQL);
}
//操盤紀錄-補貨設定SQL
/*
		 $account					=	被修改者帳號
		,$account_level		= 被修改者級別
		,$account_id			=	被修改者編號
		,$all_up_id:{
			 bm:所屬程式層編號
			,mm:所屬管理層編號
			,sc:所屬分公司層編號
			,co:所屬股東編號
			,sa:所屬代理層編號
			,ag:所屬總代理層編號
		}		
		,$op_account			=	修改者帳號
		,$op_level				=	修改者級別
		,$op_gtype				=	遊戲類別
		,$op_ptype				=	遊戲玩法
		,$chg_item				=	修改項目
		,$value_bef				=	修改前值
		,$value_aft				=	修改後值
		
		*取得今日日期
		*取得現在時間
		*取得IP
		*新增LOG
*/
function operationRecord_Replenishment($account,$account_level,$account_id,$all_up_id,$op_account,$op_level,$op_gtype,$op_ptype,$chg_item,$value_bef,$value_aft){
	global $db;
	//取得目前IP
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
  {$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	$ip=$_SERVER['REMOTE_ADDR'];}
	//取得目前日期
	$op_date=date("Y-m-d");
	//取得目前時間
	$op_point=date("Y-m-d H:i:s");
	$op_ip=$ip;
	//取得現在期數
	$op_date_sn=get_draws_to_record($op_gtype,$op_date);
	//執行mySQL語法
	$SQL[]="INSERT DELAYED INTO";
	$SQL[]="`log_replenishment`";
	$SQL[]="(";
	$SQL[]=" `account`";
	$SQL[]=",`account_level`";
	$SQL[]=",`account_id`";
	$SQL[]=",`bm_id`";
	$SQL[]=",`mm_id`";
	$SQL[]=",`sc_id`";
	$SQL[]=",`co_id`";
	$SQL[]=",`sa_id`";
	$SQL[]=",`ag_id`";
	$SQL[]=",`op_date`";
	$SQL[]=",`op_date_sn`";
	$SQL[]=",`op_point`";
	$SQL[]=",`op_account`";
	$SQL[]=",`op_level`";
	$SQL[]=",`op_ip`";
	$SQL[]=",`op_gtype`";
	$SQL[]=",`op_ptype`";
	$SQL[]=",`chg_item`";
	$SQL[]=",`value_bef`";
	$SQL[]=",`value_aft`";
	$SQL[]=")";
	$SQL[]="VALUES";
	$SQL[]="(";
	$SQL[]=" '[account]'";
	$SQL[]=",'[account_level]'";
	$SQL[]=",'[account_id]'";
	$SQL[]=",'[bm_id]'";
	$SQL[]=",'[mm_id]'";
	$SQL[]=",'[sc_id]'";
	$SQL[]=",'[co_id]'";
	$SQL[]=",'[sa_id]'";
	$SQL[]=",'[ag_id]'";
	$SQL[]=",'[op_date]'";
	$SQL[]=",'[op_date_sn]'";
	$SQL[]=",'[op_point]'";
	$SQL[]=",'[op_account]'";
	$SQL[]=",'[op_level]'";
	$SQL[]=",'[op_ip]'";
	$SQL[]=",'[op_gtype]'";
	$SQL[]=",'[op_ptype]'";
	$SQL[]=",'[chg_item]'";
	$SQL[]=",'[value_bef]'";
	$SQL[]=",'[value_aft]'";
	$SQL[]=")";
	$strSQL=implode("\n",$SQL);
	$strSQL=str_replace('[account]',$account,$strSQL);
	$strSQL=str_replace('[account_level]',$account_level,$strSQL);
	$strSQL=str_replace('[account_id]',$account_id,$strSQL);
	$strSQL=str_replace('[bm_id]',$all_up_id['bm'],$strSQL);
	$strSQL=str_replace('[mm_id]',$all_up_id['mm'],$strSQL);
	$strSQL=str_replace('[sc_id]',$all_up_id['sc'],$strSQL);
	$strSQL=str_replace('[co_id]',$all_up_id['co'],$strSQL);
	$strSQL=str_replace('[sa_id]',$all_up_id['sa'],$strSQL);
	$strSQL=str_replace('[ag_id]',$all_up_id['ag'],$strSQL);
	$strSQL=str_replace('[op_date]',$op_date,$strSQL);
	$strSQL=str_replace('[op_date_sn]',$op_date_sn,$strSQL);
	$strSQL=str_replace('[op_point]',$op_point,$strSQL);
	$strSQL=str_replace('[op_account]',$op_account,$strSQL);
	$strSQL=str_replace('[op_level]',$op_level,$strSQL);
	$strSQL=str_replace('[op_ip]',$op_ip,$strSQL);
	$strSQL=str_replace('[op_gtype]',$op_gtype,$strSQL);
	$strSQL=str_replace('[op_ptype]',$op_ptype,$strSQL);
	$strSQL=str_replace('[chg_item]',$chg_item,$strSQL);
	$strSQL=str_replace('[value_bef]',$value_bef,$strSQL);
	$strSQL=str_replace('[value_aft]',$value_aft,$strSQL);
	//echo $strSQL;
	$q=$db->sql_query($strSQL);	
}

//取得資料庫當日期數sn
/*
	 $game				=	遊戲玩法
	,$dateName		=	選擇的日期
	,$selectTimes	=	選擇的期數	
	
	回傳:[期數,期數,期數]
*/
function get_record_draws_backstage_num($game,$dateName,$selectTimes){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='date_sn';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	if($selectTimes!='all'){
		$aSQL[]='AND draws_num="[draws_num]"';
	}
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[game]',$game,$sSQL);
	$sSQL=str_replace('[rpt_date]',$dateName,$sSQL);
	$sSQL=str_replace('[draws_num]',$selectTimes,$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret[]=$r[0];
		//紀錄最後一個
		$last_r=$r[0];
	}
	$ret[]=$last_r+1;
	return $ret;
}
//取得賠率變化總筆數
/*
	 $game				=	遊戲玩法
	,$dateName		=	選擇的日期
	,$selectPlay	=	玩法類別
	,$selectTimes	=	選擇的期數
	,$pagers			=	每頁筆數(15)
	,$mm_id				=	公司編號
	
	回傳:總頁數
	
	*遊戲轉換
	*取得當日資料庫期數
	*取得資料庫欄位
	*取得筆數
	*轉換前台總頁數
*/
function get_odds_change_count($game,$dateName,$selectPlay,$selectTimes,$pagers,$mm_id){
	global $db_s;
	$aDate_sn=array();
	$ptypes=array();
	$ptypes_tmp=array();
	$ptype_tmp=array();
	$aptype=array();
	//遊戲轉換
	$game_tran=tran_game_Num_data();
	$gtype=$game_tran[$game];
	//取得當日資料庫期數
	$selectTimes=(!isset($selectTimes))?'all':$selectTimes;
	$aDate_sn=get_record_draws_backstage_num($game,$dateName,$selectTimes);
	//取得資料庫欄位
	$select=get_play_chart($gtype);
	foreach($select as $key =>$value){
		$ptype_tmp[]=$key;
	}	 
	$pytpe_tran=trn_post_2_ptype__Autooddsupdate($gtype);
	if($selectPlay!='allplay'){
		if($game=='ssc'){
			//要轉成資料庫用的必須要+1
			$play_tmp=substr($selectPlay,-1)+1;
			$selectPlay='0'.$play_tmp;
		}else{
			$aptype=$pytpe_tran[$selectPlay];
		}
	}else{
		foreach($ptype_tmp as $ptype_key =>$ptype_value){
			$ptypes_tmp[]=$pytpe_tran[$ptype_value];
		}
		foreach($ptypes_tmp as $aKey =>$aPtypes){
			if(empty($aPtypes)){continue;}
			foreach($aPtypes as $bKey =>$bPtype){
				if($game=='ssc'){
					//要轉成資料庫用的必須要+1
					$play_tmp=substr($bPtype,-1)+1;
					$selectPlay='0'.$play_tmp;
					$aptype[]=$selectPlay;
				}				
				$aptype[]=$bPtype;
			}
		}	 
	}	
	//取得筆數
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='COUNT(sn) AS count';
	$aSQL[]='FROM `log_odds_change`';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `date_sn`IN("[rpt_date]")';
	$aSQL[]='AND `op_type`!="[op_type]"';
	$aSQL[]='AND `op_gtype`=[op_gtype]';
	$aSQL[]='AND `rpt_date`="[dateName]"';
	$aSQL[]='AND `mm_id`="[mm_id]"';
	$aSQL[]='AND `op_ptype`IN("[op_ptype]")';
	$aSQL[]='AND `odds_set`!="[odds_set]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_date]',implode('","',$aDate_sn),$sSQL);
	$sSQL=str_replace('[op_type]','MB',$sSQL);
	$sSQL=str_replace('[op_gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[dateName]',$dateName,$sSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[odds_set]','N',$sSQL);	
	$sSQL=str_replace('[op_ptype]',implode('","',$aptype),$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
	$tmp=$r['count'];
	}
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;	
	return $ret;
}
//取得操盤紀錄-賠率變化
/*
	 $game				=	遊戲玩法
	,$dateName		=	選擇的日期
	,$selectPlay	=	選擇的玩法類別
	,$selectTimes	=	選擇的期數
	,$pagers			=	每頁幾筆(15)
	,$now_pager		=	目前在第幾頁
	,$mm_id				=	公司編號
	回傳:[
				 操作時間
				,操作期數
				,操作帳號
				,操作ip
				,玩法
				,所屬盤口
				,變動前值
				,變動後值
				,變動差異值
				]	 
	
	*遊戲轉換
	*取得當日資料庫期數
	*取得資料庫欄位
	*要查的筆數
	*取賠率變化紀錄
	*取得前台編號
	*判斷是否為系統自降賠率、自動跳水
	*取得前台編號的中文名稱
	*取得變動項目的中文名稱
	*回傳
*/
function get_odds_change_record($game,$dateName,$selectPlay,$selectTimes,$pagers,$now_pager,$mm_id){
	global $db_s;
	$aDate_sn=array();
	$ptypes=array();
	$ptypes_tmp=array();
	$ptype_tmp=array();
	$aptype=array();
	//遊戲轉換
	$game_tran=tran_game_Num_data();
	$gtype=$game_tran[$game];
	//取得當日資料庫期數
	$selectTimes=(!isset($selectTimes))?'all':$selectTimes;
	$aDate_sn=get_record_draws_backstage_num($game,$dateName,$selectTimes);
	//取得資料庫欄位
	$select=get_play_chart($gtype);
	foreach($select as $key =>$value){
		$ptype_tmp[]=$key;
	}	 
	$pytpe_tran=trn_post_2_ptype__Autooddsupdate($gtype);
	if($selectPlay!='allplay'){
		if($game=='ssc'){
			//要轉成資料庫用的必須要+1
			$play_tmp=substr($selectPlay,-1)+1;
			$selectPlay='0'.$play_tmp;
		}else{
			$aptype=$pytpe_tran[$selectPlay];
		}
	}else{
		foreach($ptype_tmp as $ptype_key =>$ptype_value){
			$ptypes_tmp[]=$pytpe_tran[$ptype_value];
		}
		foreach($ptypes_tmp as $aKey =>$aPtypes){
			if(empty($aPtypes)){continue;}
			foreach($aPtypes as $bKey =>$bPtype){
				if($game=='ssc'){
					//要轉成資料庫用的必須要+1
					$play_tmp=substr($bPtype,-1)+1;
					$selectPlay='0'.$play_tmp;
					$aptype[]=$selectPlay;
				}				
				$aptype[]=$bPtype;
			}
		}	 
	}	
	//要查的筆數
	$final=$now_pager*$pagers;
	$first=$final-15;
	$count_final=15;	
	//取得停押紀錄
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' op_point';
	$aSQL[]=',date_sn';
	$aSQL[]=',op_account';
	$aSQL[]=',op_type';
	$aSQL[]=',op_ip';
	$aSQL[]=',op_ptype';
	$aSQL[]=',chg_item';
	$aSQL[]=',odds_set';
	$aSQL[]=',value_bef';
	$aSQL[]=',value_aft';
	$aSQL[]='FROM `log_odds_change`';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `date_sn`IN("[rpt_date]")';
	$aSQL[]='AND `op_type`!="[op_type]"';
	$aSQL[]='AND `op_gtype`=[op_gtype]';
	$aSQL[]='AND `rpt_date`="[dateName]"';
	$aSQL[]='AND `mm_id`="[mm_id]"';
	$aSQL[]='AND `op_ptype`IN("[op_ptype]")';
	$aSQL[]='AND `odds_set`!="[odds_set]"';
	$aSQL[]='ORDER BY `op_point` DESC LIMIT [first],15';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_date]',implode('","',$aDate_sn),$sSQL);
	$sSQL=str_replace('[op_type]','MB',$sSQL);
	$sSQL=str_replace('[op_gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[first]',$first,$sSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[dateName]',$dateName,$sSQL);
	$sSQL=str_replace('[odds_set]','N',$sSQL);
	$sSQL=str_replace('[op_ptype]',implode('","',$aptype),$sSQL);
	//echo $sSQL;
	$result=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
	$ary[]=$r;
	}
	if(empty($ary)){return $array=array();}
	foreach($ary as $key=> $value){
	$date_sn_tmp=$value['date_sn'];
	$op_point=$value['op_point'];
	$account=$value['op_account'];
	$ip=$value['op_ip'];
	$op_ptype=$value['op_ptype'];
	$chg_item=$value['chg_item'];
	$odds_set=$value['odds_set'];
	$bef_tmp=$value['value_bef'];
	$aft_tmp=$value['value_aft'];
	$op_type=$value['op_type'];
	
	//取得期數
	$date_sn=get_record_draws_reception_num($game,$dateName,$date_sn_tmp);
	//取得前台編號
	$chg_num=stop_record_chart($gtype,$op_ptype);
	if($game=='ssc'){
		$ptype_tmps=substr($chg_num,-1);
		$chg_num=($ptype_tmps+1);
		$ptype_tmpa=$ptype_tmps-1;
		$ptype_tmp="0".$ptype_tmpa;
		//echo "[0$chg_num]";
	}else{
		$ptype_tmp=$chg_num;
	}
	//判斷是否為系統自降賠率、自動跳水
	if($account=='sys'){
		if($op_type=='AR'){
			$account='系統自降賠率';
		}elseif($op_type=='AJ'){
			$account='系統自動跳水';
		}
	}
	//取得前台編號的中文名稱
	$ptype=stop_record_play_chart($game,$op_ptype);
	//取得變動項目的中文名稱
	$chg=stop_record_result_det_chart($game,$op_ptype,$chg_item);
	$bef=($bef_tmp);
	$aft=($aft_tmp);
	$dif_value=round(($aft-$bef),4);
	$ret[]=array($op_point,$date_sn,$account,$ip,$ptype,$chg,$odds_set,$bef,$aft,$dif_value);		 
	}
	return $ret;
}
//取得操盤紀錄-停押紀錄
/*
	 $game				=	遊戲玩法
	,$dateName		=	選擇的日期
	,$selectPlay	=	選擇的玩法類別
	,$selectTimes	=	選擇的期數
	,$pagers			=	每頁幾筆(15)
	,$now_pager		=	目前在第幾頁
	,$mm_id				=	公司編號
	回傳:[
				 操作時間
				,操作期數
				,操作帳號
				,操作ip
				,玩法
				,變動前值
				,變動後值
				]	 
	
	*遊戲轉換
	*取得當日資料庫期數
	*取得資料庫欄位
	*要查的筆數
	*取得停押紀錄
	*取得前台編號的中文名稱
	*取得變動項目的中文名稱
	*取得變動前值的中文名稱
	*取得變動後值的中文名稱
	*回傳
*/
function get_stop_record($game,$dateName,$selectPlay,$selectTimes,$pagers,$now_pager,$mm_id){
	global $db_s;
	$aDate_sn=array();
	$ptypes=array();
	$ptypes_tmp=array();
	$ptype_tmp=array();
	$aptype=array();
	//遊戲轉換
	$game_tran=tran_game_Num_data();
	$gtype=$game_tran[$game];
	//取得當日資料庫期數
	$selectTimes=(!isset($selectTimes))?'all':$selectTimes;
	$aDate_sn=get_record_draws_backstage_num($game,$dateName,$selectTimes);
	//取得資料庫欄位
	$select=get_play_chart($gtype);
	foreach($select as $key =>$value){
		$ptype_tmp[]=$key;
	}	 
	$pytpe_tran=trn_post_2_ptype__Autooddsupdate($gtype);
	if($selectPlay!='allplay'){
		if($game=='ssc'){
			//要轉成資料庫用的必須要+1
			$play_tmp=substr($selectPlay,-1)+1;
			$selectPlay='0'.$play_tmp;
		}else{
			$aptype=$pytpe_tran[$selectPlay];
		}
	}else{
		foreach($ptype_tmp as $ptype_key =>$ptype_value){
			$ptypes_tmp[]=$pytpe_tran[$ptype_value];
		}
		//echo'<xmp>';
		//print_r($select);
		//echo'</xmp>';
		foreach($ptypes_tmp as $aKey =>$aPtypes){
			if(empty($aPtypes)){continue;}
			foreach($aPtypes as $bKey =>$bPtype){
				if($game=='ssc'){
					//要轉成資料庫用的必須要+1
					$play_tmp=substr($bPtype,-1)+1;
					$selectPlay='0'.$play_tmp;
					$aptype[]=$selectPlay;
				}				
				$aptype[]=$bPtype;
			}
		}	 
	}	
	//要查的筆數
	$final=$now_pager*$pagers;
	$first=$final-15;
	$count_final=15;	
	//取得停押紀錄
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' op_point';
	$aSQL[]=',date_sn';
	$aSQL[]=',op_account';
	$aSQL[]=',op_ip';
	$aSQL[]=',op_ptype';
	$aSQL[]=',chg_item';
	$aSQL[]=',value_bef';
	$aSQL[]=',value_aft';
	$aSQL[]='FROM `log_odds_change`';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `date_sn`IN("[rpt_date]")';
	$aSQL[]='AND `op_type`="[op_type]"';
	$aSQL[]='AND `op_gtype`=[op_gtype]';
	$aSQL[]='AND `rpt_date`="[dateName]"';
	$aSQL[]='AND `mm_id`="[mm_id]"';
	$aSQL[]='AND `op_ptype`IN("[op_ptype]")';
	$aSQL[]='AND `odds_set`="[odds_set]"';
	$aSQL[]='ORDER BY `op_point` DESC LIMIT [first],[final]';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_date]',implode('","',$aDate_sn),$sSQL);
	$sSQL=str_replace('[op_type]','MB',$sSQL);
	$sSQL=str_replace('[op_gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[first]',$first,$sSQL);
	$sSQL=str_replace('[final]',$final,$sSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[dateName]',$dateName,$sSQL);
	$sSQL=str_replace('[odds_set]','N',$sSQL);
	$sSQL=str_replace('[op_ptype]',implode('","',$aptype),$sSQL);
	//echo $sSQL;
	$result=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
	$ary[]=$r;
	}
	if(empty($ary)){return $array=array();}
	foreach($ary as $key=> $value){
	$date_sn_tmp=$value['date_sn'];
	$op_point=$value['op_point'];
	$account=$value['op_account'];
	$ip=$value['op_ip'];
	$op_ptype=$value['op_ptype'];
	$chg_item=$value['chg_item'];
	$bef_tmp=$value['value_bef'];
	$aft_tmp=$value['value_aft'];
	//取得期數
	$date_sn=get_record_draws_reception_num($game,$dateName,$date_sn_tmp);
	//取得前台編號
	$chg_num=stop_record_chart($gtype,$op_ptype);
	if($game=='ssc'){
		$ptype_tmps=substr($chg_num,-1);
		$chg_num=($ptype_tmps+1);
		$ptype_tmpa=$ptype_tmps-1;
		$ptype_tmp="0".$ptype_tmpa;
		//echo "[0$chg_num]";
	}else{
		$ptype_tmp=$chg_num;
	}
	//取得前台編號的中文名稱
	$ptype=stop_record_play_chart($game,$op_ptype);
	//取得變動項目的中文名稱
		$chg=stop_record_result_det_chart($game,$op_ptype,$chg_item);
	//取得變動前值的中文名稱
	$bef=stop_record_result_chart($bef_tmp);
	//取得變動後值的中文名稱
	$aft=stop_record_result_chart($aft_tmp);
	$chg=($chg==null)?'':$chg;
	$bef=($bef==null)?'':$bef;
	$ret[]=array($op_point,$date_sn,$account,$ip,$ptype,$chg,$bef,$aft);		 
	}
	return $ret;
}
//停押紀錄-停押紀錄ptype對照表
function stop_record_ptype_chart(){
	$array=array(
		'klc'=>array(
				 'sumDT'	 =>array('140','141','142','175')
				,'ball1'	 =>array('100,','108','116','124','132','143','151','159')
				,'ball2'	 =>array('101,','109','117','125','133','144','152','160')
				,'ball3'	 =>array('102,','110','118','126','134','145','153','161')
				,'ball4'	 =>array('103,','111','119','127','135','146','154','162')
				,'ball5'	 =>array('104,','112','120','128','136','147','155')
				,'ball6'	 =>array('105,','113','121','129','137','148','156')
				,'ball7'	 =>array('106,','114','123','130','138','149','157')
				,'ball8'	 =>array('107,','115','123','131','139','150','158')
				,'evenCode'=>array('167,','168','169','170','171','172','173','174','175')
		)
		,'ssc'=>array(
			'sumDT'=>array()
		)
		,'pk'=>array(
				//總和
			 'li1_pk10'=>array('335','336','337')
			 //1~5
			,'li2_pk10'=>array(
					 '300','310','320','330'
					,'301','311','321','331'
					,'302','312','322','332'
					,'303','313','323','333'
					,'304','314','324','334'
			)
			//6~10
			,'li3_pk10'=>array(
					 '305','315','325'
					,'306','316','326'
					,'307','317','327'
					,'308','318','328'
					,'309','319','329'			
			)
		)
		,'nc'=>array(
				 'sumDT'	 =>array('140','141','142','175')
				,'ball1'	 =>array('100,','108','116','124','132','143','151','159')
				,'ball2'	 =>array('101,','109','117','125','133','144','152','160')
				,'ball3'	 =>array('102,','110','118','126','134','145','153','161')
				,'ball4'	 =>array('103,','111','119','127','135','146','154','162')
				,'ball5'	 =>array('104,','112','120','128','136','147','155')
				,'ball6'	 =>array('105,','113','121','129','137','148','156')
				,'ball7'	 =>array('106,','114','123','130','138','149','157')
				,'ball8'	 =>array('107,','115','123','131','139','150','158')
				,'evenCode'=>array('167,','168','169','170','171','172','173','174','175')
		)
		,'kb'=>array(
				 'sumDT'=>array('501','502','503','504','505','506','507','508','509','510')
				,'ball'=>array('500')
		
		)
	);
	if($game=='ssc'){
		for($i=200;$i<=234;$i++){
			$array[$game]['sumDT'][]=$i;
		}
	}
	return $array;
}
//操盤紀錄-停押紀錄玩法中文對照表
function stop_record_play_chart($game,$ptype){
	$array=array(
		 'klc'=>array(
				 '100'=>'第一球'
				,'101'=>'第二球'
				,'102'=>'第三球'
				,'103'=>'第四球'
				,'104'=>'第五球'
				,'105'=>'第六球'
				,'106'=>'第七球'
				,'107'=>'第八球'
				,'140'=>'總和單雙'
				,'141'=>'總和大小'
				,'142'=>'總和尾大尾小'
				,'167'=>'任選二'
				,'168'=>'選二聯組'
				,'170'=>'任選三'
				,'171'=>'選三前組'
				,'173'=>'任選四'
				,'174'=>'任選五'
				,'175'=>'正碼'
		 )
		,'ssc'=>array(
				 '200'=>'單碼'
				,'201'=>'單碼'
				,'202'=>'單碼'
				,'203'=>'單碼'
				,'204'=>'單碼'
				,'205'=>'兩面'
				,'206'=>'兩面'
				,'207'=>'龙虎和'
				,'208'=>'龙虎和'
				,'209'=>'龙虎和'
				,'210'=>'豹子'
				,'211'=>'豹子'
				,'212'=>'豹子'
				,'213'=>'顺子'
				,'214'=>'顺子'
				,'215'=>'顺子'
				,'216'=>'对子'
				,'217'=>'对子'
				,'218'=>'对子'
				,'219'=>'半顺'
				,'220'=>'半顺'
				,'221'=>'半顺'
				,'222'=>'杂六'
				,'223'=>'杂六'
				,'224'=>'杂六'
		)
		,'pk'=>array(
				 '300'=>'第一名'
				,'301'=>'第二名'
				,'302'=>'第三名'
				,'303'=>'第四名'
				,'304'=>'第五名'
				,'305'=>'第六名'
				,'306'=>'第七名'
				,'307'=>'第八名'
				,'308'=>'第九名'
				,'309'=>'第十名'
		)
		,'nc'=>array(
				 '100'=>'第一球'
				,'101'=>'第二球'
				,'102'=>'第三球'
				,'103'=>'第四球'
				,'104'=>'第五球'
				,'105'=>'第六球'
				,'106'=>'第七球'
				,'107'=>'第八球'
				,'140'=>'總和單雙'
				,'141'=>'總和大小'
				,'142'=>'總和尾大尾小'
				,'167'=>'任選二'
				,'168'=>'選二聯組'
				,'169'=>'選二聯直'
				,'170'=>'任選三'
				,'171'=>'選三前組'
				,'172'=>'選三前直'
				,'173'=>'任選四'
				,'174'=>'任選五'
				,'175'=>'正碼'		
		)
		,'kb'=>array(
				 '500'=>'正碼'
				,'501'=>'總和大小'
				,'502'=>'總和單雙'
				,'503'=>'總和和局'
				,'505'=>'總和過關'
				,'506'=>'總和過關'
				,'507'=>'前後和'
				,'508'=>'單雙和'
				,'509'=>'五行'
				,'510'=>'總和'
		)
	);
	if($game=='klc' || $game=='nc'){
		for($i=108;$i<=115;$i++){
			$array[$game][$i]='1~8單雙';
		}
		for($i=116;$i<=123;$i++){
			$array[$game][$i]='1~8大小';
		}		
		for($i=124;$i<=131;$i++){
			$array[$game][$i]='1~8尾大尾小';
		}
		for($i=132;$i<=139;$i++){
			$array[$game][$i]='1~8和數單雙';
		}		
		for($i=143;$i<=150;$i++){
			$array[$game][$i]='1~8中發白';
		}		
		for($i=151;$i<=158;$i++){
			$str=($game=='klc')?'1~8方位':'1~8東南西北';
			$array[$game][$i]=$str;
		}				
		for($i=159;$i<=162;$i++){
			$array[$game][$i]='1~4龍虎';
		}				
	}
	if($game=='ssc'){
		for($i=225;$i<=234;$i++){
			$array[$game][$i]='兩面';
		}
	}
	if($game=='pk'){
		for($i=310;$i<=319;$i++){
			$array[$game][$i]='1~10大小';
		}
		for($i=320;$i<=329;$i++){
			$array[$game][$i]='1~10單雙';
		}		
		for($i=330;$i<=334;$i++){
			$array[$game][$i]='1~5龍舞';
		}
		$array[$game]['335']='冠亞大小';
		$array[$game]['336']='冠亞單雙';
		$array[$game]['337']='冠亞組合';
	}
	return $array[$game][$ptype];
}
//操盤紀錄-停押紀錄球號中文對照表
function stop_record_result_det_chart($game,$ptype,$chg_item){
	$array=array(
		 'klc'=>array()
		,'ssc'=>array(
			'205'=>array(
					'O'=>'总单'		
					,'E'=>'总双'
				)			
			,'206'=>array(
					'L'=>'总大'
					,'S'=>'总小'				
				)
			,'207'=>array(
					'D'=>'龙'
				)
			,'208'=>array(
					'T'=>'虎'
				)				
			,'209'=>array(
					'B'=>'和'
				)
			,'210'=>array(
					'4'=>'前三-豹子'
				)
			,'211'=>array(
					'4'=>'中三-豹子'
				)		
			,'212'=>array(
					'4'=>'後三-豹子'
				)			
			,'213'=>array(
					'3'=>'前三-顺子'
				)
			,'214'=>array(
					'3'=>'中三-顺子'
				)
			,'215'=>array(
					'3'=>'後三-顺子'
				)			
			,'216'=>array(
					'2'=>'前三-对子'
				)			
			,'217'=>array(
					'2'=>'中三-对子'
				)			
			,'218'=>array(
					'2'=>'後三-对子'
				)
			,'219'=>array(
					'1'=>'前三-半顺'
				)			
			,'220'=>array(
					'1'=>'中三-半顺'
				)					
			,'221'=>array(
					'1'=>'後三-半顺'
				)					
			,'222'=>array(
					'0'=>'前三-杂六'
				)					
			,'223'=>array(
					'0'=>'中三-杂六'
				)		
			,'224'=>array(
					'0'=>'後三-杂六'
			)					
		)
		,'pk'=>array()
		,'nc'=>array()
		,'kb'=>array()
				
	);
	if($game=='klc' || $game=='nc'){
		//1~8球
		for($i=100;$i<=107;$i++){
			for($j=1;$j<=20;$j++){
				$num_j=($j<10)?"0".$j:$j;
				$array[$game][$i][$j]=$num_j;
			}
		}
		//正碼
		for($i=1;$i<=20;$i++){
			$num_i=($i<10)?"0".$i:$i;
			$array[$game]['175'][$i]=$num_i;
		}
		//1~8單雙		
		for($i=108;$i<=115;$i++){
			$x=$i-107;
			$num=num_tran_ch($x);			
			$array[$game][$i]['E']="第".$num."球-雙";
			$array[$game][$i]['O']="第".$num."球-單";
		}
		//1~8大小
		for($i=116;$i<=123;$i++){
			$x=$i-115;
			$num=num_tran_ch($x);
			$array[$game][$i]['L']="第".$num."球-大";
			$array[$game][$i]['S']="第".$num."球-小";
		}		
		//1~8尾大尾小
		for($i=124;$i<=131;$i++){
			$x=$i-123;
			$num=num_tran_ch($x);
			$array[$game][$i]['L']="第".$num."球-尾大";
			$array[$game][$i]['S']="第".$num."球-尾小";
		}		
		//1~8和數單雙
		for($i=132;$i<=139;$i++){
			$x=$i-131;
			$num=num_tran_ch($x);			
			$array[$game][$i]['E']="第".$num."球-合數雙";
			$array[$game][$i]['O']="第".$num."球-合數單";
		}		
		//1~8中發白
		for($i=143;$i<=150;$i++){
			$x=$i-142;
			$num=num_tran_ch($x);			
			$array[$game][$i]['C']="第".$num."球-中";
			$array[$game][$i]['F']="第".$num."球-發";
			$array[$game][$i]['B']="第".$num."球-白";
		}		
		//1~8方位
		for($i=151;$i<=158;$i++){
			$x=$i-150;
			$num=num_tran_ch($x);			
			$array[$game][$i]['E']="第".$num."球-東";
			$array[$game][$i]['S']="第".$num."球-南";
			$array[$game][$i]['W']="第".$num."球-西";
			$array[$game][$i]['N']="第".$num."球-北";
		}	
		//1~4龍虎
		for($i=159;$i<=162;$i++){
			$x=$i-158;
			$num=num_tran_ch($x);
			$array[$game][$i]['D']="第".$num."球-龍";
			$array[$game][$i]['T']="第".$num."球-虎";
		}
		//任選系列
		for($i=167;$i<=174;$i++){
			$array[$game][$i]['0']=' ';
		}
		//總和
		$array[$game]['140']['O']='正碼-總和-總和單';
		$array[$game]['140']['E']='正碼-總和-總和雙';
		$array[$game]['141']['L']='正碼-總和-總和大';
		$array[$game]['141']['S']='正碼-總和-總和小';
		$array[$game]['142']['L']='正碼-總和-總和尾大';
		$array[$game]['142']['S']='正碼-總和-總和尾小';
	}
	if($game=='ssc'){
		//單瑪
		for($i=200;$i<=204;$i++){
			for($j=0;$j<=9;$j++){
				$x=$i-199;
				$num=num_tran_ch($x);
				$array[$game][$i][$j] ="第".$num."球-0".$j;
				$array[$game][$i]['L']="第".$num."球-大";
				$array[$game][$i]['S']="第".$num."球-小";
				$array[$game][$i]['O']="第".$num."球-單";
				$array[$game][$i]['E']="第".$num."球-雙";
			}
		}
		//兩面
		for($i=225;$i<=229;$i++){
				$x=$i-224;
				$num=num_tran_ch($x);				
				$array[$game][$i]['O']="第".$num."球-單";
				$array[$game][$i]['E']="第".$num."球-雙";
		}	
		for($i=230;$i<=234;$i++){
				$x=$i-229;
				$num=num_tran_ch($x);				
				$array[$game][$i]['L']="第".$num."球-大";
				$array[$game][$i]['S']="第".$num."球-小";
		}
	}
	if($game=='pk'){
		//1~10名
			for($j=3;$j<=19;$j++){
				$num_j=($j<10)?"0".$j:$j;								
				$array[$game]['337'][$j]=$num_j;
			}
		//1~10大小		
		for($i=310;$i<=319;$i++){
			$x=$i-309;
			$num=num_tran_ch($x);
			$array[$game][$i]['L']="第".$num."名-大";
			$array[$game][$i]['S']="第".$num."名-小";
		}
		//1~10單雙
		for($i=320;$i<=329;$i++){
			$x=$i-319;
			$num=num_tran_ch($x);
			$array[$game][$i]['E']="第".$num."名-雙";
			$array[$game][$i]['O']="第".$num."名-單";
		}
		//1~5龍虎
		for($i=330;$i<=334;$i++){
			$x=$i-329;
			$num=num_tran_ch($x);
			$array[$game][$i]['D']="第".$num."名-龍";
			$array[$game][$i]['T']="第".$num."名-虎";
		}	
			$array[$game]['335']['L']='冠亞大';
			$array[$game]['335']['S']='冠亞小';
			$array[$game]['336']['O']='冠亞單';
			$array[$game]['336']['E']='冠亞雙';
	}
	if($game=='kb'){
		for($i=1;$i<=80;$i++){
			$num_i=($i<10)?"0".$i:$i;			
			$array[$game]['500'][$i]=$num_i;;
		}
		for($i=210;$i<=1410;$i++){
			$array[$game]['510'][$i]=$i;;
		}			
		//總和
		$array[$game]['501']['L']='總和大';
		$array[$game]['501']['S']='總和小';
		$array[$game]['502']['O']='總和單';
		$array[$game]['502']['E']='總和雙';
		$array[$game]['503']['D']='總和810';
		$array[$game]['505']['LO']='總大單';		
		$array[$game]['505']['LE']='總大雙';		
		$array[$game]['506']['SO']='總小單';		
		$array[$game]['506']['SE']='總小雙';		
		$array[$game]['507']['F']='前(多)';		
		$array[$game]['507']['B']='後(多)';		
		$array[$game]['507']['D']='和(多)';		
		$array[$game]['508']['O']='單(多)';		
		$array[$game]['508']['E']='雙(多)';		
		$array[$game]['508']['D']='和(多)';		
		$array[$game]['509']['1']='金';		
		$array[$game]['509']['2']='木';		
		$array[$game]['509']['3']='水';		
		$array[$game]['509']['4']='火';		
		$array[$game]['509']['5']='土';		
	}
	return $array[$game][$ptype][$chg_item];
}
//數字轉中文數字
function num_tran_ch($num){
	$array=array(
		 '0'=>'零'
		,'1'=>'一'
		,'2'=>'二'
		,'3'=>'三'
		,'4'=>'四'
		,'5'=>'五'
		,'6'=>'六'
		,'7'=>'七'
		,'8'=>'八'
	);
	return $array[$num];
}
//取得停押紀錄總筆數
/*
	 $game				=	遊戲玩法
	,$dateName		=	選擇的日期
	,$selectPlay	=	玩法類別
	,$selectTimes	=	選擇的期數
	,$pagers			=	每頁筆數(15)
	,$mm_id				=	公司編號
	
	回傳:總頁數
	
	*遊戲轉換
	*取得當日資料庫期數
	*取得資料庫欄位
	*取得筆數
	*轉換前台總頁數
*/	
function get_stop_record_count($game,$dateName,$selectPlay,$selectTimes,$pagers,$mm_id){
	global $db_s;
	$aDate_sn=array();
	$ptypes=array();
	$ptypes_tmp=array();
	$ptype_tmp=array();
	$aptype=array();
	//遊戲轉換
	$game_tran=tran_game_Num_data();
	$gtype=$game_tran[$game];
	//取得當日資料庫期數
	$selectTimes=(!isset($selectTimes))?'all':$selectTimes;
	$aDate_sn=get_record_draws_backstage_num($game,$dateName,$selectTimes);
	//取得資料庫欄位
	$select=get_play_chart($gtype);
	foreach($select as $key =>$value){
		$ptype_tmp[]=$key;
	}	 
	$pytpe_tran=trn_post_2_ptype__Autooddsupdate($gtype);
	if($selectPlay!='allplay'){
		if($game=='ssc'){
			//要轉成資料庫用的必須要+1
			$play_tmp=substr($selectPlay,-1)+1;
			$selectPlay='0'.$play_tmp;
		}else{
			$aptype=$pytpe_tran[$selectPlay];
		}
	}else{
		foreach($ptype_tmp as $ptype_key =>$ptype_value){
			$ptypes_tmp[]=$pytpe_tran[$ptype_value];
		}
		foreach($ptypes_tmp as $aKey =>$aPtypes){
			if(empty($aPtypes)){continue;}
			foreach($aPtypes as $bKey =>$bPtype){
				if($game=='ssc'){
					//要轉成資料庫用的必須要+1
					$play_tmp=substr($bPtype,-1)+1;
					$selectPlay='0'.$play_tmp;
					$aptype[]=$selectPlay;
				}				
				$aptype[]=$bPtype;
			}
		}	 
	}	
	//取得筆數
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='COUNT(sn) AS count';
	$aSQL[]='FROM `log_odds_change`';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND `date_sn`IN("[rpt_date]")';
	$aSQL[]='AND `op_type` ="[op_type]"';
	$aSQL[]='AND `op_gtype`=[op_gtype]';
	$aSQL[]='AND `rpt_date`="[dateName]"';
	$aSQL[]='AND `mm_id`="[mm_id]"';
	$aSQL[]='AND `op_ptype`IN("[op_ptype]")';
	$aSQL[]='AND `odds_set`="[odds_set]"';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[rpt_date]',implode('","',$aDate_sn),$sSQL);
	$sSQL=str_replace('[op_type]','MB',$sSQL);
	$sSQL=str_replace('[op_gtype]',$gtype,$sSQL);
	$sSQL=str_replace('[dateName]',$dateName,$sSQL);
	$sSQL=str_replace('[mm_id]',$mm_id,$sSQL);
	$sSQL=str_replace('[odds_set]','N',$sSQL);	
	$sSQL=str_replace('[op_ptype]',implode('","',$aptype),$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
	$tmp=$r['count'];
	}
	//轉換前台總頁數
	$ret=ceil($tmp/$pagers);
	$ret=($ret==0)?1:$ret;	
	return $ret;
}
//停押紀錄結果對照表
function stop_record_result_chart($result){
	$array=array(
		 '0'=>'封單'
		,'1'=>'收單'
	);
	return $array[$result];
	
}
//取得前台期數
/*
	 $game			=	遊戲玩法
	,$dateName	=	要查詢的日期
	,$date_sn		=	要查詢的期數(資料庫)ex(84,83...)
	
	回傳:前台所顯示的期數ex:(2016073183、2016073184...)
*/
function get_record_draws_reception_num($game,$dateName,$date_sn){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='draws_num';
	$aSQL[]='FROM draws_[game]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[rpt_date]"';
	$aSQL[]='AND date_sn="[date_sn]"';
	$sSQL=implode(' ',$aSQL);	
	$sSQL=str_replace('[game]',$game,$sSQL);
	$sSQL=str_replace('[rpt_date]',$dateName,$sSQL);
	$sSQL=str_replace('[date_sn]',$date_sn,$sSQL);
	$q=$db_s->sql_query($sSQL);
	$ret=array();
	while($r=$db_s->nxt_row()){
		$ret=$r[0];
	}
	return $ret;
}
//停押紀錄用的對照表
/*
	 $gtype	= 遊戲玩法
	,$ptype	= 資料庫ptype
	
	回傳:後台對應成前台的編號
*/
function stop_record_chart($gtype,$ptype){
	$array=array(
		'11'=>array(
			 '100'=>'00'
			,'101'=>'01'
			,'102'=>'02'
			,'103'=>'03'
			,'104'=>'04'
			,'105'=>'05'
			,'106'=>'06'
			,'107'=>'07'
			,'108'=>'08'
			,'109'=>'08'
			,'110'=>'08'
			,'111'=>'08'
			,'112'=>'08'
			,'113'=>'08'
			,'114'=>'08'
			,'115'=>'08'
			,'116'=>'09'
			,'117'=>'09'
			,'118'=>'09'
			,'119'=>'09'
			,'120'=>'09'
			,'121'=>'09'
			,'122'=>'09'
			,'123'=>'09'
			,'124'=>'10'
			,'125'=>'10'
			,'126'=>'10'
			,'127'=>'10'
			,'128'=>'10'
			,'129'=>'10'
			,'130'=>'10'
			,'131'=>'10'
			,'132'=>'11'
			,'133'=>'11'
			,'134'=>'11'
			,'135'=>'11'
			,'136'=>'11'
			,'137'=>'11'
			,'138'=>'11'
			,'139'=>'11'
			,'140'=>'12'
			,'141'=>'13'
			,'142'=>'14'
			,'143'=>'15'
			,'144'=>'15'
			,'145'=>'15'
			,'146'=>'15'
			,'147'=>'15'
			,'148'=>'15'
			,'149'=>'15'
			,'150'=>'15'
			,'151'=>'16'
			,'152'=>'16'
			,'153'=>'16'
			,'154'=>'16'
			,'155'=>'16'
			,'156'=>'16'
			,'157'=>'16'
			,'158'=>'16'
			,'159'=>'17'
			,'160'=>'17'
			,'161'=>'17'
			,'162'=>'17'
			,'167'=>'18'
			,'168'=>'19'
			,'170'=>'20'
			,'171'=>'23'
			,'173'=>'24'
			,'174'=>'25'
			,'175'=>'29'
		)
		,'12'=>array(
			 '200'=>'01'
			,'201'=>'01'
			,'202'=>'01'
			,'203'=>'01'
			,'204'=>'01'
			,'205'=>'02'
			,'206'=>'02'
			,'225'=>'02'
			,'226'=>'02'
			,'227'=>'02'
			,'228'=>'02'
			,'229'=>'02'
			,'230'=>'02'
			,'231'=>'02'
			,'232'=>'02'
			,'207'=>'03'
			,'208'=>'03'
			,'213'=>'04'
			,'214'=>'04'
			,'215'=>'04'
			,'216'=>'05'
			,'217'=>'05'
			,'218'=>'05'
			,'219'=>'06'
			,'220'=>'06'
			,'221'=>'06'
			,'222'=>'07'
			,'223'=>'07'
			,'224'=>'07'
		)
		,'13'=>array(
				 '300'=>'00'
				,'301'=>'01'
				,'302'=>'02'
				,'303'=>'03'
				,'304'=>'04'
				,'305'=>'05'
				,'306'=>'06'
				,'307'=>'07'
				,'308'=>'08'
				,'309'=>'09'
				,'310'=>'10'
				,'311'=>'10'
				,'312'=>'10'
				,'313'=>'10'
				,'314'=>'10'
				,'315'=>'10'
				,'316'=>'10'
				,'317'=>'10'
				,'318'=>'10'
				,'319'=>'10'
				,'320'=>'11'
				,'321'=>'11'
				,'322'=>'11'
				,'323'=>'11'
				,'324'=>'11'
				,'325'=>'11'
				,'326'=>'11'
				,'327'=>'11'
				,'328'=>'11'
				,'329'=>'11'
				,'330'=>'12'
				,'331'=>'12'
				,'332'=>'12'
				,'333'=>'12'
				,'334'=>'12'
				,'335'=>'13'
				,'336'=>'14'
				,'337'=>'15'
		)
		,'14'=>array(
			 '100'=>'00'
			,'101'=>'01'
			,'102'=>'02'
			,'103'=>'03'
			,'104'=>'04'
			,'105'=>'05'
			,'106'=>'06'
			,'107'=>'07'
			,'108'=>'08'
			,'109'=>'08'
			,'110'=>'08'
			,'111'=>'08'
			,'112'=>'08'
			,'113'=>'08'
			,'114'=>'08'
			,'115'=>'08'
			,'116'=>'09'
			,'117'=>'09'
			,'118'=>'09'
			,'119'=>'09'
			,'120'=>'09'
			,'121'=>'09'
			,'122'=>'09'
			,'123'=>'09'
			,'124'=>'10'
			,'125'=>'10'
			,'126'=>'10'
			,'127'=>'10'
			,'128'=>'10'
			,'129'=>'10'
			,'130'=>'10'
			,'131'=>'10'
			,'132'=>'11'
			,'133'=>'11'
			,'134'=>'11'
			,'135'=>'11'
			,'136'=>'11'
			,'137'=>'11'
			,'138'=>'11'
			,'139'=>'11'
			,'140'=>'12'
			,'141'=>'13'
			,'142'=>'14'
			,'143'=>'15'
			,'144'=>'15'
			,'145'=>'15'
			,'146'=>'15'
			,'147'=>'15'
			,'148'=>'15'
			,'149'=>'15'
			,'150'=>'15'
			,'151'=>'16'
			,'152'=>'16'
			,'153'=>'16'
			,'154'=>'16'
			,'155'=>'16'
			,'156'=>'16'
			,'157'=>'16'
			,'158'=>'16'
			,'159'=>'17'
			,'160'=>'17'
			,'161'=>'17'
			,'162'=>'17'
			,'167'=>'20'
			,'168'=>'21'
			,'169'=>'22'
			,'170'=>'23'
			,'171'=>'30'
			,'173'=>'26'
			,'174'=>'27'
			,'175'=>'29'		
		)
		,'15'=>array(
			 '500'=>'00'
			,'501'=>'01'
			,'502'=>'02'
			,'503'=>'03'
			,'504'=>'04'
			,'505'=>'04'
			,'506'=>'04'
			,'507'=>'05'
			,'508'=>'06'
			,'509'=>'07'
		)
	);
	return $array[$gtype][$ptype];
}
//取得當期期數
/*
	 $op_gtype	=	遊戲類別
	,$op_date		=	現在日期
	
	回傳:期數
	
	*轉換遊戲類別名稱
	*資料庫判斷
			*抓取現在已開盤  如果已開盤=1 回傳這個已開盤的期數 並 return
				如果沒有已開盤=1 則抓已關盤=0 ， 如果抓出來不是空陣列,就回傳第一筆的期數
				如果沒有已開盤=1 則抓已關盤=0 ， 如果抓出來是空陣列,就回傳今日最後一筆的期數
			}
*/
function get_draws_to_record($op_gtype,$op_date){
	global $db_s;
	$tmp=tran_game_Num_data();
	$tran=array_flip($tmp);
	$gtype=$tran[$op_gtype];
	$SQL=array();
	//抓取已開盤
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]='FROM [`draws`]';
	$SQL[]='WHERE  `game_open`=[game_open] ';
	$SQL[]='AND  `rpt_date`="[rpt_date]" ';
	$strSQL=implode(' ',$SQL);
	$strSQL=str_replace('[`draws`]','draws_'.$gtype,$strSQL);
	$strSQL=str_replace('[game_open]','1',$strSQL);
	$strSQL=str_replace('[rpt_date]',$op_date,$strSQL);
	$result = $db_s->sql_query($strSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret=$r['date_sn'];
	}
	//如果現在有開盤中，期數=現在開盤中的期數
	if(!empty($ret)){return $ret;}
	//如果現在都沒有開盤的，抓取還沒結束的
	$SQL_1[]='SELECT';
	$SQL_1[]='date_sn,draws_num';
	$SQL_1[]='FROM [`draws`]';
	$SQL_1[]='WHERE 1';
	$SQL_1[]='AND	game_over=[game_over]';
	$SQL_1[]='AND	rpt_date="[rpt_date]"';
	$SQL_1[]='LIMIT 0,1';
	$strSQL_1=implode(' ',$SQL_1);
	$strSQL_1=str_replace('[`draws`]','draws_'.$gtype,$strSQL_1);
	$strSQL_1=str_replace('[game_over]','0',$strSQL_1);
	$strSQL_1=str_replace('[rpt_date]',$op_date,$strSQL_1);
	$result = $db_s->sql_query($strSQL_1);
	while($r_1=$db_s->nxt_row('ASSOC')){
		$ret=$r_1['date_sn'];
	}	
	//如果現在不是最後一個關盤期數，期數=現在關盤期數=0的第一個
	if(!empty($ret)){return $ret;}
	//如果以上都沒有的話，則期數=今日最後一期
	$SQL_2[]='SELECT';
	$SQL_2[]='date_sn';
	$SQL_2[]='FROM [`draws`]';
	$SQL_2[]='WHERE 1';
	$SQL_2[]='AND	game_over=[game_over]';
	$SQL_2[]='AND	rpt_date="[rpt_date]"';
	$SQL_2[]='order by date_sn desc LIMIT 0,1';
	$strSQL_2=implode(' ',$SQL_2);
	$strSQL_2=str_replace('[`draws`]','draws_'.$gtype,$strSQL_2);
	$strSQL_2=str_replace('[game_over]','0',$strSQL_2);
	$strSQL_2=str_replace('[rpt_date]',$op_date,$strSQL_2);
	$result = $db_s->sql_query($strSQL_2);
	while($r_2=$db_s->nxt_row('ASSOC')){
		$ret=$r_2['date_sn'];
	}	
	return $ret;	
}
//取得IP歸屬地
function get_ip_area($aArea){
	global $web_cfg;
	$path_lib=$web_cfg['path_lib'];
	//IP歸屬地測試
	$url=$path_lib.'ip_utf8.dat';
	$object=ipv4_area_create($url);//初始化数据库
	if($object['status']==IPV4_AREA_ERROR_SUCCESS){
		foreach($aArea as $ip){ //循环查询每一个IP的所在地
			$area[$ip]=ipv4_area_search($object,$ip);
		}
	}
	ipv4_area_free($object); //释放对象	
	return $area;
}
//取得使用者帳號紀錄
/*
	$ulv=層級,$uid=編號
	回傳=[
		  [序號,登入時間,類型,變動前值,變動後值,操作人,IP,IP歸屬]
		 ,[序號,登入時間,類型,變動前值,變動後值,操作人,IP,IP歸屬]
		 :
	]
	*
	*取得ulv對照表
	*判斷login_ui
	*取七天內該帳號操作紀錄
	*取七天後20筆操作紀錄
	*整合到ret陣列後回傳
*/
function get_operationlog($ulv,$uid){
	//vip999 看 fe99 的  ulv=1 , uid=118
	global $_UserData;
	$total=1;
	$sid=$uid;
	switch($ulv){
		case '0':
			$slv='SUB';
			break;	
		case '5':
			$slv='MEM';
			break;			
		default:
			$slv='agents';
	}	
	//取得user_name
	$user_name= get_op_rec_account($slv,$sid);
	$table=($slv=='SUB')?'log_useredit_sub':'log_useredit';
	//取得帳號管理紀錄
	$array=get_diary($user_name,$table);
	return $array;
}
//取得帳號管理紀錄
/*

	 $user_name	= 使用者名稱
	,$table			= 資料表名稱 
	回傳=[
		  [序號,登入時間,類型,變動前值,變動後值,操作人,IP,IP歸屬]
		 ,[序號,登入時間,類型,變動前值,變動後值,操作人,IP,IP歸屬]
		 :
	]

	*取15天內該帳號操作紀錄
	*取15天後50筆操作紀錄
	*取IP歸屬地
	*整合到ret陣列後回傳
*/
function get_diary($user_name,$table){
	global $db_s;
	$total=1;
	//取15天內該帳號操作紀錄
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]=' op_point';
	$aSQL[]=',op_type';
	$aSQL[]=',chg_item';
	$aSQL[]=',op_ip';
	$aSQL[]=',op_gtype';
	$aSQL[]=',op_ptype';
	$aSQL[]=',value_bef';
	$aSQL[]=',value_aft';
	$aSQL[]=',op_account';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND account="[uid]"';
	$aSQL[]='AND op_point>=date_sub(curdate(), INTERVAL 15 DAY)';
	$aSQL[]='UNION';
	//取15天後50筆操作紀錄
	$aSQL[]='(';
	$aSQL[]='SELECT';
	$aSQL[]=' op_point';
	$aSQL[]=',op_type';
	$aSQL[]=',chg_item';
	$aSQL[]=',op_ip';
	$aSQL[]=',op_gtype';
	$aSQL[]=',op_ptype';
	$aSQL[]=',value_bef';
	$aSQL[]=',value_aft';
	$aSQL[]=',op_account';
	$aSQL[]='FROM';
	$aSQL[]='[table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND account="[uid]"';
	$aSQL[]='AND op_point<date_sub(curdate(), INTERVAL 15 DAY) LIMIT 50';
	$aSQL[]=')';
	$aSQL[]='ORDER BY `op_point` DESC';
	$sSQL=implode(" ",$aSQL);
	$sSQL=str_replace ('[uid]',$user_name,$sSQL);
	$sSQL=str_replace ('[table]',$table,$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$tmp[]=$r;
	}
	if(empty($tmp)){$ret=array();}
	foreach($tmp as $key => $value){
		if(empty($value['value_aft'])){continue;}
		$chg_tmp=$value['chg_item'];
		$bef=$value['value_bef'];
		$aft=$value['value_aft'];
		$gtype=$value['op_gtype'];
		$ptype=$value['op_ptype'];
		$date=$value['op_point'];
		$ip=$value['op_ip'];
		$account=$value['op_account'];			
		//轉換中文名字
		//類型
		$chg_tmp2=get_user_chinese_agent_chart($chg_tmp);
		$bchg=get_usr_det_type_chart($gtype,$ptype);
		$game_play=get_play_class($gtype);
		$chg=$game_play.$bchg.$chg_tmp2;
		//詳細內容
		$val_tmp=get_user_chinese_play_subchart($chg_tmp,$bef,$aft);
		$val_bef=(empty($val_tmp[$bef]))?$bef:$val_tmp[$bef];
		$val_aft=(empty($val_tmp[$aft]))?$aft:$val_tmp[$aft];
		$ret[]=array($total,$date,$chg,$val_bef,$val_aft,$account,$ip);
		$total=$total+1;
	}
	//取IP歸屬地
	$area_tmp=array();
	foreach($ret as $key =>$value){
		$getip=$value[6];
		$area_tmp[$getip]=$getip;
	}
	$aArea=get_ip_area($area_tmp);
	foreach($ret as $k=>$val){
		$getip=$val[6];
		$ret[$k][7]=$aArea[$getip];
	}
	return $ret;	
}
//遊戲對照表
function get_play_class($game){
	$array=array(
		 '11'=>'廣東'
		,'12'=>'時彩'
		,'13'=>'賽車'
		,'14'=>'農場'
		,'15'=>'快樂'
	);
	return $array[$game];
}
//操作類型詳細內容對照表
function get_usr_det_type_chart($gtype,$ptype){
	$array=array(
		'11'=>array(
			'00'=>'1~8单码'
			,'29'=>'正码'
			,'08'=>'1~8两面'
			,'12'=>'总和两面'
			,'15'=>'1~8中发白'
			,'16'=>'1~8方位'
			,'17'=>'1~4龙虎'
			,'18'=>'任选二'
			,'20'=>'选二连組'
			,'21'=>'任选三'
			,'23'=>'选三前组'
			,'24'=>'任选四'
			,'25'=>'任选五'
		)
		,'12'=>array(
			'00'=>'1~5单码'
			,'01'=>'两面'
			,'02'=>'龙虎'
			,'03'=>'和'
			,'04'=>'豹子'
			,'05'=>'顺子'
			,'06'=>'对子'
			,'07'=>'半顺'
			,'08'=>'杂六'	
		)
		,'13'=>array(
			'00'=>'冠亞,3~10单码'
			,'10'=>'1~10两面'
			,'12'=>'1~5龙虎'
			,'13'=>'冠亞大小'
			,'14'=>'冠亚单双'
			,'15'=>'冠亞和'
		)
	,'14'=>array(
			'00'=>'1~8单码'
			,'29'=>'正码'
			,'08'=>'1~8两面'
			,'12'=>'总和两面'
			,'15'=>'1~8中发白'
			,'16'=>'1~8東南西北'
			,'17'=>'1~4龙虎'
			,'20'=>'任选二'
			,'22'=>'选二连直'
			,'21'=>'选二连組'
			,'23'=>'任选三'
			,'30'=>'选三前组'
			,'26'=>'任选四'
			,'27'=>'任选五'
		)	
	,'15'=>array(
			'00'=>'正码'
			,'01'=>'总和大小'
			,'02'=>'总和单双'
			,'03'=>'总和和局'
			,'04'=>'总和过关'
			,'05'=>'前后和'
			,'06'=>'单双和'
			,'07'=>'五行'
		)			
	);	
	return $array[$gtype][$ptype];
}
//帳號管理紀錄-變動類型中文對照表
function get_user_chinese_play_subchart($chg_tmp,$bef,$aft){
	if($bef!=1 && !(empty($bef))){
		$rpt_b=$bef.'倍';
	}elseif($bef==1){
		$rpt_b='不按倍數';
	}else{
		$rpt_b=$bef;
	}
	$rpt_f=($aft==1)?'不按倍數':$aft.'倍';
	$array=array(
			 'SFTJ'				=>array('true'=>'啟用','false'=>'停用')
			,'KJGL'				=>array('true'=>'啟用','false'=>'停用')
			,'BACX'				=>array('true'=>'啟用','false'=>'停用')
			,'XTSD'				=>array('true'=>'啟用','false'=>'停用')
			,'BHSD'				=>array('true'=>'啟用','false'=>'停用')
			,'QSGL'				=>array('true'=>'啟用','false'=>'停用')
			,'CPJL'				=>array('true'=>'啟用','false'=>'停用')
			,'XCJD'				=>array('true'=>'啟用','false'=>'停用')
			,'ZHGL'				=>array('true'=>'啟用','false'=>'停用')
			,'name' 			=>array($bef=>$bef,$aft=>$aft)
			,'status' 		=>array('0'=>'停用','1'=>'啟用','2'=>'停押','3'=>'禁止登入','4'=>'刪除')
			,'ylchFlag' 	=>array('true'=>'允許','false'=>'不允許')
			,'rpt_beishu' =>array($bef=>$rpt_b,$aft=>$rpt_f)
			//-------------------------------------------------------------
			,'password'=>array($bef=>'****',$aft=>'****')
			,'credit'  =>array($bef=>$bef,$aft=>$aft)
			,'odds_set'=>array($bef=>$bef,$aft=>$aft)
			,'share_total'=>array($bef=>$bef,$aft=>$aft)
			,'short_covering'=>array('0'=>'允許','1'=>'不允許')
			,'corpRptFlag'=>array('0'=>'允許','1'=>'不允許')
			,'detRptFlag'=>array('0'=>'允許','1'=>'不允許')
			,'bhRptFlag'=>array('0'=>'允許','1'=>'不允許')
			,'isDgdShare'=>array('0'=>'後台','1'=>'分公司')		
			,'share_flag'=>array('0'=>'是','1'=>'否')		
			,'share_up'=>array($bef=>$bef,$aft=>$aft)	
			,'beishu_set'=>array('0'=>'允許','1'=>'不允許')
			,'level'=>array('5'=>'会员','4'=>'代理','3'=>'总代理','2'=>'股东','1'=>'分公司','0'=>'管理员')
			,'set_water'=>array(
					 '100'=>'不賺水'
					,'0'	=>'賺取水所有退水'
					,'0.05'	=>'賺取0.05%退水'
					,'0.1'	=>'賺取0.1%退水'
					,'0.15'	=>'賺取0.15%退水'
					,'0.2'	=>'賺取0.2%退水'
					,'0.25'	=>'賺取0.25%退水'
					,'0.3'	=>'賺取0.3%退水'
					)
	);
	return $array[$chg_tmp];
}
//帳號管理紀錄-分公司操作類型中文對照表
function get_user_chinese_agent_chart($chg_tmp){
		$array=array(
				'SFTJ'				=>'收付統計'
				,'KJGL'				=>'開獎結果'
				,'BACX'				=>'報表查詢'
				,'XTSD'				=>'系統設定'
				,'BHSD'				=>'補貨設定'
				,'QSGL'				=>'期數管理'
				,'CPJL'				=>'操盤紀錄'
				,'XCJD'				=>'現場監督'
				,'ZHGL'				=>'帳號管理'
				,'name' 			=>'名稱'
				,'password' 	=>'密碼'
				,'status' 		=>'狀態'
				,'ylchFlag' 	=>'預留吃貨報表'
				,'rpt_beishu' =>'倍數查看報表'
				,'account' 		=>'賬號'
				,'credit'  			 =>'總信用额度'
				,'odds_set'			 =>'所屬盘口'
				,'share_total'   =>'占成權限总和(%)'
				,'short_covering'=>'补货設定'
				,'corpRptFlag'	 =>'查看後台報表匯總'
				,'detRptFlag'		 =>'查看後台報表明細'
				,'bhRptFlag'		 =>'查看补货報表'
				,'isDgdShare'		 =>'剩餘占成'	
				,'concede_max'	=>'单项最高'	
				,'order_max'		 =>'单注最高'		
				,'order_min'		 =>'单注最低'	
				,'water_a'		 =>'A盤退水'	
				,'water_b'		 =>'B盤退水'	
				,'water_c'		 =>'C盤退水'	
				,'share_flag'		=>'补货是否占成'
				,'share_up'			=>'實際占成(%)'				
				,'beishu_set'	=>'倍數投注'				
				,'set_water'	  =>'退水設定'
				,'level'	  =>'層級'
				
		);
	return $array[$chg_tmp];		
}
//帳號管理紀錄-子帳號操作類型中文對照表
function get_user_chinese_chart($chg_tmp){
		$array=array(
				'SFTJ'				=>'收付統計'
				,'KJGL'				=>'開獎結果'
				,'BACX'				=>'報表查詢'
				,'XTSD'				=>'系統設定'
				,'BHSD'				=>'補貨設定'
				,'QSGL'				=>'期數管理'
				,'CPJL'				=>'操盤紀錄'
				,'XCJD'				=>'現場監督'
				,'ZHGL'				=>'帳號管理'
				,'name' 			=>'名稱'
				,'password' 	=>'密碼'
				,'status' 		=>'狀態'
				,'ylchFlag' 	=>'預留吃貨報表'
				,'rpt_beishu' =>'倍數查看報表'
				,'account' 		=>'賬號'
				,'credit'  			 =>'總信用额度'
				,'odds_set'			 =>'所屬盘口'
				,'share_total'   =>'分公司及下級占成權限总和(%)'
				,'short_covering'=>'补货設定'
				,'corpRptFlag'	 =>'查看後台報表匯總'
				,'detRptFlag'		 =>'查看後台報表明細'
				,'bhRptFlag'		 =>'查看补货報表'
				,'isDgdShare'		 =>'倍數投注'
			
		);
	return $array[$chg_tmp];
}
//---log_service_freq 相關---
//紀錄某個服務的開始與結束
/*
  $sService=服務名稱
  ,$sType=種類
    S=開始,O=結束
  $fTime_exec=執行時間
  *30秒之內已經有紀錄,就PASS
*/
function log_set_service_freq($sService,$sType,$fTime_exec=0){
  global $db;
  global $db_s;
  $sType=strtoupper($sType);
  if(!in_array($sType,array('S','O'))){return;}
  $sNowTime=date('Y-m-d H:i:s');
  $iMsec=round(uti_get_microsec()*10000);
  //先確定30秒內是否已經有一個循環
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='COUNT(1) AS cnt';
  $aSQL[]='FROM log_service_freq';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND ser_name="[ser_name]"';
  $aSQL[]='AND time_over > DATE_SUB(NOW(),INTERVAL 31 SECOND)';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[ser_name]',$sService,$sSQL);
  $q=$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  if($r['cnt']>0){return ;}
  $aSQL=array();
  if($sType =='S'){
    //開始
    $aSQL[]='INSERT INTO log_service_freq';
    $aSQL[]='(`ser_name`,`time_start`,`time_start_ms`)';
    $aSQL[]='VALUES';
    $aSQL[]='("[ser_name]","[time_start]","[time_start_ms]")';
    $aSQL[]='ON DUPLICATE KEY UPDATE';
    $aSQL[]=' time_start ="[time_start]"';
    $aSQL[]=',time_start_ms ="[time_start_ms]"';
  }else if($sType =='O'){
    //結束
    $aSQL[]='INSERT INTO log_service_freq';
    $aSQL[]='(`ser_name`,`time_over`,`time_over_ms`,`time_exec`,`last_update`)';
    $aSQL[]='VALUES';
    $aSQL[]='("[ser_name]","[time_over]","[time_over_ms]","[time_exec]","[last_update]")';
    $aSQL[]='ON DUPLICATE KEY UPDATE';
    $aSQL[]=' time_over ="[time_over]"';
    $aSQL[]=',time_over_ms ="[time_over_ms]"';
    $aSQL[]=',time_exec ="[time_exec]"';
    $aSQL[]=',last_update ="[last_update]"';
  }
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[ser_name]',$sService,$sSQL);
  $sSQL=str_replace('[time_start]',$sNowTime,$sSQL);
  $sSQL=str_replace('[time_start_ms]',$iMsec,$sSQL);
  $sSQL=str_replace('[time_over]',$sNowTime,$sSQL);
  $sSQL=str_replace('[time_over_ms]',$iMsec,$sSQL);  
  $sSQL=str_replace('[time_exec]',$fTime_exec,$sSQL);  
  $sSQL=str_replace('[last_update]',$sNowTime,$sSQL);  
  $q=$db->sql_query($sSQL);
}
//---log_service 相關---
//紀錄某個服務的開始與結束
/*
  $sService=服務名稱
  ,$sType=種類
    S=開始,O=結束
  $fTime_exec=執行時間
  *30秒之內已經有紀錄,就PASS
  *開始就直接新增
  *結束要先知道剛剛那個是幾號,再去UPDATE
*/
function log_set_service($sService,$sType,$fTime_exec=0){
  global $db;
  global $db_s;
  $sType=strtoupper($sType);
  if(!in_array($sType,array('S','O'))){return;}
  $sNowTime=date('Y-m-d H:i:s');
  $iMsec=round(uti_get_microsec()*10000);
  //先確定30秒內是否已經有一個循環
  $aSQL=array();
  $aSQL[]='SELECT';
  $aSQL[]='COUNT(1) AS cnt';
  $aSQL[]='FROM log_service';
  $aSQL[]='WHERE 1';
  $aSQL[]='AND ser_name="[ser_name]"';
  $aSQL[]='AND time_over > DATE_SUB(NOW(),INTERVAL 31 SECOND)';
  $aSQL[]='LIMIT 1';
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[ser_name]',$sService,$sSQL);
  $q=$db_s->sql_query($sSQL);
  $r=$db_s->nxt_row('ASSOC');
  if($r['cnt']>0){return ;}
  $iSn=0;
  $aSQL=array();
  if($sType =='S'){
    //開始
    $aSQL[]='INSERT INTO log_service';
    $aSQL[]='(`ser_name`,`time_start`,`time_start_ms`)';
    $aSQL[]='VALUES';
    $aSQL[]='("[ser_name]","[time_start]","[time_start_ms]")';
  }else if($sType =='O'){
    //結束
    $aSQL[]='SELECT';
    $aSQL[]='sn';
    $aSQL[]='FROM log_service';
    $aSQL[]='WHERE 1';
    $aSQL[]='AND ser_name="[ser_name]"';
    $aSQL[]='AND time_over IS NULL';
    $aSQL[]='ORDER BY sn DESC';
    $aSQL[]='LIMIT 0,1';
    $sSQL=implode(' ',$aSQL);    
    $sSQL=str_replace('[ser_name]',$sService,$sSQL);
    $q=$db->sql_query($sSQL);
    if($db->numRows()<1){return ;}
    $r=$db->nxt_row('ASSOC');
    $iSn=$r['sn'];
    $aSQL=array();    
    $aSQL[]='UPDATE log_service SET ';
    $aSQL[]=' time_over="[time_over]"';
    $aSQL[]=',time_over_ms="[time_over_ms]"';
    $aSQL[]=',time_exec="[time_exec]"';
    $aSQL[]=',last_update="[last_update]"';
    $aSQL[]='WHERE 1';
    $aSQL[]='AND sn="[sn]"';
  }
  $sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[sn]',$iSn,$sSQL);
  $sSQL=str_replace('[ser_name]',$sService,$sSQL);
  $sSQL=str_replace('[time_start]',$sNowTime,$sSQL);
  $sSQL=str_replace('[time_start_ms]',$iMsec,$sSQL);
  $sSQL=str_replace('[time_over]',$sNowTime,$sSQL);
  $sSQL=str_replace('[time_over_ms]',$iMsec,$sSQL);  
  $sSQL=str_replace('[time_exec]',$fTime_exec,$sSQL);  
  $sSQL=str_replace('[last_update]',$sNowTime,$sSQL);  
  // echo $sSQL."\n";
  $q=$db->sql_query($sSQL);  
}
//紀錄某段程式做了哪些事情
/*
  $exec_script=程式名稱
  $rec_time=執行時的時間
	$info=做了什麼事情
  *開始就直接新增
*/
function log_curse($exec_script,$rec_time,$info){
  global $db;
  $aSQL[]='INSERT INTO log_curse';
  $aSQL[]='(`exec_script`,`rec_time`,`info`)';
  $aSQL[]='VALUES';
  $aSQL[]="('[exec_script]','[rec_time]','[info]')";
	$sSQL=implode(' ',$aSQL);
  $sSQL=str_replace('[exec_script]',$exec_script,$sSQL);
  $sSQL=str_replace('[rec_time]',$rec_time,$sSQL); 
  $sSQL=str_replace('[info]',$info,$sSQL); 
  // echo $sSQL."\n";
  $q=$db->sql_query($sSQL);  
}
//---log_odds_change 相關---
//新增操盤紀錄
/*
   $sOp_lv=操作者層級,$iOp_id=操作者編號
  ,$iMM_id=公司編號
  ,$sRpt_date=帳務日期,$iDate_sn=當日期數
  ,$aModify=修改的內容
    $aModify[]={
       'bf'=>修改前
      ,'af'=>修改後
      ,'gtype'=>遊戲代號
      ,'ptype'=>玩法代號
      ,'otype'=>賠率盤
      ,'item'=>項目
      ,'op_type'=>操作類型
    }
*/
function log_put_odds_change_op_rec($sOp_lv,$iOp_id,$iMM_id,$sRpt_date,$iDate_sn,$aModify){
  global $db;
  if(count($aModify)<1){return ;}
  $aValue=array();  
  //資料表
	$table='log_odds_change';
  $aValue['mm_id']=$iMM_id;
  $aValue['rpt_date']=$sRpt_date;
  $aValue['date_sn']=$iDate_sn;
  $aValue['op_point']=date('Y-m-d H:i:s');
  $aValue['op_account']=get_op_rec_account($sOp_lv,$iOp_id);
  $aValue['op_level']=$sOp_lv;  
  $aValue['op_ip']=uti_get_ip();
  $aCol=array(
    'mm_id'
    ,'rpt_date','date_sn','op_point'
    ,'op_account','op_level','op_type'
    ,'op_ip'
    ,'op_gtype','op_ptype','chg_item','odds_set'
    ,'value_bef','value_aft'
  );
	//欄位
	$aSQL=array();
	$aSQL[]='INSERT DELAYED INTO [table](`[cols]`)';
	$aSQL[]='VALUES';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[cols]',implode('`,`',$aCol),$sSQL);
	//值
	$sVAL='("[[cols]]")';
	$sVAL=str_replace('[cols]',implode(']","[',$aCol),$sVAL);
	$aVAL=array();
	foreach($aModify as $k => $v){
		$aValue['op_type']=$v['op_type'];
		$aValue['op_gtype']=$v['gtype'];
		$aValue['op_ptype']=$v['ptype'];
		$aValue['chg_item']=$v['item'];
		$aValue['odds_set']=(empty($v['otype']))?'N':$v['otype'];
		$aValue['value_bef']=$v['bf'];
		$aValue['value_aft']=$v['af'];
		$sValue=$sVAL;
		foreach($aCol as $k => $col){
			$val=$aValue[$col];		
			if($val == 'null'){
				$sValue=str_replace('"['.$col.']"',$val,$sValue);
			}else{
				$sValue=str_replace("[$col]",$val,$sValue);
			}
		}
		$aVAL[]=$sValue;    
  }
	$sOP_SQL=$sSQL.implode(',',$aVAL);
	$q=$db->sql_query($sOP_SQL);
}
//---其他用途---
//取得兩張表的某個欄位差集
/*
	$talbe_1	= 要被比對的主表
	$talbe_2	= 要比對的副表
	$id_1			=	要被比對的主要欄位
	$id_1			=	要比對的欄位
	
	*將兩張不同的表的某個欄位做差集比對
	
	回傳:[項目,項目,項目]

*/
function get_difference_set($table_1,$table_2,$id_1,$id_2){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT';
	$aSQL[]='[id_1] AS id';
	$aSQL[]='FROM';
	$aSQL[]='[table_1] AS a';
	$aSQL[]='LEFT JOIN';
	$aSQL[]='[table_2] AS b';
	$aSQL[]='ON';
	$aSQL[]='a.[id_1]=b.[id_2]';
	$aSQL[]='WHERE';
	$aSQL[]='b.[id_2]';
	$aSQL[]='IS NULL';
	$aSQL[]='ORDER BY a.[id_1] ASC';
	$sSQL=implode(' ',$aSQL);
	$sSQL=str_replace('[table_1]',$table_1,$sSQL);
	$sSQL=str_replace('[table_2]',$table_2,$sSQL);
	$sSQL=str_replace('[id_1]',$id_1,$sSQL);
	$sSQL=str_replace('[id_2]',$id_2,$sSQL);
	//echo $sSQL;
	$q=$db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$aStr[]=$r['id'];
	}
	return $aStr;
}
?>