<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Locoy extends CI_Controller {

	function __construct(){
		parent::__construct();

        //�����ӿ�����,Ĭ��Ϊ1234���������޸�
        $this->pass='1234';
	}

    //�����б�
	public function lists()
	{

	    $sid=intval($this->input->get_post('sid',TRUE)); //�������,1Ϊ������2Ϊ���£�3Ϊ��Ƶ��4Ϊ����
        if($sid==0) $sid=1;

        echo "<select name='list'>";
        if($sid==1){
              $sqlstr="select id,name from ".CS_SqlPrefix."dance_list order by xid asc";
        }elseif($sid==2){
              $sqlstr="select id,name from ".CS_SqlPrefix."news_list order by xid asc";
        }elseif($sid==3){
              $sqlstr="select id,name from ".CS_SqlPrefix."vod_list order by xid asc";
        }else{
              $sqlstr="select id,name from ".CS_SqlPrefix."singer_list order by xid asc";
        }
		$result=$this->CsdjDB->db->query($sqlstr);
		$recount=$result->num_rows();
        if($recount>0){
			foreach ($result->result() as $row) {
                 echo "<option value='".$row->id."'>".$row->name."</option>\n";
            }
        }
        echo '</select>';
	}

    //���
	public function ruku()
	{
        //�ж�����
		$pass=$this->input->get_post('pass',TRUE);
        if($this->pass=='1234' || $pass!=$this->pass){
             die('�������');
        }

        //--------------------���´����רҵ��Ա��Ҫ�޸�----------------------

		$sid=intval($this->input->get_post('sid',TRUE)); //�������,1Ϊ������2Ϊ���£�3Ϊ��Ƶ��4Ϊ����
        if($sid==0) $sid=1;

                //-------------------����------------------------//
                if($sid==1){

		            //�����ֶ�
		            $music['name']=$this->input->get_post('name', TRUE, TRUE);
		            $music['cid']=intval($this->input->get_post('cid'));
		            $music['purl']=$this->input->get_post('purl', TRUE, TRUE);
		            //ѡ���ֶ�
		            $music['tid']=intval($this->input->get_post('tid'));
		            $music['cion']=intval($this->input->get_post('cion'));
		            $music['text']=remove_xss($this->input->get_post('text'));
		            $music['lrc']=$this->input->get_post('lrc', TRUE, TRUE);
		            $music['pic']=$this->input->get_post('pic', TRUE, TRUE);
		            $music['tags']=$this->input->get_post('tags', TRUE, TRUE);
		            $music['zc']=$this->input->get_post('zc', TRUE, TRUE);
		            $music['zq']=$this->input->get_post('zq', TRUE, TRUE);
		            $music['bq']=$this->input->get_post('bq', TRUE, TRUE);
		            $music['hy']=$this->input->get_post('hy', TRUE, TRUE);
		            $music['durl']=$this->input->get_post('hy', TRUE, TRUE);
		            $music['uid']=intval($this->input->get_post('uid', TRUE));
		            $music['dx']=$this->input->get_post('dx', TRUE, TRUE);
		            $music['yz']=$this->input->get_post('yz', TRUE, TRUE);
		            $music['sc']=$this->input->get_post('sc', TRUE, TRUE);
			        $music['title']=$this->input->get_post('title',true,true);
			        $music['keywords']=$this->input->get_post('keywords',true,true);
			        $music['description']=$this->input->get_post('description',true,true);
		            $music['addtime']=time();

		            $singer=$this->input->get_post('singer', TRUE, TRUE);
		            //�жϸ����Ƿ����
		            if(!empty($singer)){
		                 $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
		            	 if($row){
		                       $music['singerid']=$row->id;
		            	 }
		            }

		            if($music['cid']==0 || empty($music['name']) || empty($music['purl'])){
		            		  echo "���ݲ�����";						
					}else{
                              $row=$this->db->query("select id from ".CS_SqlPrefix."dance where name='".$music['name']."'")->row();
							  if($row){
                                  echo "�����Ѵ���,����";
							  }else{
		            	          $did=$this->CsdjDB->get_insert('dance',$music);
		            	          if($did>0){
		            	            	echo "������Ϣ�ɹ�";
								  }else{
		            	            	echo "������Ϣʧ��";
								  }
							  }
					}

		        //-------------------����------------------------//

				}elseif($sid==2){

					$news['cion']=intval($this->input->get_post('cion'));
					$news['pic']=$this->input->get_post('pic', TRUE, TRUE);
					$news['tags']=$this->input->get_post('tags', TRUE, TRUE);
					$news['info']=$this->input->get_post('info', TRUE, TRUE);
					$news['uid']=intval($this->input->get_post('uid'));
			        $news['title']=$this->input->get_post('title',true,true);
			        $news['keywords']=$this->input->get_post('keywords',true,true);
			        $news['description']=$this->input->get_post('description',true,true);
					$news['addtime']=time();
            		//�����ֶ�
					$news['name']=$this->input->get_post('name', TRUE, TRUE);
					$news['cid']=intval($this->input->get_post('cid'));
					$news['content']=remove_xss($this->input->get_post('content'));
           		    //��ȡ����
					$news['info'] = sub_str(str_checkhtml($news['content']),120);

            		//�������ֶ�
					if($news['cid']==0 || empty($news['name']) || empty($news['content'])){
		            		  echo "���ݲ�����";						
					}else{
                              $row=$this->db->query("select id from ".CS_SqlPrefix."news where name='".$news['name']."'")->row();
							  if($row){
                                  echo "�����Ѵ���,����";
							  }else{
		            	          $did=$this->CsdjDB->get_insert('news',$news);
		            	          if($did>0){
		            	            	echo "������Ϣ�ɹ�";
								  }else{
		            	            	echo "������Ϣʧ��";
								  }
							  }
					}

		        //-------------------��Ƶ------------------------//
				}elseif($sid==3){

					$vod['cion']=intval($this->input->get_post('cion'));
					$vod['dcion']=intval($this->input->get_post('dcion'));
					$vod['text']=remove_xss($this->input->get_post('text'));
					$vod['pic']=$this->input->get_post('pic', TRUE, TRUE);
					$vod['tags']=$this->input->get_post('tags', TRUE, TRUE);
					$vod['daoyan']=$this->input->get_post('daoyan', TRUE, TRUE);
					$vod['zhuyan']=$this->input->get_post('zhuyan', TRUE, TRUE);
					$vod['yuyan']=$this->input->get_post('yuyan', TRUE, TRUE);
					$vod['diqu']=$this->input->get_post('diqu', TRUE, TRUE);
					$vod['year']=$this->input->get_post('year', TRUE, TRUE);
					$vod['info']=$this->input->get_post('info', TRUE, TRUE);
					$vod['uid']=intval($this->input->get_post('uid'));
			        $vod['title']=$this->input->get_post('title',true,true);
			        $vod['keywords']=$this->input->get_post('keywords',true,true);
			        $vod['description']=$this->input->get_post('description',true,true);
					$vod['addtime']=time();
                    //�����ֶ�
					$vod['name']=$this->input->get_post('name', TRUE, TRUE);//��Ƶ����
					$vod['cid']=intval($this->input->get_post('cid'));//��Ƶ����ID
			        $play=$this->input->get_post('play', TRUE);//������Դ
			        $purl=$this->input->get_post('purl', TRUE);//���ŵ�ַ
			        $down=$this->input->get_post('down', TRUE, TRUE);//������Դ
			        $durl=$this->input->get_post('durl', TRUE, TRUE);//���ص�ַ
			        //���ŵ�ַ���
					if(!empty($purl) && !empty($play)){
						$purl = explode("\n",str_replace("\r","",$purl));
						$playurl='';
						for($j=0;$j<count($purl);$j++){
                            $playurl.=(strpos($purl[$j],'$') !== FALSE) ? '��'.($j+1).'��$'.$purl.'$'.$play : $purl[$j];
                            $playurl.="\n";
						}
						$playurl.="\n=cscms=";
			            $vod['purl']=str_replace("\n=cscms=","",$playurl);
					}
					if(!empty($durl) && !empty($down)){
						$durl = explode("\n",str_replace("\r","",$durl));
						$downurl='';
						for($j=0;$j<count($durl);$j++){
                            $downurl.=(strpos($durl[$j],'$') !== FALSE) ? '��'.($j+1).'��$'.$durl.'$'.$down : $durl[$j];
                            $downurl.="\n";
						}
						$downurl.="\n=cscms=";
			            $vod['durl']=str_replace("\n=cscms=","",$downurl);
					}
			        $singer=$this->input->get_post('singer', TRUE, TRUE);
			        //�жϸ����Ƿ����
			        if(!empty($singer)){
			             $row=$this->CsdjDB->get_row('singer','id',$singer,'name');
				         if($row){
                               $vod['singerid']=$row->id;
				         }
			        }
                    //�������ֶ�
			        if($vod['cid']==0 || empty($vod['name'])){
		            		  echo "���ݲ�����";						
					}else{
                              $row=$this->db->query("select id,purl,durl from ".CS_SqlPrefix."vod where name='".$vod['name']."'")->row();
							  if($row){
								  $s=0;
								  if(empty($row->purl)){
                                      $vod2['purl']=$vod['purl'];
                                      echo "���ݴ��ڣ����ݸ��³ɹ�";
									  $s++;
								  }else{
								      if(strpos($row->purl,'$'.$play) === FALSE){
                                           $vod2['purl']=$row->purl.'#cscms#'.$vod['purl'];
                                           echo "���ݴ��ڣ�����һ�鲥��";
									       $s++;
								      }
								  }
								  if(empty($row->durl)){
                                      $vod2['durl']=$vod['durl'];
                                      echo "���ݴ��ڣ����ݸ��³ɹ�";
									  $s++;
								  }else{
								      if(strpos($row->durl,'$'.$down) === FALSE){
                                           $vod2['durl']=$row->durl.'#cscms#'.$vod['durl'];
                                           echo "���ݴ��ڣ�����һ������";
									       $s++;
								      }
								  }
                                  if($s>0){
								      $this->CsdjDB->get_update('vod',$row->id,$vod2);
								  }else{
                                      echo "�����Ѵ���,����";
								  }
							  }else{
		            	          $did=$this->CsdjDB->get_insert('vod',$vod);
		            	          if($did>0){
		            	            	echo "������Ϣ�ɹ�";
								  }else{
		            	            	echo "������Ϣʧ��";
								  }
							  }
			        }

		        //-------------------����------------------------//
				}elseif($sid==4){

			        $singer['name']=$this->input->get_post('name',true,true);
			        $singer['tags']=$this->input->get_post('tags',true,true);
			        $singer['pic']=$this->input->get_post('pic',true,true);
			        $singer['color']=$this->input->get_post('color',true,true);
			        $singer['bname']=$this->input->get_post('bname',true,true);
			        $singer['cid']=intval($this->input->get_post('cid'));
			        $singer['nichen']=$this->input->get_post('nichen',true,true);
			        $singer['sex']=$this->input->get_post('sex',true,true);
			        $singer['nat']=$this->input->get_post('nat',true,true);
			        $singer['yuyan']=$this->input->get_post('yuyan',true,true);
			        $singer['city']=$this->input->get_post('city',true,true);
			        $singer['sr']=$this->input->get_post('sr',true,true);
			        $singer['xingzuo']=$this->input->get_post('xingzuo',true,true);
			        $singer['height']=$this->input->get_post('height',true,true);
			        $singer['weight']=$this->input->get_post('weight',true,true);
			        $singer['content']=remove_xss($this->input->get_post('content'));
			        $singer['title']=$this->input->get_post('title',true,true);
			        $singer['keywords']=$this->input->get_post('keywords',true,true);
			        $singer['description']=$this->input->get_post('description',true,true);
					$singer['addtime']=time();

		            //��ʼ��������
		            if(empty($singer['name']) || $singer['cid']==0){
		            		  echo "���ݲ�����";
		            }else{
		            		  //�ж������Ƿ���ͬ
		            	      $row=$this->db->query("select id from ".CS_SqlPrefix."singer where name='".$singer['name']."'")->row();
							  if($row){
								  $this->CsdjDB->get_update('singer',$row->id,$singer);
                                  echo "���ݴ��ڣ������޸ĳɹ�";
							  }else{
		            	          $did=$this->CsdjDB->get_insert('singer',$singer);
		            	          if($did>0){
		            	            	echo "������Ϣ�ɹ�";
								  }else{
		            	            	echo "������Ϣʧ��";
								  }
							  }
					}
				}
	}
}

