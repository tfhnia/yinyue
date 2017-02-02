<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-02
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Show extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('vod');
		    $this->load->model('CsdjTpl');
	}

    //��Ƶ����
	public function index($fid = 'id', $id = 0, $return = FALSE)
	{
            $id = (intval($fid)>0)?intval($fid):intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('vod','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url('�����ˣ������ݲ����ڻ���û����ˣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('show',$fid,$id,0,'vod');
				header("Location: ".$Htmllink);
				exit;
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
			$skins=getzd('vod_list','skins2',$row['cid']);
			if(empty($skins)) $skins='show.html';
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('vod',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[vod:pl]",get_pl('vod',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[vod:link]",LinkUrl('show','id',$row['id'],1,'vod'),$Mark_Text);
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
			//�����������ص�ַ
            $Mark_Text=Vod_Playlist($Mark_Text,'play',$id,$row['purl']);
            $Mark_Text=Vod_Playlist($Mark_Text,'down',$id,$row['durl']);
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}


