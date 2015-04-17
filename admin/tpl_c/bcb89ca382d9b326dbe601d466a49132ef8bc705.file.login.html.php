<?php /* Smarty version Smarty-3.1.17, created on 2015-04-16 14:48:32
         compiled from "F:\Web\bdei_book\admin\tpl\login.html" */ ?>
<?php /*%%SmartyHeaderCode:17294552e231bc4f9c6-82548752%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bcb89ca382d9b326dbe601d466a49132ef8bc705' => 
    array (
      0 => 'F:\\Web\\bdei_book\\admin\\tpl\\login.html',
      1 => 1429166902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17294552e231bc4f9c6-82548752',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_552e231bdf3926_76239154',
  'variables' => 
  array (
    'tpl' => 0,
    'test' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_552e231bdf3926_76239154')) {function content_552e231bdf3926_76239154($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Panelo - Free Admin Template</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['tpl']->value;?>
resources/css/style.css" />
</head>
<body>
<div id="loginpanelwrap">
  	
	<div class="loginheader">
    <div class="logintitle"></div>
    </div>

     
    <div class="loginform">
    <?php echo $_smarty_tpl->tpl_vars['test']->value;?>

    	<?php if ($_smarty_tpl->tpl_vars['message']->value) {?><div style="align-content:center;padding-left: 100px;"><font color="red"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</font></div><?php }?>
        <form method="post" action="/admin/login/doLogin">
        <div class="loginform_row">
        <label>用户名:</label>
        <input type="text" class="loginform_input" name="user_name" />
        </div>
        <div class="loginform_row">
        <label>密码:</label>
        <input type="password" class="loginform_input" name="user_pwd" />
        </div>
        <div class="loginform_row">
        <input type="submit" class="loginform_submit" value="登录" />
        </div> 
        <div class="clear"></div>
        </form>
    </div>


</div>

    	
</body>
</html><?php }} ?>
