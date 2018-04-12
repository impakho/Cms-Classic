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
			<!-- UY BEGIN -->
			<div id="uyan_frame"></div>
			<script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js"></script>
			<!-- UY END -->
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