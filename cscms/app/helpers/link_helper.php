<?php
/**
 * @Cscms open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-07-26
 */
//��ȡ��Ա�˺�
function get_home_uid(){
		//�жϻ�Աϵͳ����
		if(User_Mode==0) msg_url(User_No_info,'http://'.Web_Url.Web_Path);
        if(Home_Ym==1){
             $arr = explode('.', $_SERVER['HTTP_HOST']);
             $uid = $arr[0];
        }else{
	         $ci = &get_instance();
             $uid = $ci->uri->segment(1);
        }
		if(Home_Fs==1){
	         $uid = getzd('user','id',$uid,'name');
		     if($uid==0) msg_url('��Ǹ���û�Ա������','http://'.Web_Url.Web_Path);
		}
		return (int)$uid;
}
//��ҳ��ǩ����
function page_mark($Mark_Text,$Arr){
		$Mark_Text=preg_replace('/\{cscms:pagenum(.*?)\}/','{cscms:pagenum}',$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagedata}',$Arr[10],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagedown}',$Arr[4],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagenow}',$Arr[5],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagecout}',$Arr[6],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagelist}',$Arr[9],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagesize}',$Arr[7],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagefirst}',$Arr[1],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pageup}',$Arr[3],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagenum}',$Arr[8],$Mark_Text);
		$Mark_Text=str_replace('{cscms:pagelast}',$Arr[2],$Mark_Text);	
		return $Mark_Text;
}
//��ȡ��ҳ��Ŀ	
function getpagenum($Mark_Text){
		preg_match('/\{cscms:(pagenum)\s*([a-zA-Z=]*)\s*([\d]*)\}/',$Mark_Text,$pagearr);
		if(!empty($pagearr)){
			if(trim($pagearr[3])!=""){
				$pagenum=$pagearr[3];
			}else{
				$pagenum=10;
			}	
		}else{
			$pagenum=10;
		}
		unset($pagearr);
 		return $pagenum;
}

//������ӵ�ַ����
function cscmslink($dir){
	    //��ȡ������ò���
		$Ym_Mode=config('Ym_Mode',$dir); //��������״̬
		$Ym_Url=config('Ym_Url',$dir);   //����������ַ
		$Web_Mode=config('Web_Mode',$dir);   //վ�����з�ʽ
		$Html=config('Html_Uri',$dir); //��̬����
        $weburl=(defined('MOBILE_YM'))?"http://".Mobile_Url.Web_Path:"http://".Web_Url.Web_Path;
		if($Web_Mode==2){ //α��̬ģʽ
		      $Rewrite=config('Rewrite_Uri',$dir); //α��̬����
              $linkurl=$Rewrite['index']['url'];
		}elseif($Web_Mode==3 && $Html['index']['check']==1 && !defined('MOBILE')){ //��̬ģʽ
              $linkurl=$Html['index']['url'];
		}else{
              $linkurl=($Ym_Mode==1 && !defined('MOBILE'))?'':'index.php/'.$dir;
		}
		//������������
		if($Ym_Mode==1 && !defined('MOBILE_YM')){
	          $weburl  = "http://".$Ym_Url.Web_Path;
			  $linkurl = str_replace($dir,'',$linkurl);
		}
        return $weburl.$linkurl;
}

//���������ַ����
function hitslink($path,$dir){
	    //��ȡ������ò���
		if($dir!='home'){
		    $Ym_Mode=config('Ym_Mode',$dir); //��������״̬
		    $Ym_Url=config('Ym_Url',$dir);   //����������ַ
		}else{
		    $Ym_Mode=2;
		}
		//������������
		if($Ym_Mode==1){
	          $linkurl="http://".$Ym_Url.Web_Path."index.php/".$path;
		}else{
              $linkurl="http://".Web_Url.Web_Path."index.php/".$dir."/".$path;
		}
        return $linkurl;
}

//���ӵ�ַ����
function linkurl($fid,$sort='id',$id=1,$pid=1,$dir='',$sname='null'){

      		if($fid=='gbook'){ //��վ����
				   if(Web_Mode==3){ //α��̬ģʽ
        		           $linkurl="http://".Web_Url.Web_Path."gbook/index/".$pid;
				   }else{
        		           $linkurl="http://".Web_Url.Web_Path."index.php/gbook/index/".$pid;
				   }
				   return $linkurl;
			}

			//��ȡ������ò���
			$dir=($dir)?$dir:(defined('PLUBPATH')?PLUBPATH:'');
			$PLUBARR=config('',$dir);
			$Ym_Mode=$PLUBARR['Ym_Mode']; //��������״̬
			$Ym_Url=$PLUBARR['Ym_Url'];   //����������ַ
			$Web_Mode=$PLUBARR['Web_Mode'];   //վ�����з�ʽ
			$Rewrite=$PLUBARR['Rewrite_Uri']; //α��̬����
			$Html=$PLUBARR['Html_Uri'];  //���ɾ�̬����

            $linkurl='';
      		if($fid=='search'){
				   $sort=strpos($sort,'=')?$sort:$sort."=";
				   $sort=strpos($sort,'?')?$sort:"?".$sort;
				   if($Web_Mode==2){ //α��̬ģʽ
        		           $linkurl=Web_Url.Web_Path.$dir."/search".$sort.$id;
				   }else{
        		           $linkurl=Web_Url.Web_Path."index.php/".$dir."/search".$sort.$id;
				   }
			       //������������
			       if($Ym_Mode==1 && !defined('MOBILE_YM')){
						 if($Web_Mode==2){
	                          $linkurl  = str_replace(Web_Url.Web_Path.$dir,$Ym_Url.Web_Path,$linkurl);
						 }else{
	                          $linkurl  = str_replace(Web_Url.Web_Path."index.php/".$dir,$Ym_Url.Web_Path."index.php",$linkurl);
						 }
			       }
				   if($pid>1){
                         $linkurl.="&page=".$pid;
				   }
				   if(defined('MOBILE_YM')){
	                     $linkurl  = str_replace(Web_Url,Mobile_Url,$linkurl);
				   }
                   return "http://".str_replace("//","/",$linkurl);
      		}
			if((defined('MOBILE') || defined('MOBILE_YM')) && $Web_Mode==3) $Web_Mode=1;
     		switch($Web_Mode){
         	      case '1':  //��̬ģʽ
				         if($pid>1){
							  if($id>0){
				                    $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id."/".$pid;
							  }else{
				                    $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$pid;
							  }
						 }else{
							  if($id>0){
				                    $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id;
							  }else{
				                    $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$pid;
							  }
						 }
         	      break;
         	      case '2':  //α��̬ģʽ
               
                         //����ר��
						 if($fid=='topic' && ($sort=='lists' || $sort=='show')){
				              $linkurl=$Rewrite[$fid.'/'.$sort]['url'];
						 }else{
							  $linkurl=$Rewrite[$fid]['url'];
						 }
						 $sname='null';
						 if($dir=='dance' && $fid=='down' && ($sort=='load' || $sort=='lrc')){
                             $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id;
						 }else{
                             $linkurl=Web_Url.Web_Path.$dir."/".str_replace_dir($linkurl,$id,$sname,$sort,$pid);
						 }

         	      break;
         	      case '3':  //��̬ģʽ

                         //����ר��
						 if($fid=='topic' && ($sort=='lists' || $sort=='show')){
							  $check=$Html[$fid.'/'.$sort]['check'];
				              $linkurl=$Html[$fid.'/'.$sort]['url'];
						 }else{
				              $linkurl=$Html[$fid]['url'];
							  $check=$Html[$fid]['check'];
						 }
						 if($check==1){
                              $linkurl=Web_Url.Web_Path.str_replace_dir($linkurl,$id,$sname,$sort,$pid,$dir,$fid);
						 }else{
				              if($pid>1){
								   if($id>0){
				                       $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id."/".$pid;
								   }else{
				                       $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$pid;
								   }
						      }else{
								   if($id>0){
				                       $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id;
								   }else{
				                       $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$pid;
								   }
						      }
						 }
						 //���ݸ������ء��������
				         if($dir=='dance' && $fid=='down' && ($sort=='load' || $sort=='lrc')){
                              $linkurl=Web_Url.Web_Path."index.php/".$dir."/".$fid."/".$sort."/".$id;
						 }
         	      break;
      		}
			//������������
			if($Ym_Mode==1 && !defined('MOBILE_YM')){
				  if($Web_Mode==2){
				        $linkurl  = str_replace(Web_Url.Web_Path.$dir,$Ym_Url.Web_Path,$linkurl);
				  }else{
				        $linkurl  = str_replace(Web_Url.Web_Path."index.php/".$dir,$Ym_Url.Web_Path."index.php",$linkurl);
						$linkurl  = str_replace(Web_Url.Web_Path,$Ym_Url.Web_Path,$linkurl);
				  }
			}
			if(defined('MOBILE_YM')){
	              $linkurl  = str_replace(Web_Url,Mobile_Url,$linkurl);
			}
  		    return "http://".str_replace("//","/",$linkurl);
}

//��Ա�ռ��������
function userlink($Classid,$Uid=0,$Name='',$ID='',$Pages=0){
	    $ID=(string)$ID;
		if(Home_Fs==2) $Name=$Uid;  //���û�ԱID����ҳ��ַ
       	if(Home_Ym==1){ 
              	if($Classid=='index'){
                       	$userlink="http://".$Name.".".Home_YmUrl;
              	}elseif($ID!=''){
                        $userlink="http://".$Name.".".Home_YmUrl."/index.php/".$Classid."/".$ID;
						if($Pages>0) $userlink.="/".$Pages;
              	}elseif($Pages>0){
                       	$userlink="http://".$Name.".".Home_YmUrl."/index.php/".$Classid."/".$Pages;
              	}else{
                       	$userlink="http://".$Name.".".Home_YmUrl."/index.php/".$Classid;
              	}
		}else{
              	if($Classid=='index'){
                       	$userlink="http://".Web_Url.Web_Path.'index.php/'.$Name.'/home';
              	}elseif($ID!=''){
                       	$userlink="http://".Web_Url.Web_Path.'index.php/'.$Name.'/home/'.$Classid.'/'.$ID;
						if($Pages>0) $userlink.="/".$Pages;
              	}elseif($Pages>0){
                       	$userlink="http://".Web_Url.Web_Path.'index.php/'.$Name.'/home/'.$Classid.'/'.$Pages;
				}else{
                       	$userlink="http://".Web_Url.Web_Path.'index.php/'.$Name.'/home/'.$Classid;
              	}
			    if(defined('MOBILE_YM')){
	                 $userlink  = str_replace(Web_Url,Mobile_Url,$userlink);
			    }
  		}
	    //α��̬
	    if(Home_Mode==1) $userlink=str_replace("/index.php/","/",$userlink);
 		return $userlink;
}

//��Ա��������
function spacelink($url='',$dir=''){
	    $uarr=explode(',',$url);
        $url=str_replace(",","/",$url);
		$plub=$dir;
		if($dir=='' && defined('PLUBPATH')){
            $plub=PLUBPATH;
		}
		if($dir==''){
			if($plub!='' && file_exists(FCPATH.'plugins/'.$plub.'/controllers/user/'.$uarr[0].'.php')){
                $url='http://'.Web_Url.Web_Path.'index.php/'.$plub.'/user/'.$url;
			}else{
                $url='http://'.Web_Url.Web_Path.'index.php/user/'.$url;
			}
		}else{
			$url='http://'.Web_Url.Web_Path.'index.php/'.$plub.'/user/'.$url;
		}
	    //α��̬
	    if(Web_Mode==3) $url=str_replace("/index.php/","/",$url);
        $url=str_replace("/user/user/","/user/",$url);
		$url=userurl($url,$plub);
        return $url;
}

//��Ա����ת��
function userurl($url,$plub=''){
	     if (defined('ADMINSELF'))  $url=str_replace(ADMINSELF,"index.php",$url);
	     //α��̬
	     if(Web_Mode==3) $url=str_replace("index.php/user","user",$url);
	     if(User_Ym!='' && !defined('MOBILE_YM')){
			 if($plub==''){ //���ǰ������
				 //α��̬
	             if(Web_Mode==3){
			         $url=str_replace('http://'.Web_Url.Web_Path.'user','http://'.User_Ym,$url);
			     }else{
			         $url=str_replace('http://'.Web_Url.Web_Path.'index.php/user','http://'.User_Ym.Web_Path.'index.php',$url);
				 }
			 }else{ //����Ա����
				 //α��̬
	             if(Web_Mode==3){
			         $url=str_replace('http://'.Web_Url.Web_Path.$plub.'/user','http://'.User_Ym.Web_Path.$plub,$url);
				 }else{
			         $url=str_replace('http://'.Web_Url.Web_Path.'index.php/'.$plub.'/user','http://'.User_Ym.Web_Path.'index.php/'.$plub,$url);
				 }
			 }
		 }
		 if(defined('MOBILE_YM')){
	         $url  = str_replace(Web_Url,Mobile_Url,$url);
		 }
         return $url;
}

//��̨���ɵ�ַת��
function adminhtml($url,$dir=''){
		 $dir=($dir)?$dir:(defined('PLUBPATH')?PLUBPATH:'');
		 $Ym_Mode=config('Ym_Mode',$dir); //��������״̬
		 $Ym_Url=config('Ym_Url',$dir);   //����������ַ
		 if($Ym_Mode==1){
		       $url=str_replace("http://".$Ym_Url,"",$url);
         }
		 $url=str_replace("http://".Web_Url,"",$url);
		 $file_ext = strtolower(trim(substr(strrchr($url, '.'), 1)));
		 if(empty($file_ext)){
			 if(substr($url,-1)!='/'){
			      $url.='.html';
			 }else{
			      $url.='index.html';
			 }
		 }
		 if(Web_Path!='/'){
             $url=str_replace(Web_Path,"/",$url);
		 }
         return $url;
}

//��ȡͼƬ��Ϣ
function piclink($Table='pic',$Url,$dx='') {

         if(UP_Mode==2){  //FTPԶ�̸���
                  $linkurl=FTP_Url;
         }elseif(UP_Mode>2){  //��������
			      $ci = &get_instance();
				  $ci->load->library('csup');
                  $linkurl=$ci->csup->down(UP_Mode);
         }else{
			      $linkurl='';
			      /*if(UP_Pan!=''){
                      $linkurl=UP_Url;
				  }else{
                      $linkurl="http://".Web_Url.Web_Path;
				  }*/
         }
		 if(substr($Url,0,7)=="http://"){
             	  $picurl=$Url;
		 }elseif(empty($Url)){
                  if($Table=='bgpic' || $Table=='toppic'){
              		     return '';
                  }elseif($Table=='logo'){
                         if($dx==1){
              		          $picurl="http://".Web_Url.Web_Path."attachment/nv_nopic.jpg";
                         }else{
              		          $picurl="http://".Web_Url.Web_Path."attachment/nan_nopic.jpg";
                         }
                  }else{
             		     $picurl="http://".Web_Url.Web_Path."attachment/nopic.gif";
                  }
				  return $picurl;
		 }else{
			      if(substr($Url,0,1)!='/') $Url='/'.$Url;
			      if(UP_Mode==1){
                  		$picurl=$linkurl."attachment/".$Table.$Url;
				  }else{
                  		$picurl=$linkurl.$Url;
				  }
             	  if($dx!=''){
				        $picurl.=".small.jpg";
             	  }
		}
		if(substr($picurl,0,7)=="http://"){
  		     return $picurl;
		}else{
             return 'http://'.Web_Url.Web_Path.'index.php/picdata/'.str_replace("attachment/","",$picurl);
		}
}
//��ȡ�������ӵ�ַ
function annexlink($url) {
         if(substr($url,0,7)=='http://' || substr($url,0,7)=='rtsp://' || substr($url,0,7)=='rtmp://' || substr($url,0,6)=='mms://'){  //�ⲿ����
                  return $url;
         }elseif(UP_Mode==2){  //FTPԶ�̸���
                  $linkurl=FTP_Url;
         }elseif(UP_Mode>2){  //��������
			      $ci = &get_instance();
				  $ci->load->library('csup');
                  $linkurl=$ci->csup->down(UP_Mode);
         }else{
			      if(UP_Pan!=''){
                      $linkurl=UP_Url;
				  }else{
					  if(substr($url,0,1)=='/') $url=substr($url,1);
                      $linkurl="http://".Web_Url.Web_Path;
				  }
         }
  		 return $linkurl.$url;
}
//����ҳ����
function spanpage($sqlstr,$nums,$pagesize,$pagenum,$fid,$sort='id',$id=1,$pages=1,$plist=0){
	    $znums=$nums;
		if($nums==0){$nums=1;}
        $pagejs=ceil($nums/$pagesize);//��ҳ��
        if($pages==0) $pages=1;
        if($pages>$pagejs){
            $pages=$pagejs;
        }
        $sqlstr.=" LIMIT ".$pagesize*($pages-1).",".$pagesize;
		$str="";

		$first=linkurl($fid,$sort,$id,1);
       	if($pages==1){
	     		$pageup=linkurl($fid,$sort,$id,1);
        }else{
	     		$pageup=linkurl($fid,$sort,$id,($pages-1));
        }
       	 if($pagejs>$pages){
	     		$pagenext=linkurl($fid,$sort,$id,($pages+1));
        }else{
	     		$pagenext=linkurl($fid,$sort,$id,$pagejs);
        }
		$last=linkurl($fid,$sort,$id,$pagejs);
		$pagelist="<select  onchange=javascript:window.location=this.options[this.selectedIndex].value;>\r\n<option value='0'>��ת</option>\r\n";
		if($plist){
		    for($k=1;$k<=$pagejs;$k++) $pagelist.="<option value='".linkurl($fid,$sort,$id,$k)."'>��".$k."ҳ</option>\r\n";
		}
		$pagelist.="</select>";	
		if($pagejs<=$pagenum){
  			for($i=1;$i<=$pagejs;$i++){
                   if($i==$pages){
   				        $str.="<a href='".linkurl($fid,$sort,$id,$i)."' class='on'>".$i."</a>";
                   }else{
   				        $str.="<a href='".linkurl($fid,$sort,$id,$i)."'>".$i."</a>";
                   }
 	 		}
		}else{
 			if($pages>=$pagenum){
 				for($i=$pages-intval($pagenum/2);$i<=$pages+(intval($pagenum/2));$i++){
   					if($i<=$pagejs){
                          if($i==$pages){
   				                $str.="<a href='".linkurl($fid,$sort,$id,$i)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".linkurl($fid,$sort,$id,$i)."'>".$i."</a>";
                          }
    			    }
  				}
  				if($i<=$pagejs){ 
	    		        $str.="<a href='".linkurl($fid,$sort,$id,$pagejs)."'>".$pagejs."</a>";
   				}
   			}else{
  				for($i=1;$i<=$pagenum;$i++){
                          if($i==$pages){
   				                $str.="<a href='".linkurl($fid,$sort,$id,$i)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".linkurl($fid,$sort,$id,$i)."'>".$i."</a>";
                          }
 		        } 
 		        if($i<=$pagejs){ 
	  		     	$str.="<a href='".linkurl($fid,$sort,$id,$pagejs)."'>".$pagejs."</a>";
    			}
 		 	}
		}
	 	$arr=array($sqlstr,$first,$last,$pageup,$pagenext,$pages,$pagejs,$pagesize,$str,$pagelist,$znums);
	 	return $arr;
}
//AJAX��ҳ����
function spanajaxpage($sqlstr,$nums,$pagesize,$pagenum,$op,$pages=1,$id=0,$fid=''){
	    $znums=$nums;
		if($nums==0){$nums=1;}
        $pagejs=ceil($nums/$pagesize);//��ҳ��
        if($pages==0) $pages=1;
        if($pages>$pagejs){
            $pages=$pagejs;
        }
        $sqlstr.=" LIMIT ".$pagesize*($pages-1).",".$pagesize;
		$str="";
		$first="javascript:".$op."('1','".$id."','".$fid."');";
       	if($pages==1){
	     		$pageup="javascript:".$op."('1','".$id."','".$fid."');";
        }else{
	     		$pageup="javascript:".$op."('".($pages-1)."','".$id."','".$fid."');";
        }
       	 if($pagejs>$pages){
	     		$pagenext="javascript:".$op."('".($pages+1)."','".$id."','".$fid."');";
        }else{
	     		$pagenext="javascript:".$op."('".$pagejs."','".$id."','".$fid."');";
        }
		$last="javascript:".$op."('".$pagejs."','".$id."','".$fid."');";
		$pagelist="<select  onchange=javascript:window.location=this.options[this.selectedIndex].value;>\r\n<option value='0'>��ת</option>\r\n";
		for($k=1;$k<=$pagejs;$k++){
			$pagelist.="<option value='".$op."('".$k."','".$id."','".$fid."');'>��".$k."ҳ</option>\r\n";
		}
		$pagelist.="</select>";	
		if($pagejs<=$pagenum){
  			for($i=1;$i<=$pagejs;$i++){
                   if($i==$pages){
   				        $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\" class='on'>".$i."</a>";
                   }else{
   				        $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\">".$i."</a>";
                   }
 	 		}
		}else{
 			if($pages>=$pagenum){
 				for($i=$pages-intval($pagenum/2);$i<=$pages+(intval($pagenum/2));$i++){
   					if($i<=$pagejs){
                          if($i==$pages){
   				                $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\" class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\">".$i."</a>";
                          }
    			    }
  				}
  				if($i<=$pagejs){ 
	    		        $str.="<a href=\"javascript:".$op."('".$pagejs."','".$id."','".$fid."');\">".$i."</a>";
   				}
   			}else{
  				for($i=1;$i<=$pagenum;$i++){
                          if($i==$pages){
   				                $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\" class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href=\"javascript:".$op."('".$i."','".$id."','".$fid."');\">".$i."</a>";
                          }
 		        } 
 		        if($i<=$pagejs){ 
	  		     	  $str.="<a href=\"javascript:".$op."('".$pagejs."','".$id."','".$fid."');\">".$i."</a>";
    			}
 		 	}
		}
	 	$arr=array($sqlstr,$first,$last,$pageup,$pagenext,$pages,$pagejs,$pagesize,$str,$pagelist,$znums);
	 	return $arr;
}
//��Ա���ķ�ҳ����
function userpage($sqlstr,$nums,$pagesize,$pagenum,$url,$pages=1,$dir=''){
	    if(substr($url,-1)!='/') $url.='/';
	    $znums=$nums;
		if($nums==0){$nums=1;}
        $pagejs=ceil($nums/$pagesize);//��ҳ��
        if($pages==0) $pages=1;
        if($pages>$pagejs){
            $pages=$pagejs;
        }
        $sqlstr.=" LIMIT ".$pagesize*($pages-1).",".$pagesize;
		$str="";
		$first=spacelink($url.'1',$dir);
       	if($pages==1){
	     		$pageup=spacelink($url.'1',$dir);
        }else{
	     		$pageup=spacelink($url.($pages-1),$dir);
        }
       	if($pagejs>$pages){
	     		$pagenext=spacelink($url.($pages+1),$dir);
        }else{
	     		$pagenext=spacelink($url.$pagejs,$dir);
        }
		$last=spacelink($url.$pagejs,$dir);
		$pagelist="<select  onchange=javascript:window.location=this.options[this.selectedIndex].value;>\r\n<option value='0'>��ת</option>\r\n";
		for($k=1;$k<=$pagejs;$k++){
			$pagelist.="<option value='".spacelink($url.$k,$dir)."'>��".$k."ҳ</option>\r\n";
		}
		$pagelist.="</select>";	
		if($pagejs<=$pagenum){
  			for($i=1;$i<=$pagejs;$i++){
                   if($i==$pages){
   				        $str.="<a href='".spacelink($url.$i,$dir)."' class='on'>".$i."</a>";
                   }else{
   				        $str.="<a href='".spacelink($url.$i,$dir)."'>".$i."</a>";
                   }
 	 		}
		}else{
 			if($pages>=$pagenum){
 				for($i=$pages-intval($pagenum/2);$i<=$pages+(intval($pagenum/2));$i++){
   					if($i<=$pagejs){
                          if($i==$pages){
   				                $str.="<a href='".spacelink($url.$i,$dir)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".spacelink($url.$i,$dir)."'>".$i."</a>";
                          }
    			    }
  				}
  				if($i<=$pagejs){ 
	    		        $str.="<a href='".spacelink($url.$pagejs,$dir)."'>".$pagejs."</a>";
   				}
   			}else{
  				for($i=1;$i<=$pagenum;$i++){
                          if($i==$pages){
   				                $str.="<a href='".spacelink($url.$i,$dir)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".spacelink($url.$i,$dir)."'>".$i."</a>";
                          }
 		        } 
 		        if($i<=$pagejs){ 
	  		     	$str.="<a href='".spacelink($url.$pagejs,$dir)."'>".$pagejs."</a>";
    			}
 		 	}
		}
	 	$arr=array($sqlstr,$first,$last,$pageup,$pagenext,$pages,$pagejs,$pagesize,$str,$pagelist,$znums);
	 	return $arr;
}
//��Ա��ҳ��ҳ����
function homepage($sqlstr,$nums,$pagesize,$pagenum,$op,$uid,$user,$id=0,$pages=1){
	    $znums=$nums;
		if($nums==0){$nums=1;}
        $pagejs=ceil($nums/$pagesize);//��ҳ��
        if($pages==0) $pages=1;
        if($pages>$pagejs){
            $pages=$pagejs;
        }
        $sqlstr.=" LIMIT ".$pagesize*($pages-1).",".$pagesize;
		$str="";
		$first=userlink($op,$uid,$user,$id,1);
       	if($pages==1){
	     		$pageup=userlink($op,$uid,$user,$id,1);
        }else{
	     		$pageup=userlink($op,$uid,$user,$id,($pages-1));
        }
       	if($pagejs>$pages){
	     		$pagenext=userlink($op,$uid,$user,$id,($pages+1));
        }else{
	     		$pagenext=userlink($op,$uid,$user,$id,$pagejs);
        }
		$last=userlink($op,$uid,$user,$id,$pagejs);
		$pagelist="<select  onchange=javascript:window.location=this.options[this.selectedIndex].value;>\r\n<option value='0'>��ת</option>\r\n";
		for($k=1;$k<=$pagejs;$k++){
			$pagelist.="<option value='".userlink($op,$uid,$user,$id,$k)."'>��".$k."ҳ</option>\r\n";
		}
		$pagelist.="</select>";	
		if($pagejs<=$pagenum){
  			for($i=1;$i<=$pagejs;$i++){
                   if($i==$pages){
   				        $str.="<a href='".userlink($op,$uid,$user,$id,$i)."' class='on'>".$i."</a>";
                   }else{
   				        $str.="<a href='".userlink($op,$uid,$user,$id,$i)."'>".$i."</a>";
                   }
 	 		}
		}else{
 			if($pages>=$pagenum){
 				for($i=$pages-intval($pagenum/2);$i<=$pages+(intval($pagenum/2));$i++){
   					if($i<=$pagejs){
                          if($i==$pages){
   				                $str.="<a href='".userlink($op,$uid,$user,$id,$i)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".userlink($op,$uid,$user,$id,$i)."'>".$i."</a>";
                          }
    			    }
  				}
  				if($i<=$pagejs){ 
	    		        $str.="<a href='".userlink($op,$uid,$user,$id,$pagejs)."'>".$pagejs."</a>";
   				}
   			}else{
  				for($i=1;$i<=$pagenum;$i++){
                          if($i==$pages){
   				                $str.="<a href='".userlink($op,$uid,$user,$id,$i)."' class='on'>".$i."</a>";
                          }else{
   				                $str.="<a href='".userlink($op,$uid,$user,$id,$i)."'>".$i."</a>";
                          }
 		        } 
 		        if($i<=$pagejs){ 
	  		     	$str.="<a href='".userlink($op,$uid,$user,$id,$pagejs)."'>".$pagejs."</a>";
    			}
 		 	}
		}
	 	$arr=array($sqlstr,$first,$last,$pageup,$pagenext,$pages,$pagejs,$pagesize,$str,$pagelist,$znums);
	 	return $arr;
}
//��̨��ҳ
function get_admin_page($url,$pagejs=1,$pages=1,$num=8){
	   if(strpos($url,'?') === FALSE) $url.='?';
       $pagenum='';
       if($pages>1){
            $pagenum.='<li class="paginItem"><a title="��һҳ" href="'.$url.'&page='.($pages-1).'"><span class="pagepre"></span></a></li>'; 
       }
       if($pages>8){
            $pagenum.='<li class="paginItem"><a href="'.$url.'&page=1">1</a></li>'; 
	   }
       if($pagejs<=$num){
           for($i=1;$i<=$pagejs;$i++){
               if($pages==$i){
                      $pagenum.='<li class="paginItem current"><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>'; 
               }else{
                      $pagenum.='<li class="paginItem"><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>'; 
               }
          }
       }else{
 		if($pages>=$num){
 			for($i=$pages-intval($num/2);$i<=$pages+(intval($num/2));$i++){
   				        if($i<=$pagejs){
                                       if($pages==$i){
                                            $pagenum.='<li class="paginItem current"><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>'; 
                                       }else{
                      			            $pagenum.='<li class="paginItem"><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>'; 
                                       }
    			        }
  			}
  			if(($pages+5)<$pagejs){ 
                      	$pagenum.='<li class="paginItem more"><a href="'.$url.'&page='.($pages+5).'">...</a></li>';
   			}
   		}else{
  			    for($i=1;$i<=$num;$i++){
                                       if($pages==$i){
                                            $pagenum.='<li class="paginItem current"><a href="'.$url.'&page='.$i.'"><span>'.$i.'</span></a></li>'; 
                                       }else{
                      			            $pagenum.='<li class="paginItem"><a href="'.$url.'&page='.$i.'">'.$i.'</a></li>'; 
                                       }
 		        } 

 		        if(($pages+5)<$pagejs){ 
	  		            $pagenum.='<li class="paginItem more"><a href="'.$url.'&page='.($pages+5).'">...</a></li>';
    			}
 		 }
       }
       $str=$pagenum;
       //ĩҳ
       if(($pages+8)<$pagejs){
             $str.='<li class="paginItem"><a href="'.$url.'&page='.$pagejs.'">'.$pagejs.'</a></li>'; 
	   }
       //��һҳ
       if($pages<$pagejs){
            $str.='<li class="paginItem"><a title="��һҳ" href="'.$url.'&page='.($pages+1).'"><span class="pagenxt"></span></a></li>'; 
       }
	   //��ת��ҳ
            $str.="<li class='paginItem'><span class='zhuan'>ת<input type=text size=4 id='gopage' value='".$pages."'/>ҳ</span><a style='cursor:pointer;' onclick=window.location='".$url."&page='+gopage.value+'' title='��ת'>GO></a></li>";
       return $str;
}
