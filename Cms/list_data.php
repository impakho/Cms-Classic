<?php
include '../Init.php';

session_start();
@$s_login=$_SESSION['login'];
if (!isset($s_login)||$s_login=="") {
  header('Location: index.php');
  die('Access Denied!');
}

@$g_action=trim($_GET['action']);

OpenDB();
if (isset($g_action)&&$g_action!="") {
  switch ($g_action) {
    case 'create':
      @$p_ParentID=trim($_POST['parentId']);
      if (isset($p_ParentID)&&$p_ParentID!="") {
        $result=RunDB("select ParentID from list where ID='".mysql_real_escape_string($p_ParentID)."'");
        if (mysql_num_rows($result)>0){
          RunDB("insert into list (ParentID,OrderID,Name,Type) values (".mysql_real_escape_string($p_ParentID).
            ",".(getMaxOrderID()+1).",'新分类',0)");
          $result=RunDB("select ID from list order by OrderID desc");
          $row=mysql_fetch_assoc($result);
          $res=array();
          $res['id']=$row['ID'];
          $res['text']="新分类";
          die(json_encode($res));
        }
      }
      break;

    case 'update':
      @$p_ID=trim($_POST['id']);
      @$p_Name=$_POST['text'];
      if (isset($p_ID)&&$p_ID!=""&&isset($p_Name)) {
        $result=RunDB("select ID from list where ID='".mysql_real_escape_string($p_ID)."'");
        if (mysql_num_rows($result)>0){
          RunDB("update list set Name='".mysql_real_escape_string($p_Name)."'".
            " where ID='".mysql_real_escape_string($p_ID)."'");
          $res=array();
          $res['id']=$p_ID;
          $res['text']=$p_Name;
          die(json_encode($res));
        }
      }
      break;

    case 'delete':
      @$p_ID=trim($_POST['id']);
      if (isset($p_ID)&&$p_ID!=""&&$p_ID!="1") {
        $result=RunDB("select ID from list where ID='".mysql_real_escape_string($p_ID)."'");
        if (mysql_num_rows($result)>0){
          RunDB("delete from list where ID='".mysql_real_escape_string($p_ID)."'");
          deleteID($p_ID);
        }
        $res=array();
        $res['success']=true;
        die(json_encode($res));
      }
      $res=array();
      $res['success']=false;
      die(json_encode($res));
      break;

    case 'move':
      @$p_ID=trim($_POST['id']);
      @$p_TargetID=trim($_POST['targetId']);
      @$p_Point=trim($_POST['point']);
      if (isset($p_ID)&&$p_ID!=""&&
        isset($p_TargetID)&&$p_TargetID!=""&&
        isset($p_Point)&&$p_Point!="") {
        $result=RunDB("select ID,ParentID from list order by OrderID");
        $rowID=array();
        if ($p_Point=="append") {
          $lastResult=RunDB("select ID from list where ParentID='".mysql_real_escape_string($p_TargetID)."'");
          $lastID=$p_TargetID;
          while ($lastRow=mysql_fetch_assoc($lastResult)) {
            $lastID=$lastRow['ID'];
          }
          while ($row=mysql_fetch_assoc($result)){
            if ($row['ID']!=$p_ID) array_push($rowID,$row['ID']);
            if ($row['ID']==$lastID) array_push($rowID,$p_ID);
          }
          RunDB("update list set ParentID='".mysql_real_escape_string($p_TargetID)."'".
            " where ID='".mysql_real_escape_string($p_ID)."'");
        }else if ($p_Point=="top") {
          while ($row=mysql_fetch_assoc($result)){
            if ($row['ID']==$p_TargetID) array_push($rowID,$p_ID);
            if ($row['ID']!=$p_ID) array_push($rowID,$row['ID']);
          }
          $parentResult=RunDB("select ParentID from list where ID='".mysql_real_escape_string($p_TargetID)."'");
          if (mysql_num_rows($parentResult)>0) {
            $parentRow=mysql_fetch_assoc($parentResult);
            RunDB("update list set ParentID=".$parentRow['ParentID']." where ID='".mysql_real_escape_string($p_ID)."'");
          }
        }else if ($p_Point=="bottom") {
          while ($row=mysql_fetch_assoc($result)){
            if ($row['ID']!=$p_ID) array_push($rowID,$row['ID']);
            if ($row['ID']==$p_TargetID) array_push($rowID,$p_ID);
          }
          $parentResult=RunDB("select ParentID from list where ID='".mysql_real_escape_string($p_TargetID)."'");
          if (mysql_num_rows($parentResult)>0) {
            $parentRow=mysql_fetch_assoc($parentResult);
            RunDB("update list set ParentID=".$parentRow['ParentID']." where ID='".mysql_real_escape_string($p_ID)."'");
          }
        }
        $i=0;
        foreach ($rowID as $key => $value) {
          $i++;
          RunDB("update list set OrderID=".$i." where ID='".mysql_real_escape_string($value)."'");
        }
        $res=array();
        $res['success']=true;
        die(json_encode($rowID));
      }
      break;

    default:
      break;
  }
}

function deleteID($ID){
  $result=RunDB("delete from list where ParentID='".mysql_real_escape_string($ID)."'");
  while ($row=mysql_fetch_assoc($result)){
    deleteID($row['ID']);
  }
}

function getMaxOrderID(){
  $result=RunDB("select OrderID from list order by OrderID desc");
  if (mysql_num_rows($result)>0){
    $row=mysql_fetch_assoc($result);
    return $row['OrderID'];
  }else{
    return 0;
  }
}

function getList($ID){
  $result=RunDB("select ID,Name from list where ParentID=".$ID." order by OrderID");
  $res=array();
  while ($row=mysql_fetch_assoc($result)){
    $nrow=array();
    $nrow['id']=$row['ID'];
    $nrow['text']=$row['Name'];
    $child=getList($row['ID']);
    if ($child!=0) $nrow['children']=$child;
    array_push($res,$nrow);
  }
  return $res;
}

$list=json_encode(getList(0));
$list=str_replace("}}","}]}",$list);
$list=str_replace("children\":{","children\":[{",$list);
echo $list;

CloseDB();
?>