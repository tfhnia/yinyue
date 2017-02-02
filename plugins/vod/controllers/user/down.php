<?php if ( ! defined('CSCMS')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-04-08
 */
class Down extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
			$this->load->helper('dance');
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
	        $this->CsdjUser->User_Login();
	}

    //����
	public function index()
	{
		    $cid=intval($this->uri->segment(4));
		    $page=intval($this->uri->segment(5));
			//ģ��
			$tpl='down.html';
			//URL��ַ
		    $url='down/index';
            $sqlstr = "select {field} from ".CS_SqlPrefix."dance_down where uid=".$_SESSION['cscms__id'];
            if($cid>0){
                $sqlstr.= " and cid=".$cid."";
			}
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//װ��ģ��
			$title='�����صĸ��� - ��Ա����';
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
            $Mark_Text=$this->CsdjTpl->user_list($row,$url,$page,$tpl,$title,'',$sqlstr,$ids,true,false);
			$Mark_Text=str_replace("[dance:cid]",$cid,$Mark_Text);
			//��Ա��鵼��
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->labelif($Mark_Text);
			echo $Mark_Text;
	}
}

