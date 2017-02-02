<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-24
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->helper('vod');
	}

    //��ҳ
	public function index()
	{
            //�ж�����ģʽ,��������ת����̬ҳ��
			$uri=config('Html_Uri');
	        if(config('Web_Mode')==3 && $uri['index']['check']==1 && !defined('MOBILE')){
				$index_url=$uri['index']['url'];
				header("Location: ".$index_url);
				exit;
			}
			//װ��ģ�岢���
	        $this->CsdjTpl->plub_index('vod');
	}
}
