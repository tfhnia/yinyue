<?php
/* *
 * ������AlipayNotify
 * ���ܣ�֧����֪ͨ������
 * ��ϸ������֧�������ӿ�֪ͨ����
 * �汾��3.2
 * ���ڣ�2011-03-25
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο�

 *************************ע��*************************
 * ����֪ͨ����ʱ���ɲ鿴���дlog��־��д��TXT������ݣ������֪ͨ�����Ƿ�����
 */

require_once("alipay_core.function.php");
require_once("alipay_rsa.function.php");
require_once("alipay_md5.function.php");

class AlipayNotify {
    /**
     * HTTPS��ʽ��Ϣ��֤��ַ
     */
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
     * HTTP��ʽ��Ϣ��֤��ַ
     */
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	var $alipay_config;

	function __construct($alipay_config){
		$this->alipay_config = $alipay_config;
	}
    function AlipayNotify($alipay_config) {
    	$this->__construct($alipay_config);
    }
    /**
     * ���notify_url��֤��Ϣ�Ƿ���֧���������ĺϷ���Ϣ
     * @return ��֤���
     */
	function verifyNotify(){
		if(empty($_POST)) {//�ж�POST���������Ƿ�Ϊ��
			return false;
		}
		else {
			
			//��notify_data����
			$decrypt_post_para = $_POST;
			if ($this->alipay_config['sign_type'] == '0001') {
				$decrypt_post_para['notify_data'] = rsaDecrypt($decrypt_post_para['notify_data'], $this->alipay_config['private_key_path']);
			}
			
			//notify_id��decrypt_post_para�н���������Ҳ����˵decrypt_post_para���Ѿ�����notify_id�����ݣ�
			$doc = new DOMDocument();
			$doc->loadXML($decrypt_post_para['notify_data']);
			$notify_id = $doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;
			
			//��ȡ֧����Զ�̷�����ATN�������֤�Ƿ���֧������������Ϣ��
			$responseTxt = 'true';
			if (! empty($notify_id)) {$responseTxt = $this->getResponse($notify_id);}
			
			//����ǩ�����
			$isSign = $this->getSignVeryfy($decrypt_post_para, $_POST["sign"],false);
			
			//д��־��¼
			//if ($isSign) {
			//	$isSignStr = 'true';
			//}
			//else {
			//	$isSignStr = 'false';
			//}
			//$log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
			//$log_text = $log_text.createLinkString($_POST);
			//logResult($log_text);
			
			//��֤
			//$responsetTxt�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
			//isSign�Ľ������true���밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	
    /**
     * ���return_url��֤��Ϣ�Ƿ���֧���������ĺϷ���Ϣ
     * @return ��֤���
     */
	function verifyReturn(){
		if(empty($_GET)) {//�ж�GET���������Ƿ�Ϊ��
			return false;
		}
		else {
			//����ǩ�����
			$isSign = $this->getSignVeryfy($_GET, $_GET["sign"],true);
			
			//д��־��¼
			//if ($isSign) {
			//	$isSignStr = 'true';
			//}
			//else {
			//	$isSignStr = 'false';
			//}
			//$log_text = "return_url_log:isSign=".$isSignStr.",";
			//$log_text = $log_text.createLinkString($_GET);
			//logResult($log_text);
			
			//��֤
			//$responsetTxt�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
			//isSign�Ľ������true���밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
			if ($isSign) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
     * ����
     * @param $input_para Ҫ��������
     * @return ���ܺ���
     */
	function decrypt($prestr) {
		return rsaDecrypt($prestr, trim($this->alipay_config['private_key_path']));
	}
	
	/**
     * �첽֪ͨʱ���Բ������̶�����
     * @param $para ����ǰ�Ĳ�����
     * @return �����Ĳ�����
     */
	function sortNotifyPara($para) {
		$para_sort['service'] = $para['service'];
		$para_sort['v'] = $para['v'];
		$para_sort['sec_id'] = $para['sec_id'];
		$para_sort['notify_data'] = $para['notify_data'];
		return $para_sort;
	}
	
    /**
     * ��ȡ����ʱ��ǩ����֤���
     * @param $para_temp ֪ͨ�������Ĳ�������
     * @param $sign ���ص�ǩ�����
     * @param $isSort �Ƿ�Դ�ǩ����������
     * @return ǩ����֤���
     */
	function getSignVeryfy($para_temp, $sign, $isSort) {
		//��ȥ��ǩ�����������еĿ�ֵ��ǩ������
		$para = paraFilter($para_temp);
		if(!empty($para_temp['body']) && empty($para_filter['body'])){
            $para_filter['body']=$para_temp['body'];
		}
		//�Դ�ǩ��������������
		if($isSort) {
			$para = argSort($para);
		} else {
			$para = $this->sortNotifyPara($para);
		}
		
		//����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
		$prestr = createLinkstring($para);
		
		$isSgin = false;
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$isSgin = md5Verify($prestr, $sign, $this->alipay_config['key']);
				break;
			case "RSA" :
				$isSgin = rsaVerify($prestr, trim($this->alipay_config['ali_public_key_path']), $sign);
				break;
			case "0001" :
				$isSgin = rsaVerify($prestr, trim($this->alipay_config['ali_public_key_path']), $sign);
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}

    /**
     * ��ȡԶ�̷�����ATN���,��֤����URL
     * @param $notify_id ֪ͨУ��ID
     * @return ������ATN���
     * ��֤�������
     * invalid����������� ��������������ⷵ�ش�����partner��key�Ƿ�Ϊ�� 
     * true ������ȷ��Ϣ
     * false �������ǽ�����Ƿ�������ֹ�˿������Լ���֤ʱ���Ƿ񳬹�һ����
     */
	function getResponse($notify_id) {
		$transport = strtolower(trim($this->alipay_config['transport']));
		$partner = trim($this->alipay_config['partner']);
		$veryfy_url = '';
		if($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		}
		else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = getHttpResponseGET($veryfy_url, $this->alipay_config['cacert']);
		
		return $responseTxt;
	}
}
?>


