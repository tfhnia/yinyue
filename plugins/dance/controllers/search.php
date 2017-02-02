<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-16-16
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl'); //װ����ͼģ��
	}

    //�����б�
	public function index()
	{
		    $data['tags'] = $this->input->get_post('tags',true,true);
		    $data['name'] = $this->input->get_post('name',true,true);
		    $data['zc'] = $this->input->get_post('zc',true,true);
		    $data['zq'] = $this->input->get_post('zq',true,true);
		    $data['bq'] = $this->input->get_post('bq',true,true);
		    $data['hy'] = $this->input->get_post('hy',true,true);
		    $data['key'] = $this->input->get_post('key',true,true);
		    $data['cid'] = intval($this->input->get_post('cid',true));
            $page  = intval($this->input->get_post('page',true));   //ҳ��
            if($page==0) $page=1;
			//������ĸ
		    $zm = $this->input->get_post('zm',true,true);
			$data['zm']['zd'] = 'name'; //Ҫ��������ĸ���ֶ�
			$data['zm']['zm'] = $zm; //Ҫ��������ĸ
			//װ��ģ�岢���
	        $this->CsdjTpl->plub_search('dance',$data,$page);
	}
}


