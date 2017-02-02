<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-21
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ģ�������
 */
class Skins {

    public function __construct()
    {
	 	     $this->ci = &get_instance();
    }

	//����ģ��
	public function template_parse($str,$ts=TRUE,$if=true) {
		     if(empty($str)) msg_txt(L('skins_null'));
			 //����ͷ�����ײ������ҷ���
		     $str = $this->topandend($str);
			 //��Ա��¼��
	         $str=str_replace('{cscms:logkuang}',$this->logkuang(),$str);
             //�Զ����ǩ
	         $str=$this->cscmsopt($str);
             //����ȫ�ֱ�ǩ
             $str=$this->cscms_common($str);
             //����ѭ��
             $str=$this->csskins($str);
			 //����ͳ�Ʊ�ǩ
             $str=$this->cscount($str);

             //PHP�������
             preg_match_all('/{cscmsphp}([\s\S]+?){\/cscmsphp}/',$str,$php_arr);
		     if(!empty($php_arr[0])){
			     for($i=0;$i<count($php_arr[0]);$i++){
                         $str=$this->cscms_php($php_arr[0][$i],$php_arr[1][$i],$str);
			     }
		     }
		     unset($php_arr);

             //����ͳ�ƽ���
             preg_match_all('/{cscmscount\s+param\=\"(.*?)\"}/',$str,$count_arr);
		     if(!empty($count_arr[0])){
			     for($i=0;$i<count($count_arr[0]);$i++){
					     $link='<script src="http://'.Web_Url.Web_Path.'index.php/api/count?param='.$count_arr[1][$i].'"></script>';
                         $str=str_replace($count_arr[0][$i],$link,$str);
			     }
		     }
		     unset($count_arr);

             //��Ա��ҳģ��
             preg_match_all('/{cscmsweb}([\s\S]+?){\/cscmsweb}/',$str,$web_arr);
		     if(!empty($web_arr[0])){
			     for($i=0;$i<count($web_arr[0]);$i++){
                         $str=$this->cscms_web($web_arr[0][$i],$web_arr[1][$i],$str);
			     }
		     }
		     unset($web_arr);

			 //����IF�жϱ�ǩ
		     if($if) $str=$this->labelif($str);
			 //����ȫ��JS
			 if($ts) $str=cs_addjs($str);

             //��̨����
			 if (defined('IS_ADMIN')){
                  $str=str_replace(SELF,"index.php",$str);
			 }

			 //α��̬
			 if (defined('PLUBPATH')){
			       $Web_Mode=config('Web_Mode',PLUBPATH);
			       if($Web_Mode==2){
					   $str=str_replace("index.php/","",$str);
				   }
			 }/*else{
			       if(Web_Mode==3){
					   $str=str_replace("index.php/","",$str);
				   }
			 }*/
			 //�ֻ������������԰�
			 if(defined('MOBILE_YM')){
	              $str  = str_replace(Web_Url,Mobile_Url,$str);
			 }
		     return $str;
	}

	//�Զ���ͳ�Ʊ�ǩ
	public function cscount($strs){
             preg_match_all('/{cscmstj\s+param\=\"([a-zA-Z0-9\_\-\|\=]+)\"}/',$strs,$Arrtj);
	         if(!empty($Arrtj[1])){
		        for($i=0;$i<count($Arrtj[1]);$i++){
                     $count=0;
		             $str=explode('|', $Arrtj[1][$i]);
			         $table=$str[0];
			         if(!empty($table) && $this->ci->db->table_exists(CS_SqlPrefix.$table)){
				          $sql="";
			              for($j=1;$j<count($str);$j++){	
				               $v=explode('=', $str[$j]);
					           if($v[0]=='times'){
                                   $k=explode('-', $v[1]);
						           $fidel=$k[0];
								   if($this->ci->db->field_exists($fidel, $table)){
						               $day=intval($k[1]);
						               $times=strtotime(date('Y-m-d 23:59:59',strtotime("-".$day." day")));
                                       $sql.="and ".$fidel.">".$times." ";
								   }
					           }else{
							       if(!empty($v[1]) || (int)$v[1]==0){
									   if($this->ci->db->field_exists($v[0], $table)){
                                           $sql.="and ".$v[0]."='".$v[1]."' ";
									   }
							       }else{
                                       $sql.="and ".$str[$j]." ";
							       }
					           }
					      }
					      if(substr($sql,0,3)=='and') $sql=substr($sql,3);
					      if(!empty($sql)) $sql=" where".$sql;
					      $sql="select count(*) as count from ".CS_SqlPrefix.$table." ".$sql;
	                      $query = $this->ci->db->query($sql)->result_array();
					      $count = (int)$query[0]['count'];
			         }
				     $strs=str_replace($Arrtj[0][$i],$count,$strs);
		        }
	         }
	         unset($Arrtj);
		     return $strs;
	}

	//����CSCMS���ǩ
	public function csskins($str,$autoarr=array()){
			 $str=$this->cscmsopt($str);
             preg_match_all('/{cscms:([\S]+)\s+(.*?)}([\s\S]+?){\/cscms:\1}/',$str,$str_arr);
		     if(!empty($str_arr)){
			     for($i=0;$i<count($str_arr[0]);$i++){
                         $str=$this->cscms_sql_to($str_arr[1][$i],$str_arr[2][$i],$str_arr[0][$i],$str_arr[3][$i],$str,$autoarr);
			     }
		     }
		     unset($str_arr);
			 //��������
             preg_match_all('/{cscmstype:([\S]+)\s+(.*?)}([\s\S]+?){\/cscmstype:\1}/',$str,$str_arr2);
		     if(!empty($str_arr2)){
			     for($i=0;$i<count($str_arr2[0]);$i++){
                        $str=$this->cscms_sql_to($str_arr2[1][$i],$str_arr2[2][$i],$str_arr2[0][$i],$str_arr2[3][$i],$str,$autoarr);
			     }
		     }
		     unset($str_arr2);
		     return $str;
	}

    //�Զ���OPTģ��
	public function cscmsopt($str,$uid=0){
			 //��ĸ��ǩ����
	         preg_match_all('/{cscmszm:([0-9]+)}([\s\S]+?){\/cscmszm}/',$str,$Mark_ZM);  //������ĸ������ǩ����
	         if(!empty($Mark_ZM)){
                 $strzm=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','����');
		         for($k=0;$k<count($Mark_ZM[0]);$k++){
                        $zmstr='';
						$zmlen=($Mark_ZM[1][$k]>27)?27:$Mark_ZM[1][$k];
		                for($i=0;$i<$zmlen;$i++){
                             $zmstr.=str_replace('[zm:name]',$strzm[$i],$Mark_ZM[2][$k]);
		                }
                        $str=str_replace($Mark_ZM[0][$k],$zmstr,$str);
				 }
	         }	
			 unset($Mark_ZM);
			 //�Զ����ǩ����
	         preg_match_all('/{cscmsself:([A-Za-z0-9-_]+)}/',$str,$Arrself);
	         if(!empty($Arrself[1])){
		        for($i=0;$i<count($Arrself[1]);$i++){
                	    $sql="SELECT selflable FROM ".CS_SqlPrefix."label where name='".$Arrself[1][$i]."'";
	        	        $row=$this->ci->db->query($sql)->row(); 
	        	        if($row){
				            $str=str_replace($Arrself[0][$i],str_decode($row->selflable),$str);
			            }
		        }
	         }
	         unset($Arrself);
			 //�Զ���JS��ǩ����
	         preg_match_all('/{cscmsjs:([A-Za-z0-9-_]+)}/',$str,$ArrJs);   
	         if(!empty($ArrJs[1])){
		        for($j=0;$j<count($ArrJs[1]);$j++){
                	    $sql="SELECT js FROM ".CS_SqlPrefix."ads where name='".$ArrJs[1][$j]."'";
	        	        $rows=$this->ci->db->query($sql)->row(); 
	        	        if($rows){
				            $str=str_replace($ArrJs[0][$j],"<script src='http://".Web_Url.Web_Path."attachment/js/".$rows->js.".js'></script>",$str);
						}
		        }
	         }
	         unset($ArrJs);
			 //�Զ���ҳ���ǩ����
	         preg_match_all('/{cscmspage:([A-Za-z0-9-_]+)}/',$str,$ArrPage);   
	         if(!empty($ArrPage[1])){
		        for($j=0;$j<count($ArrPage[1]);$j++){
                	    $sql="SELECT url FROM ".CS_SqlPrefix."page where name='".$ArrPage[1][$j]."'";
	        	        $rows=$this->ci->db->query($sql)->row(); 
	        	        if($rows){
                                if(Web_Mode==3){  //α��̬
                                       $rows->url=str_replace('index.php/','',$rows->url);
                                }
				                $str=str_replace($ArrPage[0][$j],"http://".Web_Url.$rows->url,$str);
						}
		        }
	         }
	         unset($ArrPage);
             //�Զ���optģ��
	         preg_match_all('/{cscms:opt-([A-Za-z0-9-_]+)}/',$str,$ArrBd);   //�񵥱�ǩ����
	         if(!empty($ArrBd[1])){
		          for($i=0;$i<count($ArrBd[1]);$i++){
					  $dir='';
                      if(strpos($ArrBd[1][$i],'-') !== FALSE){
                           $arr=explode('-',$ArrBd[1][$i]);
						   if(!empty($arr[0]) && is_dir(FCPATH.'plugins/'.$arr[0])){
						       $dir=$arr[0];
						   }
					  }
					  if($dir=='' && defined('PLUBPATH')){
                           $dir=PLUBPATH;
					  }
					  if($dir==''){
			               $str=str_replace($ArrBd[0][$i],site_url('opt/'.$ArrBd[1][$i]),$str);
					  }else{
						   $opt=str_replace($dir.'-','',$ArrBd[1][$i]);
						   $optlink='http://'.Web_Url.Web_Path.'index.php/'.$dir.'/opt/'.$opt;
						   //��������
	                       $Ym_Mode=config('Ym_Mode',$dir);
	                       $Ym_Url=config('Ym_Url',$dir);
	                       $Web_Mode=config('Web_Mode',$dir); //����ģʽ
						   if($Ym_Mode==1 && $Web_Mode<3 && !defined('MOBILE_YM')){
                            $optlink=str_replace(Web_Url.Web_Path.'index.php/'.$dir.'/',$Ym_Url.Web_Path.'index.php/',$optlink);
						   }
						   if($Web_Mode==2){
                               $optlink=str_replace('index.php/','',$optlink);
						   }elseif($Web_Mode==3 && !defined('MOBILE')){
                               $optlink=str_replace('index.php/'.$dir.'/','',$optlink.'_'.$dir.'.html');
						   }
                           $str=str_replace($ArrBd[0][$i],$optlink,$str);
					  }
		          }
	         }
	         unset($ArrBd);
		     return $str;
	}

    //������Ա��鵼��
	public function cscmsumenu($str,$uid=0){
	         preg_match_all('/{cscmsmenu:([A-Za-z0-9]+)}([\s\S]+?){\/cscmsmenu}/',$str,$Mark_M);   //�񵥱�ǩ����
	         if(!empty($Mark_M) && !empty($Mark_M[0][0])){
				$us=$this->ci->db->query("select level,zid from ".CS_SqlPrefix."user where id=".$uid."")->row_array();
				if(!$us){
                    $us['zid']=0;
                    $us['level']=0;
				}
                $sqlstr="select dir from ".CS_SqlPrefix."plugins order by id asc";
		        $result=$this->ci->db->query($sqlstr);
				$menu_s='';
				foreach ($result->result() as $row) {
					 if(file_exists(FCPATH.'plugins/'.$row->dir.'/config/menu.php') && file_exists(FCPATH.'plugins/'.$row->dir.'/config/site.php')){
	                      preg_match_all('/{cscmsmenu:auto}([\s\S]+?){\/cscmsmenu:auto}/',$str,$Mark_A);   //�񵥱�ǩ����
	                      if(!empty($Mark_A)){
                              $site_arr=require(FCPATH.'plugins/'.$row->dir.'/config/site.php');
                              $menu_arr=require(FCPATH.'plugins/'.$row->dir.'/config/menu.php');
						      $menu=$menu_arr['user'];
							  if(!empty($menu[0]['menu']) && getqx($us['zid'],$site_arr['User_Qx'])!='ok' && getqx($us['level'],$site_arr['User_Dj_Qx'])!='ok'){
		                          $count=count($menu[0]['menu']);
						          $mstr='';
	                              for($j=0;$j<$count;$j++){
                                     $mstr.=str_replace(array('[menu:i]','[menu:dir]','[menu:name]','[menu:link]'),array(($j+1),$row->dir,$menu[0]['menu'][$j]['name'],spacelink($menu[0]['menu'][$j]['link'],$row->dir)),$Mark_A[1][0]);
		                          }
                                  $mstr=str_replace($Mark_A[0][0],$mstr,$Mark_M[2][0]);
							  }else{
                                  $mstr='';
							  }
						  }
						  unset($Mark_A);
				     }
                     $menu_s.=$mstr;
                     if(!empty($menu[0]['name'])){
                        $menu_s=str_replace('[menu:name]',$menu[0]['name'],$menu_s);
					 }
				}
                $str=str_replace($Mark_M[0][0],$menu_s,$str);
	         }	
	         unset($Mark_M);
		     return $str;
	}

	//����ͷ�����ײ������ҷ���
	public function topandend($str,$skins=''){
		     $str = preg_replace ( "/\{cscms:head\}/", $this->topandbottom('head',$skins), $str );
		     $str = preg_replace ( "/\{cscms:left\}/", $this->topandbottom('left',$skins), $str );
		     $str = preg_replace ( "/\{cscms:right\}/", $this->topandbottom('right',$skins), $str );
		     $str = preg_replace ( "/\{cscms:bottom\}/", $this->topandbottom('bottom',$skins), $str );
             //��ȡ�ǰ��ģ��
		     $str = preg_replace ( "/\{cscms:indexhead\}/", $this->topandbottom2('indexhead',$skins), $str );
		     $str = preg_replace ( "/\{cscms:indexleft\}/", $this->topandbottom2('indexleft',$skins), $str );
		     $str = preg_replace ( "/\{cscms:indexright\}/", $this->topandbottom2('indexright',$skins), $str );
		     $str = preg_replace ( "/\{cscms:indexbottom\}/", $this->topandbottom2('indexbottom',$skins), $str );
		     return $str;
	}

	//����ͷ���ײ�ģ��
	public function topandbottom($type,$skins=''){
             $str='';
			 if(defined('PLUBPATH')){
				 if(!defined('IS_ADMIN') && defined('MOBILE') && config('Mobile_Is')==1){
				      $DIRS=APPPATH."tpl/mobile/".config('Mobile_Dir');
				 }else{
				      $DIRS=APPPATH."tpl/skins/".config('Skins_Dir');
				 }
				 if(defined('USERPATH')){
		              if(defined('MOBILE') && config('Mobile_Is')==1){
				          $DIRS.="user";
					  }else{
				          $DIRS=APPPATH."tpl/user/".config('User_Dir');
					  }
				 }
				 if(defined('HOMEPATH') && !defined('MOBILE') && config('Mobile_Is')==0){
		              if(defined('MOBILE') && config('Mobile_Is')==1){
				          $DIRS.="home";
					  }else{

				          $DIRS=empty($skins) ? APPPATH."tpl/home/".Home_Skins : APPPATH."tpl/home/".$skins;

					  }
				 }
			 }else{
				 if(!defined('IS_ADMIN') && defined('MOBILE') && Mobile_Is==1){
				      $DIRS=CSCMS."tpl/mobile/".Mobile_Skins;
				 }else{
				      $DIRS=CSCMS."tpl/skins/".Web_Skins;
				 }
				 if(defined('USERPATH')){
					  if(!defined('IS_ADMIN') && defined('MOBILE') && Mobile_Is==1){
				          $DIRS.="/user";
					  }else{
				          $DIRS=CSCMS."tpl/user/".User_Skins;
					  }
				 }
				 if(defined('HOMEPATH')){
					  if(!defined('IS_ADMIN') && defined('MOBILE') && Mobile_Is==1){
				          $DIRS.="/home";
					  }else{
						  if(empty($skins)){
				               $DIRS=CSCMS."tpl/home/".Home_Skins;
						  }else{
				               $DIRS=CSCMS."tpl/home/".$skins;
						  }
					  }
				 }
			 }
             switch($type){
				     case 'head'  : $files = str_replace("//","/",$DIRS."/head.html");break;
				     case 'left'  : $files = str_replace("//","/",$DIRS."/left.html");break;
				     case 'right' : $files = str_replace("//","/",$DIRS."/right.html");break;
				     case 'bottom': $files = str_replace("//","/",$DIRS."/bottom.html");break;
		     }
			 $str = file_exists($files) ? file_get_contents($files) : '';
             return $str;
	}

	//�����ǰ��ͷ���ײ�ģ��
	public function topandbottom2($type,$skins=''){
             $str='';
			 $Skin_Path=(defined('PLUBPATH')) ? APPPATH : CSCMS;
			 $Mobile_Is=(defined('PLUBPATH')) ? config('Mobile_Is',PLUBPATH) : Mobile_Is;
	         $Mobile_Skins=(defined('PLUBPATH')) ? config('Mobile_Dir',PLUBPATH) : Mobile_Skins;
			 if(!defined('IS_ADMIN') && defined('MOBILE') && $Mobile_Is==1){
				      $DIRS=$Skin_Path."tpl/mobile/".$Mobile_Skins;
			 }else{
				      $DIRS=CSCMS."tpl/skins/".Web_Skins;
			 }
			 if(defined('USERPATH')){
					  if(!defined('IS_ADMIN') && (!defined('MOBILE') || $Mobile_Is==0)){
				          $DIRS=CSCMS."tpl/user/".User_Skins;
					  }
			 }
			 if(defined('HOMEPATH')){
					  if(!defined('IS_ADMIN') && (!defined('MOBILE') || $Mobile_Is==0)){

						  if(empty($skins)){
				              $DIRS=CSCMS."tpl/home/".Home_Skins;
						  }else{
				              $DIRS=CSCMS."tpl/home/".$skins;
						  }
					  }
			 }
             switch($type){
				     case 'indexhead'  : $files = str_replace("//","/",$DIRS."/head.html");break;
				     case 'indexleft'  : $files = str_replace("//","/",$DIRS."/left.html");break;
				     case 'indexright' : $files = str_replace("//","/",$DIRS."/right.html");break;
				     case 'indexbottom': $files = str_replace("//","/",$DIRS."/bottom.html");break;
		     }
			 $str = file_exists($files) ? file_get_contents($files) : '';
             return $str;
	}

	//ȫ�ֻ�Ա��½��
	public function logkuang(){
			 if(defined('PLUBPATH')){ //���
				   $type=PLUBPATH;
		     }elseif(defined('USERPATH')){ //��Ա����
		           $type='user';
			 }elseif(defined('HOMEPATH')){ //��Ա�ռ�
		           $type='home';
			 }else{
		           $type='index';
			 }
			 if(defined('HOMEPATH')){
                   if(Home_Ym==1){
                        $arr = explode('.', $_SERVER['HTTP_HOST']);
                        $uid = $arr[0];
                   }else{
                        $uid = $this->ci->uri->segment(1);
                   }
		           $type.='/'.$uid;
			 }
             $Mark_Text="<div id='cscms_login'></div>
                          <script type='text/javascript'>
                                       var cscms_loginlink='http://".Web_Url.Web_Path."index.php/api/ulog/log/".$type."';
                                       var cscms_loginaddlink='http://".Web_Url.Web_Path."index.php/api/ulog/login';
                                       var cscms_logoutlink='http://".Web_Url.Web_Path."index.php/api/ulog/logout';
                                       cscms_login();
                         </script>";
		     return $Mark_Text;
	}

	//����ȫ�ֱ�ǩ
	public function cscms_common($str,$skins=''){
		     //����ͷ�����ײ������ҷ���
		     $str = $this->topandend($str,$skins);
	         $str  = str_replace("{cscms:webname}",Web_Name,$str);
	         $str  = str_replace("{cscms:weburl}",Web_Url,$str);
	         $str  = str_replace("{cscms:webpath}",Web_Path,$str);
	         $str  = str_replace("{cscms:path}","http://".Web_Url.Web_Path,$str);
	         $str  = str_replace("{cscms:indextempurl}",Skins_Dir('index',$skins),$str);
			 $dir  = !defined('PLUBPATH')?'':PLUBPATH;
	         $str  = str_replace("{cscms:tempurl}",Skins_Dir($dir,$skins),$str);
	         $str  = str_replace("{cscms:stat}",str_decode(Web_Count),$str);
	         $str  = str_replace("{cscms:notice}",str_decode(Web_Notice),$str);
	         $str  = str_replace("{cscms:regxy}",str_decode(User_Regxy),$str);

			 //SEO����
             $seo  = (defined('PLUBPATH'))?config('Seo'):'';
			 $title=(!empty($seo['title']))?$seo['title']:str_decode(Web_Title);
	         $str  = str_replace("{cscms:title}",$title,$str);
			 $keywords=(!empty($seo['keywords']))?$seo['keywords']:str_decode(Web_Keywords);
	         $str  = str_replace("{cscms:keywords}",$keywords,$str);
			 $description=(!empty($seo['description']))?$seo['description']:str_decode(Web_Description);
	         $str  = str_replace("{cscms:description}",$description,$str);

	         $str  = str_replace("{cscms:mail}",Admin_Mail,$str); 
	         $str  = str_replace("{cscms:qq}",Admin_QQ,$str);
	         $str  = str_replace("{cscms:tel}",Admin_Tel,$str);
	         $str  = str_replace("{cscms:icp}",Web_Icp,$str);

			 //�жϵ�¼״̬
			 if(isset($_SESSION['cscms__id']) && isset($_SESSION['cscms__login'])){
	              $str  = str_replace("{cscms:login}","ok",$str);
	              $str  = str_replace("{cscms:uid}",$_SESSION['cscms__id'],$str);
			 }else{
	              $str  = str_replace("{cscms:login}","no",$str);
	              $str  = str_replace("{cscms:uid}",0,$str);
			 }

			 //��վ��������
			 if(Web_Mode==3){
		          $str=str_replace('{cscms:gbooklink}','http://'.Web_Url.Web_Path.'gbook',$str);
			 }else{
		          $str=str_replace('{cscms:gbooklink}',site_url('gbook'),$str);
			 }
			 //��������
			 $solink='';
			 if($dir!=''){
		          $Ym_Mode=config('Ym_Mode',$dir); //��������״̬
		          $Ym_Url=config('Ym_Url',$dir);   //����������ַ
				  if($Ym_Mode==1){
                        $solink='http://'.$Ym_Url.Web_Path.'index.php/search';
				  }else{
                        $solink=Web_Path.'index.php/'.$dir.'/search';
				  }
			 }
		     $str=str_replace('{cscms:solink}',$solink,$str);
		     $str=str_replace('{cscms:codes}',site_url('api/codes'),$str);
		     $str=str_replace('{cscms:userlink}',site_url('user'),$str);
		     $str=str_replace('{cscms:loginlink}',site_url('user/login'),$str);
		     $str=str_replace('{cscms:reglink}',site_url('user/reg'),$str);
		     $str=str_replace('{cscms:passlink}',site_url('user/pass'),$str);
		     $str=str_replace('{cscms:qqlink}',site_url('user/open/login/qq'),$str);
	         $str=str_replace('{cscms:weibolink}',site_url('user/open/login/weibo'),$str);
	         $str=str_replace('{cscms:kaixinlink}',site_url('user/open/login/kaixin'),$str);
	         $str=str_replace('{cscms:baidulink}',site_url('user/open/login/baidu'),$str);
	         $str=str_replace('{cscms:doubanlink}',site_url('user/open/login/douban'),$str);
	         $str=str_replace('{cscms:sohulink}',site_url('user/open/login/sohu'),$str);
	         $str=str_replace('{cscms:wangyilink}',site_url('user/open/login/netease'),$str);
	         $str=str_replace('{cscms:renrenlink}',site_url('user/open/login/renren'),$str);
			 $str=userurl($str);
             return $str;
	}

	//��װSQL��ǩ
	public function cscms_sql($fields, $para, $str_arr, $label, $sorts='', $autoarr=array(), $cid=0, $sql='') {
             preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($para), $matches, PREG_SET_ORDER);
		     $arr = array('field', 'table', 'loop', 'pagesize', 'order', 'sort', 'start');
			 //��ȡ���ݱ�
			 $table=$this->arr_val('table',$matches);
             if($table==''){  //ģ���ǩ����ȱ��table����
				 $strs=str_replace($label,".....",$str_arr);
                 msg_txt(vsprintf(L('skins_table'),array($strs)));
			 }
			 //��ȡҪ��ѯ���ֶ�
			 $field=$this->arr_val('field',$matches);
			 if(!$field) $field="*";
			 if(!$this->ci->db->table_exists(CS_SqlPrefix.$table)){  //���ݱ�����
				   $strs=str_replace($label,".....",$str_arr);
                   msg_txt(vsprintf(L('skins_table_err'),array($strs,$table)));
			 }
		     if($sql==''){
				 $sql = "select ".$field." from `".CS_SqlPrefix.$table."` where 1=1";
			 }else{
				 $sql = str_replace("{field}",$field,$sql);
			 }
			 $yid=$hid=0;
		     foreach ($matches as $v) {
			     if(in_array($v[1], $arr)) {
				     $v[1] = $v[2];
				     continue;
			     }
				 //������ĸ������ǩ
				 if($v[1]=='zm'){
                     $zmall=explode(",",$v[2]);
					 if($this->ci->db->field_exists($zmall[0], $table)){ //�ж������ֶ��Ƿ����
			             $zimu_arr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			             $zimu_arr1=array(-20319,-20283,-19775,-19218,-18710,-18526,-18239,-17922,-1,-17417,-16474,-16212,-15640,-15165,-14922,-14914,-14630,-14149,-14090,-13318,-1,-1,-12838,-12556,-11847,-11055);
			             $zimu_arr2=array(-20284,-19776,-19219,-18711  ,-18527,-18240,-17923,-17418,-1,-16475,-16213,-15641,-15166,-14923,-14915,-14631,-14150,-14091,-13319,-12839,-1,-1,-12557,-11848,-11056,-2050);
                         if(!in_array(strtoupper($zmall[1]),$zimu_arr)){ //����
                              $sql.=" and substring( ".$zmall[0].", 1, 1 ) NOT REGEXP '^[a-zA-Z]' and substring( ".$zmall[0].", 1, 1 ) REGEXP '^[u4e00-u9fa5]'";
						 }else{
			                  $posarr=array_keys($zimu_arr,strtoupper($zmall[1]));
			                  $pos=$posarr[0];
			                  $sql.=" and (((ord( substring(".$zmall[0].", 1, 1 ) ) -65536>=".($zimu_arr1[$pos])." and  ord( substring( ".$zmall[0].", 1, 1 ) ) -65536<=".($zimu_arr2[$pos]).")) or UPPER(substring( ".$zmall[0].", 1, 1 ))='".$zimu_arr[$pos]."')";
						 }
					 }
				 }elseif ($this->ci->db->field_exists($v[1], $table)){ //�ж������ֶ��Ƿ����

				     if($v[2]=='auto' || substr($v[2],0,5)=='auto,'){ //��ǰ����
						  $auall=explode(",",$v[2]);
						  if(!empty($auall[1])){
							  if(empty($autoarr[$auall[1]])){
                                  $vs=0;
							  }else{
                                  $vs=(is_array($autoarr))?$autoarr[$auall[1]]:$autoarr;
							  }
						  }else{
							  if(empty($autoarr[$v[1]]) && empty($autoarr)){
                                  $vs=0;
							  }else{
                                  $vs=(is_array($autoarr))?$autoarr[$v[1]]:$autoarr;
							  }
						  }
						  if($v[1]=='tags'){ //TAGS��ǩ
					           $sql.=" and (".$this->gettags($vs).")";
						  }elseif(!empty($vs)){
							   if($v[1]=='cid' && is_numeric($vs)){
                                    if($this->ci->db->table_exists(CS_SqlPrefix.$table.'_list')){
                                         $vs=$this->ci->CsdjDB->getchild($vs,$table.'_list');
									}
							   }
							   if(strpos($vs,',') === FALSE){
					               $sql.=" and ".$v[1]."='".$vs."'";
							   }else{
					               $sql.=" and ".$v[1]." in (".$vs.")";
							   }
						  }
				     }else{
						  if($v[1]=='cid' && is_numeric($v[2])){
                              if($this->ci->db->table_exists(CS_SqlPrefix.$table.'_list')){
                                   $v[2]=$this->ci->CsdjDB->getchild($v[2],$table.'_list');
							  }
						  }
						  if(strpos($v[2],',') === FALSE){
			                  $sql.=" and ".$v[1]."=".$v[2];
						  }else{
			                  $sql.=" and ".$v[1]." in (".$v[2].")";
						  }
					 }
				 }
				 if($v[1]=='hid') $hid=1;
				 if($v[1]=='yid') $yid=1;
		     }
			 //�ж�����ֶ�
             if ($yid==0 && strpos($sql,'yid=')===FALSE && $this->ci->db->field_exists('yid', $table)){
				 $sql.=' and yid=0';
		     }
			 //�жϻ���վ�ֶ�
             if ($hid==0 && strpos($sql,'hid=')===FALSE && $this->ci->db->field_exists('hid', $table)){
				 $sql.=' and hid=0';
		     }
			 if($cid>0) $sql.=" and cid=".$cid;
			 $sort=($sorts)?$sorts:$this->arr_val('sort',$matches);
			 if(!$sort) $sort='id';
			 $order=$this->arr_val('order',$matches);
			 if(!$order) $order='desc';
			 if($sort=='rand') $sort='rand()';
			 $sql.=" order by ".$sort." ".$order;  
			 $loop=(int)$this->arr_val('loop',$matches);
			 $start=(int)$this->arr_val('start',$matches);
			 if(!$start) $start=1;
			 if($loop>0) $sql.=" limit ".($start-1).",".($loop)."";
		     unset($matches);
		     return $sql;
	}

	//����SQL��ǩ
	public function cscms_sql_to($fields, $para, $str_arr, $label, $str, $autoarr) {
		     $sql=$this->cscms_sql($fields, $para, $str_arr, $label, '', $autoarr);
		     $result_array=$this->ci->db->query($sql)->result_array();
			 if(!$result_array){
					$Data_Content="";
					$str=str_replace($str_arr,$Data_Content,$str);
			 }else{
					$Data_Content='';$sorti=1;
					foreach ($result_array as $row) {
                          $Data_Content.=$this->cscms_skins($fields,$str,$label,$row,$sorti,$autoarr);
						  $sorti++;
					}
			 }
			 $str=str_replace($str_arr,$Data_Content,$str);	
		     return $str;
	}

	//����CSCMS��ǩ
	public function cscms_skins($field, $str, $label, $row, $sorti=1, $autoarr=array()) {

		     preg_match_all('/\['.$field.':\s*([0-9a-zA-Z\_\-]+)([\s]*[link|ulink|dir|level|zd|len|style]*)[=]??([\d0-9a-zA-Z\,\_\{\}\/\-\\\\:\s]*)\]/',$str,$field_arr);
		     if(!empty($field_arr)){
			     for($i=0;$i<count($field_arr[0]);$i++){
					 $type=$field_arr[1][$i];
					 if(array_key_exists($type,$row) && trim($field_arr[2][$i])!='zd'){
						    if($type=='addtime'){
						         $label=str_replace('['.$field.':'.$type.']',date('Y-m-d H:i:s',$row[$type]),$label);
							}else{
						         $label=str_replace('['.$field.':'.$type.']',$row[$type],$label);
							}

							//�ж��Զ����ǩ
                            if(!empty($field_arr[2][$i]) && !empty($field_arr[3][$i])){
                                 //��ʽ��ʱ��
								 if(trim($field_arr[2][$i])=='style' && trim($field_arr[3][$i])=='time'){
									 $label=str_replace($field_arr[0][$i],datetime($row[$type]),$label);
                                 //��ȡIP����
								 }elseif(trim($field_arr[2][$i])=='style' && trim($field_arr[3][$i])=='city'){
									 $this->ci->load->library('ip');
									 $label=str_replace($field_arr[0][$i],$this->ci->ip->address($row[$type]),$label);
                                 //�Զ���ʱ��
								 }elseif(trim($field_arr[2][$i])=='style'){
									 $label=str_replace($field_arr[0][$i],date(str_replace('f','i',$field_arr[3][$i]),$row[$type]),$label);
                                 //ͼƬ��ַ
								 }elseif(trim($field_arr[2][$i])=='dir'){
                                     $lall=explode(",",$field_arr[3][$i]);
                                     $lass=count($lall)>1?$lall[1]:'';
									 $pic=piclink($lall[0],$row[$type],$lass);
									 $label=str_replace($field_arr[0][$i],$pic,$label);
								 }
								 //�ַ���ȡ
                                 if(trim($field_arr[2][$i])=='len'){
									 $label=str_replace($field_arr[0][$i],sub_str(str_checkhtml($row[$type]),$field_arr[3][$i]),$label);
								 }
							}
					 }else{  //�ⲿ�ֶ�

            			 	switch($type){
								  //��
				    			  case 'i'  :  
								          //�жϴӼ���ʼ
                                          if(trim($field_arr[2][$i])=='len'){
									          $label=str_replace($field_arr[0][$i],($sorti+$field_arr[3][$i]),$label);
										  }else{
									          $label=str_replace($field_arr[0][$i],$sorti,$label);
										  }
								  break;
								  //IP����λ��
				    			  case 'addres'  :  
                                           if(trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i]) && array_key_exists($field_arr[3][$i],$row)){
									            $zd=$field_arr[3][$i];
												$this->ci->load->library('ip');
									            $label=str_replace($field_arr[0][$i],$this->ci->ip->address($row[$zd]),$label);
								           }
								  break;
								  //�Զ����ֶ�
				    			  case 'zdy'  :  
                                           if(trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
									            $arr=explode(',',$field_arr[3][$i]);
                                                if(array_key_exists($arr[2],$row)){
                                                    $czd=empty($arr[3])?'id':$arr[3];
													$szd=$row[$arr[2]];
									                $label=str_replace($field_arr[0][$i],getzd($arr[0],$arr[1],$szd,$czd),$label);
												}
								           }
								  break;
								  //����ͳ��
				    			  case 'count'  : 
                                           if(trim($field_arr[2][$i])=='zdy' && !empty($field_arr[3][$i])){
												$count=0;
												$arr=explode(',',$field_arr[3][$i]);
												$table=$arr[0];
                                                $czd=empty($arr[1])?'id':$arr[1];
                                                $szd=empty($arr[2])?'id':$arr[2];
												if(array_key_exists($szd,$row)){
												     $uid=!empty($row[$szd])?$row[$szd]:(!empty($row['uid'])?$row['uid']:0);
												     if(!empty($table) && $this->ci->db->table_exists(CS_SqlPrefix.$table) && $this->ci->db->field_exists($czd, CS_SqlPrefix.$table)){
												          if(!empty($arr[3]) && (!empty($arr[4]) || (int)$arr[4]==0)){
															  if($this->ci->db->field_exists($arr[3], CS_SqlPrefix.$table)){
                                                                   $count_sql ="SELECT count(*) as count FROM ".CS_SqlPrefix.$table." where ".$czd."='".$uid."' and ".$arr[3]."='".$arr[4]."'";
	                                                               $query = $this->ci->db->query($count_sql)->result_array();
	                                                               $count = $query[0]['count'];
															  }
												          }else{
                                                               $count_sql ="SELECT count(*) as count FROM ".CS_SqlPrefix.$table." where ".$czd."='".$uid."'";
	                                                           $query = $this->ci->db->query($count_sql)->result_array();
	                                                           $count = $query[0]['count'];
														  }
												     }
												}
									            $label=str_replace($field_arr[0][$i],$count,$label);
								           }
								  break;
								  //��Ա��Ϣ
				    			  case 'user'  :  
									       if(($field=='user' or array_key_exists('uid',$row) or array_key_exists('uidb',$row)) && trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
												$ziduan=$field_arr[3][$i];
												$zdneir=($field=='gbook' or $field=='pl')?'�ο�':'null';
												if($field=='user'){
													$uid=$row['id'];
												}else{
													$lall=explode(",",$ziduan);
													if(!empty($lall[1])){
                                                       $uid=!empty($lall[1])?$lall[1]:0;
													   $ziduan=$lall[1];
													}else{
												       if(!empty($row['uid'])){
														   $uid=$row['uid'];
													   }else{
														   $uid=!empty($row['uidb'])?$row['uidb']:0;
													   }
													}
												}
												$czd=($ziduan=='nichen')?$ziduan.',name':$ziduan;
												if($this->ci->db->field_exists($ziduan, CS_SqlPrefix.'user')){
												     $rowu=$this->ci->db->query("SELECT ".$czd." FROM ".CS_SqlPrefix."user where id='".$uid."'")->row();
												     if($rowu){
													      $zdneir=($field_arr[3][$i]=='nichen' && empty($rowu->$ziduan))?$rowu->name:$rowu->$ziduan;
													 }
												}
												if($ziduan=='logo'){
													if($uid==0) $zdneir='';
                                                    $zdneir=piclink('logo',$zdneir);
												}
												if($ziduan=='zid'){
													if($zdneir==0) $zdneir=1;
                                                    $zdneir=getzd('userzu','name',$zdneir);
												}
												if($ziduan=='qianm'){
													if(empty($zdneir)) $zdneir='��ʱû��ǩ��...';
												}
												if($ziduan=='city'){
													if(empty($zdneir)) $zdneir='����';
												}
									            $label=str_replace($field_arr[0][$i],$zdneir,$label);
										   //��Ա�ȼ�
								           }elseif(($field=='user' or array_key_exists('uid',$row) or array_key_exists('uidb',$row)) && trim($field_arr[2][$i])=='level' && !empty($field_arr[3][$i])){
											    $zdneir='';
												if($field=='user'){
													$uid=$row['id'];
												}else{
												    $uid=!empty($row['uid'])?$row['uid']:(!empty($row['uidb'])?$row['uidb']:0);
												}
												$jinyan=getzd('user','jinyan',$uid);
                                                if($field_arr[3][$i]=='1'){ //������
                                                      $zdneir=getlevel($jinyan,1);
												}
                                                if($field_arr[3][$i]=='2'){ //�¸�������Ҫ����
                                                      $zdneir=getlevel($jinyan,2);
												}
                                                if($field_arr[3][$i]=='3'){ //�¸�����ʣ�ྭ��
                                                      $zdneir=getlevel($jinyan,3);
												}
                                                if($field_arr[3][$i]=='4'){ //ʣ��ٷֱ�
                                                      $zdneir=getlevel($jinyan,4);
												}
                                                if($field_arr[3][$i]=='5'){ //����
                                                      $zdneir=getlevel($jinyan,5);
												}
									            $label=str_replace($field_arr[0][$i],$zdneir,$label);
										   }
								  break;
								  //������Ϣ
				    			  case 'singer'  :  
									       if(array_key_exists('singerid',$row) && trim($field_arr[2][$i])=='zd' && !empty($field_arr[3][$i])){
												$zdneir='null';
												if($this->ci->db->table_exists(CS_SqlPrefix.'singer')){  //���ֱ����
												     $ziduan=$field_arr[3][$i];
												     if($this->ci->db->field_exists($ziduan, CS_SqlPrefix.'singer')){
												          $rows=$this->ci->db->query("SELECT ".$ziduan." FROM ".CS_SqlPrefix."singer where id='".$row['singerid']."'")->row();
												          if($rows){
													           $zdneir=$rows->$ziduan;
													      }
													 }
													 if($ziduan=='pic'){
														 if($row['singerid']==0) $zdneir='';
                                                    	 $zdneir=piclink('singer',$zdneir);
													 }
												}
									            $label=str_replace($field_arr[0][$i],$zdneir,$label);
								           }
								  break;
								  //�������
				    			  case 'murl'  :  
									       if(array_key_exists('dir',$row)){
										        $link=cscmslink($row['dir']);
									            $label=str_replace($field_arr[0][$i],$link,$label);
								           } 
								  break;
								  //��վ����
				    			  case 'url'  : 
									  //ȫ��
									  if(array_key_exists('id',$row) && trim($field_arr[2][$i])=='link' && !empty($field_arr[3][$i])){
									       $lall=explode(",",$field_arr[3][$i]);
										   $lass=count($lall)>1?$lall[1]:'';
										   $link=linkurl($lall[0],$lass,$row['id']);
									       $label=str_replace($field_arr[0][$i],$link,$label);
								      }
									  //��Ա
									  if((array_key_exists('uid',$row) || array_key_exists('uidb',$row) || $field=='user') && trim($field_arr[2][$i])=='ulink' && !empty($field_arr[3][$i])){
												$link='';
												if($field=='user'){
													 if(array_key_exists('id',$row) && array_key_exists('name',$row)){
									                      $lall=explode(",",$field_arr[3][$i]);
                                                          $lass=count($lall)>1?$lall[1]:'';
													      $link=userlink($lall[0],$row['id'],$row['name'],$lass);
													 }
												}else{
													 $uid=!empty($row['uid'])?$row['uid']:(!empty($row['uidb'])?$row['uidb']:0);
												     $rowu=$this->ci->db->query("SELECT id,name FROM ".CS_SqlPrefix."user where id='".$uid."'")->row();
												     if(!$rowu){
													      $link='http://'.Web_Url.Web_Path;
												     }else{
									                      $lall=explode(",",$field_arr[3][$i]);
														  $lass=count($lall)>1?$lall[1]:'';
													      $link=userlink($lall[0],$rowu->id,$rowu->name,$lass);
												     }
												}
									            $label=str_replace($field_arr[0][$i],$link,$label);
								      }
									  //��Ա����...
									  if(trim($field_arr[2][$i])=='userlink' && !empty($field_arr[3][$i])){
										        $link=spacelink($field_arr[3][$i]);
									            $label=str_replace($field_arr[0][$i],$link,$label);
								      }
									  //�Զ���URL����飬�ֶΣ�����������...
									  if(trim($field_arr[2][$i])=='zdy' && !empty($field_arr[3][$i])){
										        $lall=explode(",",$field_arr[3][$i]);
												if(!array_key_exists($lall[1],$row) || $row[$lall[1]]==0){
													 $link='http://'.Web_Url.Web_Path;
												}else{
										             $lass=count($lall)>3?$lall[3]:'';
													 $link=linkurl($lall[2],$lass,$row[$lall[1]],1,$lall[0]);
												}
									            $label=str_replace($field_arr[0][$i],$link,$label);
								      }
				    			  break;
		     			 	}
					 }
				 }
                 //�ж��Ƿ�Ƕ�׶���
				 preg_match_all('/{cscmstype:([\S]+)\s+(.*?)}([\s\S]+?){\/cscmstype:\1}/',$label,$type_arr);
			     if(!empty($type_arr)){
					 for($i=0;$i<count($type_arr[0]);$i++){
			             $label=$this->cscms_sql_to($type_arr[1][$i],$type_arr[2][$i],$type_arr[0][$i],$type_arr[3][$i],$label,$row['id']);
					 }
				 }
		         unset($type_arr);
			 }
		     unset($field_arr);
		     return $label;
	}

    // ��Ա��ҳģ���ǩ����
    public function cscms_web($web,$content,$str) {
        $dir=CSCMS.'tpl/home/';
		$newweb='';
	    if (is_dir($dir)){
		    if ($dh = opendir($dir)){
			    while (($file = readdir($dh))!= false){
				    $filePath = $dir.$file;
					if (is_dir($filePath)){
                      $confiles=$filePath.'/config.php';
				      if (file_exists($confiles)){
						  $con=require_once($confiles);
						  $vip='ȫ������';
						  if($con['vip']>0){
                              $vip=getzd('userzu','name',$con['vip']);
						  }
						  $level='ȫ���ȼ�';
						  if($con['level']>0){
                              $level=getzd('userlevel','name',$con['level']);
						  }
						  $pic=file_exists($filePath.'/preview.jpg')?Web_Path.'cscms/tpl/home/'.$con['dir'].'/preview.jpg':Web_Path.'packs/images/skins.jpg';
						  $ystr=array('[web:pic]','[web:name]','[web:dir]','[web:path]','[web:vip]','[web:level]','[web:cion]');
						  $xstr=array($pic,$con['name'],$con['dir'],$con['path'],$vip,$level,$con['cion']);
                          $newweb.=str_replace($ystr,$xstr,$content);
					  }
					}
			    }
			    closedir($dh);
		    }
	    }
        $str=str_replace($web,$newweb,$str);
		return $str;
    }

    // php��ǩ����
    public function cscms_php($php,$content,$str) {
		$evalstr=" return $content";
		$newsphp=@eval($evalstr);
        $str=str_replace($php,$newsphp,$str);
		return $str;
    }

	//if��ǩ����
	public function labelif($Mark_Text){
		$Mark_Text = $this->labelif2($Mark_Text);
		$ifRule = "{if:(.*?)}(.*?){end if}";
		$ifRule2 = "{elseif";
		$ifRule3 = "{else}";
		$elseIfFlag = false;
		$ifFlag = false;
		preg_match_all('/'.$ifRule.'/is',$Mark_Text,$arr);
		if(!empty($arr[1])){
			     for($i=0;$i<count($arr[1]);$i++){
			         $strIf = $arr[1][$i];
			         $strThen = $arr[2][$i];
					 if (strpos($strThen, $ifRule2) !== FALSE) {
				         $elseIfArr = explode($ifRule2, $strThen);
						 $elseIfNum = count($elseIfArr);
						 $elseIfSubArr = explode($ifRule3, $elseIfArr[$elseIfNum-1]);
				         $resultStr = $elseIfSubArr[1];
				         $elseIfstr = $elseIfArr[0];
				         @eval("if($strIf){\$resultStr=\"$elseIfstr\";}");
				         for ($k = 1;$k < $elseIfNum;$k++){
							 $temp = explode(":", $elseIfArr[$k], 2);$content = explode("}", $temp[1], 2);
					         $strElseIf = $content[0];
							 $temp1 = strpos($elseIfArr[$k],"}")+strlen("}");$temp2 = strlen($elseIfArr[$k])+1;
					         $strElseIfThen = substr($elseIfArr[$k],$temp1,$temp2-$temp1);
					         @eval("if($strElseIf){\$resultStr=\"$strElseIfThen\";}");
					         @eval("if($strElseIf){\$elseIfFlag=true;}else{\$elseIfFlag=false;}");
					         if ($elseIfFlag) {break;}
				         }
						 $temp = explode(":", $elseIfSubArr[0], 2);$content = explode("}", $temp[1], 2);
				         $strElseIf0 = $content[0];
						 $temp1 = strpos($elseIfSubArr[0],"}")+strlen("}");$temp2 = strlen($elseIfSubArr[0])+1;
				         $strElseIfThen0 = substr($elseIfSubArr[0],$temp1,$temp2-$temp1);
				         @eval("if($strElseIf0){\$resultStr=\"$strElseIfThen0\";\$elseIfFlag=true;}");
						 $Mark_Text=str_replace($arr[0][$i],$resultStr,$Mark_Text);
					 }else{
                         if(strpos($strThen, "{else}") !== FALSE) {
					         $elsearray = explode($ifRule3, $strThen);
					         $strThen1 = $elsearray[0];
					         $strElse1 = $elsearray[1];
					         @eval("if($strIf){\$ifFlag=true;}else{\$ifFlag=false;}");
					         if ($ifFlag){
								 $Mark_Text=str_replace($arr[0][$i],$strThen1,$Mark_Text);
							 }else{
								 $Mark_Text=str_replace($arr[0][$i],$strElse1,$Mark_Text);
							 }
				         } else {
				             @eval("if  ($strIf) { \$ifFlag=true;} else{ \$ifFlag=false;}");
					         if ($ifFlag){
								 $Mark_Text=str_replace($arr[0][$i],$strThen,$Mark_Text);
							 }else{
								 $Mark_Text=str_replace($arr[0][$i],"",$Mark_Text);
							 }
			             }
					 }
			     }
		}
		return $Mark_Text;
	}

	//ifǶ�ױ�ǩ����
	public function labelif2($Mark_Text){
		$ifRule = "{toif:(.*?)}(.*?){end toif}";
		$ifRule2 = "{elsetoif";
		$ifRule3 = "{elseto}";
		$elseIfFlag = false;
		$ifFlag = false;
		preg_match_all('/'.$ifRule.'/is',$Mark_Text,$arr);
		if(!empty($arr[1])){
			     for($i=0;$i<count($arr[1]);$i++){
			         $strIf = $arr[1][$i];
			         $strThen = $arr[2][$i];
					 if (strpos($strThen, $ifRule2) !== FALSE) {
				         $elseIfArr = explode($ifRule2, $strThen);
						 $elseIfNum = count($elseIfArr);
						 $elseIfSubArr = explode($ifRule3, $elseIfArr[$elseIfNum-1]);
				         $resultStr = $elseIfSubArr[1];
				         $elseIfstr = $elseIfArr[0];
				         @eval("if($strIf){\$resultStr=\"$elseIfstr\";}");
				         for ($k = 1;$k < $elseIfNum;$k++){
							 $temp = explode(":", $elseIfArr[$k], 2);$content = explode("}", $temp[1], 2);
					         $strElseIf = $content[0];
							 $temp1 = strpos($elseIfArr[$k],"}")+strlen("}");$temp2 = strlen($elseIfArr[$k])+1;
					         $strElseIfThen = substr($elseIfArr[$k],$temp1,$temp2-$temp1);
					         @eval("if($strElseIf){\$resultStr=\"$strElseIfThen\";}");
					         @eval("if($strElseIf){\$elseIfFlag=true;}else{\$elseIfFlag=false;}");
					         if ($elseIfFlag) {break;}
				         }
						 $temp = explode(":", $elseIfSubArr[0], 2);$content = explode("}", $temp[1], 2);
				         $strElseIf0 = $content[0];
						 $temp1 = strpos($elseIfSubArr[0],"}")+strlen("}");$temp2 = strlen($elseIfSubArr[0])+1;
				         $strElseIfThen0 = substr($elseIfSubArr[0],$temp1,$temp2-$temp1);
				         @eval("if($strElseIf0){\$resultStr=\"$strElseIfThen0\";\$elseIfFlag=true;}");
						 $Mark_Text=str_replace($arr[0][$i],$resultStr,$Mark_Text);
					 }else{
                         if(strpos($strThen, $ifRule3) !== FALSE) {
					         $elsearray = explode($ifRule3, $strThen);
					         $strThen1 = $elsearray[0];
					         $strElse1 = $elsearray[1];
					         @eval("if($strIf){\$ifFlag=true;}else{\$ifFlag=false;}");
					         if ($ifFlag){
								 $Mark_Text=str_replace($arr[0][$i],$strThen1,$Mark_Text);
							 }else{
								 $Mark_Text=str_replace($arr[0][$i],$strElse1,$Mark_Text);
							 }
				         } else {
				             @eval("if  ($strIf) { \$ifFlag=true;} else{ \$ifFlag=false;}");
					         if ($ifFlag){
								 $Mark_Text=str_replace($arr[0][$i],$strThen,$Mark_Text);
							 }else{
								 $Mark_Text=str_replace($arr[0][$i],"",$Mark_Text);
							 }
			             }
					 }
			     }
		}
		return $Mark_Text;
	}

	//��������ָ��Ԫ��
	public function arr_val($key,$array){
		     foreach ($array as $v) {
                  if(strtolower($v[1])==$key){
                       return $v[2];
					   break;
				  }
			 }
			 return NULL;
	}

    //��ر�ǩת��
	public function gettags($tags){
	         $tags_list=$tags_key="";
             $tags=trim($tags);
             $Str=" @,@��@|@/@_";
             $StrArr=explode('@',$Str);
             for($i=0;$i<=5;$i++){
                    if(stristr($tags,$StrArr[$i])){
                         $tags_key=explode($StrArr[$i],$tags);
                    }
	         }
             if(is_array($tags_key)){
                    for($j=0;$j<count($tags_key);$j++){
                         $tags_list.=" tags like '%".$tags_key[$j]."%' or";
			        }
	         }else{
                    $tags_list.=" tags like '%".$tags_key."%'";
	         }
             if(substr($tags_list,-2)=='or'){
                 $tags_list=substr($tags_list,0,-2);
	         }
             return $tags_list;
	}
}
