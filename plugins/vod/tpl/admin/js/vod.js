function vips(){
   if($('#vip').is(':checked')) {
        $("#vips").show();
   }else{
        $("#vips").hide();
   }
}
function seo(){
   var none=$("#seo").css('display');
   if(none=='none') {
        $("#seo").show();
        $("#seo2").show();
        $("#seo1").hide();
   }else{
        $("#seo").hide();
        $("#seo1").show();
        $("#seo2").hide();
   }
}
function add(optionStr,n){
    var znum=$('#m_playarea').find("li").length;
    var i=znum+1;
	var optionStr=unescape(optionStr);
	var area="<li id='playfb"+i+"' style='float:left;margin-bottom:7px;'><label>���ŵ�ַ"+i+"</label><div class='vocation'><select class='select4' id='m_playfrom"+i+"' name='playform[]'>"+optionStr+"</select></div><div style='margin-top:5px;'>&nbsp;&nbsp;&nbsp;<input type='button' value='��ʽУ��' title='У���·��ı����е����ݸ�ʽ' onclick='repairUrl("+i+",0)' class='button'/>&nbsp;<input type='button' value='��ַ�ɼ�' title='ֱ�Ӵ���Ƶվ��ַ����ȡ��ַ' onclick='getvideoUrl("+i+",0)' class='button'/> <input type='button' name='Submit' value='��Ƶ�ϴ�' class='button' onClick=\"Uploadup('video','m_playurl',"+i+");\">��ַ��ʽ��<font color='red'>����$��ַ$��Դ</font>(�༯����)</font>&nbsp;&nbsp;<img onclick=\"$('#m_playarea li:eq("+$(this).index()+")').remove();\" src='"+parent.web_path+"packs/admin/images/btn_dec.gif' style='cursor:pointer' title='ɾ��������Դ"+i+"' align='absmiddle'/></div><div style='float:left;margin-left:85px;margin-top:5px;'><textarea class='textinput' cols='120' rows='8' name='purl[]'  id='m_playurl"+i+"'></textarea></div>";
	$('#m_playarea').append(area);
    $(".select4").uedSelect({
		width : 200			  
	});
}
function add2(optionStr,n){
    var znum=$('#m_downarea').find("li").length;
    var i=znum+1;
	var optionStr=unescape(optionStr);
	var area="<li id='playfb2"+i+"' style='float:left;margin-bottom:7px;'><label>���ص�ַ"+i+"</label><div class='vocation'><select class='select4' id='m_downfrom"+i+"' name='downform[]'>"+optionStr+"</select></div><div style='margin-top:5px;'>&nbsp;&nbsp;&nbsp;<input type='button' value='��ʽУ��' title='У���·��ı����е����ݸ�ʽ' onclick='repairUrl("+i+",2)' class='button'/>&nbsp;<input type='button' value='��ַ�ɼ�' title='ֱ�Ӵ���Ƶվ��ַ����ȡ��ַ' onclick='getvideoUrl("+i+",2)' class='button'/> <input type='button' name='Submit' value='��Ƶ�ϴ�' class='button' onClick=\"Uploadup('video','m_downurl',"+i+");\">��ַ��ʽ��<font color='#0000ff'>����$��ַ$��Դ</font>(�༯����)</font>&nbsp;&nbsp;<img onclick=\"$('#m_downarea li:eq("+$(this).index()+")').remove();\" src='"+parent.web_path+"packs/admin/images/btn_dec.gif' style='cursor:pointer' title='ɾ��������Դ"+i+"' align='absmiddle'/></div><div style='float:left;margin-left:85px;margin-top:5px;'><textarea class='textinput' cols='120' rows='8' name='durl[]'  id='m_downurl"+i+"'></textarea></div>";
	$('#m_downarea').append(area);
    $(".select4").uedSelect({
		width : 200			  
	});
}
function repairUrl(i,sid){
	if(sid==2){
		var fromText=$("#m_downfrom"+i).val();
		var urlStr=$("#m_downurl"+i).val();
	}else{
		var fromText=$("#m_playfrom"+i).val();
		var urlStr=$("#m_playurl"+i).val();
	}
	if (urlStr.length==0){alert('����д��ַ');return false;}
	var urlArray=urlStr.split("\n");
	var newStr="";
	for(j=0;j<urlArray.length;j++){
		if(urlArray[j].length>0){
			t=urlArray[j].split('$'),flagCount=t.length-1
			switch(flagCount){
				case 0:
					urlArray[j]='��'+(j<9 ? '0' : '')+(j+1)+'��$'+urlArray[j]+'$'+fromText
					break;
				case 1:
					urlArray[j]=urlArray[j]+'$'+fromText
					break;
				case 2:
					if(t[2]==''){
						urlArray[j]=urlArray[j]+fromText
					}else{
					        urlArray[j]=t[0]+'$'+t[1]+'$'+fromText
                                        }
					break;
			}
			if(urlArray[j].indexOf('qvod://')!=-1){
				var t=urlArray[j].split("$");
				t[1]=t[1].replace(/[\u3016\u3010\u005b]\w+(\.\w+)*[\u005d\u3011\u3017]/ig,'').replace(/(qvod:\/\/\d+\|\w+\|)(.+)$/i,'$1['+(window.siteurl || document.domain)+']$2').replace(/(^\s*)|(\s*$)/ig,'');
				urlArray[j]=t.join("$");
			}
			newStr+=urlArray[j]+"\n";
		}
	}
	if(sid==2){
	     $('#m_downurl'+i).val(trimOuterStr(newStr,"\n"));
	}else{
	     $("#m_playurl"+i).val(trimOuterStr(newStr,"\n"));
	}
}
function getvideoUrl(id,sid){
        Popsboxif('��ַ�ɼ�',480,176,parent.web_path+parent.web_self+'/vod/admin/vod/caiji?id='+id+'&sid='+sid);
}
function Uploadup(dir,types,ids){
        Popsboxif('�ϴ���Ƶ',550,450,parent.web_path+parent.web_self+'/upload/up?dir='+dir+'&fhid='+types+'&fid='+ids+'&sid=1&type=*&nums=10');
}
function trimOuterStr(str,outerstr){
	var len1
	len1=outerstr.length;
	if(str.substr(0,len1)==outerstr){str=str.substr(len1)}
	if(str.substr(str.length-len1)==outerstr){str=str.substr(0,str.length-len1)}
	return str
}
