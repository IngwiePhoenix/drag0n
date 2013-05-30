#!/bin/bash
# 03-updater.fnc

needUpdate() {
	if [ $CurrentVersion != $VERSION ]; then
		return TRUE;
	else
		return FALSE;
	fi
}

getUpdateInfo() {
	TMP=$(mktemp)
	curl $upd >$TMP
	chmod +x $TMP
	. $TMP
	rm $TMP
}

getUpdate() {
	getUpdateInfo
	wget --output-document=$updDIR/$FileName $upd/$FileName
	install_update $updDIR/$FileName
}