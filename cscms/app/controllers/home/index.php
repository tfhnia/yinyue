<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-04-27
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Index extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->lang->load('home');
	} 
	public function index()
	{
            $skin = $this->uri->segment(4);   //ģ��
			//ģ��
			$tpl='index.html';
			//��ǰ��Ա
			$uid=get_home_uid();
		    $row=$this->CsdjDB->get_row_arr('user','*',$uid);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=$row['nichen'].L('index_01');
			$ids['uid']=$row['id'];
			$ids['uida']=$row['id'];
			if(!empty($skin) && file_exists(CSCMS.'tpl/home/'.$skin.'/config.php')){
                 $arr=require(CSCMS.'tpl/home/'.$skin.'/config.php');
				 $row['skins']=$arr['path'];
			}
            $Mark_Text=$this->CsdjTpl->home_list($row,'index',1,$tpl,$title,$ids,'','',true,false);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$uid,'home'));
			echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}
