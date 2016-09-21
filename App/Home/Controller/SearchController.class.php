<?php
/**
 * 搜索控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

class SearchController extends CommonController {
    public function index(){
        $a_model = D("Article");
        $c_model = D("category");
        
        $kwd = I('get.kwd',$_GET['kwd'],'');
        $cat_name = I('get.cat_name',$_GET['cat_name'],'');
        $where = $_SESSION['es_user']['user_id'] > 0 ? " (a.is_public=1 and a.type=2 or a.user_id=".$_SESSION['es_user']['user_id'] .")" : 'a.is_public=1 and a.type=1 ';
        $search_condition = ' 1 ';
        if($kwd || $cat_name) {
            $search_condition .= ' AND (';
            if($cat_name && $kwd) {
                $search_condition .= " c.category_name LIKE '%".$cat_name."%' OR a.title LIKE '%".$kwd."%' ";
            }
            if($kwd) {
                $search_condition .= " a.title LIKE '%".$kwd."%' ";
            }
            if($cat_name) {
                $search_condition .= " c.category_name LIKE '%".$cat_name."%' ";
            }
            
            $search_condition .= " )";
        }else {
            $this->redirect('Index/index');
        }
        
        $where .= " AND ".$search_condition;
        //分页
        $count = $a_model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->sample_show());
        
        //分页获取列表
        $article_list = $a_model->getList($where);
        foreach($article_list as $akey=>$value) {
            $article_list[$akey]['cat_name'] = M("category")->where("cat_id={$value[cat_id]}")->getfield('category_name');
            $article_list[$akey]['content'] = strip_tags(html_entity_decode($value['content']));
            
            //更新分类搜索次数
            M("category")->where("cat_id={$value[cat_id]}")->setInc('search_count');
        }
        $hot_cat = $c_model->getHotCat(10);
        $this->assign('hot_cat',$hot_cat);
        
        $this->assign('article_list',$article_list);
        $this->assign('kwd',$kwd);
        
        $this->display();
    }

}