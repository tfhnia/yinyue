<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Feed extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->lang->load('home');
	}
	public function index()
	{
            $fid = $this->uri->segment(4);   //���
            $page = (int)$this->uri->segment(5);   //ҳ��
			if(empty($fid)) $fid='all';
			//ģ��
			$tpl='feed.html';
			//��ǰ��Ա
			$uid=get_home_uid();
		    $row=$this->CsdjDB->get_row_arr('user','*',$uid);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=$row['nichen'].L('feed_01');
			$ids['uid']=$row['id'];
			$ids['uida']=$row['id'];
			$sql=($fid=='all')?"":"SELECT {field} FROM ".CS_SqlPrefix."dt where dir='".$fid."'";
            $this->CsdjTpl->home_list($row,'feed',$page,$tpl,$title,$ids,$fid,$sql);
	}
}
