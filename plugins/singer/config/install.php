<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //�����б�
         "CREATE TABLE IF NOT EXISTS `{prefix}singer` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '��������',
            `bname` varchar(64) default '' COMMENT 'Ӣ�ı���',
            `color` varchar(10) default '' COMMENT '������ɫ',
            `tags` varchar(64) default '' COMMENT '���ֱ�ǩ',
            `pic` varchar(255) default '' COMMENT 'ή��ͼ',
            `cid` mediumint(5) default '0' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `reco` tinyint(1) default '0' COMMENT '�Ƽ��Ǽ�',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ�����',
            `hid` tinyint(1) default '0' COMMENT '�Ƿ����վ',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `sex` varchar(5) default '��' COMMENT '�����Ա�',
            `nichen` varchar(30) default '����' COMMENT '���ֱ���',
            `nat` varchar(30) default '����' COMMENT '���ֹ���',
            `yuyan` varchar(10) default '����' COMMENT '��������',
            `city` varchar(64) default '����' COMMENT '���ֳ�����',
            `sr` varchar(15) default '����' COMMENT '��������',
            `xingzuo` varchar(10) default '����' COMMENT '��������',
            `height` varchar(10) default '����' COMMENT '�������',
            `weight` varchar(10) default '����' COMMENT '��������',
            `content` text COMMENT '���ֽ���',
            `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `hid` (`hid`),
             KEY `yid` (`yid`),
             KEY `reco` (`reco`),
             KEY `hits` (`hits`),
             KEY `yhits` (`yhits`),
             KEY `zhits` (`zhits`),
             KEY `rhits` (`rhits`),
             KEY `singer_hid_addtime` (`hid`,`addtime`),
             KEY `singer_cid_yid_hid_id` (`yid`,`hid`,`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���ֱ�';",

         //���ַ���
         "CREATE TABLE IF NOT EXISTS `{prefix}singer_list` (
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���ַ����';",

		  //Ĭ�Ϸ�������
         "INSERT INTO `{prefix}singer_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '����', 'hy', 0, 1, 0, 'list.html', '', '', ''),
            (2, 'ŷ��', 'om', 0, 2, 0, 'list.html', '', '', ''),
            (3, '�պ�', 'rh', 0, 3, 0, 'list.html', '', '', ''),
            (4, '�����и���', 'hynan', 1, 1, 0, 'list.html', '', '', ''),
            (5, '����Ů����', 'hynv', 1, 2, 0, 'list.html', '', '', ''),
            (6, '�����ֶ����', 'hyzh', 1, 3, 0, 'list.html', '', '', ''),
            (7, 'ŷ���и���', 'omnan', 2, 1, 0, 'list.html', '', '', ''),
            (8, 'ŷ��Ů����', 'omnv', 2, 2, 0, 'list.html', '', '', ''),
            (9, 'ŷ���ֶ����', 'omzh', 2, 3, 0, 'list.html', '', '', ''),
            (10, '�պ��и���', 'rhnan', 3, 1, 0, 'list.html', '', '', ''),
            (11, '�պ�Ů����', 'rhnv', 3, 2, 0, 'list.html', '', '', ''),
            (12, '�պ��ֶ����', 'rhzh', 3, 2, 0, 'list.html', '', '', '');"
);
