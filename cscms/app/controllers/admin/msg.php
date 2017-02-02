<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Msg extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
			$this->lang->load('admin_msg');
	        $this->CsdjAdmin->Admin_Login();
	}

    //˽���б�
	public function index()
	{
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

	        $data['page'] = $page;
	        $data['zd'] = $zd;
	        $data['key'] = $key;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."msg where 1=1";
			if(!empty($key)){
				 if($zd=='name'){
	                   $sql_string.= " and name like '%".str_replace('%','',$key)."%'";
				 }else{
				     if($zd=='user'){
                         $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				     }else{
                         $uid=$key;
				     }
				     $sql_string.= " and (uida=".intval($uid)." or uidb=".intval($uid).")";
				 }
			}

	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('msg')."?zd=".$zd."&key=".$key;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['msg'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('msg.html',$data);
	}

    //�Ķ�˽��
	public function look()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_01'),'javascript:history.back();','no');  //���ݲ����
            $row=$this->db->query("SELECT name,neir FROM ".CS_SqlPrefix."msg where id=".$id."")->row();
			if(!$row) admin_msg(L('plub_02'),'javascript:history.back();','no');  //��¼������
            $data['msg']=$row;
            $this->load->view('msg_look.html',$data);
	}
	
	//ɾ��˽��
	public function del()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_03'),'javascript:history.back();','no');  //���ݲ����
			$this->CsdjDB->get_del('msg',$id);
            admin_msg(L('plub_04'),site_url('msg'),'ok');  //�����ɹ�
	}
}

