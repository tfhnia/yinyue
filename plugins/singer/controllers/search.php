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
			$this->load->helper('singer');
		    $this->load->model('CsdjTpl'); //×°ÔØÊÓÍ¼Ä£ĞÍ
	}

    //ËÑË÷ÁĞ±í
	public function index()
	{
		    $data['tags'] = $this->input->get_post('tags',true,true);
		    $data['key'] = $this->input->get_post('key',true,true);
		    $data['cid'] = intval($this->input->get_post('cid',true));
            $page  = intval($this->input->get_post('page',true));   //Ò³Êı
            if($page==0) $page=1;
			//ËÑË÷×ÖÄ¸
		    $zm = $this->input->get_post('zm',true,true);
			$data['zm']['zd'] = 'name'; //ÒªËÑË÷µÄ×ÖÄ¸µÄ×Ö¶Î
			$data['zm']['zm'] = $zm; //ÒªËÑË÷µÄ×ÖÄ¸
			//×°ÔØÄ£°å²¢Êä³ö
	        $this->CsdjTpl->plub_search('singer',$data,$page);
	}
}


