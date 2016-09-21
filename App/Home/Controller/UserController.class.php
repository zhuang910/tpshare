<?php
/**
 * 用户控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

use BuiAdmin\Model\UserModel;

class UserController extends CommonController {

    /**
     * 默认
     */
    public function index() {
        //判断是否登录
        $this->checkLogin();
        
        $this->redirect("article");
    }

    /**
     * 我的文章
     */
    public function article() {
        //判断是否登录
        $this->checkLogin();

        $search = $_GET;
        $a_model = D("Article");

        //搜索条件
        $where['a.user_id'] = $_SESSION['es_user']['user_id'];
        if(!empty($search['cat_id'])) {
            $where['a.cat_id'] =  $search['cat_id'];
        }
        if(!empty($search['tit_kw'])) {
            $where['a.title'] =  array("LIKE",'%'.$search['tit_kw'].'%');
        }

        //分页
        $count = $a_model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->sample_show());
        
        //分页获取列表
        $article_list = $a_model->getList($where);
        foreach($article_list as $akey=>$value) {
            $article_list[$akey]['cat_name'] = M("category")->where("cat_id={$value[cat_id]}")->getfield('category_name');
            $article_list[$akey]['content'] = strip_tags(html_entity_decode($value['content']));
        }
        $this->assign('article_list',$article_list);

        //获取分类
        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $cat_list = D("Category")->getList(0,$search['cat_id'],true,0,$c_condition);
        $this->assign('cat_select',$cat_list);

        $this->assign('tit_kw',$search['tit_kw']);

        $this->display();
    }
    
    /**
     * 我的分类
     */
    public function category() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Category');
        $where = array();
        if($_GET['category_name']) {
            $where['category_name'] = array("LIKE",'%'.$_GET['category_name'].'%');
        }
        
        //分页获取列表
        $condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $list = $model->getList(0,0,false,0,$condition);
        $this->assign('category_list',$list);
        $this->assign('category_name',$_GET['category_name']);
        
        $this->display();
    }
    
    /**
     * 个人信息
     */
    public function profile() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("User");
        $user_info = D("User")->getInfo($_SESSION['es_user']['user_id']);
        
        $this->assign('user_info',$user_info);
        $this->display();
    }
    
    /**
     * 上传头像
     */
    public function uploads() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("User");
        if (!empty($_FILES)) {
            import("@.Think.UploadFile");
            $user_info = $model->getInfo($_POST['user_id']);
            
            $upload = new \Think\UploadFile();
            $upload->maxSize = 2048000;
            $upload->allowExts = array('jpg','jpeg','gif','png');
            $upload->savePath = "/portrait/";
            $upload->subName = $user_info['portrait_subname'] ? $user_info['portrait_subname'] : getSn();
            $upload->thumb = true; //设置缩略图
            $upload->imageClassPath = "@.Think.Image";
            $upload->thumbPrefix = "100_,50_"; //生成多张缩略图
            $upload->thumbSuffix = "100,50"; //生成多张缩略图
            $upload->thumbMaxWidth = "100,50";
            $upload->thumbMaxHeight = "100,50";
            $upload->saveRule = uniqid; //上传规则
            $upload->thumbRemoveOrigin = true; //删除原图
            $up_res = $upload->upload();
            if($up_res === false){
                $this->error($upload->getError());//获取失败信息
            } 

            $update_data = array(
                'user_id' => $_POST['user_id'],
                'portrait_subname' => $upload->subName,
                'portrait_ext' => '.'.$up_res['photo']['ext']
            );
           
            if($model->save($update_data) !== false) {
                //清缓存
                $cacheKey = \Home\Model\UserModel::getKey($_POST['user_id']);
                $cache = \Think\Cache::getInstance();
                $cache->rm($cacheKey);

                $this->redirect('portrait');
            }


        }
        
        $this->error("操作异常", U("portrait"));
    }


    /**
     * 基本信息
     */
    public function basic() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("User");
        if(IS_POST) {
           if($_SESSION['es_user']['user_id'] != $_POST['user_id']) {
               $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
           }
            
            $update_data = array(
                'user_id' => $_POST['user_id'],
                'real_name' => $_POST['real_name'],
                'gender' => $_POST['gender']
            );
            
            $res = $model->save($update_data);
            if($res !== false) {
                //清缓存
                $cacheKey = \Home\Model\UserModel::getKey($_POST['user_id']);
                $cache = \Think\Cache::getInstance();
                $cache->rm($cacheKey);
                
                $this->ajaxReturn(array('status' => 1, 'info' => '保存成功'));
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $user_info = D("User")->getInfo($_SESSION['es_user']['user_id']);
        $this->assign('user_info', $user_info);
        
        $this->display();
    }
    
    /**
     * 密码修改
     */
    public function password() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("User");
        $user_info = D("User")->getInfo($_SESSION['es_user']['user_id']);
        
        if(IS_POST) {
           if($_SESSION['es_user']['user_id'] != $_POST['user_id']) {
               $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
           }
           
           if($user_info['password'] != md5($_POST['password'])) {
               $this->ajaxReturn(array('status' => 0, 'info' => '您输入的原密码不正确！'));
           }
           
           if($_POST['newpassword'] != $_POST['repassword']) {
               $this->ajaxReturn(array('status' => 0, 'info' => '您输入的新密码与确认密码不一致！'));
           }
            
            $update_data = array(
                'user_id' => $_POST['user_id']
            );
            
            if($_POST['newpassword']) {
                $update_data['password'] = md5($_POST['newpassword']);
            }
            
            $res = $model->save($update_data);
            if($res !== false) {
                $this->ajaxReturn(array('status' => 1, 'info' => '保存成功'));
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        
        $this->assign('user_info', $user_info);
        
        $this->display();
    }
    
    /**
     * 头像
     */
    public function portrait() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("User");
        
        $user_info = $model->getInfo($_SESSION['es_user']['user_id']);
        $this->assign('user_info', $user_info);
        
        $this->display();
    }
    
    /**
     * 安全设置
     */
    public function secure() {
        //判断是否登录
        $this->checkLogin();
        
        $user_info = D("User")->getInfo($_SESSION['es_user']['user_id']);
        
        $this->assign('user_info', $user_info);
        $this->display();
    }

    /**
     * 我的相册
     */
    public function album() {
        //判断是否登录
        $this->checkLogin();

        $model = D('album');

        //用户信息
        $user_info = D("User")->getInfo($_SESSION['es_user']['user_id']);
        $this->assign('user_info', $user_info);

        $where = array('user_id' => $_SESSION['es_user']['user_id']);
        //获取相册select
        $album_select = $model->getList(0,true,$where);
        $this->assign('album_select',$album_select);

        //获取相册列表
        $album_list = $model->getList(0,0,false,$where);
        if($album_list) {
            foreach($album_list as $akey => &$album) {
                $condition = array(
                    'user_id' => $_SESSION['es_user']['user_id'],
                    'album_id' => $album['id'],
                );
                $piccount = D('picture')->getCount($condition);
                $album['picture_count'] = intval($piccount);
                if($album['picture_count'] > 0) {
                    $first_pic = D('picture')->getFirstPic($condition);
                    $album['title_picture'] = $first_pic['pic_url'];
                }
            }
        }
        $this->assign('album_list',$album_list);

        $this->display();
    }

}
?>