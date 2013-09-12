#!/bin/bash
DIR="$(dirname $0)"
$DIR/dylibbundler -d $DIR/../usr/lib -p @executable_path/../usr/lib -b -of -x $DIR/php
