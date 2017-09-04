<?php
$path=dirname(dirname(__FILE__)).'/';
//載入設定
include_once($path.'/config/ser_config.php');

//監控程式
$Monitored = Array(
	Array(path_Monitor, file_monitor)
);

//程序監控程式
settype($i, 'integer');
settype($j, 'integer');
settype($k, 'string');

//顯示出目前執行程序
$Out_Ary = '';
exec('ps aux', $Out_Ary);
$Output = join("\n", $Out_Ary);

$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
preg_match_all($pattern,$Output,$matches);

//目前程序中所執行的程式
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
