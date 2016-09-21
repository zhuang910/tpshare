<?php
/**
 * OAuthModel
 *
 * @author zhuangqianlin 2015-10-20
 */
namespace Home\Model;
use Think\Model;

class OauthModel extends Model {
    public $get_qq_auth_code_url = "https://graph.qq.com/oauth2.0/authorize";
    public $get_qq_access_token_url = "https://graph.qq.com/oauth2.0/token";
    public $get_qq_openid_url = "https://graph.qq.com/oauth2.0/me";
    public $get_qq_api_url = 'https://graph.qq.com';
    public $get_qq_get_user_info = 'https://graph.qq.com/user/get_user_info';

    //第三方平台标识：1QQ，2新浪微博,3微信，4百度'
    const SERVER_TYPE_QQ = 1;
    const SERVER_TYPE_WEIBO = 2;
    const SERVER_TYPE_WEIXIN = 3;
    const SERVER_TYPE_BAIDU = 4;

    /**
     * qq_login_url
     */
    public function qqLoginUrl($appkey, $scope, $callback) {
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
        $login_url = $this->get_qq_auth_code_url."?response_type=code&client_id=" 
        . $appkey . "&redirect_uri=" . urlencode($callback)
        . "&state=" . $_SESSION['state']
        . "&scope=".$scope;
        
        return $login_url;
    }
    
    /**
     * 获取accesstoken
     */
    public function qqAccesstoken($appkey,$appsecret,$code,$callback) {
        $token_url = $this->get_qq_access_token_url."?grant_type=authorization_code&"
            . "client_id=" . $appkey. "&redirect_uri=" . urlencode($callback)
            . "&client_secret=" . $appsecret. "&code=" . $code;

        $response = file_get_contents($token_url);
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error)) {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }

        $params = array();
        parse_str($response, $params);

        //set access token to session
        $_SESSION["qq_access_token"] = $params["access_token"];
        return $params["access_token"];
    }
    
    /**
     * 获取qq_openid
     */
    public function qqOpenid($access_token) {
        import('Com.Email.PHPMailer');
        $graph_url = $this->get_qq_openid_url."?access_token=" . $access_token;
        $str  = file_get_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($str);
        if (isset($user->error)) {
            echo "<h3>error:</h3>" . $user->error;
            echo "<h3>msg  :</h3>" . $user->error_description;
            exit;
        }

        //set openid to session
        $_SESSION["qq_openid"] = $user->openid;
        return $user->openid;
    }

    /**
     * 获取qq_openid
     */
    public function qqGetUserInfo($access_token,$appid,$openid) {
        $graph_url = $this->get_qq_get_user_info."?access_token=" . $access_token.
        "&oauth_consumer_key=".$appid."&openid=".$openid;
        $str  = self::curl($graph_url);
        $user = json_decode($str);
        if (isset($user->ret) && $user->ret) {
            echo "<h3>error:</h3>" . $user->ret;
            echo "<h3>msg  :</h3>" . $user->msg;
            exit;
        }

        return $user;
    }


        /**
     * sina_login_url
     */
    public function weiboLoginUrl($appkey, $appsecret,$callback) {
        import("Com.Oauth.weibo_sdk.Saetv2#ex");
        
        $auth = new \SaeTOAuthV2( $appkey , $appsecret );
        $login_url = $auth->getAuthorizeURL( $callback );
        
        return $login_url;
    }

    //CURL请求
    public static function curl($destURL, $paramStr='',$flag='get'){
        if(!extension_loaded('curl')) exit('php_curl.dll');
        $curl = curl_init();
        if($flag=='post'){//post
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $paramStr);
        }
        curl_setopt($curl, CURLOPT_URL, $destURL);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $str = curl_exec($curl);
        curl_close($curl);
        return $str;
    }
}
