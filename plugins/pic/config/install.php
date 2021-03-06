<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //图片列表
         "CREATE TABLE IF NOT EXISTS `{prefix}pic` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `pic` varchar(255) default '' COMMENT '图片地址',
            `cid` mediumint(5) default '0' COMMENT '分类ID',
            `sid` int(10) unsigned default '0' COMMENT '相册ID',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `yid` tinyint(1) default '0' COMMENT '是否隐藏',
            `hid` tinyint(1) default '0' COMMENT '是否回收站',
            `content` text COMMENT '图片介绍',
            `addtime` int(10) unsigned default '0' COMMENT '增加时间',
             PRIMARY KEY  (`id`),
             KEY `cid` (`cid`),
             KEY `sid` (`sid`),
             KEY `uid` (`uid`),
             KEY `yid` (`yid`),
             KEY `hid` (`hid`),
             KEY `pic_hid_addtime` (`hid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='图片表';",

         //图片相册
         "CREATE TABLE IF NOT EXISTS `{prefix}pic_type` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '名称',
            `bname` varchar(64) default '' COMMENT '英文别名',
            `tags` varchar(255) default '' COMMENT 'TAGS标签',
            `pic` varchar(255) default '' COMMENT '相册封面',
            `cid` mediumint(5) default '0' COMMENT '分类ID',
            `reco` tinyint(1) default '0' COMMENT '推荐星级',
            `yid` tinyint(1) default '0' COMMENT '是否隐藏',
            `hid` tinyint(1) default '0' COMMENT '是否回收站',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `singerid` int(10) unsigned default '0' COMMENT '歌手ID',
            `hits` int(10) unsigned default '0' COMMENT '总人气',
            `yhits` int(10) unsigned default '0' COMMENT '月人气',
            `zhits` int(10) unsigned default '0' COMMENT '周人气',
            `rhits` int(10) unsigned default '0' COMMENT '日人气',
            `dhits` int(10) unsigned default '0' COMMENT '顶人气',
            `chits` int(10) unsigned default '0' COMMENT '踩人气',
            `addtime` int(10) unsigned default '0' COMMENT '增加时间',
            `skins` varchar(64) default 'show.html' COMMENT '默认模板',
            `title` varchar(64) default '' COMMENT 'SEO标题',
            `keywords` varchar(150) default '' COMMENT 'SEO关键词',
            `description` varchar(200) default '' COMMENT 'SEO介绍',
             PRIMARY KEY  (`id`),
             KEY `cid` (`cid`),
             KEY `singerid` (`singerid`),
             KEY `reco` (`reco`),
             KEY `uid` (`uid`),
             KEY `yid` (`yid`),
             KEY `hid` (`hid`),
             KEY `hits` (`hits`),
             KEY `yhits` (`yhits`),
             KEY `zhits` (`zhits`),
             KEY `rhits` (`rhits`),
             KEY `pict_hid_addtime` (`hid`,`addtime`),
             KEY `pict_cid_yid_hid_id` (`yid`,`hid`,`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='图片相册表';",

         //图片分类
         "CREATE TABLE IF NOT EXISTS `{prefix}pic_list` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '名称',
            `bname` varchar(30) default '' COMMENT '英文别名',
            `fid` tinyint(1) default '0' COMMENT '上级ID',
            `xid` tinyint(1) default '0' COMMENT '排序ID',
            `yid` tinyint(1) default '0' COMMENT '是否显示',
            `skins` varchar(64) default 'list.html' COMMENT '默认模板',
            `title` varchar(64) default '' COMMENT 'SEO标题',
            `keywords` varchar(150) default '' COMMENT 'SEO关键词',
            `description` varchar(200) default '' COMMENT 'SEO介绍',
             PRIMARY KEY  (`id`),
             KEY `xid` (`xid`),
             KEY `yid` (`yid`),
             KEY `fid` (`fid`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='图片分类表';",

		  //默认分类数据
         "INSERT INTO `{prefix}pic_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '明星', 'mx', 0, 1, 0, 'list.html', '', '', ''),
            (2, '演唱会', 'ych', 0, 2, 0, 'list.html', '', '', ''),
            (3, '活动', 'ych', 0, 3, 0, 'list.html', '', '', ''),
            (4, '生活', 'sh', 0, 4, 0, 'list.html', '', '', '');"
);
