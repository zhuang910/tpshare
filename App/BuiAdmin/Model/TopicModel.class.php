<?php
namespace BuiAdmin\Model;
use Think\Model;
class TopicModel extends Model{
    protected $_validate = array(
        array('name','require','名称必须填写！'),
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        array('add_time','time',Model::MODEL_INSERT,'function')
    );

    /**
     * 获取单个信息
     */
    public function getInfo($id) {
        $model = M('Topic');
        return $model->where("id={$id}")->find();
    }

    /**
     * 分页获取列表
     */
    public function getList($where){
        $model = M('Topic');
        return $model->where($where)
                ->page(I('get.p',1),C('PAGE_SIZE'))
                ->select();
    }

    /**
     * 获取顶级专题 select option
     * @param   int     $selected   当前选中的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     */
    public function getTopList($selected = 0,$re_type = true) {
        $list = $this->getList('pid=0');
        if($re_type) {
            $select = '';
            foreach ($list AS $var) {
                $select .= '<option value="' . $var['id'] . '" ';
                $select .= ($selected == $var['id']) ? "selected='true'" : '';
                $select .= '>';
                $select .= htmlspecialchars(addslashes($var['name'])) . '</option>';
            }

            return $select;
        }

        return $list;
    }
    
    /**
     * 获取记录数
     */
    public function getCount($condition = '') {
        return M('Topic')->where($condition)->count();
    }

    /**
     * 获取二级专题及其子专题
     */
    public function getFatherTopicForOptions () {
        $top_topic_list = M("Topic")->where("pid=0")->select();
        if($top_topic_list) {
            foreach ($top_topic_list as $tkey=>$top_topic) {
                $topic_list = M("Topic")->where("pid={$top_topic['id']}")->select();
                $top_topic_list[$tkey]['child'] = $topic_list;
            }
        }

        return $top_topic_list;
    }



}
