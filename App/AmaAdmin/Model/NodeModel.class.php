<?php
namespace AmaAdmin\Model;
use Think\Model;
class NodeModel extends Model{
    protected $_validate = array(
        array('node_name','require','节点名称必须填写！'),
        array('node_url','require','节点链接必须填写！'),
        array('module_id','/^[1-9]\d*/','请选项父菜单！'),
    );
    
    public function getNodesByModuleIds($module_ids_str = 0) {
        $map['module_id'] = array('IN',$module_ids_str);
        $map['is_show'] = 1;
        return $this->where($map)->select();
    }
    
    public function getChildNode($pid = 0) {
        return $this->where("pid = {$pid}")->select();
    }
    
    /**
     * 根据节点id获取菜单id
     */
    public function getModuleIdsByIds($node_ids = '') {
        $module_ids = '';
        $res = M('Node')->field("distinct module_id")->where("node_id in({$node_ids})")->select();
        if($res) {
            foreach ($res as $item) {
                $module_ids .= $item['module_id'].',';
            }
        }
        
        return trim($module_ids,',');
    }

        /**
     * 获取单个菜单信息
     * 
     */
    public function getInfo($id) {
        $model = M('Node');
        return $model->where("node_id={$id}")->find();
    }


    /**
     * 分页获取模块列表
     * 
     */
    public function getList(){
        $model = M('Node');
        return $model->order('node_id asc')
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Node')->where($condition)->count();
    }
    
    
    /**
     * 获取二级菜单及菜单下的节点
     */
    public function getFatherNodeForOptions () {
        $module_list = M("Module")->where("pid !=0")->select();
        if($module_list) {
            foreach ($module_list as $mkey=>$module) {
                $node_list = M("Node")->where("module_id={$module['module_id']}")->select();
                $module_list[$mkey]['nodes'] = $node_list;
            }
        }
        
        return $module_list;
    }
}
