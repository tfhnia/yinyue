<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-16
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Show extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('news');
		    $this->load->model('CsdjTpl');
	}

    //����
	public function index($fid = 'id', $id = 0)
	{
            $id = (intval($fid)>0)?intval($fid):intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('news','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url('�����ˣ������ݲ����ڻ���û����ˣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('show',$fid,$id,0,'news');
				header("Location: ".$Htmllink);
				exit;
			}
			//�ݻٲ�����Ҫ���������ֶ�����
			$rows=$row; //�ȱ������鱣������ʹ��
			unset($row['tags']);
			unset($row['content']);
			//��ȡ��ǰ�����¶�������ID
			$arr['cid']=getChild($row['cid']);
			$arr['uid']=$row['uid'];
			$arr['tags']=$rows['tags'];
			//Ĭ��ģ��
			$skins=empty($row['skins'])?'show.html':$row['skins'];
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('news',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[news:pl]",get_pl('news',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[news:link]",LinkUrl('show','id',$row['id'],1,'news'),$Mark_Text);
			$Mark_Text=str_replace("[news:classlink]",LinkUrl('lists','id',$row['cid'],1,'news'),$Mark_Text);
			$Mark_Text=str_replace("[news:classname]",$this->CsdjDB->getzd('news_list','name',$row['cid']),$Mark_Text);
			//��ȡ����ƪ
            preg_match_all('/[news:slink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."news where yid=0 and hid=0 and id<".$id." order by id desc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[news:slink]",LinkUrl('show','id',$rowd->id,1,'news'),$Mark_Text);
			             $Mark_Text=str_replace("[news:sname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[news:sid]",$rowd->id,$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[news:slink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[news:sname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[news:sid]",0,$Mark_Text);
				   }
			}
			unset($arr);
            preg_match_all('/[news:xlink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."news where yid=0 and hid=0 and id>".$id." order by id asc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[news:xlink]",LinkUrl('show','id',$rowd->id,1,'news'),$Mark_Text);
			             $Mark_Text=str_replace("[news:xname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[news:xid]",$rowd->id,$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[news:xlink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[news:xname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[news:xid]",0,$Mark_Text);
				   }
			}
			unset($arr);
			//��ǩ�ӳ�������
			$Mark_Text=str_replace("[news:tags]",SearchLink($rows['tags']),$Mark_Text);
            //��������,�ж��Ƿ����շ�����
			if($row['vip']>0 || $row['level']>0 || $row['cion']>0){
				  $content="<div id='cscms_content'></div>";
				  if(config('Ym_Mode','news')==1){
                        $content.="<script type='text/javascript' src='http://".config('Ym_Url','news').Web_Path."index.php/show/pay/".$id."'></script>";
				  }else{
                        $content.="<script type='text/javascript' src='http://".Web_Url.Web_Path."index.php/news/show/pay/".$id."'></script>";
				  }
			}else{
                  $content=$rows['content'];
			}
			$Mark_Text=str_replace("[news:content]",$content,$Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'news'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

    //�ж�Ȩ�ޡ�����
	public function pay($id=0)
	{
            @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
            @header("Cache-Control: no-cache, must-revalidate"); 
            @header("Pragma: no-cache");
		    $this->load->model('CsdjUser');
            //�ж�ID
            if($id==0) exit();
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('news','name,uid,cid,yid,hid,id,vip,level,cion,content',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     exit("alert('����û����ˣ����߱�ɾ��~!');");
		    }

			//�ж��շ�
            if($row['vip']>0 || $row['level']>0 || $row['cion']>0){
                  $login=$this->CsdjUser->User_Login(1);
                  if(!$login) exit("alert('��Ǹ����������Ҫ��¼�����Ķ������ȵ�¼��');");
				  $rowu=$this->CsdjDB->get_row_arr('user','zid,zutime,vip,level,cion',$_SESSION['cscms__id']);
				  if($rowu['zutime']<time()){
						$this->db->query("update ".CS_SqlPrefix."user set zid=1,zutime=0 where id=".$_SESSION['cscms__id']."");
                        $rowu['zid']=1;
				  }
			}
            //�жϻ�Ա��Ȩ��
			if($row['vip']>0 && $row['uid']!=$_SESSION['cscms__id'] && $rowu['vip']==0){
				  if($row['vip']>$rowu['zid']){
					   exit("alert('��Ǹ�������ڵĻ�Ա�鲻���Ķ������£�����������');");
				  }
			}

            //�жϻ�Ա�ȼ�Ȩ��
			if($row['level']>0 && $row['uid']!=$_SESSION['cscms__id']){
				  if($row['level']>$rowu['level']){
					   exit("alert('��Ǹ�����ȼ������������Ķ������£�');");
				  }
			}

            //�жϽ��
			$down=0;
			if($row['cion']>0 && $row['uid']!=$_SESSION['cscms__id']){

				  //�ж��Ƿ����ع�
				  $did=$id;
				  $rowd=$this->db->query("SELECT id,addtime FROM ".CS_SqlPrefix."news_look where did='".$did."' and uid='".$_SESSION['cscms__id']."'")->row_array();
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
						   exit("alert('��������Ķ���Ҫ".$row['cion']."����ң����ĵ�ǰ��Ҳ��������ȳ�ֵ��');");
				      }else{
						  //�۱�
						  $edit['cion']=$rowu['cion']-$row['cion'];
						  $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);
						  //д�����Ѽ�¼
						  $add2['title']='�Ķ����¡�'.$row['name'].'��';
						  $add2['uid']=$_SESSION['cscms__id'];
						  $add2['nums']=$row['cion'];
						  $add2['ip']=getip();
						  $add2['dir']='news';
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
						              $add3['title']='���¡�'.$row['name'].'�� - �Ķ��ֳ�';
						              $add3['uid']=$row['uid'];
						              $add3['dir']='news';
						              $add3['nums']=$scion;
						              $add3['ip']=getip();
                                      $add3['addtime']=time();
					                  $this->CsdjDB->get_insert('income',$add3);
								 }
						  }
					  }
				  }
			      //�����Ķ���¼
			      if($down==0){
                       $add['name']=$row['name'];
                       $add['cid']=$row['cid'];
                       $add['did']=$did;
                       $add['uid']=$_SESSION['cscms__id'];
                       $add['cion']=$row['cion'];
                       $add['addtime']=time();
					   $this->CsdjDB->get_insert('news_look',$add);
			      }
			}
			echo "var cscms_content='".escape($row['content'])."';$('#cscms_content').html(unescape(cscms_content));";
	}
}


