<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //歌曲列表
         "CREATE TABLE IF NOT EXISTS `{prefix}dance` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '名称',
            `color` varchar(10) default '' COMMENT '名称颜色',
            `tags` varchar(64) default '' COMMENT 'TAGS标签',
            `pic` varchar(255) default '' COMMENT '歌曲图片',
            `cid` mediumint(5) default '0' COMMENT '分类ID',
            `tid` mediumint(5) default '0' COMMENT '专辑ID',
            `fid` mediumint(5) default '0' COMMENT '服务器组ID',
            `reco` tinyint(1) default '0' COMMENT '推荐星级',
            `yid` tinyint(1) default '0' COMMENT '是否隐藏',
            `hid` tinyint(1) default '0' COMMENT '是否回收站',
            `singerid` int(10) unsigned default '0' COMMENT '歌手ID',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `hits` int(10) unsigned default '0' COMMENT '总人气',
            `shits` int(10) unsigned default '0' COMMENT '收藏人气',
            `xhits` int(10) unsigned default '0' COMMENT '下载人气',
            `yhits` int(10) unsigned default '0' COMMENT '月人气',
            `zhits` int(10) unsigned default '0' COMMENT '周人气',
            `rhits` int(10) unsigned default '0' COMMENT '日人气',
            `dhits` int(10) unsigned default '0' COMMENT '顶人气',
            `chits` int(10) unsigned default '0' COMMENT '踩人气',
            `vip` tinyint(3) default '0' COMMENT '会员组权限',
            `level` tinyint(3) default '0' COMMENT '会员级别权限',
            `cion` mediumint(5) default '0' COMMENT '需要金币',
            `bq` varchar(64) default '' COMMENT '编曲',
            `zq` varchar(64) default '' COMMENT '作曲',
            `zc` varchar(64) default '' COMMENT '作词',
            `hy` varchar(64) default '' COMMENT '混音',
            `dx` varchar(10) default '' COMMENT '大小',
            `yz` varchar(10) default '' COMMENT '音质',
            `sc` varchar(10) default '' COMMENT '时长',
            `lrc` text COMMENT 'LRC歌词',
            `text` text COMMENT '介绍',
            `purl` varchar(255) default '' COMMENT '试听地址',
            `durl` varchar(255) default '' COMMENT '下载地址',
            `wpurl` varchar(255) default '' COMMENT '网盘下载地址',
            `wppass` varchar(255) default '' COMMENT '网盘下载密码',
            `skins` varchar(64) default 'play.html' COMMENT '默认模板',
            `playtime` int(10) unsigned default '0' COMMENT '播放时间',
            `addtime` int(10) unsigned default '0' COMMENT '增加时间',
            `title` varchar(64) default '' COMMENT 'SEO标题',
            `keywords` varchar(150) default '' COMMENT 'SEO关键词',
            `description` varchar(200) default '' COMMENT 'SEO介绍',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `tid` (`tid`),
             KEY `hid` (`hid`),
             KEY `yid` (`yid`),
             KEY `reco` (`reco`),
             KEY `hits` (`hits`),
             KEY `yhits` (`yhits`),
             KEY `zhits` (`zhits`),
             KEY `rhits` (`rhits`),
             KEY `dance_hid_addtime` (`hid`,`addtime`),
             KEY `dance_cid_yid_hid_id` (`yid`,`hid`,`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲表';",

         //歌曲分类
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_list` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '名称',
            `bname` varchar(30) default '' COMMENT '别名',
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲分类表';",

         //服务器组
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_server` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '名称',
            `purl` varchar(255) default '' COMMENT '试听地址',
            `durl` varchar(255) default '' COMMENT '下载地址',
             PRIMARY KEY  (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲服务器组';",

         //收藏记录
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_fav` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `sid` tinyint(1) default '1' COMMENT '类型1歌曲，2专辑',
            `name` varchar(64) default '' COMMENT '歌曲名称',
            `cid` mediumint(5) unsigned default '0' COMMENT '歌曲分类ID',
            `did` int(10) unsigned default '0' COMMENT '歌曲ID',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `addtime` int(10) unsigned default '0' COMMENT '时间',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `fav_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲收藏记录';",

         //下载记录
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_down` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '歌曲名称',
            `cid` mediumint(5) unsigned default '0' COMMENT '歌曲分类ID',
            `did` int(10) unsigned default '0' COMMENT '歌曲ID',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `cion` int(10) default '0' COMMENT '扣除金币',
            `ip` varchar(20) default '' COMMENT '下载IP',
            `addtime` int(10) unsigned default '0' COMMENT '时间',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `down_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲下载记录';",

         //视听记录
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_play` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '歌曲名称',
            `cid` mediumint(5) unsigned default '0' COMMENT '歌曲分类ID',
            `did` int(10) unsigned default '0' COMMENT '歌曲ID',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `addtime` int(10) unsigned default '0' COMMENT '时间',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `play_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲视听记录';",

         //歌曲专辑
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_topic` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '名称',
            `color` varchar(10) default '' COMMENT '名称颜色',
            `pic` varchar(255) default '' COMMENT '图片',
            `tags` varchar(64) default '' COMMENT 'TAGS标签',
            `singerid` int(10) default '0' COMMENT '歌手ID',
            `tid` tinyint(1) default '0' COMMENT '是否推荐',
            `cid` mediumint(5) unsigned default '0' COMMENT '分类ID',
            `yid` tinyint(1) default '1' COMMENT '是否审核',
            `uid` int(10) unsigned default '0' COMMENT '会员ID',
            `hits` int(10) unsigned default '0' COMMENT '总人气',
            `yhits` int(10) unsigned default '0' COMMENT '月人气',
            `zhits` int(10) unsigned default '0' COMMENT '周人气',
            `rhits` int(10) unsigned default '0' COMMENT '日人气',
            `shits` int(10) unsigned default '0' COMMENT '收藏人气',
            `neir` text COMMENT '介绍',
            `yuyan` varchar(10) default '' COMMENT '语言',
            `diqu` varchar(10) default '' COMMENT '地区',
            `fxgs` varchar(64) default '' COMMENT '发行公司',
            `year` varchar(10) default '' COMMENT '发行时间',
            `skins` varchar(64) default 'topic-show.html' COMMENT '默认模板',
            `addtime` int(10) unsigned default '0' COMMENT '时间',
            `title` varchar(64) default '' COMMENT 'SEO标题',
            `keywords` varchar(150) default '' COMMENT 'SEO关键词',
            `description` varchar(200) default '' COMMENT 'SEO介绍',
             PRIMARY KEY  (`id`),
             KEY `singerid` (`singerid`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `tid` (`tid`),
             KEY `hits` (`hits`),
             KEY `yhits` (`yhits`),
             KEY `zhits` (`zhits`),
             KEY `rhits` (`rhits`),
             KEY `shits` (`shits`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='歌曲专辑表';",

		  //默认数据
         "INSERT INTO `{prefix}dance_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '华语歌曲', 'hygq', 0, 1, 0, 'list.html', '', '', ''),
            (2, '港台歌曲', 'gtgq', 0, 2, 0, 'list.html', '', '', ''),
            (3, '日韩歌曲', 'rhgq', 0, 3, 0, 'list.html', '', '', ''),
            (4, '欧美歌曲', 'omgq', 0, 4, 0, 'list.html', '', '', ''),
            (5, '流行歌曲', 'lxgq', 1, 1, 0, 'list.html', '', '', ''),
            (6, '伤感歌曲', 'sggq', 1, 2, 0, 'list.html', '', '', '');"
);