var version = 1.0;
var margin = 20;
var sizeW = 60;
var sizeH = 62;
var siteName = 'Divine Gate 資料庫';
var siteUrl = 'http://'+window.location.hostname + window.location.pathname;
var path = siteUrl.split("/");
var cname = path[path.length-1];
var imageUrl = 'http://localhost/dgdb/img/'+image;

var list = [];
var clicked = [];

var UnitList = function(param){
	var buf = param.split(',');
	this.elem   = buf[1];
	this.own    = false;
}

function getUnit(cate, elem){
	return list[category[numbers[cate]]*element.NUM+elem];
}

function initUnit(){
	for(var i=0; i<category.NUM; i++){
		if(unit[numbers[i]]==undefined) continue;
		for(var l=0; l<unit[numbers[i]].length; l++){
			if(unit[numbers[i]][l]==undefined) continue;
			var buf = unit[numbers[i]][l].split(',');
			list[category[numbers[i]]*element.NUM+(buf[1]-0)] = new UnitList(unit[numbers[i]][l]);
		}
	}
}

function imageLoad(imgsrc){
	var img = new Image();
	img.src = imgsrc;
	return img;
}

function initCanvas(){
	var canvas = $('#list');
	$('#list,#pre').attr({width:element.NUM*sizeW+sizeW+margin*2, height:category.NUM*sizeH+margin*2+20});
	canvas.click(canvasClick);
}

function update(){
	var canvas = $('#list'), precan = $('#pre');
	var ctx = $('canvas')[0].getContext('2d'), c2=$('canvas')[1].getContext('2d');
	var cw = element.NUM*sizeW+sizeW+margin*2, ch = category.NUM*sizeH+margin*2+20;
	c2.clearRect(0, 0, cw, ch);
	c2.beginPath();
	c2.rect(0, 0, cw, ch);
	c2.fillStyle = "rgb(52,52,52)";
	c2.fill();
	var img = imageLoad(imageUrl);
	img.onload = function(){
		c2.drawImage(img, 0, 0);

		for(var i=0; i<category.NUM; i++){
			for(var l=0; l<unit[numbers[i]].length; l++){
				var u = getUnit(i,l);
				if(u){
					if(!u.own){
						c2.beginPath();
						c2.rect(l*sizeW+sizeW+margin, i*sizeH+margin, sizeW, sizeH);
						c2.fillStyle = "rgba(52,52,52, 0.6)";
						c2.fill();
					}
				}
			}
		}
		var prelenda = c2.getImageData(0,0,cw, ch);
		ctx.putImageData(prelenda, 0, 0);
	}
}

// カテゴリ内のユニット数を数える(期間限定用)
function unilen(u){
	var cnt = $.grep(u, function(e){ return e !== (undefined||'');});
	return cnt.length;
}

function contains(array, row){
    for (var i = 0; i < array.length; i++) {
        if(array[i].indexOf(row) > -1)
            return array[i];
    }
}

function canvasClick(e){
	var rect = e.target.getBoundingClientRect();
	var x = e.clientX - (rect.left+margin) ;
	var y = e.clientY - (rect.top+margin)+2;
 	var elem = Math.floor((x/sizeW)-1);
	var cate = Math.floor((y/sizeH));

	updatePosition(cate,elem);
	update();
}

function updatePosition(cate,elem){
	if(elem<0){ // row category clicked
		var have = 0;
		var have2 = 0;
		if(contains(cateLine, cate) != undefined){ // Category more than on line
            var tempArray = contains(cateLine, cate);
            var cnt = 0;
            for(var i=0; i < tempArray.length; i++){
                cnt += unilen(unit[numbers[tempArray[i]]]);
            }
            for(var i=0; i < tempArray.length; i++){
                for(var j=0; j<6; j++){
                    var u = getUnit(tempArray[i], j);
                    if(u){ if(u.own) { have++; } else { continue; } }
                }
            }

            for(var i=0; i<tempArray.length; i++){
				for(var j=0; j<element.NUM; j++){
					var u = getUnit(tempArray[i], j);
					if(u != undefined){
	                    if(have == cnt){
						   if(u) u.own = false; // ALL CLEAR
	                    } else {
	                        if(u) u.own = true; // SELECT ALL
	                    }
					   updateUrl(tempArray[i],j,u.own);
				   }
				}
            }

		} else  { // single row
			var cnt = unilen(unit[numbers[cate]]);
			for(var i=0; i<element.NUM; i++){
				var u = getUnit(cate, i);
				if(u){ if(u.own) { have++; } else { continue; } }
			}

			for(var i=0; i<element.NUM; i++){
				var u = getUnit(cate, i);
				if(u != undefined){
					if(have == cnt){
					   if(u) u.own = false; // ALL CLEAR
					} else {
						if(u) u.own = true; // SELECT ALL
					}
					updateUrl(cate,i,u.own);
				}
			}
		}
	} else { // change select state
		var u = getUnit(cate, elem);
		if(u) u.own = !u.own;
		updateUrl(cate,elem,u.own);
	}
}

function utf8_to_b64(str) { return window.btoa(unescape(encodeURIComponent(str))); }
function b64_to_utf8(str) { return decodeURIComponent(escape(window.atob(str))); }

function updateUrl(cate,elem,add){
	if(add){
		if(clicked.indexOf(cate+'|'+elem) == -1)
			clicked.push(cate+'|'+elem);
	} else {
		clicked.splice(clicked.indexOf(cate+'|'+elem),1);
	}
	var baseUrl = 'http://'+window.location.hostname + window.location.pathname;
	if(clicked.length > 0)
		$('#url').text(baseUrl + '#' + utf8_to_b64(JSON.stringify(clicked)));
	else
		$('#url').text(baseUrl);
	// save to cookie
	var d = new Date();
	d.setTime(d.getTime() + (180 * 24 * 60 * 60 * 1000));	// 180 days
	document.cookie = cname+"_sheet="+utf8_to_b64(JSON.stringify(clicked))+"; expire="+d.toGMTString()+";";
}

function updateFromHash(hash){
	fromHash = JSON.parse(b64_to_utf8(hash));
	for(var i = 0; i < fromHash.length; i++){
		var arr = fromHash[i].toString().split("|");
		updatePosition(parseInt(arr[0]),parseInt(arr[1]));
	}
}

function today(){
//	return (new Date()).toLocaleString().split(' ')[0];
	var y,m,d, now = new Date();
	y = now.getFullYear();
	m = now.getMonth()+1;
	d = now.getDate();
	return y+'/'+m+'/'+d;
}

$(function(){
	var html = '', buf=0,mx,my;

	initUnit();
	initCanvas();
	update();
	$('#url').text('http://'+window.location.hostname + window.location.pathname);

	$('.allon').click(function(){
			for(var i=0; i<element.NUM; i++){
				for(var l=0; l<numbers.length; l++){
					var u = getUnit(l, i);
					if(u) {
						u.own = true;
						updateUrl(l,i,u.own);
					}
				}
			}
		update();
		return false;
	});

	$('.alloff').click(function(){
			for(var i=0; i<element.NUM; i++){
				for(var l=0; l<numbers.length; l++){
					var u = getUnit(l, i);
					if(u) {
						u.own = false;
						updateUrl(l,i,u.own);
					}
				}
			}
		update();
		return false;
	});

	$('.imageput').click(function(){
		try {
			var img = new Image();
			var canvas = $('canvas')[0], can2 = $('canvas')[1];
			var ctx = $('canvas')[0].getContext('2d');
			var c2 = can2.getContext('2d');
			var cw = canvas.width, ch = canvas.height;

			c2.beginPath();
			c2.font = "10px 'arial'";
			c2.fillStyle = '#dddddd';
			c2.textBaseline  = 'bottom';
			c2.textAlign = 'left';
			c2.fillText(today(), 12, ch-12);
			c2.fillStyle = '#dddddd';
			c2.textAlign = 'right';
			c2.fillText(siteName+" - "+siteUrl, cw-12, ch-12);
			c2.restore();

			img.src = can2.toDataURL('image/png');
			window.open(img.src, '_blank');
		} catch(e) {
			alert(e);
		}
		return false;
	});

    return false;
});
