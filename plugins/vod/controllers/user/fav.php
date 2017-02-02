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
			$this->load->helper('vod');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	        $this->CsdjUser->User_Login();
	}

    //�ղ���Ƶ
	public function index()
	{
		    $cid=intval($this->uri->segment(4)); //����ID
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='fav.html';
			//URL��ַ
		    $url='fav/index/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."vod_fav where uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='���ղص���Ƶ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[vod:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//ɾ��
	public function del()
	{
		    $id=intval($this->uri->segment(4));
		    $callback = $this->input->get('callback',true);
            $row=$this->db->query("select uid from ".CS_SqlPrefix."vod_fav where id=".$id."")->row();
			if($row){
                     if($row->uid!=$_SESSION['cscms__id']){
						  $err=1002;
						  if(empty($callback)) msg_url('û��Ȩ�޲���','javascript:history.back();');
					 }else{
                          $this->db->query("DELETE FROM ".CS_SqlPrefix."vod_fav where id=".$id."");
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

