<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Real extends CI_Controller {

	function __construct(){
		    parent::__construct();
            $this->load->library('user_agent');
            if(!$this->agent->is_referral()) show_error('Äú·ÃÎÊµÄÍøÒ³´íÎó~!',500,Web_Name.'ÌáÐÑÄú');
	        //¹Ø±ÕÊý¾Ý¿â»º´æ
            $this->db->cache_off();
	}

    //Ö÷Ò³²¥·ÅÆ÷¸èÇú
	public function index()
	{
	        $sql_string = "SELECT id,name,fid,purl FROM ".CS_SqlPrefix."dance where yid=0 and hid=0 and reco=5 order by addtime desc limit 100";
	        $query = $this->db->query($sql_string); 
            foreach ($query->result_array() as $row) { 
			    $purl=$row['purl'];
                if($row['fid']>0){
                    $rowf=$this->db->query("Select purl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
				    if($rowf){
				         $purl=$rowf['purl'].$row['purl'];
				    }
			    }
			    $purl=annexlink($purl);
                echo 'DATA("'.$row['name'].'", "'.$purl.'","'.$row['id'].'");';
			}
	}

    //ºÚÉ«ìÅ¿áÄ£°åÖ÷Ò³²¥·ÅÆ÷ÁÐ±í
	public function hei()
	{
                $lists='';
	            $sql_string = "SELECT id,name,uid,fid,purl FROM ".CS_SqlPrefix."dance where yid=0 and reco=5 order by addtime desc limit 100";
	             $query = $this->db->query($sql_string); 
                 $i=0;
                 foreach ($query->result() as $row) {
			        $purl=$row->purl;
                    if($row->fid>0){
                        $rowf=$this->db->query("Select purl from ".CS_SqlPrefix."dance_server where id=".$row->fid."")->row();
				        if($rowf){
				             $purl=$rowf->purl.$purl;
				        }
			        }
			        $purl=annexlink($purl);
					$us=$this->db->query("SELECT name,nichen,logo FROM ".CS_SqlPrefix."user where id=".$row->uid."")->row();
					if(empty($us->nichen)) $us->nichen=$us->name;	
                    $list['uid']         = get_bm($us->name,'gbk','utf-8');
                    $list['ulink']       = userlink('index',$row->uid,$us->name);
                    $list['face']        = piclink('logo',$us->logo);
                    $list['nickname']    = get_bm($us->nichen,'gbk','utf-8');
                    $list['id']          = $row->id;
                    $list['name']        = get_bm($row->name,'gbk','utf-8');
                    $list['purl']        = get_bm($purl,'gbk','utf-8');
                    $list['downurl']     = linkurl('down','id',$row->id,1,'dance');
                    $list['i']           = $i;
					$lists[]=$list;

                    $i++;
                 }
                 echo 'var Music='.json_encode($lists);
	}
}

