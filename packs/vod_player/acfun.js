function $Showhtml(){
    document.getElementById('playad').style.display = "none";
    player = '<iframe id="ACFlashPlayer-re" frameborder="0" allowfullscreen="" src="https://ssl.acfun.tv/block-player-homura.html?salt=167320321&amp;forFlash_=94b5132a-475e-4641-e430-05aa02b295bd,s105,ac2281783,#token=6mc6jxxmtp3hxgvi;vid='+unescape(url)+';postMessage=1;autoplay=0;fullscreen=0;from=http://www.acfun.tv;hint=С��ʿ�������Ļ���������ġ�A���ְ�ť�������õ�Ļ��С��ģʽ����ɫ��" style="height: '+height+'px; width:100%; left: 0px; top: 0px;"></iframe>';
    document.getElementById('playlist').innerHTML = player;
}
if(parent.cs_adloadtime){
	setTimeout("$Showhtml();",parent.cs_adloadtime*1000);
}



