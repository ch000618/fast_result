<?php
//開獎球號的相關函式
//---球路相關---
//送入球號,得到球路陣列
/*
	*每個遊戲一堆玩法
	*每個玩法拿開獎號碼來做判斷
		*單雙,有的有和
		*大小,有的有和
		*東西南北
		*中發白
		*總和單雙,有的有和
		*總和大小,有的有和
		*前後數量誰多,或者和
		*單雙數量誰多,或者和
*/
//處理KLC的
/*
	$aResult=[號碼,號碼,....]
	回傳[玩法編號]=結果
*/
function rmp_pro_resultMap_klc($aResult){
	//中發白
	$ary_cfb=array(
		'C'=>array(1,2,3,4,5,6,7)
		,'F'=>array(8,9,10,11,12,13,14)
		,'B'=>array(15,16,17,18,19,20)
	);
	//方位
	$ary_eswn=array(
		 'E'=>array(1,5,9,13,17)
		,'S'=>array(2,6,10,14,18)
		,'W'=>array(3,7,11,15,19)
		,'N'=>array(4,8,12,16,20)
	);	
	$ret=array();
	//第?球
	for($i=100;$i<108;$i++){
		$ret[$i]=$aResult[$i-100];
	}
	//單雙
	for($i=108;$i<116;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_OE($ret[$i-8]);
	}
	//大小
	$fCenter=9.5;
	for($i=116;$i<124;$i++){
		// echo $i;
		$ret[$i]=rmp_chk_LS($ret[$i-16],$fCenter);
	}
	//尾大小
	$fCenter=4.5;
	for($i=124;$i<132;$i++){
		// echo $i;
		$ret[$i]=rmp_chk_LS(($ret[$i-24]%10),$fCenter);
	}
	//和數單雙
	for($i=132;$i<140;$i++){
		$T=floor($ret[$i-32]/10);
		$U=$ret[$i-32]%10;
		$S=$T+$U;
		//echo "$i,$T,$U,$S \n";
		$ret[$i]=rmp_chk_OE($S);
	}
	$sum=array_sum($aResult);	
	//總和單雙
	$ret[140]=rmp_chk_OE($sum);
	//總和大小
	$fCenter=84;
	$ret[141]=rmp_chk_LS($sum,$fCenter);
	//總和尾大小
	$fCenter=4.5;
	$ret[142]=rmp_chk_LS(($sum%10),$fCenter);	
	//中發白
	$ary_set=$ary_cfb;
	for($i=143;$i<151;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_SET($ret[$i-43],$ary_set);
	}
	//方位
	$ary_set=$ary_eswn;
	for($i=151;$i<159;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_SET($ret[$i-51],$ary_set);
	}
	//龍虎
	$D2T=array(
		'100'=>'107'
		,'101'=>'106'
		,'102'=>'105'
		,'103'=>'104'
	);
	for($i=159;$i<163;$i++){
		//echo "$i\n";
		$iNumD=$ret[$i-59];
		$iNumT=$ret[$D2T[$i-59]];
		$ret[$i]=rmp_chk_DT($iNumD,$iNumT);
	}
	//print_r($ret);
	return $ret;
}
//處理SSC的
/*
	$aResult=[號碼,號碼,....]
	回傳[玩法編號]=結果
*/
function rmp_pro_resultMap_ssc($aResult){
	//第?球
	for($i=200;$i<205;$i++){
		$ret[$i]=$aResult[$i-200];
	}
	//總和單雙
	$sum=array_sum($aResult);	
	$ret[205]=rmp_chk_OE($sum);
	$fCenter=22.5;
	$ret[206]=rmp_chk_LS($sum,$fCenter);
	$iNumD=$ret[200];
	$iNumT=$ret[204];
	$chk_DT=rmp_chk_DT($iNumD,$iNumT);
	$ret[207]=($chk_DT=='D')?$chk_DT:'P';
	$ret[208]=($chk_DT=='T')?$chk_DT:'P';
	$ret[209]=($chk_DT=='B')?$chk_DT:'P';
	//前三 中三 後三
	$ft_dice=rmp_chk_dice(0,9,$ret[200],$ret[201],$ret[202]);
	$md_dice=rmp_chk_dice(0,9,$ret[201],$ret[202],$ret[203]);
	$bk_dice=rmp_chk_dice(0,9,$ret[202],$ret[203],$ret[204]);
	//更簡單的寫法
	$array=array(
			'ft_dice'=>array(222,219,216,213,210)
			,'md_dice'=>array(223,220,217,214,211)
			,'bk_dice'=>array(224,221,218,215,212)
	);
		for($i=210;$i<225;$i++){
			$ret[$i]='P';
		}
		$iPlay=$array['ft_dice'][$ft_dice];
		$ret[$iPlay]=$ft_dice;
		$iPlay=$array['md_dice'][$md_dice];
		$ret[$iPlay]=$md_dice;		
		$iPlay=$array['bk_dice'][$bk_dice];
		$ret[$iPlay]=$bk_dice;
	for($i=225;$i<230;$i++){
		$ret[$i]=rmp_chk_OE($ret[$i-25]);
	}
	$fCenter=22.5;
	for($i=230;$i<235;$i++){
		$fCenter=4.5;
		$ret[$i]=rmp_chk_LS($ret[$i-30],$fCenter);
	}
	return $ret;
}
//處理PK的
/*
	$aResult=[號碼,號碼,....]
	回傳[玩法編號]=結果
*/
function rmp_pro_resultMap_pk($aResult){
	//第?名
	for($i=300;$i<310;$i++){
		$ret[$i]=$aResult[$i-300];
	}
	//1到10名大小
	for($i=310;$i<320;$i++){
		$fCenter=5.5;
		$ret[$i]=rmp_chk_LS($ret[$i-10],$fCenter);
	}
	//1到10單雙
	for($i=320;$i<330;$i++){
		$ret[$i]=rmp_chk_OE($ret[$i-20]);
	}
	$sum=($aResult[0]+$aResult[1]);	
	$ret[330]=rmp_chk_DT($ret[300],$ret[309]);
	$ret[331]=rmp_chk_DT($ret[301],$ret[308]);
	$ret[332]=rmp_chk_DT($ret[302],$ret[307]);
	$ret[333]=rmp_chk_DT($ret[303],$ret[306]);
	$ret[334]=rmp_chk_DT($ret[304],$ret[305]);
	$fCenter=11.5;
	//冠亞大小
	$ret[335]=rmp_chk_LS($sum,$fCenter);
	//冠亞單雙
	$ret[336]=rmp_chk_OE($sum);
	//冠亞組合
	$ret[337]=$sum;
	return $ret;
}
//處理NC的
/*
	$aResult=[號碼,號碼,....]
	回傳[玩法編號]=結果
*/
function rmp_pro_resultMap_nc($aResult){
	//中發白
	$ary_cfb=array(
		'C'=>array(1,2,3,4,5,6,7)
		,'F'=>array(8,9,10,11,12,13,14)
		,'B'=>array(15,16,17,18,19,20)
	);
	//方位
	$ary_eswn=array(
		 'E'=>array(1,5,9,13,17)
		,'S'=>array(2,6,10,14,18)
		,'W'=>array(3,7,11,15,19)
		,'N'=>array(4,8,12,16,20)
	);	
	$ret=array();
	//第?球
	for($i=100;$i<108;$i++){
		$ret[$i]=$aResult[$i-100];
	}
	//單雙
	for($i=108;$i<116;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_OE($ret[$i-8]);
	}
	//大小
	$fCenter=9.5;
	for($i=116;$i<124;$i++){
		// echo $i;
		$ret[$i]=rmp_chk_LS($ret[$i-16],$fCenter);
	}
	//尾大小
	$fCenter=4.5;
	for($i=124;$i<132;$i++){
		// echo $i;
		$ret[$i]=rmp_chk_LS(($ret[$i-24]%10),$fCenter);
	}
	//和數單雙
	for($i=132;$i<140;$i++){
		$T=floor($ret[$i-32]/10);
		$U=$ret[$i-32]%10;
		$S=$T+$U;
		//echo "$i,$T,$U,$S \n";
		$ret[$i]=rmp_chk_OE($S);
	}
	$sum=array_sum($aResult);	
	//總和單雙
	$ret[140]=rmp_chk_OE($sum);
	//總和大小
	$fCenter=84;
	$ret[141]=rmp_chk_LS($sum,$fCenter);
	//總和尾大小
	$fCenter=4.5;
	$ret[142]=rmp_chk_LS(($sum%10),$fCenter);	
	//中發白
	$ary_set=$ary_cfb;
	for($i=143;$i<151;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_SET($ret[$i-43],$ary_set);
	}
	//方位
	$ary_set=$ary_eswn;
	for($i=151;$i<159;$i++){
		//echo "$i\n";
		$ret[$i]=rmp_chk_SET($ret[$i-51],$ary_set);
	}
	//龍虎
	$D2T=array(
		'100'=>'107'
		,'101'=>'106'
		,'102'=>'105'
		,'103'=>'104'
	);
	for($i=159;$i<163;$i++){
		//echo "$i\n";
		$iNumD=$ret[$i-59];
		$iNumT=$ret[$D2T[$i-59]];
		$ret[$i]=rmp_chk_DT($iNumD,$iNumT);
	}
	//print_r($ret);
	return $ret;
}
//處理KB的
/*
	$aResult=[號碼,號碼,....]
	回傳[玩法編號]=結果
*/
function rmp_pro_resultMap_kb($aResult){
	//總和
	$iTotal=array_sum($aResult);
	//判斷單多雙多
	$oe_more=ball_oe_more($aResult);
	//判斷前多後多
	$fb_more=ball_fb_more($aResult);
	$fCenter=810;
	$chk_LS_iTotal=rmp_chk_LS($iTotal,$fCenter);
	//總和大小
	$ret[501]=($chk_LS_iTotal=='L' or $chk_LS_iTotal=='S')?$chk_LS_iTotal:'P';
	//總和單雙
	$ret[502]=rmp_chk_OE($iTotal);
	//總和(和局)
	$ret[503]=($chk_LS_iTotal=='D')?$chk_LS_iTotal:'P';
	//總和大單雙
	$ret[505]=($chk_LS_iTotal=='L')?$chk_LS_iTotal.rmp_chk_OE($iTotal):'P';
	//總和小單雙
	$ret[506]=($chk_LS_iTotal=='S')?$chk_LS_iTotal.rmp_chk_OE($iTotal):'P';
	//前後多,和
	$ret[507]=$fb_more;
	//單雙,和
	$ret[508]=$oe_more;
	//五行
	$ret[509]=rmp_chk_five($iTotal);
	//總和
	$ret[510]=$iTotal;
	return $ret;
}
//判斷單雙
/*
	$iNum=號碼
	回傳:
		O:單
		E:雙
*/
function rmp_chk_OE($iNum){
	//echo "rmp_chk_OE($iNum)\n";
	return ($iNum%2==0)?'E':'O';
}
//判斷五行
/*
	$iTotal=總和
	回傳:
		1:金
		2:木
		3:水
		4:火
		5:土
*/
function rmp_chk_five($iTotal){
	$Metal = array("options"=>array("min_range"=>210, "max_range"=>695));
	$Wood = array("options"=>array("min_range"=>696, "max_range"=>763));
	$Water = array("options"=>array("min_range"=>764, "max_range"=>855));
	$Fire = array("options"=>array("min_range"=>856, "max_range"=>923));
	$Earth = array("options"=>array("min_range"=>924, "max_range"=>1410));
	if(filter_var($iTotal, FILTER_VALIDATE_INT,$Metal)!=false){$ret='1';}
	if(filter_var($iTotal, FILTER_VALIDATE_INT,$Wood)!=false){$ret='2';}
	if(filter_var($iTotal, FILTER_VALIDATE_INT,$Water)!=false){$ret='3';}
	if(filter_var($iTotal, FILTER_VALIDATE_INT,$Fire)!=false){$ret='4';}
	if(filter_var($iTotal, FILTER_VALIDATE_INT,$Earth)!=false){$ret='5';}
	return $ret;
}
//判斷號碼是 前多 後多 前後和
/*
傳入
	[
	號碼1,號碼2,號碼3.............
	]
回傳 
 結果
初始化 
	40以前的號碼個數 
	40以後的號碼個數
判斷號碼有沒有在 範圍內
	40以前
		40以前的號碼個數+1
	40以後
		40以後的號碼個數+1
判斷事前多還是後多
	40以前>40以後 前多
	40以後>40以前 後多
	40以前=40以後 前後和
*/
function ball_fb_more($ary){
	$f=0;
	$b=0;
	foreach($ary as $key => $value){
		if($value>=1 && $value<=40){$f=$f+1;}
		if($value>=41 && $value<=80){$b=$b+1;}
	}
	if($f>$b){$ret="F";}
	if($b>$f){$ret="B";}
	if($b==$f){$ret="D";}
	return $ret;
}
//判斷號碼是 單多 雙多 單雙和
/*
傳入
	[
	號碼1,號碼2,號碼3.............
	]
回傳 
 結果
初始化 
	單數號碼個數 
	雙數號碼個數
判斷號碼有沒有在 範圍內
	跟1整除
		單數號碼個數+1
	跟2整除
		雙數的號碼個數+1
判斷事單多還是雙多
	單>雙 單多
	雙>單 雙多
	單=雙 單雙和
*/
function ball_oe_more($ary){
	$o=0;
	$e=0;
	foreach($ary as $key => $value){
		if($value%2==0){$e=$e+1;}
		else{$o=$o+1;}
	}
	if($o>$e){$ret="O";}
	if($e>$o){$ret="E";}
	if($o==$e){$ret="D";}
	return $ret;
}
//判斷大小
/*
	$iNum=號碼,$fCenter=中間點
	回傳:
		L:大
		S:小
		D:平手
*/
function rmp_chk_LS($iNum,$fCenter){
	//echo "rmp_chk_LS($iNum,$fCenter)\n";
	if(($iNum-$fCenter)==0){return 'D';}
	return ($iNum < $fCenter)?'S':'L';
}
//判斷是在哪個集合
/*
	$iNum=號碼,$aNumSet=集合
	集合[結果]=[號碼,號碼,.....]
	*方位
	*中發白
	回傳:
		false=錯誤
		值=哪個集合
*/
function rmp_chk_SET($iNum,$aNumSet){
	$ret=false;
	if(count($aNumSet)<1){return $ret;}
	foreach($aNumSet as $set => $values){
		if(!in_array($iNum,$values)){continue;}
		$ret=$set;
	}
	return $ret;
}
//判斷龍虎和
/*
	$iNumD=龍,$iNumT=虎
	D:龍
	T:虎
	B:和
*/
function rmp_chk_DT($iNumD,$iNumT){
	//echo "$iNumD,$iNumT\n";
	if($iNumD == $iNumT){return 'B';}
	return ($iNumD > $iNumT)?'D':'T';
}
//判斷骰子
/*
	$iNumS=最小的號碼,$iNumE=最大的號碼
	,$iNum1=號碼1,$iNum2=號碼2,$iNum3=號碼3
	4:豹子,三個都一樣
	3:順子,三個號碼各差一
	2:對子,兩個一樣
	1:半順,兩個差一
	0:雜六,以上皆非
*/
function rmp_chk_dice($iNumS,$iNumE,$iNum1,$iNum2,$iNum3){
	$aNum=array($iNum1,$iNum2,$iNum3);
	sort($aNum);	
	$aVcnt=array();
	//出現的號碼數
	foreach($aNum as $k => $v){
		if(!isset($aVcnt[$v])){$aVcnt[$v]=1;}
		$aVcnt[$v]++;
	}
	$iCnt=count($aVcnt);
	if($iCnt == 1){return 4;}//豹子
	if($iCnt == 2){return 2;}//對子
	//順子與半順	
	$kV=array_keys($aVcnt);//出現的數字
	$iCSE=0;//頭尾的數量
	/*
	 012
	 901
	 890
	*/
	if(in_array($iNumS,$kV)){$iCSE++;}
	if(in_array($iNumE,$kV)){$iCSE++;}
	sort($kV);
	$dcnt=0;//兩兩相差的數量
	$c=count($kV);
	for($i=0;$i<$c;$i++){
		if($i==0){continue;}
		if(abs($kV[$i]-$kV[$i-1])==1){$dcnt++;}
	}
	if($dcnt==2){return 3;}//順子
	if($iCSE==2 && (in_array(($iNumE-1),$kV) || in_array(($iNumS+1),$kV))){return 3;}//順子
	if($dcnt==1){return 1;}//半順
	if($iCSE==2 && $dcnt==0){return 1;}//半順
	return 0;//雜六
}
//資料庫
//檢查重複球路
function roadmap_chk_repeat($sGame,$sRpt_date,$iDate_sn){
	global $db_s;
	$db_s->fetch_type='ASSOC';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='date_sn';
	$SQL[]='FROM draws_'.$sGame.'_roadmap';
	$SQL[]='WHERE';
	$SQL[]='rpt_date ="[date]"';
	$SQL[]='AND date_sn ='.$iDate_sn;
	$sql=implode(' ',$SQL);
	$sql=str_replace('[date]',$sRpt_date,$sql);
	$q=$db_s->sql_query($sql);
	while($r=$db_s->nxt_row()){
		$ret=$r['date_sn'];
	}
	//print_r($ret);
	return $ret;
}
function inst_new_roadmap($sGame,$aResultMap){
	global $db;
	$col=array(
		'rpt_date','date_sn'
		,'ptype','result','times'
	);
	$SQL[]='INSERT INTO draws_[game]_roadmap ([cols])';
	$SQL[]='VALUES';
	foreach($aResultMap as $k => $v){
		$VALUES[]="'$v'";
	}
	$SQL[]="(";
	$SQL[]=implode(",",$VALUES);
	$SQL[]=")";
	$SQL=str_replace('[cols]',implode(',',$col),$SQL);
	$SQL=str_replace('[game]',$sGame,$SQL);
	$sql=implode(' ',$SQL);
	//echo $sql;
	$q=$db->sql_query($sql);
}
//刪除當日同一期的重複的球路
function del_repeat_roadmap($sGame,$sRpt_date,$iDate_sn){
	global $db;
	$SQL=array();
	$SQL[]='DELETE';
	$SQL[]='FROM draws_'.$sGame.'_roadmap';
	$SQL[]='WHERE';
	$SQL[]='rpt_date ="[date]"';
	$SQL[]='AND date_sn ="[iDate_sn]"';
	$SQL=str_replace('[date]',$sRpt_date,$SQL);
	$SQL=str_replace('[iDate_sn]',$iDate_sn,$SQL);
	$sql=implode(' ',$SQL);
	//echo $sql;
	$q=$db->sql_query($sql);
}
//用當天日期 當日編號 跟遊戲 找球路表
function sel_chk_roadmap($sGame,$sRpt_date,$iDate_sn){
	global $db_s;
	$db_s->fetch_type='ASSOC';//設定回傳樣式	
	$SQL=array();
	$SQL[]='SELECT';
	$SQL[]='ptype';
	$SQL[]=',result';
	$SQL[]=',times';
	$SQL[]='FROM draws_'.$sGame.'_roadmap';
	$SQL[]='WHERE';
	$SQL[]='date_sn ='.$iDate_sn;
	$sql=implode(' ',$SQL);
	//echo $sql;
	$q=$db_s->sql_query($sql);
	while($r=$db_s->nxt_row()){
		$ret[$r['ptype']]=array($r['result'],$r['times']);
	}
	return $ret;
}
//兩面長龍
/*
	$game = 遊戲玩法
	
	*取得最後一期的日期
	*取得最後一期日期的期數
	*取得兩面長龍排行
	
	回傳:[球號,結果,期數]
	
*/
function get_roadmap($game){
	//取得最後一期的日期
	$table='draws_'.$game.'_roadmap';
	$day=get_last_date($table);
	//取得最後一期日期的期數
	$times=get_last_times($day,$table);
	//取得兩面長龍排行
	$rank=get_roadmap_rank($game,$times,$day,$table);
	return $rank;
}
//取得最後一期的日期
/*
	$table = 要查詢的表格
	*取出該遊戲的報表日期,date_sn最後一期的日期
*/
function get_last_date($table){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT rpt_date';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='ORDER BY rpt_date DESC';
	$aSQL[]='LIMIT 0,1';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret=$r['rpt_date'];
	}
	return $ret;
}
//取得最後一期日期的期數 
/*
	$table=	欲查詢的表格名稱
 ,$day	=	欲查詢的日期
	*取出該遊戲的報表日期,date_sn最後一期的期數
*/
function get_last_times($day,$table){
	global $db_s;
	$aSQL=array();
	$aSQL[]='SELECT date_sn';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[day]"';
	$aSQL[]='ORDER BY date_sn DESC';
	$aSQL[]='LIMIT 0,1';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[day]',$day,$sSQL);
	$result = $db_s->sql_query($sSQL);
	while($r=$db_s->nxt_row('ASSOC')){
		$ret=$r['date_sn'];
	}
	return $ret;
}
//取得兩面長龍排行
/*
	 $game	=	遊戲玩法
	,$times	=	最後一期
	,$day		=	最後一天
	,$table	=	欲查詢的表格名稱
	
	回傳:[球號,結果,期數]
*/
function get_roadmap_rank($game,$times,$day,$table){
	global $db_s;
	$pass_ptype=array(
		 'klc'=>array('100','101','102','103','104','105','106','107')
		,'ssc'=>array('200','201','202','203','204')
		,'pk'=>array('300','301','302','303','304','305','306','307','308','309')
		,'nc'=>array('100','101','102','103','104','105','106','107')
		,'kb'=>array('1')
	);
	
	$aSQL=array();
	$aSQL[]='SELECT result,ptype,times';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[day]"';
	$aSQL[]='AND date_sn="[times]"';
	$aSQL[]='AND times >"[times_min]"';
	$aSQL[]='AND result !="P"';
	$aSQL[]='AND ptype NOT IN("[ptype]")';
	$aSQL[]='ORDER BY times DESC,ptype ASC';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[day]',$day,$sSQL);
	$sSQL=str_replace('[times]',$times,$sSQL);
	$sSQL=str_replace('[times_min]','1',$sSQL);
	$sSQL=str_replace('[ptype]',implode('","',$pass_ptype[$game]),$sSQL);
	// echo $sSQL;
	$result = $db_s->sql_query($sSQL);
	while($row=$db_s->nxt_row('ASSOC')){
		$see[]=$row;
		$ptype=$row['ptype'];
		$result=$row['result'];
		$times=$row['times'];
		$ball=road_map_ball_chart();	
		$type=road_map_type_chart();
		//echo "[$ptype,$result]\n</br>";
		foreach($ball as $key_1 => $ary_2){
			foreach($ary_2[1] as $key_2 =>$value_2){
				if($ptype==$value_2)
				$name=$ary_2[0];
				continue;
			}
		}	
		foreach($type as $key_1 => $ary_2){
			foreach($ary_2['ptypes'] as $key_2 =>$value_2){
				if($ptype==$value_2)
				$r=$ary_2[$result];
				continue;
			}
		}			
		$ret[]=array($name,$r,$times."期");
	}
	return $ret;
}
//兩面長龍對照表-ball
/*
	$game=遊戲玩法
	
	回傳:球號中文名稱
*/
function road_map_ball_chart(){
	$ball=array(
			 array('第1球',array(
					 '108','116','124','132','143','151','159'
					,'225','230'
					)
			)
			,array('第2球',array(
					 '109','117','125','133','144','152','160'
					,'226','231'
					)
			)
			,array('第3球',array(
					 '110','118','126','134','145','153','161'
					,'227','232'
					)
			)
			,array('第4球',array(
					 '111','119','127','135','146','154','162'
					,'228','233'
					)
			)
			,array('第5球',array(
					 '112','120','128','136','147','155'
					,'229','234'
					)
			)			
			,array('第6球',array(
					'113','121','129','137','148','156'
					)
			)
			,array('第7球',array(
					'114','122','130','138','149','157'
					)
			)		
			,array('第8球',array(
					'115','123','131','139','150','158'
					)
			)				
			,array('總和' ,array(
					 '140','141','142'
					,'205','206'
					,'501','502','503'
					)
			)
			,array('前三',array(
					'210','213','216','219','222'
					)
			)
			,array('中三',array(
					'211','214','217','220','223'
					)
			)
			,array('後三',array(
					'212','215','218','221','224'
					)
			)
			,array('冠軍',array('310','320','330'))
			,array('亞軍',array('311','321','331'))
			,array('第三名',array('312','322','332'))
			,array('第四名',array('313','323','333'))
			,array('第五名',array('314','324','334'))
			,array('第六名',array('315','325'))
			,array('第七名',array('316','326'))
			,array('第八名',array('317','327'))
			,array('第九名',array('318','328'))
			,array('第十名',array('319','339'))
			,array('冠亞',array('335','336'))
			,array('總大',array('505'))
			,array('總小',array('506'))
			,array('前後和',array('507'))
			,array('單雙和',array('508'))
			,array('五行',array('509'))
	);
	return $ball;
}
//兩面長龍對照表-type
/*
	$game=遊戲玩法
	回傳:玩法類型
*/
function road_map_type_chart(){
	$ary=array(
		array(
			 'E'=>'雙'
			,'O'=>'單'
			,'ptypes'=>array(
					 '108','109','110','111','112','113','114','115'
					,'225','226','227','228','229'
					,'320','321','322','323','324','325','326','327','328','329'
			)
		)
		,array(
		   'L'=>'大'
		  ,'S'=>'小'
			,'ptypes'=>array(
					 '116','117','118','119','120','121','122','123'
					,'230','231','232','233','234'
					,'310','311','312','313','314','315','316','317','318','319'
			)
		)
		,array(
			'L'=>'尾大'
		 ,'S'=>'尾小'
		 ,'ptypes'=>array(
					 '124','125','126','127','128','129','130','131'
			)	
		)
		,array(
			'E'=>'和數雙'
		 ,'O'=>'和數單'
		 ,'ptypes'=>array(
					 '132','133','134','135','136','137','138','139'
			)	
		)
		,array(
			'E'=>'總和雙'
		 ,'O'=>'總和單'
		 ,'ptypes'=>array(
					 '140'
					,'205'
					,'502'
			)	
		)		
		,array(
			'L'=>'總和大'
		 ,'S'=>'總和小'
		 ,'ptypes'=>array(
					 '141'
					,'206'
					,'501'
			)	
		)	
		,array(
			'L'=>'總和尾大'
		 ,'S'=>'總和尾小'
		 ,'ptypes'=>array(
					 '142'
			)	
		)
		,array(
			'C'=>'中'
		 ,'F'=>'發'
		 ,'B'=>'白'
		 ,'ptypes'=>array(
					 '143','144','145','146','147','148','149','150'
			)	
		)		
		,array(
			'W'=>'西'
		 ,'E'=>'東'
		 ,'S'=>'南'
		 ,'N'=>'北'
		 ,'ptypes'=>array(
					 '151','152','153','154','155','156','157','158'
			)	
		)	
		,array(
			'D'=>'龍'
		 ,'T'=>'虎'
		 ,'B'=>'和'
		 ,'ptypes'=>array(
					  '159','160','161','162'
					 ,'207','208','209'
					 ,'330','331','332','333','334'
			)	
		)
		,array(
			'4'=>'豹子'
		 ,'3'=>'順子'
		 ,'2'=>'對子'
		 ,'1'=>'半順'
		 ,'0'=>'雜六'
		 ,'ptypes'=>array(
						 '210','211','212'
						,'213','214','215'
						,'216','217','218'
						,'219','220','221'
						,'222','223','224'
			)	
		)
		,array(
			 'L'=>'冠亞大'
			,'S'=>'冠亞小'
			,'ptypes'=>array('335')
		)
		,array(
			 'E'=>'冠亞雙'
			,'O'=>'冠亞單'
			,'ptypes'=>array('336')
		)			
		,array(
			'D'=>'總和和局'
		 ,'ptypes'=>array('503')
		)					
		,array(
			'LE'=>'總大雙'
		 ,'LO'=>'總大單'
		 ,'ptypes'=>array('505')
		)						
		,array(
			'SE'=>'總小雙'
		 ,'SO'=>'總小單'
		 ,'ptypes'=>array('506')
		)	
		,array(
			'B'=>'前(多)'
		 ,'F'=>'後(多)'
		 ,'D'=>'前後(和)'
		 ,'ptypes'=>array('507')
		)
		,array(
			'O'=>'單(多)'
		 ,'E'=>'雙(多)'
		 ,'D'=>'單雙(和)'
		 ,'ptypes'=>array('508')
		)
		,array(
			'1'=>'金'
		 ,'2'=>'木'
		 ,'3'=>'水'
		 ,'4'=>'火'
		 ,'5'=>'土'
		 ,'ptypes'=>array('509')
		)				
	);
	return $ary;
}
//遺漏表
/*
$game = 遊戲玩法
	
	*取得最後一期的日期
	*取得最後一期日期的期數
	*取得遺漏資料
	
	回傳:[結果,結果,...]
*/
function get_omission($game,$ball){
	$array=array();
	//pk、kb無遺漏表
	if($game=='pk' || $game=='kb'){return $array;}	
	//取得最後一期的日期
	$table='draws_'.$game.'_drop';
	$day=get_last_date($table);
	//取得最後一期日期的期數
	$times=get_last_times($day,$table);
	//取得遺漏資料
	//klc、nc 遺漏
	if($game=='klc' || $game=='nc'){
		for($i=1;$i<=20;$i++){
			$num=($i<10)?'0'.$i:$i;
			$column[]='num_'.$num;
		}
		$omission=get_omission_data($game,$times,$day,$table,$column);
		for($i=1;$i<=20;$i++){
			$num=($i<10)?'0'.$i:$i;
			$array[]=$omission['num_'.$num];
		}
		return $array;
	}
	if($game=='ssc'){
		for($i=0;$i<=9;$i++){
			$column[]=$ball.'_num'.$i;
		}
		$omission=get_omission_data($game,$times,$day,$table,$column);
		for($i=0;$i<=9;$i++){
			$array[]=$omission[$ball.'_num'.$i];
		}
		return $array;		
	}
	return ;
}
//取得遺漏表資料
/*
	 $game
	,$times
	,$day,$table,$column
*/
function get_omission_data($game,$times,$day,$table,$column){
	global $db_s;	
	$aSQL=array();
	$aSQL[]='SELECT [column]';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[day]"';
	$aSQL[]='AND date_sn="[times]"';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[day]',$day,$sSQL);
	$sSQL=str_replace('[times]',$times,$sSQL);
	$sSQL=str_replace('[column]',implode(',',$column),$sSQL);
	//echo $sSQL;
	$result = $db_s->sql_query($sSQL);
	while($row=$db_s->nxt_row('ASSOC')){
		$ret=$row;
	}			
	return $ret;	
}
//取得出球率
/*	
	 $game				=	遊戲玩法
	,$balls				=	要查詢的球號
	,$ball_max		=	某個遊戲的球數最大值是幾號
	,$number_max	=	某個遊戲的號碼最大值是幾號
	
	*初始化陣列
	*取得最後一期的日期
	*將查詢出來的結果取代原本初始值
	
	回傳:[次數,次數,...]
	
*/
function get_ball_rate($game,$balls,$ball_max,$number_max){
	//初始化陣列
	$column_name='ball';
	$column_name=($game=='pk')?'rank':$column_name;
	$array=array();
	$number_init =($game=='ssc')?0:1;	
	for($ball=1;$ball<=$ball_max;$ball++){
		for($number=$number_init;$number<=$number_max;$number++)
		$array[$game][$ball][$number]=0;
	}

	//取得最後一期的日期
	$ary=array();
	$table='draws_'.$game.'_result';
	$day=get_last_date($table);
	//將查詢出來的結果取代原本初始值
	for($ball=1;$ball<=$ball_max;$ball++){
		$column=$column_name.'_'.$ball;
		$ary=get_ball_rate_data($table,$column,$day);
		for($number=$number_init;$number<=$number_max;$number++){
			if(empty($ary[$ball][$number])){continue;}
			$array[$game][$ball][$number]=$ary[$ball][$number];
		}
	}
	// echo '<xmp>';
	// print_r($array);
	// echo '</xmp>';	
	return $array[$game][$balls];
}
	
//取得出球率資料
/*
	 $table		= 要查詢的資料表名稱
	,$column	=	要取得球號
	,$day			=	欲查詢的日期
	
	回傳:{
		球號:{
			號碼:次數
		}
	}
*/
function get_ball_rate_data($table,$column,$day){
	global $db_s;	
	$aSQL=array();
	$aSQL[]='SELECT [column] AS number';
	$aSQL[]=',count([column]) AS count';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[day]"';
	$aSQL[]='GROUP BY [column]';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[day]',$day,$sSQL);
	$sSQL=str_replace('[column]',$column,$sSQL);
	//echo $sSQL;
	$result = $db_s->sql_query($sSQL);
	while($row=$db_s->nxt_row('ASSOC')){
		$ret_tmp[]=$row;
	}
	 $ret=array();
	foreach($ret_tmp as $key => $value){
		$k=$value['number'];
		$number=substr($column,5,2);
		$ret[$number][$k]=$value['count'];
	}
	/*
	echo '<xmp>';
	print_r($ret);
	echo '</xmp>';
	*/
	return $ret;	
}
//取得出球
/*
	$game	=	遊戲玩法
	$cat	=	畫面上選擇要看的球路
	$ball	=	第幾球
	
	回傳:[]=[項目,次數]
	
	*取得ptype遊戲玩法
	*抓最後一期日期
	*取出球路資料庫資料
	*結構轉換成前台所需要的結構
	*抓顯示前台要的資料
	
*/
function	get_result_map($game,$cat,$ball){
	//echo	"get_result_map($game,cat=$cat,ball=$ball)";
	//取得ptype遊戲玩法
	$ptype=result_chart($game,$cat,$ball);
	//抓最後一期日期
	$table='draws_'.$game.'_roadmap';
	$day=get_last_date($table);
	//取出球路資料庫資料
	$row=get_roadmap_data($table,$day,$ptype);
	//結構轉換成前台所需要的結構
	$array=get_result_map_structure($game,$cat,$row);
	//抓顯示前台要的資料
	for($i=0;$i<=24;$i++){
		$ret[$i]=$array[$i];
	}
	return $ret;
}
//出球中文對照表
function result_map_chart($game,$cat,$old){
	//echo "result_map_chart($game,$cat,$reslut);\n</br>";
	$array=array(
		 'klc'=>array(
				 '08'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'09'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'10'=>array(
					 'S'=>'尾小'
					,'L'=>'尾大'
					,'D'=>'尾和'
				)
				,'11'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'12'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'13'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'14'=>array(
					 'S'=>'尾小'
					,'L'=>'尾大'
					,'D'=>'尾和'
				)
				,'15'=>array(
					 'C'=>'中'
					,'F'=>'發'
					,'B'=>'白'
				)
				,'16'=>array(
					 'W'=>'西'
					,'E'=>'東'
					,'N'=>'北'
					,'S'=>'南'
				)
				,'17'=>array(
					 'D'=>'龍'
					,'T'=>'虎'
					,'B'=>'和'
				)				
		 )
		,'ssc'=>array(
				 '0'=>array()
				,'1'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'2'=>array(
					 'E'=>'雙'
					,'O'=>'單'				
				)		
				,'3'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'4'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'5'=>array(
					 'T'=>'龍'
					,'D'=>'虎'
					,'B'=>'和'
				)
		)
		,'pk'=>array(
				 '13'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'14'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'15'=>array()		
		)
		,'nc'=>array(
				'09'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'08'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'09'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'10'=>array(
					 'S'=>'尾小'
					,'L'=>'尾大'
					,'D'=>'尾和'
				)
				,'11'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'12'=>array(
					 'E'=>'雙'
					,'O'=>'單'
				)
				,'13'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'
				)
				,'14'=>array(
					 'S'=>'尾小'
					,'L'=>'尾大'
					,'D'=>'尾和'
				)
				,'15'=>array(
					 'C'=>'中'
					,'F'=>'發'
					,'B'=>'白'
				)
				,'16'=>array(
					 'W'=>'西'
					,'E'=>'東'
					,'N'=>'北'
					,'S'=>'南'
				)		
				,'17'=>array(
					 'D'=>'龍'
					,'T'=>'虎'
					,'B'=>'和'
				)				
		)
		,'kb'=>array(
				 '13'=>array()				
				,'01'=>array(
					 'S'=>'小'
					,'L'=>'大'
					,'D'=>'和'				
				)				
				,'02'=>array(
					 'E'=>'雙'
					,'O'=>'單'			
				)			
				,'07'=>array(
					 '1'=>'金'
					,'2'=>'木'			
					,'3'=>'水'			
					,'4'=>'火'			
					,'5'=>'土'			
				)		
				,'05'=>array(
					 'B'=>'前'
					,'F'=>'後'			
					,'D'=>'和'			
				)						
				,'06'=>array(
					 'E'=>'雙'
					,'O'=>'單'			
				)					
				// ,'14'=>array(
					 // 'LE'=>'總大雙'
					// ,'LO'=>'總大單'			
					// ,'SE'=>'總小單'			
					// ,'SO'=>'總小雙'			
				// )					
		)
	);
	if($game=='klc' || $game=='nc'){
		for($i=0;$i<=7;$i++){
			$j='0'.$i;
			for($x=1;$x<=20;$x++){
				$array[$game][$j][$x]=$x;
			}
		}
		$zg=29;
		for($x=1;$x<=20;$x++){
				$array[$game][$zg][$x]=$x;
			}		
	}
	if($game=='ssc'){
		for($i=0;$i<=9;$i++){
			$array[$game][0][$i]=$i;
		}
	}	
	if($game=='pk'){
		for($i=3;$i<=19;$i++){
			$array[$game][15][$i]=$i;
		}
	}
	if($game=='kb'){
		for($i=210;$i<=1410;$i++){
			$array[$game][13][$i]=$i;
		}
	}	
	return $array[$game][$cat][$old];
}
//球路結構轉換成前台所需要的結構
/*
	$row=[]:[
		{
			number:球號
			count:次數
		}
	]	
	
	回傳:[]=[
		 [結果,次數]
		,[結果,次數]
		....
		*25
	]
	
	*結構轉換成前台所需要的結構
	*抓出來的資料若不滿25個要補滿
*/
function get_result_map_structure($game,$cat,$row){
	$k=0;
	$k2=count($row)-1;
	$times=1;
	$type='';
	$tran_row=array();
	//結構轉換成前台所需要的結構
	$array=array();
	foreach($row as $key => $value){
		$tran_row[$k2]=$value['result'];
		$k2--;
	}
	/*
	echo '<xmp>';
	print_r($tran_row);
	echo '</xmp>';
	*/
	$k2=count($row)-1;
	for($i=0;$i<$k2;$i++){
		$old=$tran_row[$i];
		$new=result_map_chart($game,$cat,$old);
		//echo "new=$new\n</br>";
		//初始次數
		if($type==''){
			$type=$new;
			$array[$k]=array($type,$times);
		}		
		//連開次數
		else if($type==$new){
			$times=$times+1;
			$array[$k]=array($type,$times);
		}
		//結果變換
		else{
			$k=$k+1;
			$times=1;
			$type=$new;
			$array[$k]=array($type,$times);
		}			
	}
	/*
	foreach($tran_row as $key => $value){
		$old=$value;
		$new=result_map_chart($game,$cat,$old);
		//echo "new=$new\n</br>";
		//初始次數
		if($type==''){
			$type=$new;
			$array[$k]=array($type,$times);
		}		
		//連開次數
		else if($type==$new){
			$times=$times+1;
			$array[$k]=array($type,$times);
		}
		//結果變換
		else{
			$k=$k+1;
			$times=1;
			$type=$new;
			$array[$k]=array($type,$times);
		}	
	}
	*/
	//抓出來的資料若不滿25個要補滿
	if($k<=25){
		for($i=$k+1;$i<=25;$i++){
			$array[$i]=array("",0);
		}	
	}	
	return $array;
}
//取出球路資料庫資料
/*
	$table	=	表格名稱
	$day		=	查詢的日期
	$type		=	[]=[遊戲玩法(ptype,遊戲玩法(ptype,遊戲玩法(ptype)...]
*/
function get_roadmap_data($table,$day,$type){
	global $db_s;	
	$aSQL=array();
	$aSQL[]='SELECT result';
	$aSQL[]='FROM [table]';
	$aSQL[]='WHERE 1';
	$aSQL[]='AND rpt_date="[day]"';
	$aSQL[]='AND ptype IN("[type]")';
	$aSQL[]='AND result!="[result]"';
	$aSQL[]='ORDER BY `date_sn` ASC';
	$sSQL=implode("\n",$aSQL);
	$sSQL=str_replace('[table]',$table,$sSQL);
	$sSQL=str_replace('[day]',$day,$sSQL);
	$sSQL=str_replace('[result]','P',$sSQL);
	$sSQL=str_replace('[type]',implode('","',$type),$sSQL);
	//echo $sSQL;
	$result = $db_s->sql_query($sSQL);
	while($row=$db_s->nxt_row('ASSOC')){
		$ret[]=$row;
	}		
/*	
	echo '<xmp>';
	print_r($ret);
	echo '</xmp>';
	*/
	return $ret;		
	
}
//出球對照表
/*
	$table	=	表格名稱
	$day		=	查詢的日期
	$ball		=	第幾球
	
	回傳:[]=[遊戲玩法(ptype,遊戲玩法(ptype,遊戲玩法(ptype)...]
*/
function result_chart($game,$cat,$ball){
$array=array(
		'klc'=>array(
				 '00'=>array(
						 '00'=>array(100)
						,'01'=>array(100)
						,'02'=>array(100)
						,'03'=>array(100)
						,'04'=>array(100)
						,'05'=>array(100)
						,'06'=>array(100)
						,'07'=>array(100)
					)
				,'01'=>array(
						 '00'=>array(101)
						,'01'=>array(101)
						,'02'=>array(101)
						,'03'=>array(101)
						,'04'=>array(101)
						,'05'=>array(101)
						,'06'=>array(101)
						,'07'=>array(101)
				)
				,'02'=>array(
						 '00'=>array(102)
						,'01'=>array(102)
						,'02'=>array(102)
						,'03'=>array(102)
						,'04'=>array(102)
						,'05'=>array(102)
						,'06'=>array(102)
						,'07'=>array(102)	
				)
				,'03'=>array(
						 '00'=>array(103)
						,'01'=>array(103)
						,'02'=>array(103)
						,'03'=>array(103)
						,'04'=>array(103)
						,'05'=>array(103)
						,'06'=>array(103)
						,'07'=>array(103)				
				)
				,'04'=>array(
						 '00'=>array(104)
						,'01'=>array(104)
						,'02'=>array(104)
						,'03'=>array(104)
						,'04'=>array(104)
						,'05'=>array(104)
						,'06'=>array(104)
						,'07'=>array(104)				
				)
				,'05'=>array(
						 '00'=>array(105)
						,'01'=>array(105)
						,'02'=>array(105)
						,'03'=>array(105)
						,'04'=>array(105)
						,'05'=>array(105)
						,'06'=>array(105)
						,'07'=>array(105)				
				)
				,'06'=>array(
						 '00'=>array(106)
						,'01'=>array(106)
						,'02'=>array(106)
						,'03'=>array(106)
						,'04'=>array(106)
						,'05'=>array(106)
						,'06'=>array(106)
						,'07'=>array(106)				
				)
				,'07'=>array(
						 '00'=>array(107)
						,'01'=>array(107)
						,'02'=>array(107)
						,'03'=>array(107)
						,'04'=>array(107)
						,'05'=>array(107)
						,'06'=>array(107)
						,'07'=>array(107)								
				)
				,'08'=>array(
						 '00'=>array(108)
						,'01'=>array(109)
						,'02'=>array(110)
						,'03'=>array(111)
						,'04'=>array(112)
						,'05'=>array(113)
						,'06'=>array(114)
						,'07'=>array(115)
				)
				,'09'=>array(
						 '00'=>array(116)
						,'01'=>array(117)
						,'02'=>array(118)
						,'03'=>array(119)
						,'04'=>array(120)
						,'05'=>array(121)
						,'06'=>array(122)
						,'07'=>array(123)				
				)
				,'10'=>array(
						 '00'=>array(124)
						,'01'=>array(125)
						,'02'=>array(126)
						,'03'=>array(127)
						,'04'=>array(128)
						,'05'=>array(129)
						,'06'=>array(130)
						,'07'=>array(131)				
				)
				,'11'=>array(
						 '00'=>array(132)
						,'01'=>array(133)
						,'02'=>array(134)
						,'03'=>array(135)
						,'04'=>array(136)
						,'05'=>array(137)
						,'06'=>array(138)
						,'07'=>array(139)				
				)
				,'12'=>array(
						 '00'=>array(140)
						,'01'=>array(140)
						,'02'=>array(140)
						,'03'=>array(140)
						,'04'=>array(140)
						,'05'=>array(140)
						,'06'=>array(140)
						,'07'=>array(140)				
				)		
				,'13'=>array(
						 '00'=>array(141)
						,'01'=>array(141)
						,'02'=>array(141)
						,'03'=>array(141)
						,'04'=>array(141)
						,'05'=>array(141)
						,'06'=>array(141)
						,'07'=>array(141)				
				)		
				,'14'=>array(
						 '00'=>array(142)
						,'01'=>array(142)
						,'02'=>array(142)
						,'03'=>array(142)
						,'04'=>array(142)
						,'05'=>array(142)
						,'06'=>array(142)
						,'07'=>array(142)				
				)		
				,'15'=>array(
						 '00'=>array(143)
						,'01'=>array(144)
						,'02'=>array(145)
						,'03'=>array(146)
						,'04'=>array(147)
						,'05'=>array(148)
						,'06'=>array(149)
						,'07'=>array(150)				
				)
				,'16'=>array(
						 '00'=>array(151)
						,'01'=>array(152)
						,'02'=>array(153)
						,'03'=>array(154)
						,'04'=>array(155)
						,'05'=>array(156)
						,'06'=>array(157)
						,'07'=>array(158)				
				)				
				,'17'=>array(
						 '00'=>array(159)
						,'01'=>array(160)
						,'02'=>array(161)
						,'03'=>array(162)
						,'04'=>array(163)
						,'05'=>array(164)
						,'06'=>array(165)
						,'07'=>array(166)				
				)
		)
		,'ssc'=>array(
				 '0'=>array(
						 '1'=>array(200)
						,'2'=>array(201)
						,'3'=>array(202)
						,'4'=>array(203)
						,'5'=>array(204)
					)
				 ,'1'=>array(
						 '1'=>array(230)
						,'2'=>array(231)
						,'3'=>array(232)
						,'4'=>array(233)
						,'5'=>array(234)
					)
				 ,'2'=>array(
						 '1'=>array(225)
						,'2'=>array(226)
						,'3'=>array(227)
						,'4'=>array(228)
						,'5'=>array(229)
					)
				 ,'3'=>array(
						 '1'=>array(206)
						,'2'=>array(206)
						,'3'=>array(206)
						,'4'=>array(206)
						,'5'=>array(206)
					)
				 ,'4'=>array(
						 '1'=>array(205)
						,'2'=>array(205)
						,'3'=>array(205)
						,'4'=>array(205)
						,'5'=>array(205)
					)
				 ,'5'=>array(
						 '1'=>array(207,208,209)
						,'2'=>array(207,208,209)
						,'3'=>array(207,208,209)
						,'4'=>array(207,208,209)
						,'5'=>array(207,208,209)
					)
		)
		,'pk'=>array(
				  '13'=>array(
						 '00'=>array(335)
					)
				 ,'14'=>array(
						 '00'=>array(336)
					)
				 ,'15'=>array(
						 '00'=>array(337)
					)							
		)
		,'nc'=>array(
				 '00'=>array(
						 '00'=>array(100)
						,'01'=>array(100)
						,'02'=>array(100)
						,'03'=>array(100)
						,'04'=>array(100)
						,'05'=>array(100)
						,'06'=>array(100)
						,'07'=>array(100)
					)
				,'01'=>array(
						 '00'=>array(101)
						,'01'=>array(101)
						,'02'=>array(101)
						,'03'=>array(101)
						,'04'=>array(101)
						,'05'=>array(101)
						,'06'=>array(101)
						,'07'=>array(101)
				)
				,'02'=>array(
						 '00'=>array(102)
						,'01'=>array(102)
						,'02'=>array(102)
						,'03'=>array(102)
						,'04'=>array(102)
						,'05'=>array(102)
						,'06'=>array(102)
						,'07'=>array(102)	
				)
				,'03'=>array(
						 '00'=>array(103)
						,'01'=>array(103)
						,'02'=>array(103)
						,'03'=>array(103)
						,'04'=>array(103)
						,'05'=>array(103)
						,'06'=>array(103)
						,'07'=>array(103)				
				)
				,'04'=>array(
						 '00'=>array(104)
						,'01'=>array(104)
						,'02'=>array(104)
						,'03'=>array(104)
						,'04'=>array(104)
						,'05'=>array(104)
						,'06'=>array(104)
						,'07'=>array(104)				
				)
				,'05'=>array(
						 '00'=>array(105)
						,'01'=>array(105)
						,'02'=>array(105)
						,'03'=>array(105)
						,'04'=>array(105)
						,'05'=>array(105)
						,'06'=>array(105)
						,'07'=>array(105)				
				)
				,'06'=>array(
						 '00'=>array(106)
						,'01'=>array(106)
						,'02'=>array(106)
						,'03'=>array(106)
						,'04'=>array(106)
						,'05'=>array(106)
						,'06'=>array(106)
						,'07'=>array(106)				
				)
				,'07'=>array(
						 '00'=>array(107)
						,'01'=>array(107)
						,'02'=>array(107)
						,'03'=>array(107)
						,'04'=>array(107)
						,'05'=>array(107)
						,'06'=>array(107)
						,'07'=>array(107)								
				)
				,'08'=>array(
						 '00'=>array(108)
						,'01'=>array(109)
						,'02'=>array(110)
						,'03'=>array(111)
						,'04'=>array(112)
						,'05'=>array(113)
						,'06'=>array(114)
						,'07'=>array(115)
				)
				,'09'=>array(
						 '00'=>array(116)
						,'01'=>array(117)
						,'02'=>array(118)
						,'03'=>array(119)
						,'04'=>array(120)
						,'05'=>array(121)
						,'06'=>array(122)
						,'07'=>array(123)				
				)
				,'10'=>array(
						 '00'=>array(124)
						,'01'=>array(125)
						,'02'=>array(126)
						,'03'=>array(127)
						,'04'=>array(128)
						,'05'=>array(129)
						,'06'=>array(130)
						,'07'=>array(131)				
				)
				,'11'=>array(
						 '00'=>array(132)
						,'01'=>array(133)
						,'02'=>array(134)
						,'03'=>array(135)
						,'04'=>array(136)
						,'05'=>array(137)
						,'06'=>array(138)
						,'07'=>array(139)				
				)
				,'12'=>array(
						 '00'=>array(140)
						,'01'=>array(140)
						,'02'=>array(140)
						,'03'=>array(140)
						,'04'=>array(140)
						,'05'=>array(140)
						,'06'=>array(140)
						,'07'=>array(140)				
				)		
				,'13'=>array(
						 '00'=>array(141)
						,'01'=>array(141)
						,'02'=>array(141)
						,'03'=>array(141)
						,'04'=>array(141)
						,'05'=>array(141)
						,'06'=>array(141)
						,'07'=>array(141)				
				)		
				,'14'=>array(
						 '00'=>array(142)
						,'01'=>array(142)
						,'02'=>array(142)
						,'03'=>array(142)
						,'04'=>array(142)
						,'05'=>array(142)
						,'06'=>array(142)
						,'07'=>array(142)				
				)		
				,'15'=>array(
						 '00'=>array(143)
						,'01'=>array(144)
						,'02'=>array(145)
						,'03'=>array(146)
						,'04'=>array(147)
						,'05'=>array(148)
						,'06'=>array(149)
						,'07'=>array(150)				
				)
				,'16'=>array(
						 '00'=>array(151)
						,'01'=>array(152)
						,'02'=>array(153)
						,'03'=>array(154)
						,'04'=>array(155)
						,'05'=>array(156)
						,'06'=>array(157)
						,'07'=>array(158)				
				)				
				,'17'=>array(
						 '00'=>array(159)
						,'01'=>array(160)
						,'02'=>array(161)
						,'03'=>array(162)
						,'04'=>array(163)
						,'05'=>array(164)
						,'06'=>array(165)
						,'07'=>array(166)				
				)
		)//1.2.5.6.7,13
		//13.1.2.7.5.6
		,'kb'=>array(
				 '01'=>array(
						 '00'=>array(501)			
				)				
				,'02'=>array(
						 '00'=>array(502)			
				)
				,'05'=>array(
						 '00'=>array(507)			
				)
				,'06'=>array(
						 '00'=>array(508)			
				)
				,'07'=>array(
						 '00'=>array(509)			
				)
				,'13'=>array(
						 '00'=>array(510)			
				)		
				// ,'14'=>array(
						 // '00'=>array(505)
						// ,'01'=>array(506)
				// )		
		)
	);
	return $array[$game][$cat][$ball];
}
?>