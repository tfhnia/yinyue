<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-10
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cspay extends Cscms_Controller {

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
	  		$pay_url		 = 'http://pay.chshcms.com/pay';		        /* CSPAY֧�������ַ */
	  		$pay_sid		 = '1';			/* ���׷�ʽ��ҵ����룬1Ϊ֧������2Ϊ�Ƹ�ͨ��3Ϊ���� */
	  		$pay_mode		 = '';
      		$body			 = L('pay_03',array($_SESSION['cscms__name']));    /* ��Ʒ�� */	
	  		$out_trade_no	 = $row->dingdan;                        /* �̻�������*/       		 
      		$total_fee	     = $row->rmb;			                /* �ܽ�� */
	  		$partner		 = CS_Cspay_ID;        /* �̻��� */	
	  		$key			 = CS_Cspay_Key;			/* �̻�����key */      
      		$return_url	     = site_url('pay/cspay/return_url');			/* ͬ�����ص�ַ */
	  		$notify_url	     = site_url('pay/cspay/notify_url');			/* �첽���ص�ַ */
      		$remark          = '';                                /* �Զ����ֶ� */

      		/* ����ǩ�� */
      		$sign_text = "pay_sid=" . $pay_sid . "&out_trade_no=" . $out_trade_no . "&total_fee=" . intval($total_fee) .
                   "&partner=" . $partner . "&key=" . $key .  "&return_url=" . $return_url . "&notify_url=" . $notify_url;

      		$sign = strtoupper(md5($sign_text));
      		/* ���ײ��� */
      		$parameter = array(
            'charset'                   => 'gbk',  
            'pay_sid'                   => $pay_sid,            
            'body'                      => $body,                      
            'out_trade_no'              => $out_trade_no,   
            'total_fee'                 => $total_fee,              
            'partner'                   => $partner,		         
            'return_url'                => $return_url,		
            'notify_url'                => $notify_url,                  
            'remark'                    => $remark,                  
            'sign'                      => $sign                         
      		);
      		$button  = '<form name="CsPayForm" method="post" style="text-align:left;" action="' . $pay_url . '" style="margin:0px;padding:0px" >';
      		foreach ($parameter AS $key=>$val)
      		{
          		$button  .= "<input type='hidden' name='$key' value='$val'/>";
      		}
	  		$formstr = $button . '</form><script>document.CsPayForm.submit();</script>';
	  		echo $formstr;
	}

	//ͬ������
	public function return_url()
	{
            $this->CsdjUser->User_Login();
	        /*ȡ���ز���*/
            $cspay_id        = intval($this->input->get('cspay_id',TRUE));        //CSPAY���׺�
            $cspay_pid       = intval($this->input->get('cspay_pid',TRUE));       //CSPAY���׽��
            $pay_sid     	 = intval($this->input->get('pay_sid',TRUE));         //���׷�ʽ��ҵ�����
            $out_trade_no    = $this->input->get('out_trade_no',TRUE,TRUE);    //������
            $total_fee   	 = $this->input->get('total_fee',TRUE,TRUE);       //���
            $remark	 	 	 = $this->input->get('remark',TRUE,TRUE);          //�Զ����ֶ�
            $sign            = $this->input->get('sign',TRUE,TRUE);            //��������ǩ��
	        $partner		 = CS_Cspay_ID;                    	
	        $key			 = CS_Cspay_Key;			    
            $return_url		 = site_url('pay/cspay/return_url');			
	        $notify_url		 = site_url('pay/cspay/notify_url');			
		
            /* �������ǩ���Ƿ���ȷ */
            $sign_text = "cspay_id=". $cspay_id ."&cspay_pid=". $cspay_pid ."&pay_sid=" . $pay_sid . "&out_trade_no=" . $out_trade_no . "&total_fee=" . $total_fee .
                     "&partner=" . $partner . "&key=" . $key .  "&return_url=" . $return_url . "&notify_url=" . $notify_url;
            $sign_md5 = strtoupper(md5($sign_text));
            //֧��״̬��֤
            if ($sign_md5 == $sign && $cspay_pid==1){  //��֤֧���ɹ�
				   $row=$this->CsdjDB->get_row('pay','*',$out_trade_no,'dingdan');
				   if($row && $row->pid!=1){
                        //���ӽ�Ǯ
						$this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
						//�ı�״̬
						$this->db->query("update ".CS_SqlPrefix."pay set pid=1 where id=".$row->id."");
						//����֪ͨ
						$add['uida']=$row->uid;
						$add['uidb']=0;
						$add['name']=L('pay_11');
						$add['neir']=L('pay_17',array($row->rmb,$out_trade_no));
						$add['addtime']=time();
            			$this->CsdjDB->get_insert('msg',$add);
				   }
                   msg_url(L('pay_07').$out_trade_no,spacelink('pay'));
			} else {  //��֤֧��ʧ��
                   msg_url(L('pay_09'),spacelink('pay'));
			}
	}

	//�첽����
	public function notify_url()
	{
	        /*ȡ���ز���*/
            $cspay_id        = intval($this->input->post('cspay_id',TRUE));        //CSPAY���׺�
            $cspay_pid       = intval($this->input->post('cspay_pid',TRUE));       //CSPAY���׽��
            $pay_sid     	 = intval($this->input->post('pay_sid',TRUE));         //���׷�ʽ��ҵ�����
            $out_trade_no    = $this->input->post('out_trade_no',TRUE,TRUE);    //������
            $total_fee   	 = $this->input->post('total_fee',TRUE,TRUE);       //���
            $remark	 	 	 = $this->input->post('remark',TRUE,TRUE);          //�Զ����ֶ�
            $sign            = $this->input->post('sign',TRUE,TRUE);            //��������ǩ��
	        $partner		 = CS_Cspay_ID;                    	
	        $key			 = CS_Cspay_Key;			    
            $return_url		 = site_url('pay/cspay/return_url');			
	        $notify_url		 = site_url('pay/cspay/notify_url');			
		
            /* �������ǩ���Ƿ���ȷ */
            $sign_text = "cspay_id=". $cspay_id ."&cspay_pid=". $cspay_pid ."&pay_sid=" . $pay_sid . "&out_trade_no=" . $out_trade_no . "&total_fee=" . $total_fee .
                     "&partner=" . $partner . "&key=" . $key .  "&return_url=" . $return_url . "&notify_url=" . $notify_url;
            $sign_md5 = strtoupper(md5($sign_text));
            if ($getcspay != 'ok') {  //֧��״̬���
				echo "NO";
			}else{
				if ($sign_md5 == $sign && $cspay_pid==1){
				   $row=$this->CsdjDB->get_row('pay','*',$out_trade_no,'dingdan');
				   if($row && $row->pid!=1){
                        //���ӽ�Ǯ
						$this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
						//�ı�״̬
						$this->db->query("update ".CS_SqlPrefix."pay set pid=1 where id=".$row->id."");
						//����֪ͨ
						$add['uida']=$row->uid;
						$add['uidb']=0;
						$add['name']=L('pay_11');
						$add['neir']=L('pay_17',array($row->rmb,$out_trade_no));
						$add['addtime']=time();
            			$this->CsdjDB->get_insert('msg',$add);
				   }
				   echo "OK";
				}else{
				   echo "NO";
				}
			}
	}

    //Զ�̲�ѯ���׽��
	public function get_cspayurl($cspay_id){
        if(empty($cspay_id)) return '';
        $url = "http://pay.chshcms.com/pay/notify_query?key=".CS_Cspay_Key."&cspay_id=".$cspay_id;
        $FileContent=@file_get_contents($url);
        if(empty($FileContent)){
           $ch = curl_init($url);
           curl_setopt($ch, CURLOPT_TIMEOUT, 1);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           $FileContent = curl_exec($ch);
           curl_close ($ch);
        }
        return $FileContent;
	}
}
