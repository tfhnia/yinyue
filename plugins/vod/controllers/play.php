<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Play extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('vod');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	}

    //��������
	public function index($a1 , $a2 = 0, $a3 = 0, $a4 = 0)
	{
            if(intval($a1)>0){
                $id = intval($a1);   //ID
                $zu = intval($a2);   //��
                $ji = intval($a3);   //����
			}else{
                $id = intval($a2);   //ID
                $zu = intval($a3);   //��
                $ji = intval($a4);   //����
			}
			$login='no';

            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('vod','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url('�����ˣ������ݲ����ڻ���û����ˣ�',Web_Path);
		    }
		    if(empty($row['purl'])){
                     msg_url('����Ƶ���ŵ�ַ����ȷ��',Web_Path);
		    }

            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['play']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=VodPlayUrl('play',$id,$zu,$ji);
				header("Location: ".$Htmllink);
				exit;
			}

			//�ж��շ�
            if($row['vip']>0 || $row['level']>0 || $row['cion']>0){
                  if(!$this->CsdjUser->User_Login(1)){
					  msg_url('�ۿ��ⲿ��Ƶ��Ҫ��¼�����ȵ�¼��',spacelink('login'));
				  }
				  $rowu=$this->CsdjDB->get_row_arr('user','vip,zid,zutime,level,cion',$_SESSION['cscms__id']);
				  if($rowu['zutime']<time()){
						$this->db->query("update ".CS_SqlPrefix."user set zid=1,zutime=0 where id=".$_SESSION['cscms__id']."");
                        $rowu['zid']=1;
				  }
			}
            //�жϻ�Ա������Ȩ��
			if($row['vip']>0 && $row['uid']!=$_SESSION['cscms__id'] && $rowu['vip']==0){
				  if($row['vip']>$rowu['zid']){
                       msg_url('��Ǹ�������ڵĻ�Ա�鲻�ܹۿ�����Ƶ������������','javascript:window.close();');
				  }
			}

            //�жϻ�Ա�ȼ�����Ȩ��
			if($row['level']>0 && $row['uid']!=$_SESSION['cscms__id']){
				  if($row['level']>$rowu['level']){
                       msg_url('��Ǹ�����ȼ����������ܹۿ�����Ƶ��','javascript:window.close();');
				  }
			}

            //�жϽ������
			$down=0;
			if($row['cion']>0 && $row['uid']!=$_SESSION['cscms__id']){

				  //�ж��Ƿ����ع�
				  $did=$id.'-'.$zu.'-'.$ji;
				  $rowd=$this->db->query("SELECT id,addtime FROM ".CS_SqlPrefix."vod_look where did='".$did."' and uid='".$_SESSION['cscms__id']."' and sid=0")->row_array();
				  if($rowd){
					  $down=1; //�����Ѿ�����
					  $downtime=User_Downtime*3600+$rowd['addtime'];
					  if($downtime>time()){
				           $down=2; //�ڶ���ʱ���ڲ��ظ��۱�
					  }
				  }
				  //�жϻ�Ա������Ȩ��
				  $rowz=$this->db->query("SELECT id,did FROM ".CS_SqlPrefix."userzu where id='".$rowu['zid']."'")->row_array();
				  if($rowz && $rowz['did']==1){ //���������Ȩ��
                       $down=2; //�û�Ա���ز��շ�
				  }
                  if($down<2){ //�жϿ۱�
				      if($row['cion']>$rowu['cion']){
                           msg_url('�ⲿ��Ƶ�ۿ�ÿ����Ҫ'.$row['cion'].'����ң����ĵ�ǰ��Ҳ��������ȳ�ֵ��','javascript:window.close();');
				      }else{
						  //�۱�
						  $edit['cion']=$rowu['cion']-$row['cion'];
						  $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);
						  //д�����Ѽ�¼
						  $add2['title']='�ۿ���Ƶ��'.$row['name'].'��- ��'.($ji+1).'��';
						  $add2['uid']=$_SESSION['cscms__id'];
						  $add2['nums']=$row['cion'];
						  $add2['ip']=getip();
						  $add2['dir']='vod';
                          $add2['addtime']=time();
					      $this->CsdjDB->get_insert('spend',$add2);

						  //�жϷֳ�
						  if(User_DownFun==1 && $row['uid']>0){
							     //�ֳɱ���
								 $bi=(User_Downcion<10)?'0.0'.User_Downcion:'0.'.User_Downcion;
                                 $scion= intval($row['cion'] * $bi);
								 if($scion>0){
						              $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".$scion." where id=".$row['uid']."");
						              //д��ֳɼ�¼
						              $add3['title']='��Ƶ��'.$row['name'].'��- ��'.($ji+1).'�� - �ۿ��ֳ�';
						              $add3['uid']=$row['uid'];
						              $add3['dir']='vod';
						              $add3['nums']=$scion;
						              $add3['ip']=getip();
                                      $add3['addtime']=time();
					                  $this->CsdjDB->get_insert('income',$add3);
								 }
						  }
					  }
				  }
			      //���ӹۿ���¼
			      if($down==0){
                       $add['name']=$row['name'];
                       $add['cid']=$row['cid'];
                       $add['sid']=0;
                       $add['did']=$did;
                       $add['uid']=$_SESSION['cscms__id'];
                       $add['cion']=$row['cion'];
                       $add['addtime']=time();
					   $this->CsdjDB->get_insert('vod_look',$add);
			      }
			}

			//�ݻٲ�����Ҫ���������ֶ�����
			$rows=$row; //�ȱ������鱣������ʹ��
			unset($row['zhuyan']);
			unset($row['daoyan']);
			unset($row['yuyan']);
			unset($row['diqu']);
			unset($row['tags']);
			unset($row['year']);
			unset($row['pfen']);
			unset($row['phits']);
			//��ȡ��ǰ�����¶�������ID
			$arr['cid']=getChild($row['cid']);
			$arr['uid']=$row['uid'];
			$arr['singerid']=$row['singerid'];
			$arr['tags']=$rows['tags'];
			$skins=$row['skins'];
			if(empty($skins) || $skins=='play.html'){
			     $skins=getzd('vod_list','skins3',$row['cid']);
			}
			if(empty($skins)) $skins='play.html';
			//����ID
			$cacheid='vod_play_'.$id.'_'.$zu.'_'.$ji;
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('vod',$row,$arr,TRUE,$skins,$row['name'],$row['name'],'',$cacheid);
			//����
			$Mark_Text=str_replace("[vod:pl]",get_pl('vod',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[vod:zu]",($zu+1),$Mark_Text);
			$Mark_Text=str_replace("[vod:ji]",($ji+1),$Mark_Text);
			$Mark_Text=str_replace("[vod:link]",LinkUrl('show','id',$row['id'],1,'vod'),$Mark_Text);
			$Mark_Text=str_replace("[vod:playlink]",VodPlayUrl('play',$id,$zu,$ji),$Mark_Text);
			$Mark_Text=str_replace("[vod:classlink]",LinkUrl('lists','id',$row['cid'],1,'vod'),$Mark_Text);
			$Mark_Text=str_replace("[vod:classname]",$this->CsdjDB->getzd('vod_list','name',$row['cid']),$Mark_Text);
			//���ݡ����ݡ���ǩ����ݡ����������Լӳ�������
			$Mark_Text=str_replace("[vod:zhuyan]",SearchLink($rows['zhuyan'],'zhuyan'),$Mark_Text);
			$Mark_Text=str_replace("[vod:daoyan]",SearchLink($rows['daoyan'],'daoyan'),$Mark_Text);
			$Mark_Text=str_replace("[vod:yuyan]",SearchLink($rows['yuyan'],'yuyan'),$Mark_Text);
			$Mark_Text=str_replace("[vod:diqu]",SearchLink($rows['diqu'],'diqu'),$Mark_Text);
			$Mark_Text=str_replace("[vod:tags]",SearchLink($rows['tags']),$Mark_Text);
			$Mark_Text=str_replace("[vod:year]",SearchLink($rows['year'],'year'),$Mark_Text);
			//����
			$Mark_Text=str_replace("[vod:pfen]",getpf($rows['pfen'],$rows['phits']),$Mark_Text);
			$Mark_Text=str_replace("[vod:pfenbi]",getpf($rows['pfen'],$rows['phits'],2),$Mark_Text);
			//�������ŵ�ַ
            $Mark_Text=Vod_Playlist($Mark_Text,'play',$id,$row['purl']);
			//������
	        $Data_Arr=explode("#cscms#",$row['purl']);
            if($zu>=count($Data_Arr)) $zu=0;
		    $DataList_Arr=explode("\n",$Data_Arr[$zu]);
		    $Dataurl_Arr=explode('$',$DataList_Arr[$ji]);

		    $xpurl="";  //�¼����ŵ�ַ
		    $laiyuan=str_replace("\r","",@$Dataurl_Arr[2]); //��Դ
		    $url=$Dataurl_Arr[1];  //��ַ
		    $pname=$Dataurl_Arr[0];  //��ǰ����
		    $Mark_Text=str_replace("[vod:qurl]",annexlink($url),$Mark_Text);
		    $Mark_Text=str_replace("[vod:laiy]",$laiyuan,$Mark_Text);
		    $Mark_Text=str_replace("[vod:jiname]",$pname,$Mark_Text);
			if(substr($url,0,11)=='attachment/') $url=annexlink($url);
			//�ֻ����ŵ�ַ
			if(substr($url,0,7)=='http://' || substr($url,0,12)=='/attachment/'){
			     $wapurl=$url;
			}else{
			     $wapurl='http://download.chshcms.com/mp4/'.$laiyuan.'/'.cs_base64_encode($url).'/cscms.mp4';
			}
		    $Mark_Text=str_replace("[vod:wapurl]",$wapurl,$Mark_Text);

		    if(count($DataList_Arr)>($ji+1)){
			    $DataNext=$DataList_Arr[($ji+1)];
			    $DataNextArr=explode('$',$DataNext);
			    if(count($DataNextArr)==2) $DataNext=$DataNextArr[1];
			    $xurl=VodPlayUrl('play',$id,$zu,($ji+1));
		        $Dataurl_Arr2=explode('$',$DataList_Arr[($ji+1)]);
                $xpurl=@$Dataurl_Arr2[1];  //�¼����ŵ�ַ
		    }else{
			    $DataNext=$DataList_Arr[$ji];
			    $DataNextArr=explode('$',$DataNext);
			    if(count($DataNextArr)==2) $DataNext=$DataNextArr[1];			
			    $xurl='#';
                $xpurl='';  //�¼����ŵ�ַ
		    }
            if($ji==0){
			    $surl='#';
            }else{
			    $surl=VodPlayUrl('play',$id,$zu,($ji-1));
            }
            $psname='';
			for($j=0;$j<count($Data_Arr);$j++){
				   $jis='';
			       $Ji_Arr=explode("\n",$Data_Arr[$j]);
                   for($k=0;$k<count($Ji_Arr);$k++){
			            $Ly_Arr=explode('$',$Ji_Arr[$k]);
						$jis.=$Ly_Arr[0].'$$'.@$Ly_Arr[2].'====';
				   }
				   $psname.=substr($jis,0,-4).'#cscms#';
			}
            $player_arr=str_replace("\r","",substr($psname,0,-7));
	        if($laiyuan=='xgvod'||$laiyuan=='jjvod'||$laiyuan=='yyxf'||$laiyuan=='bdhd'||$laiyuan=='qvod'){
				$xpurl=str_replace("+","__",base64_encode($xpurl));
	            $url=str_replace("+","__",base64_encode($url));
			}else{
				$xpurl=escape($xpurl);
	            $url=escape($url);
			}
		    $player="<script type='text/javascript' src='".hitslink('play/form','vod')."'></script><script type='text/javascript'>var cs_playlink='".VodPlayUrl('play',$id,$zu,$ji,1)."';var cs_did='".$id."';var player_name='".$player_arr."';var cs_pid='".$ji."';var cs_zid='".$zu."';var cs_vodname='".$row['name']." - ".$pname."';var cs_root='".Web_Path."';var cs_width=".CS_Play_sw.";var cs_height=".CS_Play_sh.";var cs_surl='".$surl."';var cs_xurl='".$xurl."';var cs_url='".$url."';var cs_xpurl='".$xpurl."';var cs_laiy='".$laiyuan."';var cs_adloadtime='".CS_Play_AdloadTime."';</script><iframe border=\"0\" name=\"cscms_vodplay\" id=\"cscms_vodplay\" src=\"".Web_Path."packs/vod_player/play.html\" marginwidth=\"0\" framespacing=\"0\" marginheight=\"0\" noresize=\"\" vspale=\"0\" style=\"z-index: 9998;\" frameborder=\"0\" height=\"".(CS_Play_sh+30)."\" scrolling=\"no\" width=\"100%\" allowfullscreen></iframe>";
		    $Mark_Text=str_replace("[vod:player]",$player,$Mark_Text);
		    $Mark_Text=str_replace("[vod:surl]",$surl,$Mark_Text);
		    $Mark_Text=str_replace("[vod:xurl]",$xurl,$Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'vod'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

    //�ж�Ȩ�ޡ�����
	public function pay($id=0,$zu=0,$ji=0)
	{

            //�ж�ID
            if($id==0) exit();
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('vod','name,cid,uid,yid,hid,id,vip,level,cion,purl',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     exit("alert('����û����ˣ����߱�ɾ��~!');");
		    }
		    if(empty($row['purl'])){
					 exit("alert('��Ƶ���ŵ�ַ����ȷ��');");
		    }

			//�ж��շ�
            if($row['vip']>0 || $row['level']>0 || $row['cion']>0){
                  $login=$this->CsdjUser->User_Login(1);
				  if(!$login) exit("alert('��Ǹ������Ƶ��Ҫ��¼���ܹۿ������ȵ�¼��');");
				  $rowu=$this->CsdjDB->get_row_arr('user','vip,zid,zutime,level,cion',$_SESSION['cscms__id']);
				  if($rowu['zutime']<time()){
						$this->db->query("update ".CS_SqlPrefix."user set zid=1,zutime=0 where id=".$_SESSION['cscms__id']."");
                        $rowu['zid']=1;
				  }
			}

            //�жϻ�Ա������Ȩ��
			if($row['vip']>0 && $row['uid']!=$_SESSION['cscms__id'] && $rowu['vip']==0){
				  if($row['vip']>$rowu['zid']){
					   exit("alert('��Ǹ�������ڵĻ�Ա�鲻�ܹۿ�����Ƶ������������');");
				  }
			}

            //�жϻ�Ա�ȼ�����Ȩ��
			if($row['level']>0 && $row['uid']!=$_SESSION['cscms__id']){
				  if($row['level']>$rowu['level']){
					   exit("alert('��Ǹ�����ȼ����������ܹۿ�����Ƶ��');");
				  }
			}

            //�жϽ������
			$down=0;
			if($row['cion']>0 && $row['uid']!=$_SESSION['cscms__id']){

				  //�ж��Ƿ����ع�
				  $did=$id.'-'.$zu.'-'.$ji;
				  $rowd=$this->db->query("SELECT id,addtime FROM ".CS_SqlPrefix."vod_look where did='".$did."' and uid='".$_SESSION['cscms__id']."' and sid=0")->row_array();
				  if($rowd){
					  $down=1; //�����Ѿ�����
					  $downtime=User_Downtime*3600+$rowd['addtime'];
					  if($downtime>time()){
				           $down=2; //�ڶ���ʱ���ڲ��ظ��۱�
					  }
				  }
				  //�жϻ�Ա������Ȩ��
				  $rowz=$this->db->query("SELECT id,did FROM ".CS_SqlPrefix."userzu where id='".$rowu['zid']."'")->row_array();
				  if($rowz && $rowz['did']==1){ //���������Ȩ��
                       $down=2; //�û�Ա���ز��շ�
				  }
                  if($down<2){ //�жϿ۱�
				      if($row['cion']>$rowu['cion']){
						   exit("alert('�ⲿ��Ƶ�ۿ�ÿ����Ҫ".$row['cion']."����ң����ĵ�ǰ��Ҳ��������ȳ�ֵ��');");
				      }else{
						  //�۱�
						  $edit['cion']=$rowu['cion']-$row['cion'];
						  $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);
						  //д�����Ѽ�¼
						  $add2['title']='�ۿ���Ƶ��'.$row['name'].'��- ��'.($ji+1).'��';
						  $add2['uid']=$_SESSION['cscms__id'];
						  $add2['nums']=$row['cion'];
						  $add2['ip']=getip();
						  $add2['dir']='vod';
                          $add2['addtime']=time();
					      $this->CsdjDB->get_insert('spend',$add2);

						  //�жϷֳ�
						  if(User_DownFun==1 && $row['uid']>0){
							     //�ֳɱ���
								 $bi=(User_Downcion<10)?'0.0'.User_Downcion:'0.'.User_Downcion;
                                 $scion= intval($row['cion'] * $bi);
								 if($scion>0){
						              $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".$scion." where id=".$row['uid']."");
						              //д��ֳɼ�¼
						              $add3['title']='��Ƶ��'.$row['name'].'��- ��'.($ji+1).'�� - �ۿ��ֳ�';
						              $add3['uid']=$row['uid'];
						              $add3['dir']='vod';
						              $add3['nums']=$scion;
						              $add3['ip']=getip();
                                      $add3['addtime']=time();
					                  $this->CsdjDB->get_insert('income',$add3);
								 }
						  }
					  }
				  }
			      //���ӹۿ���¼
			      if($down==0){
                       $add['name']=$row['name'];
                       $add['cid']=$row['cid'];
                       $add['sid']=0;
                       $add['did']=$did;
                       $add['uid']=$_SESSION['cscms__id'];
                       $add['cion']=$row['cion'];
                       $add['addtime']=time();
					   $this->CsdjDB->get_insert('vod_look',$add);
			      }
			}
            $xpurl="";  //�¼����ŵ�ַ
            $Data_Arr=explode("#cscms#",$row['purl']);
            if($zu>=count($Data_Arr)) $zu=0;
		    $DataList_Arr=explode("\n",$Data_Arr[$zu]);
		    $Dataurl_Arr=explode('$',$DataList_Arr[$ji]);
			$laiyuan=$Dataurl_Arr[2]; //��Դ
		    $url=$Dataurl_Arr[1];  //��ַ
			if(substr($url,0,11)=='attachment/') $url=annexlink($url);
		    if(count($DataList_Arr)>($ji+1)){
		           $Dataurl_Arr2=explode('$',$DataList_Arr[($ji+1)]);
                   $xpurl=@$Dataurl_Arr2[1];  //�¼����ŵ�ַ
		    }else{
                   $xpurl='';  //�¼����ŵ�ַ
		    }
	        if($laiyuan=='xgvod'||$laiyuan=='jjvod'||$laiyuan=='yyxf'||$laiyuan=='bdhd'||$laiyuan=='qvod'){
				   $xpurl=str_replace("+","__",base64_encode($xpurl));
	               $url=str_replace("+","__",base64_encode($url));
			}else{
				   $xpurl=escape($xpurl);
	               $url=escape($url);
			}
			//�ֻ����ŵ�ַ
			if(substr($url,0,7)=='http://' || substr($url,0,12)=='/attachment/'){
			     $url=$url;
			}else{
			     $url='http://download.chshcms.com/mp4/'.$laiyuan.'/'.cs_base64_encode($url).'/cscms.mp4';
			}
			echo "var cs_url='".$url."';var cs_xpurl='".$xpurl."';";
	}

    //����������
	public function form()
	{
		    $str='var cscms_vod_player={};';
            require_once APPPATH.'config/player.php';
			$player=$player_config;
			for ($i=0; $i<count($player); $i++) {
					$str.="cscms_vod_player['".$player[$i]['form']."']='".$player[$i]['name']."';";
			}
			echo $str;
	}
}


