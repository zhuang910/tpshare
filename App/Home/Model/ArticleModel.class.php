<?php
/**
 * 文章模型
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Model;
use Think\Model;

class ArticleModel extends Model{
    protected $_validate = array(
        array('title','require','标题必须填写！'),
        array('content','require','内容必须填写！'),
        array('cat_id','/^[1-9]\d*/','请选择分类！'),
    );

    CONST TYPE_NORMAL = 1; //普通文章
    CONST TYPE_TOPIC = 2; //专题文章
    
    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Article');
        return $model->where("article_id={$id}")->find();
    }

    /**
     * 分页获取列表
     * 
     */
    public function getList($where){
        $model = M('Article');
        return $model->join('as a left join es_user as u on a.user_id=u.user_id')
                ->join('es_category as c on a.cat_id=c.cat_id')
                ->field('a.*,u.user_id,u.real_name,u.portrait_subname,u.portrait_ext,c.category_name')
                ->order('a.article_id desc')
                ->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        $model = M('Article');
        return $model->join('as a left join es_user as u on a.user_id=u.user_id')
                ->join('es_category as c on a.cat_id=c.cat_id')
                ->where($condition)->count();
    }


    /**
     * 分页获取列表
     *
     */
    public function getTArticleList($where,$count =0){
        $count = $count? $count : C('PAGE_SIZE');
        $model = M('Article');
        return $model->join('as a left join es_user as u on a.user_id=u.user_id')
            ->join('es_category as c on a.cat_id=c.cat_id')
            ->field('a.*,u.user_id,u.real_name,u.portrait_subname,u.portrait_ext,c.category_name')
            ->order('a.article_id desc')
            ->where($where)
            ->limit($count)
            ->select();
    }
    
}
