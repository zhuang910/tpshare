<?php
/**
 * OAuthController
 *
 * @author zhuangqianlin 2015-10-20
 */
namespace Home\Controller;

class OauthController extends CommonController{
    
    //qq应用回调
    public function QQCallback() {
        if($_REQUEST['state'] == $_SESSION['state']) {//csrf
            $code = $_REQUEST['code'];
            $access_token = D('Oauth')->qqAccesstoken(C('QQ_APPKEY'),C('QQ_APPSECRET'),$code,C('QQ_CALLBACK'));
            $openid = D('Oauth')->qqOpenid($access_token);

            //获取qq用户xx
            $qq_user_info = D('Oauth')->qqGetUserInfo($access_token,C('QQ_APPKEY'),$openid);
            
            //to login or bind
            $param['server_id'] = \Home\Model\OauthModel::SERVER_TYPE_QQ;
            $param['target_id'] = $openid;
            $param['target_user_name'] = $qq_user_info->nickname;
            $this->toBind($param);
        } else {
            echo("The state does not match. You may be a victim of CSRF.");
        }
    }
    
    //sina应用回调
    public function WeiboCallback() {
        import("Com.Oauth.weibo_sdk.Saetv2#ex");
        
        $auth = new \SaeTOAuthV2( C('WEIBO_APPKEY'),C('WEIBO_APPSECRET') );

        if (isset($_REQUEST['code'])) {
                $keys = array();
                $keys['code'] = $_REQUEST['code'];
                $keys['redirect_uri'] = C('WEIBO_CALLBACK');
                $token = $auth->getAccessToken( 'code', $keys );
                $weibo_user_id = $token['uid'];
                
                //to login or bind
                $param['server_id'] = \Home\Model\OauthModel::SERVER_TYPE_WEIBO;
                $param['target_id'] = $weibo_user_id;
                $this->toBind($param);
        }
    }
    
    /**
     * to login or bind
     */
    protected function toBind($param) {
        //获取绑定信息
        $where['server_id'] = $param['server_id'];
        $where['target_user_id'] = $param['target_id'];
        $auth_info = M('Oauth')->where($where)->find();
        if($auth_info) {//已绑定享得账号
            $user_info = D('user')->where("user_id={$auth_info[user_id]}")->find();
            if($user_info) {
                $data = array(
                    'user_id'        => $user_info['user_id'],
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(),
                );
                M('user')->save($data);

                $this->setCS($user_info['user_id']);
                //$this->success('登录成功！', U('Index/index'));
                $this->redirect('Index/index');
            } else {
                $this->error('登录异常！', U('Public/index'));
            }

        } else {//未绑定
            $this->assign('server_id', $param['server_id']);
            $this->assign('target_id', $param['target_id']);
            $this->assign('target_user_name', $param['target_user_name']);

            $this->display('Public/bind');
        }
    }
}
