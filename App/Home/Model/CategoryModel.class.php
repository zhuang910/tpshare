<?php
/**
 * 分类模型
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Model;
use Think\Model;

class CategoryModel extends Model{
    CONST HOT_CAT_LIST_KEY = 'hotcatlistkey';

    protected $_validate = array(
        array('category_name','require','分类名称必须填写！'),
    );

    /**
     * 获取单个信息
     * 
     */
    public function getInfo($id) {
        $model = M('Category');
        return $model->where("cat_id={$id}")->find();
    }


    /**
     * 分页获取列表
     * 
     * @access  public
     * @param   mix     $where      查询条件
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    public function getList($cat_id=0,$selected=0,$re_type = true,$level=0,$where=''){
        $model = M('Category');
        $res = $model->field('c.*, COUNT(cc.cat_id) AS has_children')
                ->join('as c left join es_category as cc on cc.pid=c.cat_id')
                ->where($where)
                ->order('c.pid asc')
                ->group('c.cat_id')
                ->select();
        if (empty($res) == true){
            return $re_type ? '' : array();
        }
        
        // 获得指定分类下的子分类的数组
        $options = $this->cat_options($cat_id, $res); 
        /* 截取到指定的缩减级别 */
        if ($level > 0) {
            if ($cat_id == 0) {
                $end_level = $level;
            } else {
                $first_item = reset($options); // 获取第一个元素
                $end_level  = $first_item['level'] + $level;
            }

            /* 保留level小于end_level的部分 */
            foreach ($options AS $key => $val) {
                if ($val['level'] >= $end_level) {
                    unset($options[$key]);
                }
            }
        }

        $pre_key = 0;
        foreach ($options AS $key => $value) {
            $options[$key]['has_children'] = 1;
            if ($pre_key > 0) {
                if ($options[$pre_key]['cat_id'] == $options[$key]['pid']) {
                    $options[$pre_key]['has_children'] = 1;
                }
            }
            $pre_key = $key;
        }

        if ($re_type == true) {
            $select = '';
            foreach ($options AS $var) {
                $select .= '<option value="' . $var['cat_id'] . '" ';
               
                $select .= ($selected == $var['cat_id']) ? "selected='selected'" : '';
                $select .= '>';
                if ($var['level'] > 0) {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars(addslashes($var['category_name'])) . '</option>';
            }

            return $select;
        } else {
            foreach ($options AS $key => $value) {
                //$options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
            }
            return $options;
        }
    }
    
    /**
     * 获得指定分类下的子分类的数组
     * 
     * @access  private
     * @param   int     $spec_cat_id     上级分类ID
     * @param   array   $arr        含有所有分类的数组
     * @param   int     $level      级别
     * @return  void
     */
    private function cat_options($spec_cat_id, $arr){
        static $cat_options = array();

        if (isset($cat_options[$spec_cat_id])){
            return $cat_options[$spec_cat_id];
        }

        if (!isset($cat_options[0])){
            $level = $last_cat_id = 0;
            $options = $cat_id_array = $level_array = array();
            while (!empty($arr)) {
                foreach ($arr AS $key => $value) {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0) {
                        if ($value['pid'] > 0) { break; }

                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['category_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0) { continue; }
                        $last_cat_id  = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['pid'] == $last_cat_id) {
                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['category_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0) {
                            if (end($cat_id_array) != $last_cat_id) {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    } elseif ($value['pid'] > $last_cat_id) { break; }
                }

                $count = count($cat_id_array);
                if ($count > 1) {
                    $last_cat_id = array_pop($cat_id_array);
                } elseif ($count == 1) {
                    if ($last_cat_id != end($cat_id_array)) {
                        $last_cat_id = end($cat_id_array);
                    } else {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id])) {
                    $level = $level_array[$last_cat_id];
                } else {
                    $level = 0;
                }
            }
            $cat_options[0] = $options;
        } else {
            $options = $cat_options[0];
        }

        if (!$spec_cat_id) {
            return $options;
        } else {
            if (empty($options[$spec_cat_id])) {
                return array();
            }

            $spec_cat_id_level = $options[$spec_cat_id]['level'];

            foreach ($options AS $key => $value) {
                if ($key != $spec_cat_id) {
                    unset($options[$key]);
                } else { break; }
            }

            $spec_cat_id_array = array();
            foreach ($options AS $key => $value) {
                if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                    ($spec_cat_id_level > $value['level'])) {
                    break;
                } else {
                    $spec_cat_id_array[$key] = $value;
                }
            }
            $cat_options[$spec_cat_id] = $spec_cat_id_array;

            return $spec_cat_id_array;
        }
    }
    
    /**
     * 获取热搜分类
     */
    public function getHotCat($num=10) {
        $hot_cat = array();
        $model = M('Category');
        $cache  = \Think\Cache::getInstance();

        //获取缓存
        $cache_data = $cache->get(self::HOT_CAT_LIST_KEY);
        if($cache_data) {
            return $cache_data;
        }
        $res = $model->order('search_count desc')->group("category_name")->limit($num)->select();
        if($res){
            $hot_cat = $res;
            //写缓存
            $cache->set(self::HOT_CAT_LIST_KEY,$res,86400);//3600*24
        }

        return $hot_cat;
    }
}
