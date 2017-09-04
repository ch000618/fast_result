<?php
//�HCURL����PHP�{��
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
	// ����
	$urlstr.=curl_exec($ch);
	// ����CURL�s�u
	//echo $urlstr;
	curl_close($ch);
}
//��ܳ]�w�����e
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
//Ū����r�ɬ��r��
/*
	�^��:�]�w�ɤ��e
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
//�N��r���]�w���ഫ���}�C
/*
	�^��[]{
		����{��
		,��
		,��
		,��
		,��
		,��
		,�P
	}
*/
function getSetup($_str){
	$pat = '(\*\/[0-9]+|\*\+[0-9]+|\*|[0-9]+\-[0-9]+|[0-9]+)';
	$pattern = '/[^\/]'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.'(\S+)'.'/';
	preg_match_all($pattern, $_str ,$matches);
	//�ഫ�}�C�ƦC
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
//�NTask��r�নTask�}�C
/*
	$sRules=�W�h
	�^��[]=[
		 �{���W��
		,��
		,��
		,��
		,��
		,��
		,�g
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
	//�ഫ�}�C�ƦC
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
//���o�ثe�ɶ�
/*
	�^��:{
		 0
		,��
		,��
		,��
		,��
		,��
		,�~
		,�P��
	}
	---
	*�������᭱�P�_�O�_�n���檺�{��
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
//���o�C�ӳ�쪺���W
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
//�̷ӳW�h�p��U���ɶ�
/*
	$Rule[]={
		����{��
		,��
		,��
		,��
		,��
		,��
		,�P
	}
	�^��[]={
		 �{��
		,���W
		,�ɶ�(Y-m-d H:i:s)
		,�w�g�b����(0/1)
		,����
		,�i�H����(0/1)
	}
	---
	*�C�@���W�h�h�B�z
		*�̧ǳB�z[��,��,��,��,��,�P]
			*�C�j�h�ֳ��
			*�W�[�h�ֳ��
			*�d��
			*���w
			*�C��
		*�i��p��
	---
	*�ɶ��w�g�L�h���N�������
*/
function setNext($Rule){
	global $timezone;
	$fNowMicro = getTime();//�{�b�ɶ�
	$iNowStmp = floor($fNowMicro);//�{�b���W
	$GMax = Array(0, 59, 59, 23, 31, 12, 9999, 7);
	$curtime = getNow();
	//�U���ɶ��}�C
	/*
		[�{��,��,��,��,��,��,�~]
	*/
	$NextTime = Array();	
	$MaxTime = Array();		//�p�J�i�쪺�̤j��
	$MinTime = Array();		//�p�J�i�쪺�̤p��
	$TypeTime = Array();	//�ӳ�쪺�ƭȫ��A
	// *�C�@���W�h�h�B�z
	for($i=0;$i<count($Rule);$i++){
		$Next = Array($Rule[$i][0]);
		$Max = Array(0);
		$Min = Array(0);
		$Type = Array(0);
		// *�̧ǳB�z[��,��,��,��,��,�P]
		for($j=1;$j<=5;$j++){
			// �C�j�h�ֳ�� (�ɶ�/��� + ���)
			if(preg_match('/\*\/[0-9]+\+[0-9]+/',$Rule[$i][$j])){
				$Si = explode('+', $Rule[$i][$j]);
				$Sp = $Si[1];//�[�W�X��
				$Si = explode('/', $Si[0]);
				$Se = $Si[1];//����
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se)) + $Sp;
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/+';
				continue;
			}
			if(preg_match('/\*\/[0-9]+/',$Rule[$i][$j])){
				// �C�j�h�ֳ�� (�ɶ�/���)
				$Si = explode('/', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se));
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/';
				continue;
			}
			if(preg_match('/\*\+[0-9]+/',$Rule[$i][$j])){
				// �W�[�h�ֳ�� (�ɶ�+���)
				$Si = preg_split('\+', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + $Se;
				$Max[] = $GMax[$j];
				$Min[] = 0;
				$Type[] = '+';
				continue;
			}
			if(preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][$j])){
				// �d�� (�̤p-�̤j)
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
				// ���w
				$Next[] = $Rule[$i][$j];
				$Max[] = $Rule[$i][$j];
				$Min[] = $Rule[$i][$j];
				$Type[] = '#';
				continue;
			}
			if($Rule[$i][$j]='*'){
				// �C�� (�{�b)
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
	// *�i��p��
	$stTime = Array();
	for($i=0;$i<count($NextTime);$i++){
		$ts=0;
		for($j=5;$j>0;$j--){
			if($NextTime[$i][$j]>=$curtime[$j]){continue;}
			//�P�_�O�_�W�L�ۤv�̤j��
			if($MaxTime[$i][$j]>$curtime[$j]){
				//��ۤv���W�[
				while($NextTime[$i][$j]<$curtime[$j]){
					$NextTime[$i][$j]++; 
				}
			}else {
				//�ˬd�W�@�ŬO�_����j��
				for($k=$j+1;$k<7;$k++){
					if($NextTime[$i][$k]>$curtime[$k]){
						break 2;
					}
				}
				//�ˬd�W�@�ŬO�_�i�H�[
				for($k=$j+1;$k<7;$k++){
					//�W�@�Ū��̤j���ˬd
					if($NextTime[$i][$k]<$MaxTime[$i][$k]){
						$NextTime[$i][$k]++;
						//�[�����U�ť����]���̤p
						for($l=$k-1;$l>0;$l--){
							$NextTime[$i][$l] = $MinTime[$i][$l];
						}
						break 2;
					}
				}
			}
		}
		//�[�`���u�����W
		for($j=1;$j<7;$j++){ $ts+= conTime($NextTime[$i][$j],$j); }
		//�ɮt��
		$ts-= ($timezone*5);
    //120903 �ץ� �����~
    $ts=conTime2($NextTime[$i]);
		//�P�_���ѬO�_�i���� (�g)
		//��X�Ӫ�������P�X
		$canRun  = 0;
		$runWeek = date("w",$ts);
		if(preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][6])){
			//�d��
			$Wi = explode('-', $Rule[$i][6]);
			$Wi[0]=$Wi[0]%7;
			$Wi[1]=$Wi[1]%7;
			$WMin= ($Wi[0]<$Wi[1])?$Wi[0]:$Wi[1];
			$WMax= ($Wi[0]>$Wi[1])?$Wi[0]:$Wi[1];
			if($runWeek>=$WMin && $runWeek<=$WMax){
				$canRun = 1;
			}
		}elseif(preg_match('/[0-9]+/',$Rule[$i][6])){
			//���w
			if($runWeek==($Rule[$i][6]%7)){
				$canRun = 1;
			}
		}else {
			$canRun = 1;
		}
		//�w�g���L��,�N�������
		if($ts < $iNowStmp){$canRun=0;}
		//�{��, ���W, �ɶ�, �X��, ����
		$stTime[$i][0] = $NextTime[$i][0];
		$stTime[$i][1] = $ts;
		$stTime[$i][2] = date("Y-m-d H:i:s",$ts);
		$stTime[$i][3] = 0;
		$stTime[$i][4] = 0;
		$stTime[$i][5] = $canRun;
	}
	return $stTime;
}
//�G�����r
function ary2txt($ary){
	$tmpary = Array();
	for($i=0;$i<count($ary);$i++){
		$tmpary[] = "Array(\n\t\t'".join("',\n\t\t'",$ary[$i])."'\n\t\t)";
	}
	return "Array(\n\t".join(",\n\t",$tmpary)."\n)\n";
}
//�g��r��
function wf($path,$str){
	$f2 = fopen($path,'w');
	fwrite($f2, $str);
	fclose($f2);
}
//���omicrotime
function getTime(){
	$microTime = microtime();
	$microTime = explode(" ",$microTime);
	$microTime = $microTime[1] + $microTime[0];
	return $microTime;
}
//�ন���W
/*
	$Time=[�{��,��,��,��,��,��,�~]
*/
function conTime2($Time){
  list($E,$S,$I,$H,$D,$M,$Y)=$Time;
  return mktime($H,$I,$S,$M,$D,$Y);
}
?>