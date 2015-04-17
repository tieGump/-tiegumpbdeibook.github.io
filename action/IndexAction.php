<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-4-2
 * Time: 下午3:21
 */

/**首页的动作
 * Class Index
 * @author tie.Gump
 */
class IndexAction extends Action{
    function __construct(){
        parent::__construct();
    }

    /**
     * 首页显示
     */
    function indexAction(){
//        print_r($_SESSION);
        $this->unshowbread=true;
        $user=new User();
        $this->user_name=$user->getPublicNameFromId($_SESSION['admin']['id']);
        $aui=new AutoUserInfo();
        $aui->updateUser();
        $aui->updateGroup();
//        =$_SESSION['admin']['publicname'];
        $this->_tpl='index.tpl.html';
    }
}