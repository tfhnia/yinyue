<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-10-03
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Label extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
			$this->lang->load('admin_label');
	        $this->CsdjAdmin->Admin_Login();
	}

	public function index()
	{
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."label order by addtime desc";
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            $base_url = site_url('label/index');
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['label'] = $query->result();
	        $data['page'] = $page;
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('label.html',$data);
	}

    //����
	public function add()
	{
            $data['id']=0;
            $data['name']='';
            $data['selflable']='';
            $data['neir']='';
            $this->load->view('label_edit.html',$data);
	}

    //�޸�
	public function edit()
	{
            $id   = intval($this->input->get('id'));
            $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."label where id=".$id."")->row(); 
			if(!$row) admin_msg(L('plub_01'),site_url('label'),'no');  //��¼������

            $data['id']=$row->id;
            $data['name']=$row->name;
            $data['selflable']=$row->selflable;
            $data['neir']=$row->neir;

            $this->load->view('label_edit.html',$data);
	}

    //���
	public function save()
	{
            $id=intval($this->input->post('id'));
            $data['name']=$this->input->post('name',true);
            $data['neir']=$this->input->post('neir',true);
            $data['selflable']=str_encode($this->input->post('selflable'));
            $data['addtime']=time();
			if(empty($data['name'])) admin_msg(L('plub_02'),'javascript:history.back();','no');  //���⡢��ַ����Ϊ��

			if($id==0){ //����
                 $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."label where name='".$data['name']."'")->row(); 
			     if($row) admin_msg(L('plub_03'),'javascript:history.back();','no');  //��ǩ�����Ѿ�����
                 $this->CsdjDB->get_insert('label',$data);
			}else{
                 $this->CsdjDB->get_update('label',$id,$data);
			}
            admin_msg(L('plub_04'),site_url('label'),'ok');  //�����ɹ�
	}

    //ɾ��
	public function del()
	{
            $id = $this->input->get_post('id');
			if(empty($id)) admin_msg(L('plub_05'),'javascript:history.back();','no');
			$this->CsdjDB->get_del('label',$id);
            admin_msg(L('plub_06'),'javascript:history.back();','ok');  //�����ɹ�
	}

    //JS��ǩ
	public function js()
	{
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."ads order by addtime desc";
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            $base_url = site_url('label/js');
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['label'] = $query->result();
	        $data['page'] = $page;
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('label_js.html',$data);
	}

    //JS��ǩ���
	public function js_look()
	{
            $js=$this->input->get('js',true);
			echo "<script src='".Web_Path."attachment/js/".$js.".js'></script>";
	}

    //JS��ǩ����
	public function js_add()
	{
		    $this->load->helper('string');
            $data['id']=0;
            $data['name']='';
            $data['html']='';
            $data['js']=date('YmdHis').random_string('numeric', 5);
            $data['neir']='';
            $this->load->view('label_js_edit.html',$data);
	}

    //JS��ǩ�޸�
	public function js_edit()
	{
            $id   = intval($this->input->get('id'));
            $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."ads where id=".$id."")->row(); 
			if(!$row) admin_msg(L('plub_01'),site_url('label/js'),'no');  //��¼������

            $data['id']=$row->id;
            $data['name']=$row->name;
            $data['html']=$row->html;
            $data['js']=$row->js;
            $data['neir']=$row->neir;

            $this->load->view('label_js_edit.html',$data);
	}

    //JS��ǩ���
	public function js_save()
	{
            $id=intval($this->input->post('id'));
            $data['name']=$this->input->post('name',true);
            $data['neir']=$this->input->post('neir',true);
            $data['html']=str_encode($this->input->post('html'));
            $data['js']=$this->input->post('js',true);
            $data['addtime']=time();

			if(empty($data['name']) || empty($data['js'])) admin_msg(L('plub_15'),'javascript:history.back();','no');  //���⡢��ַ����Ϊ��

			if($id==0){ //����
                 $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."ads where name='".$data['name']."'")->row(); 
			     if($row) admin_msg(L('plub_03'),'javascript:history.back();','no');  //��ǩ�����Ѿ�����
                 $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."ads where js='".$data['js']."'")->row(); 
			     if($row) admin_msg(L('plub_08'),'javascript:history.back();','no');  //��ǩJS�ļ����Ѿ�����

                 $this->CsdjDB->get_insert('ads',$data);
			}else{
                 $this->CsdjDB->get_update('ads',$id,$data);
			}
            $strs=htmltojs($this->input->post('html'));
            //д�ļ�
            if (!write_file('.'.Web_Path.'attachment/js/'.$data['js'].'.js', $strs)){
                     admin_msg(L('plub_09'),'javascript:history.back();','no');
            }
            admin_msg(L('plub_04'),site_url('label/js'),'ok');  //�����ɹ�
	}

    //JS��ǩɾ��
	public function js_del()
	{
            $id = $this->input->get_post('id');
			if(empty($id)) admin_msg(L('plub_05'),'javascript:history.back();','no');

			//ɾ���ļ�
			if(is_array($id)){
			   foreach ($id as $ids) {
				    $row=$this->db->query("SELECT js FROM ".CS_SqlPrefix."ads where id='".$ids."'")->row();
				    if($row){
                        $jsurl='.'.Web_Path.'attachment/js/'.$row->js.'.js';
				        @unlink($jsurl);
				    }
			   }
			}else{
				    $row=$this->db->query("SELECT js FROM ".CS_SqlPrefix."ads where id='".$id."'")->row();
				    if($row){
                        $jsurl='.'.Web_Path.'attachment/js/'.$row->js.'.js';
				        @unlink($jsurl);
				    }
			}
			$this->CsdjDB->get_del('ads',$id);
            admin_msg(L('plub_06'),'javascript:history.back();','ok');  //�����ɹ�
	}

    //ҳ���ǩ
	public function page()
	{
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."page order by addtime desc";
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            $base_url = site_url('label/page');
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['label'] = $query->result();
	        $data['page'] = $page;
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('label_page.html',$data);
	}

    //ҳ���ǩ����
	public function page_add()
	{
            $data['id']=0;
            $data['sid']=0;
            $data['name']='';
            $data['html']='';
            $data['url']=Web_Path.'diy/'.date('YmdHis').'.html';
            $data['neir']='';
            $this->load->view('label_page_edit.html',$data);
	}

    //ҳ���ǩ�޸�
	public function page_edit()
	{
            $id   = intval($this->input->get('id'));
            $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."page where id=".$id."")->row(); 
			if(!$row) admin_msg(L('plub_01'),site_url('label/page'),'no');  //��¼������

            $data['id']=$row->id;
            $data['sid']=$row->sid;
            $data['name']=$row->name;
            $data['html']=$row->html;
            $data['url']=$row->url;
            $data['neir']=$row->neir;

            $this->load->view('label_page_edit.html',$data);
	}

    //ҳ���ǩ���
	public function page_save()
	{
            $id=intval($this->input->post('id'));
            $data['sid']=intval($this->input->post('sid'));
            $data['name']=$this->input->post('name',true);
            $data['neir']=$this->input->post('neir',true);
            $data['html']=str_encode($this->input->post('html'));
            $data['addtime']=time();
            $url=$this->input->post('url',true);

			if(empty($data['name'])) admin_msg(L('plub_02'),'javascript:history.back();','no');  //���ⲻ��Ϊ��

            if($data['sid']==1){ //��̬
			      if(empty($url)) admin_msg(L('plub_11'),'javascript:history.back();','no');  //URL��ַ����Ϊ��
                  $file_ext = strtolower(trim(substr(strrchr($url, '.'), 1)));
				  if($file_ext!='html' && $file_ext!='htm' && $file_ext!='shtm' && $file_ext!='shtml' && $file_ext!='xml') {
                           admin_msg(L('plub_12'),'javascript:history.back();','no'); //��׺�Ƿ�
			      }
                  $data['url']=$url;
			}else{
                  $data['url']=Web_Path.'index.php/page/index/'.$data['name'];
			}

			if($id==0){ //����
                 $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."page where name='".$data['name']."'")->row(); 
			     if($row) admin_msg(L('plub_03'),'javascript:history.back();','no');  //��ǩ�����Ѿ�����

                 $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."page where url='".$data['url']."'")->row(); 
			     if($row) admin_msg(L('plub_13'),'javascript:history.back();','no');  //��ǩURL��ַ�Ѿ�����

                 $this->CsdjDB->get_insert('page',$data);
			}else{
                 $this->CsdjDB->get_update('page',$id,$data);
			}
            admin_msg(L('plub_04'),site_url('label/page'),'ok');  //�����ɹ�
	}

    //ҳ���ǩ��̬����
	public function page_html()
	{
            $id=intval($this->input->get('id'));
			$row=$this->CsdjDB->get_row_arr('page','url,html,name',$id);
			if($row){
                    $path=str_replace("//","/",FCPATH.$row['url']);
					$this->load->model('CsdjTpl');
					$neir=$this->CsdjTpl->page($row,true);
                    if (!write_file($path, $neir)){
                           echo 'no';
                    }else{
                           echo 'ok';
					}
			}
	}

    //ҳ���ǩɾ��
	public function page_del()
	{
            $id = $this->input->get_post('id');
			if(empty($id)) admin_msg(L('plub_05'),'javascript:history.back();','no');

			//ɾ���ļ�
			if(is_array($id)){
			   foreach ($id as $ids) {
				    $row=$this->db->query("SELECT sid,url FROM ".CS_SqlPrefix."page where id='".$ids."'")->row();
				    if($row && $row->sid==1){
                        $html='.'.$row->url;
				        @unlink($html);
				    }
			   }
			}else{
				    $row=$this->db->query("SELECT sid,url FROM ".CS_SqlPrefix."page where id='".$id."'")->row();
				    if($row && $row->sid==1){
                        $html='.'.$row->url;
				        @unlink($html);
				    }
			}

			$this->CsdjDB->get_del('page',$id);
            admin_msg(L('plub_06'),'javascript:history.back();','ok');  //�����ɹ�
	}

	//����ɾ������
	public function deldata()
	{
		    //���а��
            $sql_string = "SELECT dir,name FROM ".CS_SqlPrefix."plugins order by id asc";
            $query = $this->db->query($sql_string); 
		    $data['plugins']=$query->result();
            $this->load->view('label_deldata.html',$data);
	}

	//��ձ�����
	public function deldata_save()
	{
            $dir = $this->input->post('dir',true);
            $table = $this->input->post('table_'.$dir,true);
            $ids = $this->input->post('ids',true);

			if(empty($table)) admin_msg(L('plub_14'),'javascript:history.back();','no');

            $this->db->query("delete from ".CS_SqlPrefix.$table." ");
			//�޸�����ID
			if($ids=='ok'){
			     $this->db->query("TRUNCATE TABLE ".CS_SqlPrefix.$table." ");
			}
            admin_msg(L('plub_04'),'javascript:history.back();','ok');  //�����ɹ�
	}

	//�����޸�����
	public function editdata()
	{
		    //���а��
            $sql_string = "SELECT dir,name FROM ".CS_SqlPrefix."plugins order by id asc";
            $query = $this->db->query($sql_string); 
		    $data['plugins']=$query->result();
            $this->load->view('label_editdata.html',$data);
	}

	//�޸����ݲ���
	public function editdata_save()
	{
            $dir = $this->input->post('dir',true);
            $table = $this->input->post('table_'.$dir,true);
            $field = $this->input->post('field',true);
            $old = $this->input->post('old',true,true);
            $new = $this->input->post('new',true,true);

			if(empty($table) || $old===FALSE) admin_msg(L('plub_16'),'javascript:history.back();','no');

            $sql="select id,".$field." from ".CS_SqlPrefix.$table." where ".$field." like '%".$old."%'";
			$result=$this->db->query($sql);
			foreach ($result->result() as $row) { 
				$newneir=str_replace($old,$new,$row->$field);
				$sql3="update ".CS_SqlPrefix.$table." set ".$field."='".$newneir."' where id=".$row->id."";
				$this->db->query($sql3);
			}
            admin_msg(L('plub_17'),'javascript:history.back();','ok');  //�����ɹ�
	}

	//�г����ֶ�
	public function fields()
	{
			$this->load->model('CsdjBackup');
			$table = $this->input->get('table',true);
			$table = $this->CsdjBackup->repair(CS_SqlPrefix.$table);
			$arr=explode('auto_increment', $table);
            if(empty($arr[1])){				
			    $arr=explode('AUTO_INCREMENT', strtoupper($table));
			}
            preg_match_all('/`([\s\S]+?)` ([\s\S]+?) COMMENT \'([\s\S]+?)\',/',$arr[1],$tarr);
			$str='<select class="select1" name="field">';
            foreach ($tarr[1] as $k=>$v) {
					$str.='<option value="'.$tarr[1][$k].'">'.$tarr[3][$k].'</option>';
			}
			$str.='</select>';
			echo $str;
	}
}