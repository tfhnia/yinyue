<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Vod extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('vod');
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
			$tpl='vod.html';
			//URL��ַ
		    $url='vod/index/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."vod where yid=0 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵ���Ƶ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[vod:cid]",$cid,$Mark_Text);
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
		    $url='vod/verify/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."vod where yid=1 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='������Ƶ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[vod:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ���Ƶ
	public function add()
	{
			//ģ��
			$tpl='add.html';
			//URL��ַ
		    $url='vod/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if(!$rowz || $rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷�����Ƶ~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='�ϴ���Ƶ - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('vod_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:vodsave]",spacelink('vod,save','vod'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ���Ƶ����
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('vod_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');

			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if(!$rowu || $rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷�����Ƶ~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$vod['yid']=($rowu->sid==1)?0:1;
			//ѡ���ֶ�
			$vod['cion']=intval($this->input->post('cion'));
			$vod['dcion']=intval($this->input->post('dcion'));
			$vod['text']=remove_xss(str_replace("\r\n","<br>",$_POST['text']));
			$vod['pic']=$this->input->post('pic', TRUE, TRUE);
			$vod['tags']=$this->input->post('tags', TRUE, TRUE);
			$vod['daoyan']=$this->input->post('daoyan', TRUE, TRUE);
			$vod['zhuyan']=$this->input->post('zhuyan', TRUE, TRUE);
			$vod['yuyan']=$this->input->post('yuyan', TRUE, TRUE);
			$vod['diqu']=$this->input->post('diqu', TRUE, TRUE);
			$vod['year']=$this->input->post('year', TRUE, TRUE);
			$vod['info']=$this->input->post('info', TRUE, TRUE);
			$vod['uid']=$_SESSION['cscms__id'];
			$vod['addtime']=time();
			$down=$this->input->post('down', TRUE, TRUE);
			$durl=$this->input->post('durl', TRUE, TRUE);

            //�����ֶ�
			$vod['name']=$this->input->post('name', TRUE, TRUE);
			$vod['cid']=intval($this->input->post('cid'));
			$play=$this->input->post('play', TRUE, TRUE);
			$purl=$this->input->post('purl', TRUE, TRUE);

            //�������ֶ�
			if($vod['cid']==0) msg_url('��ѡ����Ƶ����~!','javascript:history.back();');
			if(empty($vod['name'])) msg_url('��Ƶ���Ʋ���Ϊ��~!','javascript:history.back();');
			if(empty($play)) msg_url('��Ƶ������Դ����Ϊ��~!','javascript:history.back();');
			if(empty($purl)) msg_url('��Ƶ���ŵ�ַ����Ϊ��~!','javascript:history.back();');

			//���ŵ�ַ���
			if($play!='flv' && $play!='media' && $play!='swf'){
			    if(substr($purl,0,7)!='http://') msg_url('��Ƶ���ŵ�ַ����ȷ~!','javascript:history.back();');
			    $arr=caiji($purl,1);
				$form=$arr['laiy'];
				$purl=$arr['url'];
				if(empty($vod['pic'])) $vod['pic']=$arr['pic'];
				$vod['purl']='��01��$'.$purl.'$'.$form;
			}else{
				$vod['purl']='��01��$'.$purl.'$'.$play;
			}

			//���ص�ַ���
			if(!empty($down) && !empty($durl)){
			    $vod['durl']='��01��$'.$durl.'$'.$down;
			}

			$singer=$this->input->post('singer', TRUE, TRUE);
			//�жϸ����Ƿ����
			if(!empty($singer)){
			     $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
				 if($row){
                       $vod['singerid']=$row->id;
				 }
			}

            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('vod',$vod);
			if(intval($did)==0){
				 msg_url('��Ƶ����ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}

            //�ݻ�token
            get_token('vod_token',2);

			//���Ӷ�̬
		    $dt['dir'] = 'vod';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $did;
		    $dt['yid'] = $vod['yid'];
		    $dt['title'] = '��������Ƶ';
		    $dt['name'] = $vod['name'];
		    $dt['link'] = linkurl('show','id',$did,1,'vod');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);

			//�������ˣ������Ա������Ӧ��ҡ�����
			if($vod['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ������Ƶ�����ɹ�~!',spacelink('vod','vod'));
			}else{
				 msg_url('��ϲ������Ƶ�����ɹ�,��ȴ�����Ա���~!',spacelink('vod/verify','vod'));
			}
	}
}

