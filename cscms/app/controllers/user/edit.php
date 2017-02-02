<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-07
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->lang->load('user');
			$this->CsdjUser->User_Login();
			$this->load->helper('string');
	}

    //����
	public function index()
	{
			//ģ��
			$tpl='edit.html';
			//URL��ַ
		    $url='edit/index';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('edit_01');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:editsave]",spacelink('edit,save'),$Mark_Text);
			echo $Mark_Text;
	}

    //�����޸�
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('edit_02'),'javascript:history.back();');

			$userinfo['nichen']=$this->input->post('usernichen', TRUE, TRUE);
			$userinfo['email']=$this->input->post('useremail', TRUE, TRUE);
			$userinfo['tel']=$this->input->post('usertel', TRUE, TRUE);
			$userinfo['qq']=$this->input->post('userqq', TRUE, TRUE);
			$userinfo['sex']=intval($this->input->post('usersex'));
			$userinfo['city']=$this->input->post('usercity', TRUE, TRUE);
			$userinfo['qianm']=$this->input->post('userqianm', TRUE);

			if(empty($userinfo['nichen']) || !is_username($userinfo['nichen'],1)) msg_url(L('edit_03'),'javascript:history.back();');
			if(empty($userinfo['email']) || !is_email($userinfo['email'])) msg_url(L('edit_04'),'javascript:history.back();');
			if(empty($userinfo['tel']) || !is_tel($userinfo['tel'])) msg_url(L('edit_05'),'javascript:history.back();');
			if(!empty($userinfo['qq']) && !is_qq($userinfo['qq'])) msg_url(L('edit_06'),'javascript:history.back();');

            //�ж��ǳ��Ƿ�ע��
			$nichen=$this->db->query("select id from ".CS_SqlPrefix."user where nichen='".$userinfo['nichen']."' and id!=".$_SESSION['cscms__id']."")->row();
			if($nichen){
                   msg_url(L('edit_07'),'javascript:history.back();');
			}

            //�ж������Ƿ�ע��
			$email=$this->db->query("select id from ".CS_SqlPrefix."user where email='".$userinfo['email']."' and id!=".$_SESSION['cscms__id']."")->row();
			if($email){
                   msg_url(L('edit_08'),'javascript:history.back();');
			}

            //�ж��ֻ��Ƿ�ע��
			$tel=$this->db->query("select id from ".CS_SqlPrefix."user where tel='".$userinfo['tel']."' and id!=".$_SESSION['cscms__id']."")->row();
			if($tel){
                   msg_url(L('edit_09'),'javascript:history.back();');
			}

			//�޸����
			$this->CsdjDB->get_update ('user',$_SESSION['cscms__id'],$userinfo);

			//�ݻ�token
			get_token('token',2);

            msg_url(L('edit_10'),'javascript:history.back();');
	}

    //ͷ��
	public function logo()
	{
			//ģ��
			$tpl='edit-logo.html';
			//URL��ַ
		    $url='edit/logo';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('edit_11');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//�ϴ��ύ��ַ
			$str['id']=$_SESSION['cscms__id'];
			$str['login']=$_SESSION['cscms__login'];
            $key = sys_auth(addslashes(serialize($str)),'E');
			$logolink=urlencode(spacelink('edit,logosave'))."&input=".$key."&uploadSize=2048";
			$Mark_Text=str_replace("[user:logosave]",$logolink,$Mark_Text);
			echo $Mark_Text;
	}

    //�ϴ�ͷ��
	public function logo_save()
	{
		    $uid=isset($_SESSION['cscms__id'])?intval($_SESSION['cscms__id']):intval($this->cookie->get_cookie('user_id'));
            $tempFile = file_get_contents("php://input");
			$picname  = $uid.".jpg";
			if(UP_Mode==1 && UP_Pan!=''){
			    $dir_pan = UP_Pan.'/';
				$dir_pan = str_replace("//","/",$dir_pan);
			}else{
			    $dir_pan = FCPATH;
			}
		    $picdirs  = date('Ym')."/".date('d')."/".$uid.".jpg";
		    $filename = $dir_pan."attachment/logo/".$picdirs; 
			$filepath = (UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$picname : '/'.date('Ymd').'/'.$picname;
		    if (!empty($tempFile) && $uid>0) {

				   //������ǰ�ļ���
				   $dir=$dir_pan."attachment/logo/".date('Ym')."/".date('d');
                   mkdirss($dir);

	    	       if($handle=fopen($filename,"w+")) {   
		    	    	if(!fwrite($handle,$tempFile)==FALSE){   
		    	    		fclose($handle);
		    	    	}
	    	       } 

 				   list($width, $height, $type, $attr) = getimagesize($filename);
				   if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
						@unlink($filename);
	                    exit('UploadPicError');
				   }

                   //�ж�ˮӡ
                   if(CS_WaterMark==1){
		                $this->load->library('watermark');
                        $this->watermark->imagewatermark($filename);
                   }

			       //�ж��ϴ���ʽ
                   $this->load->library('csup');
				   $res=$this->csup->up($filename,$picname);
				   if(!$res){
						@unlink($filename);
	                    exit('UploadPicError');
				   }

				   //ɾ��ԭ����ͼƬ
				   $pic=getzd('user','logo',$uid);
				   if($pic!=$filepath){
                       $this->csup->del($pic,'logo');
				   }

                   //д�����ݿ�
                   $this->db->query("update ".CS_SqlPrefix."user set logo='".$filepath."' where id=".$uid."");

                   exit('UploadPicSucceed');
                               
			} else {
			       exit('UploadPicError');
			}
	}

    //����
	public function pass()
	{
			//ģ��
			$tpl='edit-pass.html';
			//URL��ַ
		    $url='edit/pass';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('edit_12');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:passsave]",spacelink('edit,pass_save'),$Mark_Text);
			echo $Mark_Text;
	}

    //�����޸�
	public function pass_save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('edit_02'),'javascript:history.back();');

			$pass=$this->input->post('userpass', TRUE, TRUE);
			$pass1=$this->input->post('userpass1', TRUE, TRUE);
			$pass2=$this->input->post('userpass2', TRUE, TRUE);

			if(empty($pass)) msg_url(L('edit_13'),'javascript:history.back();');
			if(empty($pass1) || !is_userpass($pass1)) msg_url(L('edit_14'),'javascript:history.back();');
			if($pass1!=$pass2) msg_url(L('edit_15'),'javascript:history.back();');

            //�ж�ԭ����
			$row=$this->db->query("select code,pass from ".CS_SqlPrefix."user where id=".$_SESSION['cscms__id']."")->row();
			if($row->pass != md5(md5($pass).$row->code)){
                   msg_url(L('edit_16'),'javascript:history.back();');
			}

			//�޸����
			$userinfo['code']=random_string('alnum',6);
			$userinfo['pass']=md5(md5($pass1).$userinfo['code']);
			$this->CsdjDB->get_update ('user',$_SESSION['cscms__id'],$userinfo);
			get_token('token',2);

            msg_url(L('edit_17'),'javascript:history.back();');
	}

    //�󶨵�¼
	public function open()
	{
			//ģ��
			$tpl='edit-open.html';
			//URL��ַ
		    $url='edit/open';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('edit_18');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��������¼ģʽ
			$Mark_Text=str_replace("[user:appmode]",CS_Appmode,$Mark_Text);
            //�ж��Ƿ��
			$qq=$wb=$bd=$rr=$kx=$db=0;
			$result=$this->db->query("select cid from ".CS_SqlPrefix."useroauth where uid=".$_SESSION['cscms__id']." order by id desc");
            foreach ($result->result() as $rows){
                  if($rows->cid==1) $qq=1;
                  if($rows->cid==2) $wb=1;
                  if($rows->cid==3) $bd=1;
                  if($rows->cid==4) $rr=1;
                  if($rows->cid==5) $kx=1;
                  if($rows->cid==6) $db=1;
			}
			$Mark_Text=str_replace("[user:qqmode]",$qq,$Mark_Text);
			$Mark_Text=str_replace("[user:wbmode]",$wb,$Mark_Text);
			$Mark_Text=str_replace("[user:bdmode]",$bd,$Mark_Text);
			$Mark_Text=str_replace("[user:rrmode]",$rr,$Mark_Text);
			$Mark_Text=str_replace("[user:kxmode]",$kx,$Mark_Text);
			$Mark_Text=str_replace("[user:dbmode]",$db,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
			//IF��ǩ����
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

}
