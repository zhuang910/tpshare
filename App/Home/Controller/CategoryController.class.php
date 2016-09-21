<?php
/**
 * 分类控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

class CategoryController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Category');
        $where = array();
        if($_GET['category_name']) {
            $where['category_name'] = array("LIKE",'%'.$_GET['category_name'].'%');
        }
        
        //分页获取列表
        $list = $model->getList(0,0,false);
        $this->assign('category_list',$list);
        $this->assign('category_name',$_GET['category_name']);
        
        $this->display();
    }
    
    /**
     * 获取全部分类 ajax
     */
    public function getCatOptions() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Category');
        
        //分页获取列表
        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $list = $model->getList(0,0,true,0,$c_condition);
        if($list) {
            $data = array(
                            'pcat_select' => '<option value=""></option>'.$list,
                        );
            $this->ajaxReturn(array('status' => 1, 'data' => $data));
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' =>'系统异常'));
    }

        /**
     * 增加
     */
    public function add() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Category');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("User/category"),'info' => '添加成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }

        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $list = $model->getList(0,0,true,0,$c_condition);
        $this->assign('pcat_select',$list);
        $this->display('User/category_add');
    }
    
    /**
     * 发布或修改文章时添加分类
     */
    public function addForArticle() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Category');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
                    $list = $model->getList(0,$res,true,0,$c_condition);
                    $data = array(
                        'pcat_select' => '<option value=""></option>'.$list,
                    );
                    $this->ajaxReturn(array('status' => 1, 'info' => '添加成功','data'=> $data));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
    }
    
    /**
     * 修改
     */
    public function update() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("Category");
        if(IS_POST) {
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("User/category"),'info' => '修改成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $cat_id = I("get.id",0);
        if(!$cat_id) {
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
        }
         
        $category_info = $model->getInfo($cat_id);
        $this->assign("category_info",$category_info);

        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $list = $model->getList(0,$category_info['pid'],true,0,$c_condition);
        $this->assign('pcat_select',$list);

        $this->display('User/category_update');
    }
    
    /**
     * 删除
     */
    public function delete() {
        //判断是否登录
        $this->checkLogin();
        
        $model = M('Category');
        $cat_id = I("post.id",0);
        if(!$cat_id) {
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }
        
        //判断是否有子类
        $has_childcat = $model->where("pid={$cat_id}")->find();
        if($has_childcat) {
            $this->ajaxReturn(array('status' => 0, 'info' => '此分类还有子类，不能删除！'));
        }
        
        $res = $model->where("cat_id={$cat_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'url'=>U("User/category"),'info' => '删除成功'));
        }
    }
    
}