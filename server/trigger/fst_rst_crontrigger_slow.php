<?php
error_reporting(E_ALL & ~E_NOTICE);
//載入設定
include_once('../../config/ser_config.php');
include_once('../lib/func.crontrigger.php');
//路徑設定
//define('BASE_PATH', '/home/dev101/sk_java/');
//define('OPEN_HTTP', 'http://220.229.226.59/sk_java/service/');
//define("BASE_PATH", "C:/PHP/sk_java/");
//開啟網址
$base_url = baseUrl;
//設定檔檔名
$inipath = path_Trigger.'crontrigger_slow.ini';
//紀錄檔檔名 (輸出格式可Excel開啟)
$logpath = path_Trigger.'crontrigger.csv';
//功能設定
$log_enable = 0;		//寫紀錄檔
$chk_time	= 100000;	//程式檢查敏感度 (1000000=1秒)
//-----------------------------------------------------------------------------
//計算時差值
$timezone = conTime(0,1);
init();
die();
//主流程
/*
	*載入crontab.txt
	*文字轉陣列(全部的)
	*產生新的執行排程
	*重複執行
		*一條工作一條工作去檢查
		*不是已經到了執行時間的工作
		*不是最近30秒內要執行的工作
		*不是正在執行的工作
		*設定旗標與執行次數
		*產生程式分身
*/
function init(){
	global $inipath;
	global $log_enable;
	global $logpath;
	global $chk_time;
	$bDebug=true;
	if($bDebug){echo "<init()>\n";}
	// *載入crontab.txt	
	$sTask = getTxt($inipath);
	// *文字轉陣列(全部的)	
	$aTask = getSetup($sTask);
	show_task($aTask);
	// print_r($aTask);
	// *產生要執行的工作排程
	$aTask_exec = setNext($aTask);
	// print_r($aTask_exec);
	// echo getTime()."\n";
	// *重複執行
	$eTimes = 0;
	$lastshow = '';
	while(true){
		$fNowMicro = getTime();//現在時間
		$iNowStmp = floor($fNowMicro);
		//排程工作 - 時間有超過的項目
		$children = Array();//分身程序
		$aEcho=array();
		foreach($aTask_exec as $k => &$aTask_row){
			$sProgram=$aTask_row[0];//程式名稱
			$iExec_stamp=$aTask_row[1];//執行時間點的時戳
			$sExec_time=$aTask_row[2];//執行時間點
			$iAlready_exec=$aTask_row[3];//已經在執行
			$iExeced_time=$aTask_row[4];//執行的次數
			$iCan_exec=$aTask_row[5];//可以執行
			// *不是已經到了執行時間的工作
			if(($iNowStmp-$iExec_stamp)<0){continue;}
			// *不是最近60秒之前要執行的工作			
			if(($iNowStmp-$iExec_stamp)>60){continue;}
			// *不是正在執行的工作
			if($iAlready_exec != 0){continue;}
			// *不是不能執行的工作
			if($iCan_exec!=1){continue;}
			// *設定旗標與執行次數
			$aTask_row[3] = 1;//正在執行
			$aTask_row[4]++;//執行次數
			$iAlready_exec=$aTask_row[3];
			$iExeced_time=$aTask_row[4];
			$aEcho[]=str_pad($k+1,4,' ',STR_PAD_LEFT);			
			if($sExec_time!=$lastshow || count($children) == 0){
				$aEcho[]=str_pad($sExec_time,21,' ',STR_PAD_LEFT);
			}else {
				$aEcho[]=str_repeat(' ',21);
			}
			$aEcho[]='  '.str_pad($sProgram,40,' ');
			$aEcho[]=str_pad($iExeced_time,5,' ',STR_PAD_BOTH);
			//從這裡啟動程式的分身
			$pid = pcntl_fork();
			$aEcho[]=str_pad($pid,10,' ',STR_PAD_LEFT);
			$aEcho[]="\n";			
			if($pid == -1){
				echo '無法多工執行';
				exit(1);
			}
			if($pid){
				$children[] = $pid;
			}else{
				//是程式的分身,就出while迴圈去跑程式
				break 2;
			}
			if($log_enable){
				//紀錄log檔
				$logline = Array();
				$logline[] = ($i+1);
				$logline[] = $sExec_time;
				$logline[] = $sProgram;
				$logline[] = $iExeced_time;
				$handle = fopen($logpath, 'a');
				fwrite($handle,join(',',$logline)."\r\n");
				fclose($handle);
			}
			$lastshow = $sExec_time;
			if($eTimes>13){
				//echo $zlc;
				$eTimes=0;
				$lastshow = '';
			}else {
				$eTimes++;
			}
		}
		if(count($children)>0){
			$aEcho[]=str_repeat('=',40);
			$aEcho[]="\n";
			echo implode('',$aEcho);		
		}
		//等待副程序執行完
		$status = null;
		if($pid){
			foreach($children as $pid){
				//posix_kill($pid, SIGTERM);
				pcntl_waitpid($pid, $status);
			}
		}
		//取得下次時間, 並避免重複執行
		$aTask_New = setNext($aTask);
		foreach($aTask_New as $k => $aTask_row){
			if($aTask_exec[$k][3] != 1){continue;}//沒有執行中
			if($aTask_exec[$k][1] == $aTask_row[1]){continue;}//時間沒有變化
			$iExeced_time=$aTask_exec[$k][4];
			$aTask_exec[$k] = $aTask_New[$k];
			$aTask_exec[$k][3] = 0;
			$aTask_exec[$k][4] = $iExeced_time;
		}
		//顯示出每項的下次時間
		$aEcho=array();
		$aEcho[]="\n";
		// $aEcho[]="\033[1;1H\033[K";
		$aEcho[]='Num Program Name                   Execute Time        Schedule Time      '."\n";
		$aEcho[]='--- ------------------------------ ------------------- -------------------'."\n";		
		foreach($aTask_exec as $k => $aTask_row){
			// $aEcho[]="\033[".($k+1).";1H\033[K";
			$aEcho[]=str_pad($k+1,3,' ',STR_PAD_LEFT).' ';
			$aEcho[]=str_pad($aTask_row[0],30,' ').' ';
			$aEcho[]='                   '.' ';
			$aEcho[]=$aTask_row[2]."\n";
		}
		$aEcho[]='=== ============================== =================== ==================='."\n";
		// echo implode('',$aEcho);
		//休息
		usleep($chk_time);
	}
	//副程序多工執行區
	if($pid == -1){
		echo '無法多功執行';
		exit(1);
	}else if($pid){
	}else {
		exec_cron_cmd($sProgram,$iExeced_time);
		exit(0);
	}
	if($bDebug){echo "</init()>\n";}
}
?>