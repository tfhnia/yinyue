<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-09
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hits extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
          $this->load->library('user_agent');
          if(!$this->agent->is_referral()) show_error('�����ʵ�ҳ�治����~!',404,Web_Name.'������');
	      //�ر����ݿ⻺��
          $this->db->cache_off();
		  $this->load->helper('vod');
	}

    //���Ӳ�������
	public function ids()
	{
           $id = intval($this->uri->segment(3));   //ID			
           $op = $this->uri->segment(4);   //��ʽ
		   $zd=($op=='topic')?'vod_topic':'vod';
		   $row=$this->CsdjDB->get_row($zd,'rhits,zhits,yhits,hits',$id);
		   if(!$row){
			   exit();
		   }
           //��������
	       $updata['rhits']=$row->rhits+1;
	       $updata['zhits']=$row->zhits+1;
	       $updata['yhits']=$row->yhits+1;
	       $updata['hits']=$row->hits+1;
           $this->CsdjDB->get_update($zd,$id,$updata);

		  //���������
          $month=@file_get_contents(APPPATH."config/month.txt");
		  if($month!=date('m')){
			    $this->db->query("update ".CS_SqlPrefix."vod set yhits=0");
			    $this->db->query("update ".CS_SqlPrefix."vod_topic set yhits=0");
                write_file(APPPATH."config/month.txt",date('m'));
		  }
		  //���������
		  $week=@file_get_contents(APPPATH."config/week.txt");
		  if($week!=date('W',time())){
			    $this->db->query("update ".CS_SqlPrefix."vod set zhits=0");
			    $this->db->query("update ".CS_SqlPrefix."vod_topic set zhits=0");
                write_file(APPPATH."config/week.txt",date('W',time()));
		  }
		  //���������
		  $day=@file_get_contents(APPPATH."config/day.txt");
		  if($day!=date('d')){
			    $this->db->query("update ".CS_SqlPrefix."vod set rhits=0");
			    $this->db->query("update ".CS_SqlPrefix."vod_topic set rhits=0");
                write_file(APPPATH."config/day.txt",date('d'));
		  }
	}

    //��̬��������
	public function dt()
	{
           $op = $this->uri->segment(3);   //����
           $id = intval($this->uri->segment(4));   //ID
           $type = $this->uri->segment(5);   //ID
		   $zd=($type=='topic')?'vod_topic':'vod';

		   $dos = array('hits', 'yhits', 'zhits', 'rhits', 'shits', 'xhits', 'dhits', 'chits', 'pfenbi', 'pfen');
           $op= (!empty($op) && in_array($op, $dos))?$op:'hits';
           $ops=($op=='pfenbi' || $op=='pfen')?'phits,pfen':$op;
		   $row=$this->CsdjDB->get_row($zd,$ops,$id);
		   if(!$row){
			        echo "document.write('0');";
		   }else{
					if($op=='pfen'){
                         echo "document.write('".getpf($row->pfen,$row->phits)."');";
					}elseif($op=='pfenbi'){
                         echo "document.write('".getpf($row->pfen,$row->phits,2)."');";
					}else{
                         echo "document.write('".$row->$op."');";
					}
		   }
	}
}
