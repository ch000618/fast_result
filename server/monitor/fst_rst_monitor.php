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
		,Array(path_Trigger, file_trigger_slow)
	);
	//�C�b�ɬ�@��crontrigger �쥻�O�g�b�A�ȸ̭�
	foreach($Monitored AS $key => $value) {
		if(strtotime(date('Y-m-d H:i:s'))%1800==0){
			init_kill_crontrigger($value[1],$value[0]);
		}
	}
	//��ܥX�ثe����{��
	$Out_Ary = '';
	exec('ps aux | grep php', $Out_Ary);
	$Output = join("\n", $Out_Ary);

	$pattern = '/(?:\S+\s+){9}[0-9]+:[0-9]+\s+(.+)/';
	preg_match_all($pattern,$Output,$matches);

	//�ثe�{�Ǥ��Ұ��檺�{��
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
//�屼crontrigger
function init_kill_crontrigger($file_trigger,$path_Trigger){
	//echo "[".$file_trigger."] \n";
	//�N�ǤJ crontrigger���| ��liunx���O��[file_trigger] ���N��
	$cmd='ps -ef | grep [file_trigger] | awk \'{print $2 "," $7}\'';
	$cmd=str_replace('[file_trigger]',$path_Trigger.$file_trigger,$cmd);
	//�M����楦 ����浲�G �s��$output�̭�
	exec($cmd, $output, $return_var);
	//$output �S���F�� �N���}
	if(count($output)<1){exit;}
	//$output ���e 
	/*
	[
		[0]=pid
		[1]=����ɶ�
	]
	*/
	foreach($output AS $key => $msg) {
		/*�N$output ���ন�}�C*/
		$tmp=explode(',',$msg);
		/*�����ɶ��s�i $aruntime*/
		$aruntime[]=$tmp[1];
		$aPID[]=$tmp[0];
	}
	foreach($output AS $key => $msg) {
		$iruntime=$aruntime[$key];
		/*�p�G crontrigger ������ɶ� ��̤j�Ȧ�ɶ��@�� �N�Ocrontrigger ����*/
		if($iruntime=='00:00:00'){continue;}
		if($iruntime==max($aruntime)){
			//�o�쥻�骺 pid
			$iPID=$aPID[$key];
			//�屼�o�� pid
			system('kill '.$iPID);
		}
	}
}
?>
