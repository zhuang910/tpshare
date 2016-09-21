<?php
/**
 * 相册控制器
 * 
 * @author zhuangqianlin 2015-12-04
 */
namespace Home\Controller;

class AlbumController extends CommonController {

    /**
     * 上传图片
     */
    public function upload(){
        //判断是否登录
        $this->checkLogin();
        
        $model = D('album');
        $album_id = I("get.album_id",0);
        $where = array('user_id' => $_SESSION['es_user']['user_id']);

        //获取相册select
        $album_select = $model->getList($album_id,true,$where);
        $this->assign('album_select',$album_select);

        $this->display();
    }

    /**
     * 执行上传图片
     */
    public function doupload() {
        //判断是否登录
        $this->checkLogin();

        $model = D("User");
        $picmodel = D("picture");

        if (!empty($_FILES)) {
            if($_SESSION['es_user']['user_id'] != $_POST['user_id']) {
                $this->ajaxReturn(array('status' => array('code'=>1,'msg'=>'非法操作')));
            }
            import("@.Think.UploadFile");
            $user_info = $model->getInfo($_SESSION['es_user']['user_id']);

            $upload = new \Think\UploadFile();
            $upload->maxSize = 2048000;
            $upload->allowExts = array('jpg','jpeg','gif','png');
            $upload->savePath = "/album/";
            $upload->subName = $user_info['album_subname'] ? $user_info['album_subname'] : 'album_'.getSn();
            $upload->thumb = false; //设置缩略图
            $upload->saveRule = 'uniqid'; //上传规则

            $up_res = $upload->upload();
            if($up_res === false){
                $this->ajaxReturn(array('status' => array('code'=>1,'msg'=>'失败')));
            }

            $update_data = array(
                'user_id' => $_SESSION['es_user']['user_id'],
                'album_subname' => $upload->subName,
            );
            $model->save($update_data);

            $pic_data = array(
                'user_id' => $_SESSION['es_user']['user_id'],
                'album_id' => $_POST['album_id'],
                'pic_url' => $up_res['file']['savepath'].$up_res['file']['savename'],
                'add_time' => time(),
            );
            $res = $picmodel->data($pic_data)->add();
            if($res !== false) {
                $this->ajaxReturn(array('status' => array('code'=>1,'msg'=>'失败')));
            }

            $this->ajaxReturn(array('status' => array('code'=>0,'msg'=>'上传成功')));
        }
    }

    /**
     * 展现相册图片
     */
    public function picture() {
        //判断是否登录
        $this->checkLogin();

        $model = D('album');
        $picmodel = D('picture');
        $album_id = I("get.album_id",0);
        $view_type = I("get.view_type",1);

        //获取相册select
        $where = array('user_id' => $_SESSION['es_user']['user_id']);
        $album_select = $model->getList($album_id,false,$where);
        $this->assign('album_select',$album_select);

        //获取相册信息
        $where = array(
            'user_id' => $_SESSION['es_user']['user_id'],
            'id' => $album_id,
        );
        $info = $model->where($where)->find();
        $this->assign('album_info',$info);

        $condition = array(
            'user_id' => $_SESSION['es_user']['user_id'],
            'album_id' => $album_id,
        );

        //统计
        $picture_count = $picmodel->getCount($condition);
        $this->assign('picture_count',$picture_count>0?$picture_count:0);

        //获取图片列表
        $picture_list = $view_type==1? $picmodel->getList($condition,0,10) : $picmodel->getAllList($condition);
        $last_info = end($picture_list);
        $this->assign('picture_list',$picture_list);
        $this->assign('album_id',$album_id);
        $this->assign('view_type',$view_type);
        $this->assign('lastid',$last_info['id']);

        $this->display();
    }
    
    /**
     * 获取单个相册信息 ajax
     */
    public function getInofByAjax() {
        //判断是否登录
        $this->checkLogin();
        
        $model = M('Album');
        $album_id = I("post.album_id",0);
        
        //获取
        $where = array(
            'user_id' => $_SESSION['es_user']['user_id'],
            'id' => $album_id,
            );
        $info = $model->where($where)->find();
        if($info) {
            $is_public1 = $info['is_public'] ==1? 'checked':'';
            $is_public2 = $info['is_public'] ==2? 'checked':'';
            $is_public_select = '<label class="radio-inline"><input type="radio" name="is_public" id="is_public1" value="1" '.$is_public1.'> 所有人可见</label><label class="radio-inline"><input type="radio" name="is_public" id="is_public2" value="2" '.$is_public2.'> 仅自己可见</label>';
            $data = array(
                'album_info' => $info,
                'is_public_select' => $is_public_select,
            );

            $this->ajaxReturn(array('status' => 1, 'data' => $data));
        }
        
        $this->ajaxReturn(array('status' => 0, 'info' =>'系统异常'));
    }

    /**
     * 增加
     */
    public function addByAjax() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D('album');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            if($model->create()) {
                $res = $model->add();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'info' => '创建成功'));
                }
            }

            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
    }

    /**
     * 获取相册 异步
     */
    public function getAlbumSelectByAjax() {
        //判断是否登录
        $this->checkLogin();

        $model = D('album');
        if(IS_POST) {
            $_POST['user_id'] = $_SESSION['es_user']['user_id'];
            $where = array('user_id' => $_SESSION['es_user']['user_id']);
            //获取相册select
            $album_select = $model->getList(0,true,$where);
            if($album_select) {
                $data = array(
                    'album_select' => $album_select,
                );
                $this->ajaxReturn(array('status' => 1, 'info' => '成功','data'=> $data));
            }

            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
    }
    
    /**
     * 修改
     */
    public function updateByAjax() {
        //判断是否登录
        $this->checkLogin();
        
        $model = D("album");
        if(IS_POST) {
            $info = $model->where("id={$_POST[album_id]}")->find();
            if($info['user_id'] != $_SESSION['es_user']['user_id']) {
                $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));
            }
            $_POST['id'] = $_POST['album_id'];
            unset($_POST['album_id']);
            if($model->create()) {
                $res = $model->save();
                if($res !== false) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("User/album"),'info' => '修改成功'));
                }
            }
            
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
    }
    
    /**
     * 删除
     */
    public function delByAjax() {
        //判断是否登录
        $this->checkLogin();
        
        $model = M('Album');
        $picmodel = M("picture");
        $album_id = I("post.album_id",0);
        $del_type = I("post.del_type",0);
        $to_album_id = I("post.to_album_id",0);
        if(!$album_id || !$del_type) {
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }

        //删除相册，将图片移至其他相册
        if($del_type == 2) {
            M()->startTrans();//开启事务
            $model->where("id={$album_id}")->delete();
            $res = $picmodel->where("album_id={$album_id}")->save(array('album_id'=>$to_album_id));
            if(false !== $res) {
                M()->commit();//提交事务
            }else{
                M()->rollback();//事务回滚
            }
        }

        //删除相册时也删除图片
        if($del_type == 1){
            M()->startTrans();//开启事务
            $model->where("id={$album_id}")->delete();
            $todelpic = $picmodel->where("album_id={$album_id}")->select();
            $res = $picmodel->where("album_id={$album_id}")->delete();
            if(false !== $res) {
                //删除真实图片
                //code...
                M()->commit();//提交事务
            }else{
                M()->rollback();//事务回滚
            }
        }

        $this->ajaxReturn(array('status' => 1, 'url'=>U("User/album"),'info' => '删除成功'));
    }

    /**
     * 无限加载图片
     */
    public function getMorePictures() {
        //判断是否登录
        $this->checkLogin();

        $picmodel = D("picture");
        $maxid = I("post.maxid",0);
        $album_id = I("post.album_id",0);
        $condition = array(
            'id' => array('lt',$maxid),
            'album_id' => $album_id,
        );

        //获取图片列表
        $picture_list = $picmodel->getList($condition,0,10);
        $picture_list = $picture_list ? $picture_list : array();
        $this->assign('picture_list',$picture_list);
        $data = $this->fetch('pictureItem');
        $last_info = end($picture_list);

        $this->ajaxReturn(array('status' => 1, 'info' => '加载成功','data'=>$data,'lastid'=> $last_info['id']));
    }

    /**
     * 删除
     */
    public function delPic() {
        //判断是否登录
        $this->checkLogin();

        $model = D("picture");
        $pic_id = I("post.id",0);
        $album_id = I("post.album_id",0);
        if(!$pic_id) {
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }

        //判断是否有子类
        $pic_info = $model->where("id={$pic_id}")->find();
        if($pic_info['user_id'] != $_SESSION['es_user']['user_id']) {
            $this->ajaxReturn(array('status' => 0, 'info' => '只能删除自己的图片，不能删除！'));
        }

        $res = $model->where("id={$pic_id}")->delete();
        if($res) {
            @unlink('./Uploads/'.$pic_info['pic_url']);
            $this->ajaxReturn(array('status' => 1, 'url'=>U("Album/picture",array('album_id'=>$album_id)),'info' => '删除成功'));
        }
    }

    /**
     * 下载
     */
    public function downPic() {
        //判断是否登录
        $this->checkLogin();

        $model = D("picture");
        $pic_id = I("get.id",0);
        if(!$pic_id) {
            $this->ajaxReturn(array('status' => 0,'info' => '非法操作'));
        }

        $pic_info = $model->where("id={$pic_id}")->find();
        $file = './Uploads/'.$pic_info['pic_url'];
        $fileTmp = pathinfo($file);
        $saveFileName = ($fileTmp['basename']);
        $fp=fopen($file,"r");
        $file_size=filesize($file);

        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length:".$file_size);
        Header("Content-Disposition: attachment; filename=".$saveFileName);
        $buffer=1024;
        $file_count=0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count<$file_size){
            $file_con=fread($fp,$buffer);
            $file_count+=$buffer;
            echo $file_con;
        }
        fclose($fp);
        exit;
    }
    
}