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
        $this->_tpl='index.html';
    }
}