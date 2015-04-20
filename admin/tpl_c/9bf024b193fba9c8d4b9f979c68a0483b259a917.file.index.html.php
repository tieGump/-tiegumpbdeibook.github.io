<?php /* Smarty version Smarty-3.1.17, created on 2015-04-20 13:34:42
         compiled from "F:\Web\bdei_book\admin\tpl\index.html" */ ?>
<?php /*%%SmartyHeaderCode:6501552f5e72731e31-66926718%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bf024b193fba9c8d4b9f979c68a0483b259a917' => 
    array (
      0 => 'F:\\Web\\bdei_book\\admin\\tpl\\index.html',
      1 => 1429508081,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6501552f5e72731e31-66926718',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_552f5e727563d4_19473371',
  'variables' => 
  array (
    'admin_name' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_552f5e727563d4_19473371')) {function content_552f5e727563d4_19473371($_smarty_tpl) {?><body>
<div id="panelwrap">

    <div class="header">
        <div class="title"><a href="#">管理系统</a></div>

        <div class="header_right">Welcome <?php echo $_smarty_tpl->tpl_vars['admin_name']->value;?>
 <a href="#" class="settings">Settings</a> <a
                href="/admin/login/loginout" class="logout">Logout</a></div>


        <div class="center_content">
            <div id="right_wrap">
                <div id="right_content">
                    <iframe id="right_content_iframe" src="/admin/Book" frameBorder=0 scrolling=no width="100%" onload="iFrameHeight()">

                    </iframe>
                </div>
            </div>
            <!-- end of right content-->


            <div class="sidebar" id="sidebar">
                <h2>基本设置</h2>

                <ul>
                    <li><a href="#" class="selected">Main page</a></li>
                    <li><a href="#">Template settings</a></li>
                    <li><a href="#">Add page</a></li>
                    <li><a href="#">Edit section</a></li>
                    <li><a href="#">Templates</a></li>
                    <li><a href="#">Clients</a></li>
                </ul>

                <h2>图书管理</h2>
                <ul>
                    <li><a href="/admin/book">书籍管理</a></li>
                    <li><a href="/admin/bookType">图书类型管理</a></li>
                    <li><a href="/admin/book/add">新增图书</a></li>
                    <li><a href="#">Docs and info</a></li>
                </ul>

                <h2>用户设置</h2>

                <ul>
                    <li><a href="/admin/user">用户管理</a></li>
                    <li><a href="/admin/user/permission">用户权限管理</a></li>
                    <li><a href="/admin/user/add">增加用户</a></li>
                    <li><a href="/admin/user/changePassword">修改密码</a></li>
                    <li><a href="/admin/group">用户组管理</a></li>
                    <li><a href="/admin/group/permission">用户组权限管理</a></li>
                </ul>

                <h2>Text Section</h2>

                <div class="sidebar_section_text">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua.
                </div>

            </div>


            <div class="clear"></div>
        </div>
        <!--end of center_content-->

        <div class="footer">
            Panelo - Free Admin Collect from <a href="http://www.mycodes.net/" title="源码之家" target="_blank">源码之家</a>
        </div>

    </div>
</div>
<script type="text/javascript" language="javascript">
    function iFrameHeight() {
        var ifm = document.getElementById("right_content_iframe");
        var subWeb = document.frames ? document.frames["right_content_iframe"].document :
                ifm.contentDocument;
        if (ifm != null && subWeb != null) {
            ifm.height = subWeb.body.scrollHeight;
        }
    }
    $('ul li a').click(function(){
        var href=$(this).attr('href');
        $('#right_content_iframe').attr('src',href);
        return false;
    });
</script>

</body>
</html><?php }} ?>
