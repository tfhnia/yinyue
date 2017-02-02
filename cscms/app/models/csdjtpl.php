<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-04-27
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CsdjTpl extends CI_Model
{
  		//���췽����ʼ��
  		public function __construct() {
	           parent:: __construct ();
  		}

        //��վ��ҳ
        public function home($return = FALSE) {
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id ="index_".$wap;
	            if(!($this->cache->start($cache_id))){
				      $Mark_Text=$this->load->view('index.html','',true);
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

        //�����ҳ
        public function plub_index($plub, $tpl='index.html', $return = FALSE, $t='', $k='', $d='') {
			    $plub=(!defined('PLUBPATH'))?$plub:PLUBPATH;
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id =$plub."_index_".$wap;
	            if(!($this->cache->start($cache_id))){
				      $template=$this->load->view($tpl,'',true);

					  //��ȡ���ȫ��SEO
					  $Seo=config('Seo');

        	          //SEO����
        	          if(!empty($t)){
               		        $seo_title=$t;
        	          }elseif(!empty($Seo['title'])){
					        $seo_title=$Seo['title'];
        	          }else{
					        $seo_title=str_decode(Web_Title);
        	          }
        	          //SEO�ؼ���
        	          if(!empty($k)){
               		        $seo_keywords=$k;
        	          }elseif(!empty($Seo['keywords'])){
					        $seo_keywords=$Seo['keywords'];
        	          }else{
					        $seo_keywords=str_decode(Web_Keywords);
        	          }
        	          //SEO����
        	          if(!empty($d)){
               		       $seo_description=$d;
        	          }elseif(!empty($Seo['description'])){
					       $seo_description=$Seo['description'];
        	          }else{
					       $seo_description=str_decode(Web_Description);
        	          }

		              $Mark_Text=$this->skins->topandend($template);
		              $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);
                      $Mark_Text=$this->skins->template_parse($template,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

		//�������б�ѭ����row�����������顢id ��ǰ����ID��page ��ǰ��ҳID��tpl ��ǰ����ģ�塢field��ǩǰ׺��t SEO���⡢k SEO�ؼ��ʡ�d SEO���
        public function plub_list($row, $id, $fid='id', $page=1, $ids=array(), $return = FALSE, $tpl='list.html', $type='lists', $field='', $t='', $k='', $d=''){
			    if(empty($ids)) $ids=$id;
			    if(empty($row)) $row=array();
                $plub=(!defined('PLUBPATH'))?'index':PLUBPATH;
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id =$plub."_".$type."_".$id."_".$fid."_".$tpl."_".$page."_".$wap; //ҳ�滺���ʾ
	            if(!($this->cache->start($cache_id))){ //�����Ƿ����
                      $data=$data_content=$aliasname='';
                      //װ��ģ��
					  if(empty($tpl)) $tpl='list.html';
		              $template=$this->load->view($tpl,$data,true);

					  //��ȡ���ȫ��SEO
					  $Seo=config('Seo');

        	          //SEO����
        	          if(!empty($row['title'])){
					        $seo_title=$row['title'];
        	          }elseif(!empty($t)){
               		        $seo_title=$t;
        	          }elseif(!empty($Seo['title'])){
					        $seo_title=$Seo['title'];
        	          }else{
					        $seo_title=str_decode(Web_Title);
        	          }
        	          //SEO�ؼ���
        	          if(!empty($row['keywords'])){
					        $seo_keywords=$row['keywords'];
        	          }elseif(!empty($k)){
               		        $seo_keywords=$k;
        	          }elseif(!empty($Seo['keywords'])){
					        $seo_keywords=$Seo['keywords'];
        	          }else{
					        $seo_keywords=str_decode(Web_Keywords);
        	          }
        	          //SEO����
        	          if(!empty($row['description'])){
					       $seo_description=$row['description'];
        	          }elseif(!empty($d)){
               		       $seo_description=$d;
        	          }elseif(!empty($Seo['description'])){
					       $seo_description=$Seo['description'];
        	          }else{
					       $seo_description=str_decode(Web_Description);
        	          }

		              $Mark_Text=$this->skins->topandend($template);
		              $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:fid}",$fid,$Mark_Text); //��ǰʹ�õ�fid
		              $Mark_Text=str_replace("{cscms:ids}",$id,$Mark_Text); //��ǰʹ�õ�id

		              //Ԥ�ȳ��˷�ҳ
		              $pagenum=getpagenum($Mark_Text);
				      preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);//�ж��Ƿ��з�ҳ��ʶ
		              if(!empty($page_arr) && !empty($page_arr[2])){

					       $fields=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
					       $sort=$this->get_sort($fid);
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],$sort,$ids);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
						   $plist=preg_match('/{cscms:pagelist}/i', $Mark_Text);
					       $Arr=spanpage($sqlstr,$nums,$page_arr[3][0],$pagenum,$type,$fid,$id,$page,$plist);
			               if($nums>0){
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
									$datatmp='';
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
		              $Mark_Text=$this->skins->cscms_skins($field,$Mark_Text,$Mark_Text,$row);//������ǰ���ݱ�ǩ
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

		//��Ա�б�ѭ����row��Ա�������顢id ��ǰ����ID��page ��ǰ��ҳID��tpl ��ǰ����ģ�塢field��ǩǰ׺��t SEO����
        public function user_list($row, $url='', $page=1, $tpl='space.html', $t='��Ա����', $fid='', $sql='', $ids='', $return = FALSE, $if=true, $field='user'){
			    $dir=defined('PLUBPATH')?PLUBPATH:'';
			    $data=$data_content=$aliasname='';
                //װ��ģ��
		        $template=$this->load->view($tpl,$data,true);
        	    //SEO����
        	    $seo_title=(!empty($t)) ? $t : str_decode(Web_Title);
        	    //SEO�ؼ���
        	    $seo_keywords=str_decode(Web_Keywords);
        	    //SEO����
        	    $seo_description=str_decode(Web_Description);
		        $Mark_Text=$this->skins->topandend($template);
		        $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		        $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		        $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);
		        $Mark_Text=str_replace("{cscms:fid}",$fid,$Mark_Text); //��ǰʹ�õ�fid
		        //Ԥ�ȳ��˷�ҳ
		        $pagenum=getpagenum($Mark_Text);
				preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[2])){

					       $fields=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'id',$ids,0,$sql);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
					       $Arr=userpage($sqlstr,$nums,$page_arr[3][0],$pagenum,$url,$page,$dir);
			               if($nums>0){
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
									$datatmp='';
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
		        $Mark_Text=$this->skins->cscms_skins($field,$Mark_Text,$Mark_Text,$row);//������ǰ���ݱ�ǩ
                $Mark_Text=$this->skins->template_parse($Mark_Text,true,$if);
				if ($return == FALSE){
				      echo $Mark_Text;
				}else{
                      return $Mark_Text;
				}
		}

		//��Ա��ҳ��row��Ա�������顢id ��ǰ����ID��page ��ǰ��ҳID��tpl ��ǰ����ģ�塢field��ǩǰ׺��t SEO����
        public function home_list($row, $op='', $page=1, $tpl='index.html', $t='', $ids='', $fid='', $sql='', $return = FALSE, $if=true, $field='user'){
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id =$tpl."_".$row['id']."_".$op."_".$fid."_".$page."_".$wap; //ҳ�滺���ʾ
	            if(!($this->cache->start($cache_id))){ //�����Ƿ����
			          $dir=defined('PLUBPATH')?PLUBPATH:'';
			          $data=$data_content=$aliasname='';
					  if(empty($row['skins'])) $row['skins']=Home_Skins;
                      //װ��ģ��
			          $sid=defined('PLUBPATH')?1:0;
				      $this->load->get_templates('home',$sid,$row['skins']);
		              $template=$this->load->view($tpl,$data,true);
        	          //SEO����
        	          $seo_title=(!empty($t)) ? $t : $row['nichen'].'�ĸ�����ҳ';
        	          //SEO�ؼ���
        	          $seo_keywords=str_decode(Web_Keywords);
        	          //SEO����
        	          $seo_description=str_decode(Web_Description);
		              $Mark_Text=$this->skins->topandend($template,$row['skins']);
		              $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:fid}",$fid,$Mark_Text); //��ǰʹ�õ�fid
		              //Ԥ�ȳ��˷�ҳ
		              $pagenum=getpagenum($Mark_Text);
				      preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		              if(!empty($page_arr) && !empty($page_arr[2])){

					       $fields=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'id',$ids,0,$sql);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
					       $Arr=homepage($sqlstr,$nums,$page_arr[3][0],$pagenum,$op,$row['id'],$row['name'],$fid,$page);
			               if($nums>0){
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
									$datatmp='';
						             $datatmp=$this->skins->cscms_skins($fields,$page_arr[0][0],$page_arr[4][0],$row2,$sorti);
						             $sorti++;
						             $data_content.=$datatmp;
					            }
			               }
			               $Mark_Text=page_mark($Mark_Text,$Arr);	//��ҳ����
			               $Mark_Text=str_replace($page_arr[0][0],$data_content,$Mark_Text);
				      }
		              unset($page_arr);
		              $Mark_Text=$this->skins->cscms_common($Mark_Text,$row['skins']);
				      $Mark_Text=$this->skins->csskins($Mark_Text,$ids);
		              $Mark_Text=$this->skins->cscms_skins('user',$Mark_Text,$Mark_Text,$row);//������ǰ���ݱ�ǩ
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true,$if);
				      if ($return == FALSE){
				            echo $Mark_Text;
					        $this->cache->end(); //д�뻺��
				      }else{
                            return $Mark_Text;
				      }
				}
		}

		//��������б�ѭ����dir���������ݿ����ơ�so �������顢page ��ǰ��ҳID��tpl ģ�塢t SEO���⡢k �ؼ��ʡ�d ���
        public function plub_search($dir, $so, $page=1, $return = FALSE, $tpl='search.html', $t='', $k='', $d=''){
			    $so=get_bm($so);
                $data=$data_content=$aliasname=$zm='';
                //װ��ģ��
		        $template=$this->load->view($tpl,$data,true);

        	    //SEO����
        	    if(!empty($t)){
               		  $seo_title=$t;
        	    }else{
					  $seo_title="���� - ".Web_Name;
        	    }
        	    //SEO�ؼ���
        	    if(!empty($k)){
               		  $seo_keywords=$k;
				}else{
					  $seo_keywords=str_decode(Web_Keywords);
				}
        	    //SEO����
        	    if(!empty($d)){
               		  $seo_description=$d;
				}else{
					  $seo_description=str_decode(Web_Description);
				}

		        $Mark_Text=$this->skins->topandend($template);
		        $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		        $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		        $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);

		        //Ԥ�ȳ��˷�ҳ
		        $pagenum=getpagenum($Mark_Text);
				preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[2])){

					       $field=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
                           $sqlstr="select {field} from `".CS_SqlPrefix.$dir."` where 1=1";
						   $sql=$fid=$key="";
						   foreach ($so as $k=>$v) {
                               if ($this->db->field_exists($k, $dir) && !empty($v)){ //�ж������ֶ��Ƿ����
								  if(!preg_match("/^\d*$/",$v)){
                                      $sql.="and ".$k." like '%".$v."%' ";
								  }else{
                                      $sql.="and ".$k."=".$v." ";
								  }
								  $Mark_Text=str_replace("{cscms:".$k."}",$v,$Mark_Text);
								  $Mark_Text=str_replace("{cscms:key}",$v,$Mark_Text);
								  $fid.="&".$k."=".urlencode($v);
						       }
							   if($k=='key' && $this->db->field_exists('name', $dir) && !empty($v)){
                                  $sql.="and name like '%".$v."%' ";
								  $Mark_Text=str_replace("{cscms:key}",$v,$Mark_Text);
								  $fid.="&key=".urlencode($v);
					           }
							   if($k=='zm'){
                                  $zd=(empty($v['zd']))?'name':$v['zd'];
                                  $zm=(empty($v['zm']))?'':$v['zm'];

			                      $zimu_arr=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			                      $zimu_arr1=array(-20319,-20283,-19775,-19218,-18710,-18526,-18239,-17922,-1,-17417,-16474,-16212,-15640,-15165,-14922,-14914,-14630,-14149,-14090,-13318,-1,-1,-12838,-12556,-11847,-11055);
			                      $zimu_arr2=array(-20284,-19776,-19219,-18711  ,-18527,-18240,-17923,-17418,-1,-16475,-16213,-15641,-15166,-14923,-14915,-14631,-14150,-14091,-13319,-12839,-1,-1,-12557,-11848,-11056,-2050);

						          if(!empty($zm) && !in_array(strtoupper($zm),$zimu_arr) && $this->db->field_exists($zd, $dir)){
						               $sql.="and substring( ".$zd.", 1, 1 ) NOT REGEXP '^[a-zA-Z]' and substring( ".$zd.", 1, 1 ) REGEXP '^[u4e00-u9fa5]'";
                                  }elseif(in_array(strtoupper($zm),$zimu_arr) && $this->db->field_exists($zd, $dir)){
			                           $posarr=array_keys($zimu_arr,strtoupper($zm));
			                           $pos=$posarr[0];
			                           $sql.="and (((ord( substring(convert(".$zd." USING gbk), 1, 1 ) ) -65536>=".($zimu_arr1[$pos])." and  ord( substring(convert(".$zd." USING gbk), 1, 1 ) ) -65536<=".($zimu_arr2[$pos]).")) or UPPER(substring(convert(".$zd." USING gbk), 1, 1 ))='".$zimu_arr[$pos]."')";
						          }
								  $Mark_Text=str_replace("{cscms:zm}",$zm,$Mark_Text);
								  if(!empty($zm)) $fid.="&zm=".$zm;
							   }
						   }
						   if(!empty($sql)){
							      $fid=substr($fid,1);
                                  $sql=substr($sql,3);
                                  $sqlstr.=" and (".$sql.")";
						   }
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'',0,0,$sqlstr);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
						   $plist=preg_match('/{cscms:pagelist}/i', $Mark_Text);
					       $Arr=spanpage($sqlstr,$nums,$page_arr[3][0],$pagenum,'search',$fid,urlencode($key),$page,$plist);
			               if($nums==0){
				                $data_content.="";
			               }else{
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
						             $datatmp=$this->skins->cscms_skins($field,$page_arr[0][0],$page_arr[4][0],$row2,$sorti);
						             $sorti++;
						             $data_content.=$datatmp;
					            }
			               }
			               $Mark_Text=page_mark($Mark_Text,$Arr);	//��ҳ����
			               $Mark_Text=str_replace($page_arr[0][0],$data_content,$Mark_Text);
				}
		        unset($page_arr);
				$Mark_Text=str_replace("{cscms:cid}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:zhuyan}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:daoyan}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:yuyan}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:diqu}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:year}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:tags}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:name}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:type}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:key}",'',$Mark_Text);
				$Mark_Text=str_replace("{cscms:zm}",'',$Mark_Text);
		        $Mark_Text=$this->skins->cscms_common($Mark_Text);
				$Mark_Text=$this->skins->csskins($Mark_Text,$so);
                $Mark_Text=$this->skins->template_parse($Mark_Text,true);
				if ($return == FALSE){
				       echo $Mark_Text;
				}else{
                       return $Mark_Text; //��ֱ�����
				}
		}

        //�������
        public function plub_show($field, $row, $ids, $return = FALSE, $tpl='show.html', $t='', $k='', $d='', $cacheid='') {
                $plub=(!defined('PLUBPATH'))?'index':PLUBPATH;
			    if(empty($row)) $row=array('id'=>0);
				$id=$row['id'];
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id =($cacheid=='')?$plub."_show_".$tpl."_".$id:$cacheid;
				$cache_id.="_".$wap;
	            if(!($this->cache->start($cache_id))){
				      $template=$this->load->view($tpl,'',true);
					  //��ȡ���ȫ��SEO
					  $Seo=config('Seo');

        	          //SEO����
        	          if(!empty($row['title'])){
					        $seo_title=$row['title'];
        	          }elseif(!empty($t)){
               		        $seo_title=$t;
        	          }elseif(!empty($Seo['title'])){
					        $seo_title=$Seo['title'];
        	          }else{
					        $seo_title=str_decode(Web_Title);
        	          }
        	          //SEO�ؼ���
        	          if(!empty($row['keywords'])){
					        $seo_keywords=$row['keywords'];
        	          }elseif(!empty($k)){
               		        $seo_keywords=$k;
        	          }elseif(!empty($Seo['keywords'])){
					        $seo_keywords=$Seo['keywords'];
        	          }else{
					        $seo_keywords=str_decode(Web_Keywords);
        	          }
        	          //SEO����
        	          if(!empty($row['description'])){
					       $seo_description=$row['description'];
        	          }elseif(!empty($d)){
               		       $seo_description=$d;
        	          }elseif(!empty($Seo['description'])){
					       $seo_description=$Seo['description'];
        	          }else{
					       $seo_description=str_decode(Web_Description);
        	          }

		              $Mark_Text=$this->skins->topandend($template);
		              $Mark_Text=str_replace("{cscms:title}",$seo_title,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:keywords}",$seo_keywords,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:description}",$seo_description,$Mark_Text);
		              $Mark_Text=str_replace("{cscms:id}",$id,$Mark_Text); //��ǰʹ�õ�id
		              $Mark_Text=$this->skins->cscms_common($Mark_Text);
				      $Mark_Text=$this->skins->csskins($Mark_Text,$ids);
		              $Mark_Text=$this->skins->cscms_skins($field,$Mark_Text,$Mark_Text,$row);//������ǰ���ݱ�ǩ
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

		//��վ����
        public function gbook(){
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id ="cscms_gbook_".$wap;
	            if(!($this->cache->start($cache_id))){
		              $template=$this->load->view('gbook.html','',true);
				      $gbook="<div id='cscms_gbook'><img src='".Web_Path."packs/images/load.gif'>&nbsp;&nbsp;������������,���Ե�......</div>\r\n<script type='text/javascript'>cscms_gbook(1,0,0);</script>";
		              $Mark_Text=str_replace("{cscms:gbook}",$gbook,$template);
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true);
				      echo $Mark_Text;
					  $this->cache->end(); //д�뻺��
				}
		}

		//�����б�
        public function gbook_list($page=1){
			    if(User_BookFun==0){ //��վ�ر�����
				     return "<div id='cscms_gbook'>��վ�Ѿ��ر�����������~!</div>";
				}
			    $data_content='';
                //װ��ģ��
		        $Mark_Text=$this->load->view('gbook_ajax.html','',true);
		        //Ԥ�ȳ��˷�ҳ
		        $pagenum=getpagenum($Mark_Text);
				preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[2])){

					       $field=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'id',0);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
					       $Arr=spanajaxpage($sqlstr,$nums,$page_arr[3][0],$pagenum,'cscms_gbook',$page);
			               if($nums==0){
				                $data_content.="";
			               }else{
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
						             $datatmp=$this->skins->cscms_skins($field,$page_arr[0][0],$page_arr[4][0],$row2,$sorti);
						             $sorti++;
						             $data_content.=$datatmp;
					            }
			               }
			               $Mark_Text=page_mark($Mark_Text,$Arr);	//��ҳ����
			               $Mark_Text=str_replace($page_arr[0][0],$data_content,$Mark_Text);
				}
		        unset($page_arr);
				$Mark_Text=str_replace("[gbook:token]",get_token('gbook_token'),$Mark_Text);
                $Mark_Text=$this->skins->template_parse($Mark_Text,false);
				return $Mark_Text;
		}

		//�����б�
        public function pl($dir,$did,$cid=0,$page=1){
		        if(Pl_Modes==1){ //����
				     return '<div id="uyan_frame"></div><script type="text/javascript" src="http://v2.uyan.cc/code/uyan.js?uid='.Pl_Yy_Name.'"></script>';
			    }
		        if(Pl_Modes==2){ //��˵
				     return "<div class='ds-thread' data-thread-key='".$dir."-".$did."'></div><script type='text/javascript'>var duoshuoQuery={short_name:\"".Pl_Ds_Name."\"};(function(){var ds=document.createElement('script');ds.type='text/javascript';ds.async=true;ds.src=(document.location.protocol=='https:'?'https:':'http:')+'//static.duoshuo.com/embed.js';ds.charset='UTF-8';(document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(ds)})();</script>";
			    }
		        if(Pl_Modes==3){ //����
				     return "<div id='SOHUCS' sid='".$dir."-".$did."'></div><script>(function(){var appid='".Pl_Cy_Id."',conf='prod_28f42ecbb9691ec71b9dcc68742151c2';var doc=document,s=doc.createElement('script'),h=doc.getElementsByTagName('head')[0]||doc.head||doc.documentElement;s.type='text/javascript';s.charset='utf-8';s.src='http://assets.changyan.sohu.com/upload/changyan.js?conf='+conf+'&appid='+appid;h.insertBefore(s,h.firstChild)})()</script>";
			    }
			    if(Pl_Modes==4){ //��վ�ر�����
				     return "<div id='cscms_pl' style='text-align:center;'><b>��վ�Ѿ��ر�������~!</b></div>";
				}
			    if($did==0){ //��������
				     return "<div id='cscms_pl' style='text-align:center;'><b>������������IDΪ��~!</b></div>";
				}
			    $data_content='';
                //װ��ģ��
			    if($dir=='blog'){
                    $skins=getzd('user','skins',getzd('blog','uid',$did));
				    $this->load->get_templates('home',0,$skins);
			    }else{
				    $this->load->get_templates($dir,1);
				}
		        $Mark_Text=$this->load->view('pl.html','',true);
		        //Ԥ�ȳ��˷�ҳ
		        $pagenum=getpagenum($Mark_Text);
				preg_match_all('/{cscms:([\S]+)\s+(.*?pagesize=\"([\S]+)\".*?)}([\s\S]+?){\/cscms:\1}/',$Mark_Text,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[2])){

					       $field=$page_arr[1][0]; //ǰ׺��
				           //��װSQL����
						   $arr['did']=$did;
						   $sql="SELECT {field} FROM ".CS_SqlPrefix."pl where dir='".$dir."'";
					       $sqlstr=$this->skins->cscms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[3][0],'id',$arr,$cid,$sql);
						   preg_match('/select\s*(.+)from/i', strtolower($sqlstr),$sqlarr);
						   if(!empty($sqlarr[1])){
                               $sqlstr2=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sqlstr));
						       $cscmstj=$this->db->query($sqlstr2)->result_array();
					           $nums=$cscmstj[0]['counta']; //������
						   }else{
					           $nums=$this->db->query($sqlstr)->num_rows(); //������
						   }
					       $Arr=spanajaxpage($sqlstr,$nums,$page_arr[3][0],$pagenum,'cscms_pl',$page);
			               if($nums==0){
				                $data_content.="";
			               }else{
					            $sorti=1;
							    $result_array=$this->db->query($Arr[0])->result_array();
					            foreach ($result_array as $row2) {
						             $datatmp=$this->skins->cscms_skins($field,$page_arr[0][0],$page_arr[4][0],$row2,$sorti);
						             $sorti++;
						             $data_content.=$datatmp;
					            }
			               }
			               $Mark_Text=page_mark($Mark_Text,$Arr);	//��ҳ����
			               $Mark_Text=str_replace($page_arr[0][0],$data_content,$Mark_Text);
				}
		        unset($page_arr);
				$Mark_Text=str_replace("[pl:token]",get_token('pl_token'),$Mark_Text);
				//����
                $plfaces="";
    	        for($i=1;$i<=56;$i++){
                    $plfaces.="<img style='cursor:pointer;' src=\"".Web_Path."packs/images/faces/e".$i.".gif\" onclick=\"$('#cscms_pl_content').val($('#cscms_pl_content').val()+'[em:".$i."]');$('#cscms_faces').hide();\" />";
                }
				$Mark_Text=str_replace("[pl:faces]",$plfaces,$Mark_Text);
				//�жϵ�¼
				$login='ok';
				if(Pl_Youke==0){
				    if(!$this->CsdjUser->User_Login(1)){
				        $login='no';
				    }
				}
				$Mark_Text=str_replace("[pl:login]",$login,$Mark_Text);
                $Mark_Text=$this->skins->template_parse($Mark_Text,false);
				return $Mark_Text;
		}

        //�Զ���OPT
        public function opt($op,$return=FALSE) {
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id ="opt_".$op."_".$wap;
	            if(!($this->cache->start($cache_id))){
                      $mb="opt-".$op.".html";
		              $template=$this->load->view($mb,'',true);
                      $Mark_Text=$this->skins->template_parse($template,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

        //�Զ���ҳ��
        public function page($row,$return=FALSE) {
				$wap=(!defined('MOBILE'))?1:2;
	            $cache_id ="page_".$row['name']."_".$wap;
	            if(!($this->cache->start($cache_id))){
                      $Mark_Text=str_decode($row['html']);
                      $Mark_Text=$this->skins->template_parse($Mark_Text,true);
					  if ($return == FALSE){
				          echo $Mark_Text;
					      $this->cache->end(); //д�뻺��
					  }else{
                          return $Mark_Text; //���ɾ�̬��ֱ�����
					  }
				}
		}

        //�ֻ�APP����ҳ
        public function mobile() {
	            $template=$this->load->view('mobile.html','',true);
                $Mark_Text=$this->skins->template_parse($template,true);
				echo $Mark_Text; //���ɾ�̬��ֱ�����
		}

        //����3.5�汾URL��ַ
        function get_sort($fid){
			 $sort='addtime';
			 $dos = array('id', 'hits', 'yhits', 'zhits', 'rhits', 'dhits', 'chits', 'shits', 'xhits', 'news', 'reco', 'play', 'down', 'fav', 'yue', 'zhou', 'ri', 'ding', 'cai');
			 if(!empty($fid) && (in_array($fid, $dos))){
				    $old=array('yue', 'zhou', 'ri', 'ding', 'cai', 'fav', 'down', 'news', 'play');
				    $new=array('yhits', 'zhits', 'rhits', 'dhits', 'chits', 'shits', 'xhits', 'addtime', 'playtime');
                    $sort=str_replace($old,$new,$fid);
			 }
			 return $sort;
		}
}
