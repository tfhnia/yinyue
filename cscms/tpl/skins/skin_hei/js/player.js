var DomainUrl = top.location.hostname; 
var pid=2;
set_history(dance_link, dance_name, dance_id);
(function(a){typeof a.CMP=="undefined"&&(a.CMP=function(){var b=/msie/.test(navigator.userAgent.toLowerCase()),c=function(a,b){if(b&&typeof b=="object")for(var c in b)a[c]=b[c];return a},d=function(a,d,e,f,g,h,i){i=c({width:d,height:e,id:a},i),h=c({allowfullscreen:"true",allowscriptaccess:"always"},h);var j,k,l=[];if(g){j=g;if(typeof g=="object"){for(var m in g)l.push(m+"="+encodeURIComponent(g[m]));j=l.join("&")}h.flashvars=j}k="<object ",k+=b?'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ':'type="application/x-shockwave-flash" data="'+f+'" ';for(var m in i)k+=m+'="'+i[m]+'" ';k+=b?'><param name="movie" value="'+f+'" />':">";for(m in h)k+='<param name="'+m+'" value="'+h[m]+'" />';return k+="</object>",k},e=function(c){var d=document.getElementById(c);if(!d||d.nodeName.toLowerCase()!="object")d=b?a[c]:document[c];return d},f=function(a){if(a){for(var b in a)typeof a[b]=="function"&&(a[b]=null);a.parentNode.removeChild(a)}},g=function(a){if(a){var c=typeof a=="string"?e(a):a;if(c&&c.nodeName.toLowerCase()=="object")return b?(c.style.display="none",function(){c.readyState==4?f(c):setTimeout(arguments.callee,15)}()):c.parentNode.removeChild(c),!0}return!1};return{create:function(){return d.apply(this,arguments)},write:function(){var a=d.apply(this,arguments);return document.write(a),a},get:function(a){return e(a)},remove:function(a){return g(a)}}}());var b=function(b){b=b||a.event;var c=b.target||b.srcElement;if(c&&typeof c.cmp_version=="function"){var d=c.skin("list.tree","maxVerticalScrollPosition");if(d>0)return c.focus(),b.preventDefault&&b.preventDefault(),!1}};a.addEventListener&&a.addEventListener("DOMMouseScroll",b,!1),a.onmousewheel=document.onmousewheel=b})(window);

var cmpo;
function cmp_loaded(key) {
    cmpo = CMP.get("cmp");
    if (cmpo) {
	cmpo.addEventListener("view_prev", "play_up");
	cmpo.addEventListener("view_next", "play_next_click");
	cmpo.addEventListener("model_state", "cmp_model_state");
    }
}
function play_next_click(){
    play_next(1);
}
function cmp_model_state(state){
    if (state == "completed") {
        play_next(0);
    }
}
function playMode(_n){
	if(_n==2){
        $('#listLoop').addClass('selected');
        $('#singleCycle').removeClass('selected');
	}else{
        $('#listLoop').removeClass('selected');
        $('#singleCycle').addClass('selected');
	}
    pid=_n;
}
function cmpplay(pId){
    var flashvars = {
	auto_play: "1",
	name: "cscms",
	link: "http://www.chshcms.com/",
	link_target: "_blank",
	skin: cscms_path+"cscms/tpl/skins/skin_hei/js/cscms.swf",
	src: unescape(dance_path),
	api: "cmp_loaded"
    };
    var htm = CMP.create("cmp", "435", "60", cscms_path+"packs/vod_player/cmp/cmp.swf", flashvars, {wmode:"transparent"});
    document.getElementById(pId).innerHTML = htm;
}
function play_up(){
    var didN = dance_link;
    var j = 0;
    if(pid==2){//列表循环
        var cookie_info = get_cookie("cscmsHistory");
	if(cookie_info!=null && cookie_info!=""){
		cookisInfoOld = cookie_info.split("*cscms*");//正常cookie
		var N = cookisInfoOld.length-1;
		for(var i=0; i<N; i++){
		    cookisarr = cookisInfoOld[i].split("#");
		    if(cookisarr[0]==dance_id){					
			j = i-1;
			if(j<=0){
		            cookisarrs = cookisInfoOld[N-1].split("#");
			    if(cookisarrs[2]!=undefined){
				didN = cookisarrs[2];
			    }
			}else{
		            cookisarrs = cookisInfoOld[j].split("#");
			    if(cookisarrs[2]!=undefined){
				didN = cookisarrs[2];
			    }
			}
		    }
		}
	}
    }
    location.href = didN;
}
function play_next(s){
    var didN = dance_link;
    var j = 0;
    if(pid==2 || s==1){//列表循环
        var cookie_info = get_cookie("cscmsHistory");
	if(cookie_info!=null && cookie_info!=""){
		cookisInfoOld = cookie_info.split("*cscms*");//正常cookie
		var N = cookisInfoOld.length-1;
		for(var i=0; i<N; i++){
		    cookisarr = cookisInfoOld[i].split("#");
		    if(cookisarr[0]==dance_id){					
			j = i+1;
			if(j>=N){
		            cookisarrs = cookisInfoOld[0].split("#");
			    if(cookisarrs[2]!=undefined){
				didN = cookisarrs[2];
			    }
			}else{
		            cookisarrs = cookisInfoOld[j].split("#");
			    if(cookisarrs[2]!=undefined){
				didN = cookisarrs[2];
			    }
			}
		    }
		}
	}
    }
    location.href = didN;
}
function get_cookie(_name){
	var Res=eval('/'+_name+'=([^;]+)/').exec(document.cookie); return Res==null?'':unescape(Res[1]);
}
function SetCookie(name, value){
	var expdate = new Date();
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	if(expires!=null) expdate.setTime(expdate.getTime() + ( expires * 1000 ));
	document.cookie = name + "=" + escape (value) +((expires == null) ? "" : ("; expires="+ expdate.toGMTString()))+((path == null) ? "" : ("; path=" + path)) +((domain == null) ? "" : ("; domain=" + domain))+((secure == true) ? "; secure" : "");
}
function set_history(_url, _name, _id){
	var current_str = _id + "#" + _name + "#" + _url + "*cscms*";
	var cookie_info = get_cookie("cscmsHistory");
	var ok=0;
	if( "" != cookie_info){
		cookie_info = cookie_info.split("*cscms*");
		for(var i=0; i<N; i++){
			if(current_str == cookie_info[i] + "*cscms*"){
                ok=1;
				break;
			}
		}
	}
	if(ok==0){
	    current_str = ('' == cookie_info) ? current_str : current_str + cookie_info;
	    SetCookie("cscmsHistory", current_str, 48 * 3600, "/", DomainUrl, false);        
	}
}
function get_history(){
	var cookie_info=get_cookie("cscmsHistory").split("*cscms*");
	var N = cookie_info.length - 1;
	var history_list = "", infos, s;
	for(var i=0; i<N; i++){
		var ii = i+1;
		infos = cookie_info[i].split("#");
		var clas = (infos[0]==dance_id) ? ' class="playing"' : '';
		history_list += '<li'+clas+'><i><input name="check" type="checkbox" value="'+infos[0]+'"></i><p><a href="'+infos[2]+'" title="'+infos[1]+'">'+infos[1]+'</a></p></li>';
	}
	$("#music_list").html(history_list);
	$("#list_do").html('<a href="javascript:void(0)" onclick="del_history();return false;" class="his">清除记录</a>');
}
function del_history(){
	SetCookie("cscmsHistory", "", null, "/", DomainUrl, false);
	$("#music_list").html('<div style="text-align: center;margin-top: 100px;">试听记录清除成功！</div>');
}
function set_tab(_ac){
	if(_ac=='cookie'){
        get_history();
	}else{
        $("#music_list").html('<div style="text-align: center;margin-top: 100px;"><img src="'+cscms_path+'cscms/tpl/skins/skin_hei/images/loadingx.gif" /><br>玩命加载中,请稍等...</div>');
        $.getJSON(cscms_path+"index.php/dance/hei?ac="+_ac+"&cid="+dance_cid+"&id="+dance_id+"&callback=?",function(data){
           var html='<div style="text-align: center;margin-top: 100px;">网络故障，连接失败~</div>';
           if(data){
               if(data['code']==0){
                   html=data['str'];
				   if(html=='') html='<div style="text-align: center;margin-top: 100px;">没有相关歌曲~</div>';
               } else if(data['code']==1){
                   html='<div style="text-align: center;margin-top: 100px;">您已登陆超时，请登陆~</div>';
                   readylogin();
               }
           }
           $("#music_list").html(html);
		   $("#list_do").html('');
        });
	}
}

