<?php
//以CURL執行PHP程式
function exec_cron_cmd($sProgram,$iExeced_time){
	global $base_url;
	$cron_cmd=$base_url.$sProgram.'?_='.(time()).'&times='.$iExeced_time;
	$user_agent="Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36";
	// echo $cron_cmd."\n";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$cron_cmd);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_USERAGENT , $user_agent);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	$urlstr='';
	// 執行
	$urlstr.=curl_exec($ch);
	// 關閉CURL連線
	//echo $urlstr;
	curl_close($ch);
}
//顯示設定的內容
function show_task($aTask){
	$zst = '==============================================================================='."\n";
	$zca = ' Num  Time                 Program Name                               Run Times'."\r\n";
	$zhr = '----  -------------------  ------------------------------------------ ---------'."\r\n";
	$zlc = "\r\n";
	// ---
	$aEcho = array();
	$aEcho[]=$zca;
	$aEcho[]=$zhr;
	$aEcho[]=$zst;
	$aEcho[]=' CronTrigger v2.0.1 (2016-12-31)                          Author : Macaw Liao'."\n";
	$aEcho[]=$zst;
	$aEcho[]=' Num   Week   Month   Day   Hour   Minute  Sec    File Name'."\n";
	$aEcho[]=' ---   -----  -----  -----  -----  -----  -----   ----------------------------'."\n";
	foreach($aTask as $k => $aTask_row){
		$aEcho[]=str_pad($k+1,4,' ',STR_PAD_LEFT).'  ';
		$aEcho[]=str_pad($aTask_row[6],7,' ',STR_PAD_BOTH);
		$aEcho[]=str_pad($aTask_row[5],7,' ',STR_PAD_BOTH);
		$aEcho[]=str_pad($aTask_row[4],7,' ',STR_PAD_BOTH);
		$aEcho[]=str_pad($aTask_row[3],7,' ',STR_PAD_BOTH);
		$aEcho[]=str_pad($aTask_row[2],7,' ',STR_PAD_BOTH);
		$aEcho[]=str_pad($aTask_row[1],7,' ',STR_PAD_BOTH);
		$aEcho[]='  '.$aTask_row[0];
		$aEcho[]="\n";
	}
	$aEcho[]=' ---   -----  -----  -----  -----  -----  -----   ----------------------------'."\n";
	$aEcho[]=$zlc;	
	echo implode('',$aEcho);
}
//讀取文字檔為字串
/*
	回傳:設定檔內容
*/
function getTxt($_path){
	$handle = fopen($_path, 'r');
	$tempstr= '';
	if($handle){
		$lk=0;
		while(!feof($handle)){
			$ere = fgets($handle);
			if( substr($ere,0,1) != '#' ){
				$tempstr.= $ere;
			}
		}
	}
	fclose($handle);
	return $tempstr;
}
//將文字的設定值轉換為陣列
/*
	回傳[]{
		執行程式
		,秒
		,分
		,時
		,日
		,月
		,周
	}
*/
function getSetup($_str){
	$pat = '(\*\/[0-9]+|\*\+[0-9]+|\*|[0-9]+\-[0-9]+|[0-9]+)';
	$pattern = '/[^\/]'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.'(\S+)'.'/';
	preg_match_all($pattern, $_str ,$matches);
	//轉換陣列排列
	$Rule = Array();
	for($i=0;$i<count($matches[0]);$i++){
		$Rule[$i] = Array(	
			$matches[7][$i],
			$matches[1][$i],
			$matches[2][$i],
			$matches[3][$i],
			$matches[4][$i],
			$matches[5][$i],
			$matches[6][$i]
		);
	}
	return $Rule;
}
//將Task文字轉成Task陣列
/*
	$sRules=規則
	回傳[]=[
		 程式名稱
		,秒
		,分
		,時
		,日
		,月
		,週
	]
*/
function getTaskAry($sRules){
	$aPat = array();
	$aPat[] = '\*\/[0-9]+\+[0-9]+';// */N+M
	$aPat[] = '\*\/[0-9]+';// */N
	$aPat[] = '\*\+[0-9]+';// *+N
	$aPat[] = '\*';// *
	$aPat[] = '[0-9]+\-[0-9]+';// N-M
	$aPat[] = '[0-9]+';// N-M
	// $sPat = '(\*\/[0-9]+|\*\+[0-9]+|\*|[0-9]+\-[0-9]+|[0-9]+)';
	$sPat = '('.implode('|',$aPat).')';
	$aPattern   = array();
	// $aPattern[] = '/[^\/]';
	$aPattern[] = '/';
	for($i=0;$i<6;$i++){
		$aPattern[] = $sPat.'\s+';
	}
	$aPattern[] ='(\S+)'.'/';
	$sPattern = implode('',$aPattern);	
	// $sPattern = '/[^\/]'.$sPat.'\s+'.$sPat.'\s+'.$sPat.'\s+'.$sPat.'\s+'.$sPat.'\s+'.$sPat.'\s+'.'(\S+)'.'/';
	// echo $sPattern."\n";
	preg_match_all($sPattern, $sRules ,$matches);	
	// print_r($matches);
	//轉換陣列排列
	$Rule = Array();
	$iCnt=count($matches[0]);
	for($i=0;$i<$iCnt;$i++){
		$Rule[$i] = Array(	
			$matches[7][$i],
			$matches[1][$i],
			$matches[2][$i],
			$matches[3][$i],
			$matches[4][$i],
			$matches[5][$i],
			$matches[6][$i]
		);
	}
	return $Rule;
}
//取得目前時間
/*
	回傳:{
		 0
		,秒
		,分
		,時
		,日
		,月
		,年
		,星期
	}
	---
	*為對應後面判斷是否要執行的程式
*/
function getNow(){
	$today = getdate();
	$curtime = Array(	
		0
		,$today['seconds']
		,$today['minutes']
		,$today['hours']
		,$today['mday']
		,$today['mon']
		,$today['year']
		,$today['wday']		
	);
	return $curtime;
}
//取得每個單位的時戳
function conTime($_V=0,$_P=1){
	$Y = 1970;	$M = 1;		$D = 1;
	$H = 0;		$I = 0;		$S = 0;
	switch ($_P){
		case 1:	$S=$_V;	break;
		case 2:	$I=$_V;	break;
		case 3:	$H=$_V;	break;
		case 4:	$D=$_V;	break;
		case 5:	$M=$_V;	break;
		case 6:	$Y=$_V;	break;
	}
	return mktime($H,$I,$S,$M,$D,$Y);
}
//依照規則計算下次時間
/*
	$Rule[]={
		執行程式
		,秒
		,分
		,時
		,日
		,月
		,周
	}
	回傳[]={
		 程式
		,時戳
		,時間(Y-m-d H:i:s)
		,已經在執行(0/1)
		,次數
		,可以執行(0/1)
	}
	---
	*每一條規則去處理
		*依序處理[秒,分,時,日,月,周]
			*每隔多少單位
			*增加多少單位
			*範圍
			*指定
			*每次
		*進位計算
	---
	*時間已經過去的就不能執行
*/
function setNext($Rule){
	global $timezone;
	$fNowMicro = getTime();//現在時間
	$iNowStmp = floor($fNowMicro);//現在時戳
	$GMax = Array(0, 59, 59, 23, 31, 12, 9999, 7);
	$curtime = getNow();
	//下次時間陣列
	/*
		[程式,秒,分,時,日,月,年]
	*/
	$NextTime = Array();	
	$MaxTime = Array();		//如遇進位的最大值
	$MinTime = Array();		//如遇進位的最小值
	$TypeTime = Array();	//該單位的數值型態
	// *每一條規則去處理
	for($i=0;$i<count($Rule);$i++){
		$Next = Array($Rule[$i][0]);
		$Max = Array(0);
		$Min = Array(0);
		$Type = Array(0);
		// *依序處理[秒,分,時,日,月,周]
		for($j=1;$j<=5;$j++){
			// 每隔多少單位 (時間/秒數 + 秒數)
			if(preg_match('/\*\/[0-9]+\+[0-9]+/',$Rule[$i][$j])){
				$Si = explode('+', $Rule[$i][$j]);
				$Sp = $Si[1];//加上幾秒
				$Si = explode('/', $Si[0]);
				$Se = $Si[1];//除數
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se)) + $Sp;
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/+';
				continue;
			}
			if(preg_match('/\*\/[0-9]+/',$Rule[$i][$j])){
				// 每隔多少單位 (時間/秒數)
				$Si = explode('/', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se));
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/';
				continue;
			}
			if(preg_match('/\*\+[0-9]+/',$Rule[$i][$j])){
				// 增加多少單位 (時間+秒數)
				$Si = preg_split('\+', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + $Se;
				$Max[] = $GMax[$j];
				$Min[] = 0;
				$Type[] = '+';
				continue;
			}
			if(preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][$j])){
				// 範圍 (最小-最大)
				$Si = explode('-', $Rule[$i][$j]);
				$vMin= ($Si[0]<$Si[1])?$Si[0]:$Si[1];
				$vMax= ($Si[0]>$Si[1])?$Si[0]:$Si[1];
				if( ($curtime[$j] < $vMin) || ($curtime[$j] > $vMax) ){
					$Next[] = $vMin;
					$Max[] = $vMax;
					$Min[] = $vMin;
				}else {
					$Next[] = $curtime[$j];
					$Max[] = $vMax;
					$Min[] = $vMin;
				}
				$Type[] = '-';
				continue;
			}
			if(preg_match('/[0-9]+/',$Rule[$i][$j])){
				// 指定
				$Next[] = $Rule[$i][$j];
				$Max[] = $Rule[$i][$j];
				$Min[] = $Rule[$i][$j];
				$Type[] = '#';
				continue;
			}
			if($Rule[$i][$j]='*'){
				// 每次 (現在)
				$Next[] = $curtime[$j];
				$Max[] = $GMax[$j];
				$Min[] = $curtime[$j];
				$Type[] = '*';
			}
		}
		$Next[] = $curtime[6];
		$NextTime[] = $Next;
		$MaxTime[] = $Max;
		$MinTime[] = $Min;
		$TypeTime[] = $Type;
	}
	// *進位計算
	$stTime = Array();
	for($i=0;$i<count($NextTime);$i++){
		$ts=0;
		for($j=5;$j>0;$j--){
			if($NextTime[$i][$j]>=$curtime[$j]){continue;}
			//判斷是否超過自己最大值
			if($MaxTime[$i][$j]>$curtime[$j]){
				//把自己往上加
				while($NextTime[$i][$j]<$curtime[$j]){
					$NextTime[$i][$j]++; 
				}
			}else {
				//檢查上一級是否有更大的
				for($k=$j+1;$k<7;$k++){
					if($NextTime[$i][$k]>$curtime[$k]){
						break 2;
					}
				}
				//檢查上一級是否可以加
				for($k=$j+1;$k<7;$k++){
					//上一級的最大值檢查
					if($NextTime[$i][$k]<$MaxTime[$i][$k]){
						$NextTime[$i][$k]++;
						//加完後把下級全部設為最小
						for($l=$k-1;$l>0;$l--){
							$NextTime[$i][$l] = $MinTime[$i][$l];
						}
						break 2;
					}
				}
			}
		}
		//加總為真正時戳
		for($j=1;$j<7;$j++){ $ts+= conTime($NextTime[$i][$j],$j); }
		//時差值
		$ts-= ($timezone*5);
    //120903 修正 跨月錯誤
    $ts=conTime2($NextTime[$i]);
		//判斷今天是否可執行 (週)
		//算出來的日期為周幾
		$canRun  = 0;
		$runWeek = date("w",$ts);
		if(preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][6])){
			//範圍
			$Wi = explode('-', $Rule[$i][6]);
			$Wi[0]=$Wi[0]%7;
			$Wi[1]=$Wi[1]%7;
			$WMin= ($Wi[0]<$Wi[1])?$Wi[0]:$Wi[1];
			$WMax= ($Wi[0]>$Wi[1])?$Wi[0]:$Wi[1];
			if($runWeek>=$WMin && $runWeek<=$WMax){
				$canRun = 1;
			}
		}elseif(preg_match('/[0-9]+/',$Rule[$i][6])){
			//指定
			if($runWeek==($Rule[$i][6]%7)){
				$canRun = 1;
			}
		}else {
			$canRun = 1;
		}
		//已經錯過的,就不能執行
		if($ts < $iNowStmp){$canRun=0;}
		//程序, 時戳, 時間, 旗標, 次數
		$stTime[$i][0] = $NextTime[$i][0];
		$stTime[$i][1] = $ts;
		$stTime[$i][2] = date("Y-m-d H:i:s",$ts);
		$stTime[$i][3] = 0;
		$stTime[$i][4] = 0;
		$stTime[$i][5] = $canRun;
	}
	return $stTime;
}
//二維轉文字
function ary2txt($ary){
	$tmpary = Array();
	for($i=0;$i<count($ary);$i++){
		$tmpary[] = "Array(\n\t\t'".join("',\n\t\t'",$ary[$i])."'\n\t\t)";
	}
	return "Array(\n\t".join(",\n\t",$tmpary)."\n)\n";
}
//寫文字檔
function wf($path,$str){
	$f2 = fopen($path,'w');
	fwrite($f2, $str);
	fclose($f2);
}
//取得microtime
function getTime(){
	$microTime = microtime();
	$microTime = explode(" ",$microTime);
	$microTime = $microTime[1] + $microTime[0];
	return $microTime;
}
//轉成時戳
/*
	$Time=[程序,秒,分,時,日,月,年]
*/
function conTime2($Time){
  list($E,$S,$I,$H,$D,$M,$Y)=$Time;
  return mktime($H,$I,$S,$M,$D,$Y);
}
?>