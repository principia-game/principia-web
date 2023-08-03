#!/bin/bash

for file in data/thumbs/*.jpg; do
	convert $file -resize 240 -unsharp 0x0.55+0.55+0.008 -quality 92 data/thumbs_low/${file/data\/thumbs\//}
done
