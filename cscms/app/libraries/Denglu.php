<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-10
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ��������¼��
 */
class Denglu {

    function __construct ()
	{
		//���ص�ַ
		$this->redirect_uri = spacelink("open/callback");
	 	$this->ci = &get_instance();
	}

    //��¼
	public function login($ac,$log_state=''){
        if(CS_Appmode==1){  //�ٷ���¼
              $log_url="http://denglu.chshcms.com/denglu?ac=".$ac."&appid=".CS_Appid."&redirect_uri=".$this->redirect_uri."&state=".$log_state."&getdate=".time();
              header("Location: $log_url"); 
		}else{  //����
			  $mode=$ac.'_login';
              $this->$mode($log_state);
		}
    }

    //����
	public function callback($ac,$log_state=''){
        if(CS_Appmode==1){  //�ٷ���¼
              return $this->cscms_callback($ac,$log_state);
		}else{  //����
			  $mode=$ac.'_callback';
              return $this->$mode($log_state);
		}
    }

	//QQ��¼
    public  function qq_login($log_state='') {
         if(CS_Qqmode==0) exit('QQ��¼Ϊ�ر�״̬~');
         $scope= "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
         $login_url='https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.CS_Qqid.'&redirect_uri='.$this->redirect_uri.'&state='.$log_state.'&scope='.$scope;
         header("Location:$login_url");
    }

    //����΢����¼
    public  function weibo_login($log_state='') {
         if(CS_Wbmode==0) exit('΢����¼Ϊ�ر�״̬~');
         echo "
		  <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		  <html xmlns=\"http://www.w3.org/1999/xhtml\">
		  <head>
		  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=gbk\" />
		  <title>����΢������</title>
		  </head>
		  <script src=\"http://tjs.sjs.sinajs.cn/t35/apps/opent/js/frames/client.js\" language=\"JavaScript\"></script>
		  <script> 
		  function authLoad(){
			App.AuthDialog.show({
			client_id : '".CS_Wbid."',    //��ѡ��appkey
			redirect_uri : '".$this->redirect_uri."',     //��ѡ����Ȩ��Ļص���ַ
			height: 120    //��ѡ��Ĭ�Ͼඥ��120px
			});
		  }
		  </script>
		  <body onload=\"authLoad();\">
		  </body>
		  </html>";
    }

    //�ٶȵ�¼
    public  function baidu_login($log_state='') {
         if(CS_Bdmode==0) exit('�ٶ��˺ŵ�¼Ϊ�ر�״̬~');
         $login_url='https://openapi.baidu.com/oauth/2.0/authorize?response_type=code&client_id='.CS_Bdid.'&redirect_uri='.$this->redirect_uri.'&scope=netdisk&display=';
         header("Location:$login_url");
    }

    //���˵�¼
    public  function renren_login($log_state='') {
         if(CS_Rrmode==0) exit('�������˺ŵ�¼Ϊ�ر�״̬~');
         $login_url="http://graph.renren.com/oauth/authorize?client_id=".CS_Rrid."&redirect_uri=".$this->redirect_uri."&response_type=code&state=".$log_state."&display=&x_renew=";
         header("Location:$login_url");
    }

    //���ĵ�¼
    public  function kaixin_login($log_state='') {
         if(CS_Kxmode==0) exit('�������˺ŵ�¼Ϊ�ر�״̬~');
         $login_url="http://api.kaixin001.com/oauth2/authorize?response_type=code&client_id=".CS_Kxid."&redirect_uri=".$this->redirect_uri."&state=".$log_state."&scope=basic&display=popup";
         header("Location:$login_url");
    }

    //�����¼
    public  function douban_login($log_state='') {
         if(CS_Dbmode==0) exit('�������˺ŵ�¼Ϊ�ر�״̬~');
         $scope= "shuo_basic_r,shuo_basic_w,douban_basic_common";
         $login_url="https://www.douban.com/service/auth2/auth?response_type=code&client_id=".CS_Dbid."&redirect_uri=".$this->redirect_uri."&state=".$log_state."&scope=&display=".$scope;
         header("Location:$login_url");
    }

	//�ٷ���¼����
    public  function cscms_callback($ac,$log_state='') {
         $state=$this->ci->input->get_post('state', TRUE, TRUE);
         $uid=$this->ci->input->get_post('uid', TRUE, TRUE);
         $username=$this->ci->input->get_post('username', TRUE, TRUE);
         $userlogo=$this->ci->input->get_post('userlogo', TRUE, TRUE);

         if(empty($state) || empty($uid) || empty($username)){
              msg_url('��¼ʧ�ܣ����ز�������~!',spacelink('login')); 
	     }

		 if($state!=$log_state){
              msg_url('�Ƿ���¼~!',spacelink('login')); 
		 }
		 //�鿴���ݿ��Ƿ����
		 $row=$this->ci->db->query("SELECT id,uid,nickname,avatar FROM ".CS_SqlPrefix."useroauth where csid=".intval($uid)."")->row();
		 if($row){
			   $_SESSION['denglu__id'] = $row->id;
			   $_SESSION['denglu__name'] = $row->nickname;
			   $_SESSION['denglu__logo'] = $row->avatar;
		       return $row->uid;
		 }else{
			   $add['cid']=$this->class_id($ac);
			   $add['csid']=$uid;
			   $add['nickname']=$username;
			   $add['avatar']=$userlogo;
			   $add['oid']='';
			   $add['access_token']='';
			   $add['refresh_token']='';
			   $ids=$this->ci->CsdjDB->get_insert('useroauth',$add);
			   $_SESSION['denglu__id'] = intval($ids);
			   $_SESSION['denglu__name'] = $username;
			   $_SESSION['denglu__logo'] = $userlogo;
		       return 0;
		 }
    }

	//QQ��¼����
    public  function qq_callback($log_state='') {
         $state=$this->ci->input->get_post('state', TRUE, TRUE);
         $code=$this->ci->input->get('code', TRUE);

         if(empty($state) || empty($code)){
              msg_url('��¼ʧ�ܣ����ز�������~!',spacelink('login')); 
	     }

		 if($state!=$log_state){
              msg_url('�Ƿ���¼~!',spacelink('login')); 
		 }

         //��ȡACCSEE_TOTEN
         $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                      . "client_id=" . CS_Qqid. "&redirect_uri=" . urlencode($this->redirect_uri)
                      . "&client_secret=" . CS_Qqkey. "&code=" . $code;
         $response=$this->get_url_contents($token_url);

         if (strpos($response, "callback") !== false){
               msg_url('����ʧ�ܣ�û��ȡ��access_token��',spacelink('login')); 
         }
         $params = array();
         parse_str($response, $params);
         $data['access_token']=$params['access_token'];
         $data['refresh_token']=$params['refresh_token'];
         $data['expire_in']=$params['expires_in'];

         //��ȡOPENID
         $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$data['access_token'];
         $str  = $this->get_url_contents($graph_url);
         if (strpos($str, "callback") !== false){
               $lpos = strpos($str, "(");
               $rpos = strrpos($str, ")");
               $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		 }
         $user = json_decode($str);
         if (isset($user->error)){
              msg_url('��ȡopenidʧ�ܣ�',spacelink('login')); 
         }
         $qqid=$user->openid;

         //��ȡ�û���Ϣ
         $get_user_info = "https://graph.qq.com/user/get_user_info?"
                   . "access_token=" . $data['access_token']
                   . "&oauth_consumer_key=" . CS_Qqid
                   . "&openid=" . $qqid
                   . "&format=json";
         $info=$this->get_url_contents($get_user_info);
         $arr = json_decode($info, true);

		 $data['nickname']=$arr['nickname'];
		 $data['avatar']=$arr['figureurl_2'];

         //д�����ݿⲢ����
         return $this->denglu_str('qq',$qqid,$data);
    }

    //weibo����
    function weibo_callback($log_state='') { 

           $access_token=$this->ci->input->get('access_token', TRUE);
           $expires_in=(int)$this->ci->input->get('expires_in', TRUE);
           $uid=$this->ci->input->get('uid', TRUE);
           if($access_token && $uid){ 
                //��ȡ�û���Ϣ
                $url="https://api.weibo.com/2/users/show.json?source=".CS_Wbkey."&access_token=".$access_token."&uid=".$uid;
                $info=$this->get_url_contents($url);
                $arr = json_decode($info, true);

                $data['nickname']=$arr['name'];
                $data['avatar']=$arr['profile_image_url'];
                $data['access_token']=$access_token;
                $data['refresh_token']='';
                $data['expire_in']=$expires_in;

                //д�����ݿⲢ����
                return $this->denglu_str('weibo',$uid,$data);

           }else{
                  echo '<html></head><script>var url=location.hash; var uri=url.replace("#","?"); if(uri){ top.location.href =uri;}else{top.location.href ="/";}</script></head><body></body></html>';
           }
    }

    //�ٶȷ���
    function baidu_callback($log_state='') { 
           $code=$this->ci->input->get('code', TRUE);
           if($code){
               //��ȡACCSEE_TOTEN
               $token_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=authorization_code&"
                      . "client_id=" . CS_Bdid. "&redirect_uri=" . urlencode($this->redirect_uri)
                      . "&client_secret=" . CS_Bdkey. "&code=" . $code;
               $response=$this->get_url_contents($token_url);

               if (strpos($response, "error") !== false){
                    exit('����ʧ�ܣ�û��ȡ��access_token��');
               }
               $response = json_decode($response, true);
               $data['access_token']=$response['access_token'];
               $data['refresh_token']=$response['refresh_token'];
               $data['expire_in']=$response['expires_in'];

               //��ȡ�û���Ϣ
               $get_user_info = "https://openapi.baidu.com/rest/2.0/passport/users/getInfo?access_token=".$data['access_token']."&format=json";
               $info  = $this->get_url_contents($get_user_info);
               if (strpos($info, "error") !== false)
               {
                   exit('��ȡ�û���Ϣʧ�ܣ�');
               }
               $arr = json_decode($info, true);

               $data['nickname']=$arr['username'];
               if(empty($arr['portrait'])){
                   $data['avatar']="http://himg.bdimg.com/sys/portrait/item/".$arr['portrait'].".jpg";
               }else{
                   $data['avatar']="";
			   }

               //д�����ݿⲢ����
               return $this->denglu_str('baidu',$arr['userid'],$data);

           }else{
               exit('�Ƿ�����!');
           }
    }

    //����������
    function renren_callback($log_state='') { 
           $state=$this->ci->input->get('state', TRUE);
           $code=$this->ci->input->get('code', TRUE);

           if($log_state==$state){
               //��ȡACCSEE_TOTEN
               $token_url = "https://graph.renren.com/oauth/token";
               $post="grant_type=authorization_code&"
                      . "client_id=" . CS_Rrid. "&redirect_uri=" . urlencode($this->redirect_uri)
                      . "&client_secret=" . CS_Rrkey. "&code=" . $code;
               $response=$this->get_url_contents($token_url,$post,'post');

               if (strpos($response, "error_code") !== false){
                    exit('����ʧ�ܣ�û��ȡ��access_token��');
               }
               $response = json_decode($response, true);

               $data['nickname']=$response['user']['name'];
               $data['avatar']=$response['user']['avatar'][0]['url'];
               $data['access_token']=$response['access_token'];
               $data['refresh_token']=$response['refresh_token'];
               $data['expire_in']=$response['expires_in'];

               //д�����ݿⲢ����
               return $this->denglu_str('renren',$response['user']['id'],$data);

           }else{
               exit('�Ƿ�����!');
           }
    }

    //����������
    function kaixin_callback($log_state='') { 
           $state=$this->ci->input->get('state', TRUE);
           $code=$this->ci->input->get('code', TRUE);
           if($log_state==$state){
               //��ȡACCSEE_TOTEN
               $token_url = "https://api.kaixin001.com/oauth2/access_token";
               $post="grant_type=authorization_code&"
                      . "client_id=" . CS_Kxid. "&redirect_uri=" . urlencode($this->redirect_uri)
                      . "&client_secret=" . CS_Kxkey. "&code=" . $code;
               $response=$this->get_url_contents($token_url,$post,'post');

               if (strpos($response, "error_code") !== false){
                    exit('����ʧ�ܣ�û��ȡ��access_token��');
               }
               $response = json_decode($response, true);
               $data['access_token']=$response['access_token'];
               $data['refresh_token']=$response['refresh_token'];
               $data['expires_in']=$response['expires_in'];

               //��ȡ�û���Ϣ
               $get_user_info = "https://api.kaixin001.com/users/me.json?access_token=".$response['access_token'];
               $info  = $this->get_url_contents($get_user_info);
               if (strpos($info, "error_code") !== false)
               {
                   exit('��ȡ�û���Ϣʧ�ܣ�');
               }
               $arr = json_decode($info, true);

               $data['nickname']=$arr['name'];
               $data['avatar']=$arr['logo120'];

               //д�����ݿⲢ����
               return $this->denglu_str('kaixin',$arr['uid'],$data);

           }else{
               exit('�Ƿ�����!');
           }
    }

    //���귵��
    function douban_callback($log_state='') { 
           $state=$this->ci->input->get('state', TRUE);
           $code=$this->ci->input->get('code', TRUE);

           if($log_state==$state){
               //��ȡACCSEE_TOTEN
               $token_url = "https://www.douban.com/service/auth2/token";
               $post="grant_type=authorization_code&"
                      . "client_id=" . CS_Dbid. "&redirect_uri=" . urlencode($this->redirect_uri)
                      . "&client_secret=" . CS_Dbkey. "&code=" . $code;
               $response=$this->get_url_contents($token_url,$post,'post');

               if (strpos($response, "error") !== false){
                    exit('����ʧ�ܣ�û��ȡ��access_token��');
               }
               $response = json_decode($response, true);
               $data['access_token']=$response['access_token'];
               $data['refresh_token']=$response['refresh_token'];
               $data['expire_in']=$response['expires_in'];
               $uid=$response['douban_user_id'];

               //��ȡ�û���Ϣ
               $get_user_info = "http://bubbler.labs.douban.com/j/user/".$uid;
               $info  = $this->get_url_contents($get_user_info);
               if (strpos($info, "error") !== false)
               {
                   exit('��ȡ�û���Ϣʧ�ܣ�');
               }

               $arr = json_decode($info, true);
               $data['nickname']=$arr['title'];
               $data['avatar']=$arr['icon'];

               //д�����ݿⲢ����
               return $this->denglu_str('douban',$uid,$data);

           }else{
               exit('�Ƿ�����!');
           }
    }

    //������ݿ�
    function denglu_str($ac,$oid,$user=array()) { 

		 //�鿴���ݿ��Ƿ����
		 $cid=$this->class_id($ac);
		 $row=$this->ci->db->query("SELECT id,uid,nickname,avatar FROM ".CS_SqlPrefix."useroauth where oid='".$oid."' and cid=".$cid."")->row();
		 if($row){
			   $edit['access_token']=$user['access_token'];
			   $edit['refresh_token']=$user['refresh_token'];
			   $edit['expire_at']=(int)$user['expire_in'];
			   $this->ci->CsdjDB->get_update ('useroauth',$row->id,$edit);
			   $_SESSION['denglu__id'] = $row->id;
			   $_SESSION['denglu__name'] = $row->nickname;
			   $_SESSION['denglu__logo'] = $row->avatar;
		       return $row->uid;
		 }else{
			   $add['cid']=$cid;
			   $add['nickname']=get_bm($user['nickname']);
			   $add['avatar']=$user['avatar'];
			   $add['oid']=$oid;
			   $add['access_token']=$user['access_token'];
			   $add['refresh_token']=$user['refresh_token'];
			   $add['expire_at']=$user['expire_in'];
			   $ids=$this->ci->CsdjDB->get_insert('useroauth',$add);
			   $_SESSION['denglu__id'] = intval($ids);
			   $_SESSION['denglu__name'] = $add['nickname'];
			   $_SESSION['denglu__logo'] = $add['avatar'];
		       return 0;
		 }
	}

    //POST �� GET
    function get_url_contents($url,$post='',$type='get'){

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
                if($type=='post') {
		            curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
                }else{
		            curl_setopt($ch, CURLOPT_POST, 0);
                }
		        curl_setopt($ch, CURLOPT_USERAGENT, 'cscms');
		        $result = curl_exec($ch);
                curl_close($ch);
                return $result;
    }

    //��ȡ��¼����ID
    public  function class_id($ac) {
         if($ac=='weibo'){
              $cid=2;
		 }elseif($ac=='baidu'){
              $cid=3;
		 }elseif($ac=='renren'){
              $cid=4;
		 }elseif($ac=='kaixin'){
              $cid=5;
		 }elseif($ac=='douban'){
              $cid=6;
		 }else{
              $cid=1;
		 }
		 return $cid;
	}
}


