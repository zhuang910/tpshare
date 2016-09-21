<?php
/**
 * 首页控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

class IndexController extends CommonController {
    public function index(){
        $a_model = D("Article");
        $c_model = D("category");
        
        $where = "a.is_public=1 and a.type=1 ";
        $where .= $_SESSION['es_user']['user_id'] > 0 ? " or a.user_id=".$_SESSION['es_user']['user_id'] : '';
        
        //分页
        $count = $a_model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->sample_show());
        
        //分页获取列表
        $article_list = $a_model->getList($where);
        foreach($article_list as $akey=>$value) {
            $article_list[$akey]['cat_name'] = M("category")->where("cat_id={$value[cat_id]}")->getfield('category_name');
            $article_list[$akey]['content'] = strip_tags(html_entity_decode($value['content']));
        }

        $this->assign('article_list',$article_list);
        
        $hot_cat = $c_model->getHotCat(10);
        $this->assign('hot_cat',$hot_cat);
        
        $this->display();
    }

	
}