<?php
namespace BuiAdmin\Model;
use Think\Model;
class ReplyModel extends Model{

    /**
     * 分页获取列表
     */
    public function getList($where){
        $model = M('Reply');
        return $model->join('as r left join es_user as u on r.user_id=u.user_id')
            ->join('es_article as a on a.article_id=r.article_id')
            ->field('r.*,u.user_name,u.real_name,a.title')
            ->order('r.reply_id desc')
            ->where($where)
            ->page(I('get.p',1),C('PAGE_SIZE'))
            ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        $model = M('Reply');
        return $model->join('as r left join es_user as u on r.user_id=u.user_id')
            ->join('es_article as a on a.article_id=r.article_id')
            ->where($condition)
            ->count();
    }
}
