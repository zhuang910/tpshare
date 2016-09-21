<?php
/**
 * 管理员控制器
 * 
 * @author zhuangqianlin 2015-09-06
 */

namespace AmaAdmin\Controller;

class AdminController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Admin');
        
        //分页
        $count = $model->getCount();
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $admin_list = $model->getList();
        $this->assign('admin_list',$admin_list);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        $r_model = D("Role");
        $role_list = $r_model->select();
        $this->assign('role_list', $role_list);
        
        $this->display();
    }
    
    /**
     * 执行入库操作
     */
    public function insert() {
        $model = D("Admin");
        if(IS_POST) {
            $admin_info = $model->getInfo(0,$_POST['admin_name']);
            if($admin_info) {
                $this->error('此用户名已存在！');
            }
            $_POST['password'] = md5($_POST['password']);
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
        $m_model = D('Admin');
        $r_model = D("Role");

        $admin_id = I("get.id",0);
        if(!$admin_id) {
            $this->error('非法操作', U("index"));
        }
        
        //获取角色列表
        $role_list = $r_model->select();
        $this->assign('role_list', $role_list);
           
        $admin_info = $m_model->getInfo($admin_id);
        $this->assign("admin_info",$admin_info);
        
        $this->display();
    }
    
    /**
     * 执行编辑操作
     */
    public function edit() {
        $model = D("Admin");
        if(IS_POST) {
            if(!$_POST['role_id']) {
                $this->error('请选择角色！');
            }
            $update_data = array(
                'admin_id' => $_POST['admin_id'],
                'nick_name' => $_POST['nick_name'],
                'role_id' => $_POST['role_id']
            );
            if($_POST['password']) {
                $update_data['password'] = md5($_POST['password']);
            }

            $res = $model->save($update_data);
            if($res !== false) {
                $this->success('保存成功', U("index"));
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 删除某个记录
     */
    public function delete() {
        $model = M('Admin');
        $admin_id = I("post.id",0);
        if(!$admin_id) {
            $this->error('非法操作', U("index"));
        }
        
        $res = $model->where("admin_id={$admin_id}")->delete();
        if($res) {
            $this->success('删除成功', U("index"));
        }else{
            $this->error('删除失败，请重试！', U("index"));
        }
    }
}