<?php
//一些常用的變數,對照表甚麼的...
/*
	前面會加底線,之後...
	a是陣列
	i是數字
	f是浮點
	s是字串
	b是布林
	*這裡不是語系檔
*/
//AJAX回傳時用到的字串
$_sAjax_join_str='êêê';
$_sAjax_Js='<script type="text/javascript" charset="utf-8">
                        try {
                            document.domain = document.domain.replace("html.", "");
                        } catch (e) {
                        }
                    </script>';
//AJAX回傳時的通用雛形
$_aAJAX_Ret=array(
		 'data'=>array()
		,'state' => "1"
		,'errors' => ""
);
//層級
/*
	[]=層級代碼
*/
$_aLevel=array('bm','mm','sc','co','sa','ag');
//層級名稱
$_aLevel_name=array(
	 'mm'=>'公司'
	,'sc'=>'分公司'
	,'co'=>'股东'
	,'sa'=>'总代理'
	,'ag'=>'代理'
	,'mem'=>'會員'
	,'sub'=>'管理员'
);
/*
	[]=層級的資料格
*/
$_aLevel_id=array('bm_id','mm_id','sc_id','co_id','sa_id','ag_id');	
//0/1 轉成 true/false
$_Bit_Boolean=array(
		0=>false
		,1=>true
	);
$_String_Boolean=array(
		'false'=>false
		,'true'=>true
);
//---使用者資料表相關轉換---
//使用者狀態
$_aUser_Status=array(
	 'Y'=>1
	,'N'=>0
	,'S'=>2
	,'B'=>3
);
//賠率盤
/*
	[資料庫內的數字]=賠率盤
*/
$_aUser_Odd_set=array(
	 1=>'A'
	,2=>'B'
	,3=>'C'
);
//---使用者編輯相關---
//編輯時,讀取用到的陣列
//[玩法][畫面編號]=資料庫編號
$_aEdit_Ptype_r=array(
	'10'=>array(
		 "00"=>0
		,"08"=>1
		,"15"=>2
		,"18"=>3
	)
	,'11'=>array(//klc
		 "00"=>100
		,"29"=>175
		,"08"=>108
		,"12"=>140
		,"15"=>143
		,"16"=>151
		,"17"=>159
		,"18"=>167
		,"20"=>168
		,"21"=>170
		,"23"=>171
		,"24"=>173
		,"25"=>174
	)
	,'13'=>array(//pk
		 "00"=>300
		,"10"=>310
		,"12"=>330
		,"13"=>335
		,"14"=>336
		,"15"=>337
	)
	,'12'=>array(//ssc
		 "00"=>200
		,"01"=>205
		,"02"=>207
		,"03"=>209
		,"04"=>210
		,"05"=>213
		,"06"=>216
		,"07"=>219
		,"08"=>222
	)
	,'14'=>array(//nc
		 "00"=>100
		,"29"=>175
		,"08"=>108
		,"12"=>140
		,"15"=>143
		,"16"=>151
		,"17"=>159
		,"20"=>167
		,"22"=>169
		,"21"=>168
		,"23"=>170
		,"30"=>171
		,"26"=>173
		,"27"=>174
	)
	,'15'=>array(//kb
		 "00"=>500
		,"01"=>501
		,"02"=>502
		,"03"=>503
		,"04"=>505
		,"05"=>507
		,"06"=>508
		,"07"=>509
	)
);
$_aEdit_Gtype=array(
	 '10'=>'general'
	,'11'=>'klc'
	,'12'=>'ssc'
	,'13'=>'pk10'
	,'14'=>'nc'
	,'15'=>'kb'
);
//---管理員相關---
//權限的中文
$_aPower_cname=array(
	 'user_sw'=>'帳號管理'
	// ,'view_reserved_rpt_sw'=>'預留吃貨報表'
	,'monitor_sw'=>'現場監督'
	,'receive_statis_sw'=>'收付統計'
	,'operation_record_sw'=>'操盤紀錄'
	,'report_sw'=>'報表'
	,'draws_sw'=>'期數管理'
	,'result_sw'=>'開獎結果'
	,'sys_config_sw'=>'系統設定'
	,'corp_covering_sw'=>'補貨設定'
);
//form_data的權限轉資料庫欄位
$_aRights2power=array(
	 'ZHGL' => 'user_sw'
	,'XCJD' => 'monitor_sw'
	,'SFTJ' => 'receive_statis_sw'
	,'CPJL' => 'operation_record_sw'
	,'BACX' => 'report_sw'
	,'QSGL' => 'draws_sw'
	,'KJGL' => 'result_sw'
	,'XTSD' => 'sys_config_sw'
	,'BHSD' => 'corp_covering_sw'
);
//公司端的管理員權限
$_aRight_MM=array("ZHGL", "XCJD", "SFTJ", "CPJL", "BACX", "QSGL", "KJGL", "XTSD", "BHSD");
//中間商的管理員權限
$_aRight_AG=array("ZHGL", "SFTJ", "BACX", "KJGL", "XTSD");
//編輯資料時,form送的玩法編號,對應那些玩法
$_aEdit_Form2Ptype=array(
	 '10'=>array(//大項快速設定
		 '00'=>array(0)//單碼項
		,'08'=>array(1)//兩面項
		,'15'=>array(2)//連碼項
		,'18'=>array(3)//雜項
	)
	,'11'=>array(//廣東快樂十分
		 '00'=>array(//1~8單碼
			 100,101,102,103,104,105,106,107
		)
		,'29'=>array(175)//正碼
		,'08'=>array(//1~8兩面
			 108,109,110,111,112,113,114,115
			,116,117,118,119,120,121,122,123
			,124,125,126,127,128,129,130,131
			,132,133,134,135,136,137,138,139
		)
		,'12'=>array(140,141,142)//總和兩面
		,'15'=>array(143,144,145,146,147,148,149,150)//1~8中發白
		,'16'=>array(151,152,153,154,155,156,157,158)//1~8方位
		,'17'=>array(159,160,161,162)//1~4龍虎
		,'18' => array(167)//任選二
		,'20' => array(168)//選二聯組
		,'21' => array(170)//任選三
		,'23' => array(171)//選三前組
		,'24' => array(173)//任選四
		,'25' => array(174)//任選五
	)
	,'12'=>array(//重慶時時彩
		 '00' => array(200,201,202,203,204)//單碼
		,'01' => array(//兩面
					205,206
				 ,225,226,227,228,229
				 ,230,231,232,233,234
		)
		,'02' => array(207,208)//龍虎
		,'03' => array(209)//和
		,'04' => array(210,211,212)//豹子
		,'05' => array(213,214,215)//順子
		,'06' => array(216,217,218)//對子
		,'07' => array(219,220,221)//半順
		,'08' => array(222,223,224)//雜六
	)
	,'13'=>array(//北京賽車
		 '00' => array(//單碼
					 300,301,302,303,304
					,305,306,307,308,309
			 )
		,'10' => array(//1~10兩面
					 310,311,312,313,314
					,315,316,317,318,319
					,320,321,322,323,324
					,325,326,327,328,329
			 )
		,'12' => array(//1~5龍虎
					 330,331,332,333,334
			 )
		,'13' => array(335)//冠亞大小
		,'14' => array(336)//冠亞單
		,'15' => array(337)//冠亞和(2)
	)
	,'14'=>array(//幸運農場
		 '00'=>array(//1~8單碼
			 100,101,102,103,104,105,106,107
		)
		,'29'=>array(175)//正碼
		,'08'=>array(//1~8兩面
			 108,109,110,111,112,113,114,115
			,116,117,118,119,120,121,122,123
			,124,125,126,127,128,129,130,131
			,132,133,134,135,136,137,138,139
		)
		,'12'=>array(140,141,142)//總和兩面
		,'15'=>array(143,144,145,146,147,148,149,150)//1~8中發白
		,'16'=>array(151,152,153,154,155,156,157,158)//1~8方位
		,'17'=>array(159,160,161,162)//1~4龍虎
		,'20' => array(167)//任選二
		,'22' => array(168)//選二連直
		,'21' => array(169)//選二聯組
		,'23' => array(170)//任選三
		,'30' => array(171)//選三前組
		,'26' => array(173)//任選四
		,'27' => array(174)//任選五
	)
	,'15'=>array(
		 '00' => array(500)//正碼
		,'01' => array(501)//總和大
		,'02' => array(502)//總和單
		,'03' => array(503)//總和和局
		,'04' => array(504,505,506)//總和過關
		,'05' => array(507)//前後和
		,'06' => array(508)//單雙和
		,'07' => array(509)//五行
	)
);
//新增或者編輯時,直接滕過去的格子
$_aEdit_direct_col=array(
	 'name','account','password','credit','odds_set'
	,'status','rpt_beishu','share_total','share_up','set_water','level'
);
//新增或者編輯時,true/fase轉數字
$_aEdit_tf2_num=array(
	 'short_covering','corpRptFlag','detRptFlag','bhRptFlag','isDgdShare'
	,'beishu_set','share_flag'
);
//新增或者編輯時,主表的欄位-中間商
$_aEedit_main_col_a=array(
	'up_id','account_level','username','password','passwd','nickname'
	,'bm_id','mm_id','sc_id','co_id','sa_id'
	,'account_enable','build_date'
	,'max_credit','up_share','total_share'
	,'chg_pw_sw','odds_set','double_order_sw'
	,'outward_set_sw','outward_up_share_sw','view_corp_rpt_sw','view_det_rpt_sw','view_outward_rpt_sw'
	,'view_rpt_scale','over_share_SC_sw'
);
//新增或者編輯時,主表的欄位-會員
$_aEedit_main_col_m=array(
		'up_id','username','password','passwd','nickname'
		,'bm_id','mm_id','sc_id','co_id','sa_id','ag_id'
		,'account_enable','build_date'
		,'max_credit','up_share'
		,'chg_pw_sw','odds_set','double_order_sw'
	);
//新增或者編輯時,主表對照資料庫的欄位
/*
	AAA.BBB = [AAA][BBB]
	[now] = 現在時間
	$值 = 值
	左邊:資料庫欄位,右邊:data資料
*/
$_aEedit_col2data=array(
		 'up_id'=>'superiorid'
		,'account_level'=>'level'
		,'username'=>'account'
		,'password'=>'password'
		,'passwd'=>'password'
		,'nickname'=>'name'
		,'bm_id'=>'up_id.bm'
		,'mm_id'=>'up_id.mm'
		,'sc_id'=>'up_id.sc'
		,'co_id'=>'up_id.co'
		,'sa_id'=>'up_id.sa'
		,'ag_id'=>'up_id.ag'
		,'account_enable'=>'status'
		,'build_date'=>'[now]'
		,'max_credit'=>'credit'
		,'up_share'=>'share_up'
		,'total_share'=>'share_total'
		,'chg_pw_sw'=>'$1'
		,'odds_set'=>'odds_set'
		,'double_order_sw'=>'beishu_set'
		,'outward_set_sw'=>'short_covering'
		,'outward_up_share_sw'=>'share_flag'
		,'view_corp_rpt_sw'=>'corpRptFlag'
		,'view_det_rpt_sw'=>'detRptFlag'
		,'view_outward_rpt_sw'=>'bhRptFlag'
		,'view_rpt_scale'=>'rpt_beishu'
		,'over_share_SC_sw'=>'isDgdShare'
	);
//新增或者編輯時,conf的欄位
$_aEedit_Row_col=array('order_min','order_max','concede_max','water_a','water_b','water_c');
//新增或者編輯時,conf要跟基本值計算的欄位
$_aEedit_water_col=array('water_a','water_b','water_c','water_d');
?>