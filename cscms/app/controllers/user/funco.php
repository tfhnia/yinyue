<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2015-03-07
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funco extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjTpl');
		    $this->load->model('CsdjUser');
			$this->lang->load('user');
			$this->CsdjUser->User_Login();
	}

    //�ÿ��б�
	public function index()
	{
		    $op=$this->uri->segment(4); //��ҳ
		    $page=intval($this->uri->segment(5)); //��ҳ
			if(empty($op)) $op='you';
			//ģ��
			$tpl='funco.html';
			//URL��ַ
		    $url='funco/index/'.$op;
			//��ǰ��Ա
		    $row=$this->CsdjDB->get_row_arr('user','*',$_SESSION['cscms__id']);
			if(empty($row['nichen'])) $row['nichen']=$row['name'];
			//SQL
            if($op=='you'){
                  $sqlstr = "select * from ".CS_SqlPrefix."funco where uida=".$_SESSION['cscms__id'];
			}else{
                  $sqlstr = "select * from ".CS_SqlPrefix."funco where uidb=".$_SESSION['cscms__id'];
			}
			$ids['uid']=$_SESSION['cscms__id'];
			$ids['uida']=$_SESSION['cscms__id'];
			$data=$data_content=$aliasname='';
            //װ��ģ��
		    $template=$this->load->view($tpl,$data,true);
		    $Mark_Text=$this->skins->topandend($template);
		    $Mark_Text=str_replace("{cscms:title}",L('funco_01'),$Mark_Text);
		    $Mark_Text=str_replace("{cscms:keywords}",Web_Keywords,$Mark_Text);
		    $Mark_Text=str_replace("{cscms:description}",Web_Description,$Mark_Text);
		    $Mark_Text=str_replace("{cscms:fid}",$op,$Mark_Text); //��ǰʹ�õ�fid
		    //Ԥ�ȳ��˷�ҳ
		    $pagenum=getpagenum($Mark_Text);
	        preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		    if(!empty($page_arr) && !empty($page_arr[2])){

					       $fields=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'id',$ids,0,$sqlstr);
					       $nums=$this->db->query($sqlstr)->num_rows(); //������
					       $Arr=userpage($sqlstr,$nums,$page_arr[3][0],$pagenum,$url,$page);
			               if($nums>0){
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
									 $datatmp='';
									 $uida=$row2['uida'];
									 $uidb=$row2['uidb'];
                                     if($op=='my'){
                                          $row2['uida']=$uidb;
                                          $row2['uidb']=$uida;
									 }
						             $datatmp=$this->skins->cscms_skins($fields,$page_arr[0][0],$page_arr[4][0],$row2,$sorti);
						             $sorti++;
						             $data_content.=$datatmp;
					            }
			               }
			               $Mark_Text=page_mark($Mark_Text,$Arr);	//��ҳ����
			               $Mark_Text=str_replace($page_arr[0][0],$data_content,$Mark_Text);
			}
		    unset($page_arr);
		    $Mark_Text=$this->skins->cscms_common($Mark_Text);
	        $Mark_Text=$this->skins->csskins($Mark_Text,$ids);
		    $Mark_Text=$this->skins->cscms_skins('user',$Mark_Text,$Mark_Text,$row);//������ǰ���ݱ�ǩ
			$Mark_Text=$this->skins->cscmsumenu($Mark_Text,$_SESSION['cscms__id']);
            $Mark_Text=$this->skins->template_parse($Mark_Text,true);
            echo $Mark_Text;
	}
}
