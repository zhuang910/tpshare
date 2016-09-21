<?php
/**
 * 节点控制器
 * 
 * @author zhuangqianlin 2015-09-06
 */

namespace BuiAdmin\Controller;

class NodeController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Node');
        $where = array();
        if($_GET['node_name']) {
            $where['node_name'] = array("LIKE",'%'.$_GET['node_name'].'%');
        }
        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $node_list = $model->getList($where);
        $this->assign('node_list',$node_list);
        $this->assign('node_name',$_GET['node_name']);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        $m_model = D('Module');
        $n_model = D('Node');
       
        //获取菜单选项
        $module_options = $m_model->getFatherModuleForOptions();
        $this->assign('module_options',$module_options);
        
        //获取节点选项
        $node_options = $n_model->getFatherNodeForOptions();
        $this->assign('node_options',$node_options);
        
        $this->display();
    }
    
    /**
     * 执行入库操作
     */
    public function insert() {
        $model = D("Node");
        if(IS_POST) {
            $_POST['node_url'] = strtolower($_POST['node_url']);
            if($model->create()) {
                $res = $model->add();
                if($res) {
                    //清除menu_list的快速缓存
                    F('menu_list',NULL);
                    $this->ajaxReturn(array('status' => 1, 'tourl'=>__MODULE__.U("index"),'info' => '添加成功'));
                }
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 修改
     */
    public function update() {
        $m_model = D('Module');
        $n_model = D('Node');
        $node_id = I("get.id",0);
        if(!$node_id) {
            $this->ajaxReturn(array('status' => 0, 'tourl'=>__MODULE__.U("index"),'info' => '非法操作'));
        }
        
        //获取菜单选项
        $module_options = $m_model->getFatherModuleForOptions();
        $this->assign('module_options',$module_options);
        
        //获取节点选项
        $node_options = $n_model->getFatherNodeForOptions();
        $this->assign('node_options',$node_options);
           
        $node_info = $n_model->getInfo($node_id);
        $this->assign("node_info",$node_info);
        
        $this->display();
    }
    
    /**
     * 执行编辑操作
     */
    public function edit() {
        $model = D("Node");
        if(IS_POST) {
            $_POST['node_url'] = strtolower($_POST['node_url']);
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
     * 删除某个记录
     */
    public function delete() {
        $model = M('Node');
        $node_id = I("post.id",0);
        if(!$node_id) {
            $this->ajaxReturn(array('status' => 0, 'tourl'=>__MODULE__.U("index"),'info' => '非法操作'));
        }
        
        $res = $model->where("node_id={$node_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'tourl'=>__MODULE__.U("index"),'info' => '删除成功'));
        }else{
            $this->ajaxReturn(array('status' => 0, 'tourl'=>__MODULE__.U("index"),'info' => '删除失败，请重试！'));
        }
    }
}