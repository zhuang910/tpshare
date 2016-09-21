<?php
return array(
	//'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__'    => __ROOT__ . '/Public/Public',
        '__ADMIN_PUBLIC__'    => __ROOT__ . '/Public/Admin/Public',
        '__ADMIN_ADMIN__'    => __ROOT__ . '/Public/Admin/Admin',
        
    ),
    
    'PAGE_SIZE' => 20,//分页大小
    
    'NOT_AUTH_CONTROLLER' => 'Public,Index', // 默认无需认证模块
);