<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
		  $this->lang->load('home');
          $this->load->library('user_agent');
          if(!$this->agent->is_referral()) show_error(L('ajax_01'),404,Web_Name.L('ajax_02'));
	      //�ر����ݿ⻺��
          $this->db->cache_off();
		  $this->load->model('CsdjUser');
	}

    //�޻�Ա
	public function zan()
	{
		   $callback = $this->input->get('callback',true);
           $uid = (int)$this->uri->segment(4);
		   $error="";
		   if($uid==0){
               $error=L('ajax_03');
		   }elseif(!$this->CsdjUser->User_Login(1)){
               $error=L('ajax_04');
		   }elseif($uid==$_SESSION['cscms__id']){
               $error=L('ajax_05');
		   }else{
		       $row=$this->CsdjDB->get_row('user','id,zanhits',$uid);
		       if(!$row){
                    $error=L('ajax_06');
			   }else{
				    //�жϽ����Ƿ��޹�
					$zantime = $this->cookie->get_cookie('home_zan_'.$uid);
					if(!empty($zantime) && (int)$zantime>time()){
                         $error=L('ajax_07');
					}else{
					     //����������
	                     $updata['zanhits']=$row->zanhits+1;
                         $this->CsdjDB->get_update('user',$uid,$updata);
						 $this->cookie->set_cookie('home_zan_'.$uid,strtotime(date('Y-m-d 0:0:0',time()+86400)),time()+86400);
						 //֪ͨ
				         $addm['uida']=$uid;
				         $addm['uidb']=$_SESSION['cscms__id'];
				         $addm['name']=L('ajax_13');
				         $addm['neir']=vsprintf(L('ajax_12'),array($_SESSION['cscms__name']));
				         $addm['addtime']=time();
            	         $this->CsdjDB->get_insert('msg',$addm);
						 $error='ok';
					}
			   }
		   }
		   echo $callback."({error:".json_encode(get_bm($error,'gbk','utf-8'))."})";
	}

    //����Ƿ��ע
	public function fansinit()
	{
		   $callback = $this->input->get('callback',true);
           $uid = $this->input->get_post('uid',true);
		   $error="";
		   $gz=array();
		   if(empty($uid)){
               $error=L('ajax_03');
		   }elseif(!$this->CsdjUser->User_Login(1)){
               $error=L('ajax_04');
		   }else{
			   $all=explode(',',$uid);
			   for($j=0;$j<count($all);$j++){	
				   $uid=(int)$all[$j];
                   if($uid>0){
				        //�ж��Ƿ��ע
					    $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."friend where uidb=".$uid." and uida=".$_SESSION['cscms__id']."")->row();
					    if($row){ //�ѹ�ע
                             //�Ƿ��໥��ע
							 $row2=$this->db->query("SELECT id FROM ".CS_SqlPrefix."friend where uida=".$uid." and uidb=".$_SESSION['cscms__id']."")->row();
							 if($row2){
                                 $gz[$uid]=2;
							 }else{
                                 $gz[$uid]=1;
							 }
					    }
				   }
			   }
		   }
		   echo $callback."({error:".json_encode(get_bm($error,'gbk','utf-8')).",data:".json_encode($gz)."})";
	}

    //��ע
	public function fans()
	{
		   $callback = $this->input->get('callback',true);
           $uid = (int)$this->uri->segment(4);   //ID	
           $sid = intval($this->uri->segment(5));   //SID
		   if($uid==0){
               $error=L('ajax_03');
		   }elseif(!$this->CsdjUser->User_Login(1)){
               $error=L('ajax_04');
		   }elseif($uid==$_SESSION['cscms__id']){
               $error=L('ajax_08');
		   }else{
		       $rowu=$this->CsdjDB->get_row('user','id',$uid);
		       if(!$rowu){
                    $error=L('ajax_06');
			   }else{
				    //�ж��Ƿ��ע
					$row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."friend where uidb=".$uid." and uida=".$_SESSION['cscms__id']."")->row();
					if($row){ //�ѹ�ע����
						 if($sid==0){
                             $this->CsdjDB->get_del('friend',$row->id);
						 }
						 $error='del';
					}else{ 
						 //������ע
                         $add['uidb']=$uid;
                         $add['uida']=$_SESSION['cscms__id'];
                         $add['addtime']=time();
						 $this->CsdjDB->get_insert('friend',$add);

                         //ͬһ��Ա24Сʱ��ֻ����һ����Ϣ
                         if($this->cookie->get_cookie('fans_add_'.$uid)!='ok'){
				            $addm['uida']=$uid;
				            $addm['uidb']=$_SESSION['cscms__id'];
				            $addm['name']=L('ajax_13');
				            $addm['neir']=vsprintf(L('ajax_11'),array($_SESSION['cscms__name']));
				            $addm['addtime']=time();
            	            $this->CsdjDB->get_insert('msg',$addm);
                            $this->cookie->set_cookie("fans_add_".$uid,"ok",time()+86400);
						 }

						 //�жϷ�˿�Ƿ����
						 $rows=$this->db->query("SELECT id FROM ".CS_SqlPrefix."fans where uida=".$uid." and uidb=".$_SESSION['cscms__id']."")->row();
						 if(!$rows){
						     //���ӷ�˿
                             $add2['uida']=$uid;
                             $add2['uidb']=$_SESSION['cscms__id'];
                             $add2['addtime']=time();
						     $this->CsdjDB->get_insert('fans',$add2);
						 }
						 $error='ok';
					}
			   }
		   }
		   echo $callback."({error:".json_encode(get_bm($error,'gbk','utf-8'))."})";
	}

    //�������Ƿ��ղ�
	public function favinit()
	{
		   $callback = $this->input->get('callback',true);
           $did = $this->input->get_post('did',true);
		   $error="";
		   $fav=array();
		   if(empty($did)){
               $error=L('ajax_03');
		   }elseif(!$this->CsdjUser->User_Login(1)){
               $error=L('ajax_04');
		   }else{
			   $all=explode(',',$did);
			   for($j=0;$j<count($all);$j++){	
				   $did=(int)$all[$j];
                   if($did>0){
				        //�ж��Ƿ��ע
					    $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dance_fav where sid=1 and did=".$did." and uid=".$_SESSION['cscms__id']."")->row();
					    if($row){ //���ղ�
                             $fav[$did]=1;
					    }
				   }
			   }
		   }
		   echo $callback."({error:".json_encode(get_bm($error,'gbk','utf-8')).",data:".json_encode($fav)."})";
	}

    //�ղظ���
	public function fav()
	{
		   $callback = $this->input->get('callback',true);
           $did = (int)$this->uri->segment(4);   //ID	
		   if($did==0){
               $error=L('ajax_03');
		   }elseif(!$this->CsdjUser->User_Login(1)){
               $error=L('ajax_04');
		   }else{
		       $rowd=$this->CsdjDB->get_row('dance','id,name,cid,shits',$did);
		       if(!$rowd){
                    $error=L('ajax_09');
			   }else{
				    //�ж��Ƿ��ղ�
					$row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dance_fav where did=".$did." and uid=".$_SESSION['cscms__id']." and sid=1")->row();
					if($row){ //���ղ�����
						 $this->CsdjDB->get_del('dance_fav',$row->id);
						 $error='del';
					}else{ 
						 //����
                         $add['did']=$did;
                         $add['cid']=$rowd->cid;
                         $add['name']=$rowd->name;
                         $add['did']=$did;
                         $add['uid']=$_SESSION['cscms__id'];
                         $add['addtime']=time();
						 $this->CsdjDB->get_insert('dance_fav',$add);

                         //�����ղ�����
	                     $updata['shits']=$rowd->shits+1;
                         $this->CsdjDB->get_update('dance',$did,$updata);

						 //�ж϶�̬�Ƿ����
						 $rows=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dt where did=".$did." and uid=".$_SESSION['cscms__id']." and dir='dance' and link='".linkurl('play','id',$did,0,'dance')."'")->row();
						 if(!$rows){
						     //���Ӷ�̬
						     $add2['dir']='dance';
						     $add2['uid']=$_SESSION['cscms__id'];
						     $add2['did']=$did;
						     $add2['name']=$rowd->name;
						     $add2['link']=linkurl('play','id',$did,0,'dance');
						     $add2['title']=L('ajax_10');
						     $add2['addtime']=time();
						     $this->CsdjDB->get_insert('dt',$add2);
						 }
						 $error='ok';
					}
			   }
		   }
		   echo $callback."({error:".json_encode(get_bm($error,'gbk','utf-8'))."})";
	}

}
