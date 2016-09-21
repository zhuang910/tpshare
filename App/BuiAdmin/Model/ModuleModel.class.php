<?php
namespace BuiAdmin\Model;
use Think\Model;
class ModuleModel extends Model{
    protected $_validate = array(
        array('module_name','require','菜单名称必须填写！'),
        //array('module_url','require','菜单链接必须填写！'),
    );
    public function getChildModule($pid = 0,$module_ids = '') {
        $where = $module_ids ? "pid = {$pid} and module_id in({$module_ids})" : "pid = {$pid}";
        $res = M('Module')->where($where)->select();
        return $res;
    }
    
    /**
     * 
     */
    public function getPidsByUserModuleIds($module_ids = '') {
        $module_pids = '';
        $res = M('Module')->field("distinct pid")->where("module_id in({$module_ids})")->select();

        if($res) {
            foreach ($res as $item) {
                $module_pids .= $item['pid'].',';
            }
        }
        
        return trim($module_pids,',');
    }

        /**
     * 获取单个菜单信息
     * 
     */
    public function getInfo($id) {
        $model = M('Module');
        return $model->where("module_id={$id}")->find();
    }


    /**
     * 分页获取模块列表
     * 
     */
    public function getList($where){
        $model = M('Module');
        return $model->order('module_id asc')
                ->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Module')->where($condition)->count();
    }
    
    /**
     * 获取顶级菜单及其子菜单列表
     */
    public function getFatherModuleForOptions () {
        $module_list = M("Module")->where("pid=0")->select();
        if($module_list) {
            foreach ($module_list as $mkey=>$module) {
                $child_module_list = M("Module")->where("pid=$module[module_id]")->select();
                $module_list[$mkey]['child_module_list'] = $child_module_list;
            }
        }
        
        return $module_list;
    }
}
