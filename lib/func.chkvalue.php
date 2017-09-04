<?php
//========== Function List ==========
{/*--------------------------------------------------

chkInteger----------檢查 value 是否是 整數 ( 不含符號 )
chkFloat------------檢查 value 是否是 浮點數 ( 含符號 )
chkFloatPlus--------檢查 value 是否是 浮點數 ( 正數 )
chkFloatNegative----檢查 value 是否是 浮點數 ( 負數 )
chkEngNum-----------檢查 value 是否是 英數 ( 不含符號 非混合 )
chkBlendEngNum------檢查 value 是否是 混合英數 ( 不含符號 )
chkStrFormat_JSON---檢查 value 是否 只符合 JSON 字串允許的字元

proReplaceBlank-----取代空白字元

--------------------------------------------------*/}

//========== Functions ==========
//檢查 value 是否是 整數 ( 正整數 )
{/*
	輸入
		$value : 要檢查的字串
		$debug : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkInteger($value,$debug=false){
	$chkRule = "/[^0-9]/";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n"; 
	}
	
	if(count($aryMatch)==0){ return true; }
	return false;
}

//檢查 value 是否是 浮點數 ( 含符號 )
{/*
	輸入
		$value    : 要檢查的字串
		$pointnum : 小數點後幾位數
		$debug    : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkFloat($value,$pointnum,$debug=false){
	$chkRule = "/-?([0-9]+\.?[0-9]{0,".$pointnum."})/";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	$strv = ((string)$value); $strm = ((string)$aryMatch[0]);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n";
		if($strv==$strm){ echo "check is true<br> \n"; }
		if($strv!=$strm){ echo "check is false<br> \n"; }
	}	
	
	if($strv==$strm){ return true; }
	return false;	
}

//檢查 value 是否是 浮點數 ( 正數 )
{/*
	輸入
		$value    : 要檢查的字串
		$pointnum : 小數點後幾位數
		$debug    : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkFloatPlus($value,$pointnum,$debug=false){
	$chkRule = "/([0-9]+\.?[0-9]{0,".$pointnum."})/";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	$strv = ((string)$value); $strm = ((string)$aryMatch[0]);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n";
		if($strv==$strm){ echo "check is true<br> \n"; }
		if($strv!=$strm){ echo "check is false<br> \n"; }
	}	
	
	if($strv==$strm){ return true; }
	return false;	
}
//檢查 value 是否是 浮點數 ( 負數 )
{/*
	輸入
		$value    : 要檢查的字串
		$pointnum : 小數點後幾位數
		$debug    : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkFloatNegative($value,$pointnum,$debug=false){
	$chkRule = "/-([0-9]+\.+[0-9]{1,".$pointnum."})/";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	$strv = ((string)$value); $strm = ((string)$aryMatch[0]);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n";
		if($strv==$strm){ echo "check is true<br> \n"; }
		if($strv!=$strm){ echo "check is false<br> \n"; }
	}	
	
	if($strv==$strm){ return true; }
	return false;	
}

//檢查 value 是否是 只含英數 ( 不含符號 )
{/*
	輸入
		$value : 要檢查的字串
		$debug : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkEngNum($value,$debug=false){ //
	$chkRule = "/([^0-9a-z])/i";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n"; 
	}
	
	if(count($aryMatch)==0){ return true; }
	return false;	
}

//檢查 value 是否是 混合英數 ( 不含符號 )
{/*
	輸入
		$value : 要檢查的字串
		$debug : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkBlendEngNum($value,$debug=false){
	if(chkEngNum($value,$debug)==false){ return false; }
	
	$chkRule_Num = "/[0-9]+/i";
	$chkRule_Eng = "/[a-z]+/i";	
	$chkHasNum = preg_match($chkRule_Num, $value);
	$chkHasEng = preg_match($chkRule_Eng, $value);	
	
	if($debug){
		echo "chkHasNum is ".((string)$chkHasNum)."<br> \n";
		echo "chkHasEng is ".((string)$chkHasEng)."<hr> \n";
	}
	
	if($chkHasNum && $chkHasEng){ return true; }	
	return false;	
}

//檢查 value 是否 只符合 JSON 字串允許的字元
{/*
	可通過字元
		0-9 a-z A-Z @ { } [ ] " , : \ 
	輸入
		$value : 要檢查的字串
		$debug : Debug旗標
	輸出
		True  : 檢查通過
		False : 含有其他字元
*/}
function chkStrFormat_JSON($value,$debug=false){
	$chkRule = "/([^0-9a-z\\\@\_\-\{\}\[\]\"\,\:\.])/i";
	$aryMatch = array();	
	preg_match($chkRule, $value, $aryMatch);
	
	if($debug){ 
		echo "aryMatch count : ".count($aryMatch)."<br> \n";
		echo "<pre> \n"; print_r($aryMatch); echo "</pre> \n"; 
	}	
	
	if(count($aryMatch)==0){ return true; }
	$flag = false;
	for($i=0; $i<count($aryMatch); $i++){
		if(trim($aryMatch[$i])!=''){ $flag = true; break; }
	}
	if(!$flag){ return true; }
	return false;
}

//取代空白字元
{/*
	輸入
		$value : 要取代的字串
		$debug : Debug旗標
	輸出
		被取代空白後的 $value
*/}
function proReplaceBlank($value,$debug=false){
	$strReplace = $value;
	$strReplace = str_replace('　', ' ', $strReplace);
	$strReplace = str_replace(' ', 	'',  $strReplace);
	return $strReplace;
}

//清除空白字元
/*
	輸入
		$value : 要取代的字串
		$debug : Debug旗標
	輸出
		被取代空白後的 $value
*/
function proEraseBlank($value,$debug=false){
	$strReplace = $value;  
	$strReplace = trim($strReplace);  
	$strReplace = preg_replace('/[\n\r\t]/', ' ', $strReplace);  
	$strReplace = preg_replace('/\s(?=\s)/', '', $strReplace);    
	return $strReplace;  
}
?>