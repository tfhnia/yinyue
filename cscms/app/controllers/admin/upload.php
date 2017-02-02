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
		    $this->load->model('CsdjAdmin');
			$this->load->helper('string');
			$this->lang->load('admin_upload');
	}

	//网站附件管理
	public function index()
	{
	        $this->CsdjAdmin->Admin_Login();
		    $this->load->helper('file');
 	        $path = $this->input->get('path',true);
 	        $page = $this->input->get('page',true);
            if(empty($page)) $page=1;
            if(empty($path)){
				$path=Web_Path."attachment/";
			    if(UP_Pan!=''){
			        $path = UP_Pan.$path;
				    $path = str_replace("//","/",$path);
				}
			}
			if(substr($path,0,1)!='/' && UP_Pan=='') $path="/".$path;
            if(substr(str_replace(array(UP_Pan,Web_Path),array("",""),$path),0,10)!='attachment'){
                    admin_msg(L('plub_01'),'javascript:history.back();','no');
            }
            $paths=(UP_Pan!='')?$path:str_replace(Web_Path."attachment/",FCPATH."attachment/",$path);
            $showarr=get_dir_file_info($paths, $top_level_only = TRUE);
            $dirs=$list=array();
		    if ($showarr) {
			    foreach ($showarr as $t) {
				    if (is_dir($t['server_path'])) {
					    $dirs[] = array(
						    'name' => $t['name'],
						    'date' => date('Y-m-d H:i:s',$t['date']),
						    'icon' => Web_Path.'packs/admin/images/ext/dir.gif',
						    'link' => site_url('upload')."?path=".$path.$t['name']."/",
						    'dellink' => site_url('upload/del')."?path=".$path.$t['name']."/",
					    );
				    } else {
						$exts = trim(strrchr($t['name'], '.'), '.');
                        if(UP_Pan!=''){
                             $link=UP_Url.str_replace(UP_Pan,"",$path.$t['name']);
						}else{
                             $link='http://'.Web_Url.$path.$t['name'];
						}
						$list[] = array(
							'name' => $t['name'],
							'ext' => get_extpic($exts),
						    'date' => date('Y-m-d H:i:s',$t['date']),
						    'size' => formatsize($t['size']),
							'icon' => Web_Path.'packs/admin/images/ext/'.get_extpic($exts).'.gif',
							'link' => $link,
						    'dellink' => site_url('upload/del')."?path=".$path.$t['name'],
						);
				    }
			    }
		    }
            $data['path']=$path;
			$data['dirs']=$dirs;
			$data['show']=$list;

            if(str_replace(array(UP_Pan,Web_Path),array("",""),$path)=="attachment"){
                $data['uppage']="###";
            }else{
                $data['uppage']="javascript:history.back();";
            }
            $this->load->view('upload_dir.html',$data);
	}

	//删除附件
	public function del()
	{
	        $this->CsdjAdmin->Admin_Login();
 	        $path = $this->input->get('path',true);
			if(empty($path)) admin_msg(L('plub_02'),'javascript:history.back();','no');
			$path=FCPATH.$path;
            if (is_dir($path)) {
                 deldir($path);
			}else{
				 @unlink($path);
			}
            admin_msg(L('plub_03'),'javascript:history.back();','ok');
	}

    //上传附件
	public function up()
	{
	        $this->CsdjAdmin->Admin_Login();
		    $nums=intval($this->input->get('nums')); //支持数量
		    $types=$this->input->get('type',true);  //支持格式
            $fhid=$this->input->get('fhid',true); //返回ID参数
            $data['fhid']=(empty($fhid))?"pic":$fhid;
            $data['sid']=intval($this->input->get('sid')); //返回输入框方法，0替换、1换行增加
            $data['dir']=$this->input->get('dir',true);   //上传目录
            $data['fid']=$this->input->get('fid',true);   //返回ID，一个页面多个返回可以用到
            $data['upsave']=site_url('upload/up_save');
            $data['size'] = UP_Size;
            $data['types'] =(empty($types))?"*.gif;*.png;*.jpg":$types;
            $data['nums']=($nums==0)?1:$nums;
			if($data['fid']=='undefined') $data['fid']='';
			$str['id']=$_SESSION['admin_id'];
			$str['name']=$_SESSION['admin_name'];
			$str['pass']=$_SESSION['admin_pass'];
            $data['key'] = sys_auth(addslashes(serialize($str)),'E');
            $this->load->view('upload.html',$data);
	}

    //保存附件
	public function up_save()
	{
            $key=$this->input->post('key',true);
	        $this->CsdjAdmin->Admin_Login($key);
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
			if (!is_dir($path)) {
                mkdirss($path);
            }
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$file_name = $_FILES['Filedata']['name'];
			$file_size = filesize($tempFile);
	        $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));

	        //检查扩展名
			$ext_arr = explode("|", UP_Type);
	        if (in_array($file_ext,$ext_arr) === false) {
                exit(escape(L('plub_04')));
			}elseif($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpge'){
				list($width, $height, $type, $attr) = getimagesize($tempFile);
				if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
                    exit(escape(L('plub_05')));
				}
			}
            //PHP上传失败
            if (!empty($_FILES['Filedata']['error'])) {
	            switch($_FILES['Filedata']['error']){
		            case '1':
			            $error = L('plub_06');
			            break;
		            case '2':
			            $error = L('plub_07');
			            break;
		            case '3':
			            $error = L('plub_08');
			            break;
		            case '4':
			            $error = L('plub_09');
			            break;
		            case '6':
			            $error = L('plub_10');
			            break;
		            case '7':
			            $error = L('plub_11');
			            break;
		            case '8':
			            $error = 'File upload stopped by extension。';
			            break;
		            case '999':
		            default:
			            $error = L('plub_12');
	            }
	            exit(escape($error));
            }
            //新文件名
            //$file_name=date("YmdHis") . rand(10000, 99999) . '.' . $file_ext;
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

	//网站附件
	public function myattach()
	{
	        $this->CsdjAdmin->Admin_Login();
		    $this->load->helper('directory');
 	        $path = $this->input->get('path',true);
 	        $ext = $this->input->get('ext',true);

            if(empty($ext)) $ext=UP_Type;

            if(empty($path)){
				$path=Web_Path."attachment/";
			    if(UP_Pan!=''){
			        $path = UP_Pan.$path;
				    $path = str_replace("//","/",$path);
				}
			}
			if(substr($path,0,1)!='/' && UP_Pan=='') $path="/".$path;
            if(substr(str_replace(array(UP_Pan,Web_Path),array("",""),$path),0,10)!='attachment'){
                    admin_msg(L('plub_01'),'javascript:history.back();','no');
            }
            $paths=(UP_Pan!='')?$path:str_replace(Web_Path."attachment/",FCPATH."attachment/",$path);

			$dirs = $list = array();
			$ext2 = explode('|', $ext);
			$path=str_replace('//','/',$path);
            $arrs=directory_map($paths, 1);
		    if ($arrs) {
			    foreach ($arrs as $t) {
				    if (is_dir($paths.$t)) {
					    $name = trim($t, DIRECTORY_SEPARATOR);
					    $dirs[] = array(
						    'name' => $name,
						    'icon' => Web_Path.'packs/admin/images/ext/dir.gif',
						    'link' => site_url('upload/myattach')."?ext=".$ext."&path=".$path.$name."/",
					    );
				    } else {
					    $exts = trim(strrchr($t, '.'), '.');
					    if (($ext=='*' || in_array($exts, $ext2)) && $exts != 'php' && $exts != 'html') {
                            if(UP_Pan!=''){
                                 $link=UP_Url.str_replace(UP_Pan,"",$path.$t);
						    }else{
                                 $link='http://'.Web_Url.$path.$t;
						    }
						    $list[] = array(
							    'name' => $t,
							    'ext'  => get_extpic($exts),
							    'icon' => Web_Path.'packs/admin/images/ext/'.get_extpic($exts).'.gif',
							    'link' => $link,
						    );
					    }
				    }
			    }
		    }
            $data['path']=$path;
            $data['ext']=$ext;
            $data['url']=site_url('upload/myattach')."?ext=".$ext."&path=".$path;
            $data['dirs']=$dirs;
            $data['show']=$list;

            if(str_replace(array(UP_Pan,Web_Path),array("",""),$path)=="attachment"){
                $data['uppage']=site_url('upload/myattach');
            }else{
                $data['uppage']="javascript:history.back();";
            }
            $this->load->view('myattach.html',$data);
	}

    //编辑器上传
	public function editor()
	{
	        $this->CsdjAdmin->Admin_Login();
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
			            $error = L('plub_06');
			            break;
		            case '2':
			            $error = L('plub_07');
			            break;
		            case '3':
			            $error = L('plub_08');
			            break;
		            case '4':
			            $error = L('plub_09');
			            break;
		            case '6':
			            $error = L('plub_10');
			            break;
		            case '7':
			            $error = L('plub_11');
			            break;
		            case '8':
			            $error = 'File upload stopped by extension。';
			            break;
		            case '999':
		            default:
			            $error = L('plub_12');
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
				 $this->alert(L('plub_09'));
	        }
	        //检查文件大小
	        if ($file_size > $max_size) {
		        $this->alert(L('plub_07'));
	        }
	        //检查目录名
            if($dir_name=='file') $dir_name='papers';
	        if (empty($ext_arr[$dir_name])) {
	            $this->alert(L('plub_13'));
	        }
            //检查图片文件是否正确
            if($dir_name=='image'){
				list($width, $height, $type, $attr) = getimagesize($tmp_name);
				if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
					@unlink($tmp_name);
                    $this->alert(L('plub_05'));
				}
			}
	        //获得文件扩展名
		    $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));
	        //检查扩展名
	        if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		        $this->alert(L('plub_04'));
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
						$this->alert($filepath,1);
					}else{
						@unlink($file_path);
	                    $this->alert(L('plub_14'));
					}

			}else{ //上传失败
				  $this->alert(L('plub_14'));
			}
	}

	//错误输出
	public function alert($msg,$err=1) {
		if($err==0){
		    echo json_encode(array('error' => 1, 'message' => get_bm($msg,'gbk','utf-8')));
		}else{
            echo json_encode(array('error' => 0, 'url' => annexlink($msg)));
		}
        exit();
	}
}