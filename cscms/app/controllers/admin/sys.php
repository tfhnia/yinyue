<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-01
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sys extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
			$this->lang->load('admin_sys');
	}

    //����Ա�б�
	public function index()
	{
            $sid=intval($this->input->get('sid', TRUE)); 
	        $this->load->library('ip');
			if($sid>0){
	             $sql_string = "SELECT * FROM ".CS_SqlPrefix."admin where sid='$sid' order by id desc";
			}else{
	             $sql_string = "SELECT * FROM ".CS_SqlPrefix."admin order by id desc";
			}
	        $query = $this->db->query($sql_string); 
	        $data['nums'] = $query->num_rows();
	        $data['admin'] = $query->result();
            $this->load->view('admin_index.html',$data);
	}

    //��������Ա
	public function add()
	{
		    $data['adminname']='';
		    $data['adminpass']='';
		    $data['sid']=0;
		    $data['id']=0;
			$data['title']=L('plub_01');
            $this->load->view('admin_edit.html',$data);
	}

    //�޸Ĺ���Ա
	public function edit()
	{
            $id=intval($this->uri->segment(4)); 
            $row = $this->db->query("SELECT id,adminname,adminpass,sid FROM ".CS_SqlPrefix."admin where id='$id'")->row(); 
			if(!$row) admin_msg(L('plub_02'),site_url('sys'),'no');
		    $data['adminname']=$row->adminname;
		    $data['adminpass']=$row->adminpass;
		    $data['id']=$row->id;
		    $data['sid']=$row->sid;
			$data['title']=L('plub_03');
            $this->load->view('admin_edit.html',$data);
	}

    //����Ա���
	public function save()
	{
            $id=intval($this->input->post('id', TRUE)); 
		    $adminpass=$this->input->post('adminpass', TRUE);
		    $data['adminname']=$this->input->post('adminname', TRUE);
		    $data['sid']=intval($this->input->post('sid', TRUE)); 

            if(empty($data['adminname']) || $data['sid']==0){
                    admin_msg(L('plub_04'),'javascript:history.back();','no');
			}
			if($id==0){ //����
            	 if(empty($adminpass)){
                    admin_msg(L('plub_05'),'javascript:history.back();','no');
				 }
				 $this->load->helper('string');
				 $data['admincode']=random_string('alnum',6);
                 $data['adminpass']=md5(md5($adminpass).$data['admincode']);
                 $this->CsdjDB->get_insert('admin',$data);
			}else{
				 $row = $this->db->query("SELECT admincode FROM ".CS_SqlPrefix."admin where id='$id'")->row();
				 if(!empty($adminpass)){
                      $data['adminpass']=md5(md5($adminpass).$row->admincode);
				 }
                 $this->CsdjDB->get_update('admin',$id,$data);
			}
            admin_msg(L('plub_06'),site_url('sys'),'ok');  //�����ɹ�
	}

    //��ɫ�б�
	public function zu()
	{
	        $sql_string = "SELECT * FROM ".CS_SqlPrefix."adminzu order by id asc";
	        $query = $this->db->query($sql_string); 
	        $data['nums'] = $query->num_rows();
	        $data['adminzu'] = $query->result();
            $this->load->view('admin_zu.html',$data);
	}

    //������ɫ
	public function zu_add()
	{
		    $data['name']='';
		    $data['sys']='';
		    $data['id']=0;
			$data['title']=L('plub_01');
            $this->load->view('admin_edit_zu.html',$data);
	}

    //�޸Ľ�ɫ
	public function zu_edit()
	{
            $id=intval($this->uri->segment(4)); 
            $row = $this->db->query("SELECT id,name,sys FROM ".CS_SqlPrefix."adminzu where id='$id'")->row(); 
			if(!$row) admin_msg(L('plub_02'),site_url('sys'),'no');
		    $data['name']=$row->name;
		    $data['sys']=$row->sys;
		    $data['id']=$row->id;
			$data['title']=L('plub_03');
            $this->load->view('admin_edit_zu.html',$data);
	}

    //��ɫ���
	public function zu_save()
	{
            $id=intval($this->input->post('id', TRUE)); 
		    $data['name']=$this->input->post('name', TRUE);
		    $data['sys']=$this->input->post('sys', TRUE);
		    if(!empty($data['sys'])){
                 $data['sys']=implode(',', $data['sys']);
			}else{
                 $data['sys']='';
			}
            if(empty($data['name'])){
                    admin_msg(L('plub_07'),'javascript:history.back();','no');
			}
			if($id==0){ //����
		         $data['sys']='';
		         $data['app']='';
                 $this->CsdjDB->get_insert('adminzu',$data);
			}else{
                 $this->CsdjDB->get_update('adminzu',$id,$data);
			}
            admin_msg(L('plub_06'),site_url('sys/zu'),'ok');  //�����ɹ�
	}

    //�޸�����
	public function editpass()
	{
            $this->load->view('admin_edit_pass.html');
	}

    //��������
	public function editpass_save()
	{
		    $adminpass=$this->input->post('adminpass', TRUE);
		    $row = $this->db->query("SELECT admincode FROM ".CS_SqlPrefix."admin where id='".$_SESSION['admin_id']."'")->row(); 
		    $data['adminname'] = $this->input->post('adminname', TRUE);
            if(empty($data['adminname'])){
                    admin_msg(L('plub_08'),'javascript:history.back();','no');
			}
			if(!empty($adminpass)){
		        $data['adminpass'] = md5(md5($adminpass).$row->admincode);
			}
            $this->CsdjDB->get_update('admin',$_SESSION['admin_id'],$data);
            admin_msg(L('plub_09'),site_url('login'),'ok');
	}

    //���ӿ��
	public function card()
	{
		    $id=intval($this->uri->segment(4));
		    if(CS_Safe_Card==0){
                admin_msg(L('plub_10'),'###','no','',5);
			}
            if($id==0) admin_msg(L('plub_11'),'javascript:history.back();','no');
            $this->load->library('card');
	        $row = $this->db->query("SELECT card,adminname FROM ".CS_SqlPrefix."admin where id='".$id."'")->row(); 
			$data['picurl']='';
            if(!empty($row->card)){
			    $data['picurl']=$this->card->pic($row->card,$row->adminname);
			}
			$data['id']=$id;
            $this->load->view('admin_card.html',$data);
	}

    //�޸Ŀ��
	public function card_add()
	{
		    $id=intval($this->uri->segment(4));
            if($id==0) admin_msg(L('plub_11'),'javascript:history.back();','no');
			$row = $this->db->query("SELECT adminname FROM ".CS_SqlPrefix."admin where id='".$id."'")->row(); 
            $this->load->library('card');
	        $card=$this->card->add($row->adminname);

			//�޸Ŀ��
	        $updata['card'] = $card;
            $this->CsdjDB->get_update('admin',$id,$updata);
            admin_msg(L('plub_12'),site_url('sys/card/'.$id),'ok','',5);
	}

    //ɾ�����
	public function card_del()
	{
		    $id=intval($this->uri->segment(4));
            if($id==0) admin_msg(L('plub_11'),'javascript:history.back();','no');
			$row = $this->db->query("SELECT adminname FROM ".CS_SqlPrefix."admin where id='".$id."'")->row(); 
            $this->load->library('card');
	        $str=$this->card->del($row->adminname);

			//ɾ�����
	        $updata['card'] = '';
            $this->CsdjDB->get_update('admin',$id,$updata);

            admin_msg($str,site_url('sys/card/'.$id),'ok','',5);
	}

    //��¼��־
	public function log()
	{
            $id=intval($this->uri->segment(4));
            $sort = $this->input->get_post('sort',true);
            $desc = $this->input->get_post('desc',true);
			if(empty($sort)) $sort="id";
			if(empty($desc)) $desc="desc";

		    //ɾ����������ǰ�ĵ�¼��־��¼
			$times=time()-86400*90;
			$this->db->query("delete from ".CS_SqlPrefix."admin_log where logintime<".$times."");
            
	        $this->load->library('ip');
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

            if($id>0){
	             $sql_string = "SELECT * FROM ".CS_SqlPrefix."admin_log where uid=".$id." order by ".$sort." ".$desc;
			}else{
	             $sql_string = "SELECT * FROM ".CS_SqlPrefix."admin_log order by ".$sort." ".$desc;
			}
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            $base_url = ($id==0)?site_url('sys/log')."?sort=".$sort."&desc=".$desc:site_url('sys/log/'.$id)."?sort=".$sort."&desc=".$desc;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['log'] = $query->result();
	        $data['page'] = $page;
	        $data['sort'] = $sort;
	        $data['desc'] = $desc;
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('admin_log.html',$data);
	}

    //����Աɾ��
	public function del()
	{
            $id=intval($this->uri->segment(4));
			if($id==$_SESSION['admin_id']){
                admin_msg(L('plub_13'),'javascript:history.back();','no'); //����ɾ���Լ�
			}
			$this->CsdjDB->get_del('admin',$id);
            admin_msg(L('plub_14'),'javascript:history.back();','ok');  //�����ɹ�
	}

    //��ɫɾ��
	public function zu_del()
	{
            $id=intval($this->uri->segment(4));
			if($id==1){
                admin_msg(L('plub_15'),'javascript:history.back();','no'); //����ɾ���Լ�
			}
			$this->CsdjDB->get_del('admin',$id,'sid'); //ɾ���ý�ɫ�µ����г�Ա
			$this->CsdjDB->get_del('adminzu',$id);
            admin_msg(L('plub_14'),'javascript:history.back();','ok');  //�����ɹ�
	}
}

