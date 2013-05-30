#!/bin/bash
# 10-log.auto

logWrite() { echo "$1" >>$logDIR/$2.log; echo "$1" >>$logDIR/All.log; }
log_debug() { logWrite "[$(date +%d.%m.%Y\|%H:%M:%S)] [>Debug] $1" "Debug"; }
log_error() { logWrite "[$(date +%d.%m.%Y\|%H:%M:%S)] [ERROR:] $1" "Error"; }
log_debug() { logWrite "[$(date +%d.%m.%Y\|%H:%M:%S)] [Info] $1" "Info"; }
log() { logWrite "[$(date +%d.%m.%Y\|%H:%M:%S)] $1" "Normal"; }

if [ ! -d $logDIR ]; then
	mkdir $logDIR
	log "Made $logDIR"
fi