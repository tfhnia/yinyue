<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-16
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Music extends Cscms_Controller {
	function __construct(){
		    parent::__construct();
			$this->load->helper('singer');
		    $this->load->model('CsdjTpl');
	}

    //����
	public function index($fid='id', $id = 0, $page=1)
	{
            $id = intval($id);   //ID
            $page = intval($page);   //ID
			if(preg_match("/^\d*$/",$fid)){
                $id = intval($fid);
                $page = intval($id);
				$fid = 'id';
			}
			if($page==0) $page=1;
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('singer','*',$id);
		    if(!$row || $row['yid']>0 || $row['hid']>0){
                     msg_url('�����ˣ��ø��ֲ����ڣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('music','',$id,0,'singer');
				header("Location: ".$Htmllink);
				exit;
			}
			$arr['cid']=getChild($row['cid']);
			$arr['tags']=$row['tags'];
			$arr['singerid']=$id;
			//�ݻٲ�����Ҫ���������ֶ�����
			$rows=$row; //�ȱ������鱣������ʹ��
			unset($row['tags']);
			//װ��ģ�岢���
			$Mark_Text=$this->CsdjTpl->plub_list($row, $id, $fid, $page, $arr, TRUE, 'music.html', 'music', 'singer', $row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[singer:pl]",get_pl('singer',$id),$Mark_Text);
			//�����ַ������
			$Mark_Text=str_replace("[singer:link]",LinkUrl('show','id',$row['id'],1,'singer'),$Mark_Text);
			$Mark_Text=str_replace("[singer:classlink]",LinkUrl('lists','id',$row['cid'],1,'singer'),$Mark_Text);
			$Mark_Text=str_replace("[singer:classname]",$this->CsdjDB->getzd('singer_list','name',$row['cid']),$Mark_Text);
			//��ǩ�ӳ�������
			$Mark_Text=str_replace("[singer:tags]",SearchLink($rows['tags']),$Mark_Text);
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}

