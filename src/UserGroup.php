<?php

/**用户分组
 * Class UserGroup
 * @author tie.Gump
 */
class UserGroup extends Mode{
    function __construct(){
        $this->_table='bdei_user_group';
        parent::__construct();
    }

}