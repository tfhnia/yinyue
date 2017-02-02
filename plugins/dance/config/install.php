<?php

if (!defined('CSCMSPATH')) exit('No permission resources');

return array(
		  //�����б�
         "CREATE TABLE IF NOT EXISTS `{prefix}dance` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(128) default '' COMMENT '����',
            `color` varchar(10) default '' COMMENT '������ɫ',
            `tags` varchar(64) default '' COMMENT 'TAGS��ǩ',
            `pic` varchar(255) default '' COMMENT '����ͼƬ',
            `cid` mediumint(5) default '0' COMMENT '����ID',
            `tid` mediumint(5) default '0' COMMENT 'ר��ID',
            `fid` mediumint(5) default '0' COMMENT '��������ID',
            `reco` tinyint(1) default '0' COMMENT '�Ƽ��Ǽ�',
            `yid` tinyint(1) default '0' COMMENT '�Ƿ�����',
            `hid` tinyint(1) default '0' COMMENT '�Ƿ����վ',
            `singerid` int(10) unsigned default '0' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `shits` int(10) unsigned default '0' COMMENT '�ղ�����',
            `xhits` int(10) unsigned default '0' COMMENT '��������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `dhits` int(10) unsigned default '0' COMMENT '������',
            `chits` int(10) unsigned default '0' COMMENT '������',
            `vip` tinyint(3) default '0' COMMENT '��Ա��Ȩ��',
            `level` tinyint(3) default '0' COMMENT '��Ա����Ȩ��',
            `cion` mediumint(5) default '0' COMMENT '��Ҫ���',
            `bq` varchar(64) default '' COMMENT '����',
            `zq` varchar(64) default '' COMMENT '����',
            `zc` varchar(64) default '' COMMENT '����',
            `hy` varchar(64) default '' COMMENT '����',
            `dx` varchar(10) default '' COMMENT '��С',
            `yz` varchar(10) default '' COMMENT '����',
            `sc` varchar(10) default '' COMMENT 'ʱ��',
            `lrc` text COMMENT 'LRC���',
            `text` text COMMENT '����',
            `purl` varchar(255) default '' COMMENT '������ַ',
            `durl` varchar(255) default '' COMMENT '���ص�ַ',
            `wpurl` varchar(255) default '' COMMENT '�������ص�ַ',
            `wppass` varchar(255) default '' COMMENT '������������',
            `skins` varchar(64) default 'play.html' COMMENT 'Ĭ��ģ��',
            `playtime` int(10) unsigned default '0' COMMENT '����ʱ��',
            `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
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
             KEY `dance_hid_addtime` (`hid`,`addtime`),
             KEY `dance_cid_yid_hid_id` (`yid`,`hid`,`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='������';",

         //��������
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_list` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '����',
            `bname` varchar(30) default '' COMMENT '����',
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���������';",

         //��������
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_server` (
            `id` mediumint(5) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '����',
            `purl` varchar(255) default '' COMMENT '������ַ',
            `durl` varchar(255) default '' COMMENT '���ص�ַ',
             PRIMARY KEY  (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='������������';",

         //�ղؼ�¼
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_fav` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `sid` tinyint(1) default '1' COMMENT '����1������2ר��',
            `name` varchar(64) default '' COMMENT '��������',
            `cid` mediumint(5) unsigned default '0' COMMENT '��������ID',
            `did` int(10) unsigned default '0' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `fav_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='�����ղؼ�¼';",

         //���ؼ�¼
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_down` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '��������',
            `cid` mediumint(5) unsigned default '0' COMMENT '��������ID',
            `did` int(10) unsigned default '0' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `cion` int(10) default '0' COMMENT '�۳����',
            `ip` varchar(20) default '' COMMENT '����IP',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `down_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='�������ؼ�¼';",

         //������¼
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_play` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '��������',
            `cid` mediumint(5) unsigned default '0' COMMENT '��������ID',
            `did` int(10) unsigned default '0' COMMENT '����ID',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
             PRIMARY KEY  (`id`),
             KEY `uid` (`uid`),
             KEY `cid` (`cid`),
             KEY `did` (`did`),
             KEY `play_uid_addtime` (`uid`,`addtime`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����������¼';",

         //����ר��
         "CREATE TABLE IF NOT EXISTS `{prefix}dance_topic` (
            `id` int(10) unsigned NOT NULL auto_increment,
            `name` varchar(64) default '' COMMENT '����',
            `color` varchar(10) default '' COMMENT '������ɫ',
            `pic` varchar(255) default '' COMMENT 'ͼƬ',
            `tags` varchar(64) default '' COMMENT 'TAGS��ǩ',
            `singerid` int(10) default '0' COMMENT '����ID',
            `tid` tinyint(1) default '0' COMMENT '�Ƿ��Ƽ�',
            `cid` mediumint(5) unsigned default '0' COMMENT '����ID',
            `yid` tinyint(1) default '1' COMMENT '�Ƿ����',
            `uid` int(10) unsigned default '0' COMMENT '��ԱID',
            `hits` int(10) unsigned default '0' COMMENT '������',
            `yhits` int(10) unsigned default '0' COMMENT '������',
            `zhits` int(10) unsigned default '0' COMMENT '������',
            `rhits` int(10) unsigned default '0' COMMENT '������',
            `shits` int(10) unsigned default '0' COMMENT '�ղ�����',
            `neir` text COMMENT '����',
            `yuyan` varchar(10) default '' COMMENT '����',
            `diqu` varchar(10) default '' COMMENT '����',
            `fxgs` varchar(64) default '' COMMENT '���й�˾',
            `year` varchar(10) default '' COMMENT '����ʱ��',
            `skins` varchar(64) default 'topic-show.html' COMMENT 'Ĭ��ģ��',
            `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
            `title` varchar(64) default '' COMMENT 'SEO����',
            `keywords` varchar(150) default '' COMMENT 'SEO�ؼ���',
            `description` varchar(200) default '' COMMENT 'SEO����',
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
          ) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����ר����';",

		  //Ĭ������
         "INSERT INTO `{prefix}dance_list` (`id`, `name`, `bname`, `fid`, `xid`, `yid`, `skins`, `title`, `keywords`, `description`) VALUES
            (1, '�������', 'hygq', 0, 1, 0, 'list.html', '', '', ''),
            (2, '��̨����', 'gtgq', 0, 2, 0, 'list.html', '', '', ''),
            (3, '�պ�����', 'rhgq', 0, 3, 0, 'list.html', '', '', ''),
            (4, 'ŷ������', 'omgq', 0, 4, 0, 'list.html', '', '', ''),
            (5, '���и���', 'lxgq', 1, 1, 0, 'list.html', '', '', ''),
            (6, '�˸и���', 'sggq', 1, 2, 0, 'list.html', '', '', '');"
);