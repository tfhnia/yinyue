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
	        if(config('Web_Mode')==3 && $html['play']['check']==1 && !defined('MOBILE')){
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
			$arr['did']=$row['id'];
			$arr['singerid']=$row['singerid'];
			$arr['tags']=$rows['tags'];
			//װ��ģ�岢���
			$skins=empty($row['skins'])?'play.html':$row['skins'];
	        $Mark_Text=$this->CsdjTpl->plub_show('dance',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
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
			//��ȡ������
            preg_match_all('/[dance:slink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."dance where yid=0 and hid=0 and id<".$id." order by id desc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[dance:slink]",LinkUrl('play','id',$rowd->id,1,'dance'),$Mark_Text);
			             $Mark_Text=str_replace("[dance:sname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[dance:sid]",$rowd->id,$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[dance:slink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[dance:sname]",L('dance_20'),$Mark_Text);
			             $Mark_Text=str_replace("[dance:sid]",0,$Mark_Text);
				   }
			}
			unset($arr);
            preg_match_all('/[dance:xlink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."dance where yid=0 and hid=0 and id>".$id." order by id asc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[dance:xlink]",LinkUrl('play','id',$rowd->id,1,'dance'),$Mark_Text);
			             $Mark_Text=str_replace("[dance:xname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[dance:xid]",$rowd->id,$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[dance:xlink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[dance:xname]",L('dance_20'),$Mark_Text);
			             $Mark_Text=str_replace("[dance:xid]",0,$Mark_Text);
				   }
			}
			unset($arr);
			//��ǩ�ӳ�������
			$Mark_Text=str_replace("[dance:tags]",SearchLink($rows['tags']),$Mark_Text);
			//��������������ַ
            preg_match_all('/[dance:qurl]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
				 $purl=$row['purl'];
                 if($row['fid']>0){
                      $rowf=$this->db->query("Select purl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
					  if($rowf){
				           $purl=$rowf['purl'].$row['purl'];
					  }
				 }
				 $purl=annexlink($purl);
                 $Mark_Text=str_replace("[dance:qurl]",$purl,$Mark_Text);
			}
			unset($arr);
			//cmp��Ƶ������
			$player="<script type='text/javascript'>
			var mp3_w='".CS_Play_w."';
			var mp3_h='".CS_Play_h."';
			var mp3_i='".$id."';
			var mp3_p='".hitslink('play','dance')."';
			var mp3_t='".Web_Path."';
			mp3_play();
			</script>";
            $Mark_Text=str_replace("[dance:player]",$player,$Mark_Text);
			//jp��Ƶ������
			$jplayer="<script type='text/javascript'>
			var mp3_i='".$id."';
			var mp3_p='".hitslink('play','dance')."';
			var mp3_n='".str_replace("'","",$row['name'])."';
			var mp3_x='".LinkUrl('down','id',$row['id'],1,'dance')."';
			var mp3_l='".LinkUrl('down','lrc',$row['id'],1,'dance')."';
			mp3_jplayer();
			</script>";
            $Mark_Text=str_replace("[dance:jplayer]",$jplayer,$Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'dance'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

	//��ȡ���ŵ�ַ
	public function url($op='cmp', $id = 0)
	{
            @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
            @header("Cache-Control: no-cache, must-revalidate"); 
            @header("Pragma: no-cache");
            $id = intval($id);   //ID
            //�ж�ID
            if($id==0) exit();
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance','name,purl,fid,lrc',$id);
		    if(!$row) exit();
			$purl=$row['purl'];
            if($row['fid']>0){
                $rowf=$this->db->query("Select purl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
				if($rowf){
				     $purl=$rowf['purl'].$row['purl'];
				}
			}
			$purl=annexlink($purl);
			if($op=='cmp'){
                 echo '<list><m type="" src="'.$purl.'" label="'.$row['name'].'" opened="1"></m></list>';
			}elseif($op=='wap'){
				 header("location:".$purl);
			}elseif($op=='lrc'){
				 echo str_replace("'","",$row['lrc']);
			}else{
                 echo 'var mp3_u="'.$purl.'";';
                 echo 'var mp3_l="'.str_replace("'","",$row['lrc']).'";';
			}
	}
}


