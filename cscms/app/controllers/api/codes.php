<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codes extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
	}

	public function index()
	{
		   $params['width']  = (intval($this->input->get('w'))==0)?'':$this->input->get('w');  //���  Ĭ��150
		   $params['height'] = (intval($this->input->get('h'))==0)?'':$this->input->get('h');  //�߶�  Ĭ��50
		   $params['size']   = (intval($this->input->get('s'))==0)?'':$this->input->get('s');  //�����С  Ĭ��20
		   $params['len']    = (intval($this->input->get('l'))==0)?'':$this->input->get('l');  //��֤�볤��  Ĭ��5

		   //���ؿ���
           $this->load->library('code',$params);
           //������֤��
		   $this->cookie->set_cookie("codes",$this->code->getCode(),time()+1800);
		   //����ͼƬ
           $this->code->doimg();
	}
}
