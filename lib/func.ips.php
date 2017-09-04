<?php
/**
 * IP地址归属地查询 PHP函数版
 * @author Hoverlees http://www.hoverlees.com me[at]hoverlees.com
 * @comment 此功能同时包含C/C++,PHP类,PHP函数,等版本提供下载。
 * 数据库文件及相关代码下载地址：http://www.hoverlees.com/blog/?p=906
 */

/*对象状态常量*/
define('IPV4_AREA_ERROR_SUCCESS',0); //正常
define('IPV4_AREA_ERROR_OPENFILE',-1); //无法打开文件
define('IPV4_AREA_ERROR_FORMAT',-2); //数据库格式不对

function ipv4_area_get_area_string($obj,$section_offset){
	fseek($obj['fp'],$section_offset+$obj['data_pos'],SEEK_SET);
	$str=fread($obj['fp'],104);
	$a=unpack('C',$str[0]);
	$slen=$a[1];
	return substr($str,1,$slen);
}
function ipv4_signed_to_unsigned($v){
	$r=0;
	$a=unpack('C*',pack('N',$v));
	$r=($a[1]*16777216)+($a[2]<<16)+($a[3]<<8)+$a[4];
	return $r;
}
/**
 * IPv4地址转整数函数，注意本函数不检查IP地址的合法性
 * @param $ip IPv4地址
 * @return 转换后的整数
 */
function ipv4_atol($ip){
	$a=explode('.',$ip);
	$b=($a[0]*16777216)+($a[1]<<16)+($a[2]<<8)+$a[3];
	return $b;
}
/**
 * 创建IP表对象
 * @param $filename 数据库文件名
 * @param $index_read_parts 索引划分的块数，如果为1，表示索引全部读入内存（占用大约4M内存空间），如果为2则只需要占用4/2＝2M内存临时空间。此值越大，需要的内存缓冲越少，但磁盘的IO次数增加。如果需要循环查询多个IP，建议此值设为1.如果只查询一次可以根据内存要求自行设定。
 * @return 用于查询的数据库对象，使用完成后请调用ipv4_area_free释放占用的资源
 */
function ipv4_area_create($filename,$index_read_parts=1){
	if($index_read_parts<1) $index_read_parts=1;
	$obj=array(
		'status'=>IPV4_AREA_ERROR_SUCCESS,
		'encoding'=>null,
		'num_items'=>0,
		'index_pos'=>0,
		'data_pos'=>0,
		'fp'=>null,
		'index_read_parts'=>$index_read_parts,
		'each_read_item'=>0,
		'each_read_byte'=>0,
		'last_part_data'=>null
	);
	$obj['fp']=@fopen($filename,"r");
	if(!$obj['fp']){
		$obj['status']=IPV4_AREA_ERROR_OPENFILE;
		return $obj;
	}
	$meta=fread($obj['fp'],20);
	$magic=$meta[0].$meta[1].$meta[2];
	if($magic!='IPT'){
		$obj['status']=IPV4_AREA_ERROR_FORMAT;
		return $obj;
	}
	if($meta[3]=='u'){
		$obj['encoding']='UTF-8';
	}
	else if($meta[3]=='g'){
		$obj['encoding']='GBK';
	}
	$file_size=filesize($filename);
	$a=unpack('N',substr($meta,4,4));
	if($a[1]!=$file_size){
		$obj['status']=IPV4_AREA_ERROR_FORMAT;
		return $obj;
	}
	$a=unpack('N',substr($meta,8,4));
	$obj['num_items']=$a[1];
	$a=unpack('N',substr($meta,12,4));
	$obj['index_pos']=$a[1];
	$a=unpack('N',substr($meta,16,4));
	$obj['data_pos']=$a[1];
	$obj['each_read_item']=ceil($obj['num_items']/$index_read_parts);
	$obj['each_read_byte']=$obj['each_read_item']*12;
	if($index_read_parts==1){
		$obj['last_part_data']='';
		$pos=0;
		fseek($obj['fp'],$obj['index_pos'],SEEK_SET);
		while($pos<$obj['each_read_byte']){
			if($obj['each_read_byte']-$pos>4096) $len=4096;
			else $len=$obj['each_read_byte']-$pos;
			$obj['last_part_data'].=fread($obj['fp'],$len);
			$pos+=$len;
		}
	}
	return $obj;
}
/**
 * IP址所在地查询
 * @param $obj 数据库对象
 * @param $ip IP地址，可以为'XXX.XXX.XXX.XXX'格式
 * @return 归属地名称
*/
function ipv4_area_search($obj,$ip){
	$ret='';
	if(!is_numeric($ip)){
		$ip=ipv4_atol($ip);
	}
	
	$current_left_part=0;
	$current_right_part=$obj['index_read_parts'];
	while($current_left_part<$current_right_part){
		$current_process_part=floor(($current_left_part+$current_right_part)/2);
		if($obj['index_read_parts']==1){
			$data=$obj['last_part_data'];
			$read_items=$obj['each_read_item'];
			$read_bytes=$obj['each_read_byte'];
		}
		else{
			$read_bytes=$obj['num_items']*12-$obj['each_read_byte']*$current_process_part;
			if($read_bytes>$obj['each_read_byte']){
				$read_bytes=$obj['each_read_byte'];
			}
			$off=$obj['index_pos']+$obj['each_read_byte']*$current_process_part;
			fseek($obj['fp'],$off,SEEK_SET);
			$data='';
			$pos=0;
			while($pos<$read_bytes){
				if($read_bytes-$pos>4096) $len=4096;
				else $len=$read_bytes-$pos;
				$data.=fread($obj['fp'],$len);
				$pos+=$len;
			}
			$read_items=floor($read_bytes/12);
		}
		$head=unpack('N*',substr($data,0,12));
		$tail=unpack('N*',substr($data,$read_bytes-12,12));
		$head[1]=ipv4_signed_to_unsigned($head[1]);
		$head[2]=ipv4_signed_to_unsigned($head[2]);
		$tail[1]=ipv4_signed_to_unsigned($tail[1]);
		$tail[2]=ipv4_signed_to_unsigned($tail[2]);
		
		if($head[1]>$ip){
			if($current_right_part==$current_process_part) break;
			$current_right_part=$current_process_part;
		}
		else if($tail[2]<$ip){
			if($current_left_part==$current_process_part) break;
			$current_left_part=$current_process_part;
		}
		else if($head[2]<$ip && $tail[1]>$ip){
			$inner_left=0;
			$inner_right=$read_items;
			while($inner_left<$inner_right){
				$inner_current=floor(($inner_left+$inner_right)/2);
				$node=unpack('N*',substr($data,$inner_current*12,12));
				$node[1]=ipv4_signed_to_unsigned($node[1]);
				$node[2]=ipv4_signed_to_unsigned($node[2]);
				
				if($node[1]>$ip){
					if($inner_right==$inner_current) break;
					$inner_right=$inner_current;
				}
				else if($node[2]<$ip){
					if($inner_left==$inner_current) break;
					$inner_left=$inner_current;
				}
				else{
					$ret=ipv4_area_get_area_string($obj,$node[3]);
					break;
				}
			}
			break;
		}
		else if($head[1]<=$ip && $head[2]>=$ip){
			$ret=ipv4_area_get_area_string($obj,$head[3]);
			break;
		}
		else if($tail[1]<=$ip && $tail[2]>=$ip){
			$ret=ipv4_area_get_area_string($obj,$tail[3]);
			break;
		}
	}
	return $ret;
}
/**
 * 释放IP数据库
 * @param $object 由函数ipv4_area_create返回的表对象
 */
function ipv4_area_free($object){
	if($object['fp']) fclose($object['fp']);
}

/*
include('./ips.php'); //包含测试数组$test_ips，包含1760个需要查询所在地的IP地址
$s=microtime(true);
$object=ipv4_area_create('./ip_utf8.dat');//初始化数据库
if($object['status']==IPV4_AREA_ERROR_SUCCESS){
	foreach($test_ips as $ip){ //循环查询每一个IP的所在地
		$area=ipv4_area_search($object,$ip);
		echo "IP:{$ip}<BR>AREA:{$area}<BR><BR>\n";
	}	
}
ipv4_area_free($object); //释放对象
echo microtime(true)-$s; //当创建时参数index_read_parts=1时，查询1760条IP所在地仅需3.68秒（上网本）
*/
?>