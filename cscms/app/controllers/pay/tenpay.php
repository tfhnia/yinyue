<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-10
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tenpay extends Cscms_Controller {

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
			require_once(CSCMSPATH."pay/tenpay/RequestHandler.class.php");
        	$partner = CS_Tenpay_ID;
        	$key = CS_Tenpay_Key;
        	$return_url = site_url('pay/tenpay/return_url');
       		$notify_url = site_url('pay/tenpay/notify_url');

        	/* ����֧��������� */
        	$reqHandler = new RequestHandler();
        	$reqHandler->init();
        	$reqHandler->setKey($key);
       	    $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
        	$reqHandler->setParameter("partner", $partner);
        	$reqHandler->setParameter("out_trade_no", $row->dingdan);
        	$reqHandler->setParameter("total_fee", floor($row->rmb)."00");  //�ܽ��
        	$reqHandler->setParameter("return_url",  $return_url);
        	$reqHandler->setParameter("notify_url", $notify_url);
        	$reqHandler->setParameter("body", L('pay_03',array($_SESSION['cscms__name'])));
        	$reqHandler->setParameter("bank_type", "DEFAULT");  	  //�������ͣ�Ĭ��Ϊ�Ƹ�ͨ
        	//�û�ip
        	$reqHandler->setParameter("spbill_create_ip", getip());//�ͻ���IP
        	$reqHandler->setParameter("fee_type", "1");               //����
        	$reqHandler->setParameter("subject",L('pay_03',array($_SESSION['cscms__name'])));  //��Ʒ����
        	//ϵͳ��ѡ����
        	$reqHandler->setParameter("sign_type", "MD5");  	 	  //ǩ����ʽ��Ĭ��ΪMD5����ѡRSA
        	$reqHandler->setParameter("service_version", "1.0"); 	  //�ӿڰ汾��
        	$reqHandler->setParameter("input_charset", "GBK");   	  //�ַ���
        	$reqHandler->setParameter("sign_key_index", "1");    	  //��Կ���
        	//ҵ���ѡ����
        	$reqHandler->setParameter("attach", "");             	  //�������ݣ�ԭ�����ؾͿ�����
        	$reqHandler->setParameter("product_fee", "");        	  //��Ʒ����
        	$reqHandler->setParameter("transport_fee", "0");      	  //��������
        	$reqHandler->setParameter("time_start", date("YmdHis"));  //��������ʱ��
        	$reqHandler->setParameter("time_expire", "");             //����ʧЧʱ��
        	$reqHandler->setParameter("buyer_id", "");                //�򷽲Ƹ�ͨ�ʺ�
        	$reqHandler->setParameter("goods_tag", "");               //��Ʒ���
        	$reqHandler->setParameter("trade_mode","1");              //����ģʽ 1.��ʱ����ģʽ��2.�н鵣��ģʽ��3.��̨ѡ��
        	$reqHandler->setParameter("transport_desc","");              //����˵��
        	$reqHandler->setParameter("trans_type","1");              //��������
        	$reqHandler->setParameter("agentid","");                  //ƽ̨ID
        	$reqHandler->setParameter("agent_type","");               //����ģʽ��0.�޴���1.��ʾ������ģʽ��2.��ʾ����ģʽ��
        	$reqHandler->setParameter("seller_id","");                //���ҵ��̻���
       	    //�����URL
        	$reqUrl = $reqHandler->getRequestURL();
        	$debugInfo = $reqHandler->getDebugInfo();
        	echo '<form action="'.$reqHandler->getGateUrl().'" name="form1" method="post">';
        	$params = $reqHandler->getAllParameters();
        	foreach($params as $k => $v) {
	        	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        	}
        	echo '<script language="javascript">document.form1.submit();</script></form>';
	}

	//ͬ������
	public function return_url()
	{
            $this->CsdjUser->User_Login();
	        $partner = CS_Tenpay_ID;
            $key = CS_Tenpay_Key;
            require_once (CSCMSPATH."pay/tenpay/ResponseHandler.class.php");
            $resHandler = new ResponseHandler();
            $resHandler->setKey($key);
	        //֪ͨid
	        $notify_id = $this->input->get('notify_id',TRUE,TRUE); 
	        //�̻�������
	        $out_trade_no = $this->input->get('out_trade_no',TRUE,TRUE); 
	        //�Ƹ�ͨ������
	        $transaction_id = $this->input->get('transaction_id',TRUE,TRUE); 
	        //�����ʹ���ۿ�ȯ��discount��ֵ��total_fee+discount=ԭ�����total_fee
	        $discount = $this->input->get('discount',TRUE,TRUE); 
	        //֧�����
	        $trade_statess = $_GET['trade_state']; 
	        //����ģʽ,1��ʱ����
	        $trade_mode = $this->input->get('trade_mode',TRUE,TRUE); 
            //�ж�ǩ��
            if($resHandler->isTenpaySign()) {
	            if("1" == $trade_mode ) {
		            if( "0" == $trade_statess){ 
                        msg_url(L('pay_07').$out_trade_no,spacelink('pay'));
					} else {
			            msg_url(L('pay_09'),spacelink('pay'));
					}
				}elseif( "2" == $trade_mode  ) {
		            if( "0" == $trade_statess) {
						msg_url(L('pay_19'),spacelink('pay'));
		            } else {
			            msg_url(L('pay_09'),spacelink('pay'));
		            }
	            }
            } else {
				msg_url(L('pay_09'),spacelink('pay'));
            }
	}

	//�첽����
	public function notify_url()
	{

	    $partner = CS_Tenpay_ID;
        $key = CS_Tenpay_Key;
        require (CSCMSPATH."pay/tenpay/ResponseHandler.class.php");
        require (CSCMSPATH."pay/tenpay/RequestHandler.class.php");
        require (CSCMSPATH."pay/tenpay/client/ClientResponseHandler.class.php");
        require (CSCMSPATH."pay/tenpay/client/TenpayHttpClient.class.php");
	    $resHandler = new ResponseHandler();
	    $resHandler->setKey($key);
	    if($resHandler->isTenpaySign()) {
		     $notify_id = $resHandler->getParameter("notify_id");
		     $queryReq = new RequestHandler();
		     $queryReq->init();
		     $queryReq->setKey($key);
		     $queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
	         $queryReq->setParameter("partner", $partner);
		     $queryReq->setParameter("notify_id", $notify_id);
		     $httpClient = new TenpayHttpClient();
		     $httpClient->setTimeOut(5);
		     $httpClient->setReqContent($queryReq->getRequestURL());
		     if($httpClient->call()) {
			     $queryRes = new ClientResponseHandler();
			     $queryRes->setContent($httpClient->getResContent());
			     $queryRes->setKey($key);
		         if($resHandler->getParameter("trade_mode") == "1"){ //�ж�ǩ�����������ʱ���ʣ�
		                if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $resHandler->getParameter("trade_state") == "0") {
				             $out_trade_no = $resHandler->getParameter("out_trade_no");
				             $transaction_id = $resHandler->getParameter("transaction_id");
				             $total_fee = $resHandler->getParameter("total_fee");
				             $discount = $resHandler->getParameter("discount");
							 //��ȡ������¼
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
				             echo "success";
				
						} else {
			                 echo "fail";
						}

		        }elseif ($resHandler->getParameter("trade_mode") == "2"){ //�ж�ǩ����������н鵣����

		                if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" ) {
				            $out_trade_no = $resHandler->getParameter("out_trade_no");
				            $transaction_id = $resHandler->getParameter("transaction_id");
				            $total_fee = $resHandler->getParameter("total_fee");
				            $discount = $resHandler->getParameter("discount");
							$row=$this->CsdjDB->get_row('pay','*',$out_trade_no,'dingdan');
				            switch ($resHandler->getParameter("trade_state")) {
						            case "0":	//����ɹ�
						                if($row){
                                              $this->db->query("update ".CS_SqlPrefix."pay set pid=2 where id=".$row->id."");
								              //����֪ͨ
								              $add['uida']=$row->uid;
								              $add['uidb']=0;
								              $add['name']=L('pay_13');
								              $add['neir']=L('pay_14',array($row->rmb,$out_trade_no));
								              $add['addtime']=time();
            					              $this->CsdjDB->get_insert('msg',$add);
							            }
							            break;
						            case "1":	//���״���
							            break;
						            case "2":	//�ջ��ַ��д���
							            break;
						            case "4":	//���ҷ����ɹ�
						                if($row){
                                              $this->db->query("update ".CS_SqlPrefix."pay set pid=3 where id=".$row->id."");
								              //����֪ͨ
								              $add['uida']=$row->uid;
								              $add['uidb']=0;
								              $add['name']=L('pay_15');
								              $add['neir']=L('pay_16',array($out_trade_no));
								              $add['addtime']=time();
            					              $this->CsdjDB->get_insert('msg',$add);
							            }
							            break;
						            case "5":	//����ջ�ȷ�ϣ����׳ɹ�
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
							            break;
						            case "6":	//���׹رգ�δ��ɳ�ʱ�ر�
							            break;
						            case "7":	//�޸Ľ��׼۸�ɹ�
							            break;
						            case "8":	//��ҷ����˿�
							            break;
						            case "9":	//�˿�ɹ�
							            break;
						            case "10":	//�˿�ر�			
							            break;
						            default:
							            break;
							}
				            echo "success";
						} else{
			 	            echo "fail";
						}
		        }
			 }else{
	             //ͨ��ʧ��
		         echo "fail";
			 }
		} else {
             echo "fail";
		}
	}
}
