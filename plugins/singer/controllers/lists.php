<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-20
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lists extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('singer');
		    $this->load->model('CsdjTpl'); //װ����ͼģ��
	}

    //�����б�
	public function index($fid='id', $id = 0, $page = 0)
	{
            $id = intval($id);   //ID
            $page  = intval($page);   //ҳ��
            if($page==0) $page=1;
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('singer_list','*',$id);
		    if(!$row || $row['yid']>0){
                     msg_url('�����ˣ��÷��಻���ڣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['lists']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('lists',$fid,$id,$page,'singer');
				header("Location: ".$Htmllink);
				exit;
			}
			//��ȡ��ǰ�����¶�������ID
			$arr['cid']=getChild($id);
			$arr['fid']=($row['fid']==0)?$row['id']:$row['fid'];
			//װ��ģ�岢���
			$skins=empty($row['skins'])?'list.html':$row['skins'];
	        $this->CsdjTpl->plub_list($row,$id,$fid,$page,$arr,false,$skins,'lists','singer',$row['name'],$row['name']);
	}
}


