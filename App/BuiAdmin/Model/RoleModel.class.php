<?php
namespace BuiAdmin\Model;
use Think\Model;
class RoleModel extends Model{
    protected $_validate = array(
//        array('role_name','require','角色名称必须填写！'),
//        array('role_name','','角色名称已经存在！',0,'unique',3),
    );
    
    
    
    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Role');
        return $model->where("role_id={$id}")->find();
    }


    /**
     * 分页获取列表
     * 
     */
    public function getList($where){
        $model = M('Role');
        return $model->order('role_id asc')
                ->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Role')->where($condition)->count();
    }
    
    /**
     * 获取权限列表
     */
    public function getAccessList() {
        $access_list = array();
        $top_module_list = M("Module")->where("pid=0")->select();
        if($top_module_list) {
            foreach ($top_module_list as $top_module) {
                $access_list[$top_module['module_id']] = $top_module;
                $module_list = M("Module")->where("pid={$top_module[module_id]}")->select();
                if($module_list) {
                    foreach ($module_list as $module) {
                        $access_list[$top_module['module_id']]['module_list'][$module['module_id']] = $module;
                        $node_list = M("Node")->where("module_id={$module[module_id]}")->select();
                        $access_list[$top_module['module_id']]['module_list'][$module['module_id']]['node_list'] = $node_list;
                    }
                }
            }
        }
        
        return $access_list;
    }
    
    /**
     * 获取用户的访问控制列表，默认当前用户
     */
    public function getUserAccessList($user_role_id=0) {
        $role_id = $user_role_id ? $user_role_id : $_SESSION['esadmin_user']['role_id'];
        return M('Role')->where("role_id={$role_id}")->getField('access_list');
    }

}
