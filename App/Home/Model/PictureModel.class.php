<?php
/**
 * 图片模型
 * 
 * @author zhuangqianlin 2015-12-04
 */
namespace Home\Model;
use Think\Model;

class PictureModel extends Model{
    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        array('add_time','time',Model::MODEL_INSERT,'function')
    );
    
    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Article');
        return $model->where("article_id={$id}")->find();
    }


    /**
     * 获取相册的第一张图片
     * 
     */
    public function getFirstPic(){
        $model = M('picture');
        return $model->where($where)->order('add_time')->find();
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        $model = M('picture');
        return $model->where($condition)->count();
    }

    /**
     * 获取图片列表
     */
    public function getList($condition = '',$offset=0,$limit=20) {
        $model = M('picture');
        return $model->where($condition)->order('id desc')->limit($offset,$limit)->select();
    }

    /**
     * 获取所有图片
     */
    public function getAllList($condition = '') {
        $model = M('picture');
        return $model->where($condition)->order('id desc')->select();
    }
    
}
