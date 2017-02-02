<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-21
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * �ֻ�������
 */
class Smstel {

    function __construct ()
	{
		   $this->appid   = CS_Sms_ID;  //�̻�ID
		   $this->appkey  = CS_Sms_Key;  //�̻�KEY
           $this->curl    = 'http://sms.chshcms.com/index.php/api/';
	}

    //����
	function add($tel,$neir){
		   $get='index?uid='.$this->appid;
		   $get.='&key='.$this->appkey;
		   $get.='&tel='.trim($tel);
		   $get.='&neir='.$neir.'��'.CS_Sms_Name.'��';
           $url=$this->curl.$get;
		   $msg=htmlall($url);
		   $msg=$this->error($msg);
		   return $msg;
    }

    //����ע����֤��
	function seadd($tel){

		   $tel_time=$_SESSION['tel_time'];
           if($tel_time && $tel_time+60>time()){
		       return 'addok'; //����ʱ��û�й�60��
		   }
		   $code=random_string('nozero',4);
		   $_SESSION['tel_code']=$code;
		   $_SESSION['tel_time']=time();		   

		   $neir='��ӭע��'.Web_Name.'��������֤����'.$code.'���뾡�������֤��(��Ǳ��˲������ɲ������)';
		   $get='index?uid='.$this->appid;
		   $get.='&key='.$this->appkey;
		   $get.='&tel='.trim($tel);
		   $get.='&neir='.$neir.'��'.CS_Sms_Name.'��';
           $url=$this->curl.$get;
		   $msg=htmlall($url);
		   $msg=$this->error($msg);
		   return $msg;
    }

    //��ѯ���
	function balance(){

		   $get='rmb?uid='.$this->appid;
		   $get.='&key='.$this->appkey;
           $url=$this->curl.$get;
		   $rmb=htmlall($url);
		   return $rmb;
    }

    //��ѯ��¼
	function lists($len=12,$p=1){

		   $get='lists?uid='.$this->appid;
		   $get.='&key='.$this->appkey;
		   $get.='&len='.$len;
		   $get.='&p='.$p;
           $url=$this->curl.$get;
		   $str=htmlall($url);
		   return $str;
    }

    //������ʾ
    function error($msg){
		    if(empty($msg)){
                 return L('curl_err');
			}
            return $msg;
	}
}


