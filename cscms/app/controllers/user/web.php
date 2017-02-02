<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-30
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Web extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->CsdjUser->User_Login();
			$this->load->helper('string');
			$this->lang->load('user');
	}

    //��ҳģ��
	public function index()
	{
			//ģ��
			$tpl='web.html';
			//URL��ַ
		    $url='web/index';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('web_01');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //����ͼƬ
	public function pic()
	{
			//ģ��
			$tpl='web-pic.html';
			//URL��ַ
		    $url='web/pic';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('web_02');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'id','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:bgpicsave]",spacelink('web,picsave'),$Mark_Text);
			echo $Mark_Text;
	}

    //���汳��ͼƬ
	public function picsave()
	{
		   $filename=$this->upload('bgpic');
		   $bgpic=getzd('user','bgpic',$_SESSION['cscms__id']);
		   //ɾ��ԭ����ͼƬ
		   $this->load->library('csup');
		   $this->csup->del($bgpic,'bgpic'); //ɾ������

           $filepath=(UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$filename : '/'.date('Ymd').'/'.$filename;
		   $edit['bgpic']=$filepath;
		   $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);

		   $pic=piclink('bgpic',$edit['bgpic']).'?size=720*186';

           echo '<script type="text/javascript" src="'.Web_Path.'packs/js/cscms.js"></script><script type="text/javascript">
				  parent.do_alert("'.L('web_04').'");
				  parent.$(".file_working").hide();
				  parent.$(".banner_clip").show();
				  parent.$(".banner_clip").css("background","url('.$pic.')");
				  </script>';
	}

	//ͼƬ�ϴ�
    public function upload($ac) {  

		   $FILES=$_FILES[$ac];
           //�ļ�����Ŀ¼·��
           $save_path = FCPATH.'/attachment/bgpic/';
           //���������ϴ����ļ���չ��
           $ext_arr = array('gif', 'jpg', 'png');
           //����ļ���С
           $max_size = 2*1024*1024;
           //PHP�ϴ�ʧ��
           if (!empty($FILES['error'])) {
	            switch($FILES['error']){
		            case '1':
			            $error = L('web_05');
			            break;
		            case '2':
			            $error = L('web_06');
			            break;
		            case '3':
			            $error = L('web_07');
			            break;
		            case '4':
			            $error = L('web_08');
			            break;
		            case '6':
			            $error = L('web_09');
			            break;
		            case '7':
			            $error = L('web_10');
			            break;
		            case '8':
			            $error = 'File upload stopped by extension��';
			            break;
		            case '999':
		            default:
			            $error = L('web_11');
	            }
	            $this->alert($error);
           }
           //���ϴ��ļ�ʱ
           if (!empty($_FILES) === false) {
                $this->alert(L('web_12'));
           }
	       //ԭ�ļ���
	       $file_name = $FILES['name'];
	       //����������ʱ�ļ���
	       $tmp_name = $FILES['tmp_name'];
	       //�ļ���С
	       $file_size = $FILES['size'];
	       //����ļ���
	       if (!$file_name) {
		       $this->alert(L('web_13'));
	       }
	       //����Ƿ����ϴ�
	       if (@is_uploaded_file($tmp_name) === false) {
	             $this->alert(L('web_16'));
	       }
	       //����ļ���С
	       if ($file_size > $max_size) {
		         $this->alert(L('web_17'));
	       }
	       //����ļ���չ��
	       $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));
	       //�����չ��
	       if (in_array($file_ext, $ext_arr) === false) {
		         $this->alert(L('web_18',array(implode(",", $ext_arr))));
	       }
		   //����Ŀ¼
	       $save_path .= date("Ym")."/".date("d")."/";
	       mkdirss($save_path);
	       //���ļ���
           $this->load->helper('string');
	       $new_file_name = random_string('alnum',10) . '.' . $file_ext;

           //���ͼƬ�ļ��Ƿ���ȷ
           $aa=getimagesize($tmp_name);
           $weight=$aa["0"];////��ȡͼƬ�Ŀ�
           $height=$aa["1"];///��ȡͼƬ�ĸ�
           if(intval($weight)<1 || intval($height)<1){
                @unlink($tmp_name);
                $this->alert(L('web_19'));
		   }
	       //�ƶ��ļ�
	       $file_path = $save_path . $new_file_name;
	       if(move_uploaded_file($tmp_name, $file_path) === false) {
		        $this->alert(L('web_20'));
	       }
		   @chmod($file_path, 0644);

           $filepath=(UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$file_name : '/'.date('Ymd').'/'.$file_name;

		   //�ж��ϴ���ʽ
           $this->load->library('csup');
		   $res=$this->csup->up($file_path,$new_file_name);
		   if($res){
				return $new_file_name;
		   }else{
				@unlink($file_path);
				$this->alert(L('web_20'));
		   }
	}

    public function alert($msg) { 
           echo '<script type="text/javascript">
				  parent.do_alert("'.$msg.'");
				  parent.$(".file_working").hide();
				  parent.$(".banner_clip").show();
				  </script>';
		   exit();
	}
}
