<?php
namespace BuiAdmin\Model;
use Think\Model;
class UserModel extends Model{
    protected $_validate = array(
        array('user_name','require','用户名必须填写！'),
        array('password','require','密码必须填写！',0,'',self::MODEL_INSERT),
        array('real_name','require','真实姓名必须填写！'),
        
    );

    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id,$user_name='') {
        $model = M('User');
        return $model->where("user_id={$id} or user_name='{$user_name}'")->find();
    }

    /**
     * 分页获取列表
     * 
     */
    public function getList($where){
        $model = M('User');
        return $model->where($where)
                ->order('user_id desc')
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('User')->where($condition)->count();
    }

}
