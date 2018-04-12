<?php
session_start();
@$s_login=$_SESSION['login'];
if (isset($s_login)&&$s_login!="") {
  header('Location: panel.php');
  die('Redirect To Panel!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>后台登录</title>
<link href="Css/default.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="js/themes/icon.css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.js"></script>

<script type="text/javascript" src='js/outlook2.js'> </script>
</head>

<body>
<div id="loginWin" class="easyui-window" title="后台登录" style="width:320px;height:220px;padding:5px;"
   minimizable="false" maximizable="false" resizable="false" collapsible="false" closable="false">
    <div class="easyui-layout" fit="true">
            <div region="center" border="false" style="padding:5px;background:#fff;border:1px solid #ccc;">
        <form id="loginForm" method="post">
            <div style="padding:5px 0;">
                <label>用户名: admin</label>
            </div>
            <div style="padding:5px 0;">
                <label for="password">密&nbsp;&nbsp;&nbsp;码:</label>
                <input type="password" name="password" style="width:180px;">
            </div>
            <div style="padding:5px 0;">
                <label for="vcode">验证码:</label>
                <input type="text" name="vcode" style="width:60px;" maxlength="4">
                <img id="captcha" title="点击刷新" src="captcha.php" onclick="this.src='captcha.php?'+Math.random()" style="cursor:pointer;">
            </div>
             <div style="padding:5px 0;text-align: center;color: red;" id="showMsg"></div>
        </form>
            </div>
            <div region="south" border="false" style="text-align:right;padding:0px 0;">
                <a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onclick="login()">登录</a>
                <a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="cleardata()">重置</a>
            </div>
    </div>
</div>
</body>
<script type="text/javascript">
document.onkeydown = function(e){
    var event = e || window.event;  
    var code = event.keyCode || event.which || event.charCode;
    if (code == 13) {
        login();
    }
}
window.onload=function(){
    $("input[name='password']").focus();
}
function cleardata(){
    $('#loginForm').form('clear');
}
function captchaLoad(){
    $('#captcha').click();
    $("input[name='vcode']").val('');
}
function login(){
    if($("input[name='password']").val()==""){
         $("#showMsg").html("密码为空，请输入");
         $("input[name='password']").focus();
    }else if($("input[name='vcode']").val()==""){
         $("#showMsg").html("验证码为空，请输入");
         $("input[name='vcode']").focus();
    }else if($("input[name='vcode']").val().length<4){
         $("#showMsg").html("验证码有误，请重新输入");
         captchaLoad();
         $("input[name='vcode']").focus();
    }else{
            $("#showMsg").html('登录中......');
            //ajax异步提交
            $.ajax({            
                  type:"POST",   //post提交方式默认是get
                  url:"api/login.php", 
                  data:$("#loginForm").serialize(),   //序列化               
                  error:function(request) {      // 设置表单提交出错                 
                      $("#showMsg").html(request);
                  },
                  success:function(data) {
                      if (data=="Login Success!") {
                        $("#showMsg").html('登录成功!');
                        setTimeout("loginSuccess()",1000);
                      }else if (data=="Password Error!") {
                        $("#showMsg").html('密码有误，请重新输入');
                        captchaLoad();
                        $("input[name='password']").val('');
                        $("input[name='password']").focus();
                      }else if (data=="VCode Error!") {
                        $("#showMsg").html('验证码有误，请重新输入');
                        captchaLoad();
                        $("input[name='vcode']").focus();
                      }else{
                        $("#showMsg").html('未知错误：'+data);
                      }
                  }
            });
        }
}
function loginSuccess(){
    document.location = "panel.php";
}
</script>
</html>