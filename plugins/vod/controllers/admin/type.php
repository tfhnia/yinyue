<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-03
 */
class Type extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('vod');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

    //��Ƶ����
	public function index()
	{
            $sql_string = "SELECT id,name FROM ".CS_SqlPrefix."vod_list where fid=0 order by xid asc";
	        $query = $this->db->query($sql_string); 
	        $data['vod_type'] = $query->result();
            $this->load->view('vod_type.html',$data);
	}

    //��ʾ�����ز���
	public function init()
	{
            $ac  = $this->input->get_post('ac',true);
            $id   = intval($this->input->get_post('id'));
            $sid  = intval($this->input->get_post('sid'));
            if($ac=='zt'){ //��ʾ
                  $edit['yid']=$sid;
				  $str=($sid==0)?'<a title="�������" href="javascript:get_cmd(\''.site_url('vod/admin/lists/init').'?sid=1\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/ok.gif" /></a>':'<a title="�����ʾ" href="javascript:get_cmd(\''.site_url('vod/admin/lists/init').'?sid=0\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/no.gif" /></a>';
			}
            $this->CsdjDB->get_update('vod_type',$id,$edit);
            die($str);
	}

    //�����޸�
	public function plsave()
	{
            $ids=$this->input->post('id', TRUE); 
            if(empty($ids)){
                    admin_msg('��ѡ��Ҫ����������~!','javascript:history.back();','no');
			}
			foreach ($ids as $id) {
		            $data['name']=$this->input->post('name_'.$id, TRUE);
		            $data['xid']=intval($this->input->post('xid_'.$id, TRUE)); 
                    $this->CsdjDB->get_update('vod_type',$id,$data);
			}
            admin_msg('��ϲ���������ɹ�~!',site_url('vod/admin/type'),'ok');  //�����ɹ�
	}

    //�����������޸�
	public function edit()
	{
            $id   = intval($this->input->get('id'));
            $cid  = intval($this->input->get('cid'));
			if($id==0){
                $data['id']=0;
                $data['cid']=$cid;
                $data['yid']=0;
                $data['xid']=0;
                $data['name']='';
			}else{
                $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."vod_type where id=".$id."")->row(); 
			    if(!$row) admin_msg('������¼������~!','javascript:history.back();','no');  //��¼������

                $data['id']=$row->id;
                $data['cid']=$row->cid;
                $data['yid']=$row->yid;
                $data['xid']=$row->xid;
                $data['name']=$row->name;

			}
            $this->load->view('type_edit.html',$data);
	}

    //���鱣��
	public function save()
	{
            $id   = intval($this->input->post('id'));
            $data['yid']=intval($this->input->post('yid'));
            $data['cid']=intval($this->input->post('cid'));
            $data['xid']=intval($this->input->post('xid'));
            $data['name']=$this->input->post('name',true);

			if($id==0){ //����
                 $this->CsdjDB->get_insert('vod_type',$data);
			}else{
                 $this->CsdjDB->get_update('vod_type',$id,$data);
			}
            admin_msg('��ϲ���������ɹ�~!',site_url('vod/admin/type'),'ok');  //�����ɹ�
	}

    //����ɾ��
	public function del()
	{
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('��ѡ��Ҫɾ��������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$this->CsdjDB->get_del('vod_type',$ids);
            admin_msg('��ϲ����ɾ���ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}
}

