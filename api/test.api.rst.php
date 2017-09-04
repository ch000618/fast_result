<?php
//測試API
echo '<xmp>';
$test=$_GET['test'];
switch($test){
  case '1':
    $_GET['c']='get_last_result_seq';
    $_POST['game']=$_GET['g'];
    require('api.php');
    break;
  case '2':
    $_GET['c']='get_someday_draws';
    $_POST['game']=$_GET['g'];
    $_POST['date']=date('Y-m-d');
    require('api.php');
    break;
  case '3':
    $_GET['c']='get_someday_result';
    $_POST['game']=$_GET['g'];
    $_POST['date']=date('Y-m-d');
    require('api.php');
    break;
	  case '4':
    $_GET['c']='api_get_last_result';
    $_POST['game']=$_GET['g'];
    require('api.php');
    break;
}
echo '</xmp>';
?>