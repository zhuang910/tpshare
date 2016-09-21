<?php

return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__ADMIN_PUBLIC__'    => __ROOT__ . '/Public/Admin/Public',
        '__ADMIN_BUIADMIN__'    => __ROOT__ . '/Public/Admin/Buiadmin',
        '__PUBLIC__'    => __ROOT__ . '/Public/Public',
        '__UPLOADS__'    => __ROOT__ . '/Uploads',

    ),
    
    'PAGE_SIZE' => 20,//分页大小
    
    'NOT_AUTH_CONTROLLER' => 'Common,Public,Index', // 默认无需认证模块
    
    'COOKIE_DOMAIN'=> '.'.MODULE_NAME.'.'.$_SERVER['HTTP_HOST'],
);