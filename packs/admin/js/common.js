/**
 * @Cscms 4.x open source management system
 * @copyright 2008-2015 chshcms.com. All rights reserved.
 * @Author:Cheng Jie
 * @Dtime:2014-09-20
 */

//�ٷ�������ַ�������޸ģ��޸ĺ����Զ�������
var CSCMSAPI   = 'http://upgrade.chshcms.com/';
var coolid=1;
$(function () {
        //ȫѡ
	$('.quanxuan').click(function () {
               clk();
	});
        //ģ��POST�ύ
	$('#submit').click(function () {
              $('#myform').submit();
	});
        //Ȩ��ȫѡ
	$('.sys').click(function () {
              var sid=$(this).attr('sid');
              qxclk(sid);
	});
});

//��̨��½
var adminlogin = {
	init: function(){
		$('.login-field-err').click(function () {
                        $(this).hide();
			$(this).siblings("input").focus();
		});
		$('#txtAdminName').focus(function (){
		        $(this).removeClass('error');
			$('#lab_name').hide();
			$('#err_name').hide();
		}).blur(function(){
			if($(this).val() == ''){
				$('#lab_name').hide();
				$('#err_name').show();
				$(this).addClass('error');
				return false;
			}else{
			        $(this).removeClass('error');
				$('#lab_name').hide();
				$('#err_name').hide();
			}
		}).keyup(function(){
		    $('#lab_name').hide();
		});
		$('#txtAdminPass').focus(function (){
		        $(this).removeClass('error');
			$('#lab_pass').hide();
			$('#err_pass').hide();
		}).blur(function(){
			if($(this).val() == ''){
				$('#lab_pass').hide();
				$('#err_pass').show();
				$(this).addClass('error');
				return false;
			}else{
			        $(this).removeClass('error');
				$('#lab_pass').hide();
				$('#err_pass').hide();
			}
		}).keyup(function(){
		    $('#lab_pass').hide();
		});
		$('#txtAdminCodes').focus(function (){
		        $(this).removeClass('error');
			$('#lab_codes').hide();
			$('#err_codes').hide();
		}).blur(function(){
			if($(this).val() == ''){
				$('#lab_codes').hide();
				$('#err_codes').show();
				$(this).addClass('error');
				return false;
			}else{
			        $(this).removeClass('error');
				$('#lab_codes').hide();
				$('#err_codes').hide();
			}
		}).keyup(function(){
		    $('#lab_codes').hide();
		});

		$('#btnSubmit').click(function(){
                          $('#adminlog :input').trigger('blur');
                          var numError = $('#adminlog .error').length;
			  if(!numError){
                               $("#formlogin").submit(); 
			  }
		});
		return false;
	},
	card: function(){
		$('.login-field-err').click(function () {
                        $(this).hide();
			$(this).siblings("input").focus();
		});
		$('#txtAdminName').focus(function (){
		        $(this).removeClass('error');
			$('#lab_card').hide();
			$('#err_card').hide();
		}).blur(function(){
			if($(this).val() == ''){
				$('#lab_card').hide();
				$('#err_card').show();
				$(this).addClass('error');
				return false;
			}else{
			        $(this).removeClass('error');
				$('#lab_card').hide();
				$('#err_card').hide();
			}
		}).keyup(function(){
		    $('#lab_card').hide();
		});
		$('#btnSubmit').click(function(){
                          $('#adminlog :input').trigger('blur');
                          var numError = $('#adminlog .error').length;
			  if(!numError){
                               $("#formlogin").submit(); 
			  }
		});
		return false;
	}
}
//AJAX����
function get_cmd(url,ac,id){
    $('#'+ac+'_'+id).html('<img src="'+parent.web_path+'packs/images/load.gif">');
    $.get(url+'&ac='+ac+'&id='+id,function(data) {
           if(data){
                $('#'+ac+'_'+id).html(data);
           }else{
                alert('��������ʧ�ܣ�');
           }
    });
}
//ѡ�����
function clk(){
����var a=$("input[name='id[]']");  
    for (var i = 0; i < a.length; i++) {
            a[i].checked = (a[i].checked) ? false : true;
    }
}
//ѡ��Ȩ��
function qxclk(_id){
����var a=$("#sys_"+_id+" input[name='sys[]']");  
    for (var i = 0; i < a.length; i++) {
            a[i].checked = (a[i].checked) ? false : true;
    }
}
//�����汾
function get_plub_up(_mid,_v){
     $.getJSON(parent.yun_url+"plub/version?mid="+_mid+"&v="+_v+"&callback=?",
         function(data) {
              if(data['str']=='no'){
			$('#update_'+_mid).html('<img title="����Ҫ����" align="absmiddle" src="'+parent.web_path+'packs/admin/images/icon/ok.png" />');
              } else {
			$('#update_html_'+_mid).html('<font color=red>�����°汾'+data['str']+'</font>');
			$('#update_html_'+_mid).show();
			$('#update_'+_mid).hide();
              }
    });
}
//���ģ��汾
function get_skins_up(_mid,_v){
     $.getJSON(parent.yun_url+"skins/version?mid="+_mid+"&v="+_v+"&callback=?",
         function(data) {
              if(data['str']=='no'){
			$('#update_'+_mid).html('');
			$('#update_'+_mid).hide();
              } else {
			$('#update_html_'+_mid).html('<font color=red>�����°汾'+data['str']+'</font>');
			$('#update_html_'+_mid).show();
			$('#update_'+_mid).hide();
              }
    });
}
//���
function get_card(_path){
    $.get(_path,function(data) {
           if(data){
                alert(data);
		location.href=location.href;
           }else{
                alert('��������ʧ�ܣ�');
           }
    });
}
//TAGS��ǩЧ��
function get_tags(_id){
    var none=$("#tags_"+_id+"").css('display');
    if(none=='none'){
����    $("#tags_"+_id+"").show();  
    }else{
����    $("#tags_"+_id+"").hide();  
    }
}
//����TAGS��ǩ
function get_tags_add(_url,_id){
    var xid=$("#add_xid_"+_id+"").val();
    var name=$("#add_name_"+_id+"").val();
����if(name==''){
          alert('���Ʋ���Ϊ�գ�');
          $("#add_name_"+_id+"").focus();
    }else{
	  location.href=_url+'?xid='+xid+'&name='+name+'&fid='+_id;
    }
}
//����һ���Զ���ɼ�
function zdyadd(){
    var ziduan=$("#ziduan").html();
    var html='<li id="zdy_a'+coolid+'_1"><label>�������</label><input name="zdy[name][]" type="text" class="dfinput" value="" style="width:245px;"/>&nbsp;&nbsp;<img onClick="zdydel(\'a'+coolid+'\');" name="btn_addpaly" src="'+top.web_path+'packs/admin/images/cut.png" id="btn_addplay"  style="cursor:pointer" title="ɾ�����"/></li>';
    html+='<li id="zdy_a'+coolid+'_2"><label>����ֶ�</label><div class="vocation"><select class="select3" id="zds_a'+coolid+'" name="zdy[zd][]" OnChange="zdyzd(\'a'+coolid+'\');">'+ziduan+'</select></div><input name="zdy[id][]" type="hidden" value="0"></li>';
    html+='<li id="zdy_a'+coolid+'_3"><label>���ݷ�ʽ</label><div class="vocation"><select class="select3" id="fid_a'+coolid+'" name="zdy[fid][]" OnChange="zdyfid(\'a'+coolid+'\');"><option value="0">Ŀ��ɼ�</option><option value="1" selected="elected">�ֶ�����</option></select></div></li>';
    html+='<li id="zdy_a'+coolid+'_4" style="display: none;"><label>��ʼ���</label><input class="box" name="zdy[htmlid][]" type="checkbox" value="1">&nbsp;�ɼ��������HTML����&nbsp;<br><textarea name="zdy[ks][]" cols="70" rows="3" class="textinput"></textarea></li>';
    html+='<li id="zdy_a'+coolid+'_5" style="display: none;"><label>�������</label><textarea name="zdy[js][]" cols="70" rows="3" class="textinput"></textarea></li>';
    html+='<li id="zdy_a'+coolid+'_6"><label>ָ������</label><input name="zdy[fname][]" type="text" class="dfinput" value="" style="width:245px;"/></li>';
    html+='<li id="zdy_a'+coolid+'_7" class="end"></li>';
    $('#zdys').prepend(html);
    $(".select3").uedSelect({
	width : 145			  
    });
    coolid++;
}
//���ݷ�ʽת��
function zdyfid(n){
    var s=$("#fid_"+n).val();
    var b=$("#zds_"+n).val();
    if(s==1){
        $("#zdy_"+n+"_4").hide();
        $("#zdy_"+n+"_5").hide();
        $("#zdy_"+n+"_6").show();
        $("#zdy_"+n+"_6 input").css("border","1px solid red");
    }else{
        $("#zdy_"+n+"_4").show();
        $("#zdy_"+n+"_5").show();
        if(cj_dir!='vod' || (cj_dir=='vod' && b!='purl' && b!='durl')){
           $("#zdy_"+n+"_6").hide();
        }
    }
}
//�ж���Ƶ�ɼ���ַ
function zdyzd(n){
    if(cj_dir=='vod'){
        var s=$("#zds_"+n).val();
        if(s=='purl'){
            $("#fid_"+n+" option").removeAttr("selected").change();
            $("#zdy_"+n+"_4").show();
            $("#zdy_"+n+"_5").show();
            $("#zdy_"+n+"_6").show();
            $("#zdy_"+n+"_6").html('<label>������Դ</label><div class="vocation"><select class="select1" name="zdy[fname][]">'+vod_p_lauy+'</select>');
            $(".select1").uedSelect({
		width : 145			  
	    });
        } else if(s=='durl'){
            $("#fid_"+n+" option").removeAttr("selected").change();
            $("#zdy_"+n+"_4").show();
            $("#zdy_"+n+"_5").show();
            $("#zdy_"+n+"_6").show();
            $("#zdy_"+n+"_6").html('<label>������Դ</label><div class="vocation"><select class="select1" name="zdy[fname][]">'+vod_d_lauy+'</select>');
            $(".select1").uedSelect({
		width : 145			  
	    });
        } else {
            $("#fid_"+n+" option:eq(1)").attr("selected", true).change();
            $("#zdy_"+n+"_6").html('<label>ָ������</label><input name="zdy[fname][]" type="text" class="dfinput" value="" style="width:245px;"/>');
            $("#zdy_"+n+"_6").show();
        }
    }
}
//ɾ��һ���Զ���ɼ�
function zdydel(n){
    for (var i = 1; i < 8; i++) {
        $("#zdy_"+n+"_"+i).html('');
        $("#zdy_"+n+"_"+i).hide();
    }
}
//�������ɼ�����
function ruku(_form){
����$('#myform').attr('action',_form);
    $('#myform').submit();
}
//ȷ����ʾ��
function tip_ok(_href){
	$('.webox').css({display:'none'});
	$('.background').css({display:'none'});
	parent.web_box(2);
        if(_href=='submit'){
             $('#myform').submit();
        }else{
             location.href=_href;
        }
}
//ȡ����ʾ��
function tip_cokes(){
	$('.webox').css({display:'none'});
	$('.background').css({display:'none'});
        parent.web_box(2);
}
//�༭����ȫģʽ
function AdminEditor(cid){
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="'+cid+'"]', {
		      allowFileManager : true,
		      items : [
                               'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                               'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                               'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                               'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                               'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                               'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                               'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                               'anchor', 'link', 'unlink', '|', 'about'
                            ],
		      afterBlur: function(){this.sync();}
		});
	});
}
//�༭����ģʽ
function FormEditor(cid){
	var editor;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="'+cid+'"]', {
			resizeType : 1,
			allowPreviewEmoticons : false,
			allowImageUpload : false,
			items : [
				'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
			afterBlur: function(){this.sync();}
		});
	});

}
//��Ƕ������ȷ��ȡ������
function Popsbox(_title,_width,_height,_html,_href){
                var _iframe=parent.web_path+parent.web_self+'/opt/error?link='+_href+'&neir='+_html;
		$.webox({
			height:_height,
			width:_width,
			bgvisibel:true,
			title:_title,
			iframe:_iframe,
		});
}	
//iframe���������
function Popsboxif(_title,_width,_height,_iframe){
		$.webox({
			height:_height,
			width:_width,
			bgvisibel:true,
			title:_title,
			iframe:_iframe
		});
}
$.extend({
	webox:function(option){
		var _x,_y,m,allscreen=false;
		if(!option){
			alert('options can\'t be empty');
			return;
		};
		if(!option['html']&&!option['iframe']){
			alert('html attribute and iframe attribute can\'t be both empty');
			return;
		};
		option['parent']='webox';
		option['locked']='locked';
		$(document).ready(function(e){
			$('.webox').remove();
			$('.background').remove();
			var width=option['width']?option['width']:400;
			var height=option['height']?option['height']:240;
			$('body').append('<div class="background" style="display:none;"></div><div class="webox" style="width:'+width+'px;height:'+height+'px;display:none;"><div id="inside" style="height:'+height+'px;"><h1 id="locked" onselectstart="return false;">'+(option['title']?option['title']:'webox')+'<a class="span" href="javascript:void(0);">��</a></h1>'+(option['iframe']!=''?'<iframe class="w_iframe" src="'+option['iframe']+'" frameborder="0" width="100%" scrolling="yes" style="border:none;overflow-x:hidden;height:'+parseInt(height-30)+'px;"></iframe>':option['html']?option['html']:'')+'</div></div>');
			if(navigator.userAgent.indexOf('MSIE 7')>0||navigator.userAgent.indexOf('MSIE 8')>0){
				$('.webox').css({'filter':'progid:DXImageTransform.Microsoft.gradient(startColorstr=#55000000,endColorstr=#55000000)'});
			}if(option['bgvisibel']){
                                if($(window).height()<document.body.scrollHeight){
                                     $('.background').css('height',document.body.scrollHeight);
				}else{
                                     $('.background').css('height',$(window).height());
                                }
                                $('.background').css({display:'block'});
                                parent.web_box(1);
			};
			$('.webox').css({display:'block'});
			$('#'+option['locked']+' .span').click(function(){
				$('.webox').css({display:'none'});
				$('.background').css({display:'none'});
                                parent.web_box(2);
			});
			var marginLeft=parseInt(width/2);
			var marginTop=parseInt(height/2);
			var winWidth=parseInt($(window).width()/2);
			var winHeight=parseInt($(window).height()/2.2);
			var left=winWidth-marginLeft-150;
			var top=winHeight-marginTop;
			$('.webox').css({left:left,top:top});
			$('#'+option['locked']).mousedown(function(e){
				if(e.which){
					m=true;
					_x=e.pageX-parseInt($('.webox').css('left'));
					_y=e.pageY-parseInt($('.webox').css('top'));
				}
				}).dblclick(function(){
					if(allscreen){
						$('.webox').css({height:height,width:width});
						$('#inside').height(height);
						$('.w_iframe').height(height-30);
						$('.webox').css({left:left,top:top});
						allscreen = false;
					}else{
						allscreen=true;
						var screenHeight = $(window).height();
						var screenWidth = $(window).width();$
						('.webox').css({'width':screenWidth-18,'height':screenHeight-18,'top':'0px','left':'0px'});
						$('#inside').height(screenHeight-20);
						$('.w_iframe').height(screenHeight-50);
					}
				});
			}).mousemove(function(e){
				if(m && !allscreen){
					var x=e.pageX-_x;
					var y=e.pageY-_y;
					$('.webox').css({left:x});
					$('.webox').css({top:y});
				}
			}).mouseup(function(){
				m=false;
			});
			$(window).resize(function(){
				if(allscreen){
					var screenHeight = $(window).height();
					var screenWidth = $(window).width();
					$('.webox').css({'width':screenWidth-18,'height':screenHeight-18,'top':'0px','left':'0px'});
					$('#inside').height(screenHeight-20);
					$('.w_iframe').height(screenHeight-50);
				}
			});
	}
});
