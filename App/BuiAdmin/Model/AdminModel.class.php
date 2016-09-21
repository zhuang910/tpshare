<?php
namespace BuiAdmin\Model;
use Think\Model;
class AdminModel extends Model{
    protected $_validate = array(
        array('role_id','/^[1-9]\d*/','请选择角色！'),
    );

    /**
     * 获取单个菜单信息
     * 
     */
    public function getInfo($id,$admin_name='') {
        $model = M('Admin');
        return $model->where("admin_id={$id} or admin_name='{$admin_name}'")->find();
    }


    /**
     * 分页获取模块列表
     * 
     */
    public function getList($where){
        $model = M('Admin');
        return $model->order('admin_id asc')
                ->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Admin')->where($condition)->count();
    }

}
