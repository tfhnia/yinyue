//��Ա
var home = {
        init: function(){
              var eTimer = null,lTimer = null,$navBtn = $('.jsNavBtn'),$sNavMenu = $('.jsSNavMenu');
              $navBtn.hover(function() {
                  if (lTimer) clearTimeout(lTimer);
                  eTimer = setTimeout(function () {
                       $sNavMenu.show();
                  }, 100);
              }, function() {
                  if (eTimer) clearTimeout(eTimer);
                  lTimer = setTimeout(function () {
                       $sNavMenu.hide();
                  }, 100)
              });
              $sNavMenu.hover(function() {
                  if (lTimer) clearTimeout(lTimer);
                  $(this).show();
              }, function() {
                  $(this).hide();
              });
              $('.seh_list').hover(function() {
                  $('.seh_sort').show();
              }, function() {
                  $('.seh_sort').hide();
              });
              $('.seh_sort a').click(function() {
                  $('.seh_list_a').html($(this).text() + '<b class="icon icon_ser_arr"></b>');
                  $('#txtKey').attr('dir',$(this).attr('dir'));
                  $('.seh_sort').hide();
              });
              $('.seh_b').click(function() {
                  var key=$('#txtKey').val();
                  var dir=$('#txtKey').attr('dir');
                  if(key==''){
                      do_alert('������Ҫ����������~');
                  }else{
	              var url=cscms_path+"index.php/"+dir+"/search?key="+encodeURIComponent(key);
	              window.open(url);
                  }
              });
              var eTimer2 = null,lTimer2 = null;
              $('.nav_up').hover(function() {
                  if (lTimer2) clearTimeout(lTimer2);
                  eTimer2 = setTimeout(function () {
                       $('.upload_box').show();
                  }, 100);
              }, function() {
                  if (eTimer2) clearTimeout(eTimer2);
                  lTimer2 = setTimeout(function () {
                       $('.upload_box').hide();
                  }, 100)
              });
              $('.upload_box').hover(function() {
                  if (lTimer2) clearTimeout(lTimer2);
                  $(this).show();
              }, function() {
                  $(this).hide();
              });
              var eTimer3 = null,lTimer3 = null;
              $('.userinfoshow').hover(function() {
                  if (lTimer3) clearTimeout(lTimer3);
                  eTimer3 = setTimeout(function () {
                       $('.head_box').show();
                  }, 100);
              }, function() {
                  if (eTimer3) clearTimeout(eTimer3);
                  lTimer3 = setTimeout(function () {
                       $('.head_box').hide();
                  }, 100)
              });
              $('.head_box').hover(function() {
                  if (lTimer3) clearTimeout(lTimer3);
                  $(this).show();
              }, function() {
                  $(this).hide();
              });
              //������
              $('.action_share').click(function(){
                   var title=$(this).attr('data-name');
                   var url=$(this).attr('data-link');
                   $('#showbg').attr('data-title',title).attr('data-url',url);
                   $('#showbg').css({width:$(window).width(),height:document.body.scrollHeight});
                   $('#showbg').show(); 
                   $('.popup').show();     
	      });
              //�رշ���
              $('.fancybox-close').click(function(){
                   $('#showbg').css({width:0,height:0});
                   $('#showbg').hide(); 
                   $('.popup').hide();     
	      });
              //����
              $('.share li').click(function(){
                   var title=$('#showbg').attr('data-title');
                   var url=$('#showbg').attr('data-url');
                   var ac=$(this).attr('class');
                   dance_share(ac,title,url);
	      });
        },
        fav: function(uid,sid){
            var followBtns = $(".song_list > ul > li a.action_fav"), otherDIds = "";
            followBtns.each(function(){
                var did = parseInt($(this).attr("did"));
                if(!isNaN(did) && did > 0) {
                    otherDIds += did + ",";
                }
            });
            if(!!otherDIds){
                $.getJSON(cscms_path+"index.php/home/ajax/favinit?did="+otherDIds+"&callback=?",function(res1){
                    followBtns.each(function(){
                        var tmpUid = parseInt($(this).attr("did"));
                        var tmpFollorBtn = $(this);
                        if(tmpFollorBtn && !isNaN(tmpUid) && tmpUid)
                        {
                            if(!!res1.data[tmpUid]){
                                 tmpFollorBtn.addClass("action_fav_clo");
                            }
                        }
                    });
                });
            }
        },
        favadd: function(did){
            $.getJSON(cscms_path+"index.php/home/ajax/fav/"+did+"?random="+Math.random()+"&callback=?",function(data) {
                      if(data['error']=='ok'){ //�ɹ�
                           $('.fav'+did).addClass("action_fav_clo");
                           do_alert('�����ղسɹ�',2);
                      } else if(data['error']=='del'){ //����ɹ�
                           $('.fav'+did).removeClass("action_fav_clo");
                           do_alert('ȡ���ղسɹ�');
                      } else {
                           do_alert(data['error']);
                      }
            });
        },
        fans: function(uid,sid){
            var followBtns = $(".fans_items > ul > li.f_item h2.c_wap a.rt"), otherUserIds = "";
            followBtns.each(function(){
                var uid = parseInt($(this).attr("uid"));
                if(!isNaN(uid) && uid > 0) {
                    otherUserIds += uid + ",";
                }
            });
            if(!!otherUserIds){
                $.getJSON(cscms_path+"index.php/home/ajax/fansinit?uid="+otherUserIds+"&callback=?",function(res1){
                    followBtns.each(function(){
                        var tmpUid = parseInt($(this).attr("uid"));
                        var tmpFollorBtn = $(this);
                        if(tmpFollorBtn && !isNaN(tmpUid) && tmpUid)
                        {
                            if(!!res1.data[tmpUid]){
                                if(res1.data[tmpUid] == 2){
                                    tmpFollorBtn.removeClass("watch_btn").addClass("other_btn").html("<i></i>�໥��ע");
                                }else{
                                    tmpFollorBtn.removeClass("watch_btn").addClass("has_btn").html("<i></i>�ѹ�ע");
                                }
                            }
                        }
                    });
                });
            }
        },
	fansadd: function(uid,sid){
              $.getJSON(cscms_path+"index.php/home/ajax/fans/"+uid+"/"+sid+"?random="+Math.random()+"&callback=?",function(data) {
                  if(data['error']=='ok'){ //��ע�ɹ�
                       if(sid==2){
                           do_alert('��ע�ɹ�',2);
                           $('.gz'+uid).removeClass("watch_btn").addClass("has_btn").html("<i></i>�ѹ�ע");
                       } else if(sid==1){
                           do_alert('��ע�ɹ�',2);
                           $('.gz'+uid).text('�ѹ�ע');
                       } else {
                           $('.fol_btn').text('ȡ����ע');
                       }
                  } else if(data['error']=='del'){ //����ɹ�
                       if(sid==2){
                           do_alert('���Ѿ���ע�˶Է�');
                       } else if(sid==1){
                           do_alert('���Ѿ���ע�˶Է�');
                           $('.gz'+uid).text('�ѹ�ע');
                       } else {
                            $('.fol_btn').text('+��ע');
                       }
                  } else {
                       do_alert(data['error']);
                  }
              });
        },
        zan: function(uid){
            $.getJSON(cscms_path+"index.php/home/ajax/zan/"+uid+"?random="+Math.random()+"&callback=?",function(data) {
                      if(data['error']=='ok'){ //�ɹ�
                           var xhits=parseInt($('#zanhits').text())+1;
                           $('#zanhits').text(xhits);
                           do_alert('лл����֧��~',2);
                      } else {
                           do_alert(data['error']);
                      }
            });
        }
}
//����ȫ������
function playsong(n){
        var v = [];
        var nums=$('.song_list #song-'+n).length;
        for (var i = 0; i < nums; i++) {
              var did=$('.song_list #song-'+n+':eq('+i+')').attr('did');
              v.push(did);
        }
        window.open(cscms_path+'index.php/dance/playsong?id=' + v.join(','), 'play');
}
//��������
function dance_share(ac,title,url) {
	if(ac=='share_qzone'){
               var url="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url="+encodeURI(url)+"&title="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��');
        } else if(ac=='share_qwei'){
               var url="http://share.v.t.qq.com/index.php?c=share&a=index&appkey=&url="+encodeURI(url)+"&title="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��')+"&site=";
        } else if(ac=='share_weibo'){
               var url="http://service.weibo.com/share/share.php?appkey=&title="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��')+"&url="+encodeURI(url);
        } else if(ac=='share_baidu'){
               var url="http://tieba.baidu.com/f/commit/share/openShareApi?url="+encodeURI(url)+"&title="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��'+url)+"&desc="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��'+url)+"&comment="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��'+url);
        } else if(ac=='share_renren'){
               var url="http://widget.renren.com/dialog/share?resourceUrl="+encodeURI(url)+"&title="+encodeURI(title)+"&description="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��'+url)+"&srcUrl="+encodeURI(url);
        } else if(ac=='share_kaixin'){
               var url="http://www.kaixin001.com/rest/records.php?url="+encodeURI(url)+"&style=11&content="+encodeURI('����һ�׺��������֣�--��'+title+'�����ŵ�ַ��')+"&stime=&sig=";
	};
	window.open(url,'share');
}

