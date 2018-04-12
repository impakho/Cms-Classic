<?php
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
<?php
@$g_ID=trim($_GET['ID']);
if (!isset($g_ID)||$g_ID==""||!is_numeric($g_ID)) {
  header('Location: Index.php');
  die('Invalid Argument!');
}
@$g_ClassID=trim($_GET['ClassID']);
if (!isset($g_ClassID)||$g_ClassID==""||!is_numeric($g_ClassID)) {
  header('Location: Index.php');
  die('Invalid Argument!');
}

$listRight='';
$result=RunDB("select * from detail where ID='".$g_ID."' and ClassID='".$g_ClassID."'");
if (mysql_num_rows($result)>0) {
  RunDB("update detail set Count=Count+1 where ID='".$g_ID."' and ClassID='".$g_ClassID."'");
  $row=mysql_fetch_assoc($result);
  $listRight.='   <div class="list_right_title">'.$row['Title'];
  $listRight.='</div><div class="list_right_info">';
  $listRight.='作者：'.$row['Author'].'　　';
  $listRight.='来源：'.$row['Source'].'　　';
  $listRight.='发布日期：'.format_date_time_utc($row['CreateTime']).'　　';
  $listRight.='最后更新：'.format_date_time_utc($row['UpdateTime']).'　　';
  $listRight.='浏览次数：'.($row['Count']+1).'</div>';
  $listRight.='<div class="list_right_main" style="word-break:break-all;word-wrap:break-word;">'.$row['Content'].'</div>';
}else{
  header('Location: Index.php');
  die('Invalid Argument!');
}
echo $listRight;

$bn='     <div id="bn">';

$result=RunDB("select ID,Title from detail where ID > ".$g_ID." and ClassID='".$g_ClassID."' order by ID limit 1");
$bn.='<div id="bn_b">';
if (mysql_num_rows($result)>0) {
  $row=mysql_fetch_assoc($result);
  $bn.='上一篇 > ：<a href="Detail.php?ID='.$row['ID'].'&ClassID='.$g_ClassID.'">'.$row['Title'].'</a>';
}else{
  $bn.='上一篇 > ：这是本分类下的第一篇文章';
}
$bn.='</div>';

$result=RunDB("select ID,Title from detail where ID < ".$g_ID." and ClassID='".$g_ClassID."' order by ID desc limit 1");
$bn.='<div id="bn_n">';
if (mysql_num_rows($result)>0) {
  $row=mysql_fetch_assoc($result);
  $bn.='下一篇 > ：<a href="Detail.php?ID='.$row['ID'].'&ClassID='.$g_ClassID.'">'.$row['Title'].'</a>';
}else{
  $bn.='下一篇 > ：这是本分类下的最后一篇文章';
}
$bn.='</div>';

$bn.='</div>';
echo $bn;
?>
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