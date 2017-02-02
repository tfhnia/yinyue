<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-21
 */
class Html extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('vod');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
		    $this->load->model('CsdjTpl');
            //�ж�����ģʽ
	        if(config('Web_Mode')!=3){
                 admin_msg('������ģʽ�Ǿ�̬������Ҫ���ɾ�̬�ļ�~!','javascript:history.back();','no');
			}
			$this->huri=config('Html_Uri');
	}

    //��ҳ����
	public function index()
	{
            if($this->huri['index']['check']==0){
				admin_msg('�����ҳδ��������~!','javascript:history.back();','no');
			}
            //��ȡ��̬·��
			$uri=config('Html_Uri');
			$index_url=adminhtml($uri['index']['url']);
			$this->load->get_templates('vod',2);
		    $Mark_Text=$this->CsdjTpl->plub_index('vod','index.html',true);
			write_file(FCPATH.$index_url,$Mark_Text);
            admin_msg('�����ҳ�������,<a target="_blank" href="'.Web_Path.$index_url.'"><font color="red">���</font></a>','###');
	}

    //ר��ҳ
	public function topic()
	{
            $this->load->view('html_topic.html');
	}

    //ר���б�ҳ���ɲ���
	public function topic_save()
	{
            if($this->huri['topic/lists']['check']==0){
				admin_msg('ר���б�δ��������~!','javascript:history.back();','no');
			}
            $start= intval($this->input->get('start')); //��ǰҳ���ɱ��
            $pagesize= intval($this->input->get('pagesize')); //ÿҳ������
            $pagejs= intval($this->input->get('pagejs')); //��ҳ��
            $datacount= intval($this->input->get('datacount')); //��������
            $page= intval($this->input->get('page')); //��ǰҳ
			if($start==0) $start=1;

            //����URI
			$uri='?pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('vod',2);

			//��ȡ��ҳ��Ϣ
            if($datacount==0){
				 $template=$this->load->view('topic.html','',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."vod_topic where yid=0";
                 $rows=$this->db->query($sqlstr)->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/$pagesize);
			}
            if($datacount==0) $pagejs=1;


            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ����<font color=red> ר���б� </font>��,��<font color=red>'.ceil($pagejs/Html_PageNum).'</font>�����ɣ���ǰ��<font color=red>'.ceil($start/Html_PageNum).'</font>��</b><br/>';

            $n=1;
            $pagego=1;
            if($page>0 && $page<$pagejs){
    	        $pagejs=$page;
            }

            for($i=$start;$i<=$pagejs;$i++){
                  
                  //��ȡ��̬·��
				  $Htmllinks=$Htmllink=LinkUrl('topic','lists',0,$i,'vod');
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'vod');
				  $Mark_Text=$this->CsdjTpl->plub_list(array(),1,'lists',$page,'',true,'topic.html','topic/lists','','��Ƶר��');
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "<a target='_blank' href='".$Htmllinks."'>&nbsp;&nbsp;��".$i."ҳ&nbsp;&nbsp;".$Htmllinks."<font color=green>�������~!</font></a><br/>";
                  $n++;
                  ob_flush();flush();

                  if(Html_PageNum==$n){
			           $pagego=2;
        	           break;
                  }
			}

   		    if($pagego==2){ //����ϵͳ����ÿҳ�������ֶ�ҳ����
				  $url=site_url('vod/admin/html/topic_save').$uri.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>��ͣ".Html_StopTime."��������һҳ&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}
            $url=site_url('vod/admin/html/topic');
			$str="&nbsp;&nbsp;<b><font color=#0000ff>����ר���б�ȫ���������!</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('vod/admin/html/topic')."'>������� �����û����ת����������...</a></b>";
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //ר������ҳ���ɲ���
	public function topicshow_save()
	{
            if($this->huri['topic/show']['check']==0){
				admin_msg('ר������δ��������~!','javascript:history.back();','no');
			}
            $tid = $this->input->get_post('tid',true); //��Ҫ���ɵ�ר��ID
            $ksid= intval($this->input->get_post('ksid')); //��ʼID
            $jsid= intval($this->input->get_post('jsid')); //����ID
            $kstime= $this->input->get_post('kstime',true); //��ʼ����
            $jstime= $this->input->get_post('jstime',true); //��������
            $pagesize= intval($this->input->get('pagesize')); //ÿҳ������
            $pagejs= intval($this->input->get('pagejs')); //��ҳ��
            $datacount= intval($this->input->get('datacount')); //��������
            $page= intval($this->input->get('page')); //��ǰҳ
			if($page==0) $page=1;
			$str='';

            //������ת�����ַ�
			if(is_array($tid)){
			     $tid=implode(',', $tid);
			}

            if($ksid>0 && $jsid>0){
                 $str.=' and id>'.($ksid-1).' and id<'.($jsid+1).'';
			}
            if(!empty($kstime) && !empty($jstime)){
				 $ktime=strtotime($kstime)-86400;
				 $jtime=strtotime($jstime)+86400;
                 $str.=' and addtime>'.$ktime.' and addtime<'.$jtime.'';
			}
            if(!empty($tid)){
                 $str.=' and id in ('.$tid.')';
			}

            if($datacount==0){
				 $sqlstr="select count(*) from ".CS_SqlPrefix."vod_topic where yid=0 ".$str;
                 $rows=$this->db->query($sqlstr)->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/Html_PageNum);
			}
            if($datacount==0) $pagejs=1;

            $pagesize=Html_PageNum;
            if($datacount<$pagesize){
    	        $pagesize=$datacount;
            }

            //ȫ���������
	        if($datacount==0 || $page>$pagejs){
                   admin_msg('����ר������ҳȫ���������~!',site_url('vod/admin/html/topic'));
			}

            //����URI
			$uri='?tid='.$tid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('vod',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ����ר������,��<font color=red>'.$pagejs.'</font>�����ɣ���ǰ��<font color=red>'.$page.'</font>��</b><br/>';

            $sql_string="select * from ".CS_SqlPrefix."vod_topic where yid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��̬����
			      unset($row['hits']);
			      unset($row['yhits']);
			      unset($row['zhits']);
			      unset($row['rhits']);
			      unset($row['shits']);
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('topic','show',$id,1,'vod');
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'vod');
				  $ids['tid']=$id;

				  //װ��ģ�岢���
	        	  $Mark_Text=$this->CsdjTpl->plub_show('topic',$row,$ids,true,'topic-show.html',$row['name'],$row['name']);
				  //����
				  $Mark_Text=str_replace("[topic:pl]",get_pl('vod',$id,1),$Mark_Text);
				  //������̬������ǩ
				  $Mark_Text=str_replace("[topic:hits]","<script src='".hitslink('hits/dt/hits/'.$id.'/topic','vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id.'/topic','vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id.'/topic','vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id.'/topic','vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:shits]","<script src='".hitslink('hits/dt/shits/'.$id.'/topic','vod')."'></script>",$Mark_Text);
				  //��������
			      $Mark_Text=hits_js($Mark_Text,cscmslink('vod'));

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>������Ƶר��:<font color=red>".$row['name']."</font>�ɹ�:<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}

    	    $url=site_url('vod/admin/html/topicshow_save').$uri.'&page='.($page+1);
			$str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //�б�ҳ
	public function type()
	{
            $this->load->view('html_type.html');
	}

    //�б�ҳ���ɲ���
	public function type_save()
	{
            if($this->huri['lists']['check']==0){
				admin_msg('��Ƶ����δ��������~!','javascript:history.back();','no');
			}
            $ac  = $this->input->get_post('ac',true); //��ʽ
            $cid = $this->input->get_post('cid',true); //��Ҫ���ɵķ���ID
            $fid = $this->input->get_post('fid',true); //��Ҫ���ɵ�����ʽ
            $nums= intval($this->input->get('nums')); //�������������
            $numx= intval($this->input->get('numx')); //����ʽ���������
            $start= intval($this->input->get('start')); //��ǰҳ���ɱ��
            $pagesize= intval($this->input->get('pagesize')); //ÿҳ������
            $pagejs= intval($this->input->get('pagejs')); //��ҳ��
            $datacount= intval($this->input->get('datacount')); //��������
            $page= intval($this->input->get('page')); //��ǰҳ
			if($start==0) $start=1;
			if(empty($fid)){
				$fid=($ac=='all')?'id,news,reco,hits,yhits,zhits,rhits,xhits,shits,dhits,chits,phits':'id';
			}

            //����ȫ�������ȡȫ������ID
            if($ac=='all' && $nums==0){
				   $cid=array();
                   $query = $this->db->query("SELECT id FROM ".CS_SqlPrefix."vod_list where yid=0 order by xid asc"); 
                   foreach ($query->result() as $rowc) {
                       $cid[]=$rowc->id;
                   }
			}
            //������ת�����ַ�
			if(is_array($cid)){
			     $cid=implode(',', $cid);
			}
			if(is_array($fid)){
			     $fid=implode(',', $fid);
			}
			//û��ѡ�����
			if(empty($cid)){
                    admin_msg('��ѡ��Ҫ���ɵķ���~!',site_url('vod/admin/html/type'));
			}

            //����URI
			$uri='?ac='.$ac.'&cid='.$cid.'&fid='.$fid.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

            //�ָ����ID
	        $arr = explode(',',$cid);
	        $len = count($arr);
            //�ָ�����ʽ
	        $arr2 = explode(',',$fid);
	        $len2 = count($arr2);

            //ȫ���������
	        if($nums>=$len){
                    admin_msg('���з���ȫ���������~!',site_url('vod/admin/html/type'));
			}

			$id=$arr[$nums]; //��ǰ����ID
			$type=$arr2[$numx]; //��ǰ����

			//���¶���ģ��·��
			$this->load->get_templates('vod',2);

			//��ȡ������Ϣ
			$row=$this->CsdjDB->get_row_arr('vod_list','*',$id);
            $template=!empty($row['skins'])?$row['skins']:'list.html';

			$arr['cid']=getChild($id);
			$arr['fid']=($row['fid']==0)?$row['id']:$row['fid'];
			$arr['sid']=$arr['fid'];

            if($datacount==0){
				 $template=$this->load->view($template,'',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."vod where yid=0 and hid=0 and cid IN (". getChild($id).")";
                 $rows=$this->db->query($sqlstr)->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/$pagesize);
			}
            if($datacount==0) $pagejs=1;


            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ���ɷ���<font color=red> '.$row["name"].' </font>��<font color=red> '.$this->gettype($type).' </font>������б�,��<font color=red>'.ceil($pagejs/Html_PageNum).'</font>�����ɣ���ǰ��<font color=red>'.ceil($start/Html_PageNum).'</font>��</b><br/>';

            $n=1;
            $pagego=1;
            if($page>0 && $page<$pagejs){
    	        $pagejs=$page;
            }

            for($i=$start;$i<=$pagejs;$i++){
                  
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('lists',$type,$id,$i,'vod',$row['bname']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'vod');
				  $skins=empty($row['skins'])?'list.html':$row['skins'];
				  $Mark_Text=$this->CsdjTpl->plub_list($row,$id,$type,$i,$arr,true,$skins,'lists','vod',$row['name'],$row['name']);
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "<a target='_blank' href='".$Htmllinks."'>&nbsp;&nbsp;��".$i."ҳ&nbsp;&nbsp;".$Htmllinks."<font color=green>�������~!</font></a><br/>";
                  $n++;
                  ob_flush();flush();

                  if(Html_PageNum==$n){
			           $pagego=2;
        	           break;
                  }
			}

   		    if($pagego==2){ //����ϵͳ����ÿҳ�������ֶ�ҳ����
				  $url=site_url('vod/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>��ͣ".Html_StopTime."��������һҳ&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}else{  
    	          $url=site_url('vod/admin/html/type_save').$uri.'&nums='.($nums+1);
			      $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b>";
            }

            //�ж��������
			if(($numx+1)>=$len2){ //ȫ�����
                $url=site_url('vod/admin/html/type_save').$uri.'&nums='.($nums+1);
				$str="&nbsp;&nbsp;<b><font color=#0000ff>��������ҳ��ȫ���������!</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('vod/admin/html/type_save').$uri.'&nums='.($nums+1)."'>������� �����û����ת����������...</a></b>";
			}else{ //��ǰ����ʽ���
                $url=site_url('vod/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1);
				$str='&nbsp;&nbsp;<b>��<font color=#0000ff>'.$this->gettype($type).'����</font>�����������!&nbsp;>>>>&nbsp;&nbsp;<a href="'.site_url('vod/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1).'">������� �����û����ת����������...</a></b>';
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function show()
	{
            $this->load->view('html_show.html');
	}

    //����ҳ���ɲ���
	public function show_save()
	{
            if($this->huri['show']['check']==0){
				admin_msg('��Ƶ����ҳδ��������~!','javascript:history.back();','no');
			}
            $day = intval($this->input->get_post('day',true)); //�������
            $ids = $this->input->get_post('ids',true); //��Ҫ���ɵ�����ID
            $cid = $this->input->get_post('cid',true); //��Ҫ���ɵķ���ID
            $newid= intval($this->input->get_post('newid')); //���¸���
            $ksid= intval($this->input->get_post('ksid')); //��ʼID
            $jsid= intval($this->input->get_post('jsid')); //����ID
            $kstime= $this->input->get_post('kstime',true); //��ʼ����
            $jstime= $this->input->get_post('jstime',true); //��������
            $pagesize= intval($this->input->get('pagesize')); //ÿҳ������
            $pagejs= intval($this->input->get('pagejs')); //��ҳ��
            $datacount= intval($this->input->get('datacount')); //��������
            $page= intval($this->input->get('page')); //��ǰҳ
			if($page==0) $page=1;
			$str='';

            //������ת�����ַ�
			if(is_array($cid)){
			     $cid=implode(',', $cid);
			}
			if(is_array($ids)){
			     $ids=implode(',', $ids);
			}

            if($day>0){
				 $times=time()-86400*$day;
                 $str.=' and addtime>'.$times.'';
			}
            if(!empty($cid)){
                 $str.=' and cid in ('.$cid.')';
			}
            if(!empty($ids)){
                 $str.=' and id in ('.$ids.')';
			}
            if($ksid>0 && $jsid>0){
                 $str.=' and id>'.($ksid-1).' and id<'.($jsid+1).'';
			}
            if(!empty($kstime) && !empty($jstime)){
				 $ktime=strtotime($kstime)-86400;
				 $jtime=strtotime($jstime)+86400;
                 $str.=' and addtime>'.$ktime.' and addtime<'.$jtime.'';
			}

			$limit='';
            if($newid>0){
                 $limit=' order by id desc limit '.$newid;
			}
            if($datacount==0){
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."vod where yid=0 and hid=0 ".$str.$limit;
                 $rows=$this->db->query($sqlstr)->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/Html_PageNum);
			}
            if($datacount==0) $pagejs=1;

            $pagesize=Html_PageNum;
            if($datacount<$pagesize){
    	        $pagesize=$datacount;
            }

            //ȫ���������
	        if($page>$pagejs){
                   admin_msg('��������ҳȫ���������~!',site_url('vod/admin/html/show'));
			}


            //����URI
			$uri='?day='.$day.'&cid='.$cid.'&ids='.$ids.'&newid='.$newid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('vod',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ������Ƶ����,��<font color=red>'.$pagejs.'</font>�����ɣ���ǰ��<font color=red>'.$page.'</font>��</b><br/>';

            $sql_string="select * from ".CS_SqlPrefix."vod where yid=0 and hid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

			//��ȡ����ҳ�Ƿ���Ҫ����
			$html=config('Html_Uri','vod');

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('show','id',$row['id'],0,'vod');
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'vod');

				  //�ݻٲ�����Ҫ���������ֶ�����
				  $rows=$row; //�ȱ������鱣������ʹ��
				  unset($row['zhuyan']);
				  unset($row['daoyan']);
				  unset($row['yuyan']);
				  unset($row['diqu']);
				  unset($row['tags']);
				  unset($row['year']);
				  //��̬ģʽ��̬����
			      unset($row['hits']);
			      unset($row['yhits']);
			      unset($row['zhits']);
			      unset($row['rhits']);
			      unset($row['shits']);
			      unset($row['xhits']);
			      unset($row['dhits']);
			      unset($row['chits']);
			      unset($row['pfen']);

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
				  //������̬������ǩ
				  $Mark_Text=str_replace("[vod:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:shits]","<script src='".hitslink('hits/dt/shits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:xhits]","<script src='".hitslink('hits/dt/xhits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:pfen]","<script src='".hitslink('hits/dt/pfen/'.$id,'vod')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[vod:pfenbi]","<script src='".hitslink('hits/dt/pfenbi/'.$id,'vod')."'></script>",$Mark_Text);
				  //�����������ص�ַ
           	   	  $Mark_Text=Vod_Playlist($Mark_Text,'play',$id,$row['purl']);
           	  	  $Mark_Text=Vod_Playlist($Mark_Text,'down',$id,$row['durl']);

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>����ӰƬ:<font color=red>".$row['name']."</font>�ɹ�:<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";

				  //�ж��Ƿ����ɲ���ҳ
				  if($html['play']['check']==1){
				        $this->getplay($rows);
				  }
                  ob_flush();flush();
			}
            if(!empty($ids)){
                $url=$_SERVER['HTTP_REFERER'];
			    $str="&nbsp;&nbsp;<b>ȫ���������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}else{ 
    	        $url=site_url('vod/admin/html/show_save').$uri.'&page='.($page+1);
			    $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function opt()
	{
		    //��ȡ�Զ���ģ��
			$Skins_Dir=config('Skins_Dir','vod');
			$this->load->helper('directory');
            $path=FCPATH.'plugins/vod/tpl/skins/'.$Skins_Dir;
            $dir_arr=directory_map($path, 1);
            $skinsdirs=array();
		    if ($dir_arr) {
			    foreach ($dir_arr as $t) {
				    if (!is_dir($path.$t)) {
						if(substr($t,0,4)=='opt-'){
					        $skinsdirs[] = $t;
						}
				    }
			    }
		    }
	        $data['skins_skins'] = $skinsdirs;
            $this->load->view('html_opt.html',$data);
	}

    //����ҳ���ɲ���
	public function opt_save()
	{

            $path = $this->input->post('path',true); //��ʽ

			//���¶���ģ��·��
			$this->load->get_templates('vod',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';

            if(!empty($path)){
              foreach ($path as $t) {
                  
				  $t=str_replace(".html","",$t);
				  $t=str_replace("opt-","",$t);
				  $Mark_Text=$this->CsdjTpl->opt($t,true);

				  $Htmllink=Web_Path.'opt/'.$t.'_vod.html';

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>�����Զ���ҳ��:<font color=red>".$Htmllink."</font>�ɹ�:<a href=".$Htmllink." target=_blank>".$Htmllink."</a></font><br/>";
                  ob_flush();flush();
			  }
			}

    	    $url=site_url('vod/admin/html/opt');
			echo("</br><b>&nbsp;�Զ���ҳ��ȫ���������~!</b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //���ɲ���ҳ
	public function getplay($row){
			//����
			$dance_pl=get_pl('vod',$row['id']);
			$rows=$row; //�ȱ������鱣������ʹ��
			$id=$rows['id'];
		    //����ҳ
		    if(!empty($row['purl'])){
	               $Data_Arr=explode("#cscms#",$row['purl']);
				   for($i=0;$i<count($Data_Arr);$i++){
 
		                  $DataList_Arr=explode("\n",$Data_Arr[$i]);
						  for($j=0;$j<count($DataList_Arr);$j++){
			                       //�ݻٲ�����Ҫ���������ֶ�����
			                       unset($row['zhuyan']);
			                       unset($row['daoyan']);
			                       unset($row['yuyan']);
			                       unset($row['diqu']);
			                       unset($row['tags']);
			                       unset($row['year']);
								   //��̬����
			                       unset($row['hits']);
			                       unset($row['yhits']);
			                       unset($row['zhits']);
			                       unset($row['rhits']);
			                       unset($row['dhits']);
			                       unset($row['chits']);
			                       unset($row['xhits']);
			                       unset($row['shits']);

			                       $arr['cid']=getChild($row['cid']);
			                       $arr['uid']=$row['uid'];
			                       $arr['singerid']=$row['singerid'];
			                       $arr['tags']=$rows['tags'];
			                       $skins=$row['skins'];
			                       if(empty($skins) || $skins=='play.html'){
			                            $skins=getzd('vod_list','skins3',$row['cid']);
			                       }
			                       if(empty($skins)) $skins='play.html';
			                       //װ��ģ�岢���
	                               $Mark_Text=$this->CsdjTpl->plub_show('vod',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
			                       $Mark_Text=str_replace("[vod:pl]",$dance_pl,$Mark_Text);
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
                                   $Mark_Text=Vod_Playlist($Mark_Text,'play',$id,$row['purl']);
			                       //������
                                   if($i>=count($Data_Arr)) $i=0;
		                           $DataList_Arr=explode("\n",$Data_Arr[$i]);
		                           $Dataurl_Arr=explode('$',$DataList_Arr[$j]);

		                           $laiyuan=str_replace("\r","",@$Dataurl_Arr[2]); //��Դ
		                           $url=$Dataurl_Arr[1];  //��ַ
								   if(substr($url,0,11)=='attachment/') $url=annexlink($url);
		                           $pname=$Dataurl_Arr[0];  //��ǰ����
		                           $Mark_Text=str_replace("[vod:qurl]",$url,$Mark_Text);
		                           $Mark_Text=str_replace("[vod:laiy]",$laiyuan,$Mark_Text);
		                           $Mark_Text=str_replace("[vod:ji]",$pname,$Mark_Text);

		                           if(count($DataList_Arr)>($j+1)){
			                           $DataNext=$DataList_Arr[($j+1)];
			                           $DataNextArr=explode('$',$DataNext);
			                           if(count($DataNextArr)==2) $DataNext=$DataNextArr[1];
			                           $xurl=VodPlayUrl('play',$id,$i,($j+1));
		                               $Dataurl_Arr2=explode('$',$DataList_Arr[($j+1)]);
		                           }else{
			                           $DataNext=$DataList_Arr[$j];
			                           $DataNextArr=explode('$',$DataNext);
			                           if(count($DataNextArr)==2) $DataNext=$DataNextArr[1];			
			                           $xurl=VodPlayUrl('play',$id,$i,$j);
		                           }
                                   if($j==0){
			                           $surl=VodPlayUrl('play',$id,$i,$j);
                                   }else{
			                           $surl=VodPlayUrl('play',$id,$i,($j-1));
                                   }
                                   $psname='';
			                       for($a=0;$a<count($Data_Arr);$a++){
				                          $jis='';
			                              $Ji_Arr=explode("\n",$Data_Arr[$a]);
                                          for($k=0;$k<count($Ji_Arr);$k++){
			                                   $Ly_Arr=explode('$',$Ji_Arr[$k]);
						                       $jis.=$Ly_Arr[0].'$$'.@$Ly_Arr[2].'====';
				                          }
				                          $psname.=substr($jis,0,-4).'#cscms#';
			                       }
                                   $player_arr=str_replace("\r","",substr($psname,0,-7));
	                               if($laiyuan=='xgvod'||$laiyuan=='jjvod'||$laiyuan=='yyxf'||$laiyuan=='bdhd'||$laiyuan=='qvod'){
	                                   $url=str_replace("+","__",base64_encode($url));
			                       }else{
	                                   $url=escape($url);
			                       }
		                           $player="<script type='text/javascript' src='".site_url('vod/play/pay/'.$id.'/'.$i.'/'.$j)."'></script><script type='text/javascript' src='".hitslink('play/form','vod')."'></script><script type='text/javascript'>var cs_playlink='".VodPlayUrl('play',$id,$i,$j,1)."';var cs_did='".$id."';var player_name='".$player_arr."';var cs_pid='".$j."';var cs_zid='".$i."';var cs_vodname='".$row['name']." - ".$pname."';var cs_root='http://".Web_Url.Web_Path."';var cs_width=".CS_Play_sw.";var cs_height=".CS_Play_sh.";var cs_surl='".$surl."';var cs_xurl='".$xurl."';var cs_laiy='".$laiyuan."';var cs_adloadtime='".CS_Play_AdloadTime."';</script>
								   <iframe border=\"0\" name=\"cscms_vodplay\" id=\"cscms_vodplay\" src=\"".Web_Path."packs/vod_player/play.html\" marginwidth=\"0\" framespacing=\"0\" marginheight=\"0\" noresize=\"\" vspale=\"0\" style=\"z-index: 9998;\" frameborder=\"0\" height=\"".(CS_Play_sh+30)."\" scrolling=\"no\" width=\"100%\"></iframe>";
		                           $Mark_Text=str_replace("[vod:player]",$player,$Mark_Text);
		                           $Mark_Text=str_replace("[vod:surl]",$surl,$Mark_Text);
		                           $Mark_Text=str_replace("[vod:xurl]",$xurl,$Mark_Text);
				                   //������̬������ǩ
				                   $Mark_Text=str_replace("[vod:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:shits]","<script src='".hitslink('hits/dt/shits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:xhits]","<script src='".hitslink('hits/dt/xhits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:pfen]","<script src='".hitslink('hits/dt/pfen/'.$id,'vod')."'></script>",$Mark_Text);
				                   $Mark_Text=str_replace("[vod:pfenbi]","<script src='".hitslink('hits/dt/pfenbi/'.$id,'vod')."'></script>",$Mark_Text);
			                       //��������
			                       $Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'vod'));
                                   //�滻��̨������
                                   $Mark_Text=str_replace(SELF,'index.php',$Mark_Text);

                                   //��ȡ��̬·��
				                   $Htmllinks=VodPlayUrl('play',$id,$i,$j);
								   //���ɵ�ַת��
								   $Htmllink=adminhtml($Htmllinks,'vod');
                                   //����
			                       write_file(FCPATH.$Htmllink,$Mark_Text);
						  }
				          echo "&nbsp;&nbsp;&nbsp;<font style=font-size:9pt;color:red;>--���ɵ�".($i+1)."�鲥������<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
				   }
		    }
	}

    //��ȡ��������
	public function gettype($fid){
		    $str="ID";
            switch($fid){
                   case 'id':$str="ID";break;
                   case 'news':$str="����ʱ��";break;
                   case 'reco':$str="�����Ƽ�";break;
                   case 'hits':$str="������";break;
                   case 'yhits':$str="������";break;
                   case 'zhits':$str="������";break;
                   case 'rhits':$str="������";break;
                   case 'shits':$str="�ղ�";break;
                   case 'xhits':$str="����";break;
                   case 'dhits':$str="����";break;
                   case 'chits':$str="����";break;
                   case 'phits':$str="����";break;
                   case 'yue':$str="������";break;
                   case 'zhou':$str="������";break;
                   case 'ri':$str="������";break;
                   case 'fav':$str="�ղ�";break;
                   case 'down':$str="����";break;
                   case 'ding':$str="����";break;
                   case 'cai':$str="����";break;
			}
			return $str;
	}
}
