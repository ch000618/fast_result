<?php
$ret=init_service();
echo $ret;
function init_service(){
	include_once('ser_config.php');
	$cmd='ps -ef | grep [file_trigger] | awk \'{print $2 "," $7}\'';
	$cmd=str_replace('[file_trigger]',path_Trigger.file_trigger,$cmd);
	//echo $cmd;
	exec($cmd, $output, $return_var);
	if(count($output)<1){return 'ERROR';}
	foreach($output AS $key => $msg) {
		/*將$output 值轉成陣列*/
		$tmp=explode(',',$msg);
		/*把執行時間存進 $aruntime*/
		$aruntime[$key]=$tmp[1];
		$aPID[$key]=$tmp[0];
	}
	foreach($output AS $key => $msg) {
		$iruntime=$aruntime[$key];
		/*如果 crontrigger 的執行時間 跟最大值行時間一樣 就是crontrigger 本體*/
		if($iruntime=='00:00:00'){continue;}
		if($iruntime==max($aruntime)){
			//得到本體的 pid
			$iPID=$aPID[$key];
			//echo 'kill '.$iPID;
			//砍掉這個 pid
			system('kill '.$iPID);
			//continue;
		}
	}
	return 'OK';
}
?>
