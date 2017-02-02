<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-08
 */
class Saomiao extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('dance');
            $this->load->helper('file');
		    $this->load->model('CsdjAdmin');
            $this->load->library('mp3file');
	        $this->CsdjAdmin->Admin_Login();
            require_once CSCMS.'lib/Cs_FtpSm.php';
	}

    //����ɨ��
	public function index()
	{
            $data['hz']="mp3|wma|mp4|flv|avi";
            $this->load->view('saomiao.html',$data);
	}

    //��ȡĿ¼����
	public function yps()
	{
 	        $dir = $this->input->get_post('dir');
 	        $path = $this->input->get_post('path');
 	        $hz = $this->input->get_post('hz');
            if(empty($path)) $path="/";

            $dir=str_replace("\\","/",$dir);			
            $dir=str_replace("//","/",$dir);

            $path=str_replace("\\","/",$path);
            $path=str_replace("//","/",$path);

            $hz=str_replace("php","",$hz);
            $hz=str_replace("||","|",$hz);

            if(empty($dir)){
                    $paths=".".$path;
            }else{
                    $paths=$dir.$path;
            }
            $paths=str_replace("//","/",$paths);
            $showarr=get_dir_file_info($paths, $top_level_only = TRUE);

            $data['path']=$path;
            $data['dir']=$dir;
            $data['hz']=$hz;
            $data['paths']=$paths;

	        //�����չ��
			$ext_arr = explode("|", $hz);
            $dirs=$list=array();
		    if ($showarr) {
			    foreach ($showarr as $t) {
				    if (is_dir($t['server_path'])) {
					        $dirs[] = array(
						       'name' => $t['name'],
						       'date' => date('Y-m-d H:i:s',$t['date']),
						       'size' => '--',
						       'icon' => Web_Path.'packs/admin/images/ext/dir.gif',
						       'link' => site_url('dance/admin/saomiao/yps')."?dir=".$dir."&hz=".$hz."&path=".$path."/".$t['name'],
						       'rklink' => site_url('dance/admin/saomiao/ruku')."?dir=".$dir."&id=".$path."/".$t['name'],
					        );
				    } else {
						$exts = strtolower(trim(strrchr($t['name'], '.'), '.'));
	        			if (in_array($exts,$ext_arr) !== false) {
							$times=date('Y-m-d H:i:s',$t['date']);
						    $list[] = array(
							    'name' => $t['name'],
							    'ext' => get_extpic($exts),
						        'date' => (date('Y-m-d',$t['date'])==date('Y-m-d'))?'<font color=red>'.$times.'<font>':$times,
						        'size' => formatsize($t['size']),
							    'icon' => Web_Path.'packs/admin/images/ext/'.get_extpic($exts).'.gif',
							    'rklink' => site_url('dance/admin/saomiao/ruku')."?dir=".$dir."&id=".$path."/".$t['name'],
						    );
						}
				    }
			    }
		    }
			$data['dirs']=$dirs;
			$data['show']=$list;
            $this->load->view('saomiao_look.html',$data);
	}

	//�������
	public function ruku()
	{
 	        $dir = $this->input->get_post('dir');
 	        $path = $this->input->get_post('path');
 	        $hz = $this->input->get_post('hz');
 	        $ids = $this->input->get_post('id');
            if(empty($path)) $path="/";
			if(empty($ids)) admin_msg('��Ǹ����ѡ��Ҫ��������~!','javascript:history.back();','no');

            $dir=str_replace("\\","/",$dir);			
            $dir=str_replace("//","/",$dir);

            $path=str_replace("\\","/",$path);
            $path=str_replace("//","/",$path);

            $hz=str_replace("php","",$hz);
            $hz=str_replace("||","|",$hz);

            if(empty($dir)){
                    $paths=".".$path;
            }else{
                    $paths=$dir.$path;
            }
            $paths=str_replace("//","/",$paths);
            
			$files='';
			if(is_array($ids)){ //ȫѡ���
                foreach ($ids as $t) {
                    $file=$paths.'/'.$t;
                    $files.=str_replace("//","/",$file)."\r\n";
			    }
			    $files.='##cscms##';
			    $files=str_replace("\r\n##cscms##","",$files);
			}else{ //���ļ����
                $files=$paths.str_replace("//","/",$ids);
				$files=str_replace("//","/",$files);
			}
            $data['path']=$path;
            $data['dir']=$dir;
            $data['hz']=$hz;
			$data['files']=$files;
            $this->load->view('saomiao_ruku.html',$data);
	}

	//ȷ�����
	public function save()
	{
 	        $dir = $this->input->post('dir');
 	        $path = $this->input->post('path');
 	        $hz = $this->input->post('hz');
 	        $playhz = $this->input->post('playhz');
 	        $files = $this->input->post('files');
            $cid = intval($this->input->post('cid',true));
            $user = $this->input->post('user',true);
            $singer = $this->input->post('singer',true);

			if(empty($files)) admin_msg(L('plub_88'),'javascript:history.back();','no');
			if($cid==0) admin_msg(L('plub_89'),'javascript:history.back();','no');

            $dir=str_replace("\\","/",$dir);			
            $dir=str_replace("//","/",$dir);

            $path=str_replace("\\","/",$path);
            $path=str_replace("//","/",$path);

            $hz=str_replace("php","",$hz);
            $hz=str_replace("||","|",$hz);

            //��⿪ʼ
            $data['cid']=$cid;
            $data['yid']=intval($this->input->post('yid'));
            $data['tid']=intval($this->input->post('tid'));
            $data['fid']=intval($this->input->post('fid'));
            $data['reco']=intval($this->input->post('reco'));
            $data['uid']=intval(getzd('user','id',$user,'name'));
            $data['lrc']='';
            $data['text']='';
            $data['cion']=intval($this->input->post('cion'));
            $data['vip']=intval($this->input->post('vip'));
            $data['level']=intval($this->input->post('level'));
            $data['tags']=$this->input->post('tags',true);
            $data['zc']=$this->input->post('zc',true);
            $data['zq']=$this->input->post('zq',true);
            $data['bq']=$this->input->post('bq',true);
            $data['hy']=$this->input->post('hy',true);
            $data['singerid']=intval(getzd('singer','id',$singer,'name'));
            $data['skins']=$this->input->post('skins',true);
            $data['addtime']=time();

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet">';

			$filearr=explode("\r\n",$files);
            for($i=0;$i<count($filearr);$i++){
                  $file=$filearr[$i];
                  $file=str_replace("//","/",$file);
                  if(substr($file,0,2)=="./"){
                        $file=substr($file,1);
						$dir=$_SERVER['DOCUMENT_ROOT'];
                        $file=$dir.$file;
                  }
				  if(is_dir($file)){  //�ļ���
					     $strs=dirtofiles($file,$hz);
						 if(!empty($strs)){
                             foreach ($strs as $value) {  
                                  if(!empty($value)){
                                        $dance=addslashes($value);
										$dance=str_replace($dir,"",$dance);
										$exts = strtolower(trim(strrchr($dance, '.'), '.'));
										$name=explode("/",$dance);
                                        $data['name']=str_replace('.'.$exts,'',$name[count($name)-1]);
										$data['purl']=(!empty($playhz))?str_replace('.'.$exts,'.'.$playhz,$dance):$dance;
                                        $data['durl']=$dance;

										//�ж������Ƿ����
										$row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dance where durl='".$data['durl']."'")->row();
										if($row){

                                            echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=red>".$value.L('plub_90')."</font>".L('plub_91')."</font>";

										}else{

										     $data['dx']=formatsize(filesize($value));
										     $info=$this->djinfo($value);
										     if($info){
											     $data['dx']=$info['dx'];
											     $data['yz']=$info['yz'];
											     $data['sc']=$info['sc'];
										     }

                                             $this->CsdjDB->get_insert('dance',$data);
                                             echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=#0000ff>".$value."</font>".L('plub_92')."</font>";
										}
								  }
							 }
						 }

				  }else{  //�ļ�

                         if(!empty($file)){

                                $dance=addslashes($file);
								$dance=str_replace($dir,"",$dance);
								$exts = strtolower(trim(strrchr($dance, '.'), '.'));
								$name=explode("/",$dance);
                                $data['name']=str_replace('.'.$exts,'',$name[count($name)-1]);
								$data['purl']=(!empty($playhz))?str_replace('.'.$exts,'.'.$playhz,$dance):$dance;
                                $data['durl']=$dance;

								//�ж������Ƿ����
								$row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dance where durl='".$data['durl']."'")->row();
								if($row){

                                      echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=red>".$file.L('plub_90')."</font>".L('plub_91')."</font>";

								}else{

									  $data['dx']=formatsize(filesize($file));
								      $info=$this->djinfo($file);
								      if($info){
										   $data['dx']=$info['dx'];
										   $data['yz']=$info['yz'];
										   $data['sc']=$info['sc'];
								      }

                                      $this->CsdjDB->get_insert('dance',$data);
                                      echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=#0000ff>".$file."</font>".L('plub_92')."</font>";
								}
						 }
				  }
			}
            die("<br>&nbsp;&nbsp;&nbsp;&nbsp;<font style=color:red;font-size:14pt;><b>".L('plub_93')."</b></font><script>setTimeout('ReadGo();',3000);function ReadGo(){window.location.href ='javascript:history.go(-2);'}</script>");
	}

    //FTPɨ��
	public function ftp()
	{
		    if(FTP_Sm_Server=='' || FTP_Sm_Name==''){
                  admin_msg(L('plub_94'),site_url('dance/admin/saomiao/ftp_config'),'no');
			}
            $this->load->view('saomiao_ftp.html');
	}

    //FTPɨ������
	public function ftp_config()
	{
            $this->load->view('saomiao_ftp_config.html');
	}

    //FTPɨ�����ñ���
	public function ftpsave()
	{
		    $FTP_Sm_Server = $this->input->post('FTP_Sm_Server', TRUE);
		    $FTP_Sm_Port = intval($this->input->post('FTP_Sm_Port', TRUE));
		    $FTP_Sm_Name = $this->input->post('FTP_Sm_Name', TRUE);
		    $FTP_Sm_Pass = $this->input->post('FTP_Sm_Pass', TRUE);
		    $FTP_Sm_Ive = $this->input->post('FTP_Sm_Ive', TRUE);

            if($FTP_Sm_Port==0)   $FTP_Sm_Port=21;
			$ypass=substr(FTP_Sm_Pass,0,3).'*****'.substr(FTP_Sm_Pass,-3);
			if($ypass==$FTP_Sm_Pass) $FTP_Sm_Pass=FTP_Sm_Pass;

	        $strs="<?php"."\r\n";
	        $strs.="define('FTP_Sm_Server','".$FTP_Sm_Server."');  //Զ��FTP������IP   \r\n";
	        $strs.="define('FTP_Sm_Port','".$FTP_Sm_Port."');  //Զ��FTP�˿�  \r\n";
	        $strs.="define('FTP_Sm_Name','".$FTP_Sm_Name."');  //Զ��FTP�ʺ�  \r\n";
	        $strs.="define('FTP_Sm_Pass','".$FTP_Sm_Pass."');  //Զ��FTP����  \r\n";
	        $strs.="define('FTP_Sm_Ive',".$FTP_Sm_Ive.");  //�Ƿ�ʹ�ñ���ģʽ";

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_FtpSm.php', $strs)){
                      admin_msg(L('plub_95'),site_url('dance/admin/saomiao/ftp_config'),'no');
            }else{
                      admin_msg(L('plub_96'),site_url('dance/admin/saomiao/ftp'));
            }
	}

	//FTPɨ�����
	public function ftpruku()
	{
 	        $hz = $this->input->post('hz');
 	        $path = $this->input->post('path');
 	        $playhz = $this->input->post('playhz');
 	        $files = $this->input->post('files');
            $cid = intval($this->input->post('cid',true));
            $user = $this->input->post('user',true);
            $singer = $this->input->post('singer',true);

			if(empty($path)) admin_msg(L('plub_97'),'javascript:history.back();','no');
			if($cid==0) admin_msg(L('plub_89'),'javascript:history.back();','no');

            if(empty($path)) $path="/";
            if(substr($path,0,1)!="/") $path="/".$path;
            if(substr($path,-1)!="/") $path=$path."/";

            $path=str_replace("\\","/",$path);
            $path=str_replace("//","/",$path);
            $paths=".".$path;


            //��⿪ʼ
            $data['cid']=$cid;
            $data['yid']=intval($this->input->post('yid'));
            $data['fid']=intval($this->input->post('fid'));
            $data['tid']=intval($this->input->post('tid'));
            $data['reco']=intval($this->input->post('reco'));
            $data['uid']=intval(getzd('user','id',$user,'name'));
            $data['lrc']='';
            $data['text']='';
            $data['cion']=intval($this->input->post('cion'));
            $data['vip']=intval($this->input->post('vip'));
            $data['level']=intval($this->input->post('level'));
            $data['tags']=$this->input->post('tags',true);
            $data['zc']=$this->input->post('zc',true);
            $data['zq']=$this->input->post('zq',true);
            $data['bq']=$this->input->post('bq',true);
            $data['hy']=$this->input->post('hy',true);
            $data['singerid']=intval(getzd('singer','id',$singer,'name'));
            $data['skins']=$this->input->post('skins',true);
            $data['addtime']=time();

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet">';

		    $this->load->library('ftp');
		    if ($this->ftp->connect(array(
				'port' => FTP_Sm_Port,
				'debug' => FALSE,
				'passive' => FTP_Sm_Ive,
				'hostname' => FTP_Sm_Server,
				'username' => FTP_Sm_Name,
				'password' => FTP_Sm_Pass,
		    ))) { // ����ftp�ɹ�
                $arrs = $this->ftp->list_files($paths);
				$this->ftp->close();
		    }else{
                admin_msg(L('plub_98'),'javascript:history.back();','no');
			}

            $ext_arr = explode("|", $hz);

            if(!empty($arrs)){

                $i=0;
                foreach ($arrs as $file) {  
                        $file=get_bm($file);
                        $dance=addslashes($file);
				        if(strpos($dance,$path) === FALSE){
                            $dance=$path.$dance;
						}elseif(substr($dance,0,1)=='.'){
						    $dance=	substr($dance,1);
						}
						$exts = strtolower(trim(strrchr($dance, '.'), '.'));

						if (in_array($exts,$ext_arr) !== false) {

								$name=explode("/",$dance);
                                $data['name']=str_replace('.'.$exts,'',$name[count($name)-1]);
								$data['purl']=(!empty($playhz))?str_replace('.'.$exts,'.'.$playhz,$dance):$dance;
                                $data['durl']=$dance;

								//�ж������Ƿ����
								$row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dance where durl='".$data['durl']."'")->row();
								if($row){

                                      echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=red>".$file.L('plub_90')."</font>".L('plub_91')."</font>";

								}else{

                                      $this->CsdjDB->get_insert('dance',$data);
                                      echo "<br>&nbsp;&nbsp;<font style=font-size:10pt;>&nbsp;&nbsp;<font color=#0000ff>".$file."</font>".L('plub_92')."</font>";
								}
						 }
				  }
			}
            die("<br>&nbsp;&nbsp;&nbsp;&nbsp;<font style=color:red;font-size:14pt;><b>".L('plub_93')."</b></font><script>setTimeout('ReadGo();',3000);function ReadGo(){window.location.href ='javascript:history.go(-2);'}</script>");
	}

    //��ȡ��������
	public function djinfo($dir)
	{
        if(!file_exists($dir)) return false;
		$music = $this->mp3file->get_metadata($dir);
        if(!empty($music['Filesize']) && !empty($music['Bitrate']) && !empty($music['Length mm:ss'])){
      	    return array("dx"=>formatsize($music['Filesize']),"yz"=>$music['Bitrate']." Kbps","sc"=>$music['Length mm:ss']);
   	    }else{
      		return false;
   	    }
	}
}

