<?php
/**
 * 文章控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */

namespace BuiAdmin\Controller;

use BuiAdmin\Model\ArticleModel;

class ArticleController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Article');
        $where = array();
        if($_GET['title']) {
            $where['title'] = array("LIKE",'%'.$_GET['title'].'%');
        }
        if($_GET['type']) {
            $where['type'] = $_GET['type'];
        }

        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());
        
        //分页获取列表
        $article_list = $model->getList($where);
        $this->assign('article_list',$article_list);

        //文章类型
        $article_type = $model->getType();
        $this->assign('article_type',$article_type);

        //搜索信息
        $this->assign('title',$_GET['title']);
        $this->assign('type',$_GET['type']);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        $model = D('Article');
        if(IS_POST) {
            
            $cache  = \Think\Cache::getInstance();
            $lock_key = 'admin_add_article_lock_'.$_SESSION['esadmin_user']['admin_id']; 
            $lock = $cache->get($lock_key);
            //加缓存锁
            if (empty($lock)) {
                $cache->set($lock_key,'admin_add_article_lock',30);
            } else {
                $this->error('您操作的频率太快噢！',U("index"));
            }

            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>__MODULE__.U("index"),'info' => '添加成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        //获取分类
        $cat_list = D("Category")->getList(0,0,true);
        $this->assign('cat_select',$cat_list);

        $this->display();
    }

    /**
     * 增加专题文章
     */
    public function addTopicArticle() {
        $model = D('Article');
        $topic_model = D('Topic');
        if(IS_POST) {
            $_POST['type'] = ArticleModel::TYPE_TOPIC;

            $cache  = \Think\Cache::getInstance();
            $lock_key = 'admin_add_article_lock_'.$_SESSION['esadmin_user']['admin_id']; 
            $lock = $cache->get($lock_key);
            //加缓存锁
            if (empty($lock)) {
                $cache->set($lock_key,'admin_add_article_lock',30);
            } else {
                $this->error('您操作的频率太快噢！',U("index"));
            }

            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '添加成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }

        //获取专题选项
        $topic_options = $topic_model->getFatherTopicForOptions();
        $this->assign('topic_options',$topic_options);

        $this->display();
    }
    
    /**
     * 修改
     */
    public function update() {
        $model = D('Article');
        if(IS_POST) {
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '修改成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $article_id = I("get.id",0);
        if(!$article_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }
        
        $article_info = $model->getInfo($article_id);
        $article_info['content'] = htmlspecialchars_decode($article_info['content']);
        $this->assign("article_info",$article_info);
        
        //获取分类
        $cat_list = D("Category")->getList(0,$article_info['cat_id'],true);
        $this->assign('cat_select',$cat_list);
        
        $this->display();
    }

    /**
     * 修改专题文章
     */
    public function updateTopicArticle() {
        $model = D('Article');
        $topic_model = D('Topic');
        if(IS_POST) {
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '修改成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }

        $article_id = I("get.id",0);
        if(!$article_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }

        $article_info = $model->getInfo($article_id);
        $article_info['content'] = htmlspecialchars_decode($article_info['content']);
        $this->assign("article_info",$article_info);

        //获取专题选项
        $topic_options = $topic_model->getFatherTopicForOptions();
        $this->assign('topic_options',$topic_options);

        $this->display();
    }
    
    /**
     * 删除某个记录
     */
    public function delete() {
        $model = M('Article');
        $article_id = I("post.id",0);
        if(!$article_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }
        
        $res = $model->where("article_id={$article_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '删除成功'));
        }else{
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '删除失败，请重试！'));
        }
    }

    /**
     * 导出pdf
     */
    public function exportPdf() {
        $article_id = I("get.id",0);
        $article_model = D('Article');
        $user_model = D('User');
        $pdf_model = D('ArticlePdf');
        $article_info = $article_model->getInfo($article_id);
        if(!$article_info) {
            $this->error('文章不存在，不能导出');
        }

        $user_info = $user_model->getInfo($article_info['user_id']);
        $article_info['content'] = html_entity_decode ($article_info['content']);
        $pdf_info['article_info'] = $article_info;
        $pdf_info['user_info'] = $user_info;
        set_time_limit(60);
        $pdf_model->createPdf($pdf_info);

        $pdf_model->Output('article_' . $article_info['article_id'] . '.pdf', 'D');
        exit;
    }

}