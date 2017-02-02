<?php require_once '../../../../lib/Cs_Config.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>DJ播放器</title>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<style type="text/css"> 
html, body {margin:0; padding:0;background: #333333;}
</style>
<script type="text/javascript">var cscms_path="<?=Web_Path?>";</script>
<script type="text/javascript" src="<?=Web_Path?>packs/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=Web_Path?>packs/js/cscms.js"></script>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'].Web_Path?>index.php/real/hei"></script>
<script type="text/javascript" src="mplist.js"></script>
</head>
<body>
<script language="JavaScript" type="text/javascript">
create();
SetCookie("open_player", "", null, "/", "<?php echo Web_Url?>", false);
function get_cookie(_name){
	var Res=eval('/'+_name+'=([^;]+)/').exec(document.cookie); return Res==null?'':unescape(Res[1]);
}
function chk_open() {
    if( 'Y' == get_cookie('open_player') )
    {
		cmpo.sendEvent("view_stop");
    }else {
        setTimeout('chk_open()', 3000);
    }
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
chk_open();
</script>
</body>
</html>

