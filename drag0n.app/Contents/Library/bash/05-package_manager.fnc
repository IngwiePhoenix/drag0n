#!/bin/bash
# 03 package manager

getPackData() {
	undrp $1 -C $tmpDIR/$2 DRAG0N/info.d0
	chmod +x $tmpDIR/$1/info.d0
	. $tmpDIR/$1/info.d0
	log "getPackData: $tmpDIR/$1/info.d0"
}

getPackScripts() {
	undrp $1 -C $tmpDIR/$2/ DRAG0N/beforeInstall DRAG0N/duringInstall DRAG0N/beforeRemove DRAG0N/afterRemove
	chmod -R +x $tmpDIR/$1/DRAG0N
	FILES=""
	for f in $(find $tmpDIR/$1/); do
		$FILES="$FILES $f"
	done
	log "getPackScripts: Found $FILES"
}

prepairPackFiles() {
	mkdir -p $tmpDIR/$1-FILES
	undrp $1 -C $tmpDIR/$1-FILES
	rm -Rf $tmpDIR/$1-FILES/DRAG0N
	log "prepairPackFiles: $tmpDIR/$1-FILES"
}

movePackData() {
	for file in $(mv -Rv $tmpDIR/$1-FILES $DESTINATION); do
		log "movePackData: mv: $file"
	done
}