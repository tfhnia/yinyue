<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-01
 */
define('IS_ADMIN', TRUE); // ��̨��ʶ
define('ADMINSELF', pathinfo(__FILE__, PATHINFO_BASENAME)); // ��̨�ļ���
define('SELF', ADMINSELF);
define('FCPATH', str_replace("\\", "/", dirname(__FILE__).'/')); // ��վ��Ŀ¼
require('index.php'); // �������ļ�
