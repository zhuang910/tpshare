<?php
/**
 * 用户模型
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Model;
use Think\Model;

class UserModel extends Model{
    protected $_validate = array(
        array('user_name','require','用户名必须填写！'),
        array('real_name','require','真实姓名必须填写！'),
    );

    const CACHE_PREFIX  = "es_userinfo_";
    const DEFAULT_AT_TIME = 259200;//72小时
    
    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        if(empty ($id)) {
            return false;
        }
        /**
         * 从缓存获取
         */
        $cacheKey       = self::getKey($id);
        $cache  = \Think\Cache::getInstance();
        $cacheResult    = $cache->get($cacheKey);
        if ($cacheResult) {
            return $cacheResult;
        }

        /**
         * 无缓存从数据库取
         */
        $model = M('User');
        $user_info = $model->where("user_id={$id}")->find();
        if (!$user_info) {
            return false;
        }

        /**
         * 写缓存
         */
        $cache->set($cacheKey, $user_info, self::DEFAULT_AT_TIME);

        return $user_info;

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

    /**
     * 缓存key
     */
    public static function getKey($userId) {
        return self::CACHE_PREFIX.$userId;
    }

}
