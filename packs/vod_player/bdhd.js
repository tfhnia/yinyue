
var BdPlayer = new Array();
function $Showhtml(){


    document.getElementById('playad').style.display = "none";

BdPlayer['time'] = 8;//������չʾʱ��(�����Ϊ0,����ݻ�������Զ����ƹ��չʾʱ��)

BdPlayer['buffer'] = 'http://player.baidu.com/lib/show.html?buffer';//��Ƭ�����ҳ��ַ

BdPlayer['pause'] = 'http://player.baidu.com/lib/show.html?pause';//��ͣ�����ҳ��ַ

BdPlayer['end'] = 'http://player.baidu.com/lib/show.html?end';//ӰƬ������ɺ���صĹ��

BdPlayer['tn'] = '12345678';//���������ص�ַ������

BdPlayer['width'] = '+width+';//���������(ֻ��Ϊ����)

BdPlayer['height'] = '+height+';//�������߶�(ֻ��Ϊ����)

BdPlayer['showclient'] = 1;//�Ƿ���ʾ�������̰�ť(1Ϊ��ʾ 0Ϊ����)

BdPlayer['url'] = ''+url+'';//��ǰ�������񲥷ŵ�ַ

BdPlayer['nextcacheurl'] = '';//��һ�����ŵ�ַ(û��������)

BdPlayer['lastwebpage'] = ''+surl+'';//��һ����ҳ��ַ(û��������)

BdPlayer['nextwebpage'] = ''+xurl+'';//��һ����ҳ��ַ(û��������)

document.write('<script language="javascript" src="http://php.player.baidu.com/bdplayer/player.js" charset=gbk></scr'+'ipt>');

    //document.getElementById('playlist').innerHTML = player;
}

if(parent.cs_adloadtime){
	setTimeout("$Showhtml();",parent.cs_adloadtime*1000);
}
	

