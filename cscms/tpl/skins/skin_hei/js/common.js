$(function () {
	//搜索切换
	$('.seh_list strong').hover(function () {
		$("#seh_sort").show();
	}, function () {
		$("#seh_sort").hide();
	});
	$('.seh_sort').hover(function () {
		$("#seh_sort").show();
	}, function () {
		$("#seh_sort").hide();
	});
	$("#Nav li").hover(function() {
		$(this).find("ul").show(10);
	},function() {
		$(this).find("ul").hide(30);
	});
	$(document).bind("click", function (e) {
		$("#mpList").hide();
	});	
	$(".member_login li").hover(function() {
		$(this).find(".m_set_list").show(10);
	},function() {
		$(this).find(".m_set_list").hide(30);
	});
	$(".tabs a").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
		var ac=$(this).attr('sid');
		set_tab(ac);
	});
});
//歌曲全选播放
function playsong(_ac,_id){
　　var a=$("#"+_id+" input[name='check']"); 
    if(_ac=='play'){
        var v = [];
        for (var i = 0; i < a.length; i++) {
            if(a[i].checked==true){
				var did=a[i].value;
			    v.push(did);
		    }
		}
        if (1 > v.length){ 
             do_alert('请选择要播放的歌曲！');return; 
        }else{
             window.open(cscms_path+'index.php/dance/playsong?id=' + v.join(','), 'p');
        }
	}else{
        for (var i = 0; i < a.length; i++) {
            if(a[i].checked==true){
			    a[i].checked="";
		    }else{ 
			    a[i].checked="checked";
		    }
		}
    }
}
//搜索选项
function getsearch(type, text) {
	$("#keytype").val(type);
	$("#keytxt").html(text);
	$("#seh_sort").hide();
}
//搜索
function search_ok() {
	var key = $(".seh_v").val();
	var type = $("#keytype").val();
	if (key == '') {
		do_alert("请输入您要搜索的内容！");
	} else {
		var url = cscms_path + "index.php/"+type+"/search?key=" + encodeURIComponent(key);
		window.open(url,'so');
	}
}
//播放器
function bfq() {
	document.writeln("<iframe id=\"player\" marginwidth=\"0\" marginheight=\"0\" src=\"" + cscms_path + "cscms/tpl/skins/skin_hei/real/index.php\" frameborder=\"0\" width=\"960\" scrolling=\"no\" height=\"34\" leftmargin=\"0\" topmargin=\"0\"></iframe>");
}
//通用选卡
function nTabs(Ac,Num){
    var tabNum = $("#"+Ac+" li").length;
    for(i=1; i <=tabNum; i++)
    {
      if (i == Num)
      {              
          $('#'+Ac+'_List'+i).attr('class','on'); 
          $('#'+Ac+'_Content'+i).show(); 
      }else{
          $('#'+Ac+'_List'+i).attr('class','normal'); 
          $('#'+Ac+'_Content'+i).hide(); 
      }
    } 
}
//登陆窗口显示
function readylogin() {
	$('.cscms-popover-mask').fadeIn(100);
	$('.cscms-popover').slideDown(200);
}
//登陆窗口关闭
function closelogin() {
	$('.cscms-popover-mask').fadeOut(10);
	$('.cscms-popover').slideUp(20);
}
//顶歌曲 
function dance_ding(){
     $.getJSON(cscms_path+"index.php/dance/ajax/danceding/"+dance_id+"?callback=?",function(data) {
           if(data){
               if(data['msg']=='ok'){
                   $("#dhits").text(parseInt($("#dhits" ).text()) + 1);
                   do_alert('感谢您的支持!',2);
               }else{
                   do_alert(data['msg']);
               }
           } else {
                do_alert('网络故障，连接失败!');
           }
     });
}
//收藏歌曲 
function dance_fav(){
     $.getJSON(cscms_path+"index.php/dance/ajax/dancefav/"+dance_id+"?callback=?",function(data) {
           if(data){
               if(data['msg']=='ok'){
                   $("#shits").text(parseInt($("#shits" ).text()) + 1);
                   do_alert('歌曲收藏成功!',2);
               }else{
                   do_alert(data['msg']);
               }
           } else {
                do_alert('网络故障，连接失败!');
           }
     });
}
//播放器暂停
function bfqzt() {
	document.writeln("<iframe id=\"player\" marginwidth=\"0\" marginheight=\"0\" src=\"" + cscms_path + "cscms/tpl/skins/skin_hei/real/cookie.php\" frameborder=\"0\" width=\"0\" scrolling=\"no\" height=\"0\" leftmargin=\"0\" topmargin=\"0\"></iframe>");
}
//粉丝关组/取消
function guanzu(uid,sid){
     $.getJSON(cscms_path+"index.php/home/ajax/fans/"+uid+"/"+sid+"?random="+Math.random()+"&callback=?",function(data) {
          if(data['error']=='ok'){ //关注成功
               do_alert('关注成功',2);
          } else if(data['error']=='del'){ //解除成功
               do_alert('解除成功',2);
          } else {
               do_alert(data['error']);
          }
     });
}
var fans = {
	//加为粉丝
	fansAdd: function(uid, nickname, fid){ 
		guanzu(uid,0);
	},	
	//取消粉丝
	fansDel: function(uid, nickname, fid){
		guanzu(uid,0);
	}
};
$(function() {
	if ($(".sp_card").length <= 0) {
		var html = '<div class="sp_card" style="display:none; height:180px;">' + '<div class="sp_card_content">' + '<div class="sp_card_loading" style="display:none;"><img src="'+cscms_path+'cscms/tpl/skins/skin_hei/images/loading.gif" width="32" height="32" alt="正在加载中..." /></div>' + '<div id="user_info_cord"></div>' + '<b class="sp_caret sp_caret_out" style="border-width: 8px 7px 0px; bottom: -8px; left: 4px; border-color: rgb(207, 207, 207) transparent transparent; border-style: solid dashed dashed;"></b><b class="sp_caret sp_caret_in" style="border-width: 7px 6px 0px; bottom: -7px; left: 5px; border-color: rgb(255, 255, 255) transparent transparent; border-style: solid dashed dashed;"></b>' + '</div>';
		$(html).appendTo("body");
	}
	var $card = $(".sp_card");
	var v = $(".sp_card .sp_card_view"), uci = $(".sp_card .sp_card_intro");
	var w = $(".sp_card .sp_card_medal"), ucf = $(".sp_card .sp_card_follow"), ucl = $(".sp_card .sp_card_loading");
	var $sp_caret = $card.find(".sp_caret");
	var closeCardTimer = null;
	var loadTimer = null;
	
	function bindCardInfo(res) {
		if (res['success']) {
            $('.sp_card_loading').hide();
            $('#user_info_cord').html(res['success']);
		}
		else {
			$card.hide();
		}
	}

	$(".user_card").on({
		"mouseover": function() {
			if (closeCardTimer != null) {
				clearTimeout(closeCardTimer);
				closeCardTimer = null
			}
			if (loadTimer != null) {
				clearTimeout(loadTimer);
				loadTimer = null
			}
			var obj = this;
			loadTimer = setTimeout(function() {
                $('#user_info_cord').html('');
				ucl.show();
				v.hide();
				uci.hide();
				w.hide();
				ucf.hide();
				var objH = $(obj).height();
				var ucH = 180;
				var offset = $(obj).offset();
				var st = 0;
				var sl = 0;
				if (document.documentElement) {
					st = parseInt(document.documentElement.scrollTop);
					sl = parseInt(document.documentElement.scrollLeft)
				}
				if (st <= 0 && document.body) {
					st = parseInt(document.body.scrollTop)
				}
				if (sl <= 0 && document.body) {
					sl = parseInt(document.body.scrollLeft)
				}
				var g = offset.top - st;
				var h = $(window).height() - offset.top + st - $(obj).height();
				var i = offset.left;
				var j = $(window).width() + sl - offset.left;
				var k = 0;
				if (j < 350) {
					k = 350 - j
				}
				var objHalfW = parseInt($(obj).width() / 2);
				if (isNaN(objHalfW)) objHalfW = 28;
				if (g < ucH && h >= ucH) {
					$sp_caret.eq(0).css({
						"border-width": "0 8px 7px 7px",
						"bottom": (ucH - 2) + "px",
						"left": (objHalfW - 8 + k) + "px",
						"border-color": "transparent transparent #CFCFCF transparent",
						"border-style": "dashed dashed solid dashed"
					});
					$sp_caret.eq(1).css({
						"border-width": "0 6px 7px 6px",
						"bottom": (ucH - 2) + "px",
						"left": (objHalfW - 7 + k) + "px",
						"border-color": "transparent transparent #fff transparent",
						"border-style": "dashed dashed solid dashed"
					});
					$card.css({
						left: offset.left - k,
						top: offset.top + objH + 8
					}).show()
				} else {
					$sp_caret.eq(0).css({
						"border-width": "8px 7px 0 7px",
						"bottom": "-8px",
						"left": (objHalfW - 8 + k) + "px",
						"border-color": "#CFCFCF transparent transparent transparent",
						"border-style": "solid dashed dashed dashed"
					});
					$sp_caret.eq(1).css({
						"border-width": "7px 6px 0 6px",
						"bottom": "-7px",
						"left": (objHalfW - 7 + k) + "px",
						"border-color": "#fff transparent transparent transparent",
						"border-style": "solid dashed dashed dashed"
					});
					$card.css({
						left: offset.left - k,
						top: offset.top - ucH - 8
					}).show()
				}
				var uid = $(obj).attr("uid");

				var cardInfo = $("body").data("userCard" + uid);
				if(typeof cardInfo == "undefined" || cardInfo == 0){

						$.getJSON(cscms_path+"index.php/ajax/user_card?", {
							uid: uid
						},
						function(res) {
							$("body").data("userCard" + res['uid'], res);
							$(obj).attr("uid", res['uid']);
							bindCardInfo(res)
						})
				}
				else{
						
					bindCardInfo(cardInfo)
				}

			},
			800);
		},
		"mouseout": function() {
			closeCardTimer = setTimeout(function() {
				$card.hide();
				if (loadTimer != null) {
					clearTimeout(loadTimer);
					loadTimer = null
				}
			},
			400);
		}
	});
	
	$card.hover(function() {
		if (closeCardTimer != null) {
			clearTimeout(closeCardTimer);
			closeCardTimer = null
		}
	},
	function() {
		closeCardTimer = setTimeout(function() {
			$card.hide();
			if (loadTimer != null) {
				clearTimeout(loadTimer);
				loadTimer = null
			}
		},
		400)
	});
});

//隐藏文字
function hideText(e,conLen,str1,str2){textBox=document.getElementById(e);if(""==conText){conText=textBox.innerHTML}if(navigator.appName.indexOf("Explorer")>-1){if(textBox.innerText.length<conLen){return}textBox.innerHTML=textBox.innerText.substr(0,conLen)}else{if(textBox.textContent.length<conLen){return}textBox.innerHTML=textBox.textContent.substr(0,conLen)}textBox.innerHTML+='.....</div><div style="float:right;margin-top:15px;"><a class="singerintro" href="javascript:;" onclick="showText(\''+e+"','"+conLen+"', '"+str1+"', '"+str2+'\');return false" target="_self">'+str1+"</a></div><br>"}function showText(e,conLen,str1,str2){textBox=document.getElementById(e);textBox.innerHTML=conText+'</div><div style="float:right;margin-top:15px;"><a class="singerintro" href="javascript:;" onclick="hideText(\''+e+"', '"+conLen+"', '"+str1+"', '"+str2+'\');return false" target="_self">'+str2+"</a></div><br>"};

//滚动条
(function(e){e.fn.extend({slimScroll:function(g){var a=e.extend({width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:.2,railDraggable:!0,railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,wheelStep:20,touchScrollStep:200,borderRadius:"7px",railBorderRadius:"7px"},g);this.each(function(){function u(d){if(r){d=d||window.event;
var c=0;d.wheelDelta&&(c=-d.wheelDelta/120);d.detail&&(c=d.detail/3);e(d.target||d.srcTarget||d.srcElement).closest("."+a.wrapperClass).is(b.parent())&&m(c,!0);d.preventDefault&&!k&&d.preventDefault();k||(d.returnValue=!1)}}function m(d,e,g){k=!1;var f=d,h=b.outerHeight()-c.outerHeight();e&&(f=parseInt(c.css("top"))+d*parseInt(a.wheelStep)/100*c.outerHeight(),f=Math.min(Math.max(f,0),h),f=0<d?Math.ceil(f):Math.floor(f),c.css({top:f+"px"}));l=parseInt(c.css("top"))/(b.outerHeight()-c.outerHeight());
f=l*(b[0].scrollHeight-b.outerHeight());g&&(f=d,d=f/b[0].scrollHeight*b.outerHeight(),d=Math.min(Math.max(d,0),h),c.css({top:d+"px"}));b.scrollTop(f);b.trigger("slimscrolling",~~f);v();p()}function C(){window.addEventListener?(this.addEventListener("DOMMouseScroll",u,!1),this.addEventListener("mousewheel",u,!1)):document.attachEvent("onmousewheel",u)}function w(){s=Math.max(b.outerHeight()/b[0].scrollHeight*b.outerHeight(),30);c.css({height:s+"px"});var a=s==b.outerHeight()?"none":"block";c.css({display:a})}
function v(){w();clearTimeout(A);l==~~l?(k=a.allowPageScroll,B!=l&&b.trigger("slimscroll",0==~~l?"top":"bottom")):k=!1;B=l;s>=b.outerHeight()?k=!0:(c.stop(!0,!0).fadeIn("fast"),a.railVisible&&h.stop(!0,!0).fadeIn("fast"))}function p(){a.alwaysVisible||(A=setTimeout(function(){a.disableFadeOut&&r||x||y||(c.fadeOut("slow"),h.fadeOut("slow"))},1E3))}var r,x,y,A,z,s,l,B,k=!1,b=e(this);if(b.parent().hasClass(a.wrapperClass)){var n=b.scrollTop(),c=b.parent().find("."+a.barClass),h=b.parent().find("."+a.railClass);
w();if(e.isPlainObject(g)){if("height"in g&&"auto"==g.height){b.parent().css("height","auto");b.css("height","auto");var q=b.parent().parent().height();b.parent().css("height",q);b.css("height",q)}if("scrollTo"in g)n=parseInt(a.scrollTo);else if("scrollBy"in g)n+=parseInt(a.scrollBy);else if("destroy"in g){c.remove();h.remove();b.unwrap();return}m(n,!1,!0)}}else if(!(e.isPlainObject(g)&&"destroy"in g)){a.height="auto"==a.height?b.parent().height():a.height;n=e("<div></div>").addClass(a.wrapperClass).css({position:"relative",
overflow:"hidden",width:a.width,height:a.height});b.css({overflow:"hidden",width:a.width,height:a.height});var h=e("<div></div>").addClass(a.railClass).css({width:a.size,height:"100%",position:"absolute",top:0,display:a.alwaysVisible&&a.railVisible?"block":"none","border-radius":a.railBorderRadius,background:a.railColor,opacity:a.railOpacity,zIndex:90}),c=e("<div></div>").addClass(a.barClass).css({background:a.color,width:a.size,position:"absolute",top:0,opacity:a.opacity,display:a.alwaysVisible?
"block":"none","border-radius":a.borderRadius,BorderRadius:a.borderRadius,MozBorderRadius:a.borderRadius,WebkitBorderRadius:a.borderRadius,zIndex:99}),q="right"==a.position?{right:a.distance}:{left:a.distance};h.css(q);c.css(q);b.wrap(n);b.parent().append(c);b.parent().append(h);a.railDraggable&&c.bind("mousedown",function(a){var b=e(document);y=!0;t=parseFloat(c.css("top"));pageY=a.pageY;b.bind("mousemove.slimscroll",function(a){currTop=t+a.pageY-pageY;c.css("top",currTop);m(0,c.position().top,!1)});
b.bind("mouseup.slimscroll",function(a){y=!1;p();b.unbind(".slimscroll")});return!1}).bind("selectstart.slimscroll",function(a){a.stopPropagation();a.preventDefault();return!1});h.hover(function(){v()},function(){p()});c.hover(function(){x=!0},function(){x=!1});b.hover(function(){r=!0;v();p()},function(){r=!1;p()});b.bind("touchstart",function(a,b){a.originalEvent.touches.length&&(z=a.originalEvent.touches[0].pageY)});b.bind("touchmove",function(b){k||b.originalEvent.preventDefault();b.originalEvent.touches.length&&
(m((z-b.originalEvent.touches[0].pageY)/a.touchScrollStep,!0),z=b.originalEvent.touches[0].pageY)});w();"bottom"===a.start?(c.css({top:b.outerHeight()-c.outerHeight()}),m(0,!0)):"top"!==a.start&&(m(e(a.start).position().top,null,!0),a.alwaysVisible||c.hide());C()}});return this}});e.fn.extend({slimscroll:e.fn.slimScroll})})(jQuery);
