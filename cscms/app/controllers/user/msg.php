<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-06
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msg extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->lang->load('user');
			$this->CsdjUser->User_Login();
	}

    //��Ϣ�б�
	public function index()
	{
		    $op=$this->uri->segment(4); //��ʽ
		    $page=intval($this->uri->segment(5)); //��ҳ
			if(empty($op)) $op='all';
			//ģ��
			$tpl='msg.html';
			//URL��ַ
		    $url='msg/index/'.$op;
            $sqlstr = "select * from ".CS_SqlPrefix."msg where uida=".$_SESSION['cscms__id'];
            if($op=='xt'){
                  $sqlstr.= " and uidb=0";
			}
            if($op=='wd'){
                  $sqlstr.= " and did=0";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('msg_01');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$op,$sqlstr,$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //�鿴��Ϣ
	public function show()
	{
		    $id=intval($this->uri->segment(4)); //ID
            if($id==0) msg_url(L('msg_02'),'javascript:history.back();');
			//ģ��
			$tpl='msg-show.html';
			//URL��ַ
		    $url='msg/show/'.$id;
		    $row=$this->CsdjDB->get_row_arr('msg','*',$id);
			if(!$row) msg_url(L('msg_03'),'javascript:history.back();');
			if($row['uida']!=$_SESSION['cscms__id']) msg_url(L('msg_04'),'javascript:history.back();');
            if($row['did']==0){//���Ϊ�Ѷ�
                 $this->db->query("update ".CS_SqlPrefix."msg set did=1 where id=".$id."");
			}
			//��ǰ��Ա
		    $rowu=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('msg_05');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($rowu,$url,1,$tpl,$title,'','',$ids,true,false);
            $Mark_Text=$this->skins->cscms_skins('msg',$Mark_Text,$Mark_Text,$row);//������ǰ��Ϣ��ǩ
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //������Ϣ
	public function add()
	{
			$this->load->helper('string');
		    $id=intval($this->uri->segment(4)); //ID
		    $user = $this->input->get('user',true,true);
			$name='';
			if($id>0){
		        $rows=$this->CsdjDB->get_row('msg','*',$id);
			    if(!$rows) msg_url(L('msg_03'),'javascript:history.back();');
				if($rows->uidb>0){
				   $user=getzd('user','name',$rows->uidb);
				   $name=L('msg_06').$rows->name;
				}
			}
			//ģ��
			$tpl='msg-add.html';
			//URL��ַ
		    $url='msg/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','mid',$row['zid']);
			if($rowz->mid==0){
                 msg_url(L('msg_07'),'javascript:history.back();');
			}

			//װ��ģ��
			$title=L('msg_08');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			$Mark_Text=str_replace("[user:msgsave]",spacelink('msg/save'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//�жϻظ�
			$Mark_Text=str_replace("[msg:user]",$user,$Mark_Text);
			$Mark_Text=str_replace("[msg:name]",$name,$Mark_Text);
			echo $Mark_Text;
	}

    //������Ϣ�ύ
	public function save()
	{
			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','mid',$zuid);
			if($rowu->mid==0){
                 msg_url(L('msg_07'),'javascript:history.back();');
			}

		    $user = $this->input->post('user',true,true);
		    $name = $this->input->post('name',true,true);
		    $neir = $this->input->post('neir',true,true);
			if(empty($user)) msg_url(L('msg_09'),'javascript:history.back();');
			$uid=getzd('user','id',$user,'name');
            if(intval($uid)==0) msg_url(L('msg_10'),'javascript:history.back();');
			if(empty($name)) msg_url(L('msg_11'),'javascript:history.back();');
			if(empty($neir)) msg_url(L('msg_12'),'javascript:history.back();');

			$add['uida']=$uid;
			$add['uidb']=$_SESSION['cscms__id'];
			$add['name']=$name;
			$add['neir']=$neir;
			$add['addtime']=time();
            $this->CsdjDB->get_insert('msg',$add);
            msg_url(L('msg_13'),spacelink('msg'));
	}

    //ɾ����Ϣ
	public function del()
	{
		    $op=$this->uri->segment(4);
			if($op=='yue'){ //ǰһ����
				$times=time()-86400*30;
                $this->db->query("delete from ".CS_SqlPrefix."msg where uida=".$_SESSION['cscms__id']." and addtime<".$times."");
			}elseif($op=='all'){  //ȫ��
                $this->db->query("delete from ".CS_SqlPrefix."msg where uida=".$_SESSION['cscms__id']."");
			}else{ //��ID
		        $id=intval($this->uri->segment(5)); //ID
			    if($id==0) msg_url(L('msg_02'),'javascript:history.back();');
                $this->db->query("delete from ".CS_SqlPrefix."msg where uida=".$_SESSION['cscms__id']." and id=".$id."");
			}
            msg_url(L('msg_14'),$_SERVER['HTTP_REFERER']);
	}
}
