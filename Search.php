<?php
@$g_Keyword=urldecode(trim($_GET['Keyword']));
@$p_Keyword=urlencode(trim($_POST['Keyword']));

if (!isset($g_Keyword)||$g_Keyword==""||$g_Keyword=="请输入您要搜索的关键词") {
  if (!isset($p_Keyword)||$p_Keyword==""||$p_Keyword=="请输入您要搜索的关键词") {
    header('Location: Index.php');
    die('Invalid Argument!');
  }else{
    header('Location: Search.php?Keyword='.$p_Keyword);
    die('Redirect To Search');
  }
}

include 'Init.php';
OpenDB();

$result=RunDB("select * from site");
if (mysql_num_rows($result)>0) {
  $site=mysql_fetch_assoc($result);
}else{
  die('Site Not Ready!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars($site['Title']); ?></title>
<meta name="Author" content="<?php echo htmlspecialchars($site['Author']); ?>" />
<meta name="Keywords" content="<?php echo htmlspecialchars($site['Keywords']); ?>" />
<meta name="Description" content="<?php echo htmlspecialchars($site['Description']); ?>" />
<link type="text/css" rel="stylesheet" media="screen" href="Css/Style.css" />

<script src="Js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="Js/myfocus.js" language="javascript" type="text/javascript"></script>
<script src="Js/img.js" language="javascript" type="text/javascript"></script>
</head>
<body>
<div class="wrap wrapfix">
	<div id="container">
		<div id="header">
      <div id="header_link">&nbsp;<!--<a href="">设为首页</a> | <a href="">加入收藏</a> | <a href="">联系我们</a>--></div>
			<div id="header_search"><form name="formLogin" method="post" action="Search.php"><input type="text" value="请输入您要搜索的关键词" onfocus="this.value=''" onblur="if(this.value==''){this.value='请输入您要搜索的关键词';}" name="Keyword" class="stext"><input type="submit" value="搜索" class="sbtn"></form></div>
		</div>
<?php
function getChildList($list, $id) {
  $menu='';
  foreach ($list as $key => $value) {
    if ($value['ParentID']==$id) {
      if ($menu=='') $menu.='<ul>';
      $menu.='<li><a href="List.php?ID='.$value['ID'].'">'.$value['Name'].'</a>';
      $menu.=getChildList($list,$value['ID']).'</li>';
    }
  }
  if ($menu!='') $menu.='</ul>';
  return $menu;
}

$result=RunDB("select * from list order by OrderID");
$menu='   <div id="menu">';
if (mysql_num_rows($result)>0) {
  $menu.='<ul id="nav">';
  $list=array();
  while ($row=mysql_fetch_assoc($result)) {
    array_push($list,$row);
  }

  foreach ($list as $key => $value) {
    if ($value['ID']!="1"&&$value['ParentID']=="0") {
      $menu.='<li><a href="List.php?ID='.$value['ID'].'">'.$value['Name'].'</a>';
      $menu.=getChildList($list, $value['ID']).'</li>';
    }else if ($value['ID']=="1") {
      $menu.='<li id="home"><a href="Index.php">'.$value['Name'].'</a></li>';
    }
  }
  $menu.='</ul>';
}
$menu.='</div>';
echo $menu;
?>
		<div id="banner">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1000" height="220" id="12" align=""> 
			<param name=movie value="Swf/top.swf"><param name=quality value=high><param name=wmode value=transparent> 
			<embed src="Swf/top.swf" quality=high wmode=transparent width="1000" height="220" name="12" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed> 
			</object>
		</div>
		<div class="list_main">
			<div class="list_right">
				<div class="content">
					<ul id="crumbs"><li><a href="Index.php">首页</a></li><li>搜索关键词[<?php echo htmlspecialchars($g_Keyword); ?>]结果</li></ul>
					<div class='list_right_main'>
<?php
$listRight='';
$listRight.='         <div class="list_right_title">符合关键词';
$listRight.='<span class="c1">['.htmlspecialchars($g_Keyword).']</span>的结果</div>';
@$g_p=trim($_GET['p']);
if (!isset($g_p)||$g_p==""||!is_numeric($g_p)) {
  header('Location: Search.php?Keyword='.urlencode($g_Keyword).'&p=1');
  die('Redirect To First Page!');
}
$result=RunDB("select ID,ClassID,CreateTime,Title from detail where Title like '%".
  mysql_real_escape_string($g_Keyword)."%' order by CreateTime desc");
if ($g_p<=0||mysql_num_rows($result)<($g_p-1)*20) $g_p="1";
$listRight.='<ul class="list_txt">';
if (mysql_num_rows($result)>0) {
  $detail=array();
  while ($row=mysql_fetch_assoc($result)) {
    array_push($detail,$row);
  }
  if (mysql_num_rows($result)>=$g_p*20) {
    $lastNum=$g_p*20;
  }else{
    $lastNum=mysql_num_rows($result);
  }
  for ($i=($g_p-1)*20; $i<$lastNum; $i++) { 
    if ($i%2==0) {
      $listRight.='<li>';
    }else{
      $listRight.='<li class="list_txt_bg">';
    }
    $listRight.='<span>'.format_date_utc($detail[$i]['CreateTime']).'</span>';
    $listRight.='<a href="Detail.php?ID='.$detail[$i]['ID'].'&ClassID='.$detail[$i]['ClassID'].'"  target="_blank"';
    $createFTime=format_date($detail[$i]['CreateTime']);
    if (strpos($createFTime,"秒")!==false||strpos($createFTime,"分钟")!==false||
      strpos($createFTime,"小时")!==false||strpos($createFTime,"天")!==false) {
      $listRight.=' style="font-weight:bold;"';
    }
    $listRight.='>'.$detail[$i]['Title'].'</a></li>';
  }
  $listRight.='<div class="pagination"> ';
  $pbp=0;
  for ($pa=($g_p-4);$pa<$g_p;$pa++){
    if ($pa<=0){
      $pbp++;
      continue;
    }
    $listRight.='  <a href="Search.php?Keyword='.urlencode($g_Keyword).'&p='.$pa.'">'.$pa.'</a>  ';
  }
  $listRight.='  <span class="current">'.$g_p.'</span>  ';
  for ($pb=($g_p+1);$pb<=($g_p+$pbp+5);$pb++){
    if ($pb>ceil(mysql_num_rows($result)/20)) break;
    $listRight.='  <a href="Search.php?Keyword='.urlencode($g_Keyword).'&p='.$pb.'">'.$pb.'</a>  ';
  }
  $listRight.=' </div>';
}else{
  $listRight.='<li>暂无记录</li></ul>';
  $listRight.='<div class="pagination"><span class="current">0</span></div>';
}
echo $listRight;
?>
					</div>
				</div>
			</div>
			<div class="list_left">
				<div class="content"></div>
			</div>
			<div style="clear:both;"></div>
		</div>
    <div id="footer"><?php echo $site['Footer']; ?></div>
	</div>
</div>
</body>
</html>
<?php
CloseDB();
?>