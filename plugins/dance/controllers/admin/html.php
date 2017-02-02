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
		    $this->load->helper('dance');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
		    $this->load->model('CsdjTpl');
            //�ж�����ģʽ
	        if(config('Web_Mode')!=3){
                 admin_msg(L('plub_17'),'javascript:history.back();','no');
			}
			$this->huri=config('Html_Uri');
	}

    //��ҳ����
	public function index()
	{
            if($this->huri['index']['check']==0){
				admin_msg(L('plub_18'),'javascript:history.back();','no');
			}
            //��ȡ��̬·��
			$uri=config('Html_Uri');
			$index_url=adminhtml($uri['index']['url']);
			$this->load->get_templates('dance',2);
		    $Mark_Text=$this->CsdjTpl->plub_index('dance','index.html',true);
			write_file(FCPATH.$index_url,$Mark_Text);
            admin_msg(L('plub_19').'<a target="_blank" href="'.Web_Path.$index_url.'"><font color="red">'.L('plub_20').'</font></a>','###');
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
				admin_msg(L('plub_21'),'javascript:history.back();','no');
			}
            $start= intval($this->input->get('start')); //��ǰҳ���ɱ��
            $pagesize= intval($this->input->get('pagesize')); //ÿҳ������
            $pagejs= intval($this->input->get('pagejs')); //��ҳ��
            $datacount= intval($this->input->get('datacount')); //��������
            $page= intval($this->input->get('page')); //��ǰҳ
            $nums= intval($this->input->get('nums')); //�������������
            $numx= intval($this->input->get('numx')); //����ʽ���������
            $cid = $this->input->get_post('cid',true); //��Ҫ���ɵķ���ID
			$fid = $this->input->get_post('fid',true); //��Ҫ���ɵ�����ʽ
			if($start==0) $start=1;
            if(empty($fid)) $fid='id';

            //������ת�����ַ�
			if(is_array($cid)){
			     $cid=implode(',', $cid);
			}
			if(is_array($fid)){
			     $fid=implode(',', $fid);
			}


            //����URI
			$uri='?cid='.$cid.'&fid='.$fid.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

            //�ָ����ID
	        $arr = explode(',',$cid);
	        $len = count($arr);
            //�ָ�����ʽ
	        $arr2 = explode(',',$fid);
	        $len2 = count($arr2);

            //ȫ���������
	        if($nums>=$len){
                 admin_msg(L('plub_22'),site_url('dance/admin/html/topic'));
			}

			$id=$arr[$nums]; //��ǰ����ID
			$type=$arr2[$numx]; //��ǰ����

			//���¶���ģ��·��
			$this->load->get_templates('dance',2);

			//��ȡ��ҳ��Ϣ
            if($datacount==0){
				 $template=$this->load->view('topic.html','',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select id,cid from ".CS_SqlPrefix."dance_topic where yid=0";
				 if($id>0){
                     $sqlstr.=" and cid=".$id."";
				 }
                 $rows=$this->db->query(str_replace("id,cid","count(*) as count",$sqlstr))->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/$pagesize);
			}
            if($datacount==0) $pagejs=1;

            if(empty($id)){
                $row=array();
				$row['name']=L('plub_23');
				$id=0;
			}else{
                $row=$this->CsdjDB->get_row_arr('dance_list','*',$cid);
            }
            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo vsprintf(L('plub_24'),array($row["name"],$this->gettype($type),ceil($pagejs/Html_PageNum),ceil($start/Html_PageNum)));

            $n=1;
            $pagego=1;
            if($page>0 && $page<$pagejs){
    	        $pagejs=$page;
            }

            for($i=$start;$i<=$pagejs;$i++){
                  
                  //��ȡ��̬·��
				  $Htmllinks=$Htmllink=LinkUrl('topic/lists',$type,$id,$i,'dance',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'dance');
				  $Mark_Text=$this->CsdjTpl->plub_list($row,$id,$type,$i,$id,true,'topic.html','topic/lists','',L('plub_34'),L('plub_34'));
				  $Mark_Text=str_replace("[topic:link]",LinkUrl('topic/lists','id',$id,$i,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[topic:hlink]",LinkUrl('topic/lists','hits',$id,$i,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[topic:rlink]",LinkUrl('topic/lists','ri',$id,$i,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[topic:zlink]",LinkUrl('topic/lists','zhou',$id,$i,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[topic:ylink]",LinkUrl('topic/lists','yue',$id,$i,'dance'),$Mark_Text);
			      $Mark_Text=str_replace("[topic:slink]",LinkUrl('topic/lists','fav',$cid,$page,'dance'),$Mark_Text);
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "<a target='_blank' href='".$Htmllinks."'>&nbsp;&nbsp;".vsprintf(L('plub_25'),array($i))."&nbsp;&nbsp;".$Htmllinks."<font color=green>".L('plub_26')."</font></a><br/>";
                  $n++;
                  ob_flush();flush();

                  if(Html_PageNum==$n){
			           $pagego=2;
        	           break;
                  }
			}

   		    if($pagego==2){ //����ϵͳ����ÿҳ�������ֶ�ҳ����
				  $url=site_url('dance/admin/html/topic_save').$uri.'&nums='.$nums.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>".L('plub_28')."</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}else{  
    	          $url=site_url('dance/admin/html/topic_save').$uri.'&nums='.($nums+1);
			      $str="&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>".L('plub_28')."</a></b>";
            }

            //�ж��������
			if(($numx+1)>=$len2){ //ȫ�����
                $url=site_url('dance/admin/html/topic_save').$uri.'&nums='.($nums+1);
				$str="&nbsp;&nbsp;<b><font color=#0000ff>".L('plub_29')."</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('dance/admin/html/topic_save').$uri.'&nums='.($nums+1)."'>".L('plub_28')."</a></b>";
			}else{ //��ǰ����ʽ���
                $url=site_url('dance/admin/html/topic_save').$uri.'&nums='.$nums.'&numx='.($numx+1);
				$str='&nbsp;&nbsp;<b>'.vsprintf(L('plub_30'),array($this->gettype($type))).'&nbsp;>>>>&nbsp;&nbsp;<a href="'.site_url('dance/admin/html/topic_save').$uri.'&nums='.$nums.'&numx='.($numx+1).'">'.L('plub_28').'</a></b>';
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //ר������ҳ���ɲ���
	public function topicshow_save()
	{
            if($this->huri['topic/show']['check']==0){
				admin_msg(L('plub_31'),'javascript:history.back();','no');
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."dance_topic where yid=0 ".$str;
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
                   admin_msg(L('plub_32'),site_url('dance/admin/html/topic'));
			}


            //����URI
			$uri='?tid='.$tid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('dance',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo vsprintf(L('plub_33'),array($pagejs,$page));

            $sql_string="select * from ".CS_SqlPrefix."dance_topic where yid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('topic','show',$id,1,'dance',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'dance');
				  //�ݻٶ�̬�����ֶ�����
				  unset($row['hits']);
				  unset($row['yhits']);
				  unset($row['zhits']);
				  unset($row['rhits']);

				  //װ��ģ�岢���
				  $ids['tid']=$id;
				  $ids['uid']=$row['uid'];
				  $ids['singerid']=$row['singerid'];
	        	  $Mark_Text=$this->CsdjTpl->plub_show('topic',$row,$ids,true,'topic-show.html',$row['name'].'-'.L('plub_34'),$row['name']);
				  //����
				  $Mark_Text=str_replace("[topic:pl]",get_pl('dance',$id,1),$Mark_Text);
				  //��̬����
				  $Mark_Text=str_replace("[topic:hits]","<script src='".hitslink('hits/dt/hits/'.$id.'/topic','dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id.'/topic','dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id.'/topic','dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id.'/topic','dance')."'></script>",$Mark_Text);
				  //��������
			      $Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id.'/topic','dance'));

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>".L('plub_35')."<font color=red>".$row['name']."</font>".L('plub_36')."<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}

    	    $url=site_url('dance/admin/html/topicshow_save').$uri.'&page='.($page+1);
			$str="&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>".L('plub_28')."</a></b>";
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
				admin_msg(L('plub_37'),'javascript:history.back();','no');
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
				$fid=($ac=='all')?'id,dance,reco,hits,yhits,zhits,rhits,xhits,shits,dhits,chits,phits':'id';
			}

            //����ȫ�������ȡȫ������ID
            if($ac=='all' && $nums==0){
				   $cid=array();
                   $query = $this->db->query("SELECT id FROM ".CS_SqlPrefix."dance_list where yid=0 order by xid asc"); 
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
                    admin_msg(L('plub_38'),site_url('dance/admin/html/type'));
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
                    admin_msg(L('plub_39'),site_url('dance/admin/html/type'));
			}

			$id=$arr[$nums]; //��ǰ����ID
			$type=$arr2[$numx]; //��ǰ����

			//���¶���ģ��·��
			$this->load->get_templates('dance',2);

			//��ȡ������Ϣ
			$row=$this->CsdjDB->get_row_arr('dance_list','*',$id);
            $template=!empty($row['skins'])?$row['skins']:'list.html';
			$ids=getChild($id); //��ȡ�ӷ���

            if($datacount==0){
				 $template=$this->load->view($template,'',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."dance where yid=0 and hid=0 and cid IN (". getChild($id).")";
                 $rows=$this->db->query($sqlstr)->result_array();
				 $datacount=$rows[0]['count'];
    	         $pagejs = ceil($datacount/$pagesize);
			}
            if($datacount==0) $pagejs=1;


            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo vsprintf(L('plub_40'),array($row["name"],$this->gettype($type),ceil($pagejs/Html_PageNum),ceil($start/Html_PageNum)));
            $n=1;
            $pagego=1;
            if($page>0 && $page<$pagejs){
    	        $pagejs=$page;
            }

            for($i=$start;$i<=$pagejs;$i++){
                  
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('lists',$type,$id,$i,'dance',$row['bname']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'dance');
				  $skins=empty($row['skins'])?'list.html':$row['skins'];
				  $Mark_Text=$this->CsdjTpl->plub_list($row,$id,$type,$i,$ids,true,$skins,'lists','dance',$row['name'],$row['name']);
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "<a target='_blank' href='".$Htmllinks."'>&nbsp;&nbsp;".vsprintf(L('plub_25'),array($i))."&nbsp;&nbsp;".$Htmllinks."<font color=green>".L('plub_26')."</font></a><br/>";
                  $n++;
                  ob_flush();flush();

                  if(Html_PageNum==$n){
			           $pagego=2;
        	           break;
                  }
			}

   		    if($pagego==2){ //����ϵͳ����ÿҳ�������ֶ�ҳ����
				  $url=site_url('dance/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime)).L('plub_65')."&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>".L('plub_28')."</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}else{  
    	          $url=site_url('dance/admin/html/type_save').$uri.'&nums='.($nums+1);
			      $str="&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>".L('plub_28')."</a></b>";
            }

            //�ж��������
			if(($numx+1)>=$len2){ //ȫ�����
                $url=site_url('dance/admin/html/type_save').$uri.'&nums='.($nums+1);
				$str="&nbsp;&nbsp;<b><font color=#0000ff>".L('plub_29')."</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('dance/admin/html/type_save').$uri.'&nums='.($nums+1)."'>".L('plub_28')."</a></b>";
			}else{ //��ǰ����ʽ���
                $url=site_url('dance/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1);
				$str='&nbsp;&nbsp;<b>'.vsprintf(L('plub_30'),array($this->gettype($type))).'&nbsp;>>>>&nbsp;&nbsp;<a href="'.site_url('dance/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1).'">'.L('plub_28').'</a></b>';
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function play()
	{
            $this->load->view('html_play.html');
	}

    //����ҳ���ɲ���
	public function play_save()
	{
            if($this->huri['play']['check']==0){
				admin_msg(L('plub_41'),'javascript:history.back();','no');
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."dance where yid=0 and hid=0 ".$str.$limit;
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
                   admin_msg(L('plub_42'),site_url('dance/admin/html/play'));
			}


            //����URI
			$uri='?day='.$day.'&cid='.$cid.'&ids='.$ids.'&newid='.$newid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('dance',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo vsprintf(L('plub_43'),array($pagejs,$page));

            $sql_string="select * from ".CS_SqlPrefix."dance where yid=0 and hid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

			//��ȡ����ҳ�Ƿ���Ҫ����
			$html=config('Html_Uri','dance');

            foreach ($query->result_array() as $row) {

                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('play','id',$row['id'],0,'dance',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'dance');

				  //�ݻٲ�����Ҫ���������ֶ�����
				  $rows=$row; //�ȱ������鱣������ʹ��
				  unset($row['tags']);
				  unset($row['hits']);
				  unset($row['yhits']);
				  unset($row['zhits']);
				  unset($row['rhits']);
				  unset($row['dhits']);
				  unset($row['chits']);
				  unset($row['shits']);
				  unset($row['xhits']);
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
				  $Mark_Text=str_replace("[dance:link]",LinkUrl('play','id',$row['id'],1,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[dance:classlink]",LinkUrl('lists','id',$row['cid'],1,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[dance:classname]",$this->CsdjDB->getzd('dance_list','name',$row['cid']),$Mark_Text);
				  //ר��
				  if($row['tid']==0){
			    	   $Mark_Text=str_replace("[dance:topiclink]","###",$Mark_Text);
			    	   $Mark_Text=str_replace("[dance:topicname]",L('plub_44'),$Mark_Text);
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
			            	   $Mark_Text=str_replace("[dance:sname]",L('plub_45'),$Mark_Text);
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
			            	   $Mark_Text=str_replace("[dance:xname]",L('plub_45'),$Mark_Text);
			            	   $Mark_Text=str_replace("[dance:xid]",0,$Mark_Text);
				   	  }
				  }
				  unset($arr);
				  //��ǩ�ӳ�������
				  $Mark_Text=str_replace("[dance:tags]",SearchLink($rows['tags']),$Mark_Text);
				  //��̬����
				  $Mark_Text=str_replace("[dance:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:shits]","<script src='".hitslink('hits/dt/shits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:xhits]","<script src='".hitslink('hits/dt/xhits/'.$id,'dance')."'></script>",$Mark_Text);
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
                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>".L('plub_46')."<font color=red>".$row['name']."</font>".L('plub_36')."<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
				  ob_flush();flush();
			}
            if(!empty($ids)){
                $url='javascript:history.back();';
			    $str="&nbsp;&nbsp;<b>".L('plub_66')."&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>".L('plub_28')."</a></b>";
			}else{ 
    	        $url=site_url('dance/admin/html/play_save').$uri.'&page='.($page+1);
			    $str="&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>".L('plub_28')."</a></b>";
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function down()
	{
            $this->load->view('html_down.html');
	}

    //����ҳ���ɲ���
	public function down_save()
	{
            if($this->huri['down']['check']==0){
				admin_msg(L('plub_47'),'javascript:history.back();','no');
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."dance where yid=0 and hid=0 ".$str.$limit;
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
                   admin_msg(L('plub_48'),site_url('dance/admin/html/down'));
			}

            //����URI
			$uri='?day='.$day.'&cid='.$cid.'&ids='.$ids.'&newid='.$newid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('dance',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo vsprintf(L('plub_49'),array($pagejs,$page));

            $sql_string="select * from ".CS_SqlPrefix."dance where yid=0 and hid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

			//��ȡ����ҳ�Ƿ���Ҫ����
			$html=config('Html_Uri','dance');

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('down','id',$row['id'],0,'dance',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'dance');

				  //�ݻٲ�����Ҫ���������ֶ�����
				  $rows=$row; //�ȱ������鱣������ʹ��
				  unset($row['tags']);
				  unset($row['hits']);
				  unset($row['yhits']);
				  unset($row['zhits']);
				  unset($row['rhits']);
				  unset($row['dhits']);
				  unset($row['chits']);
				  unset($row['shits']);
				  unset($row['xhits']);
				  //��ȡ��ǰ�����¶�������ID
				  $arr['cid']=getChild($row['cid']);
				  $arr['uid']=$row['uid'];
				  $arr['did']=$row['id'];
				  $arr['singerid']=$row['singerid'];
				  $arr['tags']=$rows['tags'];
				  //װ��ģ�岢���
	       	      $Mark_Text=$this->CsdjTpl->plub_show('dance',$row,$arr,TRUE,'down.html',$row['name'],$row['name']);
				  //����
				  $Mark_Text=str_replace("[dance:pl]",get_pl('dance',$id),$Mark_Text);
				  //�����ַ������
				  $Mark_Text=str_replace("[dance:link]",LinkUrl('play','id',$row['id'],1,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[dance:classlink]",LinkUrl('lists','id',$row['cid'],1,'dance'),$Mark_Text);
				  $Mark_Text=str_replace("[dance:classname]",$this->CsdjDB->getzd('dance_list','name',$row['cid']),$Mark_Text);
				  //ר��
				  if($row['tid']==0){
			    	   $Mark_Text=str_replace("[dance:topiclink]","###",$Mark_Text);
			    	   $Mark_Text=str_replace("[dance:topicname]","δ����",$Mark_Text);
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
			            	   $Mark_Text=str_replace("[dance:sname]",L('plub_45'),$Mark_Text);
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
			            	   $Mark_Text=str_replace("[dance:xname]",L('plub_45'),$Mark_Text);
			            	   $Mark_Text=str_replace("[dance:xid]",0,$Mark_Text);
				   	  }
				  }
				  unset($arr);
				  //��ǩ�ӳ�������
				  $Mark_Text=str_replace("[dance:tags]",SearchLink($rows['tags']),$Mark_Text);
				  //��̬����
				  $Mark_Text=str_replace("[dance:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:shits]","<script src='".hitslink('hits/dt/shits/'.$id,'dance')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[dance:xhits]","<script src='".hitslink('hits/dt/xhits/'.$id,'dance')."'></script>",$Mark_Text);
			      //�����������������ص�ַ
                  preg_match_all('/[dance:qurl]/',$Mark_Text,$arr);
		          if(!empty($arr[0]) && !empty($arr[0][0])){
				       $purl=$row['purl'];
				       $durl=$row['durl'];
                       if($row['fid']>0){
                            $rowf=$this->db->query("Select purl,durl from ".CS_SqlPrefix."dance_server where id=".$row['fid']."")->row_array();
					        if($rowf){
				                 $purl=$rowf['purl'].$row['purl'];
				                 $durl=$rowf['durl'].$row['durl'];
					        }
				       }
				       $purl=annexlink($purl);
				       $durl=annexlink($durl);
                       $Mark_Text=str_replace("[dance:qurl]",$purl,$Mark_Text);
                       $Mark_Text=str_replace("[dance:qxurl]",$durl,$Mark_Text);
			      }
			      unset($arr);
                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>".L('plub_50')."<font color=red>".$row['name']."</font>".L('plub_36')."<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}
            if(!empty($ids)){
                $url='javascript:history.back();';
			    $str="&nbsp;&nbsp;<b>".L('plub_66')."&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>".L('plub_28')."</a></b>";
			}else{ 
    	        $url=site_url('dance/admin/html/down_save').$uri.'&page='.($page+1);
			    $str="&nbsp;&nbsp;<b>".vsprintf(L('plub_27'),array(Html_StopTime))."&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>".L('plub_28')."</a></b>";
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function opt()
	{
		    //��ȡ�Զ���ģ��
			$Skins_Dir=config('Skins_Dir','dance');
			$this->load->helper('directory');
            $path=FCPATH.'plugins/dance/tpl/skins/'.$Skins_Dir;
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
			$this->load->get_templates('dance',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';

            if(!empty($path)){
              foreach ($path as $t) {
                  
				  $t=str_replace(".html","",$t);
				  $t=str_replace("opt-","",$t);
				  $Mark_Text=$this->CsdjTpl->opt($t,true);

				  $Htmllink=Web_Path.'opt/'.$t.'_dance.html';

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>".L('plub_51')."<font color=red>".$Htmllink."</font>".L('plub_36')."<a href=".$Htmllink." target=_blank>".$Htmllink."</a></font><br/>";
                  ob_flush();flush();
			  }
			}

    	    $url=site_url('dance/admin/html/opt');
			echo("</br><b>&nbsp;".L('plub_52')."</b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //��ȡ��������
	public function gettype($fid){
		    $str="ID";
            switch($fid){
                   case 'id':$str="ID";break;
                   case 'dance':$str=L('plub_53');break;
                   case 'reco':$str=L('plub_54');break;
                   case 'hits':$str=L('plub_55');break;
                   case 'yhits':$str=L('plub_56');break;
                   case 'zhits':$str=L('plub_57');break;
                   case 'rhits':$str=L('plub_58');break;
                   case 'shits':$str=L('plub_59');break;
                   case 'xhits':$str=L('plub_60');break;
                   case 'yue':$str=L('plub_61');break;
                   case 'zhou':$str=L('plub_62');break;
                   case 'fav':$str=L('plub_59');break;
                   case 'down':$str=L('plub_60');break;
                   case 'ri':$str=L('plub_58');break;
                   case 'dhits':$str=L('plub_63');break;
                   case 'chits':$str=L('plub_64');break;
                   case 'ding':$str=L('plub_64');break;
                   case 'cai':$str=L('plub_64');break;
			}
			return $str;
	}
}
