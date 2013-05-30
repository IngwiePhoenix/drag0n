#!/bin/bash
# 05 installer.fnc

db_read() { . $dbDIR/$1; }
db_write() { . $1; mv $1 $dbDIR/$ID; }
db_sync() {
	echo "" >$instLIST
	for db in $(find $dbDIR/); do
		. $db
		echo "$ID" >$instLIST
	done
}		

install_d0p() {
	log "install_d0p: $1"
	BNAME=$(basename $1)
	mkdir -p $tmpDIR/$BNAME
	mv $1 $tmpDIR/
	getPackData $1 $BNAME # scope the package data
	getPackScripts $1 $BNAME # using basename as arg
	$tmpDIR/$BNAME/beforeInstall
	prepairPackFiles $BNAME
#	movePackFiles $BNAME
	$tmpDIR/$BNAME/afterInstall
#	killTMP
}