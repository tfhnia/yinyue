<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-01
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->library('user_agent');
            $this->load->library('card');
		    $this->load->model('CsdjAdmin');
	}

	public function index()
	{
           $this->load->view('login.html');
	}

	public function card()
	{
		   $data['zb'] = $_SESSION['card_zb'];
           $this->load->view('login_card.html',$data);
	}

	public function log_card()
	{
		   $card = $this->input->post('admincard', TRUE);
		   if (empty($card)){
			    admin_msg(L('login_card_err'),site_url('login/card'),'no');  //�����Ϊ��
		   }

		   //��֤����
		   $zb=explode(',',$_SESSION['card_zb']);
           $res=$this->card->verification($_SESSION['admin_name'],$zb,$card);

		   $log=$this->CsdjDB->get_row('admin','*',$_SESSION['card_code'],'card');
		   if($log){

						    $ip=getip();
	                        $updata['lognums']  = $log->lognums+1;
	                        $updata['logip']   = $ip;
	                        $updata['logtime'] = time();
                            $this->CsdjDB->get_update('admin',$log->id,$updata);

        					$agent = ($this->agent->is_mobile() ? $this->agent->mobile() : $this->agent->platform()).'&nbsp;/&nbsp;'.$this->agent->browser().' v'.$this->agent->version();
							$add['uid']=$log->id;
							$add['loginip']=$ip;
							$add['logintime']=time();
							$add['useragent']=$agent;
							$this->CsdjDB->get_insert('admin_log',$add);

		                    $_SESSION['card_zb'] ='';
                            $_SESSION['card_code']  = '';
                            $_SESSION['admin_name']  = $log->adminname;
                            $_SESSION['admin_id']    = $log->id;
                            $_SESSION['admin_pass']  = md5($log->adminpass);
                            $_SESSION['admin_logtime']  = date('Y-m-d H:i:s',$log->logtime);
                            $_SESSION['admin_logip']  = $log->logip;

                            //��ס��¼24Сʱ
	                        $this->cookie->set_cookie("admin_id",$log->id,time()+86400);
			                $this->cookie->set_cookie("admin_login",md5($log->adminname.$log->adminpass),time()+86400);

							if (empty($url)){
			                     exit("<script>top.location='".site_url('index')."';</script>");
							}else{
			                     exit("<script>window.location='".$url."';</script>");
							}

                }else{
			          admin_msg(L('login_go_err4'),site_url('login'),'no');  //�˺Ų�����
                }
	}

	public function log()
	{
		$name = $this->input->post('adminname', TRUE);
		$pass = $this->input->post('adminpass', TRUE);
		$code = $this->input->post('admincodes', TRUE);
		$url = urldecode($this->input->get('backurl'));

		if (empty($name)||empty($pass)||empty($code)){
			admin_msg(L('login_go_err1'),site_url('login'),'no');  //�˺����롢��֤�벻��Ϊ��
		}

		if($code!=Admin_Code){
			admin_msg(L('login_go_err2'),site_url('login'),'no');  //��֤�벻��ȷ
		}

		$log=$this->CsdjDB->get_row('admin','*',$name,'adminname');
		if($log){
                     if($log->adminpass!=md5(md5($pass).$log->admincode)){

			                admin_msg(L('login_go_err3'),site_url('login'),'no'); //�������

					 }elseif(CS_Safe_Card==1 && !empty($log->card)){  //���ӿ�����֤


                            //��ȡ�������
							$zb_arr=$this->card->authe_rand($name);
							$zb=implode(',', $zb_arr);

		                    $_SESSION['card_zb']  = $zb;
                            $_SESSION['card_code']  = $log->card;
                            $_SESSION['admin_name']  = $log->adminname;

                            exit("<script>top.location='".site_url('login/card')."';</script>");

                     }else{

						    $ip=getip();
	                        $updata['lognums']  = $log->lognums+1;
	                        $updata['logip']   = $ip;
	                        $updata['logtime'] = time();
                            $this->CsdjDB->get_update('admin',$log->id,$updata);

        					$agent = ($this->agent->is_mobile() ? $this->agent->mobile() : $this->agent->platform()).'&nbsp;/&nbsp;'.$this->agent->browser().' v'.$this->agent->version();
							$add['uid']=$log->id;
							$add['loginip']=$ip;
							$add['logintime']=time();
							$add['useragent']=$agent;
							$this->CsdjDB->get_insert('admin_log',$add);


                            $_SESSION['admin_name']  = $log->adminname;
                            $_SESSION['admin_id']    = $log->id;
                            $_SESSION['admin_pass']  = md5($log->adminpass);
                            $_SESSION['admin_logtime']  = date('Y-m-d H:i:s',$log->logtime);
                            $_SESSION['admin_logip']  = $log->logip;

                            //��ס��¼24Сʱ
	                        $this->cookie->set_cookie("admin_id",$log->id,time()+86400);
			                $this->cookie->set_cookie("admin_login",md5($log->adminname.$log->adminpass),time()+86400);

							if (empty($url)){
			                     exit("<script>top.location='".site_url('index')."';</script>");
							}else{
			                     exit("<script>window.location='".$url."';</script>");
							}
                     }
                }else{
			          admin_msg(L('login_go_err4'),site_url('login'),'no');  //�˺Ų�����
                }
	}

	public function logout()
	{
        unset($_SESSION['admin_name'],$_SESSION['admin_id'],$_SESSION['admin_pass']);
        unset($_SESSION['admin_logtime'],$_SESSION['admin_logip']);

        //�����ס��¼
	    $this->cookie->set_cookie("admin_id");
        $this->cookie->set_cookie("admin_login");

		if(empty($_SERVER['HTTP_REFERER']) || site_url('opt/head')==$_SERVER['HTTP_REFERER']){
		      exit("<script>window.location='".site_url('login')."';</script>");
		}else{
		      exit("<script>window.location='".site_url('login')."?backurl=".urlencode($_SERVER['HTTP_REFERER'])."';</script>");
		}
	}
}

