<?php
/**
 * 分类控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */

namespace BuiAdmin\Controller;

class CategoryController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
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
     * 增加
     */
    public function add() {
        $model = D('Category');
        if(IS_POST) {
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>__MODULE__.U("index"),'info' => '添加成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $list = $model->getList(0,0,true);
        $this->assign('pcat_select',$list);
        $this->display();
    }
    
    /**
     * 修改
     */
    public function update() {
        $model = D("Category");
        if(IS_POST) {
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '修改成功'));
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
        
        $list = $model->getList(0,$category_info['pid'],true);
        $this->assign('pcat_select',$list);
        
        $this->display();
    }
    
    /**
     * 删除
     */
    public function delete() {
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
            $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '删除成功'));
        }
    }
}