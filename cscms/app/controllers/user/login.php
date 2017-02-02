<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-23
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('string');
			$this->load->library('user_agent');
			$this->lang->load('user');
	}

    //��¼
	public function index()
	{
		    $template=$this->load->view('login.html','',true);
			$Mark_Text=str_replace("{cscms:title}",L('login_01')." - ".Web_Name,$template);
			$Mark_Text=str_replace("[user:loginsave]",site_url('user/login/save'),$Mark_Text);
			//�ж���֤�뿪��
			$Mark_Text=str_replace("[user:codecheck]",User_Code_Mode,$Mark_Text);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);

            $Mark_Text=$this->skins->template_parse($Mark_Text,true);
			echo $Mark_Text;
	}

    //��¼��֤
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('login_02'),'javascript:history.back();');

            $username = $this->input->get_post('username', TRUE, TRUE);   //username or useremail
            $userpass = $this->input->get_post('userpass', TRUE, TRUE);   //userpass
		    $cookietime = intval($this->input->get_post('cookie')); //cookie����ʱ��
			if($cookietime==0) $cookietime=1;

			//�ж���֤�뿪��
			if(User_Code_Mode==1){
			     $codes=$this->input->post('usercode', TRUE);
				 if(empty($codes) || $this->cookie->get_cookie('codes')!=strtolower($codes)){
					    msg_url(L('login_03'),'javascript:history.back();');
				 }
			}

            if(empty($username)){
				msg_url(L('login_04'),'javascript:history.back();');  //�û���Ϊ��
            }elseif(empty($userpass)){
				msg_url(L('login_05'),'javascript:history.back();');  //�û���Ϊ��
			}else{

                //�����û�Ա�������������е���
                $sqlu="SELECT code,email,pass,sid,yid,uid,id,name,lognum,cion,vip,logtime,viptime,zid,zutime FROM ".CS_SqlPrefix."user where name='".$username."' or email='".$username."'";
		        $row=$this->db->query($sqlu)->row();
		        if(!$row){

	                 //--------------------------- Ucenter ---------------------------
	                 if(User_Uc_Mode==1){
                                include CSCMS.'lib/Cs_Ucenter.php';
                                include CSCMSPATH.'uc_client/client.php';
                                $uid = uc_user_login($username,$userpass);
	                            if(intval($uid[0]) > 0 ) {  //UC������������Ա

                                        $this->load->helper('string');
                                        $user['name']=$username;
                                        $user['code']=random_string('alnum', 6);
                                        $user['pass']=md5(md5($userpass).$user['code']);
                                        $user['email']=$uid[3];
                                        $user['uid']=$uid[0];
                                        $user['regip'] = getip();
                                        $user['qianm'] = '';
		                                if(User_Cion_Reg>0){
                                            $user['cion'] = User_Cion_Reg;
                                        }
										if(User_Uc_Fun==1){
	                                         $user['yid']  = 2;
										}
	                                    $user['zx']  = 1;
	                                    $user['lognum']  = 1;
	                                    $user['logtime'] = time();
	                                    $user['logip'] = getip();
	                                    $user['logms'] = time();
                                        $user['addtime'] = time();
                                        $res=$this->CsdjDB->get_insert('user',$user);
                                        if(intval($res) > 0 ) {

										     if(User_Uc_Fun==0){  //����Ҫ����

                                                   //��¼��־
        					                       $agent = ($this->agent->is_mobile() ? $this->agent->mobile() :                    $this->agent->platform()).'&nbsp;/&nbsp;'.$this->agent->browser().' v'.$this->agent->version();
							                       $add['uid']=$res;
							                       $add['loginip']=getip();
							                       $add['logintime']=time();
							                       $add['useragent']=$agent;
							                       $this->CsdjDB->get_insert('user_log',$add);

                                                   $_SESSION['cscms__id']    = $res;
                                                   $_SESSION['cscms__name']  = $username;
                                                   $_SESSION['cscms__login'] = md5($username.$user['pass']);

                                                   //��ס��¼
	                                               $this->cookie->set_cookie("user_id",$res,time()+86400*$cookietime);
			                                       $this->cookie->set_cookie("user_login",md5($username.$user['pass'].$user['code']),time()+86400*$cookietime);

                                                   //UCͬ����½
			                                       $log=($row->uid > 0)?uc_user_synlogin($row->uid):'';

                                                   msg_url(L('login_06').$log,userurl(site_url('user/space')),'ok');  //��¼�ɹ�

											 }else{

				                                   $key=md5($res.$username.$user['pass'].'2');
                                                   $Msgs['username'] = $username;
                                                   $Msgs['url']      =         userurl(site_url('user/reg/verify'))."?key=".$key."&username=".$username;
                                                   $title   = Web_Name.L('login_07');
                                                   $content = getmsgto(User_RegEmailContent,$Msgs);
												   $this->load->model('CsdjEmail');
                                                   $this->CsdjEmail->send($user['email'],$title,$content);

                                                   msg_url(L('login_08',array($user['email'])).' ,�����˺�~!','javascript:history.back();','ok');  //��Ҫ����
											 }
                                        }
								}
					}else{

                         msg_url(L('login_09'),'javascript:history.back();');  //�˺Ų�����
					}
                }else{
                           if($row->pass!=md5(md5(trim($userpass)).$row->code)){
								   msg_url(L('login_10'),'javascript:history.back();');
                           }elseif($row->sid==1){
								   msg_url(L('login_11'),'javascript:history.back();');
                           }elseif($row->yid==1){
								   msg_url(L('login_12'),'javascript:history.back();');
                           }elseif($row->yid==2){
				                   $key=md5($row->id.$username.$row->pass.$row->yid);
                                   $Msgs['username'] = $username;
                                   $Msgs['url']      = userurl(site_url('user/reg/verify'))."?key=".$key."&username=".$username;
                                   $title   = Web_Name.L('login_13');
                                   $content = getmsgto(User_RegEmailContent,$Msgs);
								   $this->load->model('CsdjEmail');
                                   $this->CsdjEmail->send($row->email,$title,$content);
								   msg_url(L('login_14'),'javascript:history.back();');
                           }else{

                               //ÿ���½�ӻ���
		                       if(User_Cion_Log>0 && date("Y-m-d",$row->logtime)!=date('Y-m-d')){
                                   $updata['cion']  = $row->cion+User_Cion_Log;
                               }

                               //�ж�VIP
                               IF($row->vip>0 && $row->viptime<time()){
	                                $updata['vip']  = 0;
	                                $updata['viptime']  = 0;
                               }
                               //�жϻ�Ա��
                               IF($row->zid>1 && $row->zutime<time()){
	                                $updata['zid']  = 1;
	                                $updata['zutime']  = 0;
                               }

	                           $updata['zx']      = 1;
	                           $updata['lognum']  = $row->lognum+1;
	                           $updata['logtime'] = time();
	                           $updata['logip']   = getip();
	                           $updata['logms']   = time();
                               $this->CsdjDB->get_update ('user',$row->id,$updata);

                               //��¼��־
        					   $agent = ($this->agent->is_mobile() ? $this->agent->mobile() :                    $this->agent->platform()).'&nbsp;/&nbsp;'.$this->agent->browser().' v'.$this->agent->version();
							   $add['uid']=$row->id;
							   $add['loginip']=getip();
							   $add['logintime']=time();
							   $add['useragent']=$agent;
							   $this->CsdjDB->get_insert('user_log',$add);

                               $_SESSION['cscms__id']    = $row->id;
                               $_SESSION['cscms__name']  = $row->name;
                               $_SESSION['cscms__login'] = md5($row->name.$row->pass);

                               //��ס��¼
	                           $this->cookie->set_cookie("user_id",$row->id,time()+86400*$cookietime);
			                   $this->cookie->set_cookie("user_login",md5($row->name.$row->pass.$row->code),time()+86400*$cookietime);

	                           //--------------------------- Ucenter ---------------------------
							   $log='';
	                           if(User_Uc_Mode==1){
                                  include CSCMS.'lib/Cs_Ucenter.php';
                                  include CSCMSPATH.'uc_client/client.php';
	                              $log=($row->uid > 0)?uc_user_synlogin($row->uid):'';
                               }
	                           //--------------------------- Ucenter ---------------------------
							   //�ݻ�TOKEN
							   get_token('token',2);

                               msg_url(L('login_15').$log,userurl(site_url('user/space')),'ok');  //��¼�ɹ�
                           }
				}
			}
	}
}
