<?php if ( ! defined('HOMEPATH')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-18
 */
class News extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('news');
		    $this->load->model('CsdjTpl');
	}

	public function index()
	{
            $cid = (int)$this->uri->segment(4);   //CID
            $page = (int)$this->uri->segment(5);   //ҳ��
			//ģ��
			$tpl='news.html';
			//��ǰ��Ա
			$uid=get_home_uid();
		    $row=$this->CsdjDB->get_row_arr('user','*',$uid);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=$row['nichen'].'������';
			$ids['uid']=$row['id'];
			$ids['uida']=$row['id'];
            $sql=($cid==0)?"":"SELECT {field} FROM ".CS_SqlPrefix."news where cid in (".getChild($cid).")";
            $this->CsdjTpl->home_list($row,'news',$page,$tpl,$title,$ids,$cid,$sql);
	}
}

