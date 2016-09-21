<?php
/**
 * 后台公共控制器
 * 
 * @author zhuangqianlin 2015-09-01
 */
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
	public $loginMarked;
    const COOKIE_STR = 'escookie';
        
	public function _initialize(){
            header('Content-Type:text/html,charset=utf8');
            $sysConfig['TOKEN'] = C('TOKEN');
            ini_set('session_maxlifetime', $sysConfig['TOKEN']['user_timeout']);
            $this->loginMarked = md5($sysConfig['TOKEN']['user_marked']);
            $this->getCS();
	}
        
    protected function checkLogin() {
        $cs = $this->getCS();
        if ($cs != null) {
            if ($cs == 1) {
                $this->clearCookie();
                $this->error("登录超时，请重新登录", __MODULE__.U("Public/login"));
            }
        } else {
            $_SESSION['tourl'] = html_entity_decode($_SERVER['REQUEST_URI']);;
            $this->redirect("Public/login");
        }
        return TRUE;
    }

    /**
     * set cookie,session
     */
    protected function setCS($userId,$remember=0) {
        $user_info = D('User')->getInfo($userId);
        if($remember){
            $timeout = C("TOKEN");
            $exp_time = time() + $timeout['user_timeout'];
        }  else {
            $exp_time = 0;
        }
        $str = $userId . '|' . $exp_time . '|' . md5($userId . $user_info['password'] . self::COOKIE_STR);
        setcookie($this->loginMarked, $str, $exp_time, '/');
        session('es_user', $user_info);
    }

    /**
     * get cookie,session
     */
    protected function getCS() {
        //记住密码
        if (isset($_COOKIE[$this->loginMarked]) && $_COOKIE[$this->loginMarked] && (trim($_COOKIE[$this->loginMarked])!='')) {
            $cookieary = explode('|',$_COOKIE[$this->loginMarked]);
            if (count($cookieary) == 3) {
                if ($cookieary[1] > time() || $cookieary[1] == 0) {
                    $user = D('User')->getInfo($cookieary[0]);
                    $md5str = md5($cookieary[0] . $user['password'] . self::COOKIE_STR);
                    if ($md5str == $cookieary[2]) {
                        session('es_user', $user);
                        return $user;
                    }
                }else if($cookieary[1] <= time()) {
                    return 1;
                }
            }
        }
        $this->clearCookie();
        return null;
    }

    /**
     * clear cookie
     */
    protected function clearCookie() {
        setcookie($this->loginMarked, '', time() - 36000, '/');
        unset($_SESSION['es_user']);
        //session_destroy();
    }
}
?>