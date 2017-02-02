<?php
/**
 * @Cscms v4.0 open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-07-27
 */
@header('Content-Type: text/html; charset=gbk');
//�ж��Ƿ�װ
if(!file_exists(FCPATH.'packs/install/install.lock') && strpos(REQUEST_URI,'index.php/install/') === FALSE){
	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
	$url = substr($url,0,-9);
	$url .= 'index.php/install/';
	header("location:".$url);exit;
}
//װ��ȫ�������ļ�
require_once 'Cs_Version.php';
require_once 'Cs_DB.php';
require_once 'Cs_Config.php';
require_once 'Cs_User.php';
require_once 'Cs_Home.php';
require_once 'Cs_Pay.php';
require_once 'Cs_Mail.php';
require_once 'Cs_Water.php';
require_once 'Cs_Denglu.php';
require_once 'Cs_Ftp.php';
require_once 'Cs_Sms.php';
//�ж���վ����״̬
if(!defined('IS_ADMIN') && Web_Off==0){
    die(Web_Onneir);
}
//�жϻ�Աϵͳ����
if(!defined('IS_ADMIN') && User_Mode==0 && strpos(REQUEST_URI,'user') !== FALSE){
	die(User_No_info);
}
//�ֻ��ͻ��˷��ʱ�ʾ
if(preg_match("/(iPhone|iPad|iPod|Android)/i", strtoupper($_SERVER['HTTP_USER_AGENT']))){
    if(Mobile_Is==1 && !defined('IS_ADMIN')){
	    define('MOBILE', true);	
	}
}
//�ֻ���������
if(Mobile_Is==1 && $_SERVER['HTTP_HOST']==Mobile_Url){
    define('MOBILE_YM', true);	
}
//�ж��ֻ��ͻ��˷���
if(defined('MOBILE')){
    if(!defined('IS_ADMIN') && Mobile_Is==1){
          if(Home_Ym==0 && Mobile_Url!='' && $_SERVER['HTTP_HOST']!=Mobile_Url){
				$Web_Link="http://".str_replace('//','/',Mobile_Url.Web_Path.cscms_cur_url());
                header("location:".$Web_Link);exit;
		  }
    }
}else{
     $Web_Link="http://".str_replace('//','/',Web_Url.Web_Path.cscms_cur_url());
	 if((Mobile_Is==0 || Mobile_Win==0) && $_SERVER['HTTP_HOST']==Mobile_Url){
           header("location:".$Web_Link);exit;
	 }
}
//�ж��ֻ���������
if(defined('MOBILE_YM') && !defined('MOBILE')){
	define('MOBILE', true);	
}
//�жϻ�Ա��ҳ������
if(Home_Ym==1){
    $HOME_YMALL=explode(".",$_SERVER['HTTP_HOST']);
	$HOME_YM=str_replace($HOME_YMALL[0].'.', "",$_SERVER['HTTP_HOST']);
	$HOME_EXT = explode('|',Home_Ymext);
	if($HOME_YMALL[0]!='www' && !in_array($HOME_YMALL[0], $HOME_EXT) && $HOME_YM==Home_YmUrl){
		define('HOMEPATH', 'home');
	}
}
//�жϰ�������
$_ERYM=FALSE;
if(file_exists(CSCMS.'lib/Cs_Domain.php')){
    $_CS_Domain = require_once(CSCMS.'lib/Cs_Domain.php');
    if (is_array($_CS_Domain)) {
          foreach ($_CS_Domain as $key => $host) {
                if($_SERVER['HTTP_HOST']==$host){
					if($key=='user'){ //��Ա���Ķ�������
						 define('USERPATH', $key);
						 //�жϰ���Ա��������
						 $REQUEST=str_replace('index.php/','',REQUEST_URI);
						 $SELFALL=(substr($REQUEST,0,1)=='/')?substr($REQUEST,1):$REQUEST;
						 $Mxall=explode("/",$SELFALL);
						 if(file_exists(FCPATH.'plugins/'.$Mxall[0].'/config/site.php')){
			                 define('PLUBPATH', $Mxall[0]);
							 $app_folder = 'plugins/'.$Mxall[0];
						 }
					}else{ //����������
						if (!defined('PLUBPATH')) {
							 $app_folder = 'plugins/'.$key;
			                 define('PLUBPATH', $key);
						}
					}
					$_ERYM=TRUE;
					break;
                }
		  }
    }
}
//���δ��������������ð��ʵ��Ŀ¼
if(!$_ERYM){
	 $SELF='';
     if(strpos(REQUEST_URI,SELF)!==FALSE){ //��̬ģʽ
          $SELFALL=explode(SELF."/",REQUEST_URI);
          if(count($SELFALL)>1){
	          $Mxall=explode("/",$SELFALL[1]);
              $SELF=str_replace("/", "", $Mxall[0]);
          }
     }else{ //α��̬ģʽ
          $SELFALL=(substr(REQUEST_URI,0,1)=='/')?substr(REQUEST_URI,1):REQUEST_URI;
	      $Mxall=explode("/",$SELFALL);
          $SELF=str_replace("/", "", $Mxall[0]);
	 }
	 //���
     if(!empty($SELF) && is_dir(FCPATH.'plugins/'.$SELF) && file_exists(FCPATH.'plugins/'.$SELF.'/controllers/index.php')){
          $app_folder = 'plugins/'.$SELF;
	      define('PLUBPATH', $SELF);
     }
	 //��Ա����
     if((!defined('PLUBPATH') && $SELF=='user') || (defined('PLUBPATH') && strpos(REQUEST_URI,$SELF.'/user')!==FALSE)){
		 define('USERPATH', $SELF);
	 }
	 //��Ա��ҳ
	 if(!empty($Mxall) && count($Mxall)>1){
	     $Hall=explode("?",$Mxall[1]);
         $SELF=str_replace("/", "", $Hall[0]);
	 }
	 if((!defined('PLUBPATH') && $SELF=='home') || (defined('PLUBPATH') && strpos(REQUEST_URI,$SELF.'/home')!==FALSE)){
	      define('HOMEPATH', $SELF);
     }
}
//�жϰ���Ա������
if(!defined('IS_ADMIN') && !defined('PLUBPATH')){
     $arr=parse_url(REQUEST_URI);
     $re_url=str_replace("/".SELF."/", "",$arr['path']);
	 if(substr($re_url,0,1)=='/') $re_url=substr($re_url,1);
     $permarr=explode('/',$re_url);
	 $SELF=$permarr[0];
     if(!empty($SELF)){
            //�ֻ�����
		    if(defined('MOBILE') && is_dir(FCPATH.'plugins/'.$SELF) && !file_exists(CSCMS.'app/controllers/home/'.$SELF.'.php')){
                  define('PLUBPATH', $SELF);
				  $app_folder = 'plugins/'.$SELF;
			//��Ա
			}elseif((defined('USERPATH') || defined('HOMEPATH')) && is_dir(FCPATH.'plugins/'.$SELF) && !file_exists(CSCMS.'app/controllers/user/'.$SELF.'.php')){
                  define('PLUBPATH', $SELF);
				  $app_folder = 'plugins/'.$SELF;
			}
	 }
	 //�жϻ�Ա��ҳ���
     if(!defined('PLUBPATH') && defined('HOMEPATH')){
		    if(count($permarr)>2){
	            $SELF=$permarr[2];
                //�ֻ�����
		        if(defined('MOBILE') && is_dir(FCPATH.'plugins/'.$SELF) && !file_exists(CSCMS.'app/controllers/home/'.$SELF.'.php')){
                      define('PLUBPATH', $SELF);
				      $app_folder = 'plugins/'.$SELF;
			    //��Ա
			    }elseif((defined('USERPATH') || defined('HOMEPATH')) && is_dir(FCPATH.'plugins/'.$SELF) && !file_exists(CSCMS.'app/controllers/home/'.$SELF.'.php')){
                      define('PLUBPATH', $SELF);
				      $app_folder = 'plugins/'.$SELF;
			    }
		    }
     }
	 //��Ա��ҳ���
	 if(!defined('PLUBPATH') && defined('HOMEPATH')){
         if(is_dir(FCPATH.'plugins/'.$SELF) && file_exists(FCPATH.'plugins/'.$SELF.'/controllers/index.php')){
              $app_folder = 'plugins/'.$SELF;
	          define('PLUBPATH', $SELF);
         }
	 }
}
//��ȡ��ǰĿ¼·������
function cscms_cur_url() { 
    if(!empty($_SERVER["REQUEST_URI"])){ 
         $scrtName = $_SERVER["REQUEST_URI"]; 
         $nowurl = $scrtName; 
    } else { 
         $scrtName = $_SERVER["PHP_SELF"]; 
         if(empty($_SERVER["QUERY_STRING"])) { 
             $nowurl = $scrtName; 
         } else { 
             $nowurl = $scrtName."?".$_SERVER["QUERY_STRING"]; 
         } 
    } 
    return $nowurl; 
} 