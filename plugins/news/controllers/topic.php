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
			$this->load->helper('news');
		    $this->load->model('CsdjTpl'); //װ����ͼģ��
	}

    //ר���б�
	public function lists($page = 0)
	{
            $page  = intval($page);   //ҳ��
            if($page==0) $page=1;

            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['topic/lists']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('topic','lists',0,$page,'news');
				header("Location: ".$Htmllink);
				exit;
			}
			$row=array();
			//װ��ģ�岢���
	        $this->CsdjTpl->plub_list($row,1,'lists',$page,'',FALSE,'topic.html','topic','','����ר��');
	}

    //ר������
	public function show($id=0)
	{
            $id    = intval($id);   //ID
            //�ж�ID
            if($id==0) msg_url('�����ˣ�ID����Ϊ�գ�',Web_Path);
            //��ȡ����
		    $row=$this->CsdjDB->get_row_arr('news_topic','*',$id);
		    if(!$row || $row['yid']>0){
                     msg_url('�����ˣ���ר�ⲻ���ڣ�',Web_Path);
		    }
            //�ж�����ģʽ,��������ת����̬ҳ��
			$html=config('Html_Uri');
	        if(config('Web_Mode')==3 && $html['topic/show']['check']==1 && !defined('MOBILE')){
                //��ȡ��̬·��
				$Htmllink=LinkUrl('topic','show',$id,1,'news');
				header("Location: ".$Htmllink);
				exit;
			}
			//װ��ģ�岢���
	        $Mark_Text=$this->CsdjTpl->plub_show('topic',$row,$id,true,'topic-show.html',$row['name'],$row['name']);
			//����
			$Mark_Text=str_replace("[topic:pl]",get_pl('news',$id,1),$Mark_Text);
			//��������
			$Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id.'/topic','news'));
            echo $Mark_Text;
			$this->cache->end(); //����ǰ�治��ֱ�����������������Ҫ����д����
	}
}


