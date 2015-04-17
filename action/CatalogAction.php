<?php

/**类别的动作
 * Class CatalogAction
 * @author tie.Gump
 */
class CatalogAction extends Action{
    private $_catalog;
    function __construct(){
        parent::__construct();
        $this->_catalog=new Catalog();
    }

    /**
     * 首页左边
     */
    function indexAction(){
        $catalog=$this->_catalog;
        $this->left_list=$catalog->getListInfo();
        $this->_tpl='left_menu.tpl.html';
    }

    /**分类管理
     * @param string $catalog_id_in
     */
    function manageAction($catalog_id_in=''){
        $this->deleteAction();
        $this->_tpl='dir_admin.tpl.html';
        $catalog=$this->_catalog;
        $this->doChange();
        $group=new Group();
        $catalog_id=isset($_GET['catalog_id'])?$_GET['catalog_id']:0;
        $catalog_id=$catalog_id_in?$catalog_id_in:$catalog_id;
        $this->tree=$catalog->getTree($catalog_id);//树形结构
        $this->b_c=$catalog->breadCrumbs($catalog_id);
        $this->dir_info=$one=$catalog->getOne($catalog_id);//这个目录的基本信息
        $this->delete=false;
        if($one){
            if(!$catalog->checkPermission($one['rw_groups_id'],$_SESSION['group_id'])){
                die("您没有编辑此页面的权限");
            }
            $this->r_option=$group->createOption($one['r_groups_id'],'selected');
            $this->rw_option=$group->createOption($one['rw_groups_id'],'selected');
            $this->rwd_option=$group->createOption($one['rwd_groups_id'],'selected');
            $this->delete=$catalog->checkPermission($one['rwd_groups_id'],$_SESSION['group_id']);
        }
        $this->group_option=$group->createOption();//创建所有用户分组的option下面的基本上都是
        $this->bread ='';
    }

    /**
     * 修改
     */
    function doChange(){
        if(!$_POST)
            return;
        $catalog=$this->_catalog;
//        $catalog->addD
        if(isset($_POST['add_class_name'])&&$_POST['add_class_name']){
            $_POST['rwd_permission']=$_POST['rw_permission']=$_POST['r_permission']=($_SESSION['group_id']==1)?'1':'1,'.$_SESSION['group_id'];
            $catalog_add_id=$catalog->addList($_POST,isset($_GET['catalog_id'])?$_GET['catalog_id']:0);
            $catalog->addDes($catalog_add_id,'');
            header('Location: /usiadmin/staff_train/catalog/manage?catalog_id='.$catalog_add_id);

        }elseif(isset($_POST['submit'])&&$_POST['submit_action']=='Update'){

            if(!$_GET['catalog_id'])
                return;
            $r_permission=join(',',(array)$_POST['r_groups_ids']);
            $rw_permission=join(',',(array)$_POST['rw_groups_ids']);
            $rwd_permission=join(',',(array)$_POST['rwd_groups_ids']);
            $_POST['r_permission']=$r_permission;
            $_POST['rw_permission']=$rw_permission;
            $_POST['rwd_permission']=$rwd_permission;
            $catalog->changeList($_POST,$_GET['catalog_id']);
            $catalog->changeDes($_GET['catalog_id'],$_POST['dir_description']);
            if(isset($_POST['permissions_all_child'])&&$_POST['permissions_all_child']){
                $catalog->changeSunPermission($_GET['catalog_id'],$r_permission,$rw_permission,$rwd_permission);
            }
        }elseif(isset($_POST['action'])&&$_POST['action']=='search'){

            $this->search=$catalog->searchCatalog($_POST['search_class_name']);
        }
        $cache=new MysqlCache();
        $cache->flush();
    }

    /**
     * 删除
     */
    function deleteAction(){
        if(!isset($_POST['submit_action'])||$_POST['submit_action']!='Delete')
            return;

        $catalog=$this->_catalog;
        $info=$catalog->getOne($_GET['catalog_id']);
        $catalog_id=$info['parent_id'];
        $catalog->drop($_GET['catalog_id']);
        $cache=new MysqlCache();
        $cache->flush();
        header('Location: /usiadmin/staff_train/catalog/manage?catalog_id='.$catalog_id);
    }

    /**获取下一级别
     * @param string $catalog_id
     */
    function nextAction($catalog_id=''){
        if($catalog_id)
            $id=$catalog_id;
        else
            $id=$_GET['catalog_id'];
        if(!$id)return;
        $catalog=new Catalog();
        $info=$catalog->getBaseInfo('',$id);
        $str='';
        foreach($info as $value){
            $str.='<option value="'.$value['dir_id'].'">'.$value['dir_name'].'</option>';
        }
        if($str){
            echo '<select onchange="changeCatalog(this)"><option value="">请选择目录</option>',$str,'</select>';
        }
    }
} 