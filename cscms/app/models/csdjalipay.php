<?php
/**
 * @Cscms 3.5 open source management system
 * @copyright 2009-2013 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2013-04-27
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CsdjAlipay extends CI_Model {

    var $gateway;
    var $partner;
    var $security_code;
    var $seller_email;
    var $_input_charset;
    var $transport;
    var $notify_url;
    var $return_url;
    var $show_url;
    var $sign_type;
    var $mainname;
    var $antiphishing;
    var $parameter;
    var $mysign;

    // ���캯�� & ��������
    function __construct() {
	    parent:: __construct ();
        $this->load->helper('form');
        $this->load->helper('url');

        $this->partner = CS_Alipay_ID; //���������ID
        $this->security_code = CS_Alipay_Key; //��ȫ������
        $this->seller_email = CS_Alipay_Name; //ǩԼ֧�����˺Ż�����֧�����ʻ�
        $this->_input_charset = "gbk"; //�ַ������ʽ Ŀǰ֧�� GBK �� utf-8

        //˫���ܽ���
        $this->notify_url2 = site_url('user/pay/notify_page_1'); //���׹����з�����֪ͨ��ҳ�棬���첽֪ͨ����ʵ�ʷ����������У�Ҫ�� http://��ʽ������·�������ú��в���
        $this->return_url2 = site_url('user/pay/return_page_1'); //���׹����з�����֪ͨ��ҳ�棬���첽֪ͨ����ʵ�ʷ����������У�Ҫ�� http://��ʽ������·�������ú��в���

        //��ʱ����
        $this->notify_url = site_url('user/pay/notify_page_2'); //���׹����з�����֪ͨ��ҳ�棬���첽֪ͨ����ʵ�ʷ����������У�Ҫ�� http://��ʽ������·�������ú��в���
        $this->return_url = site_url('user/pay/return_page_2'); //���׹����з�����֪ͨ��ҳ�棬���첽֪ͨ����ʵ�ʷ����������У�Ҫ�� http://��ʽ������·�������ú��в���
        $this->show_url = "http://".$_SERVER['HTTP_HOST'].""; //��վ��Ʒ��չʾ��ַ��������� ?id=123 �����Զ������

        $this->sign_type = "MD5"; //���ܷ�ʽ �����޸�
        $this->mainname = "GooCarlos"; //�տ���ƣ��磺��˾���ơ���վ���ơ��տ���������

        $this->transport = "http";
        $this->gateway = "https://www.alipay.com/cooperate/gateway.do?"; // ���ص�ַ

        $this->antiphishing = "0"; //�����㹦�ܿ��أ�'0'��ʾ�ù��ܹرգ�'1'��ʾ�ù��ܿ�����Ĭ��Ϊ�ر�
        /**
         * һ�����������޷��رգ������̼�������վ���������ѡ���Ƿ�����
         * ���뿪ͨ��������ϵ���ǵĿͻ�����򲦴��̻�����绰0571-88158090����æ���뿪ͨ��
         * ���������㹦�ܺ󣬷��������������Ա���֧��Զ��XML�����������úøû�����
         * ��Ҫʹ�÷����㹦�ܣ�����ʹ��POST��ʽ�������ݣ������class�ļ�����alipay_function.php�ļ����ҵ����ļ����·���query_timestamp����.
         */
    }

    /**
     * ����ǩ�����
     * @param <����> $sort_array Ҫ���ܵ�����
     * @param <����> $security_code Ҫ���ܵ�����
     * @param <����> $sign_type Ҫ���ܵ�����
     * @return <�ַ���> $mysgin ǩ������ַ���
     */
    function build_mysign($sort_array, $security_code, $sign_type = "MD5") {
        $prestr = $this->create_linkstring($sort_array); //����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
        $prestr = $prestr . $security_code; //��ƴ�Ӻ���ַ������밲ȫУ����ֱ����������
        $mysgin = $this->sign($prestr, $sign_type); //�����յ��ַ������ܣ����ǩ�����
        return $mysgin;
    }

    /**
     * ����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
     * @param <����> $array ��Ҫƴ�ӵ�����
     * @return <�ַ���> $arg ƴ������Ժ���ַ���
     */
    function create_linkstring($array) {
        $arg = "";
        while (list ($key, $val) = each($array)) {
            $arg.=$key . "=" . $val . "&";
        }
        $arg = substr($arg, 0, count($arg) - 2); //ȥ�����һ��&�ַ�
        return $arg;
    }

    /**
     * ����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
     * ʹ�ó�����GET��ʽ����ʱ����URL�����Ľ��б���
     * @param <����> $array ��Ҫƴ�ӵ�����
     * @return <�ַ���> $arg ƴ������Ժ���ַ���
     */
    function create_linkstring_urlencode($array) {
        $arg = "";
        while (list ($key, $val) = each($array)) {
            if ($key != "service" && $key != "_input_charset") {
                $arg.=$key . "=" . urlencode($val) . "&";
            } else {
                $arg.=$key . "=" . $val . "&";
            }
        }
        $arg = substr($arg, 0, count($arg) - 2);       //ȥ�����һ��&�ַ�
        return $arg;
    }

    /**
     * ��ȥ�����еĿ�ֵ��ǩ������
     * @param <����> $parameter ���ܲ�����
     * @return <����> $para ȥ����ֵ��ǩ����������¼��ܲ�����
     */
    function para_filter($parameter) {
        $para = array();
        while (list ($key, $val) = each($parameter)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para[$key] = $parameter[$key];
            }
        }
        return $para;
    }

    /**
     * ����������
     * @param <����> $array ����ǰ������
     * @return <����> $array ����������
     */
    function arg_sort($array) {
        ksort($array);
        reset($array);
        return $array;
    }

    /**
     * �����ַ���
     * @param <�ַ���> $prestr ��Ҫ���ܵ��ַ���
     * @param <�ַ���> $sign_type ��������
     * @return <�ַ���> $sign ���ܽ��
     */
    function sign($prestr, $sign_type) {
        $sign = '';
        if ($sign_type == 'MD5') {
            $sign = md5($prestr);
        } elseif ($sign_type == 'DSA') {
            //DSA ǩ����������������
            die("DSA ǩ����������������������ʹ��MD5ǩ����ʽ");
        } else {
            die("֧�����ݲ�֧��" . $sign_type . "���͵�ǩ����ʽ");
        }
        return $sign;
    }

    /**
     * ʵ�ֶ����ַ����뷽ʽ
     * @param <�ַ���> $input ��Ҫ������ַ���
     * @param <�ַ���> $_output_charset ����ı����ʽ
     * @param <�ַ���> $_input_charset ����ı����ʽ
     * @return <�ַ���> $output �������ַ���
     */
    function charset_encode($input, $_output_charset, $_input_charset) {
        $output = "";
        if (!isset($_output_charset)) {
            $_output_charset = $_input_charset;
        }
        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else
            die("sorry, you have no libs support for charset change.");
        return $output;
    }

    /**
     * ʵ�ֶ����ַ����뷽ʽ
     * @param <�ַ���> $input ��Ҫ������ַ���
     * @param <�ַ���> $_input_charset ����Ľ����ʽ
     * @param <�ַ���> $_output_charset ����Ľ����ʽ
     * @return <�ַ���> $output �������ַ���
     */
    function charset_decode($input, $_input_charset, $_output_charset) {
        $output = "";
        if (!isset($_input_charset)) {
            $_input_charset = $_input_charset;
        }
        if ($_input_charset == $_output_charset || $input == null) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
        } elseif (function_exists("iconv")) {
            $output = iconv($_input_charset, $_output_charset, $input);
        } else
            die("sorry, you have no libs support for charset changes.");
        return $output;
    }

    /**
     * ���ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
     * ע�⣺���ڵͰ汾��PHP���û�����֧��Զ��XML��������˱�������������ص�����װ�и߰汾��PHP���û��������鱾�ص���ʱʹ��PHP�������
     * @param <�ַ���> $partner ���������ID
     * @return <�ַ���> $encrypt_key ʱ����ַ���
     */
    function query_timestamp($partner) {
        $URL = "https://mapi.alipay.com/gateway.do?service=query_timestamp&partner=" . $partner;
        $encrypt_key = "";
        //��Ҫʹ�÷����㣬��ȡ�������4��ע��
        //$doc = new DOMDocument();
        //$doc->load($URL);
        //$itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
        //$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
        //return $encrypt_key;
    }

    // ��������з�����֪ͨ

    /**
     * ��notify_url����֤
     * @return <����> ��֤���
     */
    function notify_verify() {
        $config['uri_protocol'] = "PATH_INFO";
        parse_str($_SERVER['QUERY_STRING'], $_POST);

        // ����ǩ�����
        if (empty($_POST)) {
            //�ж�POST���������Ƿ�Ϊ��
            return false;
        } else {
            $post = $this->para_filter($_POST); //������POST���صĲ���ȥ��
            $sort_post = $this->arg_sort($post); //������POST������������������
            $this->mysign = $this->build_mysign($sort_post, $this->security_code, $this->sign_type); //����ǩ�����

            if ($this->mysign == $_POST["sign"]) {
                return true; // ǩ������
            } else {
                return false; // ǩ������
            }
        }
    }

    /**
     * ��return_url����֤
     * @return <����> ��֤���
     */
    function return_verify() {
        $config['uri_protocol'] = "PATH_INFO";
        parse_str($_SERVER['QUERY_STRING'], $_GET);

        // ����ǩ�����
        if (empty($_GET)) {
            // �ж�GET���������Ƿ�Ϊ��
            return false;
        } else {
            $get = $this->para_filter($_GET); //������GET��������������ȥ��
            $sort_get = $this->arg_sort($get); //������GET������������������
            $this->mysign = $this->build_mysign($sort_get, $this->security_code, $this->sign_type);    //����ǩ�����

            if ($this->mysign == $_GET["sign"]) {
                return true; // ǩ������
            } else {
                return false; // ǩ������
            }
        }
    }

    /**
     * GET ������
     */
    function create_url($parameter) {
        // ��ȡ����
        $this->parameter = $this->para_filter($parameter);
        // ��ȡ����
        $this->_input_charset = $this->parameter['_input_charset'];
        // ��ȡǩ�����
        $sort_array = $this->arg_sort($this->parameter);
        $this->mysign = $this->build_mysign($sort_array, $this->security_code, $this->sign_type);

        // ������ת����
        $url = $this->gateway;
        $sort_array = array();
        $sort_array = $this->arg_sort($this->parameter);
        $arg = $this->create_linkstring_urlencode($sort_array); //����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
        //�����ص�ַ���Ѿ�ƴ�ӺõĲ��������ַ�����ǩ�������ǩ�����ͣ�ƴ�ӳ�������������url
        $url.= $arg . "&sign=" . $this->mysign . "&sign_type=" . $this->sign_type;
        return $url;
    }

    /**
     * POST ������
     */
    function build_postform($parameter) {
        // ��ȡ����
        $this->parameter = $this->para_filter($parameter);
        // ��ȡ����
        $this->_input_charset = $this->parameter['_input_charset'];
        // ��ȡǩ�����
        $sort_array = $this->arg_sort($this->parameter);
        $this->mysign = $this->build_mysign($sort_array, $this->security_code, $this->sign_type);

        // �����ύ��
        $_extension = array('name' => 'alipay_form');
        $_post_url = $this->gateway . "_input_charset=" . $this->parameter['_input_charset'];
        $payform_html = form_open($_post_url, $_extension);

        // ���֧����������
        while (list ($key, $val) = each($this->parameter)) {
            $payform_html.= form_hidden($key, $val);
        }

        $payform_html.= form_hidden('sign', $this->mysign);
        $payform_html.= form_hidden('sign_type', $this->sign_type);
        $payform_html.= form_close();
        $_button_js = 'onClick=document.forms["alipay_form"].submit();';
        $payform_html.= form_button('submit', 'Go to Alipay Now', $_button_js);

        return $payform_html;
    }

}

/* End of file alipay_model.php */
/* Location: ./application/model/alipay_model.php */

