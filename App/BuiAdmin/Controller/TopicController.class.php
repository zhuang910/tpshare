<?php
/**
 * 专题控制器
 * 
 * @author zhuangqianlin 2015-12-17
 */

namespace BuiAdmin\Controller;

class TopicController extends CommonController {
    
    /**
     * 列表
     */
    public function index(){
        $model = D('Topic');
        $where = array();

        if($_GET['name']) {
            $where['name'] = array("LIKE",'%'.$_GET['name'].'%');
        }
        if($_GET['pid']) {
            $where['pid'] = $_GET['pid'];
        }

        //分页
        $count = $model->getCount($where);
        $page = new \Think\Page($count,C('PAGE_SIZE'));
        $this->assign('page', $page->show());

        //分页获取列表
        $topic_list = $model->getList($where);
        $this->assign('topic_list',$topic_list);

        //获取顶级专题
        $top_list = $model->getTopList($_GET['pid']);
        $this->assign('top_list',$top_list);

        $this->assign('name',$_GET['name']);
        
        $this->display();
    }
    
    /**
     * 增加
     */
    public function add() {
        $model = D('Topic');
        if(IS_POST) {
            if($_POST['pid'] == 0 && empty($_POST['face_pic'])) {
                $this->error('必须上传标示图');
            }
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '添加成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }

        //获取顶级专题
        $top_list = $model->getTopList($_GET['pid']);
        $this->assign('ptopic_select',$top_list);
        $this->display();
    }
    
    /**
     * 修改
     */
    public function update() {
        $model = D("Topic");
        if(IS_POST) {
            if($_POST['pid'] == 0 && empty($_POST['face_pic'])) {
                $this->error('必须上传标示图');
            }
            //删除原标示图
            $topic_info = $model->getInfo($_POST['id']);
            if($topic_info['face_pic'] != $_POST['face_pic']) {
                @unlink('./Uploads/topic_face/'.$topic_info['face_pic']);
            }
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '修改成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $cat_id = I("get.id",0);
        if(!$cat_id) {
            $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
        }
         
        $topic_info = $model->getInfo($cat_id);
        $this->assign("topic_info",$topic_info);
        
        $list = $model->getTopList($topic_info['pid']);
        $this->assign('ptopic_select',$list);
        
        $this->display();
    }
    
    /**
     * 删除
     */
    public function delete() {
        $model = M('Topic');
        $topic_id = I("post.id",0);
        if(!$topic_id) {
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }
        
        //判断是否有子类
        $has_child = $model->where("pid={$topic_id}")->find();
        if($has_child) {
            $this->ajaxReturn(array('status' => 0, 'info' => '此专题下还有子专题，不能删除！'));
        }
        
        $res = $model->where("id={$topic_id}")->delete();
        if($res) {
            $this->ajaxReturn(array('status' => 1, 'url'=>U("index"),'info' => '删除成功'));
        }
    }

    /**
     * 执行上传专题标示图片
     */
    public function doupload() {
        $model = D("Topic");
        if (!empty($_FILES)) {
            import("@.Think.UploadFile");

            $thumb_name = getSn();
            $upload = new \Think\UploadFile();
            $upload->maxSize = 2048000;
            $upload->allowExts = array('jpg','jpeg','gif','png');
            $upload->savePath = "/";
            $upload->subName = 'topic_face';
            $upload->thumb = true; //设置缩略图
            $upload->thumbMaxWidth = "100";
            $upload->thumbMaxHeight = "100";
            $upload->thumbFile= $thumb_name;
            $upload->saveRule = uniqid; //上传规则
            $upload->thumbRemoveOrigin = true; //删除原图
            $up_res = $upload->upload();
            sleep(1);
            if($up_res === false){
                $this->ajaxReturn(array('status' => array('code'=>1,'msg'=>'失败')));
            }

            $pic_data = array(
                'topic_id' => $_POST['album_id'],
                'pic_name' => $thumb_name.'_thumb.'.$up_res['file']['ext'],
            );

            $this->ajaxReturn(array('status' => array('code'=>0,'msg'=>'上传成功'),'data' => $pic_data));
        }
    }
}