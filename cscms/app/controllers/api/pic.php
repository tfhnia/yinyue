<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pic extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			if(empty($_SERVER['HTTP_REFERER'])) exit('error 404');
	}

	public function index()
	{
		   $size = $this->input->get('size');
		   $arr = explode('/picdata/',REQUEST_URI);
		   if(empty($arr[1])){
		        header("location:".piclink('pic',''));exit;
		   }
		   $arr[1]='attachment/'.$arr[1];
		   $arrs = explode('?',$arr[1]);
		   if(UP_Mode==1 && UP_Pan!=''){
               $files = UP_Pan.$arrs[0];
		   }else{
               $files = FCPATH.$arrs[0];
		   }
           $files = str_replace("//","/",$files);
		   if(!file_exists($files)){
		        header("location:".piclink('pic',''));exit;
		   }

           if(!empty($size)){
		       $wh = explode('*',$size);
		       $w = intval($wh[0]);
		       $h = intval($wh[1]);
			   if($w>800 || $h>800){
		           $w = 0;
		           $h = 0;
			   }
		   }else{
		       $w = 0;
		       $h = 0;
		   }
		   //û�����Ż��߳���800��ֱ��ת��ָ��ͼƬ
		   if($w==0 && $h==0){
		   		header("location:".annexlink($arrs[0]));exit;
		   }

		   //�жϻ����Ƿ����
		   $file_ext = strtolower(trim(substr(strrchr($arrs[0], '.'), 1)));
	  	   $cachedir = 'cache/suo_pic/'.str_replace("attachment/","",$arrs[0]).'_'.$w.'_'.$h.'.'.$file_ext;
		   $cachedir = str_replace("//","/",$cachedir);
		   if(file_exists(FCPATH.$cachedir)){
			   //��Աͷ��1Сʱ����һ��
			   if(strpos($arrs[0],'/logo/') === FALSE || (time() - filemtime(FCPATH.$cachedir)) < 3600){
		   		    header("location:http://".Web_Url.Web_Path.$cachedir);exit;
			   }
		   }

		   //���������ļ���
		   $temp=pathinfo(FCPATH.$cachedir);
		   mkdirss($temp["dirname"]);
		   $params['file'] = $files;
		   $params['cache'] = FCPATH.$cachedir;
		   //���ؿ���
           $this->load->library('slpic',$params);
		   //����ͼƬ
           $this->slpic->resize($w,$h);
	}
}
