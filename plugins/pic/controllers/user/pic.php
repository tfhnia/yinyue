<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Pic extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('pic');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	        $this->CsdjUser->User_Login();
			$this->load->helper('string');
	}

    //����ͼƬ
	public function index()
	{
		    $cid=intval($this->uri->segment(4)); //����ID
		    $yid=intval($this->uri->segment(5)); //���ID
		    $page=intval($this->uri->segment(6)); //��ҳ
			//ģ��
			$tpl='pic.html';
			//URL��ַ
		    $url='pic/index/'.$cid.'/'.$yid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."pic where yid=".$yid." and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵ�ͼƬ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[pic:cid]",$cid,$Mark_Text);
			$Mark_Text=str_replace("[pic:yid]",$yid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //����������
	public function type()
	{
		    $cid=intval($this->uri->segment(4)); //����ID
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pic-type.html';
			//URL��ַ
		    $url='pic/type/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."pic_type where yid=0 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵ���� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[pic:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //�����
	public function verify()
	{
		    $cid=intval($this->uri->segment(4)); //����ID
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pic-verify.html';
			//URL��ַ
		    $url='pic/verify/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."pic_type where yid=1 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='������� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[pic:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ����
	public function addtype()
	{
			//ģ��
			$tpl='add-type.html';
			//URL��ַ
		    $url='pic/addtype';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if(!$rowz || $rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ���������~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='������� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('pic_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:pictypesave]",spacelink('pic,typesave','pic'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//������ᱣ��
	public function typesave()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('pic_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');

			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if(!$rowu || $rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ���������~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$pic['yid']=($rowu->sid==1)?0:1;
			//ѡ���ֶ�
			$pic['tags']=$this->input->post('tags', TRUE, TRUE);
			$pic['uid']=$_SESSION['cscms__id'];
			$pic['addtime']=time();

            //�����ֶ�
			$pic['name']=$this->input->post('name', TRUE, TRUE);
			$pic['cid']=intval($this->input->post('cid'));
			$pic['pic']=$this->input->post('pic', TRUE, TRUE);

            //�������ֶ�
			if($pic['cid']==0) msg_url('��ѡ��������~!','javascript:history.back();');
			if(empty($pic['name'])) msg_url('�����ⲻ��Ϊ��~!','javascript:history.back();');
			if(empty($pic['pic'])) msg_url('�����治��Ϊ��~!','javascript:history.back();');
			$singer=$this->input->post('singer', TRUE, TRUE);

			//�жϸ����Ƿ����
			if(!empty($singer)){
			     $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
				 if($row){
                       $pic['singerid']=$row->id;
				 }
			}

            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('pic_type',$pic);
			if(intval($did)==0){
				 msg_url('�������ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}

            //�ݻ�token
            get_token('pic_token',2);
			//���Ӷ�̬
		    $dt['dir'] = 'pic';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $did;
		    $dt['yid'] = $pic['yid'];
		    $dt['title'] = '���������';
		    $dt['name'] = $pic['name'];
		    $dt['link'] = linkurl('show','id',$did,1,'pic');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);
			//�������ˣ������Ա������Ӧ��ҡ�����
			if($pic['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ������������ɹ�~!',spacelink('pic,type','pic'));
			}else{
				 msg_url('��ϲ������������ɹ�,��ȴ�����Ա���~!',spacelink('pic/verify','pic'));
			}
	}

    //�ϴ�ͼƬ
	public function add()
	{
		    $id=intval($this->uri->segment(4));
			$name='';
			if($id>0){
			    $rowc=$this->CsdjDB->get_row('pic_type','uid,name',$id);
				if(!$rowc || $rowc->uid!=$_SESSION['cscms__id']) msg_url('��᲻����~!','javascript:history.back();');
				$name=$rowc->name;
			}
			//ģ��
			$tpl='add.html';
			//URL��ַ
		    $url='pic/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if(!$rowz || $rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ���������~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='�ϴ�ͼƬ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			$Mark_Text=str_replace("[pic:sid]",$id,$Mark_Text);
			$Mark_Text=str_replace("[pic:name]",$name,$Mark_Text);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('pics_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:picsave]",spacelink('pic,save','pic'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ�ͼƬ����
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('pics_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');

			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if(!$rowu || $rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ���ϴ�ͼƬ~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$pic['yid']=($rowu->sid==1)?0:1;
			//ѡ���ֶ�
			$pic['content']=remove_xss(str_replace("\r\n","<br>",$_POST['content']));
			$pic['uid']=$_SESSION['cscms__id'];
			$pic['addtime']=time();
			$name=$this->input->post('name', TRUE, TRUE);
            //�����ֶ�
			$pic['sid']=intval($this->input->post('sid'));
			$pic['cid']=intval($this->input->post('cid'));
			$pic['pic']=$this->input->post('pic', TRUE, TRUE);
            //�������ֶ�
			if($pic['cid']==0) msg_url('��ѡ��ͼƬ����~!','javascript:history.back();');
			if($pic['sid']==0) msg_url('��ѡ��ͼƬ�������~!','javascript:history.back();');
			if(empty($pic['pic'])) msg_url('ͼƬ��ַ����Ϊ��~!','javascript:history.back();');
            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('pic',$pic);
			if(intval($did)==0){
				 msg_url('ͼƬ�ϴ�ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}
            //�ݻ�token
            get_token('pics_token',2);
			//���Ӷ�̬
		    $dt['dir'] = 'pic';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $pic['sid'];
		    $dt['yid'] = $pic['yid'];
		    $dt['title'] = '�ϴ���ͼƬ��'.$name;
		    $dt['name'] = $name;
		    $dt['link'] = linkurl('show','id',$pic['sid'],1,'pic');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);
			//�������ˣ������Ա������Ӧ��ҡ�����
			if($pic['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ����ͼƬ�ϴ��ɹ�~!',spacelink('pic','pic'));
			}else{
				 msg_url('��ϲ����ͼƬ�ϴ��ɹ�,��ȴ�����Ա���~!',spacelink('pic','pic').'/index/0/1');
			}
	}

    //ɾ����Ϣ
	public function del()
	{
		    $id=intval($this->uri->segment(4));
			if($id==0){ //ѡ��ɾ��
				$id=$this->input->post('id', TRUE);
				if(empty($id)) msg_url('��ѡ��Ҫɾ����ͼƬ~!','javascript:history.back();');
				$ids=implode(',', $id);
                $this->db->query("delete from ".CS_SqlPrefix."pic where uid=".$_SESSION['cscms__id']." and id in(".$ids.")");
			}else{ //��ID
                $this->db->query("delete from ".CS_SqlPrefix."pic where uid=".$_SESSION['cscms__id']." and id=".$id."");
			}
            msg_url('ͼƬɾ���ɹ�~!',$_SERVER['HTTP_REFERER']);
	}

    //ѡ������б�
	public function res()
	{
		    $sid=intval($this->uri->segment(4)); //��ҳ
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pic-res.html';
			//URL��ַ
		    $url='pic/res/'.$sid;
            $sqlstr = "SELECT * FROM ".CS_SqlPrefix."pic_type where yid=0 and hid=0 and uid=".$_SESSION['cscms__id'];
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='ѡ����� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[pic:sid]",$sid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}
}

