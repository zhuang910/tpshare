<?php
/**
 * 角色控制器
 * 
 * @author zhuangqianlin 2015-09-06
 */

namespace BuiAdmin\Controller;

class RoleController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Role');
        $where = array();
        if($_GET['role_name']) {
            $where['role_name'] = array("LIKE",'%'.$_GET['role_name'].'%');
        }
        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $role_list = $model->getList($where);
        $this->assign('role_list',$role_list);
        $this->assign('role_name',$_GET['role_name']);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        
        $this->display();
    }
    
    /**
     * 执行入库操作
     */
    public function insert() {
        $model = D("Role");
        if(IS_POST) {
            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '添加成功'));
                }
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 修改
     */
    public function update() {
        $model = D('Role');
        
        $role_id = I("get.id",0);
        if(!$role_id) {
            $this->ajaxReturn(array('status' => 0, 'tourl'=>U("index"),'info' => '非法操作'));
        }
        
        $role_info = $model->getInfo($role_id);
        $this->assign("role_info",$role_info);
        
        $this->display();
    }
    
    /**
     * 执行编辑操作
     */
    public function edit() {
        $model = D("Role");
        if(IS_POST) {
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'tourl'=>U("index"),'info' => '修改成功'));
                }
            }
        }
        //p($model->getLastSql());
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
    
    /**
     * 删除某个记录
     */
    public function delete() {
        $model = M('Role');
        $role_id = I("post.id",0);
        if(!$role_id) {
            $this->ajaxReturn(array('status' => 0, 'tourl'=>U("index"),'info' => '非法操作'));
        }
        
        $res = $model->where("role_id={$role_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'tourl'=>U("index"),'info' => '删除成功'));
        }else{
            $this->ajaxReturn(array('status' => 0, 'tourl'=>U("index"),'info' => '删除失败，请重试！'));
        }
    }
    
    /**
     * 权限分配
     */
    public function accessList() {
        $model = D('Role');
        
        $role_id = I("get.id",0);
        if(!$role_id) {
            $this->ajaxReturn(array('status' => 0, 'tourl'=>U("index"),'info' => '非法操作'));
        }
        
        //获取角色信息
        $role_info = $model->getInfo($role_id);
        $access_module = array();
        if($role_info['access_list']) {
            $module_ids = M("Node")->field("distinct module_id")->where("node_id in($role_info[access_list])")->select();

            if($module_ids) {
                foreach ($module_ids as $module_id) {
                    $access_module[]['val'] = 'node_2_'.$module_id['module_id'];
                }
            }
        }
        $role_info['access_module'] = count($access_module) > 0 ? json_encode($access_module) : json_encode(array());
        $this->assign("role_info",$role_info);
        
        //获取权限列表
        $access_list = $model->getAccessList();
        $this->assign("access_list",$access_list);
        
        $this->display();
    }
    
    /**
     * 保存权限分配
     */
    public function changeAccess() {
        $model = D("Role");
        if(IS_POST) {
            $access_list = I("post.access",array());
            $role_id = I("post.role_id",0);
            $update_data = array(
                'role_id' => $role_id,
                'access_list' => implode(',', $access_list),
            );

            $res = $model->save($update_data);
            if($res !== false) {
                F("menu_list_{$role_id}",array());
                $this->ajaxReturn(array('status' => 1, 'tourl'=>U("index"),'info' => '保存成功'));
            }
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
    }
}