<?php
return array(
    //'配置项'=>'配置值'
    //模块配置
    "jquery" => "http://libs.useso.com/js/jquery/2.1.1/jquery.min.js",
    'MODULE_ALLOW_LIST' => array('AmaAdmin','BuiAdmin','Home'),
    'DEFAULT_MODULE' => 'Home',
    
    //数据库配置
    'DB_DEPLOY_TYPE' =>  0,         // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_TYPE'  =>  'mysql',         // 数据库类型
    'DB_HOST' =>  '127.0.0.1',      // 服务器地址
    'DB_NAME' =>  'enjoy_share',    // 数据库名
    'DB_USER' =>  'root',           // 用户名
    'DB_PWD' =>  '',                // 密码
    'DB_PORT' =>  '',               // 端口
    'DB_PREFIX' =>  'es_',          // 数据库表前缀
    
    //布局配置
    'TMPL_L_DELIM' =>  '<{',        // 模板引擎普通标签开始标记
    'TMPL_R_DELIM' =>  '}>',        // 模板引擎普通标签结束标记
    
    //URL配置
    'URL_MODEL' =>  2,              // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    
    'TOKEN' => array (
		'admin_marked' => 'admin.share.6655.la',
		'admin_timeout' => 7200,
		'user_marked' => 'share.6655.la',
		'user_timeout' => 3600,
    ),
    
);