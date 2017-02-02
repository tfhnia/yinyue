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
		    $this->load->helper('news');
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
			$this->load->get_templates('news',2);
		    $Mark_Text=$this->CsdjTpl->plub_index('news','index.html',true);
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
			$this->load->get_templates('news',2);

			//��ȡ��ҳ��Ϣ
            if($datacount==0){
				 $template=$this->load->view('topic.html','',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."news_topic where yid=0";
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
				  $Htmllinks=$Htmllink=LinkUrl('topic','lists',0,$i,'news');
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'news');
				  $Mark_Text=$this->CsdjTpl->plub_list(array(),1,'lists',$page,'',true,'topic.html','topic','','����ר��');
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
				  $url=site_url('news/admin/html/topic_save').$uri.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>��ͣ".Html_StopTime."��������һҳ&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}
            $url=site_url('news/admin/html/topic');
			$str="&nbsp;&nbsp;<b><font color=#0000ff>����ר���б�ȫ���������!</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('news/admin/html/topic')."'>������� �����û����ת����������...</a></b>";
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."news_topic where yid=0 ".$str;
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
                   admin_msg('����ר������ҳȫ���������~!',site_url('news/admin/html/topic'));
			}


            //����URI
			$uri='?tid='.$tid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('news',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ����ר������,��<font color=red>'.$pagejs.'</font>�����ɣ���ǰ��<font color=red>'.$page.'</font>��</b><br/>';

            $sql_string="select * from ".CS_SqlPrefix."news_topic where yid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('topic','show',$id,1,'news',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'news');
				  //�ݻٶ�̬�����ֶ�����
				  unset($row['hits']);
				  unset($row['yhits']);
				  unset($row['zhits']);
				  unset($row['rhits']);

				  //װ��ģ�岢���
	        	  $Mark_Text=$this->CsdjTpl->plub_show('topic',$row,$id,true,'topic-show.html',$row['name'],$row['name']);
				  //����
				  $Mark_Text=str_replace("[topic:pl]",get_pl('news',$id,1),$Mark_Text);
				  //��̬����
				  $Mark_Text=str_replace("[topic:hits]","<script src='".hitslink('hits/dt/hits/'.$id.'/topic','news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id.'/topic','news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id.'/topic','news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[topic:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id.'/topic','news')."'></script>",$Mark_Text);
				  //��������
			      $Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id.'/topic','news'));

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>��������ר��:<font color=red>".$row['name']."</font>�ɹ�:<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}

    	    $url=site_url('news/admin/html/topicshow_save').$uri.'&page='.($page+1);
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
				admin_msg('���ŷ���δ��������~!','javascript:history.back();','no');
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
                   $query = $this->db->query("SELECT id FROM ".CS_SqlPrefix."news_list where yid=0 order by xid asc"); 
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
                    admin_msg('��ѡ��Ҫ���ɵķ���~!',site_url('news/admin/html/type'));
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
                    admin_msg('���з���ȫ���������~!',site_url('news/admin/html/type'));
			}

			$id=$arr[$nums]; //��ǰ����ID
			$type=$arr2[$numx]; //��ǰ����

			//���¶���ģ��·��
			$this->load->get_templates('news',2);

			//��ȡ������Ϣ
			$row=$this->CsdjDB->get_row_arr('news_list','*',$id);
            $template=!empty($row['skins'])?$row['skins']:'list.html';
			$ids=getChild($id); //��ȡ�ӷ���

            if($datacount==0){
				 $template=$this->load->view($template,'',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."news where yid=0 and hid=0 and cid IN (". getChild($id).")";
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
				  $Htmllinks=LinkUrl('lists',$type,$id,$i,'news',$row['bname']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'news');
				  $skins=empty($row['skins'])?'list.html':$row['skins'];
				  $Mark_Text=$this->CsdjTpl->plub_list($row,$id,$type,$i,$ids,true,$skins,'lists','news',$row['name'],$row['name']);
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
				  $url=site_url('news/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>��ͣ".Html_StopTime."��������һҳ&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}else{  
    	          $url=site_url('news/admin/html/type_save').$uri.'&nums='.($nums+1);
			      $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b>";
            }

            //�ж��������
			if(($numx+1)>=$len2){ //ȫ�����
                $url=site_url('news/admin/html/type_save').$uri.'&nums='.($nums+1);
				$str="&nbsp;&nbsp;<b><font color=#0000ff>��������ҳ��ȫ���������!</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('news/admin/html/type_save').$uri.'&nums='.($nums+1)."'>������� �����û����ת����������...</a></b>";
			}else{ //��ǰ����ʽ���
                $url=site_url('news/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1);
				$str='&nbsp;&nbsp;<b>��<font color=#0000ff>'.$this->gettype($type).'����</font>�����������!&nbsp;>>>>&nbsp;&nbsp;<a href="'.site_url('news/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1).'">������� �����û����ת����������...</a></b>';
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
				admin_msg('��������ҳδ��������~!','javascript:history.back();','no');
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."news where yid=0 and hid=0 ".$str.$limit;
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
                   admin_msg('��������ҳȫ���������~!',site_url('news/admin/html/show'));
			}


            //����URI
			$uri='?day='.$day.'&cid='.$cid.'&ids='.$ids.'&newid='.$newid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('news',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ������������,��<font color=red>'.$pagejs.'</font>�����ɣ���ǰ��<font color=red>'.$page.'</font>��</b><br/>';

            $sql_string="select * from ".CS_SqlPrefix."news where yid=0 and hid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

			//��ȡ����ҳ�Ƿ���Ҫ����
			$html=config('Html_Uri','news');

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('show','id',$row['id'],0,'news',$row['name']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'news');

			      $arr['cid']=getChild($row['cid']);
			      $arr['uid']=$row['uid'];
			      $arr['tags']=$rows['tags'];
				  //�ݻٲ�����Ҫ���������ֶ�����
				  $rows=$row; //�ȱ������鱣������ʹ��
				  unset($row['tags']);
				  unset($row['hits']);
				  unset($row['yhits']);
				  unset($row['zhits']);
				  unset($row['rhits']);
				  unset($row['dhits']);
				  unset($row['chits']);
				  unset($row['content']);
				  //Ĭ��ģ��
			      $skins=empty($row['skins'])?'show.html':$row['skins'];
				  //װ��ģ�岢���
	        	  $Mark_Text=$this->CsdjTpl->plub_show('news',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
				  //����
				  $Mark_Text=str_replace("[news:pl]",get_pl('news',$id),$Mark_Text);
				  //�����ַ������
				  $Mark_Text=str_replace("[news:link]",LinkUrl('show','id',$row['id'],1,'news'),$Mark_Text);
				  $Mark_Text=str_replace("[news:classlink]",LinkUrl('lists','id',$row['cid'],1,'news'),$Mark_Text);
				  $Mark_Text=str_replace("[news:classname]",$this->CsdjDB->getzd('news_list','name',$row['cid']),$Mark_Text);
				  //��ȡ����ƪ
            	  preg_match_all('/[news:slink]/',$Mark_Text,$arr);
		    	  if(!empty($arr[0]) && !empty($arr[0][0])){
                   	  $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."news where yid=0 and hid=0 and id<".$id." order by id desc limit 1")->row();
				  	  if($rowd){
			             $Mark_Text=str_replace("[news:slink]",LinkUrl('show','id',$rowd->id,1,'news'),$Mark_Text);
			             $Mark_Text=str_replace("[news:sname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[news:sid]",$rowd->id,$Mark_Text);
				   	  }else{
			             $Mark_Text=str_replace("[news:slink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[news:sname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[news:sid]",0,$Mark_Text);
				   	  }
				  }
				  unset($arr);
            	  preg_match_all('/[news:xlink]/',$Mark_Text,$arr);
		    	  if(!empty($arr[0]) && !empty($arr[0][0])){
                  	  $rowd=$this->db->query("Select id,cid,name from ".CS_SqlPrefix."news where yid=0 and hid=0 and id>".$id." order by id asc limit 1")->row();
				  	  if($rowd){
			             $Mark_Text=str_replace("[news:xlink]",LinkUrl('show','id',$rowd->id,1,'news'),$Mark_Text);
			             $Mark_Text=str_replace("[news:xname]",$rowd->name,$Mark_Text);
			             $Mark_Text=str_replace("[news:xid]",$rowd->id,$Mark_Text);
				   	  }else{
			             $Mark_Text=str_replace("[news:xlink]","#",$Mark_Text);
			             $Mark_Text=str_replace("[news:xname]","û����",$Mark_Text);
			             $Mark_Text=str_replace("[news:xid]",0,$Mark_Text);
				   	  }
				  }
				  unset($arr);
				  //��ǩ�ӳ�������
				  $Mark_Text=str_replace("[news:tags]",SearchLink($rows['tags']),$Mark_Text);
				  //��̬����
				  $Mark_Text=str_replace("[news:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[news:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[news:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[news:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[news:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'news')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[news:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'news')."'></script>",$Mark_Text);
                  //��������,�ж��Ƿ����շ�����
				  if($row['vip']>0 || $row['level']>0 || $row['cion']>0){
				       $content="<div id='cscms_content'></div>";
				       if(config('Ym_Mode','news')==1){
                            $content.="<script type='text/javascript' src='http://".config('Ym_Url','news').Web_Path."index.php/show/pay/".$id."'></script>";
				       }else{
                            $content.="<script type='text/javascript' src='http://".Web_Url.Web_Path."index.php/news/show/pay/".$id."'></script>";
				       }
				  }else{
                       $content=$rows['content'];
				  }
				  $Mark_Text=str_replace("[news:content]",$content,$Mark_Text);
				  //��������
			      $Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'news'));
                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>��������:<font color=red>".$row['name']."</font>�ɹ�:<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}
            if(!empty($ids)){
                $url='javascript:history.back();';
			    $str="&nbsp;&nbsp;<b>ȫ���������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}else{ 
    	        $url=site_url('news/admin/html/show_save').$uri.'&page='.($page+1);
			    $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function opt()
	{
		    //��ȡ�Զ���ģ��
			$Skins_Dir=config('Skins_Dir','news');
			$this->load->helper('directory');
            $path=FCPATH.'plugins/news/tpl/skins/'.$Skins_Dir;
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
			$this->load->get_templates('news',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';

            if(!empty($path)){
              foreach ($path as $t) {
                  
				  $t=str_replace(".html","",$t);
				  $t=str_replace("opt-","",$t);
				  $Mark_Text=$this->CsdjTpl->opt($t,true);

				  $Htmllink=Web_Path.'opt/'.$t.'_news.html';

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>�����Զ���ҳ��:<font color=red>".$Htmllink."</font>�ɹ�:<a href=".$Htmllink." target=_blank>".$Htmllink."</a></font><br/>";
                  ob_flush();flush();
			  }
			}

    	    $url=site_url('news/admin/html/opt');
			echo("</br><b>&nbsp;�Զ���ҳ��ȫ���������~!</b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
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
                   case 'dhits':$str="����";break;
                   case 'chits':$str="����";break;
                   case 'yue':$str="������";break;
                   case 'zhou':$str="������";break;
                   case 'ri':$str="������";break;
                   case 'ding':$str="����";break;
                   case 'cai':$str="����";break;
			}
			return $str;
	}
}
