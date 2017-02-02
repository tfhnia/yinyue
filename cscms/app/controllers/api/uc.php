<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Uc extends Cscms_Controller
{
  const UC_CLIENT_RELEASE = '20110501';
  const UC_CLIENT_VERSION = '1.6.0';
 
  const API_DELETEUSER = 1;
  const API_RENAMEUSER = 1;
  const API_GETTAG = 0;
  const API_SYNLOGIN = 1;
  const API_SYNLOGOUT = 1;
  const API_UPDATEPW = 1;
  const API_UPDATEBADWORDS = 1;
  const API_UPDATEHOSTS = 1;
  const API_UPDATEAPPS = 1;
  const API_UPDATECLIENT = 1;
  const API_UPDATECREDIT = 0;
  const API_GETCREDITSETTINGS = 0;
  const API_GETCREDIT = 0;
  const API_UPDATECREDITSETTINGS = 0;
 
  const API_RETURN_SUCCEED = 1;
  const API_RETURN_FAILED = -1;
  const API_RETURN_FORBIDDEN = -2;
 
  public function index()
  {
      include CSCMS.'lib/Cs_Ucenter.php';
      if(User_Uc_Mode==0){
          echo 'UCͬ���Ѿ��ر�';
          return;
      }
      $get = $post = array();
      $code = $this->input->get('code', true);
      parse_str(self::authcode($code, 'DECODE', UC_KEY), $get);
      $timestamp = time();
      if ($timestamp - @$get['time'] > 3600)
      {
          echo '��Ȩ�ѹ���';
          return;
      }
      if (empty($get))
      {
          echo '�Ƿ�����';
          return;
      }
      $post = self::unserialize(file_get_contents('php://input'));
      if (in_array($get['action'], array(
          'test',
          'deleteuser',
          'renameuser',
          'gettag',
          'synlogin',
          'synlogout',
          'updatepw',
          'updatebadwords',
          'updatehosts',
          'updateapps',
          'updateclient',
          'updatecredit',
          'getcreditsettings',
          'updatecreditsettings')))
      {
          echo $this->$get['action']($get, $post);
          return;
      }
      else
      {
          echo self::API_RETURN_FAILED;
          return;
      }
 
  }
 
  private function test($get, $post)
  {
      return self::API_RETURN_SUCCEED;
  }
 
  private function deleteuser($get, $post)
  {
      if ( ! self::API_DELETEUSER)
      {
          return self::API_RETURN_FORBIDDEN;
      }
	  //�ر����ݿ⻺��
      $this->db->cache_off();
      $uid = (int)$get['ids'];
      //ɾ���û��ӿ�
	  if($uid>0){
	     $row=$this->db->query("SELECT id FROM ".CS_SqlPrefix."user where uid=".$uid."")->row();
	     if($row){
		    $id=$row->id;
			$this->CsdjDB->get_del('funco',$id,'uida'); //�ÿ�A
			$this->CsdjDB->get_del('funco',$id,'uidb'); //�ÿ�B
			$this->CsdjDB->get_del('fans',$id,'uida'); //��˿A
			$this->CsdjDB->get_del('fans',$id,'uidb'); //��˿B
			$this->CsdjDB->get_del('friend',$id,'uida');  //����A
			$this->CsdjDB->get_del('friend',$id,'uidb');   //����B
			$this->CsdjDB->get_del('gbook',$id,'uida'); //����A
			$this->CsdjDB->get_del('gbook',$id,'uidb'); //����B
			$this->CsdjDB->get_del('msg',$id,'uida'); //��ϢA
			$this->CsdjDB->get_del('msg',$id,'uidb'); //��ϢB
			$this->CsdjDB->get_del('pl',$id,'uid'); //����
			$this->CsdjDB->get_del('blog',$id,'uid'); //˵˵
			$this->CsdjDB->get_del('dt',$id,'uid'); //��̬
			$this->CsdjDB->get_del('user_log',$id,'uid'); //��¼��־
			$this->CsdjDB->get_del('web_pay',$id,'uid'); //ģ���¼
			$this->CsdjDB->get_del('pay',$id,'uid'); //��ֵ��¼
			$this->CsdjDB->get_del('income',$id,'uid'); //�����¼
			$this->CsdjDB->get_del('spend',$id,'uid'); //���Ѽ�¼
			$this->CsdjDB->get_del('share',$id,'uid'); //�����¼
			$this->CsdjDB->get_del('session',$id,'uid'); //session�Ự
            $this->CsdjDB->get_del('user',$id);
		 }
	  }
      return self::API_RETURN_SUCCEED;
  }
 
  private function gettag($get, $post)
  {
      if ( ! self::API_GETTAG)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      //
      return self::API_RETURN_SUCCEED;
  }
 
  private function synlogin($get, $post)
  {
      if ( ! self::API_SYNLOGIN)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	  //�ر����ݿ⻺��
      $this->db->cache_off();
      $uid = (int)$get['uid'];
      //ͬ����¼�Ĵ���
      include CSCMSPATH.'uc_client/client.php';
      if ($uc_user = uc_get_user($uid, 1))
      {
		    $row=$this->db->query("SELECT code,pass,sid,yid,id,name,lognum,cion,vip,logtime,viptime FROM ".CS_SqlPrefix."user where uid=".$uid."")->row();
		    if($row){
                     if($row->sid==0 && $row->yid==0){
						 if(!isset($_SESSION['cscms__id']) || $_SESSION['cscms__id']!=$row->id){

                               //ÿ���½�ӻ���
		                       if(User_Cion_Log>0 && date("Y-m-d",$row->logtime)!=date('Y-m-d')){
                                   $updata['cion']  = $row->cion+User_Cion_Log;
                               }

                               //�ж�VIP
                               IF($row->vip>0 && $viptime<time()){
	                                $updata['vip']  = 0;
	                                $updata['viptime']  = 0;
                               }

	                           $updata['zx']      = 1;
	                           $updata['lognum']  = $row->lognum+1;
	                           $updata['logtime'] = time();
	                           $updata['logip']   = getip();
	                           $updata['logms']   = time();
                               $this->CsdjDB->get_update ('user',$row->id,$updata);

                               //��¼��־
		                       $this->load->library('user_agent');
        					   $agent = ($this->agent->is_mobile() ? $this->agent->mobile() :                    $this->agent->platform()).'&nbsp;/&nbsp;'.$this->agent->browser().' v'.$this->agent->version();
							   $add['uid']=$row->id;
							   $add['loginip']=getip();
							   $add['logintime']=time();
							   $add['useragent']=$agent;
							   $this->CsdjDB->get_insert('user_log',$add);

                               $_SESSION['cscms__id']    = $row->id;
                               $_SESSION['cscms__name']  = $row->name;
                               $_SESSION['cscms__login'] = md5($row->name.$row->pass);

                               //��ס��¼
	                           $this->cookie->set_cookie("user_id",$row->id,time()+86400);
			                   $this->cookie->set_cookie("user_login",md5($row->name.$row->pass.$row->code),time()+86400);
						 }
					 }
			}
      }
      return self::API_RETURN_SUCCEED;
  }
 
  private function synlogout($get, $post)
  {
      if ( ! self::API_SYNLOGOUT)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	  //�ر����ݿ⻺��
      $this->db->cache_off();
      //ɾ������״̬
      $updata['zx']=0;
	  if(isset($_SESSION['cscms__id'])){

            $this->CsdjDB->get_update('user',$_SESSION['cscms__id'],$updata);
		    $this->CsdjDB->get_del('session',$_SESSION['cscms__id'],'uid');
	  }

      unset($_SESSION['cscms__id'],$_SESSION['cscms__name'],$_SESSION['cscms__login']);

      //�����ס��¼
	  $this->cookie->set_cookie("user_id");
	  $this->cookie->set_cookie("user_login");

      return self::API_RETURN_SUCCEED;
  }
 
  private function updatepw($get, $post)
  {
      if ( ! self::API_UPDATEPW)
      {
          return self::API_RETURN_FORBIDDEN;
      }
	  //�ر����ݿ⻺��
      $this->db->cache_off();
      //�޸��������
      $username=$get['username'];
	  $row=$this->db->query("SELECT code,pass,id,name FROM ".CS_SqlPrefix."user where name='".$username."'")->row();
	  if($row){
           $edit['pass']=md5(md5(trim($get['password'])).$row->code);
           //�޸�
           $this->CsdjDB->get_update('user',$row->id,$edit);
	  }
      return self::API_RETURN_SUCCEED;
  }
 
  private function updatebadwords($get, $post)
  {
      if ( ! self::API_UPDATEBADWORDS)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      $cachefile = CSCMSPATH.'uc_client/data/cache/badwords.php';
      @unlink($cachefile);
      return self::API_RETURN_SUCCEED;
  }
 
  private function updatehosts($get, $post)
  {
      if ( ! self::API_UPDATEHOSTS)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      $cachefile = CSCMSPATH.'uc_client/data/cache/hosts.php';
      @unlink($cachefile);
      return self::API_RETURN_SUCCEED;
  }
 
  private function updateapps($get, $post)
  {
      if ( ! self::API_UPDATEAPPS)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      $cachefile = CSCMSPATH.'uc_client/data/cache/apps.php';
      @unlink($cachefile);
      return self::API_RETURN_SUCCEED;
  }
 
  private function updateclient($get, $post)
  {
      if ( ! self::API_UPDATECLIENT)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      $cachefile = CSCMSPATH.'uc_client/data/cache/settings.php';
      @unlink($cachefile);
      return self::API_RETURN_SUCCEED;
  }
 
  private function updatecredit($get, $post)
  {
      if ( ! self::API_UPDATECREDIT)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      return self::API_RETURN_SUCCEED;
  }
 
  private function getcredit($get, $post)
  {
      if ( ! self::API_GETCREDIT)
      {
          return self::API_RETURN_FORBIDDEN;
      }
      return self::API_RETURN_SUCCEED;
  }
 
  public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
  {
      $ckey_length = 4;
      $key = md5($key ? $key : UC_KEY);
      $keya = md5(substr($key, 0, 16));
      $keyb = md5(substr($key, 16, 16));
      $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
 
      $cryptkey = $keya.md5($keya.$keyc);
      $key_length = strlen($cryptkey);
 
      $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
      $string_length = strlen($string);
 
      $result = '';
      $box = range(0, 255);
 
      $rndkey = array();
      for($i = 0; $i <= 255; $i++)
      {
          $rndkey[$i] = ord($cryptkey[$i % $key_length]);
      }
 
      for($j = $i = 0; $i < 256; $i++)
      {
          $j = ($j + $box[$i] + $rndkey[$i]) % 256;
          $tmp = $box[$i];
          $box[$i] = $box[$j];
          $box[$j] = $tmp;
      }
 
      for($a = $j = $i = 0; $i < $string_length; $i++)
      {
          $a = ($a + 1) % 256;
          $j = ($j + $box[$a]) % 256;
          $tmp = $box[$a];
          $box[$a] = $box[$j];
          $box[$j] = $tmp;
          $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
      }
 
      if($operation == 'DECODE')
      {
          if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
          {
              return substr($result, 26);
          }
          else
          {
              return '';
          }
      }
      else
      {
          return $keyc.str_replace('=', '', base64_encode($result));
      }
  }
 
  public static function serialize($arr, $htmlOn = 0)
  {
      if ( ! function_exists('xml_serialize'))
      {
          require CSCMSPATH.'uc_client/lib/xml.class.php';
      }
      return xml_serialize($arr, $htmlOn);
  }
 
  public static function unserialize($xml, $htmlOn = 0)
  {
      if ( ! function_exists('xml_serialize'))
      {
          require CSCMSPATH.'uc_client/lib/xml.class.php';
      }
      return xml_unserialize($xml, $htmlOn);
  }
 
  public static function gbk2utf8($string)
  {
      return iconv("GB2312", "UTF-8//IGNORE", $string);
  }
 
  public static function utf82gbk($string)
  {
      return iconv("UTF-8", "GB2312//IGNORE", $string);
  }
 
}
