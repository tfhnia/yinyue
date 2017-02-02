<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-16
 */
class Singer extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('singer');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

	public function index()
	{
            $sort = $this->input->get_post('sort',true);
            $desc = $this->input->get_post('desc',true);
            $cid  = intval($this->input->get_post('cid'));
            $yid  = intval($this->input->get_post('yid'));
			$reco = intval($this->input->get_post('reco'));
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

	        $data['page'] = $page;
	        $data['sort'] = $sort;
	        $data['cid'] = $cid;
	        $data['yid'] = $yid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;
	        $data['reco'] = $reco;
			if(empty($sort)) $sort="addtime";

            $sql_string = "SELECT id,name,pic,hits,hid,reco,cid,yid,addtime FROM ".CS_SqlPrefix."singer where 1=1";
			if($yid==1){
				 $sql_string.= " and yid=0";
			}
			if($yid==2){
				 $sql_string.= " and yid=1";
			}
			if($yid==3){
				 $sql_string.= " and hid=1";
			}else{
				 $sql_string.= " and hid=0";
			}
			if($cid>0){
	             $sql_string.= " and cid=".$cid."";
			}
			if(!empty($key)){
				 if($zd=='id'){
				     $sql_string.= " and id='".intval($key)."'";
				 }else{
				    $sql_string.= " and ".$zd." like '%".$key."%'";
				 }
			}
			if($reco>0){
	             $sql_string.= " and reco=".$reco."";
			}
	        $sql_string.= " order by ".$sort." desc";
            $count_sql = str_replace('id,name,pic,hits,hid,reco,cid,yid,addtime','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('singer/admin/singer')."?yid=".$yid."&zd=".$zd."&key=".$key."&cid=".$cid."&sort=".$sort."&reco=".$reco;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['singer'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('singer.html',$data);
	}

    //�Ƽ�����������
	public function init()
	{
            $ac  = $this->input->get_post('ac',true);
            $id   = intval($this->input->get_post('id'));
            $sid  = intval($this->input->get_post('sid'));
            if($ac=='zt'){ //����
                  $edit['yid']=$sid;
				  $str=($sid==0)?'<a title="�������" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=1\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/ok.gif" /></a>':'<a title="����������" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=0\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/no.gif" /></a>';
			}elseif($ac=='tj'){  //�Ƽ�
                  $edit['reco']=$sid;
		          $str='<a title="���ȡ���Ƽ�" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=0\',\'tj\','.$id.');"><font color="#ff0033">��</font></a>';
                  for($i=1;$i<=$sid;$i++){
                      $str.='<a title="�Ƽ���'.$i.'��" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid='.$i.'\',\'tj\','.$id.');">��</a>';
                  }
                  for($j=$sid+1;$j<=5;$j++){
                      $str.='<a title="�Ƽ���'.$j.'��" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid='.$j.'\',\'tj\','.$id.');">��</a>';
                  }
			}
            $this->CsdjDB->get_update('singer',$id,$edit);
            die($str);
	}

    //�����������޸�
	public function edit()
	{
            $id   = intval($this->input->get('id'));
			if($id==0){
                $data['id']=0;
                $data['cid']=0;
                $data['yid']=0;
                $data['reco']=0;
                $data['name']='';
                $data['pic']='';
                $data['color']='';
                $data['bname']='';
                $data['hits']=0;
                $data['yhits']=0;
                $data['zhits']=0;
                $data['rhits']=0;
                $data['content']='';
                $data['tags']='';
                $data['nichen']='';
                $data['sex']='��';
                $data['nat']='�й�';
                $data['yuyan']='����';
                $data['city']='����';
                $data['sr']='����';
                $data['xingzuo']='����';
                $data['height']='����';
                $data['weight']='����';
                $data['title']='';
                $data['keywords']='';
                $data['description']='';
			}else{
                $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."singer where id=".$id."")->row(); 
			    if(!$row) admin_msg('������¼������~!','javascript:history.back();','no');  //��¼������

                $data['id']=$row->id;
                $data['cid']=$row->cid;
                $data['yid']=$row->yid;
                $data['reco']=$row->reco;
                $data['name']=$row->name;
                $data['pic']=$row->pic;
                $data['color']=$row->color;
                $data['bname']=$row->bname;
                $data['hits']=$row->hits;
                $data['yhits']=$row->yhits;
                $data['zhits']=$row->zhits;
                $data['rhits']=$row->rhits;
                $data['content']=$row->content;
                $data['tags']=$row->tags;
                $data['nichen']=$row->nichen;
                $data['sex']=$row->sex;
                $data['nat']=$row->nat;
                $data['yuyan']=$row->yuyan;
                $data['city']=$row->city;
                $data['sr']=$row->sr;
                $data['xingzuo']=$row->xingzuo;
                $data['height']=$row->height;
                $data['weight']=$row->weight;
                $data['title']=$row->title;
                $data['keywords']=$row->keywords;
                $data['description']=$row->description;
			}
            $this->load->view('singer_edit.html',$data);
	}

    //���ֱ���
	public function save()
	{
            $id   = intval($this->input->post('id'));
            $name = $this->input->post('name',true);
			$tags = $this->input->post('tags',true);
            $content = remove_xss($this->input->post('content'));
            $addtime = $this->input->post('addtime',true);
            $data['cid']=intval($this->input->post('cid'));

            if(empty($name)||empty($data['cid'])){
                   admin_msg('��Ǹ���������ơ����಻��Ϊ��~!','javascript:history.back();','no');
			}
			//�Զ���ȡTAGS��ǩ
            if(empty($tags)){
			     $tags = gettag($name,$content);
			}
            $data['yid']=intval($this->input->post('yid'));
            $data['reco']=intval($this->input->post('reco'));
            $data['name']=$name;
            $data['tags']=$tags;
            $data['pic']=$this->input->post('pic',true);
            $data['color']=$this->input->post('color',true);
            $data['bname']=$this->input->post('bname',true);
            $data['hits']=intval($this->input->post('hits'));
            $data['yhits']=intval($this->input->post('yhits'));
            $data['zhits']=intval($this->input->post('zhits'));
            $data['rhits']=intval($this->input->post('rhits'));
            $data['nichen']=$this->input->post('nichen',true);
            $data['sex']=$this->input->post('sex',true);
            $data['nat']=$this->input->post('nat',true);
            $data['yuyan']=$this->input->post('yuyan',true);
            $data['city']=$this->input->post('city',true);
            $data['sr']=$this->input->post('sr',true);
            $data['xingzuo']=$this->input->post('xingzuo',true);
            $data['height']=$this->input->post('height',true);
            $data['weight']=$this->input->post('weight',true);
            $data['content']=$content;
            $data['title']=$this->input->post('title',true);
            $data['keywords']=$this->input->post('keywords',true);
            $data['description']=$this->input->post('description',true);

			if($id==0){ //����
				 $data['addtime']=time();
                 $this->CsdjDB->get_insert('singer',$data);
			}else{
			     if($addtime=='ok') $data['addtime']=time();
                 $this->CsdjDB->get_update('singer',$id,$data);
			}
            admin_msg('��ϲ���������ɹ�~!',site_url('singer/admin/singer'),'ok');  //�����ɹ�
	}

    //����ɾ��
	public function del()
	{
            $yid = intval($this->input->get('yid'));
            $ids = $this->input->get_post('id');
            $ac = $this->input->get_post('ac');
			//����վ
			if($ac=='hui'){
			     $result=$this->db->query("SELECT id,pic FROM ".CS_SqlPrefix."singer where hid=1")->result();
			     $this->load->library('csup');
			     foreach ($result as $row) {
                     if(!empty($row->pic)){
					    $this->csup->del($row->pic,'singer'); //ɾ��ͼƬ
				     }
					 $this->CsdjDB->get_del('singer',$row->id);
				 }
                 admin_msg('��ϲ��������վ��ճɹ�~!','javascript:history.back();','ok');  //�����ɹ�
			}
			if(empty($ids)) admin_msg('��ѡ��Ҫɾ��������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			if($yid==3){
			     $result=$this->db->query("SELECT pic FROM ".CS_SqlPrefix."singer where id in(".$idss.")")->result();
			     $this->load->library('csup');
			     foreach ($result as $row) {
                     if(!empty($row->pic)){
					    $this->csup->del($row->pic,'singer'); //ɾ��ͼƬ
				     }
				 }
			     $this->CsdjDB->get_del('singer',$ids);
                 admin_msg('��ϲ����ɾ���ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
			}else{
				 $data['hid']=1;
			     $this->CsdjDB->get_update('singer',$ids,$data);
                 admin_msg('��ϲ���������Ѿ��ƶ�������վ~!','javascript:history.back();','ok');  //�����ɹ�
			}
	}

    //���ֻ�ԭ
	public function hy()
	{
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('��ѡ��Ҫ��ԭ������~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$data['hid']=0;
			$this->CsdjDB->get_update('singer',$ids,$data);
            admin_msg('��ϲ�������ݻ�ԭ�ɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}

    //ͬ��Զ��ͼƬ������
	public function downpic()
	{
            $page = intval($this->input->get('page'));
            $pagejs = intval($this->input->get('pagejs'));

            $sql_string = "SELECT id,pic FROM ".CS_SqlPrefix."singer where hid=0 and yid=0 and Lower(Left(pic,7))='http://' order by addtime desc";
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            if($page > $pagejs || $total==0){
                   admin_msg('��ϲ��������Զ��ͼƬȫ��ͬ�����~!',site_url('singer/admin/singer'),'ok');  //�������
			}

            if($page==0) $page = 1;
	        $per_page = 20; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
            if($total<$per_page){
               $per_page=$total;
            }
			if($pagejs==0) $pagejs=$totalPages;
            $sql_string.=' limit 20';
	        $query = $this->db->query($sql_string); 

			//����Ŀ¼
			if(UP_Mode==1 && UP_Pan!=''){
			    $pathpic = UP_Pan.'/attachment/singer/'.date('Ym').'/'.date('d').'/';
				$pathpic = str_replace("//","/",$pathpic);
			}else{
			    $pathpic = FCPATH.'attachment/singer/'.date('Ym').'/'.date('d').'/';
			}
			if (!is_dir($pathpic)) {
                mkdirss($pathpic);
            }

			$this->load->library('watermark');
            $this->load->library('csup');

            echo '<LINK href="'.Web_Path.'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
	        echo "<div style='font-size:14px;'>&nbsp;&nbsp;&nbsp;<b>���ڿ�ʼͬ����<font style='color:red; font-size:12px; font-style:italic'>".$page."</font>ҳ����<font style='color:red; font-size:12px; font-style:italic'>".$pagejs."</font>ҳ��ʣ<font style='color:red; font-size:12px; font-style:italic'>".$totalPages."</font>ҳ</b><br><br>";
           
            foreach ($query->result() as $row) {
				ob_end_flush();//�رջ��� 
				$up='no';
				if(!empty($row->pic)){
                       $picdata=htmlall($row->pic);
					   $file_ext = strtolower(trim(substr(strrchr($row->pic, '.'), 1)));
                       if($file_ext!='jpg' && $file_ext!='png' && $file_ext!='gif'){
					       $file_ext = 'jpg';
					   }
                       //���ļ���
                       $file_name=date("YmdHis") . rand(10000, 99999) . '.' . $file_ext;
			           $file_path=$pathpic.$file_name;
                       if(!empty($picdata)){
						   //����ͼƬ
                           if(write_file($file_path, $picdata)){
                                   $up='ok';
                                   //�ж�ˮӡ
                                   if(CS_WaterMark==1){
                                         $this->watermark->imagewatermark($file_path);
                                   }
					               //�ж��ϴ���ʽ
					               $res=$this->csup->up($file_path,$file_name);
					               if(!$res){
					                   $up='no';
					               }
						   }
					   }
					   $filepath=(UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$file_name : '/'.date('Ymd').'/'.$file_name;
				}
				//�ɹ�
				if($up=='ok'){
                       //�޸����ݿ�
                       $this->db->query("update ".CS_SqlPrefix."singer set pic='".$filepath."' where id=".$row->id."");
                       echo "&nbsp;&nbsp;&nbsp;&nbsp;ͬ��<font color=red>".$row->pic."</font>&nbsp;ͼƬ�ɹ�!&nbsp;&nbsp;��ͼƬ����<a href=\"".piclink('singer',$filepath)."\" target=_blank>".$file_name."</a></br>";
				}else{
                       //�޸����ݿ�
                       $this->db->query("update ".CS_SqlPrefix."singer set pic='' where id=".$row->id."");
                       echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>".$row->pic."</font>Զ��ͼƬ������!</br>";
				}
				ob_flush();flush();
			}
	        echo "&nbsp;&nbsp;&nbsp;&nbsp;��".$page."ҳͼƬͬ�����,��ͣ3������ͬ��������������<script language='javascript'>setTimeout('ReadGo();',".(3000).");function ReadGo(){location.href='".site_url('singer/admin/singer/downpic')."?page=".($page+1)."&pagejs=".$pagejs."';}</script></div>";
	}

    //����ת�Ʒ���
	public function zhuan()
	{
            $cid = intval($this->input->get('cid'));
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('��ѡ��Ҫת�Ƶ�����~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$data['cid']=$cid;
			$this->CsdjDB->get_update('singer',$ids,$data);
            admin_msg('��ϲ���������Ѿ�ת�Ƴɹ�~!','javascript:history.back();','ok');  //�����ɹ�
	}
}
