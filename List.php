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
<script type="text/javascript" src="Js/ks-switch.pack.js"></script>
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
<?php
@$g_ID=trim($_GET['ID']);
if (!isset($g_ID)||$g_ID==""||!is_numeric($g_ID)) {
  header('Location: Index.php');
  die('Invalid Argument!');
}

function findChildID($list, $id){
  foreach ($list as $key => $value) {
    if ($value['ParentID']==$id) {
      return findChildID($list, $value['ID']);
      break;
    }
  }
  return $id;
}

$childID=findChildID($list, $g_ID);
if ($g_ID!=$childID) {
  header('Location: List.php?ID='.$childID);
  die('Redirect To Child!');
}

function getParentList($list, $id) {
  $crumb='';
  foreach ($list as $key => $value) {
    if ($id=="0"&&$value['ID']=="1"||$id=="1") {
      $crumb.='<li><a href="Index.php">'.$value['Name'].'</a></li>';
      break;
    }
    if ($value['ID']==$id) {
      $crumb.='<li><a href="List.php?ID='.$value['ID'].'">'.$value['Name'].'</a></li>';
      if ($value['ID']!="1") {
        $crumb=getParentList($list, $value['ParentID']).$crumb;
      }
    }
  }
  return $crumb;
}

$crumb='          <ul id="crumbs">';
foreach ($list as $key => $value) {
  if ($value['ID']==$g_ID) {
    if ($value['ID']!="1") {
      $crumb.=getParentList($list, $value['ParentID']);
    }
    $crumb.='<li><a href="List.php?ID='.$value['ID'].'">'.$value['Name'].'</a></li>';
    break;
  }
}
$crumb.='</ul>';
echo $crumb;
?>
					<div class='list_right_main'>
<?php
$listRight='';
foreach ($list as $key => $value) {
  if ($value['ID']==$g_ID) {
    if ($value['Type']=="1") {
      $result=RunDB("select * from list_detail where ID='".$value['ID']."'");
      if (mysql_num_rows($result)>0) {
        $row=mysql_fetch_assoc($result);
        $listRight.='          <div class="list_right_title">'.$row['Title'];
        $listRight.='</div><div id="list_right_content" style="word-break:break-all;word-wrap:break-word;">'.$row['Content'].'</div>';
      }
    }else{
      @$g_p=trim($_GET['p']);
      if (!isset($g_p)||$g_p==""||!is_numeric($g_p)) {
        header('Location: List.php?ID='.$g_ID.'&p=1');
        die('Redirect To First Page!');
      }
      $result=RunDB("select ID,CreateTime,Title from detail where ClassID='".$value['ID']."' order by CreateTime desc");
      if ($g_p<=0||mysql_num_rows($result)<($g_p-1)*20) $g_p="1";
      $listRight.='          <div class="list_right_title">'.$value['Name'];
      $listRight.='</div><ul class="list_txt">';
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
          $listRight.='<a href="Detail.php?ID='.$detail[$i]['ID'].'&ClassID='.$value['ID'].'"  target="_blank"';
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
          $listRight.='  <a href="List.php?ID='.$g_ID.'&p='.$pa.'">'.$pa.'</a>  ';
        }
        $listRight.='  <span class="current">'.$g_p.'</span>  ';
        for ($pb=($g_p+1);$pb<=($g_p+$pbp+5);$pb++){
          if ($pb>ceil(mysql_num_rows($result)/20)) break;
          $listRight.='  <a href="List.php?ID='.$g_ID.'&p='.$pb.'">'.$pb.'</a>  ';
        }
        $listRight.=' </div>';
      }else{
        $listRight.='<li>暂无记录</li></ul>';
        $listRight.='<div class="pagination"><span class="current">0</span></div>';
      }
    }
    break;
  }
}
if ($listRight==''){
  header('Location: Index.php');
  die('Invalid Argument!');
}else{
  echo $listRight;
}
?>
					</div>
				</div>
			</div>
			<div class="list_left">
<?php
function getParentName($list, $id) {
  $name='';
  foreach ($list as $key => $value) {
    if ($value['ID']==$id) {
      $name=$value['Name'];
      break;
    }
  }
  return $name;
}

function getChildName($list, $id, $selectId) {
  $listLeft='';
  foreach ($list as $key => $value) {
    if ($value['ParentID']==$id) {
      if ($value['ID']==$selectId) {
        $listLeft.='<li class="selected"><a href="List.php?ID='.$value['ID'];
        $listLeft.='"><span class="left_menu0">'.$value['Name'].'</span></a></li>';
      }else{
        $listLeft.='<li><a href="List.php?ID='.$value['ID'];
        $listLeft.='"><span class="left_menu0">'.$value['Name'].'</span></a></li>';
      }
    }
  }
  return $listLeft;
}

$listLeft='       <div class="content"><div class="menu_title">';
foreach ($list as $key => $value) {
  if ($value['ID']==$g_ID) {
    if ($value['ParentID']=="0") {
      $listLeft.=$value['Name'];
      $listLeft.='</div>';
    }else{
      $listLeft.=getParentName($list, $value['ParentID']);
      $listLeft.='</div><ul class="menu">';
      $listLeft.=getChildName($list, $value['ParentID'], $value['ID']);
      $listLeft.='</ul>';
    }
    break;
  }
}
$listLeft.='</div>';
echo $listLeft;
?>
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