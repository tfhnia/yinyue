<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-01
 */
//Ĭ��ʱ��
date_default_timezone_set("Asia/Shanghai");
//��ǰ����URI
define('REQUEST_URI', $_SERVER['REQUEST_URI']);

//Ӧ�û���
define('ERROR_MSG', 'production');
if (defined('ERROR_MSG')){
	switch (ERROR_MSG){
		case 'development':error_reporting(E_ALL);break;
		case 'production':error_reporting(0);break;
	}
}
//�ļ�����
if (!defined('SELF')) {
    define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
}
// ��վ��Ŀ¼
if (!defined('FCPATH')) {
    define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
}
// ���õ�ǰĿ¼��ȷ��CLI������
if (defined('STDIN')){
	chdir(dirname(__FILE__));
}

define('CSCMS', FCPATH . 'cscms/'); //ϵͳ����Ŀ¼
define('ENVIRONMENT', CSCMS . 'config/'); // ���������ļ�Ŀ¼

//Ĭ�Ͽ�����Ŀ¼
$app_folder = 'cscms/app';

//����CSCMS�����ļ�
require_once CSCMS.'lib/Cs_Cscms.php';

define('EXT', '.php'); //PHP�ļ���չ��
define('BASEPATH', FCPATH.'cscms/system/'); //CI���Ŀ¼
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/')); //ϵͳ�ļ��е�����
define('APPPATH', FCPATH.$app_folder.'/'); //���Ŀ¼
define('CSCMSPATH', FCPATH.'packs/');  //CSCMS������չĿ¼

require_once BASEPATH.'core/CodeIgniter.php';  //����CI�����ļ�
