<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-24
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Opt extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('news');
		    $this->load->model('CsdjTpl');
	}

	public function index($name)
	{
            if(empty($name)){
                    msg_url('出错了，模板标示为空！',Web_Path);
            }
            $this->CsdjTpl->opt($name);
	}
}
