<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Album extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	        $this->CsdjUser->User_Login();
			$this->load->helper('string');
	}

    //�����
	public function index()
	{
		    $page=intval($this->uri->segment(4)); //��ҳ
			//ģ��
			$tpl='album.html';
			//URL��ַ
		    $url='album/index';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵ�ר�� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance_topic where yid=0 and uid=".$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //�����
	public function verify()
	{
		    $page=intval($this->uri->segment(4)); //��ҳ
			//ģ��
			$tpl='album-verify.html';
			//URL��ַ
		    $url='album/verify';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='����ר�� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance_topic where yid=1 and uid=".$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'','',$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//����ר��
	public function add()
	{
			//ģ��
			$tpl='album-add.html';
			//URL��ַ
		    $url='album/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if($rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޴���ר��~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='����ר�� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('album_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:albumsave]",spacelink('album,save','dance'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ���������
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('album_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');

			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if($rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޴���ר��~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$album['yid']=($rowu->sid==1)?0:1;

            //�����ֶ�
			$album['name']=$this->input->post('name', TRUE, TRUE);
			$album['cid']=intval($this->input->post('cid'));
			$album['pic']=$this->input->post('pic', TRUE, TRUE);
			$album['neir']=remove_xss(str_replace("\r\n","<br>",$_POST['neir']));

            //�������ֶ�
			if($album['cid']==0) msg_url('��ѡ��ר������~!','javascript:history.back();');
			if(empty($album['name'])) msg_url('ר�����Ʋ���Ϊ��~!','javascript:history.back();');
			if(empty($album['pic'])) msg_url('ר��ͼƬ����Ϊ��~!','javascript:history.back();');
			if(empty($album['neir'])) msg_url('ר�����ܲ���Ϊ��~!','javascript:history.back();');

			//ѡ���ֶ�
			$album['yuyan']=$this->input->post('yuyan', TRUE, TRUE);
			$album['diqu']=$this->input->post('diqu', TRUE, TRUE);
			$album['tags']=$this->input->post('tags', TRUE, TRUE);
			$album['fxgs']=$this->input->post('fxgs', TRUE, TRUE);
			$album['year']=$this->input->post('year', TRUE, TRUE);
			$album['uid']=$_SESSION['cscms__id'];
			$album['addtime']=time();

			$singer=$this->input->post('singer', TRUE, TRUE);
			//�жϸ����Ƿ����
			if(!empty($singer)){
			     $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
				 if($row){
                       $album['singerid']=$row->id;
				 }
			}
            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('dance_topic',$album);
			if(intval($did)==0){
				 msg_url('ר������ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}

            //�ݻ�token
            get_token('album_token',2);

			//���Ӷ�̬
		    $dt['dir'] = 'dance';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $did;
		    $dt['yid'] = $album['yid'];
		    $dt['title'] = '������ר��';
		    $dt['name'] = $album['name'];
		    $dt['link'] = linkurl('topic/show','id',$did,1,'dance');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);

			//�������ˣ������Ա������Ӧ��ҡ�����
			if($album['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ����ר�������ɹ�~!',spacelink('album','dance'));
			}else{
				 msg_url('��ϲ����ר�������ɹ�,��ȴ�����Ա���~!',spacelink('album/verify','dance'));
			}
	}
}

