<?php
/**
 * 专题模型
 * 
 * @author zhuangqianlin 2015-12-18
 */
namespace Home\Model;
use Think\Model;

class TopicModel extends Model{

    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Topic');
        return $model->where("id={$id}")->find();
    }

    /**
     * 分页获取列表
     *
     */
    public function getList($where = ''){
        $model = M('Topic');
        return $model->where($where)->page(I('get.p',1),C('PAGE_SIZE'))->select();
    }

    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        $model = M('Topic');
        return $model->where($condition)->count();
    }

    /**
     * 获取子专题列表
     */
    public function getChildList($topic_id) {
        $model = M('Topic');
        $where['pid'] = $topic_id;
        return $model->where($where)->select();
    }
}
