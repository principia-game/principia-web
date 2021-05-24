#!/bin/bash

inotifywait -q -m -e close_write assets/css/style.scss |
while read -r filename event; do
	echo Stylesheet updated, compiling...
	bash tools/compile-scss-sassc.sh
done