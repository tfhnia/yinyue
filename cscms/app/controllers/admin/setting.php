<?php 
/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-01
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

	public function index()
	{
            //��ȡ�����ֻ�ģ��
			$this->load->helper('directory');
            $path=CSCMS.'tpl/mobile/';
            $dir_arr=directory_map($path, 1);
            $dirs=array();
		    if ($dir_arr) {
			    foreach ($dir_arr as $t) {
				    if (is_dir($path.$t)) {
						$confiles=$path.$t.'/config.php';
						if (file_exists($confiles)){
							$config=require_once($confiles);
					        $dirs[] = array(
						        'name' => $config['name'],
						        'path' => $config['path'],
					        );
						}
				    }
			    }
		    }
	        $data['mobile_skins'] = $dirs;
            $this->load->view('setting.html',$data);
	}

	public function ftp()
	{
		    $this->load->library('csup');
			$data['up']=$this->csup->init();
            $this->load->view('ftp_setting.html',$data);
	}

	public function tb()
	{
            $this->load->view('tb_setting.html');
	}

	public function denglu()
	{
            $this->load->view('denglu_setting.html');
	}

	public function save()
	{
		    $Web_Name = $this->input->post('Web_Name', TRUE, TRUE);
		    $Web_Url = $this->input->post('Web_Url', TRUE, TRUE);
		    $Web_Path = $this->input->post('Web_Path', TRUE, TRUE);
		    $Admin_Code = $this->input->post('Admin_Code', TRUE, TRUE);
		    $Web_Off = intval($this->input->post('Web_Off', TRUE));
		    $Web_Onneir = $this->input->post('Web_Onneir', TRUE, TRUE);
		    $Web_Mode = intval($this->input->post('Web_Mode', TRUE));
		    $Web_Icp = $this->input->post('Web_Icp', TRUE, TRUE);
		    $Admin_QQ = $this->input->post('Admin_QQ', TRUE, TRUE);
		    $Admin_Tel = $this->input->post('Admin_Tel', TRUE, TRUE);
		    $Admin_Mail = $this->input->post('Admin_Mail', TRUE, TRUE);
		    $Web_Key = $this->input->post('Web_Key', TRUE, TRUE);
		    $Web_Count = $_POST['Web_Count'];
		    $Web_Title = $this->input->post('Web_Title', TRUE);
		    $Web_Keywords = $this->input->post('Web_Keywords', TRUE);
		    $Web_Description = $this->input->post('Web_Description', TRUE);
		    $Web_Notice = $this->input->post('Web_Notice', TRUE, TRUE);
		    $Pl_Modes = intval($this->input->post('Pl_Modes', TRUE));
		    $Pl_Youke = intval($this->input->post('Pl_Youke', TRUE));
		    $Pl_Num = intval($this->input->post('Pl_Num', TRUE));
		    $Pl_Yy_Name = $this->input->post('Pl_Yy_Name', TRUE);
		    $Pl_Ds_Name = $this->input->post('Pl_Ds_Name', TRUE);
		    $Pl_Cy_Id = $this->input->post('Pl_Cy_Id', TRUE, TRUE);
		    $Pl_Str = $this->input->post('Pl_Str', TRUE, TRUE);
		    $Cache_Is = intval($this->input->post('Cache_Is', TRUE));
		    $Cache_Time = intval($this->input->post('Cache_Time', TRUE));
		    $CS_Play_w = intval($this->input->post('CS_Play_w'));
		    $CS_Play_h = intval($this->input->post('CS_Play_h'));
		    $CS_Play_sw = intval($this->input->post('CS_Play_sw'));
		    $CS_Play_sh = intval($this->input->post('CS_Play_sh'));
		    $CS_Play_AdloadTime = intval($this->input->post('CS_Play_AdloadTime'));
		    $Html_Index = $this->input->post('Html_Index', TRUE, TRUE);
		    $Html_StopTime = intval($this->input->post('Html_StopTime', TRUE));
		    $Html_PageNum = intval($this->input->post('Html_PageNum', TRUE));
		    $CS_Language = $this->input->post('CS_Language', TRUE, TRUE);

		    $CS_Cache_Time = intval($this->input->post('CS_Cache_Time', TRUE));
		    $CS_Cache_Dir = $this->input->post('CS_Cache_Dir', TRUE, TRUE);
		    $CS_Cache_On = $this->input->post('CS_Cache_On', TRUE, TRUE);

		    $Mobile_Is = intval($this->input->post('Mobile_Is', TRUE));
		    $Mobile_Url = $this->input->post('Mobile_Url', TRUE, TRUE);
		    $Mobile_Win = intval($this->input->post('Mobile_Win', TRUE));
		    $Mobile_Skins = $this->input->post('Mobile_Skins', TRUE, TRUE);
		    $Web_Skins = $this->input->post('Web_Skins', TRUE, TRUE);

            if($CS_Cache_Time==0)     $CS_Cache_Time=600;
            if(empty($CS_Cache_Dir))  $CS_Cache_Dir="sql";
            if($CS_Cache_On!="FALSE") $CS_Cache_On="TRUE";

            if($Html_StopTime==0)      $Html_StopTime=1;
            if($Html_PageNum==0)       $Html_PageNum=20;
            if($Pl_Num==0)             $Pl_Num=10;
            if($Cache_Time==0)         $Cache_Time=600;
            if($CS_Play_w==0)          $CS_Play_w=445;
            if($CS_Play_h==0)          $CS_Play_h=64;
            if($CS_Play_sw==0)         $CS_Play_sw=600;
            if($CS_Play_sh==0)         $CS_Play_sh=450;
            if($CS_Play_AdloadTime==0) $CS_Play_AdloadTime=10;

            //HTMLת��
            $Web_Onneir= str_encode($Web_Onneir); 
            $Web_Title= str_encode($Web_Title); 
            $Web_Keywords= str_encode($Web_Keywords); 
            $Web_Description= str_encode($Web_Description); 
            $Web_Notice=str_encode($Web_Notice); 
		    $Web_Count= str_encode($Web_Count); 


            //�ж���Ҫ���ݲ���Ϊ��
		    if (empty($Web_Name)||empty($Web_Url)||empty($Web_Path)||empty($Admin_Code)){
			      admin_msg(L('setting_err_01'),site_url('setting'),'no');  //վ�����ơ�������·������֤�벻��Ϊ��
		    }

			//�ж�������ҳ�ļ���ʽ
			$file_ext = strtolower(trim(substr(strrchr($Html_Index, '.'), 1)));
			if($file_ext!='html' && $file_ext!='htm' && $file_ext!='shtm' && $file_ext!='shtml'){
			      admin_msg(L('setting_err_60'),site_url('setting'),'no');  //��̬�ļ���ʽ����ȷ
			}

            //�ж����ݿ⻺��Ŀ¼
            if($CS_Cache_Dir!=CS_Cache_Dir){
		         if(file_exists(FCPATH.'cache/'.CS_Cache_Dir)){
			            if(!rename(FCPATH.'cache/'.CS_Cache_Dir,FCPATH.'cache/'.$CS_Cache_Dir)){
                             admin_msg(vsprintf(L('setting_err_02'),array('./cache/'.CS_Cache_Dir)),site_url('setting'),'no');
                        }
				 }else{
			            @mkdir(FCPATH.'cache/'.$CS_Cache_Dir);
				 }
			}

            //�޸����ݿ⻺������
            $this->load->helper('file');
	        $db_cof=read_file(FCPATH."cscms/lib/Cs_DB.php");
	        $db_cof=preg_replace("/'CS_Cache_On',(.*?)\)/","'CS_Cache_On',".$CS_Cache_On.")",$db_cof);
	        $db_cof=preg_replace("/'CS_Cache_Dir','(.*?)'/","'CS_Cache_Dir','".$CS_Cache_Dir."'",$db_cof);
	        $db_cof=preg_replace("/'CS_Cache_Time',(.*?)\)/","'CS_Cache_Time',".$CS_Cache_Time.")",$db_cof);
	        if(!write_file(FCPATH."cscms/lib/Cs_DB.php", $db_cof)){
                 admin_msg(vsprintf(L('setting_err_03'),array('./cscms/lib/Cs_DB.php')),site_url('setting'),'no');
			}

	        $strs="<?php"."\r\n";
	        $strs.="define('Web_Name','".$Web_Name."'); //վ������  \r\n";
	        $strs.="define('Web_Url','".$Web_Url."'); //վ������  \r\n";
	        $strs.="define('Web_Path','".$Web_Path."'); //վ��·��  \r\n";
	        $strs.="define('Admin_Code','".$Admin_Code."');  //��̨��֤��  \r\n";
	        $strs.="define('Web_Off',".$Web_Off.");  //��վ����  \r\n";
	        $strs.="define('Web_Onneir','".$Web_Onneir."');  //��վ�ر�����  \r\n";
	        $strs.="define('Web_Mode',".$Web_Mode.");  //��վ����ģʽ  \r\n";
	        $strs.="define('Html_Index','".$Html_Index."');  //��ҳ��̬URL  \r\n";
	        $strs.="define('Html_StopTime',".$Html_StopTime.");  //���ɼ������  \r\n";
	        $strs.="define('Html_PageNum',".$Html_PageNum.");  //ÿҳ��������  \r\n";

	        $strs.="define('Web_Icp','".$Web_Icp."');  //��վICP  \r\n";
	        $strs.="define('Admin_QQ','".$Admin_QQ."');  //վ��QQ  \r\n";
	        $strs.="define('Admin_Tel','".$Admin_Tel."');  //վ���绰  \r\n";
	        $strs.="define('Admin_Mail','".$Admin_Mail."');  //վ��EMAIL  \r\n";
	        $strs.="define('Web_Key','".$Web_Key."');  //��������  \r\n";
	        $strs.="define('Web_Count','".$Web_Count."');  //ͳ�ƴ���  \r\n";
	        $strs.="define('Web_Title','".$Web_Title."'); //SEO-����  \r\n";
	        $strs.="define('Web_Keywords','".$Web_Keywords."'); //SEO-Keywords  \r\n";
	        $strs.="define('Web_Description','".$Web_Description."'); //SEO-description  \r\n";
	        $strs.="define('Web_Notice','".$Web_Notice."');  //��վ����  \r\n";

	        $strs.="define('Pl_Modes',".$Pl_Modes.");  //���۷�ʽ  \r\n";
	        $strs.="define('Pl_Youke',".$Pl_Youke.");  //�ο��Ƿ��������  \r\n";
	        $strs.="define('Pl_Num',".$Pl_Num.");  //����ÿҳ����  \r\n";
	        $strs.="define('Pl_Yy_Name','".$Pl_Yy_Name."');  //�����˺�  \r\n";
	        $strs.="define('Pl_Ds_Name','".$Pl_Ds_Name."');  //��˵�˺�  \r\n";
			$strs.="define('Pl_Cy_Id','".$Pl_Cy_Id."');  //����APP_Id  \r\n";
	        $strs.="define('Pl_Str','".$Pl_Str."');  //���۹����ַ�  \r\n";

	        $strs.="define('Cache_Is',".$Cache_Is.");  //���濪��  \r\n";
	        $strs.="define('Cache_Time',".$Cache_Time.");  //����ʱ��  \r\n";

	        $strs.="define('CS_Play_w',".$CS_Play_w.");    \r\n";
	        $strs.="define('CS_Play_h',".$CS_Play_h.");    \r\n";
	        $strs.="define('CS_Play_sw',".$CS_Play_sw.");    \r\n";
	        $strs.="define('CS_Play_sh',".$CS_Play_sh.");    \r\n";
	        $strs.="define('CS_Play_AdloadTime',".$CS_Play_AdloadTime."); //��Ƶ����ǰ���ʱ��    \r\n";
	        $strs.="define('CS_Language','".$CS_Language."'); //��վ����,englishӢ�ģ�zh_cn���� \r\n";

	        $strs.="define('Mobile_Is',".$Mobile_Is.");    //�ֻ��Ż��Ƿ���    \r\n";
	        $strs.="define('Mobile_Url','".$Mobile_Url."');  //�ֻ��Ż�����    \r\n";
	        $strs.="define('Mobile_Win',".$Mobile_Win.");   //�����Ƿ���Է����ֻ�ҳ��    \r\n";
	        $strs.="define('Mobile_Skins','".$Mobile_Skins."');  //�ֻ��Ż�ģ��·��    \r\n";
	        $strs.="define('Web_Skins','".$Web_Skins."');  //Ĭ����ҳģ��·��    ";

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_Config.php', $strs)){
                      admin_msg(L('setting_err_03'),site_url('setting'),'no');
            }else{
                      admin_msg(L('setting_err_04'),site_url('setting'));
            }
	}

	public function ftp_save()
	{
		    $UP_Mode = intval($this->input->post('UP_Mode', TRUE));
		    $UP_Size = intval($this->input->post('UP_Size', TRUE));
		    $UP_Type = $this->input->post('UP_Type', TRUE, TRUE);

		    $UP_Url = $this->input->post('UP_Url', TRUE, TRUE);
		    $UP_Pan = str_replace("\\","/",$this->input->post('UP_Pan', TRUE, TRUE));

		    $FTP_Url = $this->input->post('FTP_Url', TRUE, TRUE);
		    $FTP_Port = intval($this->input->post('FTP_Port', TRUE));
		    $FTP_Server = $this->input->post('FTP_Server', TRUE, TRUE);
		    $FTP_Dir = $this->input->post('FTP_Dir', TRUE, TRUE);
		    $FTP_Name = $this->input->post('FTP_Name', TRUE, TRUE);
		    $FTP_Pass = $this->input->post('FTP_Pass', TRUE);
		    $FTP_Ive = $this->input->post('FTP_Ive', TRUE, TRUE);

            if($UP_Mode==0)   $UP_Mode=1;
            if($UP_Size==0)   $UP_Size=1024;
            if($FTP_Port==0)  $FTP_Port=21;
			if($FTP_Pass==substr(FTP_Pass,0,3).'******'){
                  $FTP_Pass=FTP_Pass;
			}

            //�ж���Ҫ���ݲ���Ϊ��
		    if ($UP_Mode==2 && (empty($FTP_Url)||empty($FTP_Server)||empty($FTP_Name)||empty($FTP_Pass))){
			      admin_msg(L('setting_72'),'javascript:history.back();','no');
		    }
		    if (!empty($UP_Pan) && empty($UP_Url)){
			      admin_msg(L('setting_73'),'javascript:history.back();','no');
		    }
			if(empty($UP_Pan) || $UP_Url=='http://'.Web_Url.Web_Path){
                 $UP_Url='';
			}

	        $strs="<?php"."\r\n";
	        $strs.="define('UP_Mode',".$UP_Mode.");      //��Ա�ϴ�������ʽ  1վ�ڣ�2FTP��3��ţ��4���̣�5�����ƣ�..... \r\n";
	        $strs.="define('UP_Size',".$UP_Size.");      //�ϴ�֧�ֵ����MB \r\n";
	        $strs.="define('UP_Type','".$UP_Type."');  //�ϴ�֧�ֵĸ�ʽ \r\n";
	        $strs.="define('UP_Url','".$UP_Url."');  //���ط��ʵ�ַ \r\n";
	        $strs.="define('UP_Pan','".$UP_Pan."');  //���ش洢·�� \r\n";

	        $strs.="define('FTP_Url','".$FTP_Url."');      //Զ��FTP���ӵ�ַ     \r\n";
	        $strs.="define('FTP_Server','".$FTP_Server."');      //Զ��FTP������IP    \r\n";
	        $strs.="define('FTP_Dir','".$FTP_Dir."');      //Զ��FTPĿ¼    \r\n";
	        $strs.="define('FTP_Port','".$FTP_Port."');      //Զ��FTP�˿�    \r\n";
	        $strs.="define('FTP_Name','".$FTP_Name."');      //Զ��FTP�ʺ�    \r\n";
	        $strs.="define('FTP_Pass','".$FTP_Pass."');      //Զ��FTP����    \r\n";
	        $strs.="define('FTP_Ive',".$FTP_Ive.");      //�Ƿ�ʹ�ñ���ģʽ   ";

			if($UP_Mode>2){ //�����ϴ��޸�����
                 $this->load->library('csup');
				 $this->csup->edit($UP_Mode);
			}

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_Ftp.php', $strs)){
                      admin_msg(vsprintf(L('setting_err_02'),array('cscms/lib/Cs_Ftp.php')),site_url('setting/ftp'),'no');
            }else{
                      admin_msg(L('setting_err_04'),site_url('setting/ftp'));
            }
	}

	public function tb_save()
	{
		    $CS_WaterMark = intval($this->input->post('CS_WaterMark', TRUE));
		    $CS_WaterMode = intval($this->input->post('CS_WaterMode', TRUE));
		    $CS_WaterFontSize = intval($this->input->post('CS_WaterFontSize', TRUE));
		    $CS_WaterLocation = intval($this->input->post('CS_WaterLocation', TRUE));
		    $CS_WaterFont = $this->input->post('CS_WaterFont', TRUE, TRUE);
		    $CS_WaterFontColor = $this->input->post('CS_WaterFontColor', TRUE, TRUE);

		    $CS_WaterLogo = $this->input->post('CS_WaterLogo', TRUE, TRUE);
		    $CS_WaterLogotm = intval($this->input->post('CS_WaterLogotm', TRUE));
		    $CS_WaterLocations = $this->input->post('CS_WaterLocations', TRUE, TRUE);


            if($CS_WaterLogotm>100)   $CS_WaterLogotm=100;
            if($CS_WaterFontSize==0)  $CS_WaterFontSize=12;
            if($CS_WaterLogotm==0)    $CS_WaterLogotm=90;
            if($CS_WaterLocation==0)  $CS_WaterLocation=2;

	        $strs="<?php"."\r\n";
	        $strs.="define('CS_WaterMark',".$CS_WaterMark.");  //ˮӡ����  \r\n";
	        $strs.="define('CS_WaterMode',".$CS_WaterMode.");  //ˮӡ����  \r\n";
	        $strs.="define('CS_WaterFontSize',".$CS_WaterFontSize.");  //ˮӡ�����С  \r\n";
	        $strs.="define('CS_WaterLocation',".$CS_WaterLocation.");  //ˮӡλ��  \r\n";
	        $strs.="define('CS_WaterFont','".$CS_WaterFont."');  //ˮӡ����  \r\n";
	        $strs.="define('CS_WaterFontColor','".$CS_WaterFontColor."');  //ˮӡ��ɫ  \r\n";
	        $strs.="define('CS_WaterLogo','".$CS_WaterLogo."');  //ˮӡͼƬ·��  \r\n";
	        $strs.="define('CS_WaterLogotm','".$CS_WaterLogotm."');  //ˮӡ����  \r\n";
	        $strs.="define('CS_WaterLocations','".$CS_WaterLocations."');  //LOGOͼƬ����λ�� \r\n";

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_Water.php', $strs)){
                      admin_msg(vsprintf(L('setting_err_02'),array('cscms/lib/Cs_Water.php')),site_url('setting/tb'),'no');
            }else{
                      admin_msg(L('setting_err_04'),site_url('setting/tb'));
            }
	}

	public function denglu_save()
	{
		    $CS_Appmode = intval($this->input->post('CS_Appmode', TRUE));
		    $CS_Appid = $this->input->post('CS_Appid', TRUE, TRUE);
		    $CS_Appkey = $this->input->post('CS_Appkey', TRUE, TRUE);
		    $CS_Qqmode = intval($this->input->post('CS_Qqmode', TRUE));
		    $CS_Qqid = $this->input->post('CS_Qqid', TRUE, TRUE);
		    $CS_Qqkey = $this->input->post('CS_Qqkey', TRUE, TRUE);
		    $CS_Wbmode = intval($this->input->post('CS_Wbmode', TRUE));
		    $CS_Wbid = $this->input->post('CS_Wbid', TRUE, TRUE);
		    $CS_Wbkey = $this->input->post('CS_Wbkey', TRUE, TRUE);
		    $CS_Bdmode = intval($this->input->post('CS_Bdmode', TRUE));
		    $CS_Bdid = $this->input->post('CS_Bdid', TRUE, TRUE);
		    $CS_Bdkey = $this->input->post('CS_Bdkey', TRUE, TRUE);
		    $CS_Rrmode = intval($this->input->post('CS_Rrmode', TRUE));
		    $CS_Rrid = $this->input->post('CS_Rrid', TRUE, TRUE);
		    $CS_Rrkey = $this->input->post('CS_Rrkey', TRUE, TRUE);
		    $CS_Kxmode = intval($this->input->post('CS_Kxmode', TRUE));
		    $CS_Kxid = $this->input->post('CS_Kxid', TRUE, TRUE);
		    $CS_Kxkey = $this->input->post('CS_Kxkey', TRUE, TRUE);
		    $CS_Dbmode = intval($this->input->post('CS_Dbmode', TRUE));
		    $CS_Dbid = $this->input->post('CS_Dbid', TRUE, TRUE);
		    $CS_Dbkey = $this->input->post('CS_Dbkey', TRUE, TRUE);

	        $strs="<?php"."\r\n";
	        $strs.="define('CS_Appmode',".$CS_Appmode.");  //��������¼��ʽ��0Ϊ������1Ϊ�ٷ� \r\n";
	        $strs.="define('CS_Appid','".$CS_Appid."');  //�ٷ�Appid \r\n";
	        $strs.="define('CS_Appkey','".$CS_Appkey."');  //�ٷ�Appkey \r\n\r\n";
	        $strs.="define('CS_Qqmode',".$CS_Qqmode.");  //QQ��½���� \r\n";
	        $strs.="define('CS_Qqid','".$CS_Qqid."');  //QQ��½id \r\n";
	        $strs.="define('CS_Qqkey','".$CS_Qqkey."');  //QQ��½key \r\n\r\n";
	        $strs.="define('CS_Wbmode',".$CS_Wbmode.");  //΢����½����  \r\n";
	        $strs.="define('CS_Wbid','".$CS_Wbid."');   //΢����½id  \r\n";
	        $strs.="define('CS_Wbkey','".$CS_Wbkey."');  //΢����½key  \r\n\r\n";
	        $strs.="define('CS_Bdmode',".$CS_Bdmode.");  //�ٶȵ�½����  \r\n";
	        $strs.="define('CS_Bdid','".$CS_Bdid."');   //�ٶȵ�½id  \r\n";
	        $strs.="define('CS_Bdkey','".$CS_Bdkey."');  //�ٶȵ�½key  \r\n\r\n";
	        $strs.="define('CS_Rrmode',".$CS_Rrmode.");  //���˵�½����  \r\n";
	        $strs.="define('CS_Rrid','".$CS_Rrid."');   //���˵�½id  \r\n";
	        $strs.="define('CS_Rrkey','".$CS_Rrkey."');  //���˵�½key  \r\n\r\n";
	        $strs.="define('CS_Kxmode',".$CS_Kxmode.");  //���ĵ�½����  \r\n";
	        $strs.="define('CS_Kxid','".$CS_Kxid."');   //���ĵ�½id  \r\n";
	        $strs.="define('CS_Kxkey','".$CS_Kxkey."');  //���ĵ�½key  \r\n\r\n";
	        $strs.="define('CS_Dbmode',".$CS_Dbmode.");  //�����½����  \r\n";
	        $strs.="define('CS_Dbid','".$CS_Dbid."');   //�����½id  \r\n";
	        $strs.="define('CS_Dbkey','".$CS_Dbkey."');  //�����½key ";

            //д�ļ�
            if (!write_file(CSCMS.'lib/Cs_Denglu.php', $strs)){
                      admin_msg(vsprintf(L('setting_err_02'),array('cscms/lib/Cs_Denglu.php')),site_url('setting/denglu'),'no');
            }else{
                      admin_msg(L('setting_err_04'),site_url('setting/denglu'));
            }
	}
}

