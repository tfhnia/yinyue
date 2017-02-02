<?php
/**
 * @Cscms 4.x open source management system
 * @copyright 2009-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-08-21
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

//�����������ID  �� cid=1,2,3,4,5,6
function getChild($CID,$type='vod_list',$zd='fid'){
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
function getcount($table='vod',$sid=0){
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

//�����б����
function Vod_Playlist($Mark_Text,$Ac,$ID,$Data,$Zu=0,$Ji=0){
     preg_match_all("/{vod:".$Ac."}([\s\S]+?){\/vod:".$Ac."}/",$Mark_Text,$field_arr);
	 $Data_Arr=explode("#cscms#",$Data);
	 if(!empty($field_arr[0]) && !empty($field_arr)){
		for($s=0;$s<count($field_arr[0]);$s++){
			  $Mark_Text2="";$data_url="";
	          if(!empty($Data)){
			      for($i=0;$i<count($Data_Arr);$i++){
		               $Play_Content_Temp=$field_arr[1][$s];
			           $count_urls = explode("\n",$Data_Arr[$i]);
			           $count_laiys = explode("$",$count_urls[0]);
				       $Play_Content_Temp=str_replace('['.$Ac.':num]',count($count_urls),$Play_Content_Temp);
				       $Play_Content_Temp=str_replace('['.$Ac.':name]',Laiyuan(@$count_laiys[2]),$Play_Content_Temp);
				       $Play_Content_Temp=str_replace('['.$Ac.':i]',($i+1),$Play_Content_Temp);
				       $Play_Content_Temp=str_replace('['.$Ac.':ly]',@$count_laiys[2],$Play_Content_Temp);
			           preg_match_all("/{vod:url\s+order=([\s\S]+?)}([\s\S]+?){\/vod:url}/",$Play_Content_Temp,$type_arr);
                       $Url_Content_Temp=$type_arr[2][0];
			           if(!empty($type_arr) && !empty($type_arr[0])){
					       $data_url=Vod_Urllist($Url_Content_Temp,$Ac,$ID,$count_urls,$i,$Ji,$type_arr[2][0]);
			           }
			           $Mark_Text2.=str_replace($type_arr[0][0],$data_url,$Play_Content_Temp);
			      }
			  }
			  $Mark_Text=str_replace($field_arr[0][$s],$Mark_Text2,$Mark_Text);
		}
	 }
	 unset($field_arr);	
	 return $Mark_Text;
}

//���ż�������
function Vod_Urllist($Mark_Text,$Ac,$ID,$Data,$Zu=0,$Ji=0,$order){
     $data_url="";
	 if($order=='desc'){ //����
	      for($j=count($Data);$j>0;$j--){
 			  $ListName=explode('$',$Data[$j]);
			  $data_url2=str_replace("[url:link]",VodPlayUrl($Ac,$ID,$Zu,$j),$Mark_Text);
			  $data_url2=str_replace("[url:qurl]",@$ListName[1],$data_url2);
			  $data_url2=str_replace("[url:name]",$ListName[0],$data_url2);
			  $data_url2=str_replace("[url:i]",$j,$data_url2);
			  $data_url.=$data_url2;
		  }
	 }else{  //����
	      for($j=0;$j<count($Data);$j++){
 			  $ListName=explode('$',$Data[$j]);
			  $data_url2=str_replace("[url:link]",VodPlayUrl($Ac,$ID,$Zu,$j),$Mark_Text);
			  $data_url2=str_replace("[url:qurl]",@$ListName[1],$data_url2);
			  $data_url2=str_replace("[url:name]",$ListName[0],$data_url2);
			  $data_url2=str_replace("[url:i]",($j+1),$data_url2);
			  $data_url.=$data_url2;
		  }
	 }
	 return $data_url;
}

//���ŵ�ַ����
function VodPlayUrl($Ac,$ID,$Zu=0,$Ji=0,$p=0){
	 $Web_Mode=config('Web_Mode','vod');
     switch($Web_Mode){
     case '1':  //��̬ģʽ
		   if($p==0){
               $linkurl="index.php/vod/".$Ac."/".$ID."/".$Zu."/".$Ji;
		   }else{
               $linkurl="index.php/vod/".$Ac."/{id}/{zu}/{ji}";
		   }
     break;
     case '2':  //α��̬ģʽ
	       $arr=config('Rewrite_Uri','vod');
	       $linkurl= ($Ac=='play')?$arr['play']['url']:$arr['down']['url'];
		   $linkurl='vod/'.$linkurl;
		   if($p==0){
                $linkurl=str_replace(array("{id}","{zu}","{ji}"),array($ID,$Zu,$Ji),$linkurl);
		   }
     break;
     case '3':  //��̬ģʽ
	       $html=config('Html_Uri','vod');
	       if($Ac=='play' && $html['play']['check']==1){
	            $linkurl= $html['play']['url'];
		        if($p==0){
                     $linkurl=str_replace(array("{id}","{zu}","{ji}"),array($ID,$Zu,$Ji),$linkurl);
				}
		   }else{
		        if($p==0){
                     $linkurl="index.php/vod/".$Ac."/".$ID."/".$Zu."/".$Ji;
		        }else{
                     $linkurl="index.php/vod/".$Ac."/{id}/{zu}/{ji}";
		        }
		   }
     break;
	 }
	 $linkurl="http://".Web_Url.Web_Path.$linkurl;
	 //������������
	 $Ym_Mode=config('Ym_Mode','vod'); //��������״̬
	 $Ym_Url=config('Ym_Url','vod');   //����������ַ
	 if($Ym_Mode==1){
		   if($Web_Mode==2){ //α��̬
	            $linkurl  = str_replace(Web_Url.Web_Path."vod/",$Ym_Url.Web_Path,$linkurl);
		   }else{
			    $linkurl  = str_replace(Web_Url.Web_Path."index.php/vod/",$Ym_Url.Web_Path."index.php/",$linkurl);
		   }
	 }
  	 return $linkurl;
}

//��ȡ��Դ����
function Laiyuan($str){
     if(empty($str))  return "δ֪";
     require APPPATH.'config/player.php';
     $player=$player_config;
     for ($i=0; $i<count($player); $i++) {
		 if($player[$i]['form']==$str){
             $str=$player[$i]['name'];
			 break;
		 }
     }
	 return $str;
}

//��ǩ����
function SearchLink($Search_Key,$fid='tags',$name=''){
	 $Search_link='http://'.Web_Url.Web_Path.'index.php/vod/'; //����ַ
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
				 $kname=(empty($name))?$Search_Key1[$j]:$name;
                 $Search_List.="<A target=\"tags\" HREF=\"".$Search_link."search?".$fid."=".urlencode($Search_Key1[$j])."\">".$kname."</A> ";
			}
	 }else{
				 $kname=(empty($name))?$Search_Key:$name;
                 $Search_List="<A target=\"tags\" HREF=\"".$Search_link."search?".$fid."=".urlencode($Search_Key)."\">".$kname."</A> ";
	 }
	 //������������
	 $Ym_Mode=config('Ym_Mode','vod'); //��������״̬
	 $Ym_Url=config('Ym_Url','vod');   //����������ַ
	 $Web_Mode=config('Web_Mode','vod'); //�������ģʽ
	 if($Ym_Mode==1){
		 if($Web_Mode==2){
	          $Search_List  = str_replace(Web_Url.Web_Path."index.php/vod/",$Ym_Url.Web_Path,$Search_List);
		 }else{
	          $Search_List  = str_replace(Web_Url.Web_Path."index.php/vod/",$Ym_Url.Web_Path."index.php/",$Search_List);
		 }
	 }
	 if($Web_Mode==2){
         $Search_List  = str_replace("index.php/","",$Search_List);
	 }
     return $Search_List;
}

function caiji($spurl='',$sid=0){
	 $str['error']='no';
     if(!empty($spurl) && substr($spurl,0,7)=='http://'){
           
              if(strpos($spurl,'ku6.com') !== FALSE){
                    $neir=htmlall($spurl);
                    if(!empty($neir)){
                        $str['name']=str_substr('<title>',' ���߹ۿ�',$neir);
                        $str['pic']=str_substr('cover: "','"',$neir);
                        $str['url']=str_substr("snyu_page_params='vid=",'&category=',$neir);
						$str['laiy']="ku6";
                    }
              }elseif(strpos($spurl,'youku.com') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr('<meta name="irTitle" content="','"',$neir);
                        $str['pic']=str_substr('&pics=','&site=�ſ�',$neir);
                        preg_match('/http:\/\/v.youku.com\/v_show\/id_\s*(.+).html/',$spurl,$vid);
                        if(!empty($vid) && !empty($vid[1])){
                            $str['url']=$vid[1];
                        }
                        $str['laiy']="youku";
                    }
              }elseif(strpos($spurl,'tudou.com') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr(",kw: '","'",$neir);
                        $str['pic']=str_substr(",pic: '","'",$neir);
                        $str['url']=str_substr(",icode: '","'",$neir);
						$str['url']=trim($str['url']);
                        $str['laiy']="tudou";
                    }
              }elseif(strpos($spurl,'sohu.com') !== FALSE){
                    $neir=htmlall($spurl);
                    if(!empty($neir)){
                        $str['name']=str_substr('<title>',' -',$neir);
                        $str['pic']=str_substr('var cover="','";',$neir);
                        $str['url']=str_substr('var vid="','";',$neir);
                        $str['laiy']="sohu";
                    }
              }elseif(strpos($spurl,'sina.com.cn') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr("title:'","',",$neir);
                        $str['pic']=str_substr("pic: '","'",$neir);
                        $str['url']=str_substr("vid:'","'",$neir);
                        $str['laiy']="sina";
                    }
              }elseif(strpos($spurl,'qq.com') !== FALSE){
                    $neir=get_curl_contents($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr('title : "','"',$neir);
                        $str['pic']=str_substr('pic :"','",',$neir);
                        $str['url']=str_substr('vid:"','"',$neir);
                        $str['laiy']="qq";
                    }
              }elseif(strpos($spurl,'pps.tv') !== FALSE){
                    $neir=htmlall($spurl);
                    if(!empty($neir)){
                        $str['name']=str_substr("<meta name='description' content=",'">',$neir);
                        $str['name']=substr($str['name'],1);
                        $str['pic']=str_substr('"sharepic":"','",',$neir);
                        $str['pic']=str_replace("\\","",$str['pic']);
                        $str['url']=str_substr('"url_key":"','",',$neir);
                        $str['laiy']="pps";
                    }
              }elseif(strpos($spurl,'letv.com') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr('title:"','",',$neir);
                        $str['pic']=str_substr('videoPic:"','"',$neir);
                        $str['url']=str_substr('vid:',',',$neir);
                        $str['laiy']="letv";
                    }
              }elseif(strpos($spurl,'56.com') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
						preg_match_all('/<img src="(.*)" alt="(.*)" class="user_cover_img" \/>/',$neir,$arr);
						if(!empty($arr[1][0])){
                            $str['pic']=$arr[1][0];
                            $str['name']=$arr[2][0];
						}
                        $str['url']=str_substr('"id":',',',$neir);
                        $str['laiy']="56";
                    }
              }elseif(strpos($spurl,'yinyuetai.com') !== FALSE){
                    $neir=htmlall($spurl,'utf-8');
                    if(!empty($neir)){
                        $str['name']=str_substr('title : "','",',$neir);
                        $str['pic']=str_substr('<meta property="og:image" content="','"/>',$neir);
						preg_match('/http:\/\/v.yinyuetai.com\/video\/\s*([0-9]+)/',$spurl,$vid);
                        $str['url']=@$vid[1];
                        $str['laiy']="yyt";
                    }
              }
	 }
     if(empty($str['url'])){
           $str['error']='no';
     }else{
		   $str['error']='ok';
     }
	 if(!empty($str['name'])) $str['name']=get_bm($str['name'],'gbk','utf-8');
	 if($sid==0) $str=json_encode($str);
	 return $str;
}
// �ַ�����ȡ����
function str_substr($start,$end,$str,$sid=1){  
             if(!empty($start) && !empty($end)){
                  $temp = explode($start, $str);
                  if(!empty($temp[1])){
                      $content = explode($end, $temp[1], 2);  
                      if($sid==2){
                             $content[0] = preg_replace("/<a[^>]+>(.+?)<\/a>/i","$1",$content[0]);
	                         //���˻��з�
	                         $content[0] = preg_replace("/ /","",$content[0]);
	                         $content[0] = preg_replace("/&nbsp;/","",$content[0]);
	                         $content[0] = preg_replace("/��/","",$content[0]);
	                         $content[0] = preg_replace("/\r\n/","",$content[0]);
	                         $content[0] = str_replace(chr(13),"",$content[0]);
	                         $content[0] = str_replace(chr(10),"",$content[0]);
	                         $content[0] = str_replace(chr(9),"",$content[0]);
                      }
				  }else{
                      $content[0]="";
				  } 
			 }else{
                   $content[0]="";
			 }
             return $content[0];     
}  
//��ȡƽ������
function getpf($pf, $phits, $sid=1){
        if($pf==0) return 0;
        $aver = $pf / $phits;
		if($sid==1){ //ƽ������
           $aver = round($aver, 1);
		}else{  //�ٷֱ�
           $aver = round($aver, 2) * 10;
		}
        return $aver;
}
//ץȡ����
function get_curl_contents($url,$bm='utf-8', $second = 30){
    if(!function_exists('curl_init')) die('php.iniδ����php_curl.dll');
    $c = curl_init();
    curl_setopt($c,CURLOPT_URL,$url);
    $UserAgent=$_SERVER['HTTP_USER_AGENT'];
    curl_setopt($c,CURLOPT_USERAGENT,$UserAgent);
    curl_setopt($c,CURLOPT_HEADER,0);
    curl_setopt($c,CURLOPT_TIMEOUT,$second);
    curl_setopt($c,CURLOPT_RETURNTRANSFER, true);
    $cnt = curl_exec($c);
    $cnt=mb_check_encoding($cnt,$bm)?iconv('utf-8','gbk//IGNORE',$cnt):$cnt; //�ַ�����ת��
    curl_close($c);
    return $cnt;
}

//��ȡ���ż�¼��ʵ��ID���������ַ
function get_play_url($id='',$ac='show'){
    $arr = explode('-', $id);
	if($ac=='show'){
        return linkurl('show','id',$arr[0],1,'vod');
	}else{
        return VodPlayUrl('play',$arr[0],$arr[1],$arr[2]);
	}
}