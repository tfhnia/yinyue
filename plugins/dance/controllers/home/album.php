<?php if ( ! defined('HOMEPATH')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-18
 */
class Album extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl');
	}

	public function index()
	{
            $page = (int)$this->uri->segment(6);   //ҳ��
			//ģ��
			$tpl='album.html';
			//��ǰ��Ա
			$uid=get_home_uid();
		    $row=$this->CsdjDB->get_row_arr('user','*',$uid);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=$row['nichen'].'��ר��';
			$ids['uid']=$row['id'];
			$ids['uida']=$row['id'];
            $this->CsdjTpl->home_list($row,'dance/album',$page,$tpl,$title,$ids,'index');
	}
}

