(function(a){typeof a.CMP=="undefined"&&(a.CMP=function(){var b=/msie/.test(navigator.userAgent.toLowerCase()),c=function(a,b){if(b&&typeof b=="object")for(var c in b)a[c]=b[c];return a},d=function(a,d,e,f,g,h,i){i=c({width:d,height:e,id:a},i),h=c({allowfullscreen:"true",allowscriptaccess:"always"},h);var j,k,l=[];if(g){j=g;if(typeof g=="object"){for(var m in g)l.push(m+"="+encodeURIComponent(g[m]));j=l.join("&")}h.flashvars=j}k="<object ",k+=b?'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ':'type="application/x-shockwave-flash" data="'+f+'" ';for(var m in i)k+=m+'="'+i[m]+'" ';k+=b?'><param name="movie" value="'+f+'" />':">";for(m in h)k+='<param name="'+m+'" value="'+h[m]+'" />';return k+="</object>",k},e=function(c){var d=document.getElementById(c);if(!d||d.nodeName.toLowerCase()!="object")d=b?a[c]:document[c];return d},f=function(a){if(a){for(var b in a)typeof a[b]=="function"&&(a[b]=null);a.parentNode.removeChild(a)}},g=function(a){if(a){var c=typeof a=="string"?e(a):a;if(c&&c.nodeName.toLowerCase()=="object")return b?(c.style.display="none",function(){c.readyState==4?f(c):setTimeout(arguments.callee,15)}()):c.parentNode.removeChild(c),!0}return!1};return{create:function(){return d.apply(this,arguments)},write:function(){var a=d.apply(this,arguments);return document.write(a),a},get:function(a){return e(a)},remove:function(a){return g(a)}}}());var b=function(b){b=b||a.event;var c=b.target||b.srcElement;if(c&&typeof c.cmp_version=="function"){var d=c.skin("list.tree","maxVerticalScrollPosition");if(d>0)return c.focus(),b.preventDefault&&b.preventDefault(),!1}};a.addEventListener&&a.addEventListener("DOMMouseScroll",b,!1),a.onmousewheel=document.onmousewheel=b})(window);

var isClose=1;
var listenFlag=1;
var pid=0;
var bid=0;
var downlink='';
var cmpo;
function cmp_loaded(key) {
	cmpo = CMP.get("cmp");
	if (cmpo) {
		cmpo.addEventListener("view_list", "openList");
		cmpo.addEventListener("view_prev", "cmp_prev_click");
		cmpo.addEventListener("view_next", "cmp_next_click");
		cmpo.addEventListener("view_random", "cmp_random");
		cmpo.addEventListener("view_link", "cmp_fav");
		cmpo.addEventListener("view_lrc", "cmp_down");
		cmpo.addEventListener("model_state", "cmp_model_state");
		addplay(0);
	}
}
function cmp_fav(){
        var danceid=Music[pid]['id'];
        parent.dance_fav();
}
function cmp_down(){
	    window.open(downlink, '_blank');
}
function cmp_random(){
        if(bid==0){
	    bid=1;
        }else{
	    bid=0;
        }
}
function cmp_next_click(){
        if(bid==1){
	    addplay(get_random());
        }else{
            if(pid==(Music.length-1)){
	        addplay(0);
            }else{
	        addplay((pid+1));
            }
        }
}
function cmp_prev_click(){
        if(bid==1){
	    addplay(get_random());
        }else{
            if(pid==0){
	        addplay((Music.length-1));
            }else{
	        addplay((pid-1));
            }
        }
}
function cmp_model_state(state){
	if (state == "completed") {
		cmp_next_click();
	}
}

function get_random(){
	var index = Math.floor(Math.random()*Music.length);
	return index;
}
function addplay(id){
	if (!cmpo) return;
	var xmlstr = '<list><m src="'+Music[id]['purl']+'" label="'+Music[id]['name']+'" /></list>';
	cmpo.list_xml(xmlstr, false);
	cmpo.sendEvent("view_play");
	downlink=Music[pid]['downurl'];
        pid=id;		
        parent.$('#userpic').html('<a style="color:#ccc;text-decoration:none" href="'+Music[id]['ulink']+'" target="_blank" title="查看个人空间"><img style="vertical-align:middle;" width="20" height="20" src="'+Music[id]['face']+'"><font style="margin-left:10px;">'+Music[id]['nickname']+'</font></a>');
}
function openList(){
        var danceList=parent.$('#mpList').html();
	if(danceList==''){
		danceList = "<div class=\"mpListScroll\"><ul id=\"player_list\">";
		for(var i=0;i<Music.length;i++){
				danceList += "<li><a class=\"danceName\" id=\"" +i+ "\" href=\"javascript:;\" Onclick=\"$('#player')[0].contentWindow.addplay("+i+");$('#mpList').hide();\">" +(i+1)+"."+Music[i].name+ "</a></li>";
		}
		danceList += "<li><a class=\"danceName\" href=\"/\" target=\"_blank\">查看更多开场播放的舞曲...</a></li>";
		danceList = danceList;
		isClose=0;
		parent.$('#mpList').html(danceList).show();
	}
	else{
		isClose=0;
		parent.$('#mpList').show();
	}
        if(isClose==0){
	     parent.$('#mpList').show();
        }else{
             isClose=1;
	     parent.$('#mpList').hide();
        }
}
function create(){
	var flashvars = {
        bgcolor: "333333",
		name: "cscms",
		link: "http://www.chshcms.com/",
		link_target: "_blank",
		skin: cscms_path+"cscms/tpl/skins/skin_hei/real/cscms_index.swf",
		api: "cmp_loaded"
	};
	var htm = CMP.create("cmp", "960", "34", cscms_path+"packs/vod_player/cmp/cmp.swf", flashvars, {wmode:"opaque"});
	document.write(htm);
}
