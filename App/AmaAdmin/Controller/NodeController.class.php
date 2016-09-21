<?php
/**
 * 节点控制器
 * 
 * @author zhuangqianlin 2015-09-06
 */

namespace AmaAdmin\Controller;

class NodeController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Node');
        
        //分页
        $count = $model->getCount();
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $node_list = $model->getList();
        $this->assign('node_list',$node_list);
        
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
                    $this->success('添加成功', U("index"));
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
            $this->error('非法操作', U("index"));
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
                    $this->success('修改成功', U("index"));
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
            $this->error('非法操作', U("index"));
        }
        
        $res = $model->where("node_id={$node_id}")->delete();
        if($res) {
            $this->success('删除成功', U("index"));
        }else{
            $this->error('删除失败，请重试！', U("index"));
        }
    }
}