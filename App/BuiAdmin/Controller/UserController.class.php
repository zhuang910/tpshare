<?php
/**
 * 前台用户控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */

namespace BuiAdmin\Controller;

class UserController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('User');
        $where = array();
        if($_GET['user_name']) {
            $where['user_name'] = array("LIKE",'%'.$_GET['user_name'].'%');
        }
        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $user_list = $model->getList($where);
        $this->assign('user_list',$user_list);
        $this->assign('user_name',$_GET['user_name']);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        $model = D("User");
        if(IS_POST) {
            $user_info = $model->getInfo(0,$_POST['user_name']);
            if($user_info) {
                $this->error('此用户名已存在！');
            }
            $_POST['password'] = md5($_POST['password']);
            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '添加成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $this->display();
    }
    
    /**
     * 修改
     */
    public function update() {
        $model = D('User');
        if(IS_POST) {
           
            $update_data = array(
                'user_id' => $_POST['user_id'],
                'real_name' => $_POST['real_name'],
                'gender' => $_POST['gender']
            );
            if($_POST['password']) {
                $update_data['password'] = md5($_POST['password']);
            }

            $res = $model->save($update_data);
            if($res !== false) {
                $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '保存成功'));
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $user_id = I("get.id",0);
        if(!$user_id) {
            $this->error('非法操作', U("index"));
        }
           
        $user_info = $model->getInfo($user_id);
        $this->assign("user_info",$user_info);
        
        $this->display();
    }
    
    /**
     * 删除某个记录
     */
    public function delete() {
        $model = M('User');
        $user_id = I("post.id",0);
        if(!$user_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }
        
        $res = $model->where("user_id={$user_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '删除成功'));
            
        }else{
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '删除失败，请重试！'));
        }
    }
}