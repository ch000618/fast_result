<?php
//時區設置
date_default_timezone_set("Asia/Taipei");

$sDbHost = "localhost";
$sDbUser = "fst_rst_r";
$sDbPass = "Frr_rst_2016";
$sDbName = "fast_result";

//GameResultArray(獎號中心 Database：fast_result 對應下述陣列Table)
$aResultGame = array( 1=>"draws_klc_result"		//广东快乐十分
					 ,2=>"draws_ssc_result"		//重庆时时彩
					 ,3=>"draws_pk_result"		//北京PK拾
					 ,4=>"draws_nc_result"		//幸运农场
					 ,5=>"draws_kb_result"		//北京快乐8
					 );
					 
//獎號中心 GameResultBallSet
$aBallRowName = array(
						 "draws_klc_result" => array("ball_1"
												    ,"ball_2"
												    ,"ball_3"
												    ,"ball_4"
												    ,"ball_5"
												    ,"ball_6"
												    ,"ball_7"
												    ,"ball_8"
													)
						,"draws_ssc_result" => array("ball_1"
												    ,"ball_2"
												    ,"ball_3"
												    ,"ball_4"
												    ,"ball_5"
													)
						,"draws_pk_result" => array("rank_1"
												   ,"rank_2"
												   ,"rank_3"
												   ,"rank_4"
												   ,"rank_5"
												   ,"rank_6"
												   ,"rank_7"
												   ,"rank_8"
												   ,"rank_9"
												   ,"rank_10"
													)
						,"draws_nc_result" => array("ball_1"
												    ,"ball_2"
												    ,"ball_3"
												    ,"ball_4"
												    ,"ball_5"
												    ,"ball_6"
												    ,"ball_7"
												    ,"ball_8"
													)
						,"draws_kb_result" => array("ball_1"
												    ,"ball_2"
												    ,"ball_3"
												    ,"ball_4"
												    ,"ball_5"
												    ,"ball_6"
												    ,"ball_7"
												    ,"ball_8"
												    ,"ball_9"
												    ,"ball_10"
												    ,"ball_11"
												    ,"ball_12"
												    ,"ball_13"
												    ,"ball_14"
												    ,"ball_15"
												    ,"ball_16"
												    ,"ball_17"
												    ,"ball_18"
												    ,"ball_19"
												    ,"ball_20"
													)
					);

/**
 * Database：fast_result
 * Table：all_result_1680210
 * gtype對應索引如下述
 * 1：广东快乐十分
 * 2：重庆时时彩
 * 3：北京PK拾
 * 4：幸运农场
 * 5：北京快乐8
 */

//外面比對的獎號中心 GameResultBallSet
$aOutBallRowName = array(
						 1 => array("num1"
								   ,"num2"
								   ,"num3"
								   ,"num4"
								   ,"num5"
								   ,"num6"
								   ,"num7"
								   ,"num8"
									)
						,2 => array("num1"
								   ,"num2"
								   ,"num3"
								   ,"num4"
								   ,"num5"
									)
						,3 => array("num1"
								   ,"num2"
								   ,"num3"
								   ,"num4"
								   ,"num5"
								   ,"num6"
								   ,"num7"
								   ,"num8"
								   ,"num9"
								   ,"num10"
									)
						,4 => array("num1"
								   ,"num2"
								   ,"num3"
								   ,"num4"
								   ,"num5"
								   ,"num6"
								   ,"num7"
								   ,"num8"
									)
						,5 => array("num1"
								   ,"num2"
								   ,"num3"
								   ,"num4"
								   ,"num5"
								   ,"num6"
								   ,"num7"
								   ,"num8"
								   ,"num9"
								   ,"num10"
								   ,"num11"
								   ,"num12"
								   ,"num13"
								   ,"num14"
								   ,"num15"
								   ,"num16"
								   ,"num17"
								   ,"num18"
								   ,"num19"
								   ,"num20"												    
									)
					);

function ChkResult(){
	global $sDbHost,$sDbUser,$sDbPass,$sDbName,$aResultGame,$aBallRowName,$aOutBallRowName;

	$conn = mysql_connect ($sDbHost, $sDbUser, $sDbPass);

	if (!$conn){
		echo "連結不到[".$sDbHost."] Service MySQL";
	}else{
		//若當下時間為 2017-04-12 02:05:00，這時間是要算 04-11的開獎日期，所以減3小時回去
		$dAccDate = date("Y-m-d", mktime(date("H") - 3, date("i"), date("s"), date("m"), date("d"), date("Y")));
		
		//減3小時回去再加一天
		$dAccDate2 = date("Y-m-d", mktime(date("H") - 3, date("i"), date("s"), date("m"), date("d") + 1, date("Y")));
		
		$bDBselect = mysql_select_db($sDbName, $conn);
		if (!$bDBselect) {
		    die ("[".$sDbHost."] Can\'t use foo : " . mysql_error());
		}
		
		$aWhereGID = array();	//用來當外面獎號中心的WHERE條件，因為外面的有的網站沒有result_time
		$aAllResult = array();	//獎號中心的結果
		$aAllLastTime = array();	//獎號最後一期開獎結果
		$bBallErr = false;	//用來檢查是否開獎結果有不同
		foreach ($aResultGame AS $iKey => $sTable){
			$qstr = "SELECT `draws_num`";
			foreach ($aBallRowName[$sTable] AS $sRowName){
				$qstr.= ", `$sRowName` ";
			}
			$qstr.= "FROM `".$sTable."` WHERE `rpt_date` = '".$dAccDate."' LIMIT 0, 200";
			
			$result = mysql_query($qstr, $conn);
			if (!$result) die ("執行 ".$qstr." SQL命令失敗");
			while($row = mysql_fetch_array($result)) {
				$sResultNum = "";
				foreach ($aBallRowName[$sTable] AS $sRowName){
					$sResultNum.= $row[$sRowName].",";
				}
				$sResultNum = substr($sResultNum,0,-1);
				
				//快樂8要排序
				if($iKey == 5){
					$aTmp = explode(",",$sResultNum);
					sort($aTmp);
					$sResultNum = "";
					foreach ($aTmp AS $sSortBall){
						$sResultNum.= $sSortBall.",";
					}
					$sResultNum = substr($sResultNum,0,-1);
				}
				
				$aAllResult[$sTable][$row['draws_num']] = $sResultNum;
				$aWhereGID[$iKey][$row['draws_num']] = $row['draws_num'];
			}
		}
		
		/**
		 * 下面開始比對別家的開獎結果
		 */
		$aTable = array("all_result_1680210"
						//,"all_result_ju888",
						//,"all_result_lianju"
						,"all_result_un");
		$aResult = array();	//$sTable結果
		$sWhere = "";
		//20170620 因為168210 期號改格式 為了可以每張表都能調整格式 改成再迴圈內 判斷where條件 
		/*
		$i=0
		foreach ($aWhereGID AS $iGtype => $sNum){
			if(count($sNum) > 0){
				if($i > 0){
					$sWhere.= " OR";
				}
				$sWhere.= " ( gtype = '".$iGtype."' AND gid IN ('".join("','",$sNum)."') )";
				$i ++;
			}
		}
		*/

		if($sWhere != ""){
			foreach ($aTable AS $sTable){
				$i = 0;
				foreach ($aWhereGID AS $iGtype => $sNum){
					if(count($sNum) > 0){
						if($i > 0){
							$sWhere.= " OR";
						}
						$sWhere.= " ( gtype = '".$iGtype."' AND gid IN ('".join("','",$sNum)."') )";
						if($sTable=='all_result_1680210' && $iGtype==4){
							$sNum=$sDraws=substr($sNum,0,8).'0'.substr($sNum,8,10);
							$sWhere.= " ( gtype = '".$iGtype."' AND gid IN ('".join("','",$sNum)."') )";
						}
						$i ++;
					}
				}
				$qstr = "SELECT 
								`reult_time`,`gid`, `gtype`, 
								`num1`, `num2`, `num3`, `num4`, `num5`, `num6`, `num7`, `num8` ,`num9`, `num10`, 
								`num11`, `num12`, `num13`, `num14`, `num15`, `num16`, `num17`, `num18` ,`num19`, `num20`, `num20` 
							FROM 
								`".$sTable."` 
							WHERE 
								".$sWhere."
								GROUP BY gtype, gid
								LIMIT 0, 1000";
				if($sWhere != ""){
					
				}
				//echo 	$qstr;
				$result = mysql_query($qstr, $conn);
				if (!$result) die ("執行 ".$qstr." SQL命令失敗");
				while($row = mysql_fetch_array($result)) {
					$sResultNum = "";
					foreach ($aOutBallRowName[$row['gtype']] AS $sRowName){
						$sResultNum.= $row[$sRowName].",";
					}
					$sResultNum = substr($sResultNum,0,-1);
					$aResult[$sTable][$aResultGame[$row['gtype']]][$row['gid']] = $sResultNum;
					$aAllLastTime[$sTable][$aResultGame[$row['gtype']]] = $row['reult_time'];
				}
			}
		}
		$aErr = array();
		//昨日半夜就掛掉的話,今天早上就會抓不到東西,這樣就就不知道是不是掛掉了,因為陣列就會沒有那家的資料
		foreach($aTable as $sKey =>$sOutName){
			//如果沒有東西去抓那個站的每個遊戲最後一筆資料的時間
			if(!is_array($aAllLastTime) || array_key_exists($sOutName,$aAllLastTime)){continue;}
			foreach($aResultGame as $sGtype =>$sTable){
				$aSQL=array();
				$aSQL[]='SELECT';
				$aSQL[]='reult_time';
				$aSQL[]='FROM';
				$aSQL[]='[table]';
				$aSQL[]='WHERE 1';
				$aSQL[]='AND gtype="[gtype]"';
				$aSQL[]='ORDER BY reult_time DESC';
				$aSQL[]='LIMIT 1';
				$sSQL=implode(' ',$aSQL);
				$sSQL=str_replace('[table]',$sOutName,$sSQL);
				$sSQL=str_replace('[gtype]',$sGtype,$sSQL);
				$result = mysql_query($sSQL, $conn);
				$row = mysql_fetch_array($result);
				$sResult_time=$row['reult_time'];
				//塞回最後更新時間
				$aAllLastTime[$sOutName][$sTable]=$sResult_time;
				$aErr[$sTable]['LastTime'][$sOutName]=$sResult_time;
			}
		}
		//echo "<PRE>";print_r($aAllResult);
		//echo "<PRE>";print_r($aResult);exit;
		foreach ($aAllResult AS $sGame => $aData){
			foreach ($aData AS $sNum => $sBall){
				foreach ($aTable AS $iOutResultName){
					if(isset($aResult[$iOutResultName][$sGame][$sNum])){
						if($sBall != $aResult[$iOutResultName][$sGame][$sNum]){
							/*
							echo $sGame.":".$sNum."--\n";
							echo $sBall ."\n".$aResult[$iOutResultName][$sGame][$sNum];
							echo "\n";
							echo "\n";
							*/
							$aErr[$sGame][$sNum][$iOutResultName] = $aResult[$iOutResultName][$sGame][$sNum];
							$bBallErr = true;
						}
						$aErr[$sGame]['LastTime'][$iOutResultName] = $aAllLastTime[$iOutResultName][$sGame];
					}
				}
			}
		}
		$sAct=(isset($_GET['act']))?$_GET['act']:'';
		if($sAct == "chk"){
			if($bBallErr){
				echo "<a href='http://result.sm2.xyz/ChkFastResult/ChkFastResult.php?act=chk' target='_blank'>";
				echo "結果有誤";
				echo "</a>";
				echo "<PRE>";print_r($aErr);
			}else{
				echo "結果一致!";
			}
			echo "<BR>";
			echo "(".date("Y-m-d H:i:s").")";
		}else{
			/*
			$tmp=array(
				 'draws_klc_result'=>array(
						'2017041335'=>array(
							 'all_result_1680210'=>'1,2,3,4'
							,'all_result_ju888'=>'1,2,3,4'
							,'all_result_lianju'=>'1,2,3,5'
						)
				 )
				,'draws_ssc_result'=>array(
						'2017041335'=>array(
							 'all_result_1680210'=>'1,2,3,4'
							,'all_result_ju888'=>'1,2,3,4'
							,'all_result_lianju'=>'1,2,3,5'
						)
				)
				,'draws_pk_result'=>array(
						'2017041335'=>array(
							 'all_result_1680210'=>'1,2,3,4'
							,'all_result_ju888'=>'1,2,3,4'
							,'all_result_lianju'=>'1,2,3,5'
						)
				)
				,'draws_nc_result'=>array(
						'2017041335'=>array(
							 'all_result_1680210'=>'1,2,3,4'
							,'all_result_ju888'=>'1,2,3,4'
							,'all_result_lianju'=>'1,2,3,5'
						)
				)
				,'draws_kb_result'=>array(
						'2017041335'=>array(
							 'all_result_1680210'=>'1,2,3,4'
							,'all_result_ju888'=>'1,2,3,4'
							,'all_result_lianju'=>'1,2,3,5'
						)
				)
			);
			*/
			//echo json_encode($tmp);
			echo json_encode($aErr);
		}		
	}
}
//while (1) {
	ChkResult();
	//sleep(3);
//}
?>