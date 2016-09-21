<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__'    => __ROOT__ . '/Public/Public',
        '__UPLOADS__'    => __ROOT__ . '/Uploads',
        '__HOME__'    => __ROOT__ . '/Public/Home',
        '__ADMIN_BUIADMIN__'    => __ROOT__ . '/Public/Admin/Buiadmin',

    ),
    'PAGE_SIZE' => 5,//分页大小
    
    'VAR_SESSION_ID' => 'session_id', 
    
    //SDK相关
    'QQ_APPKEY' => '101261498',
    'QQ_APPSECRET' => 'f3924b777d7bc22b5e65ff7198f958ca',
    'QQ_SCOPE' => 'get_user_info',
    'QQ_CALLBACK' => 'http://share.6655.la/Oauth/QQCallback',
    
    'WEIBO_APPKEY' => '2441924407',
    'WEIBO_APPSECRET' => 'f3caf4958388277b16c8fab16cb5a7aa',
    'WEIBO_CALLBACK' => 'http://share.6655.la/Oauth/WeiboCallback',

    /* 数据缓存设置 */
    'DATA_CACHE_TIME'       => 10,      // 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   => true,   // 数据缓存是否压缩缓存
    'DATA_CACHE_PREFIX'     => 'xiangde',     // 缓存前缀
    'DATA_CACHE_TYPE'       => 'Memcache',  // 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'MEMCACHE_HOST'         => '127.0.0.1:11211,127.0.0.1:11211',

);
