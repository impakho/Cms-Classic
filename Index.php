<?php
include 'Init.php';
OpenDB();

$result=RunDB("select * from site");
if (mysql_num_rows($result)>0) {
  $site=mysqli_fetch_all($result);
}else{
  die('Site Not Ready!');
}

// 此处修改快速链接
// e.g: List.php?ID=0
$link1="#";
$link2="Feedback.php";
$link3="#";

// 此处修改首页栏目ID 显示数量 英文名称
$box1_ID="0";$box1_Num="5";$box1_Title_EN="";
$box2_ID="0";$box2_Num="7";$box2_Title_EN="";
$box3_ID="0";$box3_Num="6";
$box4_ID="0";$box4_Num="6";

function getBoxName($ID){
  $result=RunDB("select Name from list where ID=".$ID);
  if (mysql_num_rows($result)>0) {
    $row=mysql_fetch_assoc($result);
    return $row['Name'];
  }else{
    return "";
  }
}

function getBoxList($ID,$Num){
  $result=RunDB("select ID,ClassID,Title,CreateTime from detail where ClassID=".$ID." order by ID desc limit ".$Num);
  $res=array();
  while ($row=mysql_fetch_assoc($result)) {
    array_push($res,$row);
  }
  return $res;
}

$box1_Title=getBoxName($box1_ID);
$box1_List=getBoxList($box1_ID,$box1_Num);
$box2_Title=getBoxName($box2_ID);
$box2_List=getBoxList($box2_ID,$box2_Num);
$box3_Title=getBoxName($box3_ID);
$box3_List=getBoxList($box3_ID,$box3_Num);
$box4_Title=getBoxName($box4_ID);
$box4_List=getBoxList($box4_ID,$box4_Num);
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
<link rel="stylesheet" type="text/css" href="Css/jquery.flipcountdown.css" />

<script src="Js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="Js/myfocus.js" language="javascript" type="text/javascript"></script>
<script src="Js/MSClass.js" language="javascript" type="text/javascript"></script>
<script src="Js/img.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="Js/jquery.min.js"></script>
<script type="text/javascript" src="Js/jquery.flipcountdown.js"></script>
<script src="Js/jquery.superslides.2.1.1.js"></script>

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
      $menu.=getChildList($list,$value['ID']).'</li>';
    }else if ($value['ID']=="1") {
      $menu.='<li id="home"><a href="Index.php">'.$value['Name'].'</a></li>';
    }
  }
  $menu.='</ul>';
}
$menu.='</div>';
echo $menu;
?>
		<!--
		<div id="banner">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1000" height="220" id="12" align=""> 
			<param name=movie value="Swf/top.swf"><param name=quality value=high><param name=wmode value=transparent> 
			<embed src="Swf/top.swf" quality=high wmode=transparent width="1000" height="220" name="12" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed> 
			</object>
		</div>-->

		<div class="banner">
			<ul class="banner_pic">
				<!--首页2 bnner-->
<?php
$result=RunDB("select * from img where Display=1 and ClassID=1 order by ID");
if (mysql_num_rows($result)>0) {
  $img1Num=0;
  while ($img1=mysql_fetch_assoc($result)) {
    $img1Num++;
    if ($img1['Link']=="") {
      echo '				<li style="background-image:url('.$img1['Url'].')"></li>'."\n";
    }else{
      echo '				<li style="background-image:url('.$img1['Url'].')"><a href="'.$img1['Link'].'" target="_blank"></a></li>'."\n";
    }
  }
}else{
  die('Img1 Not Ready!');
}
?>
			</ul>
			<div class="banner_txtbg"></div>
			<div class="banner_txt"><ul><?php for ($i=0;$i<$img1Num;$i++) echo "<li></li>"; ?></ul></div>
			<ul class="banner_num"><?php for ($i=0;$i<$img1Num;$i++) echo "<li></li>"; ?></ul>
			<!--<a class="prev" href="javascript:void(0)"></a>
			<a class="next" href="javascript:void(0)"></a>-->
		</div>

		<script type="text/javascript">
			jQuery(".banner").slide({ titCell:".banner_num li", mainCell:".banner_pic",effect:"fold", autoPlay:true,trigger:"click",interTime:4000,delayTime:1000,
				//下面startFun代码用于控制文字上下切换
				startFun:function(i){
					jQuery(".banner .banner_txt li").eq(i).animate({"bottom":0}).siblings().animate({"bottom":-36});
				}
			});
		</script>


		<div id="main">
			<div id="main0">
				<a href="List.php?ID=<?php echo $box1_ID; ?>"><h3><?php echo $box1_Title; ?><span style="margin-left:5px;"><?php echo $box1_Title_EN; ?></span><span style="float:right;margin-right:10px;">更多</span></h3></a>
				<div class="notice">
					<div id="hottitle">
						<ul class="index_list_txt3" id="ulid">
<?php
$box1_Content='';
foreach ($box1_List as $key => $value) {
  $box1_Content.='<li><span>'.format_date_utc($value['CreateTime']).'</span>';
  $box1_Content.='<a href="Detail.php?ID='.$value['ID'].'&ClassID='.$value['ClassID'].'"';
  $box1_Content.=' title="'.$value['Title'].'" target="_blank"';
  $createFTime=format_date($value['CreateTime']);
    if (strpos($createFTime,"秒")!==false||strpos($createFTime,"分钟")!==false||
      strpos($createFTime,"小时")!==false||strpos($createFTime,"天")!==false) {
    $box1_Content.=' Style="font-weight:bold;"';
  }
  $box1_Content.='>'.$value['Title'].'</a></li>';
}
echo $box1_Content;
?>
						</ul>
					</div>
				</div>
				<div id="retroclockbox" style="margin-left:10px;margin-top:20px;"></div>
				<script type="text/javascript">$(function(){ $('#retroclockbox').flipcountdown(); });</script>
				<iframe width="225" scrolling="no" height="90" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&id=7"
				 style="margin-left:10px;margin-top:15px;margin-bottom:15px;"></iframe>
				<h4 style="cursor:default;">快速链接<span style="margin-left:5px;">Links</span></h4>
				<div class="h4main">
				<p><a href="<?php echo $link1 ?>" target="_blank"><img src="Images/btn1.jpg" /></a></p>
				<p><a href="<?php echo $link2 ?>" target="_blank"><img src="Images/btn2.jpg" /></a></p>
				<p><a href="<?php echo $link3 ?>" target="_blank"><img src="Images/btn3.jpg" /></a></p>
				<p>
				<SELECT 
                        onchange="if (this.options(this.selectedIndex).value!='#') window.open(this.options(this.selectedIndex).value);" 
                        name=flink28> <OPTION 
                          style="COLOR: #000000; BACKGROUND-COLOR: #ffffff" 
                          value=# selected>========= 常用友情链接 =========</OPTION>
<?php
$result=RunDB("select * from link order by ID");
if (mysql_num_rows($result)>0) {
  while ($link=mysql_fetch_assoc($result)) {
    echo '					<OPTION style="COLOR: #000000; BACKGROUND-COLOR: #ffffff"'."\n";
    echo '							value='.$link['Url'].'>'.$link['Title'].'</OPTION>'."\n";
  }
}
echo '				</SELECT>'."\n";
?>
				</p>
				</div>
			</div>


			<div id="main1">

				<a href="List.php?ID=<?php echo $box2_ID; ?>"><h2><?php echo $box2_Title; ?><span style="margin-left:5px;"><?php echo $box2_Title_EN; ?></span></h2></a>
				<div class="news">
					<div id="banner_box2" style="visibility:hidden;background:url(../Images/noticebg.jpg) repeat-x 0 0;">
						<div class="loading"><span>请稍候...</span></div>
						<ul class="pic">
<?php
$result=RunDB("select * from img where Display=1 and ClassID=2 order by ID");
if (mysql_num_rows($result)>0) {
  while ($img2=mysql_fetch_assoc($result)) {
    if ($img2['Link']=="") {
      echo '						<li><a href="javascript:void(0)"><img src="'.$img2['Url'].'" onerror="this.src=\'/Img/error/1.jpg\'" thumb="" alt="'.$img2['Title'].'" text="" /></a></li>'."\n";
    }else{
      echo '						<li><a href="'.$img2['Link'].'" target="_blank"><img src="'.$img2['Url'].'" onerror="this.src=\'/Img/error/1.jpg\'" thumb="" alt="'.$img2['Title'].'" text="" /></a></li>'."\n";
    }
  }
}else{
  die('Img2 Not Ready!');
}
?>
						</ul>
					</div>
<?php
$box2_Content='					<ul class="index_list_txt2">';
foreach ($box2_List as $key => $value) {
  $box2_Content.='<li><span>'.format_date_utc($value['CreateTime']).'</span>';
  $box2_Content.='<a href="Detail.php?ID='.$value['ID'].'&ClassID='.$value['ClassID'].'"';
  $box2_Content.=' title="'.$value['Title'].'" target="_blank"';
  $createFTime=format_date($value['CreateTime']);
    if (strpos($createFTime,"秒")!==false||strpos($createFTime,"分钟")!==false||
      strpos($createFTime,"小时")!==false||strpos($createFTime,"天")!==false) {
    $box2_Content.=' Style="font-weight:bold;"';
  }
  $box2_Content.='>'.$value['Title'].'</a></li>';
}
$box2_Content.='</ul>';
echo $box2_Content;
?>
				</div><div style="clear:both;"></div>

<div class="index_img">
	<div id="CSSBox">
		<ul id="CSSContent">
<?php
$result=RunDB("select * from img where Display=1 and ClassID=3 order by ID");
if (mysql_num_rows($result)>0) {
  while ($img3=mysql_fetch_assoc($result)) {
    if ($img3['Link']=="") {
      echo '			<li><img src="'.$img3['Url'].'" onerror="this.src=\'/Img/error/01.jpg\'" title=""/></li>'."\n";
    }else{
      echo '			<li><a href="'.$img3['Link'].'" target="_blank"><img src="'.$img3['Url'].'" onerror="this.src=\'/Img/error/01.jpg\'" title=""/></a></li>'."\n";
    }
  }
}else{
  die('Img3 Not Ready!');
}
?>
		</ul>
	</div>
	<script type="text/javascript"> 
	new Marquee(
	{
	MSClass:["CSSBox","CSSContent"],
	Direction : 2,
	Step: 0.3,
	Width: 725,
	Height: 130,
	Timer: 20,
	DelayTime : 3000,
	WaitTime  : 0,
	ScrollStep: 145,
	SwitchType: 0,
	AutoStart : true
	});
	</script>
</div>

				<div id="main2">
					<a href="List.php?ID=<?php echo $box3_ID; ?>"><h3><?php echo $box3_Title; ?></h3></a>
					<div class="newsmain">
<?php
$box3_Content='         <ul class="index_list_txt">';
foreach ($box3_List as $key => $value) {
  $box3_Content.='<li><span>'.format_date_utc($value['CreateTime']).'</span>';
  $box3_Content.='<a href="Detail.php?ID='.$value['ID'].'&ClassID='.$value['ClassID'].'"';
  $box3_Content.=' title="'.$value['Title'].'" target="_blank"';
  $createFTime=format_date($value['CreateTime']);
    if (strpos($createFTime,"秒")!==false||strpos($createFTime,"分钟")!==false||
      strpos($createFTime,"小时")!==false||strpos($createFTime,"天")!==false) {
    $box3_Content.=' Style="font-weight:bold;"';
  }
  $box3_Content.='>'.$value['Title'].'</a></li>';
}
$box3_Content.='</ul>';
echo $box3_Content;
?>
					</div>
				</div>
				<div id="main3">
					<a href="List.php?ID=<?php echo $box4_ID; ?>"><h3><?php echo $box4_Title; ?></h3></a>
					<div class="newsmain">
<?php
$box4_Content='         <ul class="index_list_txt">';
foreach ($box4_List as $key => $value) {
  $box4_Content.='<li><span>'.format_date_utc($value['CreateTime']).'</span>';
  $box4_Content.='<a href="Detail.php?ID='.$value['ID'].'&ClassID='.$value['ClassID'].'"';
  $box4_Content.=' title="'.$value['Title'].'" target="_blank"';
  $createFTime=format_date($value['CreateTime']);
    if (strpos($createFTime,"秒")!==false||strpos($createFTime,"分钟")!==false||
      strpos($createFTime,"小时")!==false||strpos($createFTime,"天")!==false) {
    $box4_Content.=' Style="font-weight:bold;"';
  }
  $box4_Content.='>'.$value['Title'].'</a></li>';
}
$box4_Content.='</ul>';
echo $box4_Content;
?>
					</div>
				</div>

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