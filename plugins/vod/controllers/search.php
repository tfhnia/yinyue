<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('vod');
		    $this->load->model('CsdjTpl'); //װ����ͼģ��
	}

    //�����б�
	public function index()
	{
		    $data['zhuyan'] = $this->input->get_post('zhuyan',true,true); //����
		    $data['daoyan'] = $this->input->get_post('daoyan',true,true); //����
		    $data['yuyan'] = $this->input->get_post('yuyan',true,true); //����
		    $data['diqu'] = $this->input->get_post('diqu',true,true); //����
		    $data['year'] = $this->input->get_post('year',true,true); //���
		    $data['tags'] = $this->input->get_post('tags',true,true); //TAGS��ǩ
		    $data['type'] = $this->input->get_post('type',true,true); //����
		    $data['key'] = $this->input->get_post('key',true,true);   //�ؼ���
		    $data['cid'] = intval($this->input->get_post('cid',true)); //����ID
            $page  = intval($this->input->get_post('page',true));   //ҳ��
            if($page==0) $page=1;
			//������ĸ
		    $zm = $this->input->get_post('zm',true,true);
			$data['zm']['zd'] = 'name'; //Ҫ��������ĸ���ֶ�
			$data['zm']['zm'] = $zm; //Ҫ��������ĸ
            //�������ID
			$data['sid'] = $data['cid'];
			if($data['cid']>0){
				$fid=getzd('vod_list','fid',$data['cid']);
				$data['sid'] = ($fid==0)?$data['cid']:$fid;
			}
			//װ��ģ�岢���
	        $this->CsdjTpl->plub_search('vod',$data,$page);
	}
}


