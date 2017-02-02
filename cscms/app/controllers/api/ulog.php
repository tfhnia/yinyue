<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-04-27
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ulog extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
		  $this->load->library('user_agent');
	      //�ر����ݿ⻺��
          $this->db->cache_off();
	}

    //��Ա��¼״̬
	public function log()
	{
          $ac=$this->uri->segment(4);
          $user=$this->uri->segment(5);
		  $this->load->model('CsdjUser');
		  $callback=$this->input->get('callback',true);	
		  //�жϻ�Ա�Ƿ�ر�
		  if(User_Mode==0){
		      $Mark_Text=get_bm(User_No_info,'gbk','utf-8');
              echo $callback."({str:".json_encode($Mark_Text)."})";
			  exit;
		  }
		  $ucid='logout';
		  $login=$this->CsdjUser->User_Login(1);
		  $template=(!$login)?'ulogin.html':'uinfo.html';
		  if(!empty($ac) && is_file(FCPATH.'plugins/'.$ac.'/config/site.php')){
			       $skins = '';
			       if(!empty($user)){
					  if(!defined('HOMEPATH')) define('HOMEPATH', 'home');
			          $skins = (Home_Fs==1)?getzd('user','skins',$user,'name'):getzd('user','skins',$user);
				   }
                   $this->load->get_templates($ac,1,$skins,1);
		  }elseif($ac=='home'){
    	           if(!defined('HOMEPATH')) define('HOMEPATH', 'home');
			       $skins = (Home_Fs==1)?getzd('user','skins',$user,'name'):getzd('user','skins',$user);
			       $this->load->get_templates($ac,0,$skins,1);
		  }elseif($ac!='index'){
			       $this->load->get_templates($ac);
		  }
		  $Mark_Text=$this->load->view($template,'',true);
		  $Mark_Text=str_replace("{cscms:logadd}","cscms_logadd();",$Mark_Text);
		  $Mark_Text=str_replace("{cscms:logout}","cscms_logout();",$Mark_Text);
		  if(defined('HOMEPATH')){
		       $Mark_Text=$this->skins->cscms_common($Mark_Text,$skins);
		  }
		  if($login){
		        $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
				if(empty($row['nichen'])) $row['nichen']=$row['name'];
				$Mark_Text=$this->skins->cscms_skins('user',$Mark_Text,$Mark_Text,$row);
				$ucid=$row['uid'];
		  }
          $Mark_Text=$this->skins->template_parse($Mark_Text,false);
		  //ͬ��UC��������������������
		  if(User_Uc_Mode==1){
			   $Mark_Text.="<iframe marginwidth=\"0\" marginheight=\"0\" src=\"".site_url('api/ulog/uclog')."?uid=".$ucid."\" frameborder=\"0\" width=\"1\" scrolling=\"no\" height=\"1\" leftmargin=\"0\" topmargin=\"0\"></iframe>";
		  }
		  $Mark_Text=get_bm($Mark_Text,'gbk','utf-8');
          echo $callback."({str:".json_encode($Mark_Text)."})";
	}

	//UCͬ����¼
	public function uclog()
	{
		  if(User_Mode==0) die(User_No_info);
		  if(User_Uc_Mode==0) die('Uc Close');
          $uid = $this->input->get_post('uid',TRUE);
	      include CSCMS.'lib/Cs_Ucenter.php';
          include CSCMSPATH.'uc_client/client.php';
		  if($uid=='logout'){
			   echo uc_user_synlogout();
	      }elseif((int)$uid > 0) {
			   echo uc_user_synlogin($uid);
		  }
	}

    //�ύ��¼
	public function login()
	{
          //��sessionsʹ���ļ��洢ʱ��ÿ������һ��sessions�ļ���
          if(CS_Session_Is==1){
	          $day=@file_get_contents(FCPATH."cache/sessions/day.txt");
	          if($day!=date('Y-m-d')){
                   $dh=opendir(FCPATH."cache/sessions/");
                   while ($file=readdir($dh)) {
                      if($file!="." && $file!="..") {
                         $fullpath=FCPATH."cache/sessions/".$file;
                         @unlink($fullpath);
                      }
                   }
                   closedir($dh);
	               @file_put_contents(FCPATH."cache/sessions/day.txt",date('Y-m-d'));
	          }
		  }
		  if(User_Mode==0) die(User_No_info);
          $username = $this->input->get('username', TRUE, TRUE);   //username or useremail
          $userpass = $this->input->get('userpass', TRUE, TRUE);   //userpass
		  $callback = $this->input->get('callback',true);
		  $cookietime = intval($this->input->get('cookie')); //cookie����ʱ��
	      if($cookietime==0) $cookietime=1;
		  //Ԥ������ת��
		  $username=get_bm($username);
		  $userpass=get_bm($userpass);

            if(empty($username)){
				$error='10001';  //�û���Ϊ��
            }elseif(empty($userpass)){
				$error='10002';  //����Ϊ��
			}else{

                //�����û�Ա�������������е���
                $sqlu="SELECT code,email,pass,sid,yid,id,name,lognum,cion,vip,logtime,viptime,zid,zutime FROM ".CS_SqlPrefix."user where name='".$username."' or email='".$username."'";
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

                                                   $error='10006'; //����ɹ�

											 }else{

				                                   $key=md5($res.$username.$user['pass'].$user['yid']);
                                                   $Msgs['username'] = $username;
                                                   $Msgs['url']      =         userurl(site_url('user/reg/verify'))."?key=".$key."&username=".$username;
                                                   $title   = Web_Name.'ע�ἤ���ʼ�';
                                                   $content = getmsgto(User_RegEmailContent,$Msgs);
												   $this->load->model('CsdjEmail');
                                                   $this->CsdjEmail->send($user['email'],$title,$content);

                                                   $error='10007';  //��Ҫ����
											 }
                                        }
								}else{
                                    $error='10003';  //�ʺŲ�����
								}
					}else{

                         $error='10003';  //�ʺŲ�����
					}
                }else{
                           if($row->pass!=md5(md5(trim($userpass)).$row->code)){
                                   $error='10004'; //�������
                           }elseif($row->sid==1){
                                   $error='10005'; //�ʺű�����
                           }elseif($row->yid==1){
                                   $error='10008'; //վ��δ���
                           }elseif($row->yid==2){
				                   $key=md5($row->id.$username.$row->pass.$row->yid);
                                   $Msgs['username'] = $username;
                                   $Msgs['url']      = userurl(site_url('user/reg/verify'))."?key=".$key."&username=".$username;
                                   $title   = Web_Name.'ע�ἤ���ʼ�';
                                   $content = getmsgto(User_RegEmailContent,$Msgs);
								   $this->load->model('CsdjEmail');
                                   $this->CsdjEmail->send($row->email,$title,$content);
                                   $error='10007'; //�ʼ�δ����
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

                               $error='10006'; //����ɹ�
                           }
				}
			}
            echo $callback."({error:".json_encode($error)."})";
	}

    //�˳���¼
	public function logout()
	{
		    $callback = $this->input->get('callback',true);
            //ɾ������״̬
            $updata['zx']=0;
			if(isset($_SESSION['cscms__id'])){
                $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$updata);
				$this->CsdjDB->get_del('session',$_SESSION['cscms__id'],'uid');
			}

            unset($_SESSION['cscms__id'],$_SESSION['cscms__name'],$_SESSION['cscms__login']);

            //�����ס��¼
	        $this->cookie->set_cookie("user_id");
			$this->cookie->set_cookie("user_login");

            echo $callback."({error:'10001'})";
	}
}
