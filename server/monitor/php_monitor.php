<?php
//���J�]�w
include_once('../../config/ser_config.php');
//�{�Ǻʱ��{��

settype($i, 'integer');
settype($j, 'integer');
settype($k, 'string');

while(true) {
	//�ʱ��{��
	$Monitored = Array(
		Array(path_Trigger, file_trigger)
	);
	
	//��ܥX�ثe����{��
	$Out_Ary = '';
	exec('ps aux', $Out_Ary);
	$Output = join("\n", $Out_Ary);

	$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
	preg_match_all($pattern,$Output,$matches);

	//�ثe�{�Ǥ��Ұ��檺�{��
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
