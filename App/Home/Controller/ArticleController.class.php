<?php
/**
 * 文章控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

class ArticleController extends CommonController {
    public function index(){

        $this->redirect('Index/index');
    }
    
    /**
     * 发布文章
     */
    public function add() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Article');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            $_POST['add_time'] = time();

            $cache  = \Think\Cache::getInstance();
            $lock_key = 'user_add_article_lock_'.$_POST['user_id']; 
            $lock = $cache->get($lock_key);
            //加缓存锁
            if (empty($lock)) {
                $cache->set($lock_key,'user_add_article_lock',30);
            } else {
                $this->error('您操作的频率太快噢！',U("index/index"));
            }
            
            //$_POST['content'] = htmlspecialchars(stripslashes($_POST['content']));
            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index/index"),'info' => '发布成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        //获取分类
        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $cat_list = D("Category")->getList(0,0,true,0,$c_condition);
        $this->assign('cat_select',$cat_list);
        
        $this->display();
    }
    
    /**
     * 修改文章
     */
    public function update() {
        //判断是否登录
        $this->checkLogin();
        
        if(!$_SESSION['es_user']['user_id']) {
            $this->redirect("Public/login");
        }
        $model = D('Article');
        if(IS_POST) {
            if($_POST['user_id'] != $_SESSION['es_user']['user_id']) {
                $this->error('只能修改自己的文章！');
            }

            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index/index"),'info' => '保存成功'));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $article_id = I("get.id",0);
        if(!$article_id) {
            $this->ajaxReturn(array('status' => 0, 'url'=>U("index"),'info' => '非法操作'));
        }
        
        //获取文章信息
        $article_info = $model->getInfo($article_id);
        $article_info['content'] = htmlspecialchars_decode($article_info['content']);
        $this->assign("article_info",$article_info);
        
        //获取分类
        $c_condition = array('c.user_id' => $_SESSION['es_user']['user_id']);
        $cat_list = D("Category")->getList(0,$article_info['cat_id'],true,0,$c_condition);
        $this->assign('cat_select',$cat_list);

        $this->display();
    }
    
    /**
     * view
     */
    public function view() {
        $model = D('Article');
        $article_id = I("get.id",0);
        if(!$article_id) {
            $this->error('非法操作');
        }
        
        //更新浏览次数
        $model->where("article_id={$article_id}")->setInc('click_count');
        
        //获取评论列表
        $reply_list = D("Reply")->getList("article_id={$article_id}");
        $reply_count = count($reply_list);
        $this->assign("reply_list",$reply_list);
        
        $article_info = $model->getInfo($article_id);
        $article_info['real_name'] = M("user")->where("user_id={$article_info[user_id]}")->getfield('real_name');
        $article_info['cat_name'] = M("category")->where("cat_id={$article_info[cat_id]}")->getfield('category_name');
        
        $article_info['content'] = html_entity_decode($article_info['content']);
        $article_info['reply_count'] = $reply_count;
        $this->assign("article_info",$article_info);
        
        $this->display();
    }

    /**
     * 专题文章列表
     */
    public function tarticle() {
        $t_model = D('Topic');
        $a_model = D('Article');
        $topic_id = I("get.tid",0);
        $child_topic_id = I("get.ctid",0);
        if(empty($topic_id)) {
            $this->redirect('Index/index');
        }

        $topic_info = $t_model->getInfo($topic_id);
        //不是顶级专题 报错
        if(!($topic_info && $topic_info['pid'] == 0)) {
            $this->error('您访问的专题异常！');
        }

        //子专题列表
        $child_topic_list = $t_model->getChildList($topic_id);
        if(empty($child_topic_list)) {
            $this->error('此专题暂无文章');
        }
        $this->assign("child_topic_list",$child_topic_list);

        //获取专题文章
        $child_topic_id = $child_topic_id ? $child_topic_id : $child_topic_list[0]['id'];
        $where = "a.type=2 and a.cat_id = {$child_topic_id}";

        $where = array(
            'a.type' => 2,
            'a.cat_id' => $child_topic_id,
        );
        //获取列表
        $article_list = $a_model->getTArticleList($where);
        foreach($article_list as $akey=>$value) {
            $article_list[$akey]['cat_name'] = M("category")->where("cat_id={$value['cat_id']}")->getfield('category_name');
            $article_list[$akey]['content'] = strip_tags(html_entity_decode($value['content']));
        }
        $this->assign('article_list',$article_list);
        $this->assign('top_topic_id',$topic_id);
        $this->assign('child_topic_id',$child_topic_id);
        $this->assign('active_topic_id',$child_topic_id);

        $this->display();
    }

    /**
     * 无限加载专题文章
     */
    public function getMoreTArticle() {
        //判断是否登录
        $this->checkLogin();

        $a_model = D('Article');
        $child_topic_id = I("post.ctid",0);
        $max_id = I("post.max_id",0);

        $where = array(
            'a.type' => 2,
            'a.cat_id' => $child_topic_id,
            'a.article_id' => array('lt',$max_id),
        );

        //获取列表
        $article_list = $a_model->getTArticleList($where,5);
        foreach($article_list as $akey=>$value) {
            $article_list[$akey]['cat_name'] = M("category")->where("cat_id={$value['cat_id']}")->getfield('category_name');
            $article_list[$akey]['content'] = strip_tags(html_entity_decode($value['content']));
        }
        $this->assign('article_list',$article_list);
        $data = $this->fetch('tarticleItem');

        $this->ajaxReturn(array('status' => 1, 'info' => '加载成功','data'=>$data));
    }
}