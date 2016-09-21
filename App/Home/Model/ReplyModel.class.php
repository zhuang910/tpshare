<?php
/**
 * 评论模型
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Model;
use Think\Model;

class ReplyModel extends Model{
    protected $_validate = array(
        array('content','require','内容必须填写！'),
    );
    
    
    
    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Reply');
        return $model->where("reply_id={$id}")->find();
    }


    /**
     * 分页获取列表
     * 
     */
    public function getList($where){
        $model = M('Reply');
        $list = array();
        $list = $model->join("as r left join es_user as u on r.user_id=u.user_id")
                ->field('r.*,u.real_name,u.portrait_subname,u.portrait_ext')
                ->order('reply_id desc')
                ->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
        if($list) {
            foreach($list as $k=>$v) {
                $list[$k]['content'] = htmlspecialchars_decode($v['content']);
                $list[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            }
        }
        
        return $list;
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Reply')->where($condition)->count();
    }
    
}
