<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-01-17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hei extends Cscms_Controller {

	function __construct(){
		  parent::__construct();
          $this->load->library('user_agent');
          if(!$this->agent->is_referral()) show_error(L('dance_01'),404,Web_Name.L('dance_02'));
	      //关闭数据库缓存
          $this->db->cache_off();
		  $this->load->model('CsdjUser');
	}

    //操作
	public function index()
	{
		   $callback = $this->input->get('callback',true);
		   $ac = $this->input->get('ac',true);
           $cid = (int)$this->input->get('cid',true);
           $id = (int)$this->input->get('id',true);
		   $data['code']=0;
		   $sql='';
		   $str='';
		   if($ac=='hits'){
                $sql = "SELECT id,name FROM ".CS_SqlPrefix."dance where yid=0 and hid=0 and cid=".$cid." order by hits desc limit 30";
		   }elseif($ac=='reco'){
                $sql = "SELECT id,name FROM ".CS_SqlPrefix."dance where yid=0 and hid=0 and cid=".$cid." and reco>0 order by zhits desc limit 30";
		   }elseif($ac=='ri'){
                $sql = "SELECT id,name FROM ".CS_SqlPrefix."dance where yid=0 and hid=0 and cid=".$cid." order by rhits desc limit 30";
		   }elseif($ac=='news'){
                $sql = "SELECT id,name FROM ".CS_SqlPrefix."dance where yid=0 and hid=0 and cid=".$cid." order by addtime desc limit 30";
		   }else{
			   if(!$this->CsdjUser->User_Login(1)){
                  $data['code']=1;
			   }else{
                  $sql = "SELECT id,name FROM ".CS_SqlPrefix."dance_fav where uid=".$_SESSION['cscms__id']." order by addtime desc limit 30";
			   }
           }
		   if($sql!=''){
               $result = $this->db->query($sql)->result_array();
			   foreach ($result as $row) {
				   $clas = $row['id']==$id ? ' class="playing"' : '';
                   $str.='<li'.$clas.'><i><input name="check" type="checkbox" value="'.$row['id'].'"></i><p><a href="'.linkurl('play','id',$row['id'],1,'dance').'" title="'.$row['name'].'">'.$row['name'].'</a></p></li>';
			   }
		   }
		   $data['str']=get_bm($str,'gbk','utf-8');
           echo $callback."(".json_encode($data).")";
	}

    //song
	public function song()
	{
            $ids=$this->input->get_post('id',TRUE);
            $Arr=explode(',',$ids);
            $lists=array();
            for($j=0;$j<count($Arr);$j++){
                    $id=intval($Arr[$j]);
					if($id>0){
						   $row=$this->db->query("select id,name from ".CS_SqlPrefix."dance where id=".$id."")->row();
						   if($row){
                                $lists[$j]['id']     = $id;
                                $lists[$j]['name']       = get_bm($row->name,'gbk','utf-8');
                                $lists[$j]['link']   = LinkUrl('play','id',$row->id,1,'dance');
						   }
					}
			}
			echo 'var music='.json_encode($lists).';'; 
	}
}
