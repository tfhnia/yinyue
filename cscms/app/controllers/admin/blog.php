<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
			$this->lang->load('admin_blog');
	        $this->CsdjAdmin->Admin_Login();
	}

    //说说列表
	public function index()
	{
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true,true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

	        $data['page'] = $page;
	        $data['zd'] = $zd;
	        $data['key'] = $key;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."blog where 1=1";
			if(!empty($key)){
				 if($zd=='name'){
                     $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				 }else{
                     $uid=$key;
				 }
				 $sql_string.= " and uid=".intval($uid)."";
			}

	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('blog')."?zd=".$zd."&key=".$key;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // 总页数
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['blog'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //获取分页类

            $this->load->view('blog.html',$data);
	}

    //删除说说
	public function del()
	{
            $id = $this->input->get_post('id',true,true);
			if(empty($id)) admin_msg(L('plub_01'),'javascript:history.back();','no');  //数据不完成
			$this->CsdjDB->get_del('blog',$id);
            admin_msg(L('plub_02'),site_url('blog'),'ok');  //操作成功
	}
}

