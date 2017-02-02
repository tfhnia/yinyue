<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-01-25
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Down extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl');
	}

    //��������
	public function index($fid = 'id', $id = 0)
	{
            $id = (intval($fid)>0)?intval($fid):intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url(L('dance_09'),Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url(L('dance_10'),Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['down']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('play','id',$id,0,'dance');
				header("Location: ".$Htmllink);
				exit;
			}
			//�ݻٲ�����Ҫ���������ֶ�����
			$rows=$row; //�ȱ������鱣������ʹ��
			unset($row['tags']);
			//��ȡ��ǰ�����¶�������ID
			$arr['cid']=getChild($row['cid']);
			$arr['uid']=$row['uid'];
			$arr['singerid']=$row['singerid'];
			$arr['tags']=$rows['tags'];
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('dance',$row,$arr,TRUE,'down.html',$row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[dance:pl]",get_pl('dance',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[dance:link]",LinkUrl('play','id',$row['id'],1,'news'),$Mark_Text);
			$Mark_Text=str_replace("[dance:classlink]",LinkUrl('lists','id',$row['cid'],1,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[dance:classname]",$this->CsdjDB->getzd('dance_list','name',$row['cid']),$Mark_Text);
			//ר��
			if($row['tid']==0){
			     $Mark_Text=str_replace("[dance:topiclink]","###",$Mark_Text);
			     $Mark_Text=str_replace("[dance:topicname]",L('dance_11'),$Mark_Text);
			}else{
			     $Mark_Text=str_replace("[dance:topiclink]",LinkUrl('topic','show',$row['tid'],1,'dance'),$Mark_Text);
			     $Mark_Text=str_replace("[dance:topicname]",$this->CsdjDB->getzd('dance_topic','name',$row['tid']),$Mark_Text);
			}
			//��ǩ�ӳ�������
			$Mark_Text=str_replace("[dance:tags]",SearchLink($rows['tags']),$Mark_Text);
			//�����������������ص�ַ
            preg_match_all('/[dance:qurl]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
				 $purl=$row['purl'];
				 $durl=$row['durl'];
                 if($row['fid']>0){
                      $rowf=$this->db->query("Select purl,durl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
					  if($rowf){
				           $purl=$rowf['purl'].$row['purl'];
				           $durl=$rowf['durl'].$row['durl'];
					  }
				 }
				 $purl=annexlink($purl);
				 $durl=annexlink($durl);
                 $Mark_Text=str_replace("[dance:qurl]",$purl,$Mark_Text);
                 $Mark_Text=str_replace("[dance:qxurl]",$durl,$Mark_Text);
			}
			unset($arr);
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

	//���ص�����
	public function load($id = 0)
	{
            @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
            @header("Cache-Control: no-cache, must-revalidate"); 
            @header("Pragma: no-cache");
		    $this->load->model('CsdjUser');
			$login='no';
            $id = (int)$id;   //ID
            //�ж�ID
            if($id==0) msg_url(L('dance_12'),Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance','id,cid,name,durl,fid,uid,cion,vip,level',$id);
		    if(!$row) msg_url(L('dance_12'),Web_Path);
		    if(empty($row['durl'])) msg_url(L('dance_12'),Web_Path);
			$durl=$row['durl'];
            if($row['fid']>0){
                $rowf=$this->db->query("Select durl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
				if($rowf){
			         $durl=$rowf['durl'].$row['durl'];
				}
			}
			//�Զ���������·��
			$durl=annexlink($durl);
			if(substr($durl,0,7)!='http://'){  
			      $durl="http://".Web_Url.Web_Path.$durl;
			}

			//�ж��շ�
            if($row['vip']>0 || $row['level']>0 || $row['cion']>0 || User_YkDown==0){
                  $this->CsdjUser->User_Login();
				  $rowu=$this->CsdjDB->get_row_arr('user','vip,zutime,level,cion,zid',$_SESSION['cscms__id']);
				  if($rowu['zutime']<time()){
						$this->db->query("update ".CS_SqlPrefix."user set zid=1,zutime=0 where id=".$_SESSION['cscms__id']."");
                        $rowu['zid']=1;
				  }
			}

            //�жϻ�Ա������Ȩ��
			if($row['vip']>0 && $row['uid']!=$_SESSION['cscms__id'] && $rowu['vip']==0){
				  if($row['vip']>$rowu['zid']){
                       msg_url(L('dance_13'),'javascript:window.close();');
				  }
			}

            //�жϻ�Ա�ȼ�����Ȩ��
			if($row['level']>0 && $row['uid']!=$_SESSION['cscms__id']){
				  if($row['level']>$rowu['level']){
                       msg_url(L('dance_14'),'javascript:window.close();');
				  }
			}

            //�жϽ������
			$down=0;
			if($row['cion']>0 && $row['uid']!=$_SESSION['cscms__id']){

				  //�ж��Ƿ����ع�
				  $rowd=$this->db->query("SELECT id,addtime FROM ".CS_SqlPrefix."dance_down where did='".$id."' and uid='".$_SESSION['cscms__id']."'")->row_array();
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
                           msg_url(vsprintf(L('dance_15'),array($row['cion'])),'javascript:window.close();');
				      }else{
						  //�۱�
						  $edit['cion']=$rowu['cion']-$row['cion'];
						  $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$edit);
						  //д�����Ѽ�¼
						  $add2['title']=L('dance_16').'��'.$row['name'].'��';
						  $add2['uid']=$_SESSION['cscms__id'];
						  $add2['dir']='dance';
						  $add2['nums']=$row['cion'];
						  $add2['ip']=getip();
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
						              $add3['title']=vsprintf(L('dance_17'),array($row['name']));
						              $add3['uid']=$row['uid'];
						              $add3['dir']='dance';
						              $add3['nums']=$scion;
						              $add3['ip']=getip();
                                      $add3['addtime']=time();
					                  $this->CsdjDB->get_insert('income',$add3);
								 }
						  }
					  }
				  }
			      //�������ؼ�¼��ֻ��¼�۱Ҹ���
			      if($down==0){
                       $add['name']=$row['name'];
                       $add['cid']=$row['cid'];
                       $add['did']=$id;
					   $add['ip']=getip();
                       $add['uid']=$_SESSION['cscms__id'];
                       $add['cion']=$row['cion'];
                       $add['addtime']=time();
					   $this->CsdjDB->get_insert('dance_down',$add);
			      }else{
			           //�޸�����ʱ��
			           $this->db->query("update ".CS_SqlPrefix."dance_down set addtime=".time()." where id=".$rowd['id']."");
				  }
			}
			//ͬһ����24Сʱ��ֻ��¼һ�λ�Ա���ض�̬
            if(!empty($_SESSION['cscms__id'])){
			    $rowx=$this->db->query("SELECT id FROM ".CS_SqlPrefix."dt where link='".linkurl('play','id',$id,0,'dance')."' and uid=".$_SESSION['cscms__id']."")->row_array();
				if(!$rowx){
			        $dt['dir']='dance';
			        $dt['uid']=$_SESSION['cscms__id'];
			        $dt['did']=$id;
			        $dt['name']=$row['name'];
			        $dt['link']=linkurl('play','id',$id,0,'dance');
			        $dt['title']=L('dance_16');
			        $dt['addtime']=time();
			        $this->CsdjDB->get_insert('dt',$dt);
				}else{
			        //�޸Ķ�̬ʱ��
			        $this->db->query("update ".CS_SqlPrefix."dt set addtime=".time()." where id=".$rowx['id']."");
				}
			}

			//������������
			$this->db->query("update ".CS_SqlPrefix."dance set xhits=xhits+1 where id=".$id."");

		    //------------------��ʼ�����ļ�����--------------------------------------
			//�ж��Ƿ�֧��CURL
            if (! function_exists ( 'curl_init' )) { //��֧��CURL
                  header("Location: ".$durl);
            }else{

				  //������URLת������
				  $durl = mb_convert_encoding($durl, "utf-8", "gb2312");
				  $durl = rawurlencode($durl);
				  $durl = str_replace('%3A',':',$durl);
				  $durl = str_replace('%2F','/',$durl);

				  //�ж�302��ת
			      $a_array = get_headers($durl,true);
			      if(strpos($a_array[0],'302') !== FALSE){  //302��ת
                      header("Location: ".$durl);
					  exit;
			      }
			      //�ļ���С
			      $filesize = $a_array['Content-Length'];
			      //��׺
			      $file_ext = strtolower(trim(substr(strrchr($durl, '.'), 1)));
			      //����
			      $filename = $row['name'].'.'.$file_ext;
			      //��С
			      $fsize = sprintf("%u", $filesize);
			      //����
			      $file_path = $durl;
	              if(ob_get_length() !== false) @ob_end_clean();
	              header('Pragma: public');
	              header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	              header('Cache-Control: no-store, no-cache, must-revalidate');
	              header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	              header('Content-Transfer-Encoding: binary');
	              header('Content-Encoding: none');
	              header('Content-type: application/force-download');
	              header('Content-Disposition: attachment; filename="'.$filename.'"');
	              header('Content-length: '.$filesize);
	              $curl = curl_init();
	              curl_setopt($curl, CURLOPT_URL, $file_path);
	              curl_exec($curl);
	              curl_close($curl);
            }
	}

	//���ظ��
	public function lrc($id = 0)
	{
            @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
            @header("Cache-Control: no-cache, must-revalidate"); 
            @header("Pragma: no-cache");
		    $this->load->model('CsdjUser');
			$login='no';
            $id = (int)$id;   //ID
            //�ж�ID
            if($id==0) msg_url(L('dance_25'),Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance','name,lrc',$id);
		    if(!$row) msg_url(L('dance_25'),Web_Path);
		    if(empty($row['lrc'])) msg_url(L('dance_25'),Web_Path);
		    $data = $row['lrc'];
		    $name = $row['name'].'.lrc';
			$this->load->helper('download');
		    force_download($name, $data);
	}
}


