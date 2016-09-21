<?php
/**
 * 专题控制器
 * 
 * @author zhuangqianlin 2015-12-18
 */
namespace Home\Controller;

class TopicController extends CommonController {

    /**
     * 专题中心
     */
    public function index() {
        $model = D("Topic");

        //条件
        $where = 'pid = 0';

        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->sample_show());

        //分页获取列表
        $topic_list = $model->getList($where);
        $this->assign('topic_list',$topic_list);

        $this->display();
    }
    
}