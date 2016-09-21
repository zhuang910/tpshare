-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.5.20-log - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win32
-- HeidiSQL 版本:                  8.2.0.4675
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出 enjoy_share 的数据库结构
CREATE DATABASE IF NOT EXISTS `enjoy_share` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `enjoy_share`;


-- 导出  表 enjoy_share.es_admin 结构
CREATE TABLE IF NOT EXISTS `es_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `admin_name` varchar(50) NOT NULL COMMENT '登录用户名',
  `nick_name` varchar(50) NOT NULL DEFAULT '''''' COMMENT '昵称',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态:1正常',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `last_login_ip` varchar(32) NOT NULL COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- 正在导出表  enjoy_share.es_admin 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `es_admin` DISABLE KEYS */;
INSERT INTO `es_admin` (`admin_id`, `admin_name`, `nick_name`, `password`, `role_id`, `status`, `add_time`, `last_login_ip`, `last_login_time`) VALUES
	(1, 'admin', '超级管理员', '21232f297a57a5a743894a0e4a801fc3', 1, 1, 0, '127.0.0.1', 1445420704),
	(4, 'demo', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 1, 0, '127.0.0.1', 1441606015);
/*!40000 ALTER TABLE `es_admin` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_article 结构
CREATE TABLE IF NOT EXISTS `es_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id,0表示系统',
  `cat_id` int(11) NOT NULL COMMENT '分类id',
  `title` varchar(128) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `click_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示，2隐藏',
  `is_public` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1所有人可见，2尽自己可见',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`article_id`),
  KEY `user_id` (`user_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- 正在导出表  enjoy_share.es_article 的数据：~9 rows (大约)
/*!40000 ALTER TABLE `es_article` DISABLE KEYS */;
INSERT INTO `es_article` (`article_id`, `user_id`, `cat_id`, `title`, `content`, `click_count`, `is_show`, `is_public`, `add_time`) VALUES
	(1, 0, 1, 'test', 'wewewr', 0, 2, 1, 0),
	(2, 0, 3, 'ddd', 'demo', 0, 1, 1, 0),
	(3, 0, 1, 'zhuang', 'safsfsfdf', 0, 1, 1, 0),
	(4, 0, 1, 'qianlin', 'sfsfsfsdfssfdfs', 0, 1, 1, 0),
	(8, 0, 3, '3', '&lt;h3 class=&quot;t&quot;&gt;&lt;a data-click=&quot;{\n			&amp;#39;F&amp;#39;:&amp;#39;778317EA&amp;#39;,\n			&amp;#39;F1&amp;#39;:&amp;#39;9D73F1E4&amp;#39;,\n			&amp;#39;F2&amp;#39;:&amp;#39;4CA6DE6B&amp;#39;,\n			&amp;#39;F3&amp;#39;:&amp;#39;54E7243F&amp;#39;,\n			&amp;#39;T&amp;#39;:&amp;#39;1442217344&amp;#39;,\n						&amp;#39;y&amp;#39;:&amp;#39;CEA49FFD&amp;#39;\n			 \n									}&quot; href=&quot;http://www.baidu.com/link?url=TYZ-lgmwYi8ywosw0rR0ZIyIExZZADshxFGPTEvJQ0QM8oWf0Z0cLUvpg_h7AbLjnwUAlQqDMvbmbpyASNqQH9JWNFK0_37Jadkw-Gx_u-y&amp;wd=&amp;eqid=91350123000033c40000000255f67d80&quot; target=&quot;_blank&quot;&gt;&lt;em&gt;PHP&lt;/em&gt; &lt;em&gt;html_entity_decode&lt;/em&gt;() 函数&lt;/a&gt;&lt;/h3&gt;&lt;p&gt;把HTML 实体转换为字符: &amp;lt;?&lt;em&gt;php&lt;/em&gt; $str = &amp;quot;&amp;lt;© W3Sçh°°¦§&amp;gt;&amp;quot;; echo &lt;em&gt;html_entity_decode&lt;/em&gt;($str); ?&amp;gt; &amp;nbsp;以上代码的 HTML 输出如下(查看源代码): &amp;lt;...&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', 0, 1, 1, 0),
	(9, 0, 1, '32让我', '&lt;p&gt;欢迎使用UEditor！发送到&lt;/p&gt;', 0, 1, 1, 0),
	(10, 0, 1, '往往往往无法投入问题', '&lt;p&gt;欢迎使用UEditor！&lt;/p&gt;', 0, 1, 1, 0),
	(11, 0, 3, '1121312312', '&lt;p&gt;sfsfsfsd&lt;br/&gt;&lt;/p&gt;', 0, 1, 1, 0),
	(12, 1, 1, '我的第一篇文章', '&lt;p&gt;这是文章内容。。。。。。。&lt;/p&gt;', 1, 1, 1, 0),
	(13, 18, 6, '测试', '&lt;p&gt;测试来了啦啦啦啦&lt;/p&gt;', 6, 1, 1, 1444873811),
	(14, 18, 21, 'hhhhhh', '&lt;p&gt;sfsfsdfsfsdf111&lt;br/&gt;&lt;/p&gt;', 2, 1, 1, 1444895928),
	(15, 18, 1, '分享thinkphp 模板截取中文字符串函数', '&lt;p&gt;thinkphp 模板如何截取中文字符串代码&lt;br/&gt;项目开发中，常常会遇到中文字符串截取问题，比如说新闻列表页面需要新闻内容简介，这就要用到字符串截取了。下面我就给大家分享一个已经封装好的字符串截取函数。&lt;br/&gt;//函数解释：&lt;br/&gt;//msubstr($str, $start=0, $length, $charset=”utf-8″, $suffix=true)&lt;br/&gt;//$str:要截取的字符串&lt;br/&gt;// $start=0：开始位置，默认从0开始&lt;br/&gt;// $length：截取长度&lt;br/&gt;// $charset=”utf-8″：字符编码，默认UTF－8&lt;br/&gt;// $suffix=true：是否在截取后的字符后面显示省略号，默认true显示，false为不显示&lt;br/&gt;//模版使用：{$vo.title|msubstr=0,5,&amp;#39;utf-8&amp;#39;,false}&lt;br/&gt;把如下代码粘贴到thinkphp核心包的/common/functions.php 的最后便可在html模型里直接使用&lt;code style=&quot;&quot; class=&quot;prettyprint linenums lang-php prettyprinted&quot;&gt;&lt;ol class=&quot;linenums list-paddingleft-2&quot;&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;kwd&quot;&gt;function&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;msubstr&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;lit&quot;&gt;0&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;utf-8&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$suffix&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;true&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;)&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;{&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;if&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;function_exists&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;mb_substr&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;)){&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;if&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$suffix&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;)&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;mb_substr&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;).&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;...&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;else&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;mb_substr&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;);&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;}&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;elseif&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;function_exists&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;#39;iconv_substr&amp;#39;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;))&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;{&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;if&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$suffix&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;)&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;iconv_substr&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;).&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;...&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;else&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;iconv_substr&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;);&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;}&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;$re&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;#39;utf-8&amp;#39;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;]&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;str&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;[x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;$re&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;#39;gb2312&amp;#39;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;]&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;/[x01-x7f]|[xb0-xf7][xa0-xfe]/&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;$re&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;#39;gbk&amp;#39;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;]&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;/[x01-x7f]|[x81-xfe][x40-xfe]/&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;$re&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;#39;big5&amp;#39;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;]&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;preg_match_all&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$re&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$charset&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;],&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$str&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$match&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;);&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;$slice&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;=&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;join&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;array_slice&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$match&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;[&lt;/span&gt;&lt;span class=&quot;lit&quot;&gt;0&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;],&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$start&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;,&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$length&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;));&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;if&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;(&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;$suffix&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;)&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$slice&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;.&lt;/span&gt;&lt;span class=&quot;str&quot;&gt;&amp;quot;…&amp;quot;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;kwd&quot;&gt;return&lt;/span&gt;&lt;span class=&quot;pln&quot;&gt;&amp;nbsp;$slice&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;;&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &lt;/span&gt;&lt;br/&gt;&lt;/p&gt;&lt;/li&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;}&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;/ol&gt;&lt;p&gt;复制代码&lt;/p&gt;&lt;/code&gt;以上就是我要分享的字符串截取函数，希望对大家有帮助，请大家继续关注脚本100&lt;code style=&quot;&quot; class=&quot;prettyprint linenums lang-php prettyprinted&quot;&gt;&lt;ol class=&quot;linenums list-paddingleft-2&quot;&gt;&lt;li&gt;&lt;p&gt;&lt;span class=&quot;pln&quot;&gt;http&lt;/span&gt;&lt;span class=&quot;pun&quot;&gt;:&lt;/span&gt;&lt;span class=&quot;com&quot;&gt;//www.jb100.net/html/list-28-1.html&lt;/span&gt;&lt;/p&gt;&lt;/li&gt;&lt;/ol&gt;&lt;p&gt;复制代码&lt;/p&gt;&lt;/code&gt;,我们会继续给大家带来惊喜。&lt;/p&gt;', 21, 1, 1, 1444985009),
	(17, 18, 3, '112', '&lt;p&gt;大大的法师法&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p style=&quot;line-height: 16px;&quot;&gt;&lt;img style=&quot;vertical-align: middle; margin-right: 2px;&quot; src=&quot;http://eshare.com/Public/Public/ueditor1_4_3_1/dialogs/attachment/fileTypeImages/icon_rar.gif&quot;/&gt;&lt;a style=&quot;font-size:12px; color:#0066cc;&quot; href=&quot;/Uploads/ueditor/php/upload/file/20151019/1445247051639624.zip&quot; title=&quot;Bootstrap的Affix与ScrollSpy用法.zip&quot;&gt;Bootstrap的Affix与ScrollSpy用法.zip&lt;/a&gt;&lt;/p&gt;&lt;p style=&quot;line-height: 16px;&quot;&gt;&lt;img style=&quot;vertical-align: middle; margin-right: 2px;&quot; src=&quot;http://eshare.com/Public/Public/ueditor1_4_3_1/dialogs/attachment/fileTypeImages/icon_jpg.gif&quot;/&gt;&lt;a style=&quot;font-size:12px; color:#0066cc;&quot; href=&quot;/Uploads/ueditor/php/upload/file/20151019/1445247073777153.png&quot; title=&quot;桌面背景图.png&quot;&gt;桌面背景图.png&lt;/a&gt;&lt;/p&gt;&lt;!--默认内容在这里 --&gt;', 1, 1, 1, 1445247080);
/*!40000 ALTER TABLE `es_article` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_category 结构
CREATE TABLE IF NOT EXISTS `es_category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父类id',
  `category_name` varchar(50) NOT NULL COMMENT '分类名称',
  `search_count` int(11) NOT NULL DEFAULT '0' COMMENT '搜索次数',
  PRIMARY KEY (`cat_id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- 正在导出表  enjoy_share.es_category 的数据：~5 rows (大约)
/*!40000 ALTER TABLE `es_category` DISABLE KEYS */;
INSERT INTO `es_category` (`cat_id`, `pid`, `category_name`, `search_count`) VALUES
	(1, 0, 'PHP', 71),
	(3, 0, 'MySQL', 23),
	(4, 3, 'session', 0),
	(6, 0, 'Linux', 14),
	(21, 0, 'Nginx', 14);
/*!40000 ALTER TABLE `es_category` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_module 结构
CREATE TABLE IF NOT EXISTS `es_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父模块id',
  `module_name` varchar(50) NOT NULL COMMENT '模块名称',
  `module_url` varchar(255) DEFAULT NULL COMMENT '模块链接',
  `module_sort` int(11) NOT NULL DEFAULT '1' COMMENT '模块顺序',
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='菜单模块表';

-- 正在导出表  enjoy_share.es_module 的数据：~5 rows (大约)
/*!40000 ALTER TABLE `es_module` DISABLE KEYS */;
INSERT INTO `es_module` (`module_id`, `pid`, `module_name`, `module_url`, `module_sort`) VALUES
	(1, 0, '网站管理', '', 1),
	(2, 1, '系统设置', '/admin/module/index', 1),
	(3, 1, '其他管理', '1', 1),
	(4, 1, '用户管理', '', 1),
	(5, 1, '文章管理', '', 1);
/*!40000 ALTER TABLE `es_module` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_node 结构
CREATE TABLE IF NOT EXISTS `es_node` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `module_id` int(11) NOT NULL COMMENT '菜单模块id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父节点id',
  `node_name` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `node_sort` int(11) DEFAULT '1' COMMENT '节点顺序',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示：1显示，2隐藏',
  `node_url` varchar(255) NOT NULL DEFAULT '''''' COMMENT '节点链接',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='节点表';

-- 正在导出表  enjoy_share.es_node 的数据：~39 rows (大约)
/*!40000 ALTER TABLE `es_node` DISABLE KEYS */;
INSERT INTO `es_node` (`node_id`, `module_id`, `pid`, `node_name`, `node_sort`, `is_show`, `node_url`) VALUES
	(1, 2, 0, '菜单管理', 1, 1, 'module/index'),
	(2, 2, 0, '节点管理', 1, 1, 'node/index'),
	(3, 2, 0, '管理员管理', 1, 1, 'admin/index'),
	(5, 2, 0, '角色管理', 1, 1, 'role/index'),
	(6, 2, 1, '修改菜单', 1, 2, 'module/update'),
	(7, 2, 1, '修改菜单操作', 1, 2, 'module/edit'),
	(8, 2, 2, '修改节点', 1, 2, 'node/update'),
	(9, 2, 2, '修改节点操作', 1, 2, 'node/edit'),
	(10, 2, 5, '修改角色', 1, 2, 'role/update'),
	(11, 2, 5, '修改角色操作', 1, 2, 'role/edit'),
	(12, 2, 5, '权限分配', 1, 2, 'role/accesslist'),
	(13, 2, 5, '权限分配操作', 1, 2, 'role/changeaccess'),
	(14, 2, 3, '修改管理员', 1, 2, 'admin/update'),
	(15, 2, 3, '修改管理员操作', 1, 2, 'admin/edit'),
	(16, 2, 1, '删除菜单', 1, 2, 'module/delete'),
	(17, 2, 2, '删除节点', 1, 2, 'node/delete'),
	(18, 2, 5, '删除角色', 1, 2, 'role/delete'),
	(19, 2, 3, '删除管理员', 1, 2, 'admin/delete'),
	(20, 2, 1, '添加菜单', 1, 2, 'module/add'),
	(21, 2, 1, '添加菜单操作', 1, 2, 'module/insert'),
	(22, 2, 2, '添加节点', 1, 2, 'node/add'),
	(23, 2, 2, '添加节点操作', 1, 2, 'node/insert'),
	(24, 2, 5, '添加角色', 1, 2, 'role/add'),
	(25, 2, 5, '添加角色操作', 1, 2, 'role/insert'),
	(26, 2, 3, '添加管理员', 1, 2, 'admin/add'),
	(27, 2, 3, '添加管理员操作', 1, 2, 'admin/insert'),
	(28, 3, 0, '测试', 1, 1, 'test/test'),
	(29, 4, 0, '用户管理', 1, 1, 'user/index'),
	(30, 4, 29, '添加用户', 1, 2, 'user/add'),
	(31, 4, 29, '修改用户', 1, 2, 'user/update'),
	(32, 5, 0, '分类管理', 1, 1, 'category/index'),
	(33, 5, 32, '添加分类', 1, 2, 'category/add'),
	(34, 5, 32, '修改分类', 1, 2, 'category/update'),
	(38, 5, 0, '文章管理', 1, 1, 'article/index'),
	(39, 5, 38, '添加文章', 1, 2, 'article/add'),
	(40, 5, 38, '修改文章', 1, 2, 'article/update'),
	(41, 4, 29, '删除用户', 1, 2, 'user/delete'),
	(42, 5, 32, '删除分类', 1, 2, 'category/delete'),
	(44, 5, 38, '删除文章', 1, 2, 'article/delete');
/*!40000 ALTER TABLE `es_node` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_oauth 结构
CREATE TABLE IF NOT EXISTS `es_oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `server_id` int(2) NOT NULL DEFAULT '0' COMMENT '第三方平台标识：1QQ，2新浪微博,3微信，4百度',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `target_user_id` varchar(128) NOT NULL COMMENT '第三方用户标识id',
  `target_user_name` varchar(128) DEFAULT NULL COMMENT '第三那方用户名称',
  `access_token` varchar(128) DEFAULT NULL COMMENT 'access_token',
  `expires_in` varchar(128) DEFAULT NULL COMMENT 'access_token 生命周期 单位秒，如7776000（90天）',
  `token_update` int(11) DEFAULT NULL COMMENT 'access_token修改时间',
  `bind_time` int(11) NOT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `weibo_user_id` (`target_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='第三方登录绑定';

-- 正在导出表  enjoy_share.es_oauth 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `es_oauth` DISABLE KEYS */;
INSERT INTO `es_oauth` (`id`, `server_id`, `user_id`, `target_user_id`, `target_user_name`, `access_token`, `expires_in`, `token_update`, `bind_time`) VALUES
	(1, 0, 18, '0', NULL, NULL, NULL, NULL, 1445413030);
/*!40000 ALTER TABLE `es_oauth` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_reply 结构
CREATE TABLE IF NOT EXISTS `es_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `article_id` int(11) NOT NULL COMMENT '文章id',
  `content` text NOT NULL COMMENT '评论内容',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`reply_id`),
  KEY `user_id` (`user_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='文章评论表';

-- 正在导出表  enjoy_share.es_reply 的数据：~10 rows (大约)
/*!40000 ALTER TABLE `es_reply` DISABLE KEYS */;
INSERT INTO `es_reply` (`reply_id`, `user_id`, `article_id`, `content`, `add_time`) VALUES
	(1, 1, 12, '&lt;p&gt;请输入内容...sfs &lt;br/&gt;&lt;/p&gt;', 1442799956),
	(2, 1, 12, '&lt;p&gt;请fsfsf&lt;br/&gt;&lt;/p&gt;', 1442799966),
	(3, 18, 12, '&lt;p&gt;好&lt;/p&gt;', 1444872166),
	(4, 18, 12, '&lt;p&gt;挺好&lt;/p&gt;', 1444873140),
	(5, 18, 12, '&lt;p&gt;哈哈哈&lt;/p&gt;', 1444873245),
	(6, 18, 12, '&lt;p&gt;哈哈哈&lt;/p&gt;', 1444873286),
	(7, 18, 12, '&lt;p&gt;请输入内容...&lt;/p&gt;', 1444873296),
	(8, 18, 12, '&lt;p&gt;请输入内容...&lt;/p&gt;', 1444873330),
	(9, 18, 12, '&lt;p&gt;请输入内容...&lt;/p&gt;', 1444873337),
	(10, 18, 12, '&lt;p&gt;1111&lt;br/&gt;&lt;/p&gt;', 1444873343);
/*!40000 ALTER TABLE `es_reply` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_role 结构
CREATE TABLE IF NOT EXISTS `es_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `role_name` varchar(50) NOT NULL COMMENT '角色名称',
  `access_list` text COMMENT '权限列表',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- 正在导出表  enjoy_share.es_role 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `es_role` DISABLE KEYS */;
INSERT INTO `es_role` (`role_id`, `role_name`, `access_list`) VALUES
	(1, '超级管理员', '1,2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,41,32,33,34,35,36,37,38,39,40,42,43,44'),
	(2, '普通管理员', '1,2,3,5,6,7,8,9,10,11,14,15,20,21,22,23,24,25,26,27');
/*!40000 ALTER TABLE `es_role` ENABLE KEYS */;


-- 导出  表 enjoy_share.es_user 结构
CREATE TABLE IF NOT EXISTS `es_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `real_name` varchar(50) NOT NULL DEFAULT '''''' COMMENT '真实姓名',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `gender` smallint(1) NOT NULL DEFAULT '0' COMMENT '性别：0保密，1男，2女',
  `portrait_subname` varchar(100) DEFAULT NULL COMMENT '头像存放目录名',
  `portrait_ext` varchar(50) DEFAULT NULL COMMENT '头像图片后缀',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态:1正常,2禁用',
  `add_time` int(11) NOT NULL COMMENT '注册时间',
  `last_login_time` int(11) NOT NULL COMMENT '最后一次登录时间',
  `last_login_ip` int(11) NOT NULL COMMENT '最后一次登录ip',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- 正在导出表  enjoy_share.es_user 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `es_user` DISABLE KEYS */;
INSERT INTO `es_user` (`user_id`, `user_name`, `real_name`, `password`, `gender`, `portrait_subname`, `portrait_ext`, `status`, `add_time`, `last_login_time`, `last_login_ip`) VALUES
	(1, 'demo', '演示账号', '0a9976be540736ae3e999a88ce40f4f0', 1, NULL, NULL, 1, 0, 0, 0),
	(18, 'zql_0539@163.com', '庄', '0a9976be540736ae3e999a88ce40f4f0', 0, '15101411040110081350', '.png', 1, 0, 0, 0);
/*!40000 ALTER TABLE `es_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
