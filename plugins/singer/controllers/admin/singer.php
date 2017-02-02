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
            $totalPages = ceil($total / $per_page); // 总页数
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['singer'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //获取分页类

            $this->load->view('singer.html',$data);
	}

    //推荐、锁定操作
	public function init()
	{
            $ac  = $this->input->get_post('ac',true);
            $id   = intval($this->input->get_post('id'));
            $sid  = intval($this->input->get_post('sid'));
            if($ac=='zt'){ //锁定
                  $edit['yid']=$sid;
				  $str=($sid==0)?'<a title="点击锁定" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=1\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/ok.gif" /></a>':'<a title="点击解除锁定" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=0\',\'zt\','.$id.');"><img align="absmiddle" src="'.Web_Path.'packs/admin/images/icon/no.gif" /></a>';
			}elseif($ac=='tj'){  //推荐
                  $edit['reco']=$sid;
		          $str='<a title="点击取消推荐" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid=0\',\'tj\','.$id.');"><font color="#ff0033">×</font></a>';
                  for($i=1;$i<=$sid;$i++){
                      $str.='<a title="推荐：'.$i.'星" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid='.$i.'\',\'tj\','.$id.');">★</a>';
                  }
                  for($j=$sid+1;$j<=5;$j++){
                      $str.='<a title="推荐：'.$j.'星" href="javascript:get_cmd(\''.site_url('singer/admin/singer/init').'?sid='.$j.'\',\'tj\','.$id.');">☆</a>';
                  }
			}
            $this->CsdjDB->get_update('singer',$id,$edit);
            die($str);
	}

    //歌手新增、修改
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
                $data['sex']='男';
                $data['nat']='中国';
                $data['yuyan']='国语';
                $data['city']='暂无';
                $data['sr']='暂无';
                $data['xingzuo']='暂无';
                $data['height']='暂无';
                $data['weight']='暂无';
                $data['title']='';
                $data['keywords']='';
                $data['description']='';
			}else{
                $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."singer where id=".$id."")->row(); 
			    if(!$row) admin_msg('该条记录不存在~!','javascript:history.back();','no');  //记录不存在

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

    //歌手保存
	public function save()
	{
            $id   = intval($this->input->post('id'));
            $name = $this->input->post('name',true);
			$tags = $this->input->post('tags',true);
            $content = remove_xss($this->input->post('content'));
            $addtime = $this->input->post('addtime',true);
            $data['cid']=intval($this->input->post('cid'));

            if(empty($name)||empty($data['cid'])){
                   admin_msg('抱歉，歌手名称、分类不能为空~!','javascript:history.back();','no');
			}
			//自动获取TAGS标签
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

			if($id==0){ //新增
				 $data['addtime']=time();
                 $this->CsdjDB->get_insert('singer',$data);
			}else{
			     if($addtime=='ok') $data['addtime']=time();
                 $this->CsdjDB->get_update('singer',$id,$data);
			}
            admin_msg('恭喜您，操作成功~!',site_url('singer/admin/singer'),'ok');  //操作成功
	}

    //歌手删除
	public function del()
	{
            $yid = intval($this->input->get('yid'));
            $ids = $this->input->get_post('id');
            $ac = $this->input->get_post('ac');
			//回收站
			if($ac=='hui'){
			     $result=$this->db->query("SELECT id,pic FROM ".CS_SqlPrefix."singer where hid=1")->result();
			     $this->load->library('csup');
			     foreach ($result as $row) {
                     if(!empty($row->pic)){
					    $this->csup->del($row->pic,'singer'); //删除图片
				     }
					 $this->CsdjDB->get_del('singer',$row->id);
				 }
                 admin_msg('恭喜您，回收站清空成功~!','javascript:history.back();','ok');  //操作成功
			}
			if(empty($ids)) admin_msg('请选择要删除的数据~!','javascript:history.back();','no');
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
					    $this->csup->del($row->pic,'singer'); //删除图片
				     }
				 }
			     $this->CsdjDB->get_del('singer',$ids);
                 admin_msg('恭喜您，删除成功~!','javascript:history.back();','ok');  //操作成功
			}else{
				 $data['hid']=1;
			     $this->CsdjDB->get_update('singer',$ids,$data);
                 admin_msg('恭喜您，数据已经移动至回收站~!','javascript:history.back();','ok');  //操作成功
			}
	}

    //歌手还原
	public function hy()
	{
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('请选择要还原的数据~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$data['hid']=0;
			$this->CsdjDB->get_update('singer',$ids,$data);
            admin_msg('恭喜您，数据还原成功~!','javascript:history.back();','ok');  //操作成功
	}

    //同步远程图片到本地
	public function downpic()
	{
            $page = intval($this->input->get('page'));
            $pagejs = intval($this->input->get('pagejs'));

            $sql_string = "SELECT id,pic FROM ".CS_SqlPrefix."singer where hid=0 and yid=0 and Lower(Left(pic,7))='http://' order by addtime desc";
	        $query = $this->db->query($sql_string); 
	        $total = $query->num_rows();

            if($page > $pagejs || $total==0){
                   admin_msg('恭喜您，所有远程图片全部同步完成~!',site_url('singer/admin/singer'),'ok');  //操作完成
			}

            if($page==0) $page = 1;
	        $per_page = 20; 
            $totalPages = ceil($total / $per_page); // 总页数
            if($total<$per_page){
               $per_page=$total;
            }
			if($pagejs==0) $pagejs=$totalPages;
            $sql_string.=' limit 20';
	        $query = $this->db->query($sql_string); 

			//保存目录
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
	        echo "<div style='font-size:14px;'>&nbsp;&nbsp;&nbsp;<b>正在开始同步第<font style='color:red; font-size:12px; font-style:italic'>".$page."</font>页，共<font style='color:red; font-size:12px; font-style:italic'>".$pagejs."</font>页，剩<font style='color:red; font-size:12px; font-style:italic'>".$totalPages."</font>页</b><br><br>";
           
            foreach ($query->result() as $row) {
				ob_end_flush();//关闭缓存 
				$up='no';
				if(!empty($row->pic)){
                       $picdata=htmlall($row->pic);
					   $file_ext = strtolower(trim(substr(strrchr($row->pic, '.'), 1)));
                       if($file_ext!='jpg' && $file_ext!='png' && $file_ext!='gif'){
					       $file_ext = 'jpg';
					   }
                       //新文件名
                       $file_name=date("YmdHis") . rand(10000, 99999) . '.' . $file_ext;
			           $file_path=$pathpic.$file_name;
                       if(!empty($picdata)){
						   //保存图片
                           if(write_file($file_path, $picdata)){
                                   $up='ok';
                                   //判断水印
                                   if(CS_WaterMark==1){
                                         $this->watermark->imagewatermark($file_path);
                                   }
					               //判断上传方式
					               $res=$this->csup->up($file_path,$file_name);
					               if(!$res){
					                   $up='no';
					               }
						   }
					   }
					   $filepath=(UP_Mode==1)?'/'.date('Ym').'/'.date('d').'/'.$file_name : '/'.date('Ymd').'/'.$file_name;
				}
				//成功
				if($up=='ok'){
                       //修改数据库
                       $this->db->query("update ".CS_SqlPrefix."singer set pic='".$filepath."' where id=".$row->id."");
                       echo "&nbsp;&nbsp;&nbsp;&nbsp;同步<font color=red>".$row->pic."</font>&nbsp;图片成功!&nbsp;&nbsp;新图片名：<a href=\"".piclink('singer',$filepath)."\" target=_blank>".$file_name."</a></br>";
				}else{
                       //修改数据库
                       $this->db->query("update ".CS_SqlPrefix."singer set pic='' where id=".$row->id."");
                       echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>".$row->pic."</font>远程图片不存在!</br>";
				}
				ob_flush();flush();
			}
	        echo "&nbsp;&nbsp;&nbsp;&nbsp;第".$page."页图片同步完毕,暂停3秒后继续同步．．．．．．<script language='javascript'>setTimeout('ReadGo();',".(3000).");function ReadGo(){location.href='".site_url('singer/admin/singer/downpic')."?page=".($page+1)."&pagejs=".$pagejs."';}</script></div>";
	}

    //歌手转移分类
	public function zhuan()
	{
            $cid = intval($this->input->get('cid'));
            $ids = $this->input->get_post('id');
			if(empty($ids)) admin_msg('请选择要转移的数据~!','javascript:history.back();','no');
			if(is_array($ids)){
			     $idss=implode(',', $ids);
			}else{
			     $idss=$ids;
			}
			$data['cid']=$cid;
			$this->CsdjDB->get_update('singer',$ids,$data);
            admin_msg('恭喜您，数据已经转移成功~!','javascript:history.back();','ok');  //操作成功
	}
}
