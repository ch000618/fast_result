 <?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.draws.php');
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
echo main();
function main(){
	$from_data=$_POST;
	$sGame=$from_data['g'];
	$rpt_date=$from_data['d'];
	$ary=dws_get_all_lottery_draws($sGame,$rpt_date);
	//$ary=dws_get_all_result_draws($sGame,$rpt_date);
	$js_ary=json_encode($ary);
	return $js_ary;
}
?>
