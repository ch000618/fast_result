<?php
$path=dirname(dirname(__FILE__)).'/';
//���J�]�w
include_once($path.'/config/ser_config.php');

//�ʱ��{��
$Monitored = Array(
	Array(path_Monitor, file_monitor)
);

//�{�Ǻʱ��{��
settype($i, 'integer');
settype($j, 'integer');
settype($k, 'string');

//��ܥX�ثe����{��
$Out_Ary = '';
exec('ps aux', $Out_Ary);
$Output = join("\n", $Out_Ary);

$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
preg_match_all($pattern,$Output,$matches);

//�ثe�{�Ǥ��Ұ��檺�{��
$mt_cnt		= count($matches[0]) - 1;

for ($i=$mt_cnt;$i>=0;$i--) {
  $k = $matches[1][$i];
  $mo_cnt	= count($Monitored) - 1;
  for ($j=$mo_cnt;$j>=0;$j--) {
    $res = strpos($k, $Monitored[$j][1]);
    if ($res !== false) {
      unset($Monitored[$j]);
    }
  }
}

$mo_cnt	= count($Monitored) - 1;
for ($i=$mo_cnt;$i>=0;$i--) {
  chdir($Monitored[$i][0]);
  system(php_cli.' '.$Monitored[$i][0].$Monitored[$i][1].' > /dev/null &');
}
?>
