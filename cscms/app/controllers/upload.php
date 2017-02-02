<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upload extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjUser');
			$this->load->helper('string');
	}

    //上传附件
	public function index()
	{
	        if(!$this->CsdjUser->User_Login(1)){
                 exit('No Login');
			}
			//检测会员组上传附件权限
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','fid',$zuid);
			if($rowu->fid==0){
                 exit(L('up_01'));
			}
		    $nums=intval($this->input->get('nums')); //支持数量
		    $types=$this->input->get('type',true);  //支持格式
            $data['tsid']=$this->input->get('tsid',true); //返回提示ID
            $data['sid']=intval($this->input->get('sid')); //返回输入框方法，0替换、1换行增加
            $data['dir']=$this->input->get('dir',true);   //上传目录
            $data['fid']=$this->input->get('fid',true);   //返回ID，一个页面多个返回可以用到
            $data['upsave']=site_url('upload/up_save');
            $data['size'] = UP_Size;
			$data['types'] = (empty($types))?"*":$types;
            $data['nums']=($nums==0)?1:$nums;
			if($data['fid']=='undefined') $data['fid']='';
			if($data['tsid']=='undefined') $data['tsid']='';
			if($data['types']=='undefined') $data['types']='*';
			if($data['dir']=='undefined') $data['dir']='other';
			$str['fid']=$rowu->fid;
			$str['id']=$_SESSION['cscms__id'];
			$str['login']=$_SESSION['cscms__login'];
            $data['key'] = sys_auth(addslashes(serialize($str)),'E');
			$this->load->get_templates('common');
            $this->load->view('upload.html',$data);
	}

    //保存附件
	public function up_save()
	{
            $key=$this->input->post('key',true);
	        if(!$this->CsdjUser->User_Login(1,$key)){
                 exit('No Login');
			}
			//检测会员组上传附件权限
			$key   = unserialize(stripslashes(sys_auth($key,'D')));
            $fid   = isset($key['fid'])?intval($key['fid']):0;
			if($fid==0){
                 exit('You do not have permission to upload attachments of group members!');
			}
            $dir=$this->input->post('dir',true);
			if(empty($dir) || !preg_match('/^[0-9a-zA-Z\_]*$/', $dir)) {  
                 $dir='other';
			}
			//上传目录
			if(UP_Mode==1 && UP_Pan!=''){
			    $path = UP_Pan.'/attachment/'.$dir.'/'.date('Ym').'/'.date('d').'/';
				$path = str_replace("//","/",$path);
			}else{
			    $path = FCPATH.'attachment/'.$dir.'/'.date('Ym').'/'.date('d').'/';
			}
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$file_name = $_FILES['Filedata']['name'];
			$file_size = filesize($tempFile);
	        $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));

	        //检查扩展名
			$ext_arr = explode("|", UP_Type);
	        if (in_array($file_ext,$ext_arr) === false) {
                exit(escape(L('up_02')));
			}elseif($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpge'){
				list($width, $height, $type, $attr) = getimagesize($tempFile);
				if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
                    exit(escape(L('up_03')));
				}
			}
            //PHP上传失败
            if (!empty($_FILES['Filedata']['error'])) {
	            switch($_FILES['Filedata']['error']){
		            case '1':
			            $error = L('up_04');
			            break;
		            case '2':
			            $error = L('up_05');
			            break;
		            case '3':
			            $error = L('up_06');
			            break;
		            case '4':
			            $error = L('up_07');
			            break;
		            case '6':
			            $error = L('up_08');
			            break;
		            case '7':
			            $error = L('up_09');
			            break;
		            case '8':
			            $error = 'File upload stopped by extension。';
			            break;
		            case '999':
		            default:
			            $error = L('up_10');
	            }
	            exit(escape($error));
            }
			//创建目录
			if (!is_dir($path)) {
                mkdirss($path);
            }
            //新文件名
			$file_name=random_string('alnum', 20). '.' . $file_ext;
			$file_path=$path.$file_name;
			if (move_uploaded_file($tempFile, $file_path) !== false) { //上传成功

                    $filepath=(UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$file_name : '/'.date('Ymd').'/'.$file_name;

                    //判断水印
                    if($dir!='links' && CS_WaterMark==1){
						if($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpge'){
		                     $this->load->library('watermark');
                             $this->watermark->imagewatermark($file_path);
						}
                    }

					//判断上传方式
                    $this->load->library('csup');
					$res=$this->csup->up($file_path,$file_name);
					if($res){
						if(UP_Mode==1 && ($dir=='music' || $dir=='video')){
						    $filepath='attachment/'.$dir.$filepath;
						}
					    exit('ok=cscms='.$filepath);
					}else{
						@unlink($file_path);
	                    exit('no');
					}

			}else{ //上传失败
				  exit('no');
			}
	}

    //编辑器上传
	public function editor()
	{
		    $this->CsdjUser->User_Login();
			//检测会员组上传附件权限
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','fid',$zuid);
			if($rowu->fid==0){
                 $this->alert(L('up_01'));
			}
            $dir_name = $this->input->get('dir',true);
			if(empty($dir_name) || !preg_match('/^[0-9a-zA-Z\_]*$/', $dir_name)) {  
                 $dir_name = 'image';
			}
            //文件保存目录路径
			if(UP_Mode==1 && UP_Pan!=''){
			    $save_path = UP_Pan.'/attachment/editor/'.$dir_name.'/'.date('Ym').'/'.date('d').'/';
				$save_path = str_replace("//","/",$save_path);
			}else{
			    $save_path = FCPATH.'attachment/editor/'.$dir_name.'/'.date('Ym').'/'.date('d').'/';
			}
            //定义允许上传的文件扩展名
            $ext_arr = array(
	            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	            'flash' => array('swf', 'flv'),
	            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
            );
            //最大文件大小
            $max_size = 10000000;
            //PHP上传失败
            if (!empty($_FILES['imgFile']['error'])) {
	            switch($_FILES['imgFile']['error']){
		            case '1':
			            $error = L('up_04');
			            break;
		            case '2':
			            $error = L('up_05');
			            break;
		            case '3':
			            $error = L('up_06');
			            break;
		            case '4':
			            $error = L('up_07');
			            break;
		            case '6':
			            $error = L('up_08');
			            break;
		            case '7':
			            $error = L('up_09');
			            break;
		            case '8':
			            $error = 'File upload stopped by extension。';
			            break;
		            case '999':
		            default:
			            $error = L('up_10');
	            }
	            $this->alert($error);
            }
	        //原文件名
	        $file_name = $_FILES['imgFile']['name'];
	        //服务器上临时文件名
	        $tmp_name = $_FILES['imgFile']['tmp_name'];
	        //文件大小
	        $file_size = $_FILES['imgFile']['size'];
	        //检查文件名
	        if (!$file_name) {
				 $this->alert(L('up_07'));
	        }
	        //检查文件大小
	        if ($file_size > $max_size) {
		        $this->alert(L('up_05'));
	        }
	        //检查目录名
            if($dir_name=='file') $dir_name='papers';
	        if (empty($ext_arr[$dir_name])) {
	            $this->alert(L('up_11'));
	        }
            //检查图片文件是否正确
            if($dir_name=='image'){
				list($width, $height, $type, $attr) = getimagesize($tmp_name);
				if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
					@unlink($tmp_name);
                    $this->alert(L('up_03'));
				}
			}
	        //获得文件扩展名
		    $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));
	        //检查扩展名
	        if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		        $this->alert(L('up_02'));
	        }
			//创建文件夹
			if (!is_dir($save_path)) {
                mkdirss($save_path);
            }
            //新文件名
			$file_name=random_string('alnum', 20). '.' . $file_ext;
			$file_path=$save_path.$file_name;
			if (move_uploaded_file($tmp_name, $file_path) !== false) { //上传成功

                    $filepath=(UP_Mode==1)?'/attachment/editor/'.$dir_name.'/'.date('Ym').'/'.date('d').'/'.$file_name : '/'.date('Ymd').'/'.$file_name;

                    //判断水印
                    if(CS_WaterMark==1){
						if($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpge'){
		                     $this->load->library('watermark');
                             $this->watermark->imagewatermark($file_path);
						}
                    }

					//判断上传方式
                    $this->load->library('csup');
					$res=$this->csup->up($file_path,$file_name);
					if($res){
						echo "<script type=\"text/javascript\">
						function cscms_host(){
     						var host=window.location.host;
     						var DomainUrl = window.location.host.match(/[^.]*\.(com\.cn|gov\.cn|net\.cn|cn\.com)(\.[^.]*)?/ig);
     						var reip = /^([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.([0-9]|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])$/;
     						if(DomainUrl==null && host!='localhost' && host!='localhost' && !reip.test(host)){
         						var host_arr=host.split(\".\"); 
         						var nums=host_arr.length;
         						DomainUrl=host_arr[nums-2]+'.'+host_arr[nums-1];
     						}
     						return DomainUrl;
						}
						var DomainUrl = cscms_host();
						if(DomainUrl!=null){
    						document.domain = DomainUrl;
						}
						</script>
						<pre>{\"error\":0,\"url\":\"".annexlink($filepath)."\"}</pre>";
					}else{
						@unlink($file_path);
	                    $this->alert(L('up_12'));
					}

			}else{ //上传失败
				  $this->alert(L('up_12'));
			}
	}

	//错误输出
	public function alert($msg) {
		echo json_encode(array('error' => 1, 'message' => get_bm($msg,'gbk','utf-8')));
        exit();
	}
}