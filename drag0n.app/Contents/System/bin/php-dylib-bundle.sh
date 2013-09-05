#!/bin/bash
DIR="$(dirname $0)"
$DIR/dylibbundler -p $DIR/../usr/lib -d ../usr/lib -b -x $DIR/php
