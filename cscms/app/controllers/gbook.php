<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-25
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gbook extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
	}

    //����
	public function index()
	{
            $this->CsdjTpl->gbook();
	}

    //�����б�
	public function lists($page=1)
	{
		    //�ر����ݿ⻺��
            $this->db->cache_off();
		    $callback = $this->input->get('callback',true);
            $Mark_Text=$this->CsdjTpl->gbook_list($page);
			$Mark_Text=get_bm($Mark_Text,'gbk','utf-8');
			echo $callback."({str:".json_encode($Mark_Text)."})";
	}

    //��������
	public function add()
	{
		    //�ر����ݿ⻺��
            $this->db->cache_off();
			$token=$this->input->post('token', TRUE);
			$add['neir']=$this->input->post('neir', TRUE);
			$add['neir']=filter(get_bm($add['neir']));
			if(User_BookFun==0){
                 $error='10000';
			}elseif(!get_token('gbook_token',1,$token)){
                 $error='10001';
			}elseif(empty($add['neir'])){
                 $error='10002';
			}else{

                $add['uidb']=isset($_SESSION['cscms__id'])?intval($_SESSION['cscms__id']):0;
			    $add['cid']=1;
			    $add['ip']=getip();
			    $add['addtime']=time();

                $ids=$this->CsdjDB->get_insert('gbook',$add);
			    if(intval($ids)==0){
                    $error='10003'; //ʧ��
				}else{
                    //�ݻ�token
			        get_token('gbook_token',2);
                    $error='10004';
				}
			}
			$data['error']=$error;
			echo json_encode($data);
	}
}
