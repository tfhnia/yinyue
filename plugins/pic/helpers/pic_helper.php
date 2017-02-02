<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-21
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

//�����������ID  �� cid=1,2,3,4,5,6
function getChild($CID,$type='pic_list',$zd='fid'){
	 $ci = &get_instance();
	 if (!isset($ci->db)){
        $ci->load->database();
	 }
     if(!empty($CID)){
            $ClassArr=explode(',',$CID);
            for($i=0;$i<count($ClassArr);$i++){
				$sql="select id from ".CS_SqlPrefix.$type." where ".$zd."='$ClassArr[$i]'";//sql������֯����
				$result=$ci->db->query($sql)->result();
				if(!empty($result)){
                       foreach ($result as $row) {
                            $ClassArr[]=$row->id;
                       }
                }
          		$CID=implode(',',$ClassArr);
       		}
	 }
	 return $CID;
}
//��ȡ��������
function getcount($table='pic',$sid=0){
	 $ci = &get_instance();
	 if (!isset($ci->db)){
        $ci->load->database();
	 }
	 $sql="select count(*) as count from ".CS_SqlPrefix.$table." where yid=0 and hid=0";
     if($sid>0){
		$time=strtotime(date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d"),date("Y"))));
	    $sql.=" and addtime>".$time."";
     }
	 $query=$ci->db->query($sql)->result_array();
     $nums=$query[0]['count'];
	 return (int)$nums;
}

//��ǩ����
function SearchLink($Search_Key,$fid='tags'){
	 $Search_link='http://'.Web_Url.Web_Path.'index.php/pic/'; //����ַ
     $Search_List=$Search_Key1="";
     $Search_Key=trim($Search_Key);
     $Str=" @,@��@|@/@_";
     $StrArr=explode('@',$Str);
     for($i=0;$i<=5;$i++){
            if(stristr($Search_Key,$StrArr[$i])){
                 $Search_Key1=explode($StrArr[$i],$Search_Key);
            }
	 }
     if(is_array($Search_Key1)){
            for($j=0;$j<count($Search_Key1);$j++){
                 $Search_List.="<A target=\"tags\" HREF=\"".$Search_link."search?".$fid."=".urlencode($Search_Key1[$j])."\">".$Search_Key1[$j]."</A> ";
			}
	 }else{
                 $Search_List="<A target=\"tags\" HREF=\"".$Search_link."search?".$fid."=".urlencode($Search_Key)."\">".$Search_Key."</A> ";
	 }
	 //������������
	 $Ym_Mode=config('Ym_Mode','pic'); //��������״̬
	 $Ym_Url=config('Ym_Url','pic');   //����������ַ
	 $Web_Mode=config('Web_Mode','pic');   //�������ģʽ
	 if($Ym_Mode==1){
		 if($Web_Mode==2){
	          $Search_List  = str_replace(Web_Url.Web_Path."index.php/pic/",$Ym_Url.Web_Path,$Search_List);
		 }else{
	          $Search_List  = str_replace(Web_Url.Web_Path."index.php/pic/",$Ym_Url.Web_Path."index.php/",$Search_List);
		 }
	 }
	 if($Web_Mode==2){
         $Search_List  = str_replace("index.php/","",$Search_List);
	 }
     return $Search_List;
}
