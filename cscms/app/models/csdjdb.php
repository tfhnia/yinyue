<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2014 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-05-27
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CsdjDB extends CI_Model{

    function __construct (){
	       parent:: __construct ();
		   //�������ݿ�����
           $this->load->database();
		   //���ص�ǰģ��Ŀ¼
		   if (!defined('PLUBPATH')) {
			      if(defined('IS_ADMIN')) { //��̨��ͼ
		                $this->load->get_templates('admin');
			      }elseif(defined('HOMEPATH')){ //��Ա�ռ���ͼ
		                $this->load->get_templates('home');
			      }elseif(defined('USERPATH')){ //��Ա������ͼ
		                $this->load->get_templates('user');
			      }else{  //Ĭ����ͼ
		                $this->load->get_templates();
			      }
		   }else{  //�����ͼ

		           $this->load->get_templates(PLUBPATH);
		   }
	}

    //��������ѯ
    function get_all($sql){
	       $query=$this->db->query($sql);
	       return $query->result();
	}

    //��������ѯ������
    function get_allnums($sql='')  {
           if(!empty($sql)){
			   preg_match('/select\s*(.+)from/i', strtolower($sql),$sqlarr);
			   if(!empty($sqlarr[1])){
                   $sql=str_replace($sqlarr[1],' count(*) as counta ',strtolower($sql));
				   $rows=$this->db->query($sql)->result_array();
				   $nums=(int)$rows[0]['counta'];
			   }else{
				   $query=$this->db->query($sql);
				   $nums=(int)$query->num_rows();
			   }
           }else{
               $nums=0;
           }
	       return $nums;
	}

    //��ѯ������
    function get_nums($table,$czd='',$arr=''){
		   $sql="SELECT count(*) as count FROM ".CS_SqlPrefix.$table;
           if(!empty($czd) && !empty($arr)){
	           $this->db->where($czd,$arr);
			   if(is_array($arr)){
				   $arr2=array();
			       for ($i=0;$i<count($arr);$i++) {
				       $arr2[]=$czd[$i]."=".$arr[$i];
				   }
				   if(!empty($arr2)){
                       $arr2=implode(' and ', $arr2);
                       $sql.=" where ".$arr2;
				   }
			   }else{
			       $sql.=" where ".$czd."=".$arr;
			   }
           }
		   $rows=$this->db->query($sql)->result_array();
		   $nums=(int)$rows[0]['counta'];
	       return $nums;
	}

    //��������ѯ��һ����
    function get_row($table,$fzd='*',$arr='',$czd='id'){
          if($czd && $arr){
	             $this->db->where($czd,$arr);
          }
	      $this->db->select($fzd);
	      $query=$this->db->get($table);
	      return $query->row();
	}

    //��������ѯ��һ����
    function get_row_arr($table,$fzd='*',$arr='',$czd='id'){
          if($czd && $arr){
	             $this->db->where($czd,$arr);
          }
	      $this->db->select($fzd);
	      $query=$this->db->get($table);
	      return $query->row_array();
	}

    //��������ѯ
    function get_select($table,$czd='',$fzd='*',$arr=''){
          if($czd && $arr){
	             $this->db->where($czd,$arr);
          }
	      $this->db->select($fzd);
	      $query=$this->db->get($table);
	      return $query->result();
	}

    //����
    function get_insert($table,$arr){
          if($arr){
	         $this->db->insert($table,$arr);
             $ids = $this->db->insert_id();
			 if($ids){
                   //�ж϶�̬��������
                   if($table=='dt' && User_Dtts>0 && !empty($_SESSION['cscms__id'])){
                           $rows=$this->db->query("select count(*) as count from ".CS_SqlPrefix."dt where uid=".$_SESSION['cscms__id']."")->result_array();
                           $nums=(int)$rows[0]['count'];
                           if($nums>User_Dtts){
							   $limit=$nums-User_Dtts;
                               $this->db->query("DELETE FROM ".CS_SqlPrefix."dt where uid=".$_SESSION['cscms__id']." order by addtime asc LIMIT ".$limit);
                           }
                   }
                   //�ж�˵˵��������
                   if($table=='blog' && User_Ssts>0 && !empty($_SESSION['cscms__id'])){
                           $rows=$this->db->query("select count(*) as count from ".CS_SqlPrefix."blog where uid=".$_SESSION['cscms__id']."")->result_array();
                           $nums=(int)$rows[0]['count'];
                          if($nums>User_Ssts){
							   $limit=$nums-User_Ssts;
                               $this->db->query("DELETE FROM ".CS_SqlPrefix."blog where uid=".$_SESSION['cscms__id']." order by addtime asc LIMIT ".$limit);
                          }
                   }
                   //�жϷÿͱ�������
                   if($table=='funco' && User_Fkts>0 && !empty($_SESSION['cscms__id'])){
                           $rows=$this->db->query("select count(*) as count from ".CS_SqlPrefix."funco where uida=".$_SESSION['cscms__id']."")->result_array();
                           $nums=(int)$rows[0]['count'];
                          if($nums>User_Ssts){
							   $limit=$nums-User_Ssts;
                               $this->db->query("DELETE FROM ".CS_SqlPrefix."funco where uida=".$_SESSION['cscms__id']." order by addtime asc LIMIT ".$limit);
                          }
                    }
                    //�жϷ�˿��������
                    if($table=='fans' && User_Fsts>0 && !empty($_SESSION['cscms__id'])){
                           $rows=$this->db->query("select count(*) as count from ".CS_SqlPrefix."fans where uida=".$_SESSION['cscms__id']."")->result_array();
                           $nums=(int)$rows[0]['count'];
                          if($nums>User_Ssts){
							   $limit=$nums-User_Ssts;
                               $this->db->query("DELETE FROM ".CS_SqlPrefix."fans where uida=".$_SESSION['cscms__id']." order by addtime asc LIMIT ".$limit);
                          }
                    }
                    //�жϹ�ע��������
                    if($table=='friend' && User_Hyts>0 && !empty($_SESSION['cscms__id'])){
                           $rows=$this->db->query("select count(*) as count from ".CS_SqlPrefix."friend where uida=".$_SESSION['cscms__id']."")->result_array();
                           $nums=(int)$rows[0]['count'];
                          if($nums>User_Ssts){
							   $limit=$nums-User_Ssts;
                               $this->db->query("DELETE FROM ".CS_SqlPrefix."friend where uida=".$_SESSION['cscms__id']." order by addtime asc LIMIT ".$limit);
                          }
                    }
			 }
		     return $ids;
          }else{
		     return false;
          }
	}

    //�޸�
    function get_update($table,$id,$arr,$zd='id'){
          if(!empty($id)){
	            if(is_array($id)){
		          $this->db->where_in($zd,$id);
	            }else{
		          $this->db->where($zd,intval($id));
	            }
	            if($this->db->update($table,$arr)){
	                 return true;
                }else{
		             return false;
                }
          }else{
		         return false;
          }
    }

    //ɾ��
    function get_del ($table,$ids,$zd='id'){
	       if(is_array($ids)){
		          $this->db->where_in($zd,$ids);
	       }else{
		          $this->db->where($zd,intval($ids));
	       }
	       if($this->db->delete($table)){
	              return true;
           }else{
		          return false;
           }
	}

    //������
    function get_table ($sql){
             //�������ݿ�
             $lnk=@mysql_connect(CS_Sqlserver,CS_Sqluid,CS_Sqlpwd);
             if(!$lnk){
                  $this->CsdjAdmin->Admin_Url('<font color=red>��Ǹ,���ݿ�����ʧ�ܣ�</font>','javascript:history.back();');
                  exit();
             }
	         @mysql_select_db(CS_Sqlname,$lnk);
	         @mysql_query("SET NAMES ".CS_Sqlcharset."", $lnk);
             @mysql_query($sql, $lnk);
	 }

     //��ȡ�����ֶ���Ϣ
     function getzd($table,$ziduan,$id,$cha='id'){
              if($table && $ziduan && $id){
	      	        $this->db->where($cha,$id);
	   	            $this->db->select($ziduan);
	   	            $row=$this->db->get($table)->row();
		            if($row){
			             return $row->$ziduan;
		            }else{
			             return "";	
		            }
			  }
     }

	 //�����������ID  �� cid=1,2,3,4,5,6
	 function getchild($cid,$table){
        	   if(!empty($cid)){
             	    $ClassArr=explode(',',$cid);
               	 	for($i=0;$i<count($ClassArr);$i++){
				             $sql="select id from ".CS_SqlPrefix.$table." where fid='$ClassArr[$i]'";//sql������֯����
				             $result=$this->db->query($sql);
				             if($result){
                        	    foreach ($result->result() as $row) {
                              		$ClassArr[]=$row->id;
                        	    }
							 }
                	}
          		    $cid=implode(',',$ClassArr);
			   }
		       return $cid;
	  }
}

