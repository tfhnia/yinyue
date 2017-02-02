<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-11-23
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->lang->load('user');
	}

    //�˳���¼
	public function index()
	{
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

	        //--------------------------- Ucenter ---------------------------
	        $log=(User_Uc_Mode==1)?uc_user_synlogout:'';
	        //--------------------------- Ucenter ---------------------------

            msg_url(L('logout_01').$log,userurl(site_url('user/login')),'ok');  //�˳���¼�ɹ�
	}
}
