<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Fav extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	        $this->CsdjUser->User_Login();
	}

    //����
	public function index()
	{
		    $page=intval($this->uri->segment(4));
			//ģ��
			$tpl='fav.html';
			//URL��ַ
		    $url='fav/index';
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance_fav where sid=1 and uid=".$_SESSION['cscms__id'];
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='���ղصĸ��� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//ר��
	public function album()
	{
		    $page=intval($this->uri->segment(4));
			//ģ��
			$tpl='fav-album.html';
			//URL��ַ
		    $url='fav/album';
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance_fav where sid=2 and uid=".$_SESSION['cscms__id'];
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='���ղص�ר�� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//ɾ��
	public function del()
	{
		    $id=intval($this->uri->segment(4));
		    $sid=intval($this->uri->segment(5));
		    $callback = $this->input->get('callback',true);
			if($sid==0) $sid=1;
            $row=$this->db->query("select uid from ".CS_SqlPrefix."dance_fav where id=".$id." and sid=".$sid."")->row();
			if($row){
                     if($row->uid!=$_SESSION['cscms__id']){
						  $err=1002;
						  if(empty($callback)) msg_url('û��Ȩ�޲���','javascript:history.back();');
					 }else{
                          $this->db->query("DELETE FROM ".CS_SqlPrefix."dance_fav where id=".$id."");
						  $err=1001;
						  if(empty($callback)) msg_url('ɾ���ɹ�~!','javascript:history.back();');
					 }
			}else{
				      $err=1002;
					  if(empty($callback)) msg_url('���ݲ�����','javascript:history.back();');
			}
			echo $callback."({error:".$err."})";
	}
}

