<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-19
 */

/**
 * Ĭ��·�����ã���������ģ�
 */
if(Home_Ym==0){
   $route['([a-zA-Z0-9\_\-]+)/home/'.PLUBPATH.'/([0-9_\/]+)'] = 'home/'.PLUBPATH.'/index/$1/$2';
   $route['([a-zA-Z0-9\_\-]+)/home/'.PLUBPATH.'/(.+)'] = 'home/$2/index/$1';
   $route['([a-zA-Z0-9\_\-]+)/home/'.PLUBPATH.''] = 'home/'.PLUBPATH.'/index/$1';
}else{
   $route['home/'.PLUBPATH.'/([0-9_\/]+)'] = 'home/'.PLUBPATH.'/index/$1';
   $route['home/'.PLUBPATH.'/(.+)'] = 'home/$1/index';
   $route['home/'.PLUBPATH.''] = 'home/'.PLUBPATH.'/index';
}
if (is_file(FCPATH.'plugins/'.PLUBPATH.'/config/rewrite.php')) require FCPATH.'plugins/'.PLUBPATH.'/config/rewrite.php';
/**
 * �Զ���·��
 */
 
//$route['�Զ���·���������']	= 'ָ���·��URI��URI���򣺿�����/����/����1��ֵ/����2��ֵ...��';


