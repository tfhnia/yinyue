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

    //������
	public function picding()
	{
		   $callback = $this->input->get('callback',true);
           $ac = $this->uri->segment(3);   //ac
           $id = intval($this->uri->segment(4));   //ID
		   $ding = $this->cookie->get_cookie("picding_id_".$id);
		   if($id==0){
                $error='IDΪ��';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='����û�е�¼';
		   }elseif(!empty($ding) && date('Y-m-d',$ding)==date('Y-m-d')){
                $error='�������Ѿ��޹���';
		   }else{
		        $row=$this->CsdjDB->get_row('pic_type','dhits,chits',$id);
		        if(!$row){
			        $error='���ݲ�����';
		        }else{
                        //��סcookie
	                    $this->cookie->set_cookie("picding_id_".$id,time(),time()+86400);
                        //���Ӷ�����
						if($ac=='ding'){
	                        $updata['dhits']=$row->dhits+1;
						}else{
	                        $updata['chits']=$row->chits+1;
						}
                        $this->CsdjDB->get_update('pic_type',$id,$updata);
						$error='ok';
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}
}
