function show(page) {
	$.get('pages/'+page,function(d){ $('#MAINCONTENT').html(d); });
}

function alert(title,msg,infomsg) {
	bash=d0.d0+' --exec-fnc mac_infobox "'+title+'" "'+msg+'" "'+infomsg+'"'
	console.log(bash);
	exec(bash,function(e,stdout,stderr) {console.log(stdout);console.log(stderr);console.log(e);});
}