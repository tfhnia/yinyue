<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-19
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ��ƽ̨������
 */
class Csapp {

    function __construct ()
	{
		//.....
	}

    //��װURL
	function url($mx='plub',$data=''){
		$url = CS_YPTURL.$mx.'?time='.time().'&param='.cs_base64_encode(arraystring(array(
			'site' => Web_Url,
			'url'  => 'http://'.Web_Url.Web_Path,
			'name' => Web_Name,
			'data' => $data,
			'admin' => SELF,
			'version' => CS_Version,
			'charset' => CS_Charset,
			'uptime' => CS_Uptime,
		)));
		return $url;
    }

    //��ȡ��װ��ȨKEY
	function keys($data,$mx='plub'){
		$url = CS_YPTURL.$mx.'/key?param='.cs_base64_encode(arraystring(array(
			'site' => Web_Url,
			'url'  => 'http://'.Web_Url.Web_Path,
			'data' => $data,
			'admin' => SELF,
			'encry' => CS_Encryption_Key,
		)));
        return htmlall($url);
    }

    //����ģ�嵽����
    function down($file,$filename) {   
        if (! $file) {  
           return '-1';  
        }   
        if (ini_get('allow_url_fopen')) {
            $data=@file_get_contents($file);
        }
        if (empty($data) && function_exists('curl_init') && function_exists('curl_exec')) {
		$curl = curl_init(); //��ʼ��curl
		curl_setopt($curl, CURLOPT_URL, $file); //���÷��ʵ���վ��ַ
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //ģ���û�ʹ�õ������
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);    //�Զ�������·��Ϣ
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);      //���ó�ʱ���Ʒ�ֹ��ѭ��
		curl_setopt($curl, CURLOPT_HEADER, 0);         //��ʾ���ص�header��������
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //��ȡ����Ϣ���ļ�������ʽ����
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		$data = curl_exec($curl);
		curl_close($curl);
        }
        if(empty($data)){
            return '-2'; 
		}elseif($data=='10001' || $data=='10002' || $data=='10003'){
            return $data; 
        }else{
            if (!@file_put_contents($filename, $data)){
                 return '-3';
            }else{
                 return false;
            }
        }
    } 
}


