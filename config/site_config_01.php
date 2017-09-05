<?php
//站台設定檔
/*
	$aSiteHost:{
		 Seat						=	監控站位置
		,ch_site				=	網站中文名稱
		,draws					=	站台類型
		,hostName				=	站台host名稱
		,DBName					=	站台資料庫名稱
		,PSLogin				=	看Process用帳密
		,Chkline				=	線路檢查
		,DB_machine			=	資料庫機群
		,WebHost_common	=	共用網站host

	}
*/
//取得目前domain
$sDomainHost_tmp=$_SERVER['HTTP_HOST'];
//取得第一個字串
$sDomainNumber=substr($sDomainHost_tmp,0,1);
//如果是1的話要轉過來
$sDomainNumber=($sDomainNumber=='c')?'1':$sDomainNumber;
switch($sDomainNumber){
	case '1':
		$sDomain_Host='1';
		break;
	case '7':
		$sDomain_Host='71';
		break;
	case '8':
		$sDomain_Host='81';
		break;
	case '9':
		$sDomain_Host='91';
		break;
	default:
		$sDomain_Host='1';
		break;
}
$aSiteHost=array();
//監控站位置
$aSiteHost['Seat']='GC';
//網站中文名稱
$aSiteHost['ch_site']='JRD';
//網站名稱
$aSiteHost['site_name']='888jrd.me';
//站台類型
$aSiteHost['draws']='純快';
//站台名稱
$aSiteHost['hostName']=$sDomain_Host.'.888jrd.me';
//工程用站台名稱
$aSiteHost['RD_hostName']=$sDomain_Host.'.888jrd.me';
//站台資料庫名稱
$aSiteHost['DBName']='fast_lottery_01';
//線路名稱
$sSite_name=$aSiteHost['site_name'];
for($Line_num=1;$Line_num<=3;$Line_num++){
	$aSiteHost['Line'][]=($Line_num!=3)?$Line_num.'.'.$sSite_name:$sSite_name;
	$aSiteHost['Line'][]='7'.$Line_num.'.'.$sSite_name;
	$aSiteHost['Line'][]='8'.$Line_num.'.'.$sSite_name;
	$aSiteHost['Line'][]='9'.$Line_num.'.'.$sSite_name;
}
//看Process用帳密
/*
	$aSiteHost['PSLogin']:{
		一般使用者:{
				 username	=	帳號
				,password	=	密碼
		}
		,監控站使用者:{
				 username	=	帳號
				,password	=	密碼
		}
		,服務使用者:{
				 username	=	帳號
				,password	=	密碼
		}
	}
*/
$aSiteHost['PSLogin']=array(
	'common'=>array(
		 'username'=>'shio_lotto_mysql'
		,'password'=>'shio_lotto_2015'
	)
	,'monitor'=>array(
		 'username'=>'chk_db'
		,'password'=>'chk_db'
	)
	,'srv_user'=>array(
		 'username'=>'srv_user'
		,'password'=>'srv_user'
	)
	,'ssh'=>array(
		 'username'=>'rdrd'
		,'password'=>'rd01672321'
	)	
);

//線路檢查
/*
	$aSiteHost['Chkline']:{
		線路名稱
	}
*/
$aSiteHost['Chkline']=array(
	 $sDomain_Host.'.888jrd.me'
);
//資料庫機群
/*
	$aSiteHost['ChkMSHost']:{
		 Master	:{
			  db名稱
		 }
		,Slave	:{
			  db名稱
		}
	}
*/
$aSiteHost['DB_machine']=array(
	'Master'=>array(
		 'fast_db_01'
	)
	,'Slave'=>array(
		 'fast_db_02'
	)
);
//共用網站host
/*
	$aSiteHost['WebHost_common']:{
		Web01:{
			 hostName	=	網站名稱
			,Features	=	功能	
		}
		,Web02:{
			 hostName	=	網站名稱
			,Features	=	功能	
		}		
	}
*/
$aSiteHost['WebHost_common']=array(
	  'Web01'=>array(
			 'hostName'=>'fast-web-01'
			,'Features'=>array(
				 'Serverice'
				,'API'
			)
	 )
	 ,'Web02'=>array(
			 'hostName'=>'fast-web-02'
			,'Features'=>array()
	 )	 
);

?>