<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-01
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Upgrade extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
            $this->load->helper('file');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
			$this->lang->load('admin_upgrade');
			$this->_upgrade = "http://upgrade.chshcms.com/"; //����������ַ
			$this->_filearr = array('.','packs','cscms'); //MD5Ч��Ŀ¼
	}

    //ϵͳ����
	public function index()
	{
			$cscms_update = htmlall($this->_upgrade.'ajax/update?charset=gbk&version='.CS_Version.'&update='.CS_Uptime);
            $data['cscms_update'] = json_decode($cscms_update,1);
            $this->load->view('upgrade.html',$data);
	}

	//���߸���
	public function init() 
	{
 	        $id = intval($this->input->get('id'));
			if($id==0) admin_msg(L('plub_01'),'javascript:history.back();','no');

			//��ǰ�汾����
            $versiondata=read_file(CSCMS.'lib/Cs_Version.php');

			//���������ļ���
		    $dircache=FCPATH.'cache/upgrade/';
			if(!file_exists($dircache)) {
				mkdirss($dircache);
			}

	        $this->load->library('csapp');
            $zip='http://www.chshcms.com/down/xia/gbk/'.$id.'.html';
            $zippath=FCPATH."attachment/other/backup_".$id.".zip";
            $files_file=$this->csapp->down($zip,$zippath);
    	    if($files_file=='-1') admin_msg(L('plub_02'),'javascript:history.back();','no');
    	    if($files_file=='-2') admin_msg(L('plub_03'),'javascript:history.back();','no');
    	    if($files_file=='-3') admin_msg(L('plub_04'),'javascript:history.back();','no');
    	    if($files_file=='10001') admin_msg(L('plub_05'),'javascript:history.back();','no');
    	    if($files_file=='10002') admin_msg(L('plub_06'),'javascript:history.back();','no');
	        if(filesize($zippath) == 0) admin_msg(L('plub_07'),'javascript:history.back();','no');

		    //��ѹ��
	        $this->load->library('cszip');
		    $this->cszip->PclZip($zippath);
		    if ($this->cszip->extract(PCLZIP_OPT_PATH, $dircache, PCLZIP_OPT_REPLACE_NEWER) == 0) {
                   @unlink($zippath);
                   admin_msg(L('plub_08'),'javascript:history.back();','no');
		    }

			//���ǵ���Ŀ¼
			$this->copyfailnum = 0;
			$this->copymsg = array();
			$this->copydir($dircache, FCPATH);
				
			//����ļ�����Ȩ�ޣ��Ƿ��Ƴɹ�
			if($this->copyfailnum > 0) {
					//���ʧ�ܣ��ָ���ǰ�汾
					write_file(CSCMS.'lib/Cs_Version.php', $versiondata);
					echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
					$k=1;
                    foreach ($this->copymsg as $msg) {
					    echo '&nbsp;&nbsp;'.$k.'.'.$msg;
						$k++;
					}
					echo '&nbsp;&nbsp;<b style="color:red">'.L('plub_09').'&nbsp;&nbsp;<a href="'.site_url('upgrade').'">'.L('plub_10').'</a</b>';
					@unlink($zippath);
					exit;
			}

			//���ִ��SQL���
			$this->upsql($dircache);

            //ɾ���ļ�
			@unlink($zippath);
			//ɾ���ļ���
	 		deldir($dircache);
            //�ɹ���ʾ
            admin_msg(L('plub_11'),site_url('upgrade'));
	}

	//�鿴����
	public function look()
	{
		    $path = $this->input->get_post('path',true);
		    $path = isset($path) && trim($path) ? stripslashes(urldecode(trim($path))) : die(L('plub_12'));
		    $path = str_replace("..","",$path);
		
		    if (!file_exists(FCPATH.$path) || is_dir($path)) {
				 die(L('plub_13'));
		    }
		    $data['html'] = str_replace('</textarea>','&lt;/textarea&gt;',file_get_contents(FCPATH.$path));
		    //�ж���Ҫ�ļ������ܲ鿴
		    if(strpos(strtolower($path),'cscms/lib') !== FALSE){
			    die(L('plub_14'));
		    }
            $this->load->view('upgrade_look.html',$data);
	}

	//�ļ�MD5��Ч
	public function checkfile() 
	{
		    if(!empty($_GET['ok'])) {
				$data['diff']=array();
				$data['lostfile']=array();
				$data['unknowfile']=array();
			    $this->filemd5('.');

			    //��ȡcscms�ӿ�
			    $cscms_md5 = htmlall($this->_upgrade.'ajax/filemd5?update='.CS_Uptime);
			    $cscms_md5_arr = json_decode($cscms_md5, 1);

			    if(!empty($cscms_md5_arr)){
			        //��������
			        $diff = array_diff($cscms_md5_arr, $this->md5_arr);

			        //��ʧ�ļ��б�
			        $lostfile = array();
			        foreach($cscms_md5_arr as $k=>$v) {
				        if(!in_array($k, array_keys($this->md5_arr))) {
					        $lostfile[] = $k;
					        unset($diff[$k]);
				        }
			        }
			        $data['diff'] = $diff;
					$data['lostfile'] = $lostfile;

			        //δ֪�ļ��б�
			        $data['unknowfile'] = array_diff(array_keys($this->md5_arr), array_keys($cscms_md5_arr));
				}
			    $this->load->view('upgrade_md5.html',$data);

			} else {
			        admin_msg(L('plub_15'),site_url('upgrade/checkfile').'?ok=1','ok');  //�����ɹ�
			}
	}

	//�ļ�MD5����
	private function filemd5($path='') 
	{
		$dir_arr = explode('/', dirname($path));
		if(is_dir($path)) {
			$handler = opendir($path);
			while(($filename = @readdir($handler)) !== false) {
				if(substr($filename, 0, 1) != ".") {
					$this->filemd5($path.'/'.$filename);
				}
			}
			closedir($handler);
		} else {
			if (dirname($path) == '.' || (isset($dir_arr[1]) && in_array($dir_arr[1], $this->_filearr))) {
				if(strpos($path,'cscms/lib/') === FALSE && strpos($path,'packs/install/') === FALSE){
					if(strpos($path,'cscms/tpl/') !== FALSE){
						if(strpos($path,'cscms/tpl/admin/') !== FALSE){
				             $this->md5_arr[base64_encode($path)] = md5_file($path);
						}
					}else{
				        $this->md5_arr[base64_encode($path)] = md5_file($path);
					}
				}
			}
		}
	}

    //�ļ�����
	public function copydir($dirfrom, $dirto) {

	    //���챸��Ŀ¼
		$dirbackup=FCPATH.'attachment/other/backup_'.date('Ymd').'/';

	    $handle = opendir($dirfrom); //�򿪵�ǰĿ¼
	    //ѭ����ȡ�ļ�
	    while(false !== ($file = readdir($handle))) {
	    	if($file != '.' && $file != '..'){ //�ų�"."��"."
		        //����Դ�ļ���
			    $filefrom = $dirfrom.'/'.$file;
		     	//����Ŀ���ļ���
		        $fileto = $dirto.'/'.$file;
		     	//���ɱ����ļ���
		        $filebackup = $dirbackup.'/'.$file;

		        if(is_dir($filefrom)){ //�������Ŀ¼������еݹ����
		            $this->copydir($filefrom, $fileto);
		        } else { //������ļ�����ֱ����copy��������
					//����ļ��������ȱ���ԭ�ļ�
					if(file_exists($fileto)){
						$ydata=read_file($fileto);
                        write_file(str_replace(FCPATH,$dirbackup,$fileto), $ydata); //����
					}
		            //�ж�SQL�ļ�
				    $exts = strtolower(trim(substr(strrchr($file, '.'), 1)));
				    if($exts!='sql' && $file!=L('plub_16')){
					    //��������
					    $data=read_file($filefrom);
					    if(!write_file($fileto, $data)) {
					         $this->copyfailnum++;
						     $this->copymsg[]=L('plub_17')." <font color=#ff3300>".str_replace("//","/",$filefrom)."</font> ".L('plub_18')." <font color=#0000ff>".str_replace("//","/",$fileto)."</font> ".L('plub_19')."<br />";
				        }
					}
		        }
	    	}
	    }
	}

    //����SQLִ��
	public function upsql($dirsql) {

	    $handle = opendir($dirsql); //�򿪵�ǰĿ¼
	    //ѭ����ȡ�ļ�
	    while(false !== ($file = readdir($handle))) {
	    	if($file != '.' && $file != '..'){ //�ų�"."��"."
		        //�ж�SQL�ļ�
				$exts = strtolower(trim(substr(strrchr($file, '.'), 1)));
				if($exts=='sql'){
					 //��ȡSQL���
					 $sql=read_file($dirsql.$file);
                     //ִ��SQL
					 if(!empty($sql)){
						   $sqlarr=explode(";",$sql);
	                       foreach ($sqlarr as $sqls) {
	                           $this->CsdjDB->get_table(str_replace('{prefix}', CS_SqlPrefix, $sqls));
	                       }
					 }
		        }
	    	}
	    }
	}
}

