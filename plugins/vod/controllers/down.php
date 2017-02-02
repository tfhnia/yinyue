<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-08
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Down extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('vod');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	}

    //����
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
		    if(empty($row['durl'])){
                     msg_url('����Ƶ���ص�ַ����ȷ��',Web_Path);
		    }

			//�ж��շ�
            if($row['vip']>0 || $row['level']>0 || $row['cion']>0 || User_YkDown==0){
                  $this->CsdjUser->User_Login();
				  $rowu=$this->CsdjDB->get_row_arr('user','vip,zid,zutime,level,cion',$_SESSION['cscms__id']);
				  if($rowu['zutime']<time()){
						$this->db->query("update ".CS_SqlPrefix."user set zid=1,zutime=0 where id=".$_SESSION['cscms__id']."");
                        $rowu['zid']=1;
				  }
			}

            //�жϻ�Ա������Ȩ��
			if($row['vip']>0 && $row['uid']!=$_SESSION['cscms__id'] && $rowu['vip']==0){
				  if($row['vip']>$rowu['zid']){
                       msg_url('��Ǹ�������ڵĻ�Ա�鲻�����ظ���Ƶ������������','javascript:window.close();');
				  }
			}

            //�жϻ�Ա�ȼ�����Ȩ��
			if($row['level']>0 && $row['uid']!=$_SESSION['cscms__id']){
				  if($row['level']>$rowu['level']){
                       msg_url('��Ǹ�����ȼ��������������ظ���Ƶ��','javascript:window.close();');
				  }
			}

            //�жϽ������
			$down=0;
			if($row['dcion']>0 && $row['uid']!=$_SESSION['cscms__id']){

				  //�ж��Ƿ����ع�
				  $did=$id.'-'.$zu.'-'.$ji;
				  $rowd=$this->db->query("SELECT id,addtime FROM ".CS_SqlPrefix."vod_look where did='".$did."' and uid='".$_SESSION['cscms__id']."' and sid=1")->row_array();
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
				      if($row['dcion']>$rowu['cion']){
                           msg_url('�ⲿ��Ƶ����ÿ����Ҫ'.$row['cion'].'����ң����ĵ�ǰ��Ҳ��������ȳ�ֵ��','javascript:window.close();');
				      }else{
						  //�۱�
						  $edit['cion']=$rowu['cion']-$row['dcion'];
						  $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);
						  //д�����Ѽ�¼
						  $add2['title']='������Ƶ��'.$row['name'].'��- ��'.($ji+1).'��';
						  $add2['uid']=$_SESSION['cscms__id'];
						  $add2['dir']='vod';
						  $add2['nums']=$row['cion'];
						  $add2['ip']=getip();
                          $add2['addtime']=time();
					      $this->CsdjDB->get_insert('spend',$add2);

						  //�жϷֳ�
						  if(User_DownFun==1 && $row['uid']>0){
							     //�ֳɱ���
								 $bi=(User_Downcion<10)?'0.0'.User_Downcion:'0.'.User_Downcion;
                                 $scion= intval($row['dcion'] * $bi);
								 if($scion>0){
						              $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".$scion." where id=".$row['uid']."");
						              //д��ֳɼ�¼
						              $add3['title']='��Ƶ��'.$row['name'].'��- ��'.($ji+1).'�� - ���طֳ�';
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
			      //�������ؼ�¼
			      if($down==0){
                       $add['name']=$row['name'];
                       $add['cid']=$row['cid'];
                       $add['sid']=1;
                       $add['did']=$did;
                       $add['uid']=$_SESSION['cscms__id'];
                       $add['cion']=$row['dcion'];
                       $add['addtime']=time();
					   $this->CsdjDB->get_insert('vod_look',$add);
			      }
			}
			//������������
			$this->db->query("update ".CS_SqlPrefix."vod set xhits=xhits+1 where id=".$row['id']."");
			//�����������
			$arr['cid']=getChild($row['cid']);
			$arr['uid']=$row['uid'];
			$arr['tags']=$row['tags'];
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('vod',$row,$arr,TRUE,'down.html');
			//����
			$dance_pl="<div id='cscms_pl'><img src='".Web_Path."packs/images/load.gif'>&nbsp;&nbsp;������������,���Ե�......</div>\r\n<script type='text/javascript'>var dir='vod';var did=".$id.";var cid=0;cscms_pl(1,0,0);</script>";
			$Mark_Text=str_replace("[vod:pl]",$dance_pl,$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[vod:link]",LinkUrl('show','id',$row['id'],1,'vod'),$Mark_Text);
			$Mark_Text=str_replace("[vod:classlink]",LinkUrl('lists','id',$row['cid'],1,'vod'),$Mark_Text);
			$Mark_Text=str_replace("[vod:classname]",$this->CsdjDB->getzd('vod_list','name',$row['cid']),$Mark_Text);

			//������ص�ַ
	        $Data_Arr=explode("#cscms#",$row['durl']);
            if($zu>=count($Data_Arr)) $zu=0;
		    $DataList_Arr=explode("\n",$Data_Arr[$zu]);
		    $Dataurl_Arr=explode('$',$DataList_Arr[$ji]);

		    $laiyuan=$Dataurl_Arr[2]; //��Դ
		    $url=$Dataurl_Arr[1];  //��ַ
		    $pname=$Dataurl_Arr[0];  //��ǰ����
			if(substr($url,0,11)=='attachment/') $url=annexlink($url);
		    $Mark_Text=str_replace("[down:url]",$url,$Mark_Text); //��ǰ�����ص�ַ
		    $Mark_Text=str_replace("[down:laiy]",$laiyuan,$Mark_Text); //��ǰ����Դ
		    $Mark_Text=str_replace("[down:ji]",$pname,$Mark_Text);  //��ǰ����

            echo $Mark_Text;
	}
}


