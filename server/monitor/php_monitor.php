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
	);
	
	//顯示出目前執行程序
	$Out_Ary = '';
	exec('ps aux', $Out_Ary);
	$Output = join("\n", $Out_Ary);

	$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
	preg_match_all($pattern,$Output,$matches);

	//目前程序中所執行的程式
	$mt_cnt		= count($matches[0]) - 1;

	$i = count($matches[0]);
	while ($i--) {
		$k = $matches[1][$i];
		$j = count($Monitored);
		while ($j--) {
			$res = strpos($k, $Monitored[$j][1]);
			if ($res !== false) {
				unset($Monitored[$j]);
			}
		}
	}
	
	foreach($Monitored AS $key => $value) {
		chdir($value[0]);
		system(php_cli.' '.$value[0].$value[1].' > /dev/null &');
	}
	
	usleep(1000000);
}
?>
