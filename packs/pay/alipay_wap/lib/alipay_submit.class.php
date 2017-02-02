<?php
/* *
 * ������AlipaySubmit
 * ���ܣ�֧�������ӿ������ύ��
 * ��ϸ������֧�������ӿڱ�HTML�ı�����ȡԶ��HTTP����
 * �汾��3.3
 * ���ڣ�2012-07-23
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
 */
require_once("alipay_core.function.php");
require_once("alipay_rsa.function.php");
require_once("alipay_md5.function.php");

class AlipaySubmit {

	var $alipay_config;
	/**
	 *֧�������ص�ַ
	 */
	//var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	var $alipay_gateway_new = 'http://wappaygw.alipay.com/service/rest.htm?';

	function __construct($alipay_config){
		$this->alipay_config = $alipay_config;
	}
    function AlipaySubmit($alipay_config) {
    	$this->__construct($alipay_config);
    }
	
	/**
	 * ����ǩ�����
	 * @param $para_sort ������Ҫǩ��������
	 * return ǩ������ַ���
	 */
	function buildRequestMysign($para_sort) {
		//����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
		$prestr = createLinkstring($para_sort);
		
		$mysign = "";
		switch (strtoupper(trim($this->alipay_config['sign_type']))) {
			case "MD5" :
				$mysign = md5Sign($prestr, $this->alipay_config['key']);
				break;
			case "RSA" :
				$mysign = rsaSign($prestr, $this->alipay_config['private_key_path']);
				break;
			case "0001" :
				$mysign = rsaSign($prestr, $this->alipay_config['private_key_path']);
				break;
			default :
				$mysign = "";
		}
		
		return $mysign;
	}

	/**
     * ����Ҫ�����֧�����Ĳ�������
     * @param $para_temp ����ǰ�Ĳ�������
     * @return Ҫ����Ĳ�������
     */
	function buildRequestPara($para_temp) {
		//��ȥ��ǩ�����������еĿ�ֵ��ǩ������
		$para_filter = paraFilter($para_temp);

		//�Դ�ǩ��������������
		$para_sort = argSort($para_filter);

		//����ǩ�����
		$mysign = $this->buildRequestMysign($para_sort);
		
		//ǩ�������ǩ����ʽ���������ύ��������
		$para_sort['sign'] = $mysign;
		if($para_sort['service'] != 'alipay.wap.trade.create.direct' && $para_sort['service'] != 'alipay.wap.auth.authAndExecute') {
			$para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));
		}
		
		return $para_sort;
	}

	/**
     * ����Ҫ�����֧�����Ĳ�������
     * @param $para_temp ����ǰ�Ĳ�������
     * @return Ҫ����Ĳ��������ַ���
     */
	function buildRequestParaToString($para_temp) {
		//�������������
		$para = $this->buildRequestPara($para_temp);
		
		//�Ѳ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ����������ַ�����urlencode����
		$request_data = createLinkstringUrlencode($para);
		
		return $request_data;
	}
	
    /**
     * ���������Ա�HTML��ʽ���죨Ĭ�ϣ�
     * @param $para_temp �����������
     * @param $method �ύ��ʽ������ֵ��ѡ��post��get
     * @param $button_name ȷ�ϰ�ť��ʾ����
     * @return �ύ��HTML�ı�
     */
	function buildRequestForm($para_temp, $method, $button_name) {
		//�������������
		$para = $this->buildRequestPara($para_temp);
		
		$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->alipay_gateway_new."_input_charset=".trim(strtolower($this->alipay_config['input_charset']))."' method='".$method."'>";
		while (list ($key, $val) = each ($para)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

		//submit��ť�ؼ��벻Ҫ����name����
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
		
		return $sHtml;
	}
	
	/**
     * ����������ģ��Զ��HTTP��POST����ʽ���첢��ȡ֧�����Ĵ�����
     * @param $para_temp �����������
     * @return ֧����������
     */
	function buildRequestHttp($para_temp) {
		$sResult = '';
		
		//��������������ַ���
		$request_data = $this->buildRequestPara($para_temp);

		//Զ�̻�ȡ����
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'],$request_data,trim(strtolower($this->alipay_config['input_charset'])));

		return $sResult;
	}
	
	/**
     * ����������ģ��Զ��HTTP��POST����ʽ���첢��ȡ֧�����Ĵ����������ļ��ϴ�����
     * @param $para_temp �����������
     * @param $file_para_name �ļ����͵Ĳ�����
     * @param $file_name �ļ���������·��
     * @return ֧�������ش�����
     */
	function buildRequestHttpInFile($para_temp, $file_para_name, $file_name) {
		
		//�������������
		$para = $this->buildRequestPara($para_temp);
		$para[$file_para_name] = "@".$file_name;
		
		//Զ�̻�ȡ����
		$sResult = getHttpResponsePOST($this->alipay_gateway_new, $this->alipay_config['cacert'],$para,trim(strtolower($this->alipay_config['input_charset'])));

		return $sResult;
	}
	
	/**
     * ����Զ��ģ���ύ�󷵻ص���Ϣ
	 * @param $str_text Ҫ�������ַ���
     * @return �������
     */
	function parseResponse($str_text) {
		//�ԡ�&���ַ��и��ַ���
		$para_split = explode('&',$str_text);
		//���и����ַ��������ɱ�������ֵ��ϵ�����
		foreach ($para_split as $item) {
			//��õ�һ��=�ַ���λ��
			$nPos = strpos($item,'=');
			//����ַ�������
			$nLen = strlen($item);
			//��ñ�����
			$key = substr($item,0,$nPos);
			//�����ֵ
			$value = substr($item,$nPos+1,$nLen-$nPos-1);
			//����������
			$para_text[$key] = $value;
		}
		
		if( ! empty ($para_text['res_data'])) {
			//�������ܲ����ַ���
			if($this->alipay_config['sign_type'] == '0001') {
				$para_text['res_data'] = rsaDecrypt($para_text['res_data'], $this->alipay_config['private_key_path']);
			}
			
			//token��res_data�н���������Ҳ����˵res_data���Ѿ�����token�����ݣ�
			$doc = new DOMDocument();
			$doc->loadXML($para_text['res_data']);
			$para_text['request_token'] = $doc->getElementsByTagName( "request_token" )->item(0)->nodeValue;
		}
		
		return $para_text;
	}
	
	/**
     * ���ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
	 * ע�⣺�ù���PHP5����������֧�֣���˱�������������ص�����װ��֧��DOMDocument��SSL��PHP���û��������鱾�ص���ʱʹ��PHP�������
     * return ʱ����ַ���
	 */
	function query_timestamp() {
		$url = $this->alipay_gateway_new."service=query_timestamp&partner=".trim(strtolower($this->alipay_config['partner']))."&_input_charset=".trim(strtolower($this->alipay_config['input_charset']));
		$encrypt_key = "";		

		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
		
		return $encrypt_key;
	}
}
?>

