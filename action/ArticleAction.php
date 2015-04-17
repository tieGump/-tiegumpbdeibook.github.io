<?php

/**文章动作
 * Class ArticleAction
 * @author tie.Gump
 */
class ArticleAction extends Action {
    /**
     * 获取列表
     */
    function getListAction(){
        $article= new Article();
        $article_list=$article->getList($_GET);
        $info=$article_list['info'];
        $user=new User();
        $catalog=new Catalog();
        foreach($info as $key=>$value){
            $info[$key]['job_number']=$user->getJobFromId($value['admin_id']);
            $info[$key]['url']='/usiadmin/staff_train/article/show/article_id/'.$value['words_id'];
            $info[$key]['edit_url']='/usiadmin/staff_train/article/change/article_id/'.$value['words_id'];
            $info[$key]['edit_permission']=$catalog->checkPermission($value['rw_groups_id'],$_SESSION['group_id']);
            $info[$key]['delete_permission']=$catalog->checkPermission($value['rwd_groups_id'],$_SESSION['group_id']);
        }

        $this->info=$info;
        $this->page_info=$article_list['page'];
        $dir_id=isset($_GET['dir_id'])?$_GET['dir_id']:'';
        $this->option=$catalog->getOption($dir_id);
//        echo $article_list['page_info'];exit;
        $this->bread=$catalog->breadCrumbs(isset($_GET['dir_id'])?$_GET['dir_id']:0,'/usiadmin/staff_train/article/getlist','首页');
        $this->dir_id=$dir_id;
        $this->_tpl='article_list.tpl.html';
    }

    /**
     * 显示产品
     */
    function showAction(){
        if(!isset($_GET['article_id'])||!$_GET['article_id'])
            die('没有指定的产品id');
        $article_id=$_GET['article_id'];
        $article=new Article();
        $catalog=new Catalog();
        $one=$article->getOne($article_id);
        if(!$one)
            die('指定的产品不存在！！！');
        if(!Catalog::checkPermission($one['r_groups_id'],$_SESSION['group_id'])){
            die('没有阅读的权限');
        }
        $user=new User();
        $this->one=$one;

        $this->edit_permission=Catalog::checkPermission($one['rw_groups_id'],$_SESSION['group_id']);
        $this->delete_permission=Catalog::checkPermission($one['rwd_groups_id'],$_SESSION['group_id']);
        $this->addJobId=$user->getJobFromId($one['admin_id']);
        $this->changeJobId=$user->getJobFromId($one['last_admin_id']);
        $tmp=$article->getCatalog($article_id);
        $this->bread=$catalog->breadCrumbs($tmp[0]['dir_id'],'/usiadmin/staff_train/article/getlist','首页');
        $file_info=$article->getFile($article_id);
        $file_str='';
        foreach($file_info as $value){

            $file_info=$this->getFileInfo($value['annex_file_name']);
            $file_str.='<input type="hidden" name="annex_files_name[]" value="'.$value['annex_file_name'].'">
                        <a href="/usiadmin/staff_train/article/showfile/file_id/'.$value['annex_id'].'" target="_blank">
                            '.$file_info['file_name'].'
                        </a><br />';
        }
        $this->file_info=$file_str;
        $this->_tpl='article_read.html';
    }

    /**获取文件的信息
     * @param $annex_file_name
     * @return array
     */
    private function getFileInfo($annex_file_name){
        $tmp= explode('.',basename($annex_file_name));
        $file_name=ascii2string($tmp[0],'_');
        $file_name=iconv('GBK','UTF-8',$file_name);
        return array('file_name'=>$file_name,'file_type'=>strtolower($tmp[1]),'file_path'=>UPLOAD_DIR.$annex_file_name);
    }

    /**显示文件
     * @return bool
     */
    function showfileAction(){
        if(!isset($_GET['file_id'])||!$_GET['file_id']){
            return false;
        }

        $article=new Article();
        $file_array=$article->getFile('',$_GET['file_id']);
        if(!$file_array)return;
        $file_array=$file_array[0];
        $file_info=$this->getFileInfo($file_array['annex_file_name']);

        $file=new File();
        $file->showFile($file_info);

    }

    /**
     * 修改文章
     */
    function changeAction(){
        $catalog_id='';
        $article_id='';
        $this->show=false;
        $this->title='添加文章';
        $article=new Article();
        $catalog=new Catalog();
        $group=new Group();

        if($_POST){
            $this->title='修改文章';
            $catalog_info=$catalog->getBaseInfo($_POST['catalog_id']);
            $catalog_info=$catalog_info[0];
            if(!$_POST['article_id']){

                $article_id=$article->add($_POST,$catalog_info);
                if($_FILES)
                $article->addFile($_FILES,$article_id);
            }else{

                $article_id=$_POST['article_id'];
                if(isset($_POST['files_base']))
                $article->deleteFile($article_id,$_POST['files_base']);
                else
                    $article->deleteFile($article_id);
                if($_FILES)
                $article->addFile($_FILES,$article_id);

                $article->dropCatalog($article_id);
                $article->change($_POST,$article_id,$catalog_info);
            }

            $article->addToCatalog($article_id,$_POST['catalog_id'],1);

            if(isset($_POST['dirs_ids'])&&$_POST['dirs_ids']){
                foreach($_POST['dirs_ids'] as $value){
                    $article->addToCatalog($article_id,$value,0);
                }
                ;
            $article->addReadPermission($article_id,$catalog->getBaseInfo($_POST['dirs_ids']));

            }

        }


        $this->all_group=$group->createOption();
//        $catalog_id=$_GET['catalog_id'];

        if(isset($_GET['article_id'])&&$_GET['article_id']||$article_id){
            $article_id=$article_id?$article_id:$_GET['article_id'];
            $this->show=true;
            $this->one=$one=$article->getOne($article_id);
//            print_r($one);
            $article_catalog=$article->getCatalog($article_id);
            if(!$article_catalog)
                return ;
            $article_catalog_other=$article->getCatalog($article_id,'',0);
            $other_catalog='';
            foreach($article_catalog_other as $value){
                $other_catalog.='<option value="'.$value['dir_id'].'" selected>'.$value['dir_name'].'</option>';
            }
            $file_info=$article->getFile($article_id);
            $file_str='';
            foreach($file_info as $value){
                $file_info=$this->getFileInfo($value['annex_file_name']);
                $file_str.='<div><input name="files_base[]" type="hidden" value="'.$value['annex_id'].'"/>'.$file_info['file_name'].' <a href="JavaScript:void(0)" onClick="div_remove(this);"><img src="'.$this->tpl.'resources/images/icons/u20.png" border="0" /></a></div>';
            }
            $this->other_catalog=$other_catalog;
            $catalog_id=$article_catalog[0]['dir_id'];


            $this->file_str=$file_str;
            $this->$message_mark='<font color="green">'.$this->title.'成功!!!</font>';
            $this->r_group=$group->createOption($one['r_groups_id'],'selected');
            $this->rw_group=$group->createOption($one['rw_groups_id'],'selected');
            $this->rwd_group=$group->createOption($one['rwd_groups_id'],'selected');
            $this->title='修改文章';
        }




        $catalog_id=$catalog_id?$catalog_id:(isset($_GET['dir_id'])?$_GET['dir_id']:0);

        $catalog_up=$catalog->getUp($catalog_id);
        $catalog_select=$catalog->createUpSelect($catalog_up,'changeCatalog(this)');
//        krsort($catalog_select);
        $catalog_str='';
        foreach($catalog_select as $value){
            $catalog_str='<div name="up">'.$value.$catalog_str.'</div>';
        }

        $str='';
        foreach($catalog->getBaseInfo('',$catalog_id) as $value){
            $str.='<option value="'.$value['dir_id'].'">'.$value['dir_name'].'</option>';
        }
        $tmp='';
        if($str){
            $tmp= '&nbsp;<select onchange="changeCatalog(this)"><option value="">请选择目录</option>'.$str.'</select>';
        }
        $this->catalog_str=join('&nbsp;',$catalog_select).$tmp;
        $this->catalog_tree=$catalog->getOption('','','rw');
        $this->catalog_id=$catalog_id;
        $tmp=$catalog->getBaseInfo($catalog_id);
        $this->up_catalog_id=$tmp[0]['parent_id'];
        $this->article_id=$article_id;
        $this->bread=$catalog->breadCrumbs($catalog_id,'/usiadmin/staff_train/article/getlist','首页');
        $this->_tpl='article_manage.html';
    }

    /**删除
     * @return bool
     */
    function dropAction(){
        if(!isset($_GET['article_id'])||!$_GET['article_id']){
            return false;
        }
        $article=new Article();
        $article->dropOne($_GET['article_id']);
    }
} 