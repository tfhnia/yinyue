<!DOCTYPE html>
<html lang="zh">
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<meta name='viewport' content='width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no'>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo Web_Path;?>packs/images/msg.css"/>
<title>���ݿ���� <?php echo Web_Url;?></title>
</head>
<body class="msg_txt">
	<div id="container">
		<h1>Database Error��</h1>
		<div class="box"><span class="code"><b class="f14"><?php echo $heading; ?></b></span></div>
		<div class="box"><span class="code"><b class="f14">���󱨸棺</b><font><p><?php echo str_replace(FCPATH,Web_Path,$message); ?></p></font></span></div>
		<div class="box"><span class="code"><b class="f14">���������</b><font><p>�����Ե���������ӷ�����ҳ���߼��������һҳ�棺</p><p><a href='<?php echo Web_Path;?>'>������ҳ (<?php echo Web_Url;?>)</a></p><p><a href='javascript:history.back(-1)'>������һҳ</a></p></font></span></div>
	</div>
</body>
</html>

