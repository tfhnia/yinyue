<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-10
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wypay extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjUser');
			$this->lang->load('pay');
	}

    //����֧��
	public function index()
	{
            $this->CsdjUser->User_Login();
		    $id=(int)$this->uri->segment(4); //����ID
            if($id==0)  msg_url(L('pay_01'),spacelink('pay'));
            $row=$this->CsdjDB->get_row('pay','*',$id);
			if(!$row || $row->uid!=$_SESSION['cscms__id']){
                 msg_url(L('pay_02'),spacelink('pay'));
			}

            $v_amount    = $row->rmb;
            $v_moneytype = 'CNY';
            $v_oid       = $row->dingdan;
            $v_mid       = CS_Wypay_ID;
            $v_url       = site_url('pay/wypay/return_url');
		    $v_url2      = "[url:=".site_url('pay/wypay/notify_url')."]";
            $key         = CS_Wypay_Key;
            $text        = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;
            $md5info     = strtoupper(md5($text));

            echo '
	        <form method="post" id="form1" name="form1" action="https://Pay3.chinabank.com.cn/PayGate">
	        <input type="hidden" name="v_mid" value="'.$v_mid.'">
	        <input type="hidden" name="v_oid" value="'.$v_oid.'">
	        <input type="hidden" name="v_amount" value="'.$v_amount.'">
	        <input type="hidden" name="v_moneytype" value="'.$v_moneytype.'">
	        <input type="hidden" name="v_url" value="'.$v_url.'">
	        <input type="hidden" name="remark2" value="'.$v_url2.'">
	        <input type="hidden" name="v_md5info" value="'.$md5info.'">
	        </form><script language="javascript">document.form1.submit();</script>';
	}

	//ͬ������
	public function return_url()
	{
            $this->CsdjUser->User_Login();
		    $v_oid=$this->input->get('v_oid', TRUE,TRUE);      
		    $v_pstatus=$this->input->get('v_pstatus', TRUE,TRUE);    
		    $v_pstring=$this->input->get('v_pstring', TRUE,TRUE);   
		    $v_amount=$this->input->get('v_amount', TRUE,TRUE);  
		    $v_moneytype=$this->input->get('v_moneytype', TRUE,TRUE);   
		    $v_md5str=$this->input->get('v_md5str', TRUE,TRUE); 
			$key = CS_Wypay_Key;
		    //���¼���md5��ֵ
		    $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //ƴ�ռ��ܴ�
            //֧��״̬��֤
		    if ($v_md5str==$md5string && $v_pstatus=="20"){
                 msg_url(L('pay_07').$v_oid,spacelink('pay'));
			} else {  //��֤֧��ʧ��
                 msg_url(L('pay_09'),spacelink('pay'));
			}
	}

	//�첽����
	public function notify_url()
	{
	        /*ȡ���ز���*/
		    $v_oid=$this->input->post('v_oid', TRUE,TRUE);      
		    $v_pstatus=$this->input->post('v_pstatus', TRUE,TRUE);    
		    $v_pstring=$this->input->post('v_pstring', TRUE,TRUE);   
		    $v_amount=$this->input->post('v_amount', TRUE,TRUE);  
		    $v_moneytype=$this->input->post('v_moneytype', TRUE,TRUE);   
		    $v_md5str=$this->input->post('v_md5str', TRUE,TRUE); 
			$key = CS_Wypay_Key;
		    //���¼���md5��ֵ
		    $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //ƴ�ռ��ܴ�
            //֧��״̬��֤
		    if ($v_md5str==$md5string){
       		   if($v_pstatus=="20"){
				   $row=$this->CsdjDB->get_row('pay','*',$v_oid,'dingdan');
				   if($row && $row->pid!=1){
                        //���ӽ�Ǯ
						$this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
						//�ı�״̬
						$this->db->query("update ".CS_SqlPrefix."pay set pid=1 where id=".$row->id."");
						//����֪ͨ
						$add['uida']=$row->uid;
						$add['uidb']=0;
						$add['name']=L('pay_11');
						$add['neir']=L('pay_17',array($row->rmb,$v_oid));
						$add['addtime']=time();
            			$this->CsdjDB->get_insert('msg',$add);
				   }
			   }
			   echo "ok";
			}else{
		       echo "error";
			}
	}
}
