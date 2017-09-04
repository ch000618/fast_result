<?php
//class:SSH連線操作
/*
	建構子
	解構子
	建立連線
	關閉連線
	檢查帳密
	執行命令
	回傳結果
	*一個物件就一個連線
	*可以一口氣執行很多指令,再一口氣取回結果
	*使用方式:
		(單一命令)
		$ssh=new php_ssh(主機,帳號,密碼);
		$ssh->exec($cmd);
		echo $ssh->get_result();
*/
class php_ssh{
	private $host='';//主機
	private $port=22;//連接port
	private $user='';//使用者
	private $pass='';//密碼
	private $connection;//SSH連線
	// private $shell_type = 'vt100+';
	private $shell_type = 'xterm';
	private $shell = null;//要下的指令
	//狀態:文字
	public	$status_text='no connection';
	//狀態碼
	public	$status_code=0;
	private $result='';//執行結果
	private $error_code=array(
		 'no_conn'		=>0
		,'conn_ok'		=>1
		,'login_ok'		=>2
		,'conn_ng'		=>3
		,'login_ng'		=>4
		,'shell_ng'		=>5
		,'stream_ng'	=>6
	);
	//------
	// 建構子
	function __construct($host,$user,$pass){
		if($host!=''){$this->host=$host;}
		if($user!=''){$this->user=$user;}
		if($pass!=''){$this->pass=$pass;}
		$this->open();
		if($this->status_code==$this->error_code['conn_ng']){return;}
		$this->auth();
	}
	// 解構子
	function __destruct(){
		$this->close();
	}
	// 建立連線
	function open(){
		@$this->connection  = ssh2_connect($this->host, $this->port);
     if( !$this->connection ) {
       $this->status_code = $this->error_code['conn_ng'];
       $this->status_text = "Connection failed !";
			 return;
     }
		 $this->status_code = $this->error_code['conn_ok'];
     $this->status_text = "Connection success !";

		 return;
	}
	// 關閉連線
	function close(){
		$this->exec('echo "EXITING" && exit;');
    $this->connection = null;
	}
	//檢查帳密
	function auth(){
		$r=ssh2_auth_password( $this->connection, $this->user, $this->pass );
		if($r){
			$this->status_code = $this->error_code['login_ok'];
			$this->status_text = "Authorization success !";
			return;
		}
		$this->status_code = $this->error_code['login_ng'];
		$this->status_text = "Authorization failed !";
	}
	// 單次執行
	function exec($cmd){
		if($this->status_code == $this->error_code['no_conn']){return ;}
		if($this->status_code == $this->error_code['conn_ng']){return ;}
		if($this->status_code == $this->error_code['login_ng']){return ;}
		$stream = ssh2_exec($this->connection, $cmd);
		if(!$stream){
			$this->status_code = $this->error_code['stream_ng'];
			$this->status_text = "stream failed !";
			return;
		}
		stream_set_blocking($stream, true);
		$ret=array();
		while ($buf = fread($stream, 4096)) {
			$ret[]=$buf;
    }
		fclose($stream);
		$this->result=implode('',$ret);
	}
	// 批次執行命令
	/*
		$cmds=[命令,命令,...]
	*/
	function batch_exec($cmds){
		$this->open_shell();
		if($this->status_code == $this->error_code['no_conn']){return;}
		if($this->status_code == $this->error_code['conn_ng']){return;}
		if($this->status_code == $this->error_code['login_ng']){return;}
		$command=implode(';',$cmds).';exit;';
		fwrite($this->shell, $command);
		//fwrite($this->shell, 'exit;');
		$ret=array();
		while (!feof($this->shell)) {
			$str=trim(fgets($this->shell));
			if($str==''){continue;}
			$ret[]=fgets($this->shell);
		}
		$this->result=implode("\n",$ret);
	}
	//開啟shell
	function open_shell(){
		$this->shell = ssh2_shell( $this->connection,  $this->shell_type );
		if( !$this->shell ){
			$this->status_code = $this->error_code['shell_ng'];
			$this->status_text = "Shell connection failed !";
		}else{
			
		}
	}
	// 回傳結果
	function get_result(){
		return $this->result;
	}
}
//將top的訊息整理成二微陣列
/*
$str=top的訊息
回傳:[
	[資訊,資訊,...]
	]
*/
function arrange_top($str){
	$ary=explode("\n",$str);
	$ret=array();
	foreach($ary as $k => $v){
		$tmp=array();
		$str=trim($v);
		if($str== ''){continue;}
		$ary2=explode(" ",$str);
		foreach($ary2 as $k2 => $v2){
			$v2=trim($v2);
			if($v2==''){continue;}
			$tmp[]=$v2;
		}
		$ret[]=$tmp;
	}
	return $ret;
}
//將top的訊息整理成二微陣列
/*
$str=top的訊息
回傳:[
	[資訊,資訊,...]
	]
*/
function arrange_top_v2($str){
	$colum=array('PID','USER','PR','NI','VIRT','RES','SHR','S','CPU','MEM','TIME','COMMAND');
	$ary=explode("\n",$str);
	$ret=array();
	foreach($ary as $k => $v){
		$tmp=array();
		$str=trim($v);
		if($str== ''){continue;}
		$ary2=explode(" ",$str);
		$c=0;
		foreach($ary2 as $k2 => $v2){
			$v2=trim($v2);
			if($v2==''){continue;}
			$col=$colum[$c];
			$tmp[$col]=$v2;
			$c++;			
		}
		$ret[]=$tmp;
	}
	return $ret;
}
?>