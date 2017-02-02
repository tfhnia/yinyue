<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //�����б�
         "CREATE TABLE IF NOT EXISTS `{prefix}news` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '����',
            `bname` varchar(64) default '' COMMENT 'Ӣ�ı���',
            `color` varchar(10) default '' COMMENT '������ɫ',
            `tags` varchar(255) default '' COMMENT 'TAGS��ǩ',
            `pic` varchar(255) default '' COMMENT 'ή��ͼ',
            `pic2` varchar(255) default '' COMMENT '�õ�ͼƬ',
            `cid` mediumint(5) default '0' COMMENT '����ID',
            `tid` mediumint(5) default '0' COMMENT 'ר��ID',
            `reco` tinyint(1) default '0' COMMENT '�Ƽ��Ǽ�',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ�����',
            `hid` tinyint(1) default '0' COMMENT '�Ƿ����վ',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `dhits` int(10) unsigned default '0' COMMENT '������',
            `chits` int(10) unsigned default '0' COMMENT '������',
            `cion` mediumint(5) default '0' COMMENT '�Ķ���Ҫ���',
            `vip` mediumint(5) default '0' COMMENT '�ɹۿ���',
            `level` mediumint(5) default '0' COMMENT '�ɹۿ��ȼ�',
            `info` varchar(128) default '' COMMENT '����',
            `content` text COMMENT '��������',
            `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
            `skins` varchar(64) default 'show.html' COMMENT 'Ĭ��ģ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
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
             KEY `news_hid_addtime` (`hid`,`addtime`),
             KEY `news_cid_yid_hid_id` (`yid`,`hid`,`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���ű�';",

         //���ŷ���
         "CREATE TABLE IF NOT EXISTS `{prefix}news_list` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '����',
            `bname` varchar(30) default '' COMMENT 'Ӣ�ı���',
            `fid` tinyint(1) default '0' COMMENT '�ϼ�ID',
            `xid` tinyint(1) default '0' COMMENT '����ID',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ���ʾ',
            `skins` varchar(64) default 'list.html' COMMENT 'Ĭ��ģ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
             PRIMARY KEY  (`id`),
             KEY `xid` (`xid`),
             KEY `yid` (`yid`),
             KEY `fid` (`fid`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���ŷ����';",

         //�շ��Ķ���¼
         "CREATE TABLE IF NOT EXISTS `{prefix}news_look` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '��������',
            `cid` mediumint(5) unsigned default '0' COMMENT '���ŷ���ID',
            `did` varchar(128) default '' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `cion` int(10) default '0' COMMENT '�۳����',
            `ip` varchar(20) default '' COMMENT '��ԱIP',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='�����շ��Ķ���¼';",


         //����ר��
         "CREATE TABLE IF NOT EXISTS `{prefix}news_topic` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '����',
            `bname` varchar(20) default '' COMMENT '����',
            `pic` varchar(255) default '' COMMENT 'ͼƬ',
            `toppic` varchar(255) default '' COMMENT '����ͼƬ',
            `tid` tinyint(1) default '0' COMMENT '�Ƿ��Ƽ�',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ����',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `neir` text COMMENT '����',
            `skins` varchar(64) default 'topic.html' COMMENT 'Ĭ��ģ��',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
             PRIMARY KEY  (`id`),
             KEY `tid` (`tid`),
             KEY `yid` (`yid`),
             KEY `hits` (`hits`),
             KEY `yhits` (`yhits`),
             KEY `zhits` (`zhits`),
             KEY `rhits` (`rhits`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����ר���';",

		  //Ĭ�Ϸ�������
         "INSERT INTO `{prefix}news_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '����', 'gl', 0, 1, 0, 'list.html', '', '', ''),
            (2, 'ʱ��', 'sz', 0, 2, 0, 'list.html', '', '', ''),
            (3, '���', 'sh', 0, 3, 0, 'list.html', '', '', ''),
            (4, '����', 'gj', 0, 4, 0, 'list.html', '', '', ''),
            (5, '����', 'yl', 0, 5, 0, 'list.html', '', '', ''),
            (6, '����', 'js', 0, 6, 0, 'list.html', '', '', ''),
            (7, '����', 'jk', 0, 7, 0, 'list.html', '', '', ''),
            (8, 'ʱ��', 'ss', 0, 8, 0, 'list.html', '', '', ''),
            (9, '�Ƽ�', 'kj', 0, 9, 0, 'list.html', '', '', ''),
            (10, '����', 'ty', 0, 10, 0, 'list.html', '', '', ''),
            (11, '����', 'jy', 0, 11, 0, 'list.html', '', '', '');"
);
