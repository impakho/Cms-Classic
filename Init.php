<?php
$GLOBALS["sql_con"]=NULL;

function OpenDB(){
	@$GLOBALS['sql_con']=mysql_connect("127.0.0.1","root","123456");
	if (!$GLOBALS['sql_con']) die("Could not connect: ".mysql_error());
	mysql_select_db("cms_classic",$GLOBALS['sql_con']);
}

function RunDB($sql_query){
	mysql_query("SET NAMES 'utf8'");
	$result=mysql_query($sql_query);
	return $result;
}

function CloseDB(){
	mysql_close($GLOBALS['sql_con']);
}

function format_date($time){
	if (!is_numeric($time)){
		if (strpos($time,"-")===false) return '未知';
		$time=strtotime($time);
	}
	$t=time()-$time;
	$f=array(
		'31536000'=>'年',
		'2592000'=>'个月',
		'604800'=>'星期',
		'86400'=>'天',
		'3600'=>'小时',
		'60'=>'分钟',
		'1'=>'秒'
	);
	foreach ($f as $k=>$v){
		if (0 !=$c=floor($t/(int)$k)) {
			return $c.$v.'前';
		}
	}
}

function format_date_utc($time){
	if (!is_numeric($time)){
		if (strpos($time,"-")===false) return '未知';
		$time=strtotime($time);
	}
	return date('Y-m-d',$time);
}

function format_date_time_utc($time){
	if (!is_numeric($time)){
		if (strpos($time,"-")===false) return '未知';
		$time=strtotime($time);
	}
	return date('Y-m-d H:i:s',$time);
}
?>