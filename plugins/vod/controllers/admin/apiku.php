<?php if ( ! defined('IS_ADMIN')) exit('No direct script access allowed');
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-12-03
 */
class Apiku extends Cscms_Controller {

	function __construct(){
		    parent::__construct();
		    $this->load->helper('vod');
		    $this->load->model('CsdjAdmin');
	        $this->CsdjAdmin->Admin_Login();
	}

    //��Դ���б�
	public function index()
	{
            $ac = $this->input->get('ac',true);
            $op = $this->input->get('op',true);
            $do = $this->input->get('do',true);
			$rid  = intval($this->input->get('rid'));

			if($do=='caiji'){ //���

 	                $api  = $this->input->get('api',TRUE);
 	                $page = intval($this->input->get('page'));
 	                $cid  = intval($this->input->get('cid'));
 	                $ac   = $this->input->get('ac',TRUE);
 	                $ops   = $this->input->get('op',TRUE);
 	                $key  = $keys = $this->input->get('key',TRUE);
 	                $ids  = $this->input->get('ids',TRUE);
		            if($page==0) $page=1;
		            if($ops=='24') $ops='day';
		            if($ops=='day'){
			            $op=24;
		            }elseif($ops=='week'){
			            $op=98;
		            }else{
			            $op=0;
		            }
					//�󶨷�������
                    $LIST = require_once(APPPATH.'config/bind.php');

		            $api_url ='?api='.$api.'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&do=caiji&key='.$key.'&cid='.$cid;
                    if($api){
                          $API_URL=cs_base64_decode($api).'?ac=videolist&rid='.$rid.'&wd='.iconv('gbk','utf-8',$key).'&t='.$cid.'&h='.$op.'&ids='.$ids.'&pg='.$page;
                          $strs=htmlall($API_URL,'utf-8');

			              if(empty($strs)) admin_msg('<font color=red>�ɼ�ʧ�ܣ�����Լ��Σ���һֱ���ָô���ͨ��Ϊ���粻�ȶ�������˲ɼ���</font>','javascript:history.go(-1);');

			              //��Ϸ�ҳ��Ϣ
			              preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$strs,$page_array);
						  if(!empty($page_array)){
			                   $recordcount = $page_array[4];
			                   $pagecount = $page_array[2];
			                   $pagesize = $page_array[3];
			                   $pageindex = $page_array[1];	
						  }else{
			                   $recordcount = 0;
			                   $pagecount = 0;
			                   $pagesize = 0;
			                   $pageindex = 0;	
						  }


                          echo '<LINK href="'.base_url().'packs/admin/css/style.css" type="text/css" rel="stylesheet"><br>';
						  echo '<div id="loading" style="display:none;position: absolute;left:40%;top:300px;z-index:10;"><span style="width:100px;height:40px;line-height:40px;background-color:#ccc;">&nbsp;&nbsp;<img align="absmiddle" src="'.Web_Path.'packs/admin/images/loading.gif">���ݼ�����</span></div>';
			              echo "&nbsp;&nbsp;<b><font color=#0000ff>��ǰҳ����".$recordcount."�����ݣ���Ҫ�ɼ�".$pagecount."�Σ�ÿһ�βɼ�".$pagesize."��������ִ�е�".$pageindex."�βɼ�����</font></b><br/>";

			              //����б�
			              $vod='';
			              preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><pic>([\s\S]*?)<\/pic><lang>([\s\S]*?)<\/lang><area>([\s\S]*?)<\/area><year>([\s\S]*?)<\/year><state>([\s\S]*?)<\/state><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor><director><\!\[CDATA\[([\s\S]*?)\]\]><\/director><dl>([\s\S]*?)<\/dl><des><\!\[CDATA\[([\s\S]*?)\]\]><\/des>([\s\S]*?)<\/video>/',$strs,$vod_array);
                          $s=1;
			              foreach($vod_array[1] as $key=>$value){

                                  $p=($pageindex-1)*$pagesize+$s;

				                  $add['name']     = str_replace("'","",htmlspecialchars_decode($vod_array[4][$key]));
				                  $add['pic']      = $vod_array[6][$key];
				                  $add['uid']      = 1;
                                  $add['daoyan']   = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($vod_array[13][$key])));
                                  $add['zhuyan']     = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($vod_array[12][$key])));
                                  $add['year']     = $vod_array[9][$key];
                                  $add['diqu']     = $vod_array[8][$key];
                                  $add['yuyan']    = $vod_array[7][$key];
					              $add['remark']   = (empty($vod_array[10][$key]))?'���':$vod_array[10][$key];
                                  $add['text']  = str_replace("'","",htmlspecialchars_decode($vod_array[15][$key]));
                                  $add['addtime']  = time();

                                  $add['daoyan']   = str_replace("//","/",$add['daoyan']);
                                  $add['zhuyan']   = str_replace("//","/",$add['zhuyan']);

								  //�滻����
								  $add['name']=str_replace("--���Ӿ�","",$add['name']);
								  $add['name']=str_replace("--΢��Ӱ","",$add['name']);
								  $add['name']=str_replace("--����","",$add['name']);
								  $add['name']=str_replace("--����","",$add['name']);
								  $add['name']=str_replace(" ���Ӿ�","",$add['name']);
								  $add['name']=str_replace(" ΢��Ӱ","",$add['name']);
								  $add['name']=str_replace(" ����","",$add['name']);
								  $add['name']=str_replace(" ����","",$add['name']);

                                  preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$vod_array[14][$key],$url_arr);

								  $purl_arr=array();
								  for($j=0;$j<count($url_arr[2]);$j++){
									  $laiy = $url_arr[1][$j];
									  //��Դվ��Դ�滻
									  $laiy=str_replace("xigua","xgvod",$laiy);
					                  $laiy=str_replace("xfplay","yyxf",$laiy);
					                  $laiy=str_replace("�ٶ�Ӱ��","bdhd",$laiy);
									  //��˿����Դ��Դ�滻
									  $laiy=str_replace("������","letv",$laiy);
									  $laiy=str_replace("�ſ�","youku",$laiy);
									  $laiy=str_replace("Ӱ���ȷ�","yyxf",$laiy);
									  $laiy=str_replace("����Ӱ��","jjvod",$laiy);
									  //������Դ��Դ�滻
									  $laiy=str_replace("hdbaofeng1080P","baofeng",$laiy);
									  $laiy=str_replace("hdbaofeng720P","baofeng",$laiy);
									  $laiy=str_replace("hdbaofeng480P","baofeng",$laiy);
									  $laiy=str_replace("hdbaofeng240P","baofeng",$laiy);
									  $laiy=str_replace("yinyuetai","yyt",$laiy);

                                      $purl = $url_arr[2][$j];
					                  $purl=htmlspecialchars_decode($purl);
					                  $purl=str_replace("xigua","xgvod",$purl);
					                  $purl=str_replace("xfplay","yyxf",$purl);
					                  $purl=str_replace("yyxf://","xfplay://",$purl);
					                  $purl=str_replace("�ٶ�Ӱ��","bdhd",$purl);
					                  $purl=str_replace("#","$".$laiy."\n",$purl)."$".$laiy;
					                  $purl=str_replace("$".$laiy."$".$laiy,"$".$laiy,$purl);
									  $purl_arr[]=$purl;
								   }
								   $purl = implode('#cscms#',$purl_arr);
                                   $add['purl'] = $purl;

                                  //�жϰ�
			                      $val=arr_key_value($LIST,$ac.'_'.$vod_array[3][$key]);
			                      if(!$val){

			                                   echo "&nbsp;&nbsp;&nbsp;��".$p."��ӰƬ&nbsp;<font color=red>".$vod_array[4][$key]."</font>&nbsp;&nbsp;����û�а󶨷��࣬��������⴦��<br/>";

                                  //�ж�����������
					              }elseif(empty($vod_array[4][$key]) || empty($purl)){

			                                   echo "&nbsp;&nbsp;&nbsp;��".$p."��ӰƬ&nbsp;<font color=red>".$vod_array[4][$key]."</font>&nbsp;&nbsp;���ݲ���������������⴦��<br/>";

					              }else{

				                        $add['cid']  = $val;

							            //�ж������Ƿ����
					                    $sql="SELECT id,purl,remark FROM ".CS_SqlPrefix."vod where name='".$add['name']."'";
	 		                            $row=$this->db->query($sql)->row();
			                            if(!$row){

                                               $this->CsdjDB->get_insert('vod',$add);
					                           echo "&nbsp;&nbsp;&nbsp;��".$p."��ӰƬ&nbsp;<font color=#ff00ff>".$vod_array[4][$key]."</font>���ݿ���û�м�¼���������ɣ�<br/>";

							            }else{

								             //�жϸ���״̬
								             if($row->purl==$purl){

			                                      echo "&nbsp;&nbsp;&nbsp;��".$p."��ӰƬ&nbsp;<font color=#ff6600>".$vod_array[4][$key]."</font>&nbsp;&nbsp;������ͬ�����޲���Ҫ����<br/>";

								             }else{

					                             $edit['remark']  = $add['remark'];
                                                 $edit['purl'] = $this->isdata($purl,$row->purl);
                                                 echo "&nbsp;&nbsp;&nbsp;��".$p."��ӰƬ&nbsp;<font color=#ff00ff>".$vod_array[4][$key]."</font>&nbsp;&nbsp;���ݴ��ڣ����ݸ��³ɹ�~!<br/>";

									              //��������
                                                  $edit['addtime'] = time();
									              $this->CsdjDB->get_update('vod',$row->id,$edit);
								             }
							            }
					              }
			              $s++;
			              }

			              if($pageindex < $pagecount){
					            //����ϵ�����
					            $jumpurl = site_url('vod/admin/apiku').$api_url.'&page='.($page+1);
					            write_file(APPPATH."config/jumpurl.txt", $jumpurl);
					            //��ת����һҳ
					            echo("</br>&nbsp;&nbsp;&nbsp;<a href='".site_url('vod/admin/apiku')."?api=".$api."&op=".$ops."&ac=".$ac."&key=".$keys."&cid=".$cid."'>����ֹͣ</a>&nbsp;&nbsp;&nbsp;<b>&nbsp;��<font color=red>".$page."</font>ҳ������,��ͣ<font color=red>3</font>���������������</b><script>setTimeout('updatenext();',3000);
								function updatenext(){
									document.getElementById('loading').style.display = 'block';
									location.href='".$api_url."&page=".($page+1)."';
								}
								</script></br></br>");
			              }else{
					            //����ϵ�����
					            write_file(APPPATH."config/jumpurl.txt", "0");
					            echo("</br>&nbsp;&nbsp;&nbsp;&nbsp;<b>��ϲ����ȫ��������������������</b><script>
								setTimeout('updatenext();',3000);
								function updatenext(){
									document.getElementById('loading').style.display = 'block';
									location.href='".site_url('vod/admin/apiku').str_replace("&do=caiji","",$api_url)."';
								}
								</script>");				
			              }
		            }else{
                           
						  admin_msg('<font color=red>API����</font>','javascript:history.go(-1);');
		            }

			}elseif(!empty($ac)){  //��Դ��鿴

 	    		$api  = $this->input->get('api',TRUE);
 	    		$page = intval($this->input->get('page'));
 	   		    $cid  = intval($this->input->get('cid'));
 	    		$ac   = $this->input->get('ac',TRUE);
 	    		$op   = $this->input->get('op',TRUE);
 	    		$key  = $this->input->get('key',TRUE);
				if($page==0) $page=1;
				if($op=='all') $op=0;

				$data['api_url'] ='?api='.$api.'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&key='.$key.'&cid='.$cid;
				$data['key'] = $key;
				$data['op']  = $op;
				$data['cid'] = $cid;
				$data['rid'] = $rid;
				$data['api'] = $api;
        		if($api){
              		 $API_URL=cs_base64_decode($api).'?ac=list&rid='.$rid.'&wd='.iconv('gbk','utf-8',$key).'&t='.$cid.'&h=0&ids=&pg='.$page;
					 $strs=htmlall($API_URL,'utf-8');
					 if(empty($strs)) admin_msg('<font color=red>��ȡ�б�ʧ�ܣ�����Լ��Σ���һֱ���ָô���ͨ��Ϊ���粻�ȶ�������˲ɼ���</font>','javascript:history.go(-1);');

			  		 //��Ϸ�ҳ��Ϣ
			  		 preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$strs,$page_array);
			  		 $data['recordcount'] = $page_array[4];
			 		 $data['pagecount'] = $page_array[2];
			  		 $data['pagesize'] = $page_array[3];
			  		 $data['pageindex'] = $page_array[1];	

			 		 $path=site_url('vod/admin/apiku').$data['api_url'].'&key='.$key.'&cid='.$cid.'&';
			 		 $data['pages'] = get_admin_page($data['api_url'],$data['pagecount'],$page,10);
			  
			  		 //����б�
			  		 $vod='';
			  		 preg_match_all('/<video>([\s\S]*?)<\/video>/',$strs,$vod_array);
			  		 foreach($vod_array[1] as $key=>$value){
                         preg_match_all('/<last>([\s\S]*?)<\/last>/',$value,$times);
                         preg_match_all('/<id>([0-9]+)<\/id>/',$value,$ids);
                         preg_match_all('/<tid>([0-9]+)<\/tid>/',$value,$cids);
                         preg_match_all('/<name><\!\[CDATA\[([\s\S]*?)\]\]><\/name>/',$value,$names);
                         preg_match_all('/<type>([\s\S]*?)<\/type>/',$value,$cnames);
                         preg_match_all('/<dt>([\s\S]*?)<\/dt>/',$value,$dts);
						 $vod[$key]['addtime'] = $times[1][0];
						 $vod[$key]['id'] = $ids[1][0];
						 $vod[$key]['cid'] = intval($cids[1][0]);
						 $vod[$key]['name'] = $names[1][0];
						 $vod[$key]['laiy'] = (!empty($dts[1][0]))?Laiyuan($dts[1][0]):$ac;
						 $vod[$key]['cname'] = $cnames[1][0];
			  		 }
             		 $data['vod']=$vod;

			 		  //��Ϸ���
			 		  preg_match_all('/<ty id="([0-9]+)">([\s\S]*?)<\/ty>/',$strs,$list_array);
			 		  foreach($list_array[1] as $key=>$value){
						   $vod_list[$key]['id'] = $value;
						   $vod_list[$key]['name'] = $list_array[2][$key];
			 		  }
             		  $data['vod_list']=$vod_list;
            		  $data['ac']=$ac;
            		  $data['page']=$page;

                      $data['LIST'] = require_once(APPPATH.'config/bind.php');
            		  $this->load->view('apiku_list.html',$data);

        		}else{
            		  admin_msg('<font color=red>API����</font>','javascript:history.go(-1);');
				}

			}elseif(empty($do)){  //��Դ����ҳ
				 $data['jumpurl']=@file_get_contents(APPPATH."config/jumpurl.txt");
                 $data['api']='http://vod.chshcms.com/api/cscms_zy_4.x.js?'.rand(1000, 9999);
                 $this->load->view('apiku.html',$data);
			}
	}

    //�󶨷���
	public function bind()
	{
	        $csid = intval($this->input->get('csid'));
 	        $ac  = $this->input->get('ac',TRUE);

            $LIST = require_once(APPPATH.'config/bind.php');
			$val=arr_key_value($LIST,$ac.'_'.$csid);
            $strs='<option value="0">&nbsp;|��ѡ��Ŀ�����</option>';
            $query = $this->db->query("SELECT id,name FROM ".CS_SqlPrefix."vod_list where fid=0 order by xid asc"); 
            foreach ($query->result() as $row) {
                        $clas=($row->id==$val)?' selected="elected"':'';
			            $strs.='<option value="'.$row->id.'"'.$clas.'>&nbsp;|��'.$row->name.'</option>';
                        $query2 = $this->db->query("SELECT id,name FROM ".CS_SqlPrefix."vod_list where fid=".$row->id." order by xid asc"); 
                        foreach ($query2->result() as $row2) {
                            $clas2=($row2->id==$val)?' selected="elected"':'';
			                $strs.='<option value="'.$row2->id.'"'.$clas2.'>&nbsp;|&nbsp;&nbsp;&nbsp;|��'.$row2->name.'</option>';
			            }
            }
            echo '<select class="select" name="cid" id="cid">'.$strs.'
                 </select><input class="button" type="button" value="�� ��" onClick="submitbind(\''.$ac.'\',\''.$csid.'\');" style="cursor:pointer"> <input name="button" type="button" value="ȡ ��" class="button" onClick="hidebind();" style="cursor:pointer">
				 ';
    }

    //�󶨷���洢
	public function bind_save()
	{
 	    $ac   = $this->input->get('ac',TRUE);
	    $csid = intval($this->input->get_post('csid'));
	    $id = intval($this->input->get_post('cid'));

	    $LIST = require_once(APPPATH.'config/bind.php');
	    $LIST[$ac.'_'.$csid] = $id;
		arr_file_edit($LIST,APPPATH.'config/bind.php');
        echo 'ok';
	}

	//���ȫ����
	public function jie_bind()
	{
 	        $api  = $this->input->get('api',TRUE);
 	        $ac   = $this->input->get('ac',TRUE);
	        $LIST = require_once(APPPATH.'config/bind.php');
            foreach ($LIST as $k=>$v) {
                 if(strpos($k,$ac.'_') !== FALSE){
                     unset($LIST[$k]);
				 }
			}
			arr_file_edit($LIST,APPPATH.'config/bind.php');
            header("Location: ".site_url('vod/admin/apiku')."?api=".$api."&ac=".$ac.""); 
    }

	//�ϲ���������
	public function isdata($xpurl,$ypurl)
	{
		    $news=$old=array();
			$xarr=explode("#cscms#",$xpurl);
			$xcount=count($xarr);
			for($i=0;$i<$xcount;$i++){
                 $lys=explode("\n",$xarr[$i]);
				 $ly=explode("$",str_replace("\r","",$lys[0]));
                 $news[$ly[2]]=$xarr[$i];
			}
			$yarr=explode("#cscms#",$ypurl);
			$ycount=count($yarr);
			for($i=0;$i<$ycount;$i++){
                 $lys=explode("\n",$yarr[$i]);
				 $ly=explode("$",str_replace("\r","",$lys[0]));
                 $old[$ly[2]]=$yarr[$i];
			}
			$str=implode('#cscms#',array_merge($old,$news));
			return $str;
	}
}

