function Showhtml(){
       if(window.ActiveXObject || window.ActiveXObject !== undefined)
            player=PlayerIe();
       else
            player=PlayerNt();
       if(player==''){
		parent.document.getElementById('cscms_vodplay').src='http://error.xfplay.com/error.htm';
       }else{
            document.getElementById('playlist').innerHTML = player;
	        setInterval('vodstatus()','1000');
       }
}

function PlayerNt(){
       var player='';
	   if (navigator.plugins) {
                var Install = false;
				for (i=0; i < navigator.plugins.length; i++ ) 
				{
					var n = navigator.plugins[i].name;
					if( navigator.plugins[n][0]['type'] == 'application/xfplay-plugin')
					{
						Install = true; break;
					}		
				} 

		        if(Install){
			        var player = '<div style="width:100%; height:'+height+'px;overflow:hidden;position:relative"><embed type="application/xfplay-plugin" PARAM_URL="'+unescape(url)+'" PARAM_Status="1" width="'+width+'" height="'+height+'" wmode="opaque" id="Xfplay" name="Xfplay"></embed></div>';
		        }
	    }
        return player;
}

function PlayerIe(){
         player  = "<div style='position:relative'>";
         player += '<object id="Xfplay" name="Xfplay" width="100%" height="'+height+'" onerror="yyxfdown();" classid="clsid:E38F2429-07FE-464A-9DF6-C14EF88117DD" >';
         player += '<PARAM name="URL" value="'+unescape(url)+'">';
         player += "<param name='wmode' value='opaque'>";
         player += '<PARAM name="Status" value="1"></object></div>';
         return player;
}

function vodstatus(){
    if(document.getElementById('Xfplay').PlayState==12){
	if(xurl!=''){
		parent.window.document.location.href=xurl;
	}
    }
}

function yyxfdown(){
	parent.document.getElementById('cscms_vodplay').src='http://error.xfplay.com/error.htm';
}

if(parent.cs_adloadtime){
	setTimeout("Showhtml();",parent.cs_adloadtime*1000);
}
