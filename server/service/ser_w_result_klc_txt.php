<?php
$_SERVER['HTTP_HOST']='console_c';
include_once('../../config/connect.php');
include_once($web_cfg['path_lib'].'class.db.PDO.php');
include_once($web_cfg['path_lib'].'func.write_result_txt.php');
include_once($web_cfg['path_lib'].'func.ser_result.php');
include_once($web_cfg['path_lib'].'func.ser_result_hist.php');
//新增昨天的開獎結果存文字檔-重慶時時彩
/*
	*到開獎結果的網站抓取昨天的開獎結果
	*存成文字檔
	*修改權限
*/
$db=mke_pdo_link($insert_db);
$db_s=mke_pdo_link($select_db);
$strService='w_result_klc_txt';
$sGame='klc';
txt_mke_ser($sGame);
?>