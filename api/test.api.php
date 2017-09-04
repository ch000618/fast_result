<?php
//ini_set('display_errors', 1); //顯示錯誤訊息
//error_reporting(E_ALL); //錯誤回報
//測試API
echo '<xmp>';
$test=$_GET['test'];
switch($test){
  case '1':
    $_GET['c']='get_last_seq';
    $_POST['game']='klc';
    require('api.php');
    break;
  case '2':
    $_GET['c']='get_last_result_seq';
    $_POST['game']='klc';
    require('api.php');
    break;
  case '3':
    $_GET['c']='get_seq_data';
    $_POST['game']='klc';
    $_POST['seq_name']='2016081667';
    require('api.php');
    break;
  case '4':
    $_GET['c']='get_seq_result';
    $_POST['game']='klc';
    $_POST['seq_name']='2016081667';
    require('api.php');
    break;
  case '5':
    $_GET['c']='get_now_time';
    require('api.php');
    break;
  case '6':
    $_GET['c']='get_now_timestamp';
    require('api.php');
    break;
  case '7':
    $_GET['c']='get_day_draws';
    $_POST['game']='klc';
    require('api.php');
    break;
  case '8':
    $_GET['c']='get_day_result';
    $_POST['game']='klc';
    require('api.php');
    break;
  case '9':
    $_GET['c']='get_someday_draws';
    $_POST['game']='klc';
    $_POST['date']='2016-08-20';
    require('api.php');
    break;
  case '10':
    $_GET['c']='get_someday_result';
    $_POST['game']='klc';
    $_POST['date']='2016-08-20';
    require('api.php');
    break;
	  case '11':
    $_GET['c']='api_get_last_result';
    $_POST['game']='klc';
    require('api.php');
    break;
	case '12':
    $_GET['c']='api_chk_drop_draws_result';
    require('api.php');
    break;
	case '13':
    $_GET['c']='api_remedy_drop_result';
    require('api.php');
    break;
	case '14':
    $_GET['c']='api_get_all_game';
    require('api.php');
    break;
	case '15':
    $_GET['c']='api_chk_result_source';
    require('api.php');
    break;
	case '16':
    $_GET['c']='api_put_lh_site_result';
		$_POST['game']='lh';
		$data=array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"77"
      ,'rpt_date'=>"2017-07-04"
      ,'num'=>"15,5,38,27,26,2,18"
      ,'lottery_Time'=>"2017-07-04 09:30:00"
		);
		$_POST['data']=$data;
    require('api.php');
    break;
	case '17':
    $_GET['c']='api_put_lh_site_result_batch';
		$_POST['game']='lh';
		$data=array(
			0=>array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"77"
      ,'rpt_date'=>"2017-07-04"
      ,'num'=>"15,5,38,27,26,2,18"
      ,'lottery_Time'=>"2017-07-04 09:30:00"
			),
			1=>array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"78"
      ,'rpt_date'=>"2017-07-06"
      ,'num'=>"30,7,2,8,10,6,28"
      ,'lottery_Time'=>"2017-07-06 09:30:00"
			),
			2=>array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"79"
      ,'rpt_date'=>"2017-07-08"
      ,'num'=>"21,8,36,11,3,48,37"
      ,'lottery_Time'=>"2017-07-08 09:30:00"
			),
			3=>array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"80"
      ,'rpt_date'=>"2017-07-11"
      ,'num'=>"43,22,45,1,20,11,35"
      ,'lottery_Time'=>"2017-07-11 09:30:00"
			),
			4=>array(
       'site'=>"jrd"
      ,'rpt_year'=>"2017"
      ,'rpt_year_sn'=>"76"
      ,'rpt_date'=>"2017-07-02"
      ,'num'=>"9,39,4,33,27,42,35"
      ,'lottery_Time'=>"2017-07-02 09:30:00"
			)
		);
		$_POST['list']=$data;
    require('api.php');
    break;
	case '18':
    $_GET['c']='api_get_lh_result';
		$_POST['game']='lh';
    require('api.php');
    break;
}
echo '</xmp>';
?>