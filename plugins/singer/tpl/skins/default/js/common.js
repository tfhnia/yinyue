//��������
var singer = {
	init: function(){
            //�����л�
	    $("#music_1").hover(function(){
                  $('#music_1').addClass('selected');
                  $('#music_2').removeClass('selected');
                  $('#musics_1').show();
                  $('#musics_2').hide();
	    },function(){});
	    $("#music_2").hover(function(){
                  $('#music_2').addClass('selected');
                  $('#music_1').removeClass('selected');
                  $('#musics_2').show();
                  $('#musics_1').hide();
	    },function(){});
	    $('p[rel=htop5small]').hover(function(){
		  var idx=$(this).attr('oindex');
		  idx=Math.floor(idx);
		  for(var i=1;i<11;i++){
			if(i!=idx){
				$('#hbigDiv'+i).addClass('untxt');
				$('#hsmallDiv'+i).removeClass('untxt');
			}else{
				$('#hbigDiv'+i).removeClass('untxt');
				$('#hsmallDiv'+i).addClass('untxt');
			}
		  }
	    });
        }
}
//��ҳ����ȫ������
function playsong(n){
        var v = [];
        var nums=$('.tab_div #song-'+n).length;
        for (var i = 0; i < nums; i++) {
              var did=$('.tab_div #song-'+n+':eq('+i+')').attr('did');
              v.push(did);
        }
        window.open(cscms_path+'index.php/dance/playsong?id=' + v.join(','), 'play');
}
//�б����ȫ������
function playsongs(n){
        var v = [];
����    var a=$("input[name='check']"); 
        for (var i = 0; i < a.length; i++) {
            if(n==2){
                if(a[i].checked==true){
                    var did=a[i].value;
                    v.push(did);
                }
            }else{
                var did=a[i].value;
                v.push(did);
            }
        }
        if (1 > v.length){ 
             do_alert('��ѡ��Ҫ���ŵĸ�����');return; 
        }else{
             window.open(cscms_path+'index.php/dance/playsong?id=' + v.join(','), 'play');
        }
}
//ȫѡ����ѡ
function checkAll(){
        var v = [];
����    var a=$("input[name='check']"); 
        for (var i = 0; i < a.length; i++) {
            if(a[i].checked==true){a[i].checked="";}else{ a[i].checked="checked";}
        }
}
//��������
function showShareMusic(e, name, pic, link) {
    var show = $("#fsId").css("display");
    if (show == 'none') {
        var x = e.offsetLeft;
        var y = e.offsetTop;
        while (e = e.offsetParent) {
            x += e.offsetLeft;
            y += e.offsetTop;
        }
        $("#fsId").css("top",((y - 30) - jQuery('#checkALL').scrollTop()) + 'px');
        $("#fsId").css("left",(x - 180) + 'px');
        $("#fsId").css("display" ,'block');
        var title = "�������ס�" + name + "�������޷������ˡ���һ��Ҫת�����һ����һ����(������@����cms)�����������";
        try {
           var bdObj = $('.fenmiddle #bdshare');
           bdObj.attr("data", "{'comment':'" + title + "','text':'" + title + "','pic':'" + pic + "','url':'" + link + "'}");
        } catch(e) {}
    }
    if (show == 'block') {
        $("#fsId").css("display","none");
    }
}
