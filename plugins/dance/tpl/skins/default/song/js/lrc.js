var $song_Lrc = new Array();
var $song_Lrci = new Array();
var playlrcid;
var lrcjz=lrcs=0;
function Playergeci() {
	var p = 0;
	var t;
	var song_u = new Array();
	var rnd_id = 0;
	var song_u_1;
	var total = 0;
	var lrctimea = 8888888;
	var Stat_drag = 0;
	var Stat_Time = 0;
	var Stat_inn = '-1';
	var Stat_num = '';
	var utils_s = 0;
	var stnum = 1190351137140;
	var stnumk = 0;
	var picisload = 0;
	this.downloadlrc = function(t, lrcgc) {
		var tfolder = "";
		var fdata = t / 10000 + 1;
		fdata = fdata.toString();
		tfolder = fdata;
		if (fdata.indexOf(".") != -1) {
			tfolder = fdata.split(".")[0]
		}
		if (!$song_Lrc[t]) {
			this.led('', '', '', '����������...', '', '', '');
			$song_Lrc[0] = lrcgc
		}
		lrctimea = 8888888
	};
	this.led = function(s1, s2, s3, s4, s5, s6, s7) {

	};
	this.doSp = function(oT) {
		if (parseInt(oT) > 1) {
			var zongsj = $(".js-play-time-total").html();
			var b1 = zongsj.split(":");
			var b2 = parseInt(b1[0]);
			var b3 = parseInt(b1[1]);
			var b4 = b2 * 60 + b3;
			var rat = 285 / b4;
			$("#player").jPlayer("playHead", 100 * (oT / 285 * rat))
		}
	};
	this.lrci = function(xi, i) {
		/*if (_l[i]) {
                        var html='<li id="lrcs_'+_t[i]+'" style="CURSOR: pointer" class="" onclick="pu.doSp(\'' + (_t[i] / 1000) + '\')" >' + _l[i] + '</li>';
                        $('#lyric').append(html);
                        lrcs=_t[i];
		}*/
		if (_l[i]) {
			document.getElementById(xi).innerHTML = '<a style="CURSOR: pointer" onclick="pu.doSp(\'' + (_t[i] / 1000) + '\')" >' + _l[i] + '</a>'
		} else {
			document.getElementById(xi).innerHTML = ''
		}
	};
	this.PlayLrc = function() {
		var t = 0;
		var nolrc = false;
		if ($song_Lrc[t]) {
			try {
				var bfqtime = $(".js-play-time-now").html();
				var b1 = bfqtime.split(":");
				var b2 = parseInt(b1[0]);
				var b3 = parseInt(b1[1]);
				var b4 = b2 * 60 + b3;
				var curTime = parseInt(b4 * 1000) + 500
			} catch(e) {
				var curTime = 0
			}
			if (Stat_drag == 1 || curTime - 1000 > Stat_Time || Stat_Time > curTime) {
				Stat_drag = 1
			} else {
				Stat_drag = 0
			}
			Stat_Time = curTime;
			if ($song_Lrc[t] == '0') {
				if (8888888 == lrctimea) {
					//this.led('', '', 'û�ҵ���ظ��','','','')
                                        $('.js-lrc-load').hide();
                                        $('.js-lrc-no').show();
				}
				lrctimea = 0;
				nolrc = false
			} else if ($song_Lrc[t].length > 0) {
				nolrc = false;
				try {
					$("#lyric").show();
                                        $('.js-lrc-load').hide();
				} catch(e) {
					return (false)
				}
				if (8888888 == lrctimea) {
					if ($song_Lrci[t]) {} else {
						var lrc1 = $song_Lrc[t].split("[");
						var array = [];
						for (var i = 0; i < lrc1.length; i++) {
							var g = {};
							var t = lrc1[i].split("]");
							g.time = jtime(t[0]);
							if (isNaN(g.time)) continue;
							g.c = t[1];
							if (g.c == "") g.c = getnextlrc(i, lrc1);
							array.push(g)
						}
						array.sort(function(x, y) {
							if (x.time > y.time) return 1;
							else if (x.time < y.time) return - 1;
							else return 0
						});
						$song_Lrci[t] = array
					}
					var tin = "";
					var tim = "";
					if ($song_Lrci[t]) {
						var array1 = $song_Lrci[t];
						for (var i = 0; i < array1.length; i++) {
							var g = array1[i];
							if (!g.c) {
								g.c = ""
							}
							tin += g.time;
							tim += g.c;
							if (i < array1.length - 1) {
								tin += ",";
								tim += "[n]"
							}
						}
					}
					var timeH = '0,0,0,0,' + tin + '8888888';
					var TxtH = '[n][n][n]֧�ֱ�վ���Ͱѱ�վ�Ƽ�������QQ����[n]' + tim + '[n]';
					_t = timeH.split(",");
					_l = TxtH.split('[n]')
				}
                                k=1;
				for (var i = 0; i < _t.length; i++) {
					/*if (lrcjz==0) {
                                              this.lrci("LR"+i, i);
					}*/
					if (_t[i] < curTime && curTime < _t[i + 1] || 8888888 == lrctimea) {
						if (lrctimea != i) {
							this.lrci("LR1", i - 3);
							this.lrci("LR2", i - 2);
							this.lrci("LR3", i - 1);
							this.lrci("LR4", i);
							this.lrci("LR5", i + 1);
							this.lrci("LR6", i + 2);
							this.lrci("LR7", i + 3);
							this.lrci("LR8", i + 4);
							this.lrci("LR9", i + 5);
							this.lrci("LR10", i + 6);
							this.lrci("LR11", i + 7);
							this.lrci("LR12", i + 8);
							this.lrci("LR13", i + 9);
							this.lrci("LR14", i + 10);
							this.lrci("LR15", i + 11);
							this.lrci("LR16", i + 12);
							this.lrci("LR17", i + 13);
							this.lrci("LR18", i + 14);
							this.lrci("LR19", i + 15);
							this.lrci("LR20", i + 16);
						}
						lrctimea = i
					}
				}
                                /*if(lrcjz==0) {
                                       $('#lyric').append('<li class="empty"></li>');
                                }
                                lrcjz=1;
                                var ids=jtime($(".js-play-time-now").html());
                                var lrcids='lrcs_'+ids;
                                if (typeof(lrcids) == "undefined" || $('#lrcs_'+lrcs).html()==""){
                                }else{
                                     $('#lrcs_'+lrcs).removeClass('curr');
                                     lrcs=ids;
                                     $('#lrcs_'+ids).addClass('curr');
                                }*/
			} else {
				if (8888888 == lrctimea) {
					this.led('', '������ʧ�ܣ�', '');
					lrctimea = 0;
					nolrc = false
				}
			}
		}
		if (nolrc) {
			clearTimeout(playlrcid)
		} else {
			playlrcid = setTimeout("pu.PlayLrc()", 200)
		}
	}
}
function getnextlrc(y, lrc) {
	var result = "";
	var i = y + 1;
	if (lrc[i]) {
		t = lrc[i].split("]");
		if (t[1] == "") result = getnextlrc(i, lrc);
		else result = t[1]
	}
	return result
};
function jtime(tn) {
	var time = 0;
	var ta = tn.split(":");
	if (ta.length < 2) return time;
	if (ta[1].indexOf(".") > 0) {
		var tb = ta[1].split(".");
		time = ta[0] * 60 * 1000 + tb[0] * 1000 + tb[1] * 10
	} else time = ta[0] * 60 * 1000 + ta[1] * 1000;
	return time
}
