<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Dance extends Cscms_Controller {

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
		    $cid=intval($this->uri->segment(4)); //����ID
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='dance.html';
			//URL��ַ
		    $url='dance/index/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance where yid=0 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵĸ��� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[dance:cid]",$cid,$Mark_Text);
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
			$tpl='verify.html';
			//URL��ַ
		    $url='dance/verify/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance where yid=1 and uid=".$_SESSION['cscms__id'];
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
			$Mark_Text=str_replace("[dance:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ�����
	public function add()
	{
			//ģ��
			$tpl='add.html';
			//URL��ַ
		    $url='dance/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if(!$rowz || $rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷������~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='�ϴ����� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('dance_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:dancesave]",spacelink('dance,save','dance'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ���������
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('dance_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');

			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if(!$rowu || $rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷������~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$music['yid']=($rowu->sid==1)?0:1;

            //�����ֶ�
			$music['name']=str_encode($_POST['name']);
			$music['cid']=intval($this->input->post('cid'));
			$music['purl']=$this->input->post('purl', TRUE, TRUE);

            //�������ֶ�
			if($music['cid']==0) msg_url('��ѡ���������~!','javascript:history.back();');
			if(empty($music['name'])) msg_url('�������Ʋ���Ϊ��~!','javascript:history.back();');
			if(empty($music['purl'])) msg_url('������ַ����Ϊ��~!','javascript:history.back();');

			//ѡ���ֶ�
			$music['tid']=intval($this->input->post('tid'));
			$music['cion']=intval($this->input->post('cion'));
			$music['text']=remove_xss(str_replace("\r\n","<br>",$_POST['text']));
			$music['lrc']=$this->input->post('lrc', TRUE, TRUE);
			$music['pic']=$this->input->post('pic', TRUE, TRUE);
			$music['tags']=$this->input->post('tags', TRUE, TRUE);
			$music['zc']=$this->input->post('zc', TRUE, TRUE);
			$music['zq']=$this->input->post('zq', TRUE, TRUE);
			$music['bq']=$this->input->post('bq', TRUE, TRUE);
			$music['hy']=$this->input->post('hy', TRUE, TRUE);
			$music['durl']=$music['purl'];
			$music['uid']=$_SESSION['cscms__id'];
			$music['addtime']=time();

			$singer=$this->input->post('singer', TRUE, TRUE);
			//�жϸ����Ƿ����
			if(!empty($singer)){
			     $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
				 if($row){
                       $music['singerid']=$row->id;
				 }
			}
			//��ȡ��С�����ʡ�ʱ��
			if(substr($music['purl'],0,7)!='http://' && UP_Mode==1){
				 if(UP_Pan==''){
                      $filename=FCPATH.$music['purl'];
				 }else{
                      $filename=UP_Pan.$music['purl'];
				 }
                 $this->load->library('mp3file');
		         $arr = $this->mp3file->get_metadata($filename);
				 $music['dx']=!empty($arr['Filesize'])?formatsize($arr['Filesize']):'';
                 $music['yz']=!empty($arr['Bitrate'])?$arr['Bitrate'].' Kbps':'';
                 $music['sc']=!empty($arr['Length mm:ss'])?$arr['Length mm:ss']:'';
			}
            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('dance',$music);
			if(intval($did)==0){
				 msg_url('��������ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}

            //�ݻ�token
            get_token('dance_token',2);

			//���Ӷ�̬
		    $dt['dir'] = 'dance';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $did;
		    $dt['yid'] = $music['yid'];
		    $dt['title'] = '�����˸���';
		    $dt['name'] = $music['name'];
		    $dt['link'] = linkurl('play','id',$did,1,'dance');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);

			//�������ˣ������Ա������Ӧ��ҡ�����
			if($music['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ�������������ɹ�~!',spacelink('dance','dance'));
			}else{
				 msg_url('��ϲ�������������ɹ�,��ȴ�����Ա���~!',spacelink('dance/verify','dance'));
			}
	}
}
