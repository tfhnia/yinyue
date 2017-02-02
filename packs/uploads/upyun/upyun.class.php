<?php

class UpYunException extends Exception {/*{{{*/
    public function __construct($message, $code, Exception $previous = null) {
        parent::__construct($message, $code);   // For PHP 5.2.x
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}/*}}}*/

class UpYunAuthorizationException extends UpYunException {/*{{{*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 401, $previous);
    }
}/*}}}*/

class UpYunForbiddenException extends UpYunException {/*{{{*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 403, $previous);
    }
}/*}}}*/

class UpYunNotFoundException extends UpYunException {/*{{{*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 404, $previous);
    }
}/*}}}*/

class UpYunNotAcceptableException extends UpYunException {/*{{{*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 406, $previous);
    }
}/*}}}*/

class UpYunServiceUnavailable extends UpYunException {/*{{{*/
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 503, $previous);
    }
}/*}}}*/

class UpYun {
    const VERSION            = '2.0';

/*{{{*/
    const ED_AUTO            = 'v0.api.upyun.com';
    const ED_TELECOM         = 'v1.api.upyun.com';
    const ED_CNC             = 'v2.api.upyun.com';
    const ED_CTT             = 'v3.api.upyun.com';

    const CONTENT_TYPE       = 'Content-Type';
    const CONTENT_MD5        = 'Content-MD5';
    const CONTENT_SECRET     = 'Content-Secret';

    // ����ͼ
    const X_GMKERL_THUMBNAIL = 'x-gmkerl-thumbnail';
    const X_GMKERL_TYPE      = 'x-gmkerl-type';
    const X_GMKERL_VALUE     = 'x-gmkerl-value';
    const X_GMKERL_QUALITY   = 'x-gmkerl-quality';
    const X_GMKERL_UNSHARP   = 'x-gmkerl-unsharp';
/*}}}*/

    private $_bucket_name;
    private $_username;
    private $_password;
    private $_timeout = 30;

    /**
     * @deprecated
     */
    private $_content_md5 = NULL;

    /**
     * @deprecated
     */
    private $_file_secret = NULL;

    /**
     * @deprecated
     */
    private $_file_infos= NULL;

    protected $endpoint;

    /**
     * @var string: UPYUN ����Ψһid, ���ִ���ʱ, ���Խ���id����� UPYUN,���е���
     */
    private $x_request_id;

	/**
	* ��ʼ�� UpYun �洢�ӿ�
	* @param $bucketname �ռ�����
	* @param $username ����Ա����
	* @param $password ����
    *
	* @return object
	*/
	public function __construct($bucketname, $username, $password, $endpoint = NULL, $timeout = 30) {/*{{{*/
		$this->_bucketname = $bucketname;
		$this->_username = $username;
		$this->_password = md5($password);
        $this->_timeout = $timeout;

        $this->endpoint = is_null($endpoint) ? self::ED_AUTO : $endpoint;
	}/*}}}*/

    /**
     * ��ȡ��ǰSDK�汾��
     */
    public function version() {
        return self::VERSION;
    }

    /** 
     * ����Ŀ¼
     * @param $path ·��
     * @param $auto_mkdir �Ƿ��Զ���������Ŀ¼�����10���
     *
     * @return void
     */
    public function makeDir($path, $auto_mkdir = false) {/*{{{*/
        $headers = array('Folder' => 'true');
        if ($auto_mkdir) $headers['Mkdir'] = 'true';
        return $this->_do_request('PUT', $path, $headers);
    }/*}}}*/

    /**
     * ɾ��Ŀ¼���ļ�
     * @param string $path ·��
     *
     * @return boolean
     */
    public function delete($path) {/*{{{*/
        return $this->_do_request('DELETE', $path);
    }/*}}}*/


    /**
     * �ϴ��ļ�
     * @param string $path �洢·��
     * @param mixed $file ��Ҫ�ϴ����ļ����������ļ��������ļ�����
     * @param boolean $auto_mkdir �Զ�����Ŀ¼
     * @param array $opts ��ѡ����
     */
    public function writeFile($path, $file, $auto_mkdir = False, $opts = NULL) {/*{{{*/
        if (is_null($opts)) $opts = array();
        if (!is_null($this->_content_md5) || !is_null($this->_file_secret)) {
            //if (!is_null($this->_content_md5)) array_push($opts, self::CONTENT_MD5 . ": {$this->_content_md5}");
            //if (!is_null($this->_file_secret)) array_push($opts, self::CONTENT_SECRET . ": {$this->_file_secret}");
            if (!is_null($this->_content_md5)) $opts[self::CONTENT_MD5] = $this->_content_md5;
            if (!is_null($this->_file_secret)) $opts[self::CONTENT_SECRET] = $this->_file_secret;
        }

        // ������������԰汾��������ͼ���ͣ������Ĭ��ѹ���������񻯲���
        //if (isset($opts[self::X_GMKERL_THUMBNAIL]) || isset($opts[self::X_GMKERL_TYPE])) {
        //    if (!isset($opts[self::X_GMKERL_QUALITY])) $opts[self::X_GMKERL_QUALITY] = 95;
        //    if (!isset($opts[self::X_GMKERL_UNSHARP])) $opts[self::X_GMKERL_UNSHARP] = 'true';
        //}

        if ($auto_mkdir === True) $opts['Mkdir'] = 'true';

        $this->_file_infos = $this->_do_request('PUT', $path, $opts, $file);

        return $this->_file_infos;
    }/*}}}*/

    /**
     * �����ļ�
     * @param string $path �ļ�·��
     * @param mixed $file_handle
     *
     * @return mixed
     */
    public function readFile($path, $file_handle = NULL) {/*{{{*/
        return $this->_do_request('GET', $path, NULL, NULL, $file_handle);
    }/*}}}*/

    /**
     * ��ȡĿ¼�ļ��б�
     *
     * @param string $path ��ѯ·��
     *
     * @return mixed
     */
    public function getList($path = '/') {/*{{{*/
        $rsp = $this->_do_request('GET', $path);

        $list = array();
        if ($rsp) {
            $rsp = explode("\n", $rsp);
            foreach($rsp as $item) {
                @list($name, $type, $size, $time) = explode("\t", trim($item));
                if (!empty($time)) {
                    $type = $type == 'N' ? 'file' : 'folder';
                }

                $item = array(
                    'name' => $name,
                    'type' => $type,
                    'size' => intval($size),
                    'time' => intval($time),
                );
                array_push($list, $item);
            }
        }

        return $list;
    }/*}}}*/

    /**
     * @deprecated
     * @param string $path Ŀ¼·��
     * @return mixed
     */
    public function getFolderUsage($path = '/') {/*{{{*/
        $rsp = $this->_do_request('GET', '/?usage');
        return floatval($rsp);
    }/*}}}*/

    /**
     * ��ȡ�ļ���Ŀ¼��Ϣ
     *
     * @param string $path ·��
     *
     * @return mixed
     */
    public function getFileInfo($path) {/*{{{*/
        $rsp = $this->_do_request('HEAD', $path);

        return $rsp;
    }/*}}}*/

	/**
	* ����ǩ������
	* @param $method ����ʽ {GET, POST, PUT, DELETE}
	* return ǩ���ַ���
	*/
	private function sign($method, $uri, $date, $length){/*{{{*/
        //$uri = urlencode($uri);
		$sign = "{$method}&{$uri}&{$date}&{$length}&{$this->_password}";
		return 'UpYun '.$this->_username.':'.md5($sign);
	}/*}}}*/

    /**
     * HTTP REQUEST ��װ
     * @param string $method HTTP REQUEST����������PUT��POST��GET��OPTIONS��DELETE
     * @param string $path ��Bucketname֮�������·��������get����
     * @param array $headers ������Ҫ������HTTP HEADERS
     * @param array $body ��ҪPOST���͵�����
     *
     * @return mixed
     */
    protected function _do_request($method, $path, $headers = NULL, $body= NULL, $file_handle= NULL) {/*{{{*/
        $uri = "/{$this->_bucketname}{$path}";
        $ch = curl_init("http://{$this->endpoint}{$uri}");

        $_headers = array('Expect:');
        if (!is_null($headers) && is_array($headers)){
            foreach($headers as $k => $v) {
                array_push($_headers, "{$k}: {$v}");
            }
        }

        $length = 0;
		$date = gmdate('D, d M Y H:i:s \G\M\T');

        if (!is_null($body)) {
            if(is_resource($body)){
                fseek($body, 0, SEEK_END);
                $length = ftell($body);
                fseek($body, 0);

                array_push($_headers, "Content-Length: {$length}");
                curl_setopt($ch, CURLOPT_INFILE, $body);
                curl_setopt($ch, CURLOPT_INFILESIZE, $length);
            } else {
                $length = @strlen($body);
                array_push($_headers, "Content-Length: {$length}");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
        } else {
            array_push($_headers, "Content-Length: {$length}");
        }

        array_push($_headers, "Authorization: {$this->sign($method, $uri, $date, $length)}");
        array_push($_headers, "Date: {$date}");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method == 'PUT' || $method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
        } else {
			curl_setopt($ch, CURLOPT_POST, 0);
        }

        if ($method == 'GET' && is_resource($file_handle)) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FILE, $file_handle);
        }

        if ($method == 'HEAD') {
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code == 0) throw new UpYunException('Connection Failed', $http_code);

        curl_close($ch);

        $header_string = '';
        $body = '';

        if ($method == 'GET' && is_resource($file_handle)) {
            $header_string = '';
            $body = $response;
        } else {
            list($header_string, $body) = explode("\r\n\r\n", $response, 2);
        }
        $this->setXRequestId($header_string);
        if ($http_code == 200) {
            if ($method == 'GET' && is_null($file_handle)) {
                return $body;
            } else {
                $data = $this->_getHeadersData($header_string);
                return count($data) > 0 ? $data : true;
            }
        } else {
            $message = $this->_getErrorMessage($header_string);
            if (is_null($message) && $method == 'GET' && is_resource($file_handle)) {
                $message = 'File Not Found';
            }
            return false;
        }
    }/*}}}*/

    /**
     * ����HTTP HEADERS�з��ص��Զ�������
     *
     * @param string $text header�ַ���
     *
     * @return array
     */
    private function _getHeadersData($text) {/*{{{*/
        $headers = explode("\r\n", $text);
        $items = array();
        foreach($headers as $header) {
            $header = trim($header);
			if(stripos($header, 'x-upyun') !== False){
				list($k, $v) = explode(':', $header);
                $items[trim($k)] = in_array(substr($k,8,5), array('width','heigh','frame')) ? intval($v) : trim($v);
			}
        }
        return $items;
    }/*}}}*/

    /**
     * ��ȡ���صĴ�����Ϣ
     *
     * @param string $header_string
     *
     * @return mixed
     */
    private function _getErrorMessage($header_string) {
        list($status, $stash) = explode("\r\n", $header_string, 2);
        list($v, $code, $message) = explode(" ", $status, 3);
        return $message . " X-Request-Id: " . $this->getXRequestId();
    }

    private function setXRequestId($header_string) {
        preg_match('~^X-Request-Id: ([0-9a-zA-Z]{32})~ism', $header_string, $result);
        $this->x_request_id = isset($result[1]) ? $result[1] : '';
    }

    public function getXRequestId() {
        return $this->x_request_id;
    }

    /**
     * ɾ��Ŀ¼
     * @deprecated 
     * @param $path ·��
     *
     * @return void
     */
    public function rmDir($path) {/*{{{*/
        $this->_do_request('DELETE', $path);
    }/*}}}*/

    /**
     * ɾ���ļ�
     *
     * @deprecated 
     * @param string $path Ҫɾ�����ļ�·��
     *
     * @return boolean
     */
    public function deleteFile($path) {/*{{{*/
        $rsp = $this->_do_request('DELETE', $path);
    }/*}}}*/

    /**
     * ��ȡĿ¼�ļ��б�
     * @deprecated
     * 
     * @param string $path Ҫ��ȡ�б��Ŀ¼
     * 
     * @return array
     */
    public function readDir($path) {/*{{{*/
        return $this->getList($path);
    }/*}}}*/

    /**
     * ��ȡ�ռ�ʹ�����
     *
     * @deprecated �Ƽ�ֱ��ʹ�� getFolderUsage('/')����ȡ
     * @return mixed
     */
    public function getBucketUsage() {/*{{{*/
        return $this->getFolderUsage('/');
    }/*}}}*/

	/**
	* ��ȡ�ļ���Ϣ
    *
    * #deprecated
	* @param $file �ļ�·���������ļ�����
	* return array('type'=> file | folder, 'size'=> file size, 'date'=> unix time) �� null
	*/
	//public function getFileInfo($file){/*{{{*/
    //    $result = $this->head($file);
	//	if(is_null($r))return null;
	//	return array('type'=> $this->tmp_infos['x-upyun-file-type'], 'size'=> @intval($this->tmp_infos['x-upyun-file-size']), 'date'=> @intval($this->tmp_infos['x-upyun-file-date']));
	//}/*}}}*/

	/**
	* �л� API �ӿڵ�����
    *
    * @deprecated
	* @param $domain {ĬȻ v0.api.upyun.com �Զ�ʶ��, v1.api.upyun.com ����, v2.api.upyun.com ��ͨ, v3.api.upyun.com �ƶ�}
	* return null;
	*/
	public function setApiDomain($domain){/*{{{*/
		$this->endpoint = $domain;
	}/*}}}*/

	/**
	* ���ô��ϴ��ļ��� Content-MD5 ֵ���������Ʒ�����յ����ļ�MD5ֵ���û����õĲ�һ�£����ر� 406 Not Acceptable ����
    *
    * @deprecated
	* @param $str ���ļ� MD5 У���룩
	* return null;
	*/
	public function setContentMD5($str){/*{{{*/
		$this->_content_md5 = $str;
	}/*}}}*/

	/**
	* ���ô��ϴ��ļ��� ������Կ��ע�⣺��֧��ͼƬ�գ���������Կ���޷�����ԭ�ļ�URLֱ�ӷ��ʣ���� URL ������� ������ͼ�����־��+��Կ�� ���з��ʣ�
	* ������ͼ�����־��Ϊ ! ����ԿΪ bac���ϴ��ļ�·��Ϊ /folder/test.jpg ����ô��ͼƬ�Ķ�����ʵ�ַΪ�� http://�ռ�����/folder/test.jpg!bac
    *
    * @deprecated
	* @param $str ���ļ� MD5 У���룩
	* return null;
	*/
	public function setFileSecret($str){/*{{{*/
		$this->_file_secret = $str;
	}/*}}}*/

	/**
     * @deprecated
	* ��ȡ�ϴ��ļ������Ϣ����ͼƬ�ռ��з������ݣ�
	* @param $key ��Ϣ�ֶ�����x-upyun-width��x-upyun-height��x-upyun-frames��x-upyun-file-type��
	* return value or NULL
	*/
	public function getWritedFileInfo($key){/*{{{*/
		if(!isset($this->_file_infos))return NULL;
		return $this->_file_infos[$key];
	}/*}}}*/
}

