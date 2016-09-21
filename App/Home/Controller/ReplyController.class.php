<?php
/**
 * 评论控制器
 * 
 * @author zhuangqianlin 2015-09-11
 */
namespace Home\Controller;

class ReplyController extends CommonController {
    public function index(){
        
    }
    
    /**
     * 发布评论
     */
    public function save() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('Reply');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            $_POST['add_time'] = time();
            if($model->create()) {
                $res = $model->add();
                if($res) {
                    $user = M("User")->where("user_id={$_SESSION['es_user']['user_id']}")->find();
                    $data = array(
                        'user_name' => $_SESSION['es_user']['real_name'],
                        'user_portrait' => $user['portrait_subname'].'/50_50'.$user['portrait_ext'],
                        'content' => $_POST['content'],
                        'add_time' => date("Y-m-d H:i:s",$_POST['add_time']),
                    );
                    $this->ajaxReturn(array('status' => 1, 'info' => '评论成功','data'=> $data));
                }
            }
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
    }
}