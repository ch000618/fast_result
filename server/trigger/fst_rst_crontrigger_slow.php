<?php
error_reporting(E_ALL & ~E_NOTICE);
//���J�]�w
include_once('../../config/ser_config.php');
include_once('../lib/func.crontrigger.php');
//���|�]�w
//define('BASE_PATH', '/home/dev101/sk_java/');
//define('OPEN_HTTP', 'http://220.229.226.59/sk_java/service/');
//define("BASE_PATH", "C:/PHP/sk_java/");
//�}�Һ��}
$base_url = baseUrl;
//�]�w���ɦW
$inipath = path_Trigger.'crontrigger_slow.ini';
//�������ɦW (��X�榡�iExcel�}��)
$logpath = path_Trigger.'crontrigger.csv';
//�\��]�w
$log_enable = 0;		//�g������
$chk_time	= 100000;	//�{���ˬd�ӷP�� (1000000=1��)
//-----------------------------------------------------------------------------
//�p��ɮt��
$timezone = conTime(0,1);
init();
die();
//�D�y�{
/*
	*���Jcrontab.txt
	*��r��}�C(������)
	*���ͷs������Ƶ{
	*���ư���
		*�@���u�@�@���u�@�h�ˬd
		*���O�w�g��F����ɶ����u�@
		*���O�̪�30���n���檺�u�@
		*���O���b���檺�u�@
		*�]�w�X�лP���榸��
		*���͵{������
*/
function init(){
	global $inipath;
	global $log_enable;
	global $logpath;
	global $chk_time;
	$bDebug=true;
	if($bDebug){echo "<init()>\n";}
	// *���Jcrontab.txt	
	$sTask = getTxt($inipath);
	// *��r��}�C(������)	
	$aTask = getSetup($sTask);
	show_task($aTask);
	// print_r($aTask);
	// *���ͭn���檺�u�@�Ƶ{
	$aTask_exec = setNext($aTask);
	// print_r($aTask_exec);
	// echo getTime()."\n";
	// *���ư���
	$eTimes = 0;
	$lastshow = '';
	while(true){
		$fNowMicro = getTime();//�{�b�ɶ�
		$iNowStmp = floor($fNowMicro);
		//�Ƶ{�u�@ - �ɶ����W�L������
		$children = Array();//�����{��
		$aEcho=array();
		foreach($aTask_exec as $k => &$aTask_row){
			$sProgram=$aTask_row[0];//�{���W��
			$iExec_stamp=$aTask_row[1];//����ɶ��I�����W
			$sExec_time=$aTask_row[2];//����ɶ��I
			$iAlready_exec=$aTask_row[3];//�w�g�b����
			$iExeced_time=$aTask_row[4];//���檺����
			$iCan_exec=$aTask_row[5];//�i�H����
			// *���O�w�g��F����ɶ����u�@
			if(($iNowStmp-$iExec_stamp)<0){continue;}
			// *���O�̪�60���e�n���檺�u�@			
			if(($iNowStmp-$iExec_stamp)>60){continue;}
			// *���O���b���檺�u�@
			if($iAlready_exec != 0){continue;}
			// *���O������檺�u�@
			if($iCan_exec!=1){continue;}
			// *�]�w�X�лP���榸��
			$aTask_row[3] = 1;//���b����
			$aTask_row[4]++;//���榸��
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
			//�q�o�̱Ұʵ{��������
			$pid = pcntl_fork();
			$aEcho[]=str_pad($pid,10,' ',STR_PAD_LEFT);
			$aEcho[]="\n";			
			if($pid == -1){
				echo '�L�k�h�u����';
				exit(1);
			}
			if($pid){
				$children[] = $pid;
			}else{
				//�O�{��������,�N�Xwhile�j��h�]�{��
				break 2;
			}
			if($log_enable){
				//����log��
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
		//���ݰƵ{�ǰ��槹
		$status = null;
		if($pid){
			foreach($children as $pid){
				//posix_kill($pid, SIGTERM);
				pcntl_waitpid($pid, $status);
			}
		}
		//���o�U���ɶ�, ���קK���ư���
		$aTask_New = setNext($aTask);
		foreach($aTask_New as $k => $aTask_row){
			if($aTask_exec[$k][3] != 1){continue;}//�S�����椤
			if($aTask_exec[$k][1] == $aTask_row[1]){continue;}//�ɶ��S���ܤ�
			$iExeced_time=$aTask_exec[$k][4];
			$aTask_exec[$k] = $aTask_New[$k];
			$aTask_exec[$k][3] = 0;
			$aTask_exec[$k][4] = $iExeced_time;
		}
		//��ܥX�C�����U���ɶ�
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
		//��
		usleep($chk_time);
	}
	//�Ƶ{�Ǧh�u�����
	if($pid == -1){
		echo '�L�k�h�\����';
		exit(1);
	}else if($pid){
	}else {
		exec_cron_cmd($sProgram,$iExeced_time);
		exit(0);
	}
	if($bDebug){echo "</init()>\n";}
}
?>