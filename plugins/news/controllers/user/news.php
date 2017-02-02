<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class News extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('news');
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
			$tpl='news.html';
			//URL��ַ
		    $url='news/index/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."news where yid=0 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�ҵ����� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[news:cid]",$cid,$Mark_Text);
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
		    $url='news/verify/'.$cid;
            $sqlstr = "select {field} from ".CS_SqlPrefix."news where yid=1 and uid=".$_SESSION['cscms__id'];
            if($cid>0){
				$cids=getChild($cid);
                $sqlstr.= " and cid in(".$cids.")";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�������� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$cid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[news:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//��������
	public function add()
	{
			//ģ��
			$tpl='add.html';
			//URL��ַ
		    $url='news/add';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];

			//��ⷢ��Ȩ��
			$rowz=$this->CsdjDB->get_row('userzu','aid,sid',$row['zid']);
			if(!$rowz || $rowz->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷�������~!','javascript:history.back();');
			}
			
			//װ��ģ��
			$title='����Ͷ�� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token('news_token'),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:newssave]",spacelink('news,save','news'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

	//�ϴ����±���
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('news_token',1,$token)) msg_url('�Ƿ��ύ~!','javascript:history.back();');
			//��ⷢ��Ȩ��
			$zuid=getzd('user','zid',$_SESSION['cscms__id']);
			$rowu=$this->CsdjDB->get_row('userzu','aid,sid',$zuid);
			if(!$rowu || $rowu->aid==0){
                 msg_url('�����ڻ�Ա��û��Ȩ�޷�������~!','javascript:history.back();');
			}
			//��ⷢ�������Ƿ���Ҫ���
			$news['yid']=($rowu->sid==1)?0:1;
			//ѡ���ֶ�
			$news['cion']=intval($this->input->post('cion'));
			$news['pic']=$this->input->post('pic', TRUE, TRUE);
			$news['tags']=$this->input->post('tags', TRUE, TRUE);
			$news['info']=$this->input->post('info', TRUE, TRUE);
			$news['uid']=$_SESSION['cscms__id'];
			$news['addtime']=time();
            //�����ֶ�
			$news['name']=$this->input->post('name', TRUE, TRUE);
			$news['cid']=intval($this->input->post('cid'));
			$news['content']=remove_xss($this->input->post('content'));
            //�������ֶ�
			if($news['cid']==0) msg_url('��ѡ�����·���~!','javascript:history.back();');
			if(empty($news['name'])) msg_url('�������Ʋ���Ϊ��~!','javascript:history.back();');
			if(empty($news['content'])) msg_url('�������ݲ���Ϊ��~!','javascript:history.back();');
            //��ȡ����
			$news['info'] = sub_str(str_checkhtml($news['content']),120);
            //���ӵ����ݿ�
            $did=$this->CsdjDB->get_insert('news',$news);
			if(intval($did)==0){
				 msg_url('���·���ʧ�ܣ����Ժ�����~!','javascript:history.back();');
			}
            //�ݻ�token
            get_token('news_token',2);
			//���Ӷ�̬
		    $dt['dir'] = 'news';
		    $dt['uid'] = $_SESSION['cscms__id'];
		    $dt['did'] = $did;
		    $dt['yid'] = $news['yid'];
		    $dt['title'] = '����������';
		    $dt['name'] = $news['name'];
		    $dt['link'] = linkurl('show','id',$did,1,'news');
		    $dt['addtime'] = time();
            $this->CsdjDB->get_insert('dt',$dt);
			//�������ˣ������Ա������Ӧ��ҡ�����
			if($news['yid']==0){
			     $addhits=getzd('user','addhits',$_SESSION['cscms__id']);
				 if($addhits<User_Nums_Add){
                     $this->db->query("update ".CS_SqlPrefix."user set cion=cion+".User_Cion_Add.",jinyan=jinyan+".User_Jinyan_Add.",addhits=addhits+1 where id=".$_SESSION['cscms__id']."");
				 }
				 msg_url('��ϲ�������·����ɹ�~!',spacelink('news','news'));
			}else{
				 msg_url('��ϲ�������·����ɹ�,��ȴ�����Ա���~!',spacelink('news/verify','news'));
			}
	}
}

