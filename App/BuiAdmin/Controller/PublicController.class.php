<?php
/**
 * 后台Public控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */

namespace BuiAdmin\Controller;

class PublicController extends CommonController {
    
    /**
     * 初始化
     */
    public function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        $loginMarked = C("TOKEN");
        $this->loginMarked = md5($loginMarked['admin_marked']);
    }

    /**
     * 后台用户登录
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            /* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }

            $db = M('admin');
            $map['admin_name'] = $username;
            $map['status'] = 1;
            $admin_user = $db->where($map)->find();
            if(!$admin_user){
                    $this->error('帐号不存在或被禁用');
            }

            if($admin_user['password'] != md5($password)){
                    $this->error('密码错误');
            }

            $data = array(
                    'admin_id'        => $admin_user['admin_id'],
                    //'login'           => array('exp', '`login`+1'),
                    'last_login_time' => NOW_TIME,
                    'last_login_ip'   => get_client_ip(),
            );
            $db->save($data);

            $shell = $admin_user['admin_id'] . md5($info['pwd'] . C('AUTH_CODE'));
            $_SESSION[$this->loginMarked] = "$shell";
            $shell.= "_" . time();
            setcookie($this->loginMarked, "$shell", 0, "/");
            unset($info['pwd']);

            /* 记录登录SESSION和COOKIES */
            $auth = array(
                    'admin_id' => $admin_user['admin_id'],
                    'admin_name' => $admin_user['admin_name'],
                    'nick_name' => $admin_user['nick_name'],
                    'last_login_time' => $data['last_login_time'],
                    'role_id' => $admin_user['role_id'],
            );
            session('esadmin_user', $auth);
            $this->success('登录成功！', U('Index/index'));

        } else {
            if($_COOKIE[$this->loginMarked]){
                $this->redirect(U('Index/index'));
            }else{
                $this->display();
            }
        }
    }
    
    /**
     *  退出登录 
     */
    public function logout(){
        if($_COOKIE[$this->loginMarked]){
            setcookie("$this->loginMarked", NULL, -3600, "/");
            unset($_SESSION["$this->loginMarked"], $_COOKIE["$this->loginMarked"]);
            if (isset($_SESSION['admin_user'])) {
                unset($_SESSION['admin_user']);
                unset($_SESSION);
                session_destroy();
            }
        } 
        
        $this->redirect(U('Index/index'));
    }

    public function verify(){
        ob_end_clean();
        $verify = new \Think\Verify();
        $verify->entry();
    }
}
?>