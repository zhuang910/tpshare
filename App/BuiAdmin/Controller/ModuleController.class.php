<?php
/**
 * 菜单控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */

namespace BuiAdmin\Controller;

class ModuleController extends CommonController {
    
    /**
     * 菜单列表
     */
    public function index(){
        $model = D('Module');
        $where = array();
        if($_GET['module_name']) {
            $where['module_name'] = array("LIKE",'%'.$_GET['module_name'].'%');
        }
        
        //分页
        $count = $model->getCount($where);

        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取菜单列表
        $list = $model->getList($where);
        $this->assign('module_list',$list);
        $this->assign('module_name',$_GET['module_name']);
        
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
                    //清除menu_list的快速缓存
                    F('menu_list',NULL);
                    $this->ajaxReturn(array('status' => 1, 'url'=>__MODULE__.U("index"),'info' => '添加成功'));
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
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
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
                    //清除menu_list的快速缓存
                    F('menu_list',NULL);
                    $this->ajaxReturn(array('status' => 1, 'tourl'=>__MODULE__.U("index"),'info' => '修改成功'));
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
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }
        
        $res = $model->where("module_id={$module_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'tourl'=>__MODULE__.U("index"),'info' => '删除成功'));
        }
    }
}