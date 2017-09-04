<?php
//載入設定
include_once('../../config/ser_config.php');

//路徑設定
//define('BASE_PATH', '/home/dev101/sk_java/');
//define('OPEN_HTTP', 'http://220.229.226.59/sk_java/service/');
//define("BASE_PATH", "C:/PHP/sk_java/");

//開啟網址
$base_url = baseUrl;

//設定檔檔名
$inipath = path_Trigger.'crontrigger.ini';

//紀錄檔檔名 (輸出格式可Excel開啟)
$logpath = path_Trigger.'crontrigger.csv';

//功能設定
$log_enable = 0;		//寫紀錄檔
$chk_time	= 20000;	//程式檢查敏感度 (1000000=1秒)


//-----------------------------------------------------------------------------
//計算時差值
$timezone = conTime(0,1);

//讀取文字檔為字串
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

//將文字的設定值轉換為陣列
function getSetup($_str) {
	$pat = '(\*\/[0-9]+|\*\+[0-9]+|\*|[0-9]+\-[0-9]+|[0-9]+)';
	$pattern = '/[^\/]'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.$pat.'\s+'.'(\S+)'.'/';
	preg_match_all($pattern, $_str ,$matches);
	//轉換陣列排列
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
//取得目前時間
function getNow() {
	$today = getdate();
	$curtime = Array(	0,
						$today['seconds'],	$today['minutes'],	$today['hours'],
						$today['mday'],		$today['mon'],		$today['year'],
						$today['wday']		);
	return $curtime;
}

//取得每個單位的時戳
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

//依照規則計算下次時間
function setNext($Rule) {
	global $timezone;
	
	$GMax = Array(0, 59, 59, 23, 31, 12, 9999, 7);
	
	$curtime = getNow();
	$NextTime = Array();	//下次時間陣列
	$MaxTime = Array();		//如遇進位的最大值
	$MinTime = Array();		//如遇進位的最小值
	$TypeTime = Array();	//該單位的數值型態
	for ($i=0;$i<count($Rule);$i++) {
		$Next = Array($Rule[$i][0]);
		$Max = Array(0);
		$Min = Array(0);
		$Type = Array(0);
		for ($j=1;$j<=5;$j++) {
			if (preg_match('/\*\/[0-9]+/',$Rule[$i][$j])) {
				// 每隔多少單位 (時間/秒數)
				$Si = explode('/', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + ($Se-($curtime[$j] % $Se));
				$Max[] = $GMax[$j] + ($Se-($GMax[$j] % $Se));
				$Min[] = 0;
				$Type[] = '/';
				continue;
			}
			if (preg_match('/\*\+[0-9]+/',$Rule[$i][$j])) {
				// 增加多少單位 (時間+秒數)
				$Si = preg_split('\+', $Rule[$i][$j]);
				$Se = $Si[1];
				$Next[] = $curtime[$j] + $Se;
				$Max[] = $GMax[$j];
				$Min[] = 0;
				$Type[] = '+';
				continue;
			}
			if (preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][$j])) {
				// 範圍 (最小-最大)
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
				// 指定
				$Next[] = $Rule[$i][$j];
				$Max[] = $Rule[$i][$j];
				$Min[] = $Rule[$i][$j];
				$Type[] = '#';
				continue;
			}
			if ($Rule[$i][$j]='*') {
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
	//進位計算
	$stTime = Array();
	for ($i=0;$i<count($NextTime);$i++) {
		$ts=0;
		for ($j=5;$j>0;$j--) {
			if ($NextTime[$i][$j]<$curtime[$j]) {
				//判斷是否超過自己最大值
				if ($MaxTime[$i][$j]>$curtime[$j]) {
					//把自己往上加
					while($NextTime[$i][$j]<$curtime[$j]) {
						$NextTime[$i][$j]++;
					}
				} else {
					//檢查上一級是否有更大的
					for ($k=$j+1;$k<7;$k++) {
						if ($NextTime[$i][$k]>$curtime[$k]) {
							break 2;
						}
					}
					//檢查上一級是否可以加
					for ($k=$j+1;$k<7;$k++) {
						//上一級的最大值檢查
						if ($NextTime[$i][$k]<$MaxTime[$i][$k]) {
							$NextTime[$i][$k]++;
							//加完後把下級全部設為最小
							for ($l=$k-1;$l>0;$l--) {
								$NextTime[$i][$l] = $MinTime[$i][$l];
							}
							break 2;
						}
					}
				}
			}
		}
		//加總為真正時戳
		for ($j=1;$j<7;$j++) {
			$ts+= conTime($NextTime[$i][$j],$j);
		}

		//時差值
		$ts-= ($timezone*5);
    //120903 修正 跨月錯誤
    $ts=conTime2($NextTime[$i]);
		//判斷今天是否可執行 (週)
		//算出來的日期為周幾
		$canRun  = 0;
		$runWeek = date("w",$ts);
		if (preg_match('/[0-9]+\-[0-9]+/',$Rule[$i][6])) {
			//範圍
			$Wi = explode('-', $Rule[$i][6]);
			$Wi[0]=$Wi[0]%7;
			$Wi[1]=$Wi[1]%7;
			$WMin= ($Wi[0]<$Wi[1])?$Wi[0]:$Wi[1];
			$WMax= ($Wi[0]>$Wi[1])?$Wi[0]:$Wi[1];
			if ($runWeek>=$WMin && $runWeek<=$WMax) {
				$canRun = 1;
			}
		} elseif (preg_match('/[0-9]+/',$Rule[$i][6])) {
			//指定
			if ($runWeek==($Rule[$i][6]%7)) {
				$canRun = 1;
			}
		} else {
			$canRun = 1;
		}
		
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
function ary2txt($ary) {
	$tmpary = Array();
	for ($i=0;$i<count($ary);$i++) {
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
function getTime() {
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
//=============================================================================

//載入crontab.txt
$task = getTxt($inipath);
//文字轉陣列
$list = getSetup($task);
//產生新的執行排程
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
//反覆
while (true) {
	$nowmicro = getTime();
	//排程工作 - 時間有超過的項目
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
			
			
			//啟動
			$pid = pcntl_fork();
			if ($pid == -1) {
				echo '無法多功執行';
				exit(1);
			} else if ($pid) {
				$children[] = $pid;
			} else {
				break 2;
			}
			
			if ($log_enable) {
				//紀錄log檔
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
	//等待副程序執行完
	$status = null;
	if (isset($pid)) {
		foreach($children as $pid) {
			//posix_kill($pid, SIGTERM);
			pcntl_waitpid($pid, $status);
		}
	}
	
	//取得下次時間, 並避免重複執行
	$new = setNext($list);
	for($i=0;$i<count($nexttime);$i++) {
		if ($nexttime[$i][3] == 1 && $nexttime[$i][1] != $new[$i][1]) {
			$temptime = $nexttime[$i][4];
			$nexttime[$i] = $new[$i];
			$nexttime[$i][3] = 0;
			$nexttime[$i][4] = $temptime;
		}
	}
	
	//顯示出每項的下次時間
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
	//休息
	usleep($chk_time);
}

//副程序多工執行
if ($pid == -1) {
	echo '無法多功執行';
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
