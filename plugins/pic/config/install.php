<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //ͼƬ�б�
         "CREATE TABLE IF NOT EXISTS `{prefix}pic` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `pic` varchar(255) default '' COMMENT 'ͼƬ��ַ',
            `cid` mediumint(5) default '0' COMMENT '����ID',
            `sid` int(10) unsigned default '0' COMMENT '���ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ�����',
            `hid` tinyint(1) default '0' COMMENT '�Ƿ����վ',
            `content` text COMMENT 'ͼƬ����',
            `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
             PRIMARY KEY  (`id`),
             KEY `cid` (`cid`),
             KEY `sid` (`sid`),
             KEY `uid` (`uid`),
             KEY `yid` (`yid`),
             KEY `hid` (`hid`),
             KEY `pic_hid_addtime` (`hid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='ͼƬ��';",

         //ͼƬ���
         "CREATE TABLE IF NOT EXISTS `{prefix}pic_type` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '����',
            `bname` varchar(64) default '' COMMENT 'Ӣ�ı���',
            `tags` varchar(255) default '' COMMENT 'TAGS��ǩ',
            `pic` varchar(255) default '' COMMENT '������',
            `cid` mediumint(5) default '0' COMMENT '����ID',
            `reco` tinyint(1) default '0' COMMENT '�Ƽ��Ǽ�',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ�����',
            `hid` tinyint(1) default '0' COMMENT '�Ƿ����վ',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `singerid` int(10) unsigned default '0' COMMENT '����ID',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `dhits` int(10) unsigned default '0' COMMENT '������',
            `chits` int(10) unsigned default '0' COMMENT '������',
            `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
            `skins` varchar(64) default 'show.html' COMMENT 'Ĭ��ģ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='ͼƬ����';",

         //ͼƬ����
         "CREATE TABLE IF NOT EXISTS `{prefix}pic_list` (
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='ͼƬ�����';",

		  //Ĭ�Ϸ�������
         "INSERT INTO `{prefix}pic_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '����', 'mx', 0, 1, 0, 'list.html', '', '', ''),
            (2, '�ݳ���', 'ych', 0, 2, 0, 'list.html', '', '', ''),
            (3, '�', 'ych', 0, 3, 0, 'list.html', '', '', ''),
            (4, '����', 'sh', 0, 4, 0, 'list.html', '', '', '');"
);
