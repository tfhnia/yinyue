<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Cscms open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-07-26
 */
class Watermark {

  		//���췽����ʼ��
  		public function __construct() {

  		}

  		public function getimageinfo($src)
  		{
	  		return getimagesize($src);
  		}
  		/**
  		* ����ͼƬ��������Դ����
  		* @param string $src ͼƬ·��
  		* @return resource $im ������Դ���� 
  		* **/
  		public function create($src)
  		{
	  		$info=$this->getimageinfo($src);
	  		switch ($info[2])
	  		{
		  		case 1:
		  			$im=imagecreatefromgif($src);
		  			break;
		  		case 2:
		  			$im=imagecreatefromjpeg($src);
			  		break;
		  		case 3:
			  		$im=imagecreatefrompng($src);
			  		break;
	  		}
	  		return $im;
  		}
  		/**
  		* ����ͼ������
  		* @param string $src ͼƬ·��
  		* @param int $w ����ͼ���
  		* @param int $h ����ͼ�߶�
  		* @return mixed ��������ͼ·��
  		* **/
  		public function resize($src,$temp_w='',$temp_h='')
  		{
	  		$temp=pathinfo($src);
	  		$name=$temp["basename"];//�ļ���
	  		$dir=$temp["dirname"];//�ļ����ڵ��ļ���
	  		$extension=$temp["extension"];//�ļ���չ��
	  		$savepath="{$dir}/{$name}.small.jpg";//����ͼ����·��,�µ��ļ���Ϊ*.small.jpg

	  		//��ȡͼƬ�Ļ�����Ϣ
	  		$info=$this->getimageinfo($src);
	  		$width=$info[0];//��ȡͼƬ���
	  		$height=$info[1];//��ȡͼƬ�߶�
          	$w=intval($width/2);
	  		$h=intval($height/2);//��������ͼ�����

            if($temp_w=='' && $temp_h==''){
	  		    $temp_w=$w;//����ԭͼ���ź�Ŀ��
	  		    $temp_h=$h;//����ԭͼ���ź�ĸ߶�
            }
	  		$temp_img=imagecreatetruecolor($temp_w,$temp_h);//��������
	  		$im=$this->create($src);
	  		imagecopyresampled($temp_img,$im,0,0,0,0,$temp_w,$temp_h,$width,$height);
	  		if($w>$h)
	  		{
		  		imagejpeg($temp_img,$savepath, 100);
	  			imagedestroy($im);
		  		return $this->addbg($savepath,$w,$h,"w");
		  		//������ȣ�������֮��߶Ȳ��������²��ϱ���
	  		}
	  		if($w==$h)
	  		{
		  		imagejpeg($temp_img,$savepath, 100);
		  		imagedestroy($im);
		  		return $savepath;
		  		//�ȱ�����
	  		}
	  		if($w<$h)
	  		{
		  		imagejpeg($temp_img,$savepath, 100);
		  		imagedestroy($im);
		  		return $this->addbg($savepath,$w,$h,"h");
		  		//�߶����ȣ�������֮���Ȳ��������²��ϱ���
	  		}
  		}
  		/**
  		* ��ӱ���
  		* @param string $src ͼƬ·��
  		* @param int $w ����ͼ����
  		* @param int $h ����ͼ��߶�
  		* @param String $first ����ͼ������λ�õģ�w ������� h �߶����� wh:�ȱ�
  		* @return ���ؼ��ϱ�����ͼƬ
  		* **/
  		public function addbg($src,$w,$h,$fisrt="w")
  		{
	  		$bg=imagecreatetruecolor($w,$h);
	  		$white = imagecolorallocate($bg,255,255,255);
	  		imagefill($bg,0,0,$white);//��䱳��

	  		//��ȡĿ��ͼƬ��Ϣ
	  		$info=$this->getimageinfo($src);
	  		$width=$info[0];//Ŀ��ͼƬ���
	  		$height=$info[1];//Ŀ��ͼƬ�߶�
	  		$img=$this->create($src);
	  		if($fisrt=="wh")
	  		{
		  		//�ȱ�����
	  			return $src;
	  		}else{
		  		if($fisrt=="w")
		  		{
			  		$x=0;
			  		$y=($h-$height)/2;//��ֱ����
		  		}
		  		if($fisrt=="h")
		  		{
			  		$x=($w-$width)/2;//ˮƽ����
			  		$y=0;
		  		}
		  		imagecopymerge($bg,$img,$x,$y,0,0,$width,$height,100);
		  		imagejpeg($bg,$src,100);
		  		imagedestroy($bg);
		  		imagedestroy($img);
		  		return $src;
	  		}

  		}

  		public function imagewatermark($filename){

          		$watertype=CS_WaterMode; //ˮӡ����(1Ϊ����,2ΪͼƬ) 
          		$waterposition=CS_WaterLocation; //����ˮӡλ��(1Ϊ���½�,2Ϊ���½�,3Ϊ���Ͻ�,4Ϊ���Ͻ�,5Ϊ����);  
          		$waterpositions=CS_WaterLocations; //ͼƬˮӡλ��(1Ϊ���½�,2Ϊ���½�,3Ϊ���Ͻ�,4Ϊ���Ͻ�,5Ϊ����);  
          		$waterstring=CS_WaterFont; //ˮӡ�ַ���  
          		$waterstringw=130; //ˮӡ�ַ����ĸ߶�
          		$waterstringh=20; //ˮӡ�ַ����ĸ߶�
          		$waterstringpadding = 1; //ˮӡ�ַ����ľ���������
          		$waterimg=FCPATH.CS_WaterLogo; //ˮӡͼƬ  
          		$imgquality = CS_WaterLogotm; //ͼƬ����0-100��ֵ���ͼƬ�������ã�ͼƬ�Ĵ�СҲԽ�� *�Ƽ�90-100 ̫СͼƬ�����ģ������

          		$image_size = getimagesize($filename);   //�ϴ�ͼƬ�Ĵ�С
	  		    $upimgw = $image_size[0];  //�ϴ�ͼƬ�Ŀ��
	  		    $upimgh = $image_size[1];  //�ϴ�ͼƬ�ĸ߶�

	  		    $waterimg_size =  getimagesize($waterimg); //ˮӡͼƬ�Ĵ�С
	  		    $waterimgw = $waterimg_size[0];  //ˮӡͼƬ�Ŀ��
	  		    $waterimgh = $waterimg_size[1];  //ˮӡͼƬ�ĸ߶�
	  		    $wpinfo=pathinfo($waterimg);    
         		$wptype=$wpinfo['extension'];     //ˮӡͼƬ�ĺ�׺

          		$destination=$filename;

            	if(CS_WaterFontColor!="") { 
              			$R = hexdec(substr(CS_WaterFontColor,1,2)); 
              			$G = hexdec(substr(CS_WaterFontColor,3,2)); 
              			$B = hexdec(substr(CS_WaterFontColor,5)); 
				} else { 
              			$R = hexdec(substr('#0000CC',1,2)); 
              			$G = hexdec(substr('#0000CC',3,2)); 
              			$B = hexdec(substr('#0000CC',5)); 
				}

          		$iinfo=getimagesize($destination,$iinfo);  
          		$nimage=imagecreatetruecolor($image_size[0],$image_size[1]);  
          		$white=imagecolorallocate($nimage, $R, $G, $B);  
          		$black=imagecolorallocate($nimage,0,0,0);  
          		$red=imagecolorallocate($nimage,255,0,0);  
          		imagefill($nimage,0,0,$white);  
          		switch ($iinfo[2])  
         		 {  
             		 case 1:  
             		      $simage =imagecreatefromgif($destination);  
             		      break;  
             		 case 2:  
             		      $simage =imagecreatefromjpeg($destination);  
              		      break;  
             		 case 3:  
             		      $simage =imagecreatefrompng($destination);  
             		      break;  
             		 case 6:  
             		      $simage =imagecreatefromwbmp($destination);  
              		      break;  
              		default:  
             		      $simage =imagecreatefromgif($destination);  
             		      break;  
          		}  
          		imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]); 
			    switch($watertype)  
			    {  
					case 1:   //��ˮӡ�ַ���  
				  		switch ($waterposition) {  
					  		case 1:   //ˮӡλ�ã�����
				  		  		$waterstart_x = $waterstringpadding ;
				  		  		$waterstart_y = $upimgh-$waterstringh;
				  		  		break;
					  		case 2:   //ˮӡλ�ã�����
				  		  		$waterstart_x = $upimgw-$waterstringw-$waterstringpadding;
				  		  		$waterstart_y = $upimgh-$waterstringh-$waterstringpadding ;
				  		  		break;
					  		case 3:   //ˮӡλ�ã�����
				  		  		$waterstart_x = $waterstringpadding;
				  		  		$waterstart_y = $waterstringpadding;
				  		  		break;
					  		case 4:   //ˮӡλ�ã�����
				  		  		$waterstart_x = $upimgw-$waterstringw-$waterstringpadding;
				  		  		$waterstart_y = $waterstringpadding;
				  		  		break;
					  		case 5:   //ˮӡλ�ã��м�
				  		  		$waterstart_x = ($upimgw-$waterstringw)/2;
				  		  		$waterstart_y = ($upimgh-$waterstringh)/2;
				  		  		break;
						}
              		    imagestring($nimage,CS_WaterFontSize,$waterstart_x,$waterstart_y,$waterstring,$white);  
              		    break;  
              		case 2:   //��ˮӡͼƬ
					    $simage1 ="";
					    switch($wptype)  
					    {  
						    case 'png':   //ˮӡͼƬ��ʽpng  
						    $simage1 =imagecreatefrompng($waterimg); break;
						    case 'gif':   //ˮӡͼƬ��ʽgif  
						    $simage1 =imagecreatefromgif($waterimg); break;
					    }

			  		    switch ($waterpositions) {  
				  			case 1:   //ˮӡλ�ã�����
			  		  			$waterstart_x = 0;
			  		  			$waterstart_y = $upimgh-$waterimgh;
			  		  			break;
				  			case 2:   //ˮӡλ�ã�����
			  		  			$waterstart_x = $upimgw-$waterimgw;
			  		  			$waterstart_y = $upimgh-$waterimgh;
			  		  			break;
				  			case 3:   //ˮӡλ�ã�����
			  		  			$waterstart_x = 0;
			  		  			$waterstart_y = 0;
			  		  			break;
				  			case 4:   //ˮӡλ�ã�����
			  		  			$waterstart_x = $upimgw-$waterimgw;
			  		  			$waterstart_y = 0;
			  		  			break;
				  			case 5:   //ˮӡλ�ã��м�
			  		  			$waterstart_x = ($upimgw-$waterimgw)/2;
			  		  			$waterstart_y = ($upimgh-$waterimgh)/2;
			  		  			break;
			  			}
			
			  			imagecopy($nimage,$simage1,$waterstart_x,$waterstart_y,0,0,$waterimgw,$waterimgh); 
						imagedestroy($simage1);  
              			break;  
          		}
          		switch ($iinfo[2])  
          		{  
             		  	case 1:  
             		  		 //imagegif($nimage, $destination);  
              		  		imagejpeg($nimage, $destination,$imgquality);  
              		  		break;  
              		        case 2:  
              		  		imagejpeg($nimage, $destination,$imgquality);  
              		  		break;  
              		        case 3:  
              		  		imagepng($nimage, $destination,$imgquality);  
              		  		break;  
              		        case 6:  
              		  		imagewbmp($nimage, $destination,$imgquality);    
              		  		break;  
          		}  
          		//����ԭ�ϴ��ļ�  
          		imagedestroy($nimage);  
          		imagedestroy($simage);   
  		}
}

