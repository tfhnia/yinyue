<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-01-17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
          $this->load->library('user_agent');
          if(!$this->agent->is_referral()) show_error('�����ʵ�ҳ�治����~!',404,Web_Name.'������');
	      //�ر����ݿ⻺��
          $this->db->cache_off();
		  $this->load->model('CsdjUser');
	}

    //�ղ�
	public function vodfav()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //��ʽ
		   if($id==0){
                $error='IDΪ��';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='����û�е�¼';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','cid,name,shits',$id);
		        if(!$row){
			        $error='���ݲ�����';
		        }else{
					//�ж��Ƿ��ղ�
		            $rows=$this->db->query("SELECT id FROM ".CS_SqlPrefix."vod_fav where did=".$id." and uid=".$_SESSION['cscms__id']." and sid=0")->row();
		            if($rows){
			            $error='���Ѿ��ղ��˸���Ƶ';
					}else{
						$add['did']=$id;
						$add['cid']=$row->cid;
						$add['uid']=$_SESSION['cscms__id'];
						$add['sid']=0;
						$add['name']=$row->name;
						$add['addtime']=time();
						$this->CsdjDB->get_insert('vod_fav',$add);
                        //�����ղ�����
	                    $updata['shits']=$row->shits+1;
                        $this->CsdjDB->get_update('vod',$id,$updata);

					    //�������ض�̬
					    $dt['dir']='vod';
					    $dt['uid']=$_SESSION['cscms__id'];
					    $dt['did']=$id;
					    $dt['name']=$row->name;
					    $dt['link']=linkurl('show','id',$id,0,'vod');
					    $dt['title']='�ղ�����Ƶ';
					    $dt['addtime']=time();
					    $this->CsdjDB->get_insert('dt',$dt);

						$error='ok';
					}
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}

    //��
	public function vodding()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
		   $ding = $this->cookie->get_cookie("vodding_id_".$id);
		   if($id==0){
                $error='IDΪ��';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='����û�е�¼';
		   }elseif(!empty($ding)){
                $error='�������Ѿ��޹���';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','dhits',$id);
		        if(!$row){
			        $error='���ݲ�����';
		        }else{
                        //��סcookie
	                    $this->cookie->set_cookie("vodding_id_".$id,'ok',time()+86400);
                        //���Ӷ�����
	                    $updata['dhits']=$row->dhits+1;
                        $this->CsdjDB->get_update('vod',$id,$updata);
						$error='ok';
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}

    //��ȡ����
	public function vodpfen()
	{
		   $this->load->helper('vod');
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
		   $row=$this->CsdjDB->get_row('vod','phits,pfen',$id);
		   if(!$row){
			    $fen=0;
		   }else{
                $fen=getpf($row->pfen,$row->phits);
           }
           echo $callback."({fen:".json_encode($fen)."})";
	}

    //��Ƶ����
	public function vodpfenadd()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
           $fen = intval($this->uri->segment(4));   //��
		   $pfen = $this->cookie->get_cookie("vodpfen_id_".$id);
		   if($id==0 || $fen==0){
                $error='��������';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='����û�е�¼';
		   }elseif(!empty($pfen)){
                $error='���Ѿ���������';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','phits,pfen',$id);
		        if(!$row){
			        $error='���ݲ�����';
		        }else{
                        //��סcookie
	                    $this->cookie->set_cookie("vodpfen_id_".$id,'ok',time()+86400*30);
                        //�������֡�����
	                    $updata['phits']=$row->phits+1;
	                    $updata['pfen']=$row->pfen+$fen;
                        $this->CsdjDB->get_update('vod',$id,$updata);
						$error='ok';
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}
}
