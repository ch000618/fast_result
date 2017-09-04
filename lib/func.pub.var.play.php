<?php
//玩法相關的變數
//每個玩法的項目
/*
	$_aDraws_play_item[玩法]=[項目,項目,....]
*/
$_aDraws_play_item=array();
$_item_OE=array('O','E');//單雙
$_item_LS=array('L','S');//大小
$_item_CFB=array('C','F','B');//中發白
$_item_ESWN=array('E','S','W','N');//方位
$_item_DT=array('D','T');//龍虎
//===klc & nc===
$_tmp=array();
for($i=1;$i<=20;$i++){$_tmp[]=$i;}
for($i=100;$i<=107;$i++){$_aDraws_play_item[$i]=$_tmp;}//第一球~第八球
$_aDraws_play_item[175]=$_tmp;//正碼
unset($_tmp);
for($i=108;$i<=115;$i++){$_aDraws_play_item[$i]=$_item_OE;}//單雙
for($i=116;$i<=123;$i++){$_aDraws_play_item[$i]=$_item_LS;}//大小
for($i=124;$i<=131;$i++){$_aDraws_play_item[$i]=$_item_LS;}//尾大小
for($i=132;$i<=139;$i++){$_aDraws_play_item[$i]=$_item_OE;}//和數單雙
$_aDraws_play_item[140]=$_item_OE;//總和單雙
$_aDraws_play_item[141]=$_item_LS;//總和大小
$_aDraws_play_item[142]=$_item_LS;//總和尾大小
for($i=143;$i<=150;$i++){$_aDraws_play_item[$i]=$_item_CFB;}//中發白
for($i=151;$i<=158;$i++){$_aDraws_play_item[$i]=$_item_ESWN;}//方位
for($i=159;$i<=162;$i++){$_aDraws_play_item[$i]=$_item_DT;}//龍虎
for($i=167;$i<=174;$i++){//連碼
	if($i==172){continue;}
	$_aDraws_play_item[$i]=array('0');
}
//===ssc===
$_tmp=array();
for($i=0;$i<=9;$i++){$_tmp[]=$i;}
for($i=200;$i<=204;$i++){$_aDraws_play_item[$i]=$_tmp;}//第一球~第五球
unset($_tmp);
$_aDraws_play_item[205]=$_item_OE;//總和單雙
$_aDraws_play_item[206]=$_item_LS;//總和大小
$_aDraws_play_item[207]=array('D');//龍
$_aDraws_play_item[208]=array('T');//虎
$_aDraws_play_item[209]=array('B');//和
$_tmp=array('4','3','2','1','0');//骰子的狀況
//前三,中三,後三
for($i=210;$i<=224;$i++){
	$sItem=$_tmp[ceil($i-210)/3];
	$_aDraws_play_item[$i]=array($sItem);
}
unset($_tmp);
for($i=225;$i<=229;$i++){$_aDraws_play_item[$i]=$_item_OE;}//第一球~第五球單雙
for($i=230;$i<=234;$i++){$_aDraws_play_item[$i]=$_item_LS;}//第一球~第五球大小
//===pk===
$_tmp=array();
for($i=1;$i<=10;$i++){$_tmp[]=$i;}
for($i=300;$i<=309;$i++){$_aDraws_play_item[$i]=$_tmp;}//第一名~第十名
unset($_tmp);
for($i=310;$i<=319;$i++){$_aDraws_play_item[$i]=$_item_LS;}//大小
for($i=320;$i<=329;$i++){$_aDraws_play_item[$i]=$_item_OE;}//單雙
for($i=330;$i<=334;$i++){$_aDraws_play_item[$i]=$_item_DT;}//龍虎
$_aDraws_play_item[335]=$_item_LS;//冠亞大小
$_aDraws_play_item[336]=$_item_OE;//冠亞單雙
$_tmp=array();
for($i=3;$i<=19;$i++){$_tmp[$i]=$i;}
$_aDraws_play_item[337]=$_tmp;//冠亞和
unset($_tmp);
//===kb===
$_tmp=array();
for($i=1;$i<=80;$i++){$_tmp[$i]=$i;}
$_aDraws_play_item[500]=$_tmp;
unset($_tmp);
$_aDraws_play_item[501]=$_item_LS;//總和大小
$_aDraws_play_item[502]=$_item_OE;//總和單雙
$_aDraws_play_item[503]=array('D');//總和和局
$_aDraws_play_item[505]=array('LO','LE');//總大單雙
$_aDraws_play_item[506]=array('SO','SE');//總小單雙
$_aDraws_play_item[507]=array('D','F','B');//前後和
$_aDraws_play_item[508]=array('D','O','E');//單雙和
$_aDraws_play_item[509]=array('1','2','3','4','5');//總和五行
//每個遊戲有哪些玩法
$_aDraws_game_play=array();
//===klc & nc ===
$_tmp=array();
$_aDraws_game_play[11]=array();
$_aDraws_game_play[14]=array();
for($i=100;$i<=175;$i++){ 
	if($i>=163 && $i<=166){continue;}
	if($i==172){continue;}
	$_tmp[]=$i; 
}
$_aDraws_game_play[11]=$_tmp;
$_aDraws_game_play[14]=$_tmp;
//刪除沒有的玩法
$key=array_search(169,$_aDraws_game_play[11]);
if($key!==FALSE){ unset($_aDraws_game_play[11][$key]); }
$key=array_search(172,$_aDraws_game_play[14]);
if($key!==FALSE){ unset($_aDraws_game_play[14][$key]); }
//===ssc===
$_aDraws_game_play[12]=range(200,234);
//===pk===
$_aDraws_game_play[13]=range(300,337);
//===kb===
$_aDraws_game_play[15]=array(500,501,502,503,505,506,507,508,509);
?>