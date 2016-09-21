<?php
namespace BuiAdmin\Model;
use Think\Model;
class ArticleModel extends Model{
    protected $_validate = array(
        array('title','require','标题必须填写！'),
        array('content','require','内容必须填写！'),
        array('cat_id','/^[1-9]\d*/','请选择分类！'),
    );

    CONST TYPE_NORMAL = 1; //普通文章
    CONST TYPE_TOPIC = 2; //专题文章

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        array('add_time','time',Model::MODEL_INSERT,'function')
    );

    /**
     * 获取单个菜单信息
     * 
     */
    public function getInfo($id) {
        $model = M('Article');
        return $model->where("article_id={$id}")->find();
    }


    /**
     * 分页获取模块列表
     * 
     */
    public function getList($where){
        $model = M('Article');
        return $model->where($where)
                ->order('article_id desc')
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Article')->where($condition)->count();
    }

    /**
     * 获取文章类型
     */
    public function getType() {
        return array(
            self::TYPE_NORMAL => '普通文章',
            self::TYPE_TOPIC => '专题文章',
        );
    }
}
