<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-30
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pl extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    //�ر����ݿ⻺��
            $this->db->cache_off();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	}

    //�����б�
	public function index($dir='blog',$did=0,$cid=0,$page=1)
	{
		    $callback = $this->input->get('callback',true);
            $Mark_Text=$this->CsdjTpl->pl($dir,intval($did),intval($cid),intval($page));
			$Mark_Text=get_bm($Mark_Text,'gbk','utf-8');
			echo $callback."({str:".json_encode($Mark_Text)."})";
	}

    //��������
	public function add()
	{
		    $callback = $this->input->get('callback',true);
			$token=$this->input->get_post('token', TRUE);
			$add['dir']=$this->input->get_post('dir', TRUE);
			$add['content']=$this->input->get_post('neir', TRUE);
			$add['content']=facehtml(filter(get_bm($add['content'])));
			//ת���ظ�
			$hf=0;
            preg_match_all ('/�ظ�@(.*)@:/i',$add['content'],$bs);
            if(!empty($bs[0][0]) && !empty($bs[1][0])){
				  $uid=getzd('user','id',$bs[1][0],'name');
				  $nichen=getzd('user','nichen',$bs[1][0],'name');
				  $ulink=userlink('index',$uid,$bs[1][0]);
				  if(empty($nichen)) $nichen=$bs[1][0];
				  $b='�ظ�<a target="_blank" href="'.$ulink.'">@'.$nichen.'@</a>:';
                  $add['content']=str_replace($bs[0][0],$b,$add['content']);
				  $hf=1;
			}
            unset($bs);
			$add['did']=intval($this->input->get_post('did'));
			if(Pl_Modes==3){
                 $error='10000';
			}elseif($add['did']==0){
                 $error='10001';
			}elseif(!get_token('pl_token',1,$token)){
                 $error='10002';
			}elseif(isset($_SESSION['pladdtime']) && time()<$_SESSION['pladdtime']+60){
                 $error='10007';
			}elseif(empty($add['content'])){
                 $error='10003';
			}elseif(Pl_Youke==0 && empty($_SESSION['cscms__id'])){//�ر��ο�����
                 $error='10004';
			}else{

                $add['uid']=isset($_SESSION['cscms__id'])?intval($_SESSION['cscms__id']):0;
                $add['user']=isset($_SESSION['cscms__name'])?$_SESSION['cscms__name']:'�ο�';
			    $add['cid']=intval($this->input->get_post('cid'));
			    $add['fid']=intval($this->input->get_post('fid'));
			    $add['ip']=getip();
			    $add['addtime']=time();

                $ids=$this->CsdjDB->get_insert('pl',$add);
			    if(intval($ids)==0){
                    $error='10005'; //ʧ��
				}else{
                    //�ݻ�token
			        get_token('pl_token',2);
                    $error='10006';
                    $_SESSION['pladdtime']=time();

					//����֪ͨ
					if($add['dir']!='singer'){
						$dirname=getzd('plugins','name',$add['dir'],'dir');
					    if($add['dir']=='dance'){
                             $link=linkurl('play','id',$add['did'],1,'dance');
						}elseif($add['dir']!='blog'){
                             $link=linkurl('show','id',$add['did'],1,$add['dir']);
						}
						if($add['dir']=='pic'){
							 $dataname=getzd('pic_type','name',$add['did']);
							 $pluid=getzd('pic_type','uid',$add['did']);
						}elseif($add['dir']=='blog'){
							 $pluid=getzd('blog','uid',$add['did']);
							 $dataname=getzd('blog','neir',$add['did']);
							 $dirname='˵˵';
							 $username=getzd('user','name',$pluid);
                             $link=userlink('blog',$pluid,$username,$add['did']);
						}else{
							 $dataname=getzd($add['dir'],'name',$add['did']);
							 $pluid=getzd($add['dir'],'uid',$add['did']);
						}
						if($hf==0){
				             $pltitle=vsprintf(L('pl_01'),array($dirname));
				             $plneir=vsprintf(L('pl_03'),array($_SESSION['cscms__name'],$dirname,$link,$dataname));
						}else{
							 $pltitle=L('pl_02');
				             $plneir=vsprintf(L('pl_04'),array($_SESSION['cscms__name'],$link,$dataname));
							 $pluid=$uid;
					    }
						if($pluid>0){
				            $addm['uida']=$pluid;
				            $addm['uidb']=$_SESSION['cscms__id'];
				            $addm['name']=$pltitle;
				            $addm['neir']=$plneir;
				            $addm['addtime']=time();
            	            $this->CsdjDB->get_insert('msg',$addm);
						}
					}
				}
			}
			echo $callback."({error:".$error."})";
	}

    //ɾ������
	public function del()
	{
		    $callback = $this->input->get('callback',true);
			$id = intval($this->input->get_post('id'));
			$token=$this->input->get_post('token', TRUE);
			if($id==0){
                    $error='10001';
			}elseif(!get_token('pl_token',1,$token)){
                 $error='10002';
			}elseif(empty($_SESSION['cscms__id'])){ //δ��½
                    $error='10000';
			}else{
		            $row=$this->CsdjDB->get_row('pl','uid',$id);
		            if(!$row){
                         $error='10001';
					}elseif($row->uid!=$_SESSION['cscms__id']){
                         $error='10003';
					}else{
                         //Ȩ��ͨ��ɾ��
						 $this->CsdjDB->get_del('pl',$id,'fid');
						 $this->CsdjDB->get_del('pl',$id);
                         $error='10004';
						 get_token('pl_token',2);
					}
			}
			echo $callback."({error:".$error."})";
	}
}
