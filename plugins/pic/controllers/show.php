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
			$this->load->helper('pic');
		    $this->load->model('CsdjTpl');
	}

    //����
	public function index($fid = 'id', $id = 0)
	{
            $id = (intval($fid)>0)?intval($fid):intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('pic_type','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url('�����ˣ������ݲ����ڻ���û����ˣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('show',$fid,$id,0,'pic');
				header("Location: ".$Htmllink);
				exit;
			}
			//�ݻٲ�����Ҫ���������ֶ�����
			$rows=$row; //�ȱ������鱣������ʹ��
			unset($row['tags']);
			//��ȡ��ǰ�����¶�������ID
			$arr['cid']=getChild($row['cid']);
			$arr['uid']=$row['uid'];
			$arr['tags']=$rows['tags'];
			$arr['sid']=$row['id'];
			//Ĭ��ģ��
			$skins=empty($row['skins'])?'show.html':$row['skins'];
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('pic',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[pic:pl]",get_pl('pic',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[pic:link]",LinkUrl('show','id',$row['id'],1,'pic'),$Mark_Text);
			$Mark_Text=str_replace("[pic:classlink]",LinkUrl('lists','id',$row['cid'],1,'pic'),$Mark_Text);
			$Mark_Text=str_replace("[pic:classname]",$this->CsdjDB->getzd('pic_list','name',$row['cid']),$Mark_Text);
			//��ȡ������
            preg_match_all('/[pic:slink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,pic,name from ".CS_SqlPrefix."pic_type where yid=0 and hid=0 and id<".$id." order by id desc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[pic:slink]",LinkUrl('show','id',$rowd->id,1,'pic'),$Mark_Text);
			             $Mark_Text=str_replace("[pic:sname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[pic:sid]",$rowd->id,$Mark_Text);
			             $Mark_Text=str_replace("[pic:spic]",piclink('pic',$rowd->pic),$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[pic:slink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[pic:sname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[pic:sid]",0,$Mark_Text);
			             $Mark_Text=str_replace("[pic:spic]",piclink('pic',''),$Mark_Text);
				   }
			}
			unset($arr);
            preg_match_all('/[pic:xlink]/',$Mark_Text,$arr);
		    if(!empty($arr[0]) && !empty($arr[0][0])){
                   $rowd=$this->db->query("Select id,cid,pic,name from ".CS_SqlPrefix."pic_type where yid=0 and hid=0 and id>".$id." order by id asc limit 1")->row();
				   if($rowd){
			             $Mark_Text=str_replace("[pic:xlink]",LinkUrl('show','id',$rowd->id,1,'pic'),$Mark_Text);
			             $Mark_Text=str_replace("[pic:xname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[pic:xid]",$rowd->id,$Mark_Text);
			             $Mark_Text=str_replace("[pic:xpic]",piclink('pic',$rowd->pic),$Mark_Text);
				   }else{
			             $Mark_Text=str_replace("[pic:xlink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[pic:xname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[pic:xid]",0,$Mark_Text);
			             $Mark_Text=str_replace("[pic:xpic]",piclink('pic',''),$Mark_Text);
				   }
			}
			unset($arr);
			//��ǩ�ӳ�������
			$Mark_Text=str_replace("[pic:tags]",SearchLink($rows['tags']),$Mark_Text);
			//��ȡ��ǰ�������
			$pcount=$this->db->query("Select id from ".CS_SqlPrefix."pic where sid=".$id." and hid=0 and yid=0")->num_rows();
			$Mark_Text=str_replace("[pic:count]",$pcount,$Mark_Text);
			//��һ��ͼƬ
			$rowp=$this->db->query("Select pic,content from ".CS_SqlPrefix."pic where sid=".$id." and hid=0 and yid=0 order by id desc limit 1")->row();
            $pics=($rowp)?$rowp->pic:'';
            $content=($rowp)?$rowp->content:'';
			$Mark_Text=str_replace("[pic:url]",piclink('pic',$pics),$Mark_Text);
			$Mark_Text=str_replace("[pic:content]",$content,$Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'pic'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}


