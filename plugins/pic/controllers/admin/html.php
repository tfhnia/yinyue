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
		    $this->load->helper('pic');
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
			$this->load->get_templates('pic',2);
		    $Mark_Text=$this->CsdjTpl->plub_index('pic','index.html',true);
			write_file(FCPATH.$index_url,$Mark_Text);
            admin_msg('�����ҳ�������,<a target="_blank" href="'.Web_Path.$index_url.'"><font color="red">���</font></a>','###');
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
				admin_msg('������δ��������~!','javascript:history.back();','no');
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
				$fid=($ac=='all')?'id,news,reco,hits,yhits,zhits,rhits,xhits,shits':'id';
			}

            //����ȫ�������ȡȫ������ID
            if($ac=='all' && $nums==0){
				   $cid=array();
                   $query = $this->db->query("SELECT id FROM ".CS_SqlPrefix."pic_list where yid=0 order by xid asc"); 
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
                    admin_msg('��ѡ��Ҫ���ɵķ���~!',site_url('pic/admin/html/type'));
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
                    admin_msg('���з���ȫ���������~!',site_url('pic/admin/html/type'));
			}

			$id=$arr[$nums]; //��ǰ����ID
			$type=$arr2[$numx]; //��ǰ����

			//���¶���ģ��·��
			$this->load->get_templates('pic',2);

			//��ȡ������Ϣ
			$row=$this->CsdjDB->get_row_arr('pic_list','*',$id);
            $template=!empty($row['skins'])?$row['skins']:'list.html';
			$ids['cid']=getChild($id); //��ȡ�ӷ���

            if($datacount==0){
				 $template=$this->load->view($template,'',true);
				 $pagesize=10;
			     preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$template,$page_arr);
		         if(!empty($page_arr) && !empty($page_arr[3][0])){
					    $pagesize=$page_arr[3][0];
				 }
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."pic_type where yid=0 and hid=0 and cid IN (". getChild($id).")";
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
				  $Htmllinks=LinkUrl('lists',$type,$id,$i,'pic',$row['bname']);
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'pic');
				  $skins=empty($row['skins'])?'list.html':$row['skins'];
				  $Mark_Text=$this->CsdjTpl->plub_list($row,$id,$type,$i,$ids,true,$skins,'lists','pic',$row['name'],$row['name']);
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
				  $url=site_url('pic/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.$numx.'&start='.($i+1);
			      exit("</br>&nbsp;&nbsp;<b>��ͣ".Html_StopTime."��������һҳ&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
    		}else{  
    	          $url=site_url('pic/admin/html/type_save').$uri.'&nums='.($nums+1);
			      $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a target='_blank' href='".$url."'>������� �����û����ת����������...</a></b>";
            }

            //�ж��������
			if(($numx+1)>=$len2){ //ȫ�����
                $url=site_url('pic/admin/html/type_save').$uri.'&nums='.($nums+1);
				$str="&nbsp;&nbsp;<b><font color=#0000ff>��������ҳ��ȫ���������!</font>&nbsp;>>>>&nbsp;&nbsp;<a href='".site_url('pic/admin/html/type_save').$uri.'&nums='.($nums+1)."'>������� �����û����ת����������...</a></b>";
			}else{ //��ǰ����ʽ���
                $url=site_url('pic/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1);
				$str='&nbsp;&nbsp;<b>��<font color=#0000ff>'.$this->gettype($type).'����</font>�����������!&nbsp;>>>>&nbsp;&nbsp;<a href="'.site_url('pic/admin/html/type_save').$uri.'&nums='.$nums.'&numx='.($numx+1).'">������� �����û����ת����������...</a></b>';
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
				admin_msg('�������ҳδ��������~!','javascript:history.back();','no');
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
				 $sqlstr="select count(*) as count from ".CS_SqlPrefix."pic_type where yid=0 and hid=0 ".$str.$limit;
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
                   admin_msg('��������ҳȫ���������~!',site_url('pic/admin/html/show'));
			}


            //����URI
			$uri='?day='.$day.'&cid='.$cid.'&ids='.$ids.'&newid='.$newid.'&ksid='.$ksid.'&jsid='.$jsid.'&kstime='.$kstime.'&jstime='.$jstime.'&pagesize='.$pagesize.'&pagejs='.$pagejs.'&datacount='.$datacount;

			//���¶���ģ��·��
			$this->load->get_templates('pic',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
			echo '&nbsp;&nbsp;<b>���ڿ�ʼ�����������,��<font color=red>'.$pagejs.'</font>�����ɣ���ǰ��<font color=red>'.$page.'</font>��</b><br/>';

            $sql_string="select * from ".CS_SqlPrefix."pic_type where yid=0 and hid=0 ".$str." order by id desc";
            $sql_string.=' limit '. $pagesize*($page-1) .','. $pagesize;
			$query = $this->db->query($sql_string);

			//��ȡ����ҳ�Ƿ���Ҫ����
			$html=config('Html_Uri','pic');

            foreach ($query->result_array() as $row) {
                  
				  $id=$row['id'];
                  //��ȡ��̬·��
				  $Htmllinks=LinkUrl('show','id',$row['id'],0,'pic');
				  //ת��������·��
				  $Htmllink=adminhtml($Htmllinks,'pic');

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

				  $arr['cid']=getChild($row['cid']);
				  $arr['uid']=$row['uid'];
				  $arr['tags']=$rows['tags'];
				  $arr['sid']=$row['id'];
				  //װ��ģ�岢���
	        	  $Mark_Text=$this->CsdjTpl->plub_show('pic',$row,$arr,TRUE,$skins,$row['name'],$row['name']);
				  //����
				  $Mark_Text=str_replace("[pic:pl]",get_pl('pic',$id),$Mark_Text);
				  //�����ַ������
			      $Mark_Text=str_replace("[pic:link]",LinkUrl('show','id',$row['id'],1,'pic'),$Mark_Text);
			      $Mark_Text=str_replace("[pic:classlink]",LinkUrl('lists','id',$row['cid'],1,'pic'),$Mark_Text);
			      $Mark_Text=str_replace("[pic:classname]",$this->CsdjDB->getzd('pic_list','name',$row['cid']),$Mark_Text);
				  //��ȡ����ƪ
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
				  //��̬����
				  $Mark_Text=str_replace("[pic:hits]","<script src='".hitslink('hits/dt/hits/'.$id,'pic')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[pic:yhits]","<script src='".hitslink('hits/dt/yhits/'.$id,'pic')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[pic:zhits]","<script src='".hitslink('hits/dt/zhits/'.$id,'pic')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[pic:rhits]","<script src='".hitslink('hits/dt/rhits/'.$id,'pic')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[pic:dhits]","<script src='".hitslink('hits/dt/dhits/'.$id,'pic')."'></script>",$Mark_Text);
				  $Mark_Text=str_replace("[pic:chits]","<script src='".hitslink('hits/dt/chits/'.$id,'pic')."'></script>",$Mark_Text);
			      //��ȡ��ǰ�������
				  $rows=$this->db->query("Select id from ".CS_SqlPrefix."pic where sid=".$id." and hid=0 and yid=0")->result_array();
				  $pcount=$rows[0]['count'];
			      $Mark_Text=str_replace("[pic:count]",$pcount,$Mark_Text);
			      //��һ��ͼƬ
			      $rowp=$this->db->query("Select pic,content from ".CS_SqlPrefix."pic where sid=".$id." and hid=0 and yid=0 order by id desc limit 1")->row();
                  $pics=($rowp)?$rowp->pic:'';
                  $content=($rowp)?$rowp->content:'';
			      $Mark_Text=str_replace("[pic:url]",piclink('pic',$pics),$Mark_Text);
			      $Mark_Text=str_replace("[pic:content]",$content,$Mark_Text);
				  //��������
			      $Mark_Text=hits_js($Mark_Text,hitslink('hits/ids/'.$id,'pic'));
                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>�������:<font color=red>".$row['name']."</font>�ɹ�:<a href=".$Htmllinks." target=_blank>".$Htmllinks."</a></font><br/>";
                  ob_flush();flush();

			}
            if(!empty($ids)){
                $url='javascript:history.back();';
			    $str="&nbsp;&nbsp;<b>ȫ���������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}else{ 
    	        $url=site_url('pic/admin/html/show_save').$uri.'&page='.($page+1);
			    $str="&nbsp;&nbsp;<b>��ͣ".Html_StopTime."������&nbsp;>>>>&nbsp;&nbsp;<a href='".$url."'>������� �����û����ת����������...</a></b>";
			}
			echo("</br>".$str."<script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //����ҳ
	public function opt()
	{
		    //��ȡ�Զ���ģ��
			$Skins_Dir=config('Skins_Dir','pic');
			$this->load->helper('directory');
            $path=FCPATH.'plugins/pic/tpl/skins/'.$Skins_Dir;
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
			$this->load->get_templates('pic',2);

            echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
            if(!empty($path)){
              foreach ($path as $t) {
                  
				  $t=str_replace(".html","",$t);
				  $t=str_replace("opt-","",$t);
				  $Mark_Text=$this->CsdjTpl->opt($t,true);

				  $Htmllink=Web_Path.'opt/'.$t.'_pic.html';

                  //����
			      write_file(FCPATH.$Htmllink,$Mark_Text);
				  echo "&nbsp;<font style=font-size:10pt;>�����Զ���ҳ��:<font color=red>".$Htmllink."</font>�ɹ�:<a href=".$Htmllink." target=_blank>".$Htmllink."</a></font><br/>";
                  ob_flush();flush();
			  }
			}

    	    $url=site_url('pic/admin/html/opt');
			echo("</br><b>&nbsp;�Զ���ҳ��ȫ���������~!</b><script>setTimeout('updatenext();',".Html_StopTime."000);function updatenext(){location.href='".$url."';}</script>");
	}

    //��ȡ��������
	public function gettype($fid){
		    $str="ID";
            switch($fid){
                   case 'id':$str="ID";break;
                   case 'pic':$str="����ʱ��";break;
                   case 'reco':$str="�����Ƽ�";break;
                   case 'hits':$str="������";break;
                   case 'yhits':$str="������";break;
                   case 'zhits':$str="������";break;
                   case 'rhits':$str="������";break;
                   case 'yue':$str="������";break;
                   case 'zhou':$str="������";break;
                   case 'ri':$str="������";break;
			}
			return $str;
	}
}
