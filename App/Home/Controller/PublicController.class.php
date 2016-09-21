<?php
/**
 * 后台Public控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */
namespace Home\Controller;

class PublicController extends CommonController {

    /**
     * 后台用户登录
     */
    public function login($user_name = null, $password = null, $remember_password = null, $verify = null){
        if(IS_POST){
            //检测验证码
//            if(!check_verify($verify)){
//                $this->error('验证码输入错误！');
//            }

            //检查账号有效性
            if(!checkEmail($user_name)) {
                $this->error('邮箱格式错误');
            }
            if(!$user_name || !$password) {
                $this->error('请输入用户名或密码');
            }
            $db = M('user');
            $map['user_name'] = $user_name;
            $map['status'] = 1;
            $user = $db->where($map)->find();
            if(!$user){
                    $this->error('帐号不存在或被禁用');
            }
            if($user['password'] != md5($password)){
                    $this->error('密码错误！');
            }

            //更新登录信息
            $data = array(
                    'user_id' => $user['user_id'],
                    'last_login_time' => NOW_TIME,
                    'last_login_ip' => get_client_ip(),
            );
            $db->save($data);

            /* 记录登录SESSION和COOKIES */
            $this->setCS($user['user_id'],$remember_password);
            unset($user['password']);
            
            //绑定第三方账号
            $server_id = I('post.server',0);
            $target_id = I('post.target',0);
            $where['server_id'] = $server_id;
            $where['target_user_id'] = $target_id;
            $auth_info = M('Oauth')->where($where)->find();
            if(!$auth_info) {
                $bind_data = array(
                        'server_id' => $server_id,
                         'user_id' => $user['user_id'],
                         'target_user_id' => $target_id,
                         'bind_time' => time(),
                    );
                    $bind_res = M('Oauth')->data($bind_data)->add();
            }

            //返回原访问页面
            $tourl = U('Index/index');
            if(isset($_SESSION['tourl']) && $_SESSION['tourl']) {
                $tourl = $_SESSION['tourl'];
            }
            $this->success('登录成功！', $tourl);
        } else {
            if($_COOKIE[$this->loginMarked]){
                $this->redirect('Index/index');
            }else{
                //第三方登录链接
                $qq_login_url = D('Oauth')->qqLoginUrl(C('QQ_APPKEY'),C('QQ_SCOPE'),C('QQ_CALLBACK'));
                $this->assign('qq_login_url', $qq_login_url);
                $weibo_login_url = D('Oauth')->weiboLoginUrl(C('WEIBO_APPKEY'),C('WEIBO_APPSECRET'),C('WEIBO_CALLBACK'));
                $this->assign('weibo_login_url', $weibo_login_url);
                
                //for绑定
                $server_id = I('get.server',0);
                $target_id = I('get.target',0);
                $this->assign('server_id', $server_id);
                $this->assign('target_id', $target_id);
                
                $this->display();
            }
        }
    }
    
    /**
     *  退出登录 
     */
    public function logout(){
        $this->clearCookie();
        $this->redirect("Index/index");
    }
    
    /**
     * 注册
     */
    public function register() {
        $model = D('User');
        if(IS_POST) {
            if(!checkEmail($_POST['user_name'])) {
                $this->ajaxReturn(array('status' => 0, 'info' => '邮箱格式错误！'));
            }
            $_POST['password'] = md5($_POST['password']);
           if($model->create()) {
                $res = $model->add();
                if($res) {
                    $this->ajaxReturn(array('status' => 1, 'url'=>U("Index/index"),'info' => '注册成功'));
                }
            } 
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $this->display();
    }
    
    /**
     * 绑定注册
     */
    public function bind() {
        $model = D('User');
        if(IS_POST) {
            $server_id = I('post.server',0);
            $target_id = I('post.target',0);
            if(in_array($server_id,array(1,2,3)) && $target_id) {
               
            }else{
                $this->ajaxReturn(array('status' => 0, 'info' => '非法操作！'));
            }
            if(!checkEmail($_POST['user_name'])) {
                $this->ajaxReturn(array('status' => 0, 'info' => '邮箱格式错误！'));
            }
            if($_POST['password'] != $_POST['repassword']) {
                $this->ajaxReturn(array('status' => 0, 'info' => '两次密码不一致'));
            }
            if(strtolower($_POST['verify_code']) != strtolower($_SESSION['es_email_code'])) {
                $this->ajaxReturn(array('status' => 0, 'info' => '验证码错误'));
            }
            $_POST['password'] = md5($_POST['password']);
            D('User')->startTrans();//启动事务
           if($model->create()) {
                $res = $model->add();
                if($res) {
                     $bind_data = array(
                        'server_id' => $server_id,
                         'user_id' => $res,
                         'target_user_id' => $target_id,
                         'bind_time' => time(),
                    );
                    $bind_res = M('Oauth')->data($bind_data)->add();
                    if($bind_res) {
                        $user_info = D('user')->where("user_id={$res}")->find();
                        $shell = $user_info['user_id'] . md5($user_info['password'] . C('AUTH_CODE'));
                        $_SESSION[$this->loginMarked] = "$shell";
                        $shell.= "_" . time();
                        setcookie($this->loginMarked, "$shell", 0, "/");

                        /* 记录登录SESSION和COOKIES */
                        $auth = array(
                                'user_id' => $user_info['user_id'],
                                'user_name' => $user_info['user_name'],
                                'real_name' => $user_info['real_name'],
                                'last_login_time' => time(),
                        );
                        session('es_user', $auth);
                        D('User')->commit();
                        $this->ajaxReturn(array('status' => 1, 'url'=>U("Index/index"),'info' => '绑定成功'));
                    }else{
                        D('User')->rollback();
                        $this->ajaxReturn(array('status' => 0, 'url'=>U("Public/login"),'info' => '绑定异常'));
                    }
                    
                }
            } 
            $this->ajaxReturn(array('status' => 0, 'info' => $model->getError()));
        }
        
        $server_id = I('get.server',0);
        $target_id = I('get.target',0);
        if(in_array($server_id,array(1,2,3)) && $target_id) {
            $this->assign('server_id', $server_id);
            $this->assign('target_id', $target_id);
        }else{
            $this->error('非法操作！', U('Public/login'));
        }
        $this->display();
    }

    public function verify(){
        ob_end_clean();
        $config =    array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }

    /**
     * 找回密码
     */
    public function getpass() {
        $model = D('User');
        $user_email = I("post.uemail",'');
        $vcode = I("post.vcode",'');

        $verify_uemail = I('get.verify_uemail','');

        $newpass = I('post.newpass','');
        $renewpass = I('post.renewpass','');

        //确认账号
        if($user_email) {
            //验证码验证
            if(!check_verify($vcode)) {
                $this->ajaxReturn(array('status' => 0, 'info' => '验证码错误'));
            }

            //判断用户输入的邮箱是否存在
            $user_info = $model->where("user_name='{$user_email}'")->find();
            if($user_info) {
                $url = U("Public/getpass",array('verify_uemail'=>$user_email));

                $this->ajaxReturn(array('status' => 1, 'url' => $url, 'info' => 'success'));
            }else{
                $this->ajaxReturn(array('status' => 0, 'info' => '您输入的邮箱不存在'));
            }
        }

        //安全验证
        if($verify_uemail) {

            $user_info = $model->where("user_name='{$verify_uemail}'")->find();
            $this->assign('user_info',$user_info);

            $this->display('Public/getpass/verify');
            exit;
        } 

        $this->display('Public/getpass/confirm');
    }

    /**
     * 执行安全验证
     */
    public function do_verify() {
        $model = D('User');
        $verify_method = I('post.verify_method','');
        $user_name = I('post.user_name','');
        $verify_code = I('post.verify_code','');
        if($verify_method && $user_name) {
            if(strtolower($verify_code) != strtolower($_SESSION['es_email_code'])) {
                $this->ajaxReturn(array('status' => 0, 'info' => '验证码错误'));
            }

            if((time()-$_SESSION['es_email_code_st']) > 3600) {
                $this->ajaxReturn(array('status' => 0, 'info' => '验证码已失效'));
            }

            $url = U("Public/reset",array('user_name'=>$user_name));
            $this->ajaxReturn(array('status' => 1, 'url'=>$url, 'info' => '验证成功'));
        }

        $this->ajaxReturn(array('status' => 0, 'info' => '非法操作'));

    }

    /**
     * 重置密码
     */
    public function reset() {
        $model = D('User');
        $user_name = I('get.user_name','');
        $user_info = $model->where("user_name='{$user_name}'")->find();
        if(IS_POST) {

            if($_POST['newpass'] != $_POST['renewpass']) {
                $this->ajaxReturn(array('status' => 0, 'info' => '两次密码不一致'));
            }

            $res = $model->where("user_id={$_POST[user_id]}")->save(array('password'=>md5(trim($_POST['newpass']))));
            if($res !== false) {
                $this->ajaxReturn(array('status' => 1, 'url' => U("Public/login"), 'info' => '重置成功'));
            }

            $this->ajaxReturn(array('status' => 0, 'info' => '系统异常'));
        }

        $this->assign('user_info',$user_info);
        $this->display('Public/getpass/reset');
    }

    /**
     * 发送验证码
     */
    public function sendcode() {

        $verify_method = I('post.verify_method','');
        $username = I('post.user_name','');

        $title="享得帐号--邮箱身份验证";
        $code = make_code(4);
        $_SESSION['es_email_code'] = $code;
        $_SESSION['es_email_code_st'] = time();
        $template_data = array(
            'user_name' => $username,
            'code' => $code,
            'nowtime' => time(),

        );
        $this->assign('info',$template_data);

        $content = $this->fetch('Public/email_template');

        $from="zql_0539@163.com"; //修改为你的发送邮箱
        $to=trim($username);

        // 调用发送邮件函数
        $status = send_mail ( $title,$content,$from,$to);
        if($status==1){
            $this->ajaxReturn(array('status' => 1, 'info' => '发送成功'));
        }else{
            $this->ajaxReturn(array('status' => 0, 'info' => '发送失败'));
        }
    }
}
?>