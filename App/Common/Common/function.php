<?php

//格式化输出
function p($arr) {
    echo '<pre>';
    print_r($arr);
}
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true,$suffix_str='...')
{
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset).$suffix_str;
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset).$suffix_str;
        else
                return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice.$suffix_str;
    return $slice;
}

function utf8substr($str, $start=0, $length, $charset="utf-8", $suffix=true,$suffix_str='...')
{
	if(function_exists("mb_substr")){
		if ($suffix && strlen($str)>$length*3)
			return mb_substr($str, $start, $length, $charset).$suffix_str;
		else
			return mb_substr($str, $start, $length, $charset);
	}
	
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice.$suffix_str;
	return $slice;
}

function curl_get_contents($url,$timeout=100) { 
	$curlHandle = curl_init(); 
	curl_setopt( $curlHandle , CURLOPT_URL, $url ); 
	curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout ); 
	$result = curl_exec( $curlHandle ); 
	curl_close( $curlHandle ); 
	return $result; 

}
function getfirstchar($s0){
	$firstchar_ord=ord(strtoupper($s0{0}));
	if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0};
	//$content = iconv("utf-8","gb2312//IGNORE",$content);
	$s=iconv("UTF-8","gb2312", $s0);
	$asc=ord($s{0})*256+ord($s{1})-65536;
	if($asc>=-20319 and $asc<=-20284)return "A";
	if($asc>=-20283 and $asc<=-19776)return "B";
	if($asc>=-19775 and $asc<=-19219)return "C";
	if($asc>=-19218 and $asc<=-18711)return "D";
	if($asc>=-18710 and $asc<=-18527)return "E";
	if($asc>=-18526 and $asc<=-18240)return "F";
	if($asc>=-18239 and $asc<=-17923)return "G";
	if($asc>=-17922 and $asc<=-17418)return "H";
	if($asc>=-17417 and $asc<=-16475)return "J";
	if($asc>=-16474 and $asc<=-16213)return "K";
	if($asc>=-16212 and $asc<=-15641)return "L";
	if($asc>=-15640 and $asc<=-15166)return "M";
	if($asc>=-15165 and $asc<=-14923)return "N";
	if($asc>=-14922 and $asc<=-14915)return "O";
	if($asc>=-14914 and $asc<=-14631)return "P";
	if($asc>=-14630 and $asc<=-14150)return "Q";
	if($asc>=-14149 and $asc<=-14091)return "R";
	if($asc>=-14090 and $asc<=-13319)return "S";
	if($asc>=-13318 and $asc<=-12839)return "T";
	if($asc>=-12838 and $asc<=-12557)return "W";
	if($asc>=-12556 and $asc<=-11848)return "X";
	if($asc>=-11847 and $asc<=-11056)return "Y";
	if($asc>=-11055 and $asc<=-10247)return "Z";
	return 0;//null
}
     /**
     * 加密，解密方法。
     *
     * @param string $string
     * @param string $key
     * @param string $operation encode|decode
     * @return string
     */
    function myCrypt($string, $key, $operation = 'encode') {
        $key_length = strlen($key);
        $string = (strtolower($operation) == 'decode') ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';

        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if (strtolower($operation) == 'decode') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return base64_encode($result);
        }
    }

    function checkEmail($email) {
        $regexp = '/^[\w\-\.]+@[\w\-]+(\.[\w\-]+)*(\.[a-z]{2,})$/';
        if (strlen($email) <= 32 && preg_match($regexp, $email)) {
            return true;
        }
        return false;
    }


    /**
     * 发送邮件
     *
     * @author zhuangqianlin 2015-10-18
     */
    function send_mail($title, $content, $from, $to, $chart = 'utf-8', $attachment = '') {
        import('Com.Email.PHPMailer');
        import('Com.Email.SMTP');
        $mail = new PHPMailer ();
        $mail->CharSet = $chart; // 设置采用gb2312中文编码
        $mail->IsSMTP ( 'smtp' ); // 设置采用SMTP方式发送邮件
        $mail->Host = "smtp.163.com"; // 设置邮件服务器的地址
        $mail->Port = 25; // 设置邮件服务器的端口，默认为25
        $mail->From = $from; // 设置发件人的邮箱地址
        $mail->FromName = "享得科技 "; // 设置发件人的姓名
        $mail->SMTPAuth = true; // 设置SMTP是否需要密码验证，true表示需要
        //$mail->SMTPSecure = 'ssl';
        $mail->Username = "zql_0539@163.com"; // 设置发送邮件的邮箱
        $mail->Password = "1359590400@zs"; // 设置邮箱的密码
        $mail->Subject = $title; // 设置邮件的标题
        $mail->AltBody = "text/html"; // optional, comment out and test
        $mail->Body = $content; // 设置邮件内容
        $mail->IsHTML ( true ); // 设置内容是否为html类型
        $mail->WordWrap = 50; // 设置每行的字符数
        $mail->AddReplyTo ( "地址", "名字" ); // 设置回复的收件人的地址
        $mail->AddAddress ( $to, "" ); // 设置收件的地址
        if ($attachment != '') {
            $mail->AddAttachment ( $attachment, $attachment );
        }
        if ($mail->Send ()) {
            //$status1 = "$to" . '&nbsp;&nbsp;已投送成功<br />';
            $status = 1;

        } else {
            //$status2 = "$to" . '&nbsp;&nbsp;发送邮件失败<br />';
            $status = $mail->ErrorInfo;
        }
        return $status;
    }

    /**
     * 生产随机字符串(含大小写字母数字特殊符号)
     */
    function make_code( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
//        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
//            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
//            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
//            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
//            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
//            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!',
//            '@','#' , '$', '%', '^', '&', '*', '(', ')', '-', '_',
//            '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',',
//            '.', ';', ':', '/', '?', '|');
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h','i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y','z','0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            );

        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars,$length);


        $password = '';
        for($i = 0; $i < $length; $i++)
        {
            // 将 $length 个数组元素连接成字符串
            $password .= $chars[$keys[$i]];
        }

        return $password;
    }

