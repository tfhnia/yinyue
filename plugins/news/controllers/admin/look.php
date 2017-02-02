<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-16
 */
class Look extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('news');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

    //�Ķ���¼�б�
	public function index()
	{
            $kstime = $this->input->get_post('kstime',true);
            $jstime = $this->input->get_post('jstime',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;
			$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
			$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
			if($kstimes>$jstimes) $kstimes=strtotime($kstime);

	        $data['key'] = $key;
	        $data['page'] = $page;
	        $data['kstime'] = $kstime;
	        $data['jstime'] = empty($jstime)?date('Y-m-d'):$jstime;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."news_look where 1=1";
			if(!empty($key)){
				 $sql_string.= " and name like '%".$key."%'";
			}
			if($kstimes>0){
	             $sql_string.= " and addtime>".$kstimes."";
			}
			if($jstimes>0){
	             $sql_string.= " and addtime<".$jstimes."";
			}
	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('news/admin/look')."?key=".$key."&kstime=".$kstime."&jstime=".$jstime;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['look'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('look.html',$data);
	}

    //��¼ɾ��
	public function del()
	{
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('��ѡ��Ҫɾ��������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$this->CsdjDB->get_del('news_look',$ids);
            admin_msg('��ϲ����ɾ���ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}

    //��¼����ɾ��
	public function pldel()
	{
            $id = intval($this->input->get('id'));
            if($id==3){
				 $times=time()-86400*90;
                 $this->db->query("delete from ".CS_SqlPrefix."news_look where addtime<".$times."");
			}else{
			     $this->db->query("delete from ".CS_SqlPrefix."news_look");
				 $this->db->query("TRUNCATE TABLE ".CS_SqlPrefix."news_look");
			}
            admin_msg('��ϲ����ɾ���ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}
}

