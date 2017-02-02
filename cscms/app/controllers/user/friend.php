<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-07
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friend extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->lang->load('user');
			$this->CsdjUser->User_Login();
	}

    //��ע�б�
	public function index()
	{
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='friend.html';
			//URL��ַ
		    $url='friend/index';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('friend_01');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //ɾ����ע
	public function del()
	{
		    $id=intval($this->uri->segment(4)); //ID
			if($id==0) msg_url(L('friend_02'),'javascript:history.back();');
            $this->db->query("delete from ".CS_SqlPrefix."friend where uida=".$_SESSION['cscms__id']." and id=".$id."");
            msg_url(L('friend_03'),$_SERVER['HTTP_REFERER']);
	}
}
