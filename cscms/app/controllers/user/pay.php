<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-30
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->CsdjUser->User_Login();
			$this->load->helper('string');
			$this->lang->load('user');
	}

    //��ֵ
	public function index()
	{
			//ģ��
			$tpl='pay.html';
			//URL��ַ
		    $url='pay/index';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_01');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//��ֵ�ύ��ַ
			$Mark_Text=str_replace("[user:paysave]",spacelink('pay/save'),$Mark_Text);
			$Mark_Text=str_replace("[user:alipay]",CS_Alipay,$Mark_Text);//֧��������
			$Mark_Text=str_replace("[user:tenpay]",CS_Tenpay,$Mark_Text);//�Ƹ�ͨ����
			$Mark_Text=str_replace("[user:wypay]",CS_Wypay,$Mark_Text);//�������߿���
			$Mark_Text=str_replace("[user:kqpay]",CS_Kqpay,$Mark_Text);//��Ǯ����
			$Mark_Text=str_replace("[user:ybpay]",CS_Ybpay,$Mark_Text);//�ױ�֧������
			$Mark_Text=str_replace("[user:cspay]",CS_Cspay,$Mark_Text);//�ٷ�֧������
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //��ֵ����
	public function save()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('pay_06'),'javascript:history.back();');

			$rmb=intval($this->input->post('rmb'));  //��ֵ���
			$type=$this->input->post('type',true,true);  //��ֵ��ʽ
			if($rmb<1 || $rmb>99999){
                  msg_url(L('pay_02'),'javascript:history.back();');
			}
			if(empty($type)){
                  msg_url(L('pay_03'),'javascript:history.back();');
			}
			//��¼����
			$add['dingdan'] = date('Ymd').time().random_string('numeric',5);
			$add['type'] = $type;
			$add['rmb'] = $rmb;
			$add['uid'] = $_SESSION['cscms__id'];
			$add['ip'] = getip();
			$add['addtime'] = time();
			$ids=$this->CsdjDB->get_insert('pay',$add);
			get_token('token',2);
			if($ids){
				  //ת����Ӧ֧��ƽ̨
				  exit("<script>window.location='".site_url('pay/'.$type.'/index/'.$ids)."';</script>");
			}else{
                  msg_url(L('pay_04'),'javascript:history.back();');
			}
	}

    //��ֵ����ֵ
	public function card()
	{
			//ģ��
			$tpl='pay-card.html';
			//URL��ַ
		    $url='pay/card';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_05');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//��ֵ�ύ��ַ
			$Mark_Text=str_replace("[user:cardsave]",spacelink('pay/cardsave'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //��ֵ����ֵ
	public function cardsave()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('pay_06'),'javascript:history.back();');

			$card=$this->input->post('card',true,true);  //����
			$pass=$this->input->post('pass',true,true);  //����
			if(empty($card) || empty($pass)){
                 msg_url(L('pay_07'),'javascript:history.back();');
			}
			//�жϳ�ֵ���Ƿ����
			$row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."paycard where card='".$card."' and pass='".$pass."'")->row();
			if(!$row){
                 msg_url(L('pay_08'),'javascript:history.back();');
			}
			if($row->uid>0){
                 msg_url(L('pay_09'),'javascript:history.back();');
			}
			$edit['uid']=$_SESSION['cscms__id'];
			$edit['usertime']=time();
            $this->CsdjDB->get_update('paycard',$row->id,$edit);
			//���ӽ�Ǯ
			$this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$_SESSION['cscms__id']."");
			//����֪ͨ
			$add['uida']=$_SESSION['cscms__id'];
			$add['uidb']=0;
			$add['name']=L('pay_10');
			$add['neir']=L('pay_11',array($row->rmb));
			$add['addtime']=time();
            $this->CsdjDB->get_insert('msg',$add);
            msg_url(L('pay_12',array($row->rmb)),spacelink('pay/card'));
	}

    //����
	public function group()
	{
			//ģ��
			$tpl='group.html';
			//URL��ַ
		    $url='pay/upgrade';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_13');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//��ֵ�ύ��ַ
			$Mark_Text=str_replace("[user:groupsave]",spacelink('pay/groupsave'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //������Ա��
	public function groupsave()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('pay_06'),'javascript:history.back();');

			$zid=intval($this->input->post('zid'));  //��ID
			$type=intval($this->input->post('type'));  //��ʽ
			$times=intval($this->input->post('times')); //ʱ��
			if($zid==0 || $type==0 || $times<1 || $times>99999){
                 msg_url(L('pay_14'),'javascript:history.back();');
			}
			$row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."userzu where id=".$zid."")->row();
			if(!$row){
                 msg_url(L('pay_15'),'javascript:history.back();');
			}
			if($type==3){ //����
                 $cion=$row->cion_y;
				 $zutime=time()+86400*365*$times;
			}elseif($type==2){ //����
                 $cion=$row->cion_m;
				 $zutime=time()+86400*30*$times;
			}else{ //����
                 $cion=$row->cion_d;
				 $zutime=time()+86400*$times;
			}
			//�ܽ��
			$zcion=$cion*$times;
			//�жϽ���Ƿ�
			$ucion=getzd('user','cion',$_SESSION['cscms__id']);
			if($ucion < $zcion){
                 msg_url(L('pay_16'),'javascript:history.back();');
			}
			//�ж�ԭ���Ƿ�Ϊ�߼���Ա
			$yzutime=getzd('user','zutime',$_SESSION['cscms__id']);
			if($yzutime>time()){
                $zutime=$zutime+($yzutime-time());
			}
            //�޸����
			$this->db->query("update ".CS_SqlPrefix."user set cion=cion-".$zcion.",zid=".$zid.",zutime=".$zutime." where id=".$_SESSION['cscms__id']."");
			//д�����Ѽ�¼
			$add2['title']='����Ϊ'.$row->name.'���Ա';
			$add2['uid']=$_SESSION['cscms__id'];
			$add2['dir']='user';
			$add2['nums']=$zcion;
			$add2['ip']=getip();
            $add2['addtime']=time();
			$this->CsdjDB->get_insert('spend',$add2);
			//����֪ͨ
			$add['uida']=$_SESSION['cscms__id'];
			$add['uidb']=0;
			$add['name']=L('pay_17');
			$add['neir']=L('pay_18',array($row->name));
			$add['addtime']=time();
            $this->CsdjDB->get_insert('msg',$add);
			get_token('token',2);
            msg_url(L('pay_19',array($row->name)),spacelink('pay/group'));
	}

    //�һ�
	public function change()
	{
			//ģ��
			$tpl='change.html';
			//URL��ַ
		    $url='pay/change';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_20');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,1,$tpl,$title,'','',$ids,true,false);
			//token
			$Mark_Text=str_replace("[user:token]",get_token(),$Mark_Text);
			//�ύ��ַ
			$Mark_Text=str_replace("[user:changesave]",spacelink('pay/changesave'),$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //�һ����
	public function changesave()
	{
			$token=$this->input->post('token', TRUE);
			if(!get_token('token',1,$token)) msg_url(L('pay_06'),'javascript:history.back();');

			$rmb=intval($this->input->post('rmb'));
			if($rmb<1 || $rmb>99999){
                 msg_url(L('pay_21'),'javascript:history.back();');
			}
			//�ж�����Ƿ�
			$urmb=getzd('user','rmb',$_SESSION['cscms__id']);
			if($urmb < $rmb){
                 msg_url(L('pay_22',array($rmb)),'javascript:history.back();');
			}
			$cion=$rmb*User_RmbToCion;
            //�޸����
			$this->db->query("update ".CS_SqlPrefix."user set rmb=rmb-".$rmb.",cion=cion+".$cion." where id=".$_SESSION['cscms__id']."");
			//д�����Ѽ�¼
			$add2['title']=L('pay_23',array($cion));
			$add2['uid']=$_SESSION['cscms__id'];
			$add2['dir']='user';
			$add2['nums']=$rmb;
			$add2['sid']=1;
			$add2['ip']=getip();
            $add2['addtime']=time();
			$this->CsdjDB->get_insert('spend',$add2);
			//����֪ͨ
			$add['uida']=$_SESSION['cscms__id'];
			$add['uidb']=0;
			$add['name']=L('pay_24');
			$add['neir']=L('pay_25',array($rmb,$cion));
			$add['addtime']=time();
            $this->CsdjDB->get_insert('msg',$add);
			get_token('token',2);
            msg_url(L('pay_26',array($cion)),spacelink('pay/change'));
	}

    //��ֵ��¼
	public function lists()
	{
		    $pid=intval($this->uri->segment(4)); //��ҳ
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pay-list.html';
			//URL��ַ
		    $url='pay/lists/'.$pid;
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_27');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
			$sqlstr = "select * from ".CS_SqlPrefix."pay where uid=".$_SESSION['cscms__id'];
            if($pid>0){
				$pids=($pid>3)?0:$pid;
			    $sqlstr.=" and pid=".$pids;
			}
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$pid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[pay:pid]",$pid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //���Ѽ�¼
	public function spend()
	{
		    $sid=intval($this->uri->segment(4)); //��ҳ
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pay-spend.html';
			//URL��ַ
		    $url='pay/spend/'.$sid;
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_28');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
			$sqlstr = "select * from ".CS_SqlPrefix."spend where uid=".$_SESSION['cscms__id'];
            if($sid>0){
			    $sqlstr.=" and sid=".($sid-1);
			}
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$sid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[spend:sid]",$sid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //�ֳɼ�¼
	public function income()
	{
		    $sid=intval($this->uri->segment(4)); //��ҳ
		    $page=intval($this->uri->segment(5)); //��ҳ
			//ģ��
			$tpl='pay-income.html';
			//URL��ַ
		    $url='pay/income/'.$sid;
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_29');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
			$sqlstr = "select * from ".CS_SqlPrefix."income where uid=".$_SESSION['cscms__id'];
            if($sid>0){
			    $sqlstr.=" and sid=".($sid-1);
			}
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,$sid,$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[income:sid]",$sid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}

    //��ֵ����¼
	public function cardlist()
	{
		    $page=intval($this->uri->segment(4)); //��ҳ
			//ģ��
			$tpl='pay-cardlist.html';
			//URL��ַ
		    $url='pay/cardlist';
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title=L('pay_30');
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
			$sqlstr = "select * from ".CS_SqlPrefix."paycard where uid=".$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}
}
