#!/bin/bash
# 09 os.auto
os_detect() {
	OS="$(uname)"
	case $OS in
		"Darwin") return MAC;;
		"CYGWIN_NT-5.1") return CYGWIN;;
		"Linux") return LINUX;;
	esac
}

mac_infobox() { $CCD ok-msgbox --no-cancel --title "$1" --text "$2" --informative-text "$3" $4; }
mac_msgbox() { $CCD textbox --title "$1" --informative-text "$2" $3; }
mac_note() { $CCD bubble --timeout $DRAG0N_note_timeout --x-placement top --y-placement right \
             --text-color "ffffff" --border-color "000000" --background‑top "0B20A5" background‑bottom "03092D" \
             --title "$1" --text "$2"; }
mac_input() { $CCD standard-inputbox --no-cancel --title "$1" --informative-text "$2" $3; }