<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-16
 */
class Pic extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('pic');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

	public function index()
	{
            $cid  = intval($this->input->get_post('cid'));
            $sid  = intval($this->input->get_post('sid'));
            $yid  = intval($this->input->get_post('yid'));
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

	        $data['page'] = $page;
	        $data['cid'] = $cid;
	        $data['sid'] = $sid;
	        $data['yid'] = $yid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."pic where 1=1";
			if($yid==1){
				 $sql_string.= " and yid=0";
			}
			if($yid==2){
				 $sql_string.= " and yid=1";
			}
			if($yid==3){
				 $sql_string.= " and hid=1";
			}else{
				 $sql_string.= " and hid=0";
			}
			if($cid>0){
	             $sql_string.= " and cid=".$cid."";
			}
			if($sid>0){
	             $sql_string.= " and sid=".$sid."";
			}
			if(!empty($key)){
				if($zd=='id' || $zd=='sid'){
				     $sql_string.= " and ".$zd."=".$key."";
				}else{
				     $sql_string.= " and ".$zd." like '%".$key."%'";
				}
			}
	        $sql_string.= " order by id desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pic/admin/pic')."?yid=".$yid."&zd=".$zd."&key=".$key."&cid=".$cid."&sid=".$sid;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['pic'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('pic.html',$data);
	}

    //�Ƽ�����������
	public function init()
	{
            $ac  = $this->input->get_post('ac',true);
            $id   = intval($this->input->get_post('id'));
            $sid  = intval($this->input->get_post('sid'));
            if($ac=='zt'){ //����
                  $edit['yid']=$sid;
				  if($sid==0) $this->dt($id);
				  $str=($sid==0)?'<a title="�������" href="javascript:get_cmd(\''.site_url('pic/admin/pic/init').'?sid=1\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/ok.gif" /></a>':'<a title="����������" href="javascript:get_cmd(\''.site_url('pic/admin/pic/init').'?sid=0\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/no.gif" /></a>';
			}
            $this->CsdjDB->get_update('pic',$id,$edit);
            die($str);
	}

    //ͼƬ�������޸�
	public function edit()
	{
            $id   = intval($this->input->get('id'));
			if($id==0){
                $data['id']=0;
                $data['cid']=0;
                $data['sid']=0;
                $data['yid']=0;
                $data['pic']='';
                $data['user']='';
                $data['content']='';
			}else{
                $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."pic where id=".$id."")->row(); 
			    if(!$row) admin_msg('������¼������~!','javascript:history.back();','no');  //��¼������

                $data['id']=$row->id;
                $data['cid']=$row->cid;
                $data['yid']=$row->yid;
                $data['sid']=$row->sid;
                $data['pic']=$row->pic;
                $data['user']=getzd('user','name',$row->uid);
                $data['content']=$row->content;
			}
            $this->load->view('pic_edit.html',$data);
	}

    //ͼƬ����
	public function save()
	{
            $id   = intval($this->input->post('id'));
            $sid = intval($this->input->post('sid'));
            $cid = intval($this->input->post('cid'));
            $content = remove_xss($this->input->post('content'));
            $addtime = $this->input->post('addtime',true);
            $pic = $this->input->post('pic',true);
            $user = $this->input->post('user',true);

            if($sid==0 || $cid==0 || empty($pic)){
                   admin_msg('��Ǹ��������ᡢ���ࡢͼƬ��ַ����Ϊ��~!','javascript:history.back();','no');
			}

            $data['cid']=$cid;
            $data['yid']=intval($this->input->post('yid'));
            $data['sid']=$sid;
            $data['pic']=$pic;
            $data['uid']=intval(getzd('user','id',$user,'name'));
            $data['content']=$content;

			if($id==0){ //����
				 $data['addtime']=time();
                 $this->CsdjDB->get_insert('pic',$data);
			}else{
				 if($data['yid']==0) $this->dt($id);
                 if($addtime=='ok') $data['addtime']=time();
                 $this->CsdjDB->get_update('pic',$id,$data);
			}
            admin_msg('��ϲ���������ɹ�~!',site_url('pic/admin/pic'),'ok');  //�����ɹ�
	}

    //ͼƬɾ��
	public function del()
	{
            $yid = intval($this->input->get('yid'));
            $ids = $this->input->get_post('id');
            $ac = $this->input->get_post('ac');
			//����վ
			if($ac=='hui'){
			     $result=$this->db->query("SELECT id,pic FROM ".CS_SqlPrefix."pic where hid=1")->result();
			     $this->load->library('csup');
			     foreach ($result as $row) {
                     if(!empty($row->pic) && substr($row->pic,0,7)!='http://'){
					    $this->csup->del($row->pic,'pic'); //ɾ��ͼƬ
				     }
					 $this->CsdjDB->get_del('pic',$row->id);
				 }
                 admin_msg('��ϲ��������վ��ճɹ�~!','javascript:history.back();','ok');  //�����ɹ�
			}
			if(empty($ids)) admin_msg('��ѡ��Ҫɾ��������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			if($yid==3){
			     $result=$this->db->query("SELECT id,pic FROM ".CS_SqlPrefix."pic where id in(".$idss.")")->result();
			     $this->load->library('csup');
			     foreach ($result as $row) {
                     if(!empty($row->pic) && substr($row->pic,0,7)!='http://'){
					    $this->csup->del($row->pic,'pic'); //ɾ��ͼƬ
				     }
				 }
			     $this->CsdjDB->get_del('pic',$ids);
                 admin_msg('��ϲ����ɾ���ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
			}else{
				 //��ȥ��ҡ�����
				 if(is_array($ids)){
				     foreach ($ids as $id) $this->dt($id,1);
				 }else{
					 $this->dt($ids,1);
				 }
				 $data['hid']=1;
			     $this->CsdjDB->get_update('pic',$ids,$data);
                 admin_msg('��ϲ���������Ѿ��ƶ�������վ~!','javascript:history.back();','ok');  //�����ɹ�
			}
	}

    //ͼƬ��ԭ
	public function hy()
	{
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('��ѡ��Ҫ��ԭ������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$data['hid']=0;
			$this->CsdjDB->get_update('pic',$ids,$data);
            admin_msg('��ϲ�������ݻ�ԭ�ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}

    //ѡ������б�
	public function res()
	{
 	        $page = intval($this->input->get('page'));
			$sid = intval($this->uri->segment(4));
            if($page==0) $page=1;
	        $data['page'] = $page;
	        $data['sid'] = $sid;
            $sql_string = "SELECT * FROM ".CS_SqlPrefix."pic_type where yid=0 and hid=0 order by id desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pic/admin/pic/res/'.$sid);
	        $per_page = 8; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['pic_type'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��
            $this->load->view('pic_res.html',$data);
	}

	//���ͼƬ���ӻ��֡����顢ͬʱ��̬��ʾ
	public function dt($id,$yid=0)
	{
		    $sid=getzd('pic','sid',$id);
			$dt=$this->db->query("SELECT id,name,yid FROM ".CS_SqlPrefix."dt where link='".linkurl('show','id',$sid,1,'pic')."'")->row();
			if($dt){
                  $uid=getzd('pic','uid',$id);
				  if($yid>0){ //ɾ������վ

					  $str='';
					  if(User_Jinyan_Del>0){
					      $jinyan=getzd('user','jinyan',$uid);
						  if( User_Jinyan_Del <= $jinyan){
							  $str['jinyan']=$jinyan-User_Jinyan_Del;
						  }
					  }
					  if(User_Cion_Del>0){
					      $cion=getzd('user','cion',$uid);
						  if( User_Jinyan_Del <= $jinyan){
							  $str['cion']=$cion-User_Cion_Del;
						  }
					  }
					  if($str!=''){
			              $this->CsdjDB->get_update('user',$uid,$str);
					  }
				      //����ͼƬɾ��֪ͨ
				      $add['uida']=$uid;
				      $add['uidb']=0;
				      $add['name']='ͼƬ��ɾ��';
				      $add['neir']='����ͼƬ��'.$dt->name.'����ɾ����ϵͳͬʱ�۳���'.User_Cion_Del.'����ң�'.User_Jinyan_Del.'������';
				      $add['addtime']=time();
            	      $this->CsdjDB->get_insert('msg',$add);
					  //ɾ����̬
				      $this->CsdjDB->get_del('dt',$dt->id);

				  }elseif($dt->yid==1){ //���

			          $addhits=getzd('user','addhits',$uid);
				      $str='';
				      if($addhits<User_Nums_Add){
                         $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$uid."");
					     $str.=L('plub_99');
				      }
                      $this->db->query("update ".CS_SqlPrefix."dt set yid=0,addtime='".time()."' where id=".$dt->id."");
				      //����ͼƬ���֪ͨ
				      $add['uida']=$uid;
				      $add['uidb']=0;
				      $add['name']='ͼƬ���֪ͨ';
				      $add['neir']='��ϲ��������ͼƬ��'.$dt->name.'���Ѿ����ͨ����'.$str.'��л����֧��~~';
				      $add['addtime']=time();
            	      $this->CsdjDB->get_insert('msg',$add);
				  }
			}
	}
}
