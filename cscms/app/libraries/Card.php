<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-04-27
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ���ӿ����
 */
class Card {

  		public function __construct() {

			   $this->url="http://card.chshcms.com/index.php/home/";
  		}

        //Զ�̻�ȡKEY
        public function keys($admin) {
			    $url=$this->url.'keys?code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'version' => CS_Version,
			    )));
                $key=$this->get_url($url);
				return $key;
		}

        //������
        public function add($admin) {
                $key=$this->keys($admin);
			    $url=$this->url.'add?key='.$key.'&code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'version' => CS_Version,
			    )));
                $code=$this->get_url($url);
				return $code;
		}

        //������
        public function del($admin) {
                $key=$this->keys($admin);
			    $url=$this->url.'del?key='.$key.'&code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'version' => CS_Version,
			    )));
                $code=$this->get_url($url);
				return $code;
		}

        //��ȡ���ͼƬ��ַ
        public function pic($code,$admin) {
                $key=$this->keys($admin);
			    $url=$this->url.'?key='.$key.'&sn='.$code.'&code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'version' => CS_Version,
			    )));
                $pic_url=$this->get_url($url);
				return $pic_url;
		}

        //���������֤
        public function authe_rand($admin) {
                $key=$this->keys($admin);
			    $url=$this->url.'authe?key='.$key.'&code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'version' => CS_Version,
			    )));
                $zb_arr=$this->get_url($url);
				return $zb_arr;
		}

        //��֤��̬����
        public function verification($admin,$rand,$code) {
                $key=$this->keys($admin);
			    $url=$this->url.'verification?key='.$key.'&code='.cs_base64_encode(arraystring(array(
			           'host' => Web_Url,
			           'name' => Web_Name,
			           'admin' => $admin,
			           'code' => $code,
			           'rand' => $rand,
			           'version' => CS_Version,
			    )));
                $str=$this->get_url($url);
				return $str;
		}

        //��ȡԶ������
        public function get_url($url) {
                $arr=htmlall($url);
				if(empty($arr)){
                     admin_msg(L('curl_err'),@$_SERVER['HTTP_REFERER'],'no');  //��ȡԶ��ʧ��
				}else{

			         $arr = json_decode($arr, true);
                     $arr=get_bm($arr);
					 if($arr['status']==0){
                           admin_msg($arr['msg'],@$_SERVER['HTTP_REFERER'],'no');  //����״̬
					 }else{
				           return $arr['msg'];
					 }
				}
		}
}
