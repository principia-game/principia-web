#!/bin/sh

for file in *.jpg; do convert $file -resize 240 -unsharp 0x0.55+0.55+0.008 -quality 80% low/$file; done