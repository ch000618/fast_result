<?php
//載入設定
include_once('../../config/ser_config.php');
//程序監控程式

settype($i, 'integer');
settype($j, 'integer');
settype($k, 'string');

while(true) {
	//監控程式
	$Monitored = Array(
		Array(path_Trigger, file_trigger)
		,Array(path_Trigger, file_trigger_slow)
	);
	//每半時砍一次crontrigger 原本是寫在服務裡面
	foreach($Monitored AS $key => $value) {
		if(strtotime(date('Y-m-d H:i:s'))%1800==0){
			init_kill_crontrigger($value[1],$value[0]);
		}
	}
	//顯示出目前執行程序
	$Out_Ary = '';
	exec('ps aux | grep php', $Out_Ary);
	$Output = join("\n", $Out_Ary);

	$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
	preg_match_all($pattern,$Output,$matches);

	//目前程序中所執行的程式
	$mt_cnt		= count($matches[0]) - 1;

	$i = count($matches[0]);
	foreach($matches[1] as $key =>$sStr){
		foreach($Monitored as $index =>$sTriggerName){
			//echo $sTriggerName[0].$sTriggerName[1]."\n";
			//echo $sStr."\n";
			$bRes = strpos($sStr, $sTriggerName[0].$sTriggerName[1]);
			if ($bRes !== false) {
				unset($Monitored[$index]);
			}
		}
	}
	foreach($Monitored AS $key => $value) {
		chdir($value[0]);
		system(php_cli.' '.$value[0].$value[1].' > /dev/null &');
	}
	
	usleep(1000000);
}
//砍掉crontrigger
function init_kill_crontrigger($file_trigger,$path_Trigger){
	//echo "[".$file_trigger."] \n";
	//將傳入 crontrigger路徑 把liunx指令的[file_trigger] 取代掉
	$cmd='ps -ef | grep [file_trigger] | awk \'{print $2 "," $7}\'';
	$cmd=str_replace('[file_trigger]',$path_Trigger.$file_trigger,$cmd);
	//然後執行它 把執行結果 存到$output裡面
	exec($cmd, $output, $return_var);
	//$output 沒有東西 就離開
	if(count($output)<1){exit;}
	//$output 內容 
	/*
	[
		[0]=pid
		[1]=執行時間
	]
	*/
	foreach($output AS $key => $msg) {
		/*將$output 值轉成陣列*/
		$tmp=explode(',',$msg);
		/*把執行時間存進 $aruntime*/
		$aruntime[]=$tmp[1];
		$aPID[]=$tmp[0];
	}
	foreach($output AS $key => $msg) {
		$iruntime=$aruntime[$key];
		/*如果 crontrigger 的執行時間 跟最大值行時間一樣 就是crontrigger 本體*/
		if($iruntime=='00:00:00'){continue;}
		if($iruntime==max($aruntime)){
			//得到本體的 pid
			$iPID=$aPID[$key];
			//砍掉這個 pid
			system('kill '.$iPID);
		}
	}
}
?>
