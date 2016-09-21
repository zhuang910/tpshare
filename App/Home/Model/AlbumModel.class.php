<?php
/**
 * 相册模型
 * 
 * @author zhuangqianlin 2015-12-04
 */
namespace Home\Model;
use Think\Model;

class AlbumModel extends Model{
    protected $_validate = array(
        array('user_id','require','未找到用户！'),
        array('name','require','相册名称必须填写！'),
    );

    //array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array (
        array('create_time','time',Model::MODEL_INSERT,'function')
    );

    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('album');
        return $model->where("id={$id}")->find();
    }


    /**
     * 分页获取列表
     * 
     * @access  public
     * @param   mix     $where      查询条件
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @return  mix
     */
    public function getList($selected=0,$re_type = true,$where = ''){
        $model = M('album');
        $res = $model->where($where)->order('create_time desc')->select();
        if (empty($res) == true){
            return $re_type ? '' : array();
        }

        if ($re_type == true) {
            $select = '';
            foreach ($res AS $key => $value) {
                $select .= '<option value="' . $value['id'] . '" ';
                $select .= ($selected == $value['id']) ? "selected='selected'" : '';
                $select .= '>';

                $select .= htmlspecialchars(addslashes($value['name'])) . '</option>';
            }

            return $select;
        }else{
            return $res;
        }
    }
}
