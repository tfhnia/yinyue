<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-01-17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
          $this->load->library('user_agent');
          if(!$this->agent->is_referral()) show_error('您访问的页面不存在~!',404,Web_Name.'提醒您');
	      //关闭数据库缓存
          $this->db->cache_off();
		  $this->load->model('CsdjUser');
	}

    //收藏
	public function vodfav()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //方式
		   if($id==0){
                $error='ID为空';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='您还没有登录';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','cid,name,shits',$id);
		        if(!$row){
			        $error='数据不存在';
		        }else{
					//判断是否收藏
		            $rows=$this->db->query("SELECT id FROM ".CS_SqlPrefix."vod_fav where did=".$id." and uid=".$_SESSION['cscms__id']." and sid=0")->row();
		            if($rows){
			            $error='您已经收藏了该视频';
					}else{
						$add['did']=$id;
						$add['cid']=$row->cid;
						$add['uid']=$_SESSION['cscms__id'];
						$add['sid']=0;
						$add['name']=$row->name;
						$add['addtime']=time();
						$this->CsdjDB->get_insert('vod_fav',$add);
                        //增加收藏人气
	                    $updata['shits']=$row->shits+1;
                        $this->CsdjDB->get_update('vod',$id,$updata);

					    //增加下载动态
					    $dt['dir']='vod';
					    $dt['uid']=$_SESSION['cscms__id'];
					    $dt['did']=$id;
					    $dt['name']=$row->name;
					    $dt['link']=linkurl('show','id',$id,0,'vod');
					    $dt['title']='收藏了视频';
					    $dt['addtime']=time();
					    $this->CsdjDB->get_insert('dt',$dt);

						$error='ok';
					}
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}

    //顶
	public function vodding()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
		   $ding = $this->cookie->get_cookie("vodding_id_".$id);
		   if($id==0){
                $error='ID为空';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='您还没有登录';
		   }elseif(!empty($ding)){
                $error='您今天已经赞过了';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','dhits',$id);
		        if(!$row){
			        $error='数据不存在';
		        }else{
                        //记住cookie
	                    $this->cookie->set_cookie("vodding_id_".$id,'ok',time()+86400);
                        //增加顶人气
	                    $updata['dhits']=$row->dhits+1;
                        $this->CsdjDB->get_update('vod',$id,$updata);
						$error='ok';
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}

    //获取评分
	public function vodpfen()
	{
		   $this->load->helper('vod');
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
		   $row=$this->CsdjDB->get_row('vod','phits,pfen',$id);
		   if(!$row){
			    $fen=0;
		   }else{
                $fen=getpf($row->pfen,$row->phits);
           }
           echo $callback."({fen:".json_encode($fen)."})";
	}

    //视频评分
	public function vodpfenadd()
	{
		   $callback = $this->input->get('callback',true);
           $id = intval($this->uri->segment(3));   //ID
           $fen = intval($this->uri->segment(4));   //分
		   $pfen = $this->cookie->get_cookie("vodpfen_id_".$id);
		   if($id==0 || $fen==0){
                $error='参数错误';
		   }elseif(!$this->CsdjUser->User_Login(1)){
                $error='您还没有登录';
		   }elseif(!empty($pfen)){
                $error='您已经评过分了';
		   }else{
		        $row=$this->CsdjDB->get_row('vod','phits,pfen',$id);
		        if(!$row){
			        $error='数据不存在';
		        }else{
                        //记住cookie
	                    $this->cookie->set_cookie("vodpfen_id_".$id,'ok',time()+86400*30);
                        //增加评分、人气
	                    $updata['phits']=$row->phits+1;
	                    $updata['pfen']=$row->pfen+$fen;
                        $this->CsdjDB->get_update('vod',$id,$updata);
						$error='ok';
				}
           }
		   $error=get_bm($error,'gbk','utf-8');
           echo $callback."({msg:".json_encode($error)."})";
	}
}
