<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-18
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Info extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->lang->load('home');
	}
	public function index()
	{
			//ģ��
			$tpl='info.html';
			//��ǰ��Ա
			$uid=get_home_uid();
		    $row=$this->CsdjDB->get_row_arr('user','*',$uid);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=$row['nichen'].L('info_01');
			$ids['uid']=$row['id'];
			$ids['uida']=$row['id'];
            $Mark_Text=$this->CsdjTpl->home_list($row,'info',1,$tpl,$title,$ids);
	}
}
