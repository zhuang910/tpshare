<?php
/**
 * 菜单控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */

namespace AmaAdmin\Controller;

class ModuleController extends CommonController {
    
    /**
     * 菜单列表
     */
    public function index(){
        $model = D('Module');
        
        //分页
        $count = $model->getCount();
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取菜单列表
        $list = $model->getList();
        $this->assign('module_list',$list);
        
        $this->display();
    }
    
    /**
     * 增加模块
     * 
     */
    public function add() {
        $model = M('Module');
        $list = $model->where("pid=0")->select();
        
        $this->assign('module_list',$list);
        $this->display();
    }
    
    /**
     * 执行菜单入库操作
     */
    public function insert() {
        $model = D("Module");
        if(IS_POST) {
            $_POST['module_url'] = strtolower($_POST['module_url']);
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $this->success('添加成功', U("index"));
                }
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 修改模块
     * 
     */
    public function update() {
        $model = D("Module");
        $module_id = I("get.id",0);
        if(!$module_id) {
            $this->error('非法操作', U("index"));
        }
        
        $plist = $model->where("pid=0")->select();
        
        $this->assign('pmodule_list',$plist);
           
        $module_info = $model->getInfo($module_id);
        $this->assign("module_info",$module_info);
        
        $this->display();
    }
    
    /**
     * 执行菜单编辑操作
     */
    public function edit() {
        $model = D("Module");
        if(IS_POST) {
            $_POST['module_url'] = strtolower($_POST['module_url']);
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->success('修改成功', U("index"));
                }
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 删除某个菜单
     */
    public function delete() {
        $model = M('Module');
        $module_id = I("post.id",0);
        if(!$module_id) {
            $this->error('非法操作', U("index"));
        }
        
        $res = $model->where("module_id={$module_id}")->delete();
        if($res) {
            $this->success('删除成功', U("index"));
        }
    }
}