<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-10-31
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pay extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
			$this->lang->load('admin_pay');
	        $this->CsdjAdmin->Admin_Login();
	}

	public function index()
	{
            $this->load->view('pay_setting.html');
	}

	public function save()
	{
		    $CS_Alipay = intval($this->input->post('CS_Alipay', TRUE));
		    $CS_Alipay_JK = intval($this->input->post('CS_Alipay_JK', TRUE));
		    $CS_Alipay_ID = trim($this->input->post('CS_Alipay_ID', TRUE));
		    $CS_Alipay_Key = trim($this->input->post('CS_Alipay_Key', TRUE));
		    $CS_Alipay_Name = trim($this->input->post('CS_Alipay_Name', TRUE));

		    $CS_Tenpay = intval($this->input->post('CS_Tenpay', TRUE));
		    $CS_Tenpay_ID = trim($this->input->post('CS_Tenpay_ID', TRUE));
		    $CS_Tenpay_Key = trim($this->input->post('CS_Tenpay_Key', TRUE));

		    $CS_Wypay = intval($this->input->post('CS_Wypay', TRUE));
		    $CS_Wypay_ID = trim($this->input->post('CS_Wypay_ID', TRUE));
		    $CS_Wypay_Key = trim($this->input->post('CS_Wypay_Key', TRUE));

		    $CS_Kqpay = intval($this->input->post('CS_Kqpay', TRUE));
		    $CS_Kqpay_ID = trim($this->input->post('CS_Kqpay_ID', TRUE));
		    $CS_Kqpay_Key = trim($this->input->post('CS_Kqpay_Key', TRUE));

		    $CS_Ybpay = intval($this->input->post('CS_Ybpay', TRUE));
		    $CS_Ybpay_ID = trim($this->input->post('CS_Ybpay_ID', TRUE));
		    $CS_Ybpay_Key = trim($this->input->post('CS_Ybpay_Key', TRUE));

		    $CS_Cspay = intval($this->input->post('CS_Cspay', TRUE));
		    $CS_Cspay_ID = trim($this->input->post('CS_Cspay_ID', TRUE));
		    $CS_Cspay_Key = trim($this->input->post('CS_Cspay_Key', TRUE));

	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Alipay',".$CS_Alipay.");  //֧��������  \r\n";
	        $strs.="define('CS_Alipay_JK',".$CS_Alipay_JK.");  //֧�����ӿ�,1Ϊ˫���ܽӿڣ�2Ϊ��ʱ���ˣ�3Ϊ�ֶ��ӿ�\r\n";
	        $strs.="define('CS_Alipay_Name','".$CS_Alipay_Name."');  //֧�����ʺ�  \r\n";
	        $strs.="define('CS_Alipay_ID','".$CS_Alipay_ID."');  //������ID  \r\n";
	        $strs.="define('CS_Alipay_Key','".$CS_Alipay_Key."');  //��ȫ��Ч��KEY  \r\n";
	        $strs.="define('CS_Tenpay',".$CS_Tenpay.");  //�Ƹ�ͨ����  \r\n";
	        $strs.="define('CS_Tenpay_ID','".$CS_Tenpay_ID."');  //�Ƹ�ͨID  \r\n";
	        $strs.="define('CS_Tenpay_Key','".$CS_Tenpay_Key."');  //��ȫ��Ч��KEY  \r\n";
	        $strs.="define('CS_Wypay',".$CS_Wypay.");  //��������  \r\n";
	        $strs.="define('CS_Wypay_ID','".$CS_Wypay_ID."');  //����ID  \r\n";
	        $strs.="define('CS_Wypay_Key','".$CS_Wypay_Key."');  //��ȫ��Ч��KEY  \r\n";
	        $strs.="define('CS_Kqpay',".$CS_Kqpay.");  //��Ǯ  \r\n";
	        $strs.="define('CS_Kqpay_ID','".$CS_Kqpay_ID."');  //��ǮID  \r\n";
	        $strs.="define('CS_Kqpay_Key','".$CS_Kqpay_Key."');  //��ȫ��Ч��KEY\r\n";
	        $strs.="define('CS_Ybpay',".$CS_Ybpay.");  //�ױ�֧��  \r\n";
	        $strs.="define('CS_Ybpay_ID','".$CS_Ybpay_ID."');  //�ױ�ID  \r\n";
	        $strs.="define('CS_Ybpay_Key','".$CS_Ybpay_Key."');  //��ȫ��Ч��KEY \r\n";
	        $strs.="define('CS_Cspay',".$CS_Cspay.");  //�ٷ�֧��  \r\n";
	        $strs.="define('CS_Cspay_ID','".$CS_Cspay_ID."');  //�ٷ�ID  \r\n";
	        $strs.="define('CS_Cspay_Key','".$CS_Cspay_Key."');  //��ȫ��Ч��KEY";

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_Pay.php', $strs)){
                      admin_msg(L('plub_01'),site_url('pay'),'no');
            }else{
                      admin_msg(L('plub_02'),site_url('pay'));
            }
	}

    //֧����¼�б�
	public function lists()
	{
            $kstime = $this->input->get_post('kstime',true);
            $jstime = $this->input->get_post('jstime',true);
            $dingdan= str_replace('%','',$this->input->get_post('dingdan',true));
            $pid  = intval($this->input->get_post('pid'));
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;
			$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
			$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
			if($kstimes>$jstimes) $kstimes=strtotime($kstime);

	        $data['page'] = $page;
	        $data['dingdan'] = $dingdan;
	        $data['pid'] = $pid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;
	        $data['kstime'] = $kstime;
	        $data['jstime'] = empty($jstime)?date('Y-m-d'):$jstime;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."pay where 1=1";
			if($pid>0){
	             $sql_string.= " and pid=".($pid-1)."";
			}
			if(!empty($dingdan)){
	             $sql_string.= " and dingdan like '%".$dingdan."%'";
			}
			if(!empty($key)){
				 if($zd=='name'){
                     $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				 }else{
                     $uid=$key;
				 }
				 $sql_string.= " and uid=".intval($uid)."";
			}
			if($kstimes>0){
	             $sql_string.= " and addtime>".$kstimes."";
			}
			if($jstimes>0){
	             $sql_string.= " and addtime<".$jstimes."";
			}
	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pay/lists')."?dingdan=".$dingdan."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&pid=".$pid;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['pay'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('pay_list.html',$data);
	}

    //ǿ�Ƹ��¶������ɹ�
	public function init()
	{
 	        $id = intval($this->input->get('id'));
            $row=$this->db->query("SELECT * FROM ".CS_SqlPrefix."pay where id=".$id."")->row();
			if($row){
				 $edit['pid']=1;
                 $this->CsdjDB->get_update('pay',$id,$edit);
				 //����Ա���ӽ�Ǯ
                 $this->db->query("update ".CS_SqlPrefix."user set rmb=rmb+".$row->rmb." where id=".$row->uid."");
				 //�����ʼ�
				 $email=$this->CsdjDB->getzd('user','email',$row->uid);
		         $this->load->model('CsdjEmail');
			     $title=L('plub_03');
                 $emailneir=vsprintf(L('plub_04'),array($row->rmb));
			     $this->CsdjEmail->send($email,$title,$emailneir);  //����֪ͨ�ʼ�
			}
            admin_msg(L('plub_05'),'javascript:history.back();');
	}

    //֧������ɾ��
	public function del()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_06'),'javascript:history.back();','no');  //���ݲ����
            $this->CsdjDB->get_del('pay',$id);
            admin_msg(L('plub_05'),site_url('pay/lists'),'ok');  //�����ɹ�
	}

    //���Ѽ�¼�б�
	public function spend()
	{
            $kstime = $this->input->get_post('kstime',true);
            $jstime = $this->input->get_post('jstime',true);
            $sid  = intval($this->input->get_post('sid'));
            $dir  = $this->input->get_post('dir',true);
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;
			$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
			$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
			if($kstimes>$jstimes) $kstimes=strtotime($kstime);

	        $data['page'] = $page;
	        $data['dir'] = $dir;
	        $data['sid'] = $sid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;
	        $data['kstime'] = $kstime;
	        $data['jstime'] = empty($jstime)?date('Y-m-d'):$jstime;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."spend where 1=1";
			if($sid>0){
	             $sql_string.= " and sid=".($sid-1)."";
			}
			if(!empty($key)){
				 if($zd=='name'){
                     $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				 }else{
                     $uid=$key;
				 }
				 $sql_string.= " and uid=".intval($uid)."";
			}
			if(!empty($dir)){
				 $sql_string.= " and dir='".$dir."'";
			}
			if($kstimes>0){
	             $sql_string.= " and addtime>".$kstimes."";
			}
			if($jstimes>0){
	             $sql_string.= " and addtime<".$jstimes."";
			}
	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pay/spend')."?dir=".$dir."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&sid=".$sid;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['spend'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('pay_spend.html',$data);
	}

    //���Ѽ�¼ɾ��
	public function spend_del()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_06'),'javascript:history.back();','no');  //���ݲ����
            $this->CsdjDB->get_del('spend',$id);
            admin_msg(L('plub_05'),site_url('pay/spend'),'ok');  //�����ɹ�
	}

    //�ֳɼ�¼�б�
	public function income()
	{
            $kstime = $this->input->get_post('kstime',true);
            $jstime = $this->input->get_post('jstime',true);
            $dir  = $this->input->get_post('dir',true);
            $sid  = intval($this->input->get_post('sid'));
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;
			$kstimes=empty($kstime)?0:strtotime($kstime)-86400;
			$jstimes=empty($jstime)?0:strtotime($jstime)+86400;
			if($kstimes>$jstimes) $kstimes=strtotime($kstime);

	        $data['page'] = $page;
	        $data['dir'] = $dir;
	        $data['sid'] = $sid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;
	        $data['kstime'] = $kstime;
	        $data['jstime'] = empty($jstime)?date('Y-m-d'):$jstime;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."income where 1=1";
			if($sid>0){
	             $sql_string.= " and sid=".($sid-1)."";
			}
			if(!empty($key)){
				 if($zd=='name'){
                     $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				 }else{
                     $uid=$key;
				 }
				 $sql_string.= " and uid=".intval($uid)."";
			}
			if(!empty($dir)){
				 $sql_string.= " and dir='".$dir."'";
			}
			if($kstimes>0){
	             $sql_string.= " and addtime>".$kstimes."";
			}
			if($jstimes>0){
	             $sql_string.= " and addtime<".$jstimes."";
			}
	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pay/income')."?dir=".$dir."&zd=".$zd."&kstime=".$kstime."&jstime=".$jstime."&key=".$key."&sid=".$sid;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['income'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('pay_income.html',$data);
	}

    //�ֳɼ�¼ɾ��
	public function income_del()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_06'),'javascript:history.back();','no');  //���ݲ����
            $this->CsdjDB->get_del('income',$id);
            admin_msg(L('plub_05'),site_url('pay/income'),'ok');  //�����ɹ�
	}

    //��ֵ���б�
	public function card()
	{
            $card = str_replace('%','',$this->input->get_post('card',true));
            $sid  = intval($this->input->get_post('sid'));
            $zd   = $this->input->get_post('zd',true);
            $key  = $this->input->get_post('key',true);
 	        $page = intval($this->input->get('page'));
            if($page==0) $page=1;

	        $data['page'] = $page;
	        $data['sid'] = $sid;
	        $data['zd'] = $zd;
	        $data['key'] = $key;
	        $data['card'] = $card;

            $sql_string = "SELECT * FROM ".CS_SqlPrefix."paycard where 1=1";
			if(!empty($card)){
	             $sql_string.= " and card like '%".$card."%'";
			}
			if($sid==1){
	             $sql_string.= " and uid>0";
			}
			if($sid==2){
	             $sql_string.= " and uid=0";
			}
			if(!empty($key)){
				 if($zd=='name'){
                     $uid=$this->CsdjDB->getzd('user','id',$key,'name');
				 }else{
                     $uid=$key;
				 }
				 $sql_string.= " and uid=".intval($uid)."";
			}

	        $sql_string.= " order by addtime desc";
            $count_sql = str_replace('*','count(*) as count',$sql_string);
	        $query = $this->db->query($count_sql)->result_array();
	        $total = $query[0]['count'];

            $base_url = site_url('pay/card')."?zd=".$zd."&card=".$card."&key=".$key."&sid=".$sid;
	        $per_page = 15; 
            $totalPages = ceil($total / $per_page); // ��ҳ��
	        $data['nums'] = $total;
            if($total<$per_page){
                  $per_page=$total;
            }
            $sql_string.=' limit '. $per_page*($page-1) .','. $per_page;
	        $query = $this->db->query($sql_string);

	        $data['paycard'] = $query->result();
	        $data['pages'] = get_admin_page($base_url,$totalPages,$page,10); //��ȡ��ҳ��

            $this->load->view('pay_card.html',$data);
	}

    //������ֵ��
	public function card_add()
	{
            $this->load->view('pay_card_add.html');
	}

    //���ɳ�ֵ��
	public function card_save()
	{
		    $this->load->helper('string');
            $rmb = intval($this->input->post('rmb'));
            $nums = intval($this->input->post('nums'));
			for($j=0;$j<$nums;$j++){	
                  $add['rmb']=$rmb;
                  $add['card']=date('Ymd').random_string('alnum',10);
                  $add['pass']=random_string('alnum',6);
                  $add['addtime']=time();
				  $this->CsdjDB->get_insert('paycard',$add);
			}
            admin_msg(L('plub_05'),site_url('pay/card'),'ok');  //�����ɹ�
	}

    //ɾ����ֵ��
	public function card_del()
	{
            $id = $this->input->get_post('id',true);
			if(empty($id)) admin_msg(L('plub_06'),'javascript:history.back();','no');  //���ݲ����
            $this->CsdjDB->get_del('paycard',$id);
            admin_msg(L('plub_05'),site_url('pay/card'),'ok');  //�����ɹ�
	}
}

