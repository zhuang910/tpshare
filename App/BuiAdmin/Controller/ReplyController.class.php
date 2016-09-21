<?php
/**
 * 评论控制器
 * 
 * @author zhuangqianlin 2015-09-22
 */

namespace BuiAdmin\Controller;

class ReplyController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Reply');
        $where = array();
        if($_GET['art_title']) {
            $where['a.title'] = array("LIKE",'%'.$_GET['art_title'].'%');
        }
        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $reply_list = $model->getList($where);
        foreach($reply_list as $rkey=>$value) {
            $reply_list[$rkey]['content'] = html_entity_decode($value['content']);
        }
        $this->assign('reply_list',$reply_list);
        $this->assign('art_title',$_GET['art_title']);
        
        $this->display();
    }

    /**
     * 删除某个记录
     */
    public function delete() {
        $model = M('Reply');
        $reply_id = I("post.id",0);
        if(!$reply_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }
        
        $res = $model->where("reply_id={$reply_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '删除成功'));
        }else{
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '删除失败，请重试！'));
        }
    }
}