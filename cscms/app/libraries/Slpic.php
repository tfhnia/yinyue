<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Cscms open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-07-26
 */

class Slpic {
    //ԭͼƬ�ļ�������·�����ļ���
    private $orpic; 
    //ԭͼ����ʱͼ��
    private $tempic;
    //����ͼ
    private $thpic;
    //ԭ���
    private $width; 
    //ԭ�߶�
    private $height;
    //�¿��
    private $newswidth; 
    //�¸߶�
    private $newsheight;
    //���Ժ�Ŀ��
    private $thwidth;
    //���Ժ�ĸ߶�
    private $thheight;
	//���Ժ��Ŀ¼��ַ
    private $cache;
     
    public function __construct($params=array()){
		$file = $params['file'];
		$this->cache = $params['cache'];
        $this->orpic = $file;
        $infos = getimagesize($file);
        $this->width = $infos[0];
        $this->height = $infos[1];
        $this->type = $infos[2];
    }

    //�����û���ָ�����������������ͼ�ߴ�
    function cal_size(){
        //����ͼ����������߶ȱ�
        $thcrown = $this->newswidth/$this->newsheight;    
        //ԭͼ��߱�
        $crown = $this->width/$this->height;    
        $this->thwidth = $this->newswidth;
        $this->thheight = $this->newswidth/$crown;
		if($this->thheight<$this->newsheight){
             $this->thheight=$this->newsheight;
		}
    }
     
	//��ȡͼƬ����
    function init(){
        switch($this->type){
            case 1:     //GIF
                $this->tempic = imagecreatefromgif($this->orpic);
                break;
            case 2:     //JPG
                $this->tempic = imagecreatefromjpeg($this->orpic);
                break;
            case 3:     //PNG
                $this->tempic = imagecreatefrompng($this->orpic);
                break;
        }
    }
 
    //��СͼƬ
    function resize($maxwidth, $maxheight){
        $this->newswidth = ($maxwidth==0)?$this->width:$maxwidth;
        $this->newsheight = ($maxheight==0)?$this->height:$maxheight;
        //��ʼ��ͼ��
        $this->init();
        //���������ͼ�ߴ�
        $this->cal_size();

		//ԭͼ������
		if($maxwidth==0 && $maxheight==0){
               $this->topic($this->tempic);
			   exit;
		}

        //�ȱ���С
        $this->thpic = imagecreatetruecolor($this->thwidth, $this->thheight);
        imagecopyresampled($this->thpic, $this->tempic, 0, 0, 0 ,0, $this->thwidth, $this->thheight, $this->width, $this->height);
		//������ȣ�������֮��߶Ȳ��������²��ϱ���
	  	if($this->thwidth>$this->thheight){
		  		$this->addBg($this->thpic,$this->thwidth,$this->thheight,"wh");
	  	}
		//�ȱ�����
	  	if($this->thwidth==$this->thheight){
		  		$this->addBg($this->thpic,$this->thwidth,$this->thheight,"wh");
	  	}
		//�߶����ȣ�������֮���Ȳ��������²��ϱ���
	  	if($this->thwidth<$this->thheight){
		  		$this->addBg($this->thpic,$this->thwidth,$this->thheight,"h");
	  	}
    }
     
  	//����ͼƬ����
  	public function addBg($temp_img,$w,$h,$fisrt="wh"){
	  	$bg=imagecreatetruecolor($this->newswidth,$this->newsheight);
	  	$white = imagecolorallocate($bg,255,255,255);
	    imagefill($bg,0,0,$white);//��䱳��
	    if($fisrt=="w"){
			$x=0;
			$y=($h-$this->newsheight)/2;//��ֱ����
		}
		if($fisrt=="h"){
		    $x=($w-$this->newswidth)/2;//ˮƽ����
			$y=0;
		}
		if($fisrt=="wh"){
            $x=0;
			$y=0;
		}
		imagecopymerge($bg,$temp_img,$x,$y,0,0,$this->newswidth,$this->newsheight,100);
        switch($this->type){
            case 1:     //GIF
                imagegif($bg);
                break;
            case 2:     //JPG
                imagejpeg($bg);
                break;
            case 3:     //PNG
                imagepng($bg);
                break;
            default:
                echo '�ݲ�֧�ָ�ͼƬ��ʽ';
        }
		$this->topic($bg);
		//imagedestroy($bg);
	}

  	//���ͼƬ
  	public function topic($temp_img){
        if($this->type==1){
		      header("Content-type: image/gif"); 
		}elseif($this->type==2){
		      header("Content-type: image/jpeg"); 
		}else{
		      header("Content-type: image/png"); 
		}
        switch($this->type){
            case 1:     //GIF
                imagegif($temp_img,$this->cache,90);
                break;
            case 2:     //JPG
                imagejpeg($temp_img,$this->cache,90);
                break;
            case 3:     //PNG
                imagepng($temp_img,$this->cache,9);
                break;
            default:
                echo '�ݲ�֧�ָ�ͼƬ��ʽ';
        }
		@imagedestroy($temp_img);
		@imagedestroy($this->tempic);
		@imagedestroy($this->thpic);
	}
}
