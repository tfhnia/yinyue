<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-18
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * �ϴ�������
 */
class Csup {

    public function __construct ()
	{
		error_reporting(0); //�رմ���
		set_time_limit(0); //��ʱʱ��
		require_once CSCMS.'lib/Cs_Qiniu.php';
		require_once CSCMS.'lib/Cs_Kpan.php';
	    require_once CSCMS.'lib/Cs_Oss.php';
	    require_once CSCMS.'lib/Cs_Upyun.php';
	}

    //�ϴ���ʽ
	public function init(){
        $str = array(
			'1'=>array(
			      'name'=>'���ؿռ�',
			      'type'=>'web',
			      'file'=>''
		     ),
			'2'=>array(
			      'name'=>'Զ��FTP',
			      'type'=>'ftp',
			      'file'=>''
		     ),
			'3'=>array(
			      'name'=>'��ţ����',
			      'type'=>'qiniu',
			      'file'=>'Cs_Qiniu.php'
		     ),
			'4'=>array(
			      'name'=>'��ɽ����',
			      'type'=>'kdisk',
			      'file'=>'Cs_Kpan.php'
		     ),
			'5'=>array(
			      'name'=>'�����ƴ洢',
			      'type'=>'oss',
			      'file'=>'Cs_Oss.php'
		     ),
			'6'=>array(
			      'name'=>'������',
			      'type'=>'upyun',
			      'file'=>'Cs_Upyun.php'
		     )
		);
		return $str;
    }

    //�ж��ϴ���ʽ
	public function up($file_path,$file_name=''){
		if(empty($file_path)) return false;
        $types=$this->init();
		if(UP_Mode>1){
			  $mode=$types[UP_Mode]['type']; //����
              $res=$this->$mode($file_path,$file_name);
			  return $res;
        }else{
		      return true;
		}
    }

	//��ȡ���ص�ַ
	public function down($ids){
        if($ids==3){  //��ţ����
			$linkurl=CS_Qn_Url;
			if($linkurl!=''){
                if(substr($linkurl,0,7)!='http://') $linkurl="http://".$linkurl;
			}else{
                $linkurl="http://".CS_Qn_Bk.".qiniudn.com";
			}
        }elseif($ids==4){  //����
              $linkurl="http://".Web_Url.Web_Path."packs/uploads/kdisk/acc.php?filename=";
        }elseif($ids==5){  //������
              $linkurl="http://".CS_Os_Bk.".oss-cn-hangzhou.aliyuncs.com";
        }elseif($ids==6){  //������
              $linkurl="http://".Web_Url.Web_Path."packs/uploads/upyun/down.php";
		}else{
              $linkurl="";
		}
        return $linkurl;
	}

	//�޸������ļ�
	public function edit($id){
        $str = $this->init();
		$files = $str[$id]['file'];

        if($id<3 || empty($files)) return false;
        $ci = &get_instance();

		//��ţ�洢
		if($id==3){ 
		    $CS_Qn_Bk = $ci->input->post('CS_Qn_Bk', TRUE);
		    $CS_Qn_Ak = $ci->input->post('CS_Qn_Ak', TRUE);
		    $CS_Qn_Sk = $ci->input->post('CS_Qn_Sk', TRUE);
		    $CS_Qn_Url = $ci->input->post('CS_Qn_Url', TRUE);
		    if (empty($CS_Qn_Bk)||empty($CS_Qn_Ak)||empty($CS_Qn_Sk)){
			    admin_msg('��ţ�ռ����ơ�AccessKey��SecretKey������Ϊ��','javascript:history.back();','no');
		    }
	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Qn_Bk','".$CS_Qn_Bk."'); //�ռ����� \r\n";
	        $strs.="define('CS_Qn_Ak','".$CS_Qn_Ak."'); //AK   \r\n";
	        $strs.="define('CS_Qn_Sk','".$CS_Qn_Sk."'); //SK  \r\n";
	        $strs.="define('CS_Qn_Url','".$CS_Qn_Url."'); //��ţ���ص�ַ  ";
		}

        //���̴洢
		if($id==4){
		    $CS_Kp_Ck = $ci->input->post('CS_Kp_Ck', TRUE);
		    $CS_Kp_Cs = $ci->input->post('CS_Kp_Cs', TRUE);
		    $CS_Kp_Acc = $ci->input->post('CS_Kp_Acc', TRUE);
		    $CS_Kp_Acc_Key = $ci->input->post('CS_Kp_Acc_Key', TRUE);

		    if (empty($CS_Kp_Ck) || empty($CS_Kp_Cs)){
			      admin_msg('����Ӧ��Ŀ¼��consumer_key��consumer_secret������Ϊ��','javascript:history.back();','no');
		    }

	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Kp_Ck','".$CS_Kp_Ck."'); //CK   \r\n";
	        $strs.="define('CS_Kp_Cs','".$CS_Kp_Cs."'); //Cs  \r\n";
	        $strs.="define('CS_Kp_Acc','".$CS_Kp_Acc."'); //AC  \r\n";
	        $strs.="define('CS_Kp_Acc_Key','".$CS_Kp_Acc_Key."'); //AK  ";
		}

        //�����ƴ洢
	    if($id==5){
		    $CS_Os_Bk = $ci->input->post('CS_Os_Bk', TRUE);
		    $CS_Os_Ai = $ci->input->post('CS_Os_Ai', TRUE);
		    $CS_Os_Ak = $ci->input->post('CS_Os_Ak', TRUE);
		    if (empty($CS_Os_Bk)||empty($CS_Os_Ai)||empty($CS_Os_Ak)){
			      admin_msg('BUCKET��ACCESS_ID��ACCESS_KEY������Ϊ��','javascript:history.back();','no');
		    }
	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Os_Bk','".$CS_Os_Bk."'); //BK  \r\n";
	        $strs.="define('CS_Os_Ai','".$CS_Os_Ai."'); //AI   \r\n";
	        $strs.="define('CS_Os_Ak','".$CS_Os_Ak."'); //AK  ";
		}

        //�����ƴ洢
	    if($id==6){
		    $CS_Upy_Name = $ci->input->post('CS_Upy_Name', TRUE);
		    $CS_Upy_Pwd = $ci->input->post('CS_Upy_Pwd', TRUE);
		    $CS_Upy_Bucket = $ci->input->post('CS_Upy_Bucket', TRUE);
		    if (empty($CS_Upy_Name)||empty($CS_Upy_Pwd)||empty($CS_Upy_Bucket)){
			      admin_msg('��Ȩ�˺š���Ȩ���롢�ռ���������Ϊ��','javascript:history.back();','no');
		    }
			if($CS_Upy_Pwd==substr(CS_Upy_Pwd,0,3).'******'){
                  $CS_Upy_Pwd=CS_Upy_Pwd;
			}
	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Upy_Name','".$CS_Upy_Name."'); //��������Ȩ�˺�  \r\n";
	        $strs.="define('CS_Upy_Pwd','".$CS_Upy_Pwd."'); //��������Ȩ����   \r\n";
	        $strs.="define('CS_Upy_Bucket','".$CS_Upy_Bucket."'); //�����ƿռ��� ";
		}

		//�޸������ļ�
        write_file(CSCMS.'lib/'.$files, $strs);
		return true;
	}

    //�ж�ɾ����ʽ
	public function del($file_path,$dir=''){
		if(empty($file_path)) return false;
		if(UP_Mode==2){ //FTP
              return $this->ftpdel($file_path);
		}elseif(UP_Mode==3){ //��ţ
              return $this->qiniudel($file_path);
		}elseif(UP_Mode==4){ //����
              return $this->kdiskdel($file_path);
		}elseif(UP_Mode==5){ //������
              return $this->ossdel($file_path);
		}elseif(UP_Mode==6){ //������
              return $this->upyundel($file_path);
        }else{
			  if(substr($file_path,0,7)=='http://'){
		          return true;
			  }
			  if(UP_Pan!=''){
			      $path = UP_Pan.$file_path;
			  }else{
			      $path = FCPATH.'attachment/'.$dir.'/'.$file_path;
			  }
			  $path = str_replace("//","/",$path);
			  unlink($path);
		      return true;
		}
    }

    //FTP�ϴ�
	public function ftp($file_path,$file_name){
		$ci = &get_instance();
		$ci->load->library('ftp');
		if ($ci->ftp->connect(array(
				'port' => FTP_Port,
				'debug' => false,
				'passive' => FTP_Ive,
				'hostname' => FTP_Server,
				'username' => FTP_Name,
				'password' => FTP_Pass,
		))) { // ����ftp�ɹ�
				$Dirs=FTP_Dir;
				if(substr($Dirs,-1)=='/') $Dirs=substr($Dirs,0,-1);
				$dir = $Dirs.'/'.date('Ymd').'/';
				$ci->ftp->mkdir($dir);
				if ($ci->ftp->upload($file_path, $dir.$file_name, SITE_ATTACH_MODE, 0775)) {
				     unlink($file_path);
				}else{
                     return false;
				}
				$ci->ftp->close();
		        return true;
		}
		return false;
    }

    //ɾ��Զ��FTP�ļ�
    public function ftpdel($file_path){
		$ci = &get_instance();
		$ci->load->library('ftp');
		if(substr($file_path,0,1)=='/') $file_path=substr($file_path,1);
		if ($ci->ftp->connect(array(
				'port' => FTP_Port,
				'debug' => FALSE,
				'passive' => FTP_Ive,
				'hostname' => FTP_Server,
				'username' => FTP_Name,
				'password' => FTP_Pass,
		))) { // ����ftp�ɹ�
				$Dirs=FTP_Dir;
				if(substr($Dirs,-1)=='/') $Dirs=substr($Dirs,0,-1);
				$path = $Dirs.'/'.$file_path;
				$res=$ci->ftp->delete_file(".".$path);
				$ci->ftp->close();
                if (!$res) {  //ʧ��
                    return FALSE;
                } else {  //�ɹ�
                    return TRUE;
                }
		}
    }

    //��ţ�ϴ�
	public function qiniu($file_path,$file_name){
		require_once CSCMSPATH.'uploads/qiniu/io.php';
		require_once CSCMSPATH.'uploads/qiniu/rs.php';

		$key1 = date('Ymd').'/'.$file_name;
		Qiniu_SetKeys(CS_Qn_Ak, CS_Qn_Sk);
		$putPolicy = new Qiniu_RS_PutPolicy(CS_Qn_Bk);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_PutFile($upToken, $key1, $file_path, $putExtra);
		if ($err !== null) {
   		    return false;
		} else {
			unlink($file_path);
    		return true;
		}
    }

    //ɾ����ţ�ļ�
    public function qiniudel($file_path){
		require_once CSCMSPATH.'uploads/qiniu/io.php';
		require_once CSCMSPATH.'uploads/qiniu/rs.php';
		Qiniu_SetKeys(CS_Qn_Ak, CS_Qn_Sk);
        $client = new Qiniu_MacHttpClient(null);
		if(substr($file_path,0,1)=='/') $file_path=substr($file_path,1);
        $err = Qiniu_RS_Delete($client, CS_Qn_Bk, $file_path);
        if ($err !== null) {  //ʧ��
            return FALSE;
        } else {  //�ɹ�
            return TRUE;
        }
    }

	//�����ϴ�
	public function kdisk($file_path,$file_name){
		require_once CSCMSPATH.'uploads/kdisk/kuaipan.class.php';
        $access_token_arr['oauth_token']=CS_Kp_Acc;
        $access_token_arr['oauth_token_secret']=CS_Kp_Acc_Key;
        $kp = new Kuaipan ( CS_Kp_Ck, CS_Kp_Cs);
        $params = array (
            'root' => 'app_folder',
            'path' => date('Ymd')
        );
        $ret = $kp->api ( 'fileops/create_folder', '', $params , 'GET', '' ,$access_token_arr);
        if (false === $ret) {
   		    return false;
        }

        $upload = $kp->api ( 'fileops/upload_locate', '', array (), 'GET', '' ,$access_token_arr);
        if (false !== $upload) {
            $params = array (
                'root' => 'app_folder',
                'overwrite' => 'true',
                'path' => date('Ymd').'/'.$file_name 
            );
            // ����upload�ľ��Ե�ַ
            $ret = $kp->api ( sprintf ( '%s/1/fileops/upload_file', rtrim ( $upload ['url'], '/' ) ), '', $params, 'POST', $file_path ,$access_token_arr );
            if (false === $ret) {
   		        return false;
            }
			unlink($file_path);
            return true;
        } else {
   		    return false;
		}
    }

    //ɾ�������ļ�
    public function kdiskdel($file_path){
		require_once CSCMSPATH.'uploads/kdisk/kuaipan.class.php';
		if(substr($file_path,0,1)=='/') $file_path=substr($file_path,1);
        $kp = new Kuaipan ( CS_Kp_Ck, CS_Kp_Cs);
        $params = array (
            'root' => 'app_folder',
            'path' => $file_path
        );
        $access_token_arr['oauth_token']=CS_Kp_Acc;
        $access_token_arr['oauth_token_secret']=CS_Kp_Acc_Key;
        $ret = $kp->api ( 'fileops/delete', '', $params  , 'GET', '' ,$access_token_arr);
        if (false === $ret) {
    		return false;
		}else{
            return true;
		}
    }

	//�������ϴ�
	public function oss($file_path,$file_name){
		require_once CSCMSPATH.'uploads/oss/sdk.class.php';
        $obj = new ALIOSS();
	    $dir=date('Ymd');
        $response  = $obj->create_object_dir(BUCKET,$dir);
		if ($response->status != '200') {
    		return false;
		}
        $object=date('Ymd').'/'.$file_name;
	    $response = $obj->upload_file_by_file(BUCKET,$object,$file_path);
		if ($response->status == 200) {
			unlink($file_path);
            return true;
		}else{
    		return false;
		}
    }

    //ɾ���������ļ�
    public function ossdel($file_path){
		require_once CSCMSPATH.'uploads/oss/sdk.class.php';
		if(substr($file_path,0,1)=='/') $file_path=substr($file_path,1);
        $obj = new ALIOSS();
	    $response = $obj->delete_object(BUCKET,$file_path);
		if ($response->status == 200) {
            return true;
		}else{
    		return false;
		}
    }

	//�������ϴ�
	public function upyun($file_path,$file_name){
		require_once CSCMSPATH.'uploads/upyun/upyun.class.php';
        $upyun = new UpYun(CS_Upy_Bucket, CS_Upy_Name, CS_Upy_Pwd);
        $object='/'.date('Ymd').'/'.$file_name;
		$data = @file_get_contents($file_path);
        $response = $upyun->writeFile($object, $data, True);
		if ($response) {
			unlink($file_path);
            return true;
		}else{
    		return false;
		}
    }

    //ɾ���������ļ�
    public function upyundel($file_path){
		require_once CSCMSPATH.'uploads/upyun/upyun.class.php';
        $upyun = new UpYun(CS_Upy_Bucket, CS_Upy_Name, CS_Upy_Pwd);
        $response = $upyun->delete($file_path);
		if ($response) {
            return true;
		}else{
    		return false;
		}
    }
}

