<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-03
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topic extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl'); //װ����ͼģ��
	}

    //ר����ҳ
	public function index($fid='id',$page = 0)
	{
            $page  = intval($page);   //ҳ��
            if($page==0) $page=1;

            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['topic/lists']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('topic/lists',$fid,0,$page,'dance');
				header("Location: ".$Htmllink);
				exit;
			}
			$row=array();
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_list($row,0,$fid,$page,'',true,'topic.html','topic','','����ר��','����ר��');
			$Mark_Text=str_replace("[topic:link]",LinkUrl('topic','id',0,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:hlink]",LinkUrl('topic','hits',0,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:rlink]",LinkUrl('topic','ri',0,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:zlink]",LinkUrl('topic','zhou',0,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:ylink]",LinkUrl('topic','yue',0,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:slink]",LinkUrl('topic','fav',0,$page,'dance'),$Mark_Text);
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

    //ר���б�
	public function lists($fid='id', $cid=0, $page=1)
	{
            $page  = intval($page);   //ҳ��
            if($page==0) $page=1;
            //�ж�ID
            if($cid==0) msg_url(L('dance_09'),Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance_list','*',$cid);
		    if(!$row){
                     msg_url(L('dance_18'),Web_Path);
		    }

            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['topic/lists']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('topic/lists',$fid,$cid,$page,'dance');
				header("Location: ".$Htmllink);
				exit;
			}

			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_list($row,$cid,$fid,$page,$cid,true,'topic.html','topic/lists','',L('dance_22'),L('dance_22'));
			$Mark_Text=str_replace("[topic:cids]",$cid,$Mark_Text);
			$Mark_Text=str_replace("[topic:link]",LinkUrl('topic/lists','id',$cid,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:hlink]",LinkUrl('topic/lists','hits',$cid,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:rlink]",LinkUrl('topic/lists','ri',$cid,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:zlink]",LinkUrl('topic/lists','zhou',$cid,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:ylink]",LinkUrl('topic/lists','yue',$cid,$page,'dance'),$Mark_Text);
			$Mark_Text=str_replace("[topic:slink]",LinkUrl('topic/lists','fav',$cid,$page,'dance'),$Mark_Text);
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}

    //ר������
	public function show($fid='id',$id=0)
	{
            $id    = (intval($fid)>0)?intval($fid):intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url(L('dance_09'),Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('dance_topic','*',$id);
		    if(!$row || $row['yid']>0){
                     msg_url(L('dance_23'),Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['topic/show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('topic/show',$id,1,'dance');
				header("Location: ".$Htmllink);
				exit;
			}
			//װ��ģ�岢���
			$ids['tid']=$id;
			$ids['singerid']=$row['singerid'];
			$ids['uid']=$row['uid'];
	        $Mark_Text=$this->CsdjTpl->plub_show('topic',$row,$ids,true,'topic-show.html',$row['name'].' - '.L('dance_22'),$row['name']);
			//����
			$Mark_Text=str_replace("[topic:pl]",get_pl('dance',$id,1),$Mark_Text);
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id.'/topic','dance'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}


