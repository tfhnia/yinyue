<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-21
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ������֤��
 * @author yanyujiangnan
 * ���÷�
 * $this->load->library('code');
 * $this->code->getCode(); //�����֤��
 * $this->code->doimg(); //���ͼƬ
 */
class Code {

		private $charset = 'ABCDEFGHIGKLMNOPQRSTUVWXYZ23456789'; //�������
  		private $code;       //��֤��
  		private $codelen = 5;     //��֤�볤��
  		private $width = 180;     //���
  		private $height = 50;     //�߶�
  		private $img;        //ͼ����Դ���
  		private $font;        //ָ��������
  		private $fontsize = 22;    //ָ�������С
  		private $fontcolor;      //ָ��������ɫ
  
  		//���췽����ʼ��
  		public function __construct($params=array()) {
            if(!empty($params['width'])) $this->width=$params['width'];     //��֤��Ŀ��
            if(!empty($params['height'])) $this->height=$params['height'];  //��֤��ĸ�
            if(!empty($params['size'])) $this->fontsize = $params['size']; //�����С
            if(!empty($params['len'])) $this->codelen = $params['len']; //��֤�볤��
   			$this->font = BASEPATH.'fonts/arrusbt.ttf';
  		}
  
 		 //���������
  		private function createCode() {
   			$_len = strlen($this->charset)-1;
   			for ($i=0;$i<$this->codelen;$i++) {
    				$this->code .= $this->charset[mt_rand(0,$_len)];
   			}
  		}
  
  		//���ɱ���
  		private function createBg() {
   			$this->img = imagecreatetruecolor($this->width, $this->height);
   			$color = imagecolorallocate($this->img, 245, 255, 255);
   			imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
  		}
  
  		//��������
  		private function createFont() { 
   			$_x = $this->width / $this->codelen;
   			for ($i=0;$i<$this->codelen;$i++) {
    				$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
    				imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
   			}
  		}
  
  		//����������ѩ��
  		private function createLine() {
   			for ($i=0;$i<6;$i++) {
    				$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
    				imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
   			}
   			for ($i=0;$i<100;$i++) {
    				$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(100,255),mt_rand(200,255));
    				imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
   			}
  		}
  
  		//���
  		private function outPut() {
   			header('Content-type:image/png');
   			imagepng($this->img);
   			imagedestroy($this->img);
  		}
  
  		//��������
  		public function doimg() {
  	 		$this->outPut();
  		}
  
  		//��ȡ��֤��
  		public function getCode() {
   			$this->createBg();
   			$this->createCode();
   			$this->createLine();
   			$this->createFont();
   			return strtolower($this->code);
  		}
}
