<?php
//���J�]�w
include_once('../../config/ser_config.php');

//���|�]�w
//define('BASE_PATH', '/home/dev101/sk_java/');
//define('OPEN_HTTP', 'http://220.229.226.59/sk_java/service/');
//define("BASE_PATH", "C:/PHP/sk_java/");

//�}�Һ��}
$base_url = baseUrl;

//�]�w���ɦW
$inipath = path_Trigger.'crontrigger.ini';

//�������ɦW (��X�榡�iExcel�}��)
$logpath = path_Trigger.'crontrigger.csv';

//�\��]�w
$log_enable = 0;		//�g������
$chk_time	= 20000;	//�{���ˬd�ӷP�� (1000000=1��)


//-----------------------------------------------------------------------------
//�p��ɮt��
$timezone = conTime(0,1);

//Ū����r�ɬ��r��
function getTxt($_path) {
	$handle = fopen($_path, 'r');
	$tempstr= '';
	if ($handle) {
		$lk=0;
		while (!feof($handle)) {
			$ere = fgets($handle);
			if ( substr($ere,0,1) != '#' ) {
				$tempstr.= $ere;
			}
		}
	}
	fclose($handle);
	return $tempstr;
}

//�N��r���]�w���ഫ���}�C
function getSetup($_str) {
	$pat = '(\*\/[0-9]+|\*\+[0-9]+|\*|[0-9]+\-[0-9]+|[0-9]+)';
	$pattern = '/[^\/]'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.'(\S+)'.'/';
	preg_match_all($pattern, $_str ,$matches);
	//�ഫ�}�C�ƦC
	$Rule = Array();
	for ($i=0;$i<count($matches[0]);$i++) {
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
function getNow() {
	$today = getdate();
	$curtime = Array(	0,
						$today['seconds'],	$today['minutes'],	$today['hours'],
						$today['mday'],		$today['mon'],		$today['year'],
						$today['wday']		);
	return $curtime;
}

//���o�C�ӳ�쪺���W
function conTime($_V=0,$_P=1) {
	$Y = 1970;	$M = 1;		$D = 1;
	$H = 0;		$I = 0;		$S = 0;
	switch ($_P) {
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
function setNext($Rule) {
	global $timezone;
	
	$GMax = Array(0, 59, 59, 23, 31, 12, 9999, 7);
	
	$curtime = getNow();
	$NextTime = Array();	//�U���ɶ��}�C
	$MaxTime = Array();		//�p�J�i�쪺�̤j��
	$MinTime = Array();		//�p�J�i�쪺�̤p��
	$TypeTime = Array();	//�ӳ�쪺�ƭȫ��A
	for ($i=0;$i<count($Rule);$i++) {
		$Next = Array($Rule[$i][0]);
		$Max = Array(0);
		$Min = Array(0);
		$Type = Array(0);
		for ($j=1;$j<=5;$j++) {
			if (preg_match('/\*\/[0-9]+/',$Rule[$i][$j])) {
				// �C�j�h�ֳ�� (�ɶ�/���)
				$Si = explode('/', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se));
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/';
				continue;
			}
			if (preg_match('/\*\+[0-9]+/',$Rule[$i][$j])) {
				// �W�[�h�ֳ�� (�ɶ�+���)
				$Si = preg_split('\+', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + $Se;
				$Max[] = $GMax[$j];
				$Min[] = 0;
				$Type[] = '+';
				continue;
			}
			if (preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][$j])) {
				// �d�� (�̤p-�̤j)
				$Si = explode('-', $Rule[$i][$j]);
				$vMin= ($Si[0]<$Si[1])?$Si[0]:$Si[1];
				$vMax= ($Si[0]>$Si[1])?$Si[0]:$Si[1];
				if ($curtime[$j] < $vMin or $curtime[$j] > $vMax) {
					$Next[] = $vMin;
					$Max[] = $vMax;
					$Min[] = $vMin;
				} else {
					$Next[] = $curtime[$j];
					$Max[] = $vMax;
					$Min[] = $vMin;
				}
				$Type[] = '-';
				continue;
			}
			if (preg_match('/[0-9]+/',$Rule[$i][$j])) {
				// ���w
				$Next[] = $Rule[$i][$j];
				$Max[] = $Rule[$i][$j];
				$Min[] = $Rule[$i][$j];
				$Type[] = '#';
				continue;
			}
			if ($Rule[$i][$j]='*') {
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
	//�i��p��
	$stTime = Array();
	for ($i=0;$i<count($NextTime);$i++) {
		$ts=0;
		for ($j=5;$j>0;$j--) {
			if ($NextTime[$i][$j]<$curtime[$j]) {
				//�P�_�O�_�W�L�ۤv�̤j��
				if ($MaxTime[$i][$j]>$curtime[$j]) {
					//��ۤv���W�[
					while($NextTime[$i][$j]<$curtime[$j]) {
						$NextTime[$i][$j]++;
					}
				} else {
					//�ˬd�W�@�ŬO�_����j��
					for ($k=$j+1;$k<7;$k++) {
						if ($NextTime[$i][$k]>$curtime[$k]) {
							break 2;
						}
					}
					//�ˬd�W�@�ŬO�_�i�H�[
					for ($k=$j+1;$k<7;$k++) {
						//�W�@�Ū��̤j���ˬd
						if ($NextTime[$i][$k]<$MaxTime[$i][$k]) {
							$NextTime[$i][$k]++;
							//�[�����U�ť����]���̤p
							for ($l=$k-1;$l>0;$l--) {
								$NextTime[$i][$l] = $MinTime[$i][$l];
							}
							break 2;
						}
					}
				}
			}
		}
		//�[�`���u�����W
		for ($j=1;$j<7;$j++) {
			$ts+= conTime($NextTime[$i][$j],$j);
		}

		//�ɮt��
		$ts-= ($timezone*5);
    //120903 �ץ� �����~
    $ts=conTime2($NextTime[$i]);
		//�P�_���ѬO�_�i���� (�g)
		//��X�Ӫ�������P�X
		$canRun  = 0;
		$runWeek = date("w",$ts);
		if (preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][6])) {
			//�d��
			$Wi = explode('-', $Rule[$i][6]);
			$Wi[0]=$Wi[0]%7;
			$Wi[1]=$Wi[1]%7;
			$WMin= ($Wi[0]<$Wi[1])?$Wi[0]:$Wi[1];
			$WMax= ($Wi[0]>$Wi[1])?$Wi[0]:$Wi[1];
			if ($runWeek>=$WMin && $runWeek<=$WMax) {
				$canRun = 1;
			}
		} elseif (preg_match('/[0-9]+/',$Rule[$i][6])) {
			//���w
			if ($runWeek==($Rule[$i][6]%7)) {
				$canRun = 1;
			}
		} else {
			$canRun = 1;
		}
		
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
function ary2txt($ary) {
	$tmpary = Array();
	for ($i=0;$i<count($ary);$i++) {
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
function getTime() {
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
//=============================================================================

//���Jcrontab.txt
$task = getTxt($inipath);
//��r��}�C
$list = getSetup($task);
//���ͷs������Ƶ{
$nexttime = setNext($list);

$zst = '==============================================================================='."\n";
$zca = ' Num  Time                 Program Name                               Run Times'."\r\n";
$zhr = '----  -------------------  ------------------------------------------ ---------'."\r\n";
$zlc = "\r\n";
echo $zca;
echo $zhr;
echo $zst;
echo ' CronTrigger v0.3.1 (2009-08-27)                          Author : Kanzaki Jiya'."\n";
echo $zst;
echo ' Num   Week   Month   Day   Hour   Minute  Sec    File Name'."\n";
echo ' ---   -----  -----  -----  -----  -----  -----   ----------------------------'."\n";
for($i=0;$i<count($list);$i++) {
	echo str_pad($i+1,4,' ',STR_PAD_LEFT).'  ';
	echo str_pad($list[$i][6],7,' ',STR_PAD_BOTH);
	echo str_pad($list[$i][5],7,' ',STR_PAD_BOTH);
	echo str_pad($list[$i][4],7,' ',STR_PAD_BOTH);
	echo str_pad($list[$i][3],7,' ',STR_PAD_BOTH);
	echo str_pad($list[$i][2],7,' ',STR_PAD_BOTH);
	echo str_pad($list[$i][1],7,' ',STR_PAD_BOTH);
	echo '  '.$list[$i][0];
	echo "\n";
}
echo ' ---   -----  -----  -----  -----  -----  -----   ----------------------------'."\n";
echo $zlc;

$eTimes = 0;
$lastshow = '';
//����
while (true) {
	$nowmicro = getTime();
	//�Ƶ{�u�@ - �ɶ����W�L������
	$children = Array();
	for($i=0;$i<count($nexttime);$i++) {
		if ($nexttime[$i][1] < $nowmicro && $nexttime[$i][3] == 0 && $nexttime[$i][5]==1) {
			$nexttime[$i][3] = 1;
			$nexttime[$i][4]++;
			echo str_pad($i+1,4,' ',STR_PAD_LEFT);
			if ($nexttime[$i][2]!=$lastshow) {
				echo str_pad($nexttime[$i][2],21,' ',STR_PAD_LEFT);
			} else {
				echo str_repeat(' ',21);
			}
			echo '  '.str_pad($nexttime[$i][0],42,' ');
			echo str_pad($nexttime[$i][4],10,' ',STR_PAD_LEFT);
			echo "\n";
			
			
			//�Ұ�
			$pid = pcntl_fork();
			if ($pid == -1) {
				echo '�L�k�h�\����';
				exit(1);
			} else if ($pid) {
				$children[] = $pid;
			} else {
				break 2;
			}
			
			if ($log_enable) {
				//����log��
				$logline = Array();
				$logline[] = ($i+1);
				$logline[] = $nexttime[$i][2];
				$logline[] = $nexttime[$i][0];
				$logline[] = $nexttime[$i][4];
				$handle = fopen($logpath, 'a');
				fwrite($handle,join(',',$logline)."\r\n");
				fclose($handle);
			}
			
			$lastshow = $nexttime[$i][2];
			
			if ($eTimes>13) {
				echo $zlc;
				$eTimes=0;
				$lastshow = '';
			} else {
				$eTimes++;
			}
		}
	}
	//���ݰƵ{�ǰ��槹
	$status = null;
	if (isset($pid)) {
		foreach($children as $pid) {
			//posix_kill($pid, SIGTERM);
			pcntl_waitpid($pid, $status);
		}
	}
	
	//���o�U���ɶ�, ���קK���ư���
	$new = setNext($list);
	for($i=0;$i<count($nexttime);$i++) {
		if ($nexttime[$i][3] == 1 && $nexttime[$i][1] != $new[$i][1]) {
			$temptime = $nexttime[$i][4];
			$nexttime[$i] = $new[$i];
			$nexttime[$i][3] = 0;
			$nexttime[$i][4] = $temptime;
		}
	}
	
	//��ܥX�C�����U���ɶ�
	/*
	for($i=0;$i<count($nexttime);$i++) {
		echo("\033[1;1H\033[K");
		echo 'Num Program Name         Execute Time        Schedule Time      '."\n";
		echo '--- -------------------- ------------------- -------------------'."\n";
		echo("\033[".($i+1).";1H\033[K");
		echo str_pad($i+1,3,' ',STR_PAD_LEFT).' ';
		echo str_pad($nexttime[$i][0],20,' ').' ';
		echo '                   '.' ';
		echo $nexttime[$i][2];
	}
	*/
	//��
	usleep($chk_time);
}

//�Ƶ{�Ǧh�u����
if ($pid == -1) {
	echo '�L�k�h�\����';
	exit(1);
} else if ($pid) {
} else {
	$cron_cmd=$base_url.$nexttime[$i][0].'?times='.$nexttime[$i][4];
	// echo $cron_cmd;
	$url = fopen($cron_cmd, 'r');

	$urlstr = '';
	if ($url) {
		while (!feof($url)) {
			$urlstr.= fgets($url, 1024);
		}
	}
	echo $urlstr;

	fclose($url);
	exit(0);
}

?>
