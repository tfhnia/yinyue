--
-- ���ݿ�: 'cscmcs_v4.x'
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--cscms_admin<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}admin` (
  `id` smallint(5) NOT NULL auto_increment,
  `adminname` varchar(64) default '' COMMENT '�˺�',
  `adminpass` varchar(64) default '' COMMENT '����',
  `admincode` varchar(6) default '' COMMENT '��Կ',
  `logip` varchar(128) default '' COMMENT '����¼IP',
  `lognums` int(10) default '0' COMMENT '��¼����',
  `logtime` int(10) default '0' COMMENT '����¼ʱ��',
  `card` varchar(255) default '' COMMENT '���',
  `sid` smallint(3) unsigned default '0' COMMENT '��ɫid',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����Ա��';

--cscms_adminzu<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}adminzu` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(64) default '' COMMENT '��ɫ����',
  `sys` text COMMENT 'Ĭ��Ȩ��',
  `app` text COMMENT '���Ȩ��',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����Ա��ɫ��';


--cscms_admin_log<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}admin_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned default '0' COMMENT '�û�ID',
  `loginip` varchar(50) default '' COMMENT '��¼IP',
  `logintime` int(10) unsigned default '0' COMMENT '��¼ʱ��',
  `useragent` varchar(255) default '' COMMENT '�ͻ�����Ϣ',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='����Ա��¼��';


--cscms_ads<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}ads` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) default '' COMMENT '��ǩ��ʾ',
  `js` varchar(100) default '' COMMENT 'JS·��',
  `html` text COMMENT '��ǩ����',
  `neir` varchar(200) default '' COMMENT '��ǩ����',
  `addtime` int(11) default '0' COMMENT '����ʱ��',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�Զ���JS��';


--cscms_blog<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}blog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `hits` int(10) unsigned default '0' COMMENT '�������',
  `phits` int(10) unsigned default '0' COMMENT '���۴���',
  `neir` text COMMENT '˵˵����',
  `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `pjits` (`phits`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա˵˵��';


--cscms_caiji<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}caiji` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(50) default '',
  `url` varchar(250) default '',
  `code` varchar(10) default '',
  `dir` varchar(64) default '',
  `zid` tinyint(1) default '0',
  `fid` smallint(5) default '0',
  `cfid` tinyint(1) default '0',
  `picid` tinyint(1) default '0',
  `dxid` tinyint(1) default '0',
  `rkid` tinyint(1) default '0',
  `htmlid` tinyint(1) default '0',
  `cjurl` text,
  `ksid` int(10) default '0',
  `jsid` int(10) default '0',
  `listks` text,
  `listjs` text,
  `picmode` tinyint(1) default '0',
  `picks` text,
  `picjs` text,
  `linkks` text,
  `linkjs` text,
  `nameks` text,
  `namejs` text,
  `strth` text,
  `addtime` int(10) unsigned default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='�ɼ������';



--cscms_cjannex<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}cjannex` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(128) default '',
  `cid` int(10) unsigned default '0',
  `fid` tinyint(1) default '0',
  `htmlid` tinyint(1) default '0',
  `zd` varchar(128) default '',
  `ks` text,
  `js` text,
  `fname` varchar(64) default '',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�ɼ����������';


--cscms_cjdata<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}cjdata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dir` varchar(64) default '',
  `name` varchar(255) default '',
  `pic` varchar(255) default '',
  `zid` tinyint(1) default '0',
  `cfid` tinyint(1) default '0',
  `zdy` text,
  `addtime` int(10) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `zid` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�ɼ����ݱ�';



--cscms_cjlist<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}cjlist` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) default '',
  `dir` varchar(64) default '',
  `url` varchar(255) default '',
  `names` varchar(255) default '',
  `zid` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `zid` (`zid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�ɼ�վ���';


--cscms_dt<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}dt` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `dir` varchar(64) default '' COMMENT '����ʾ',
  `yid` tinyint(1) default '0' COMMENT '�Ƿ���ʾ',
  `title` varchar(255) default '' COMMENT '���ͱ���',
  `did` int(10) unsigned default '0' COMMENT '����ID',
  `name` varchar(255) default '' COMMENT '���ݱ���',
  `link` varchar(255) default '' COMMENT '��������',
  `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `yid` (`yid`),
  KEY `did` (`did`),
  KEY `dt_dir_id` (`dir`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա��̬��';


--cscms_fans<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}fans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uida` int(10) unsigned default '0' COMMENT '��ԱID',
  `uidb` int(10) unsigned default '0' COMMENT '��˿ID',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `fans_uida_id` (`uida`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��˿��';


--cscms_friend<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}friend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uida` int(10) unsigned default '0' COMMENT '��ԱID',
  `uidb` int(10) unsigned default '0' COMMENT '����ID',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `friend_uida_id` (`uida`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='���ѱ�';


--cscms_funco<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}funco` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uida` int(10) unsigned default '0' COMMENT '��ԱID',
  `uidb` int(10) unsigned default '0' COMMENT '������ID',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `funco_uida_id` (`uida`,`id`),
  KEY `funco_uidb_id` (`uidb`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�ÿͱ�';


--cscms_gbook<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` tinyint(1) default '0' COMMENT '����ID',
  `fid` int(10) unsigned default '0' COMMENT '�ϼ�ID',
  `uida` int(10) unsigned default '0' COMMENT '��ԱID',
  `uidb` int(10) unsigned default '0' COMMENT '������ID',
  `neir` text COMMENT '����',
  `ip` varchar(20) default '' COMMENT 'IP',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `cid` (`cid`),
  KEY `gbook_uida_id` (`uida`,`id`),
  KEY `gbook_uidb_id` (`uidb`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='���Ա�';


--cscms_label<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}label` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(64) default '' COMMENT 'Ψһ��ʾ',
  `selflable` text COMMENT '��ǩ����',
  `neir` varchar(128) default '' COMMENT '��ǩ����',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��̬��ǩ��';


--cscms_link<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}link` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(64) default '' COMMENT '����',
  `url` varchar(255) default '' COMMENT '��ַ',
  `pic` varchar(255) default '' COMMENT 'LOGO',
  `cid` tinyint(1) default '1' COMMENT '����',
  `sid` tinyint(1) default '1' COMMENT '��ҳ�Ƿ���ʾ',
  `xid` smallint(5) default '0' COMMENT '�����',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `sid` (`sid`),
  KEY `xid` (`xid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�������ӱ�';


--cscms_income<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}income` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dir` varchar(64) default '' COMMENT '�������',
  `title` varchar(255) default '' COMMENT '��������',
  `sid` tinyint(1) default '0' COMMENT '����ID',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `nums` int(10) unsigned default '0' COMMENT '����',
  `ip` varchar(15) default '' COMMENT 'IP',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`),
  KEY `dir` (`dir`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='�����¼��';


--cscms_page<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}page` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `sid` tinyint(1) default '0' COMMENT '���з�ʽ',
  `name` varchar(64) default '' COMMENT 'Ψһ��ʾ',
  `neir` varchar(128) default '' COMMENT 'ҳ�����',
  `url` varchar(100) default '' COMMENT 'ҳ��·��',
  `html` text COMMENT 'ҳ�汻��',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `sid` (`sid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='�Զ���ҳ���';


--cscms_pay<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}pay` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dingdan` varchar(64) default '' COMMENT '����',
  `type` varchar(30) default '' COMMENT '֧����ʽ',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `rmb` decimal(10,2) default '0' COMMENT '���',
  `pid` tinyint(1) default '0' COMMENT '״̬',
  `ip` varchar(15) default '' COMMENT 'IP',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `dingdan` (`dingdan`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='֧����¼��';


--cscms_paycard<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}paycard` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `card` varchar(20) default '' COMMENT '����',
  `pass` varchar(10) default '' COMMENT '����',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `rmb` decimal(10,2) default '0' COMMENT '���',
  `usertime` int(10) unsigned default '0' COMMENT 'ʹ��ʱ��',
  `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��ֵ����';


--cscms_plugins<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}plugins` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(100) default '' COMMENT '�������',
  `author` varchar(20) default '' COMMENT '����',
  `dir` varchar(30) default '' COMMENT 'Ŀ¼',
  `version` varchar(10) default '' COMMENT '�汾��',
  `description` varchar(200) default '' COMMENT '����',
  `sid` tinyint(1) default '0' COMMENT '����',
  `ak` text COMMENT 'ak',
  PRIMARY KEY  (`id`),
  KEY `dir` (`dir`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='����';


--cs_pl<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}pl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(64) default '' COMMENT '��Ա����',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `content` text COMMENT '��������',
  `ip` varchar(18) default '' COMMENT '����IP',
  `did` int(10) unsigned default '0' COMMENT '����ID',
  `dir` varchar(64) default '' COMMENT '�������',
  `cid` tinyint(2) default '0' COMMENT '����֧ID',
  `fid` int(10) unsigned default '0' COMMENT '�ϼ�ID',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `uid` (`uid`),
  KEY `did` (`did`),
  KEY `fid` (`fid`),
  KEY `pl_dir_did_id` (`dir`,`did`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='���۱�';


--cscms_session<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}session` (
  `sessionid` varchar(40) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `plub` varchar(18) default '' COMMENT '����ID',
  `data` text COMMENT 'session����',
  `ip` varchar(15) default '' COMMENT 'IP',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`sessionid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='session���ݱ�';



--cscms_spend<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}spend` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `dir` varchar(64) default '' COMMENT '�������',
  `title` varchar(255) default '' COMMENT '��������',
  `sid` tinyint(1) default '0' COMMENT '����ID',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `nums` int(10) unsigned default '0' COMMENT '����',
  `ip` varchar(15) default '' COMMENT 'IP',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`),
  KEY `dir` (`dir`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='���Ѽ�¼��';


--cscms_tags<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}tags` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) default '' COMMENT '����',
  `fid` int(8) unsigned default '0' COMMENT '����ID',
  `xid` int(3) unsigned default '0' COMMENT '����ID',
  `hits` int(10) unsigned default '0' COMMENT '����',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `xid` (`xid`),
  KEY `hits` (`hits`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='ȫվTAGS��ǩ��';


--cscms_user<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) default '' COMMENT '�˺�',
  `uid` bigint(20) default '0' COMMENT 'UCID',
  `tid` tinyint(1) default '0' COMMENT '�Ƿ��Ƽ�',
  `sid` tinyint(1) default '0' COMMENT '�Ƿ�����',
  `yid` tinyint(1) default '0' COMMENT '�Ƿ񼤻�',
  `zid` int(6) unsigned default '1' COMMENT '��Ա��ID',
  `rzid` tinyint(1) default '0' COMMENT '�Ƿ���֤',
  `pass` varchar(32) default '' COMMENT '����',
  `code` varchar(6) default '' COMMENT '��Կ',
  `logip` varchar(20) default '' COMMENT '��¼IP',
  `lognum` smallint(5) unsigned default '0' COMMENT '��¼����',
  `logtime` int(10) unsigned default '0' COMMENT '��¼ʱ��',
  `addtime` int(10) unsigned default '0' COMMENT 'ע��ʱ��',
  `zutime` int(10) unsigned default '0' COMMENT '��Ա�鵽��ʱ��',
  `qq` varchar(50) default '' COMMENT 'QQ',
  `tel` varchar(15) default '' COMMENT '�绰',
  `sex` tinyint(1) default '0' COMMENT '�Ա�',
  `city` varchar(30) default '' COMMENT '����',
  `email` varchar(50) default '' COMMENT '����',
  `logo` varchar(255) default '' COMMENT 'ͷ��',
  `nichen` varchar(50) default '' COMMENT '�ǳ�',
  `cion` int(10) unsigned default '0' COMMENT '���',
  `rmb` decimal(10,2) default '0' COMMENT '��Ǯ',
  `vip` tinyint(1) unsigned default '0' COMMENT '�Ƿ�VIP',
  `viptime` int(10) unsigned default '0' COMMENT 'VIP����ʱ��',
  `qianm` varchar(255) default '' COMMENT 'ǩ��',
  `zx` tinyint(1) default '0' COMMENT '����״̬',
  `logms` int(10) unsigned default '0' COMMENT '������ʱ��',
  `qdts` smallint(5) unsigned default '0' COMMENT 'ǩ������',
  `qdtime` int(10) unsigned default '0' COMMENT 'ǩ��ʱ��',
  `level` int(6) unsigned default '0' COMMENT '�ȼ�',
  `jinyan` int(10) unsigned default '0' COMMENT '����',
  `hits` int(10) unsigned default '0' COMMENT '�ռ�����',
  `yhits` int(10) unsigned default '0' COMMENT '�ռ�������',
  `zhits` int(10) unsigned default '0' COMMENT '�ռ�������',
  `rhits` int(10) unsigned default '0' COMMENT '�ռ�������',
  `zanhits` int(10) unsigned default '0' COMMENT '��������',
  `addhits` int(10) unsigned default '0' COMMENT '�������ݴ���',
  `regip` varchar(20) default '' COMMENT 'ע��IP',
  `skins` varchar(128) default '' COMMENT 'ģ��·��',
  `bgpic` varchar(255) default '' COMMENT '��ҳ����',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `tid` (`tid`),
  KEY `sid` (`sid`),
  KEY `yid` (`yid`),
  KEY `rzid` (`rzid`),
  KEY `sex` (`sex`),
  KEY `zx` (`zx`),
  KEY `hits` (`hits`),
  KEY `yhits` (`yhits`),
  KEY `zhits` (`zhits`),
  KEY `rhits` (`rhits`),
  KEY `addhits` (`addhits`),
  KEY `user_yid_id` (`yid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա��';


--cscms_user_log<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}user_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `loginip` varchar(50) default '' COMMENT '��¼IP',
  `logintime` int(10) unsigned default '0' COMMENT '��¼ʱ��',
  `useragent` varchar(255) default '' COMMENT '�ͻ�����Ϣ',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա��¼��';


--cscms_userzu<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}userzu` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(100) default '' COMMENT '����',
  `xid` smallint(5) default '0' COMMENT '����ID',
  `color` varchar(10) default '' COMMENT '������ɫ',
  `pic` varchar(255) default '' COMMENT '��ͼ��',
  `info` varchar(255) default '' COMMENT '����',
  `cion_y`  int(10) unsigned default '0' COMMENT '������',
  `cion_m`  int(10) unsigned default '0' COMMENT '���½��',
  `cion_d`  int(10) unsigned default '0' COMMENT '������',
  `fid` tinyint(1) default '0' COMMENT '�ϴ�����Ȩ��',
  `aid` tinyint(1) default '0' COMMENT '��������Ȩ��',
  `sid` tinyint(1) default '0' COMMENT '�����������',
  `vid` tinyint(1) default '0' COMMENT '��������Ȩ��',
  `mid` tinyint(1) default '0' COMMENT '����˽��Ȩ��',
  `did` tinyint(1) default '0' COMMENT '�������Ȩ��',
  PRIMARY KEY  (`id`),
  KEY `xid` (`xid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա���';


--cscms_userlevel<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}userlevel` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `xid` smallint(5) default '0' COMMENT '����ID',
  `name` varchar(100) default '' COMMENT '����',
  `stars` smallint(3) default '0' COMMENT '��������',
  `jinyan` int(10) unsigned default '0' COMMENT '���辭��',
  PRIMARY KEY  (`id`),
  KEY `xid` (`xid`),
  KEY `stars` (`stars`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա�ȼ���';


--cscms_useroauth<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}useroauth` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` tinyint(2) default '0' COMMENT '����ID',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `csid` int(10) unsigned default '0' COMMENT 'cscms�ٷ�����ID',
  `nickname` varchar(255) default '' COMMENT '��Ȩ�����ǳ�',
  `avatar` varchar(255) default '' COMMENT '��Ȩ����ͷ���ַ',
  `oid` varchar(255) default '' COMMENT '��Ȩ����ID',
  `access_token` varchar(255) default '' COMMENT '��Ȩtoken',
  `refresh_token` varchar(255) default '' COMMENT '��Ȩˢ��token',
  `expire_at` int(10) unsigned default '0' COMMENT '��Ȩ����ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `csid` (`csid`),
  KEY `cid` (`cid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��ԱOAuth2��Ȩ��';


--cscms_msg<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}msg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `did` tinyint(1) default '0' COMMENT '�Ƿ��Ѷ�',
  `name` varchar(255) default '' COMMENT '����',
  `neir` text COMMENT '����',
  `uida` int(10) unsigned default '0' COMMENT '��ԱID',
  `uidb` int(10) unsigned default '0' COMMENT '������ID',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `did` (`did`),
  KEY `msg_uida_id` (`uida`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='��Ա��Ϣ��';


--cscms_web_pay<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}web_pay` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` int(10) unsigned default '0' COMMENT 'ģ��ΨһID',
  `name` varchar(255) default '' COMMENT 'ģ�����',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `cion` int(10) unsigned default '0' COMMENT '�۳����',
  `addtime` int(10) unsigned default '0' COMMENT 'ʱ��',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='ģ��ʹ�ü�¼��';


--cscms_share<cscms>--

CREATE TABLE IF NOT EXISTS `{Prefix}share` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ip` varchar(20) default '' COMMENT '����IP',
  `agent` varchar(255) default '' COMMENT '���ʿͻ���',
  `uid` int(10) unsigned default '0' COMMENT '��ԱID',
  `cion` int(10) unsigned default '0' COMMENT '���ͽ��',
  `jinyan` int(10) unsigned default '0' COMMENT '���;���',
  `addtime` int(10) unsigned default '0' COMMENT '����ʱ��',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='������¼��';
