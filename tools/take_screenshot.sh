#!/bin/bash

LEVEL=$1

DATADIR=/srv/principia-web/data/

docker cp ${DATADIR}/levels/${LEVEL}.plvl ss:/principia/storage/lvl/db/${LEVEL}.plvl

docker exec ss /principia/principia principia://play/lvl/db/${LEVEL}

OUTPUT=0

while [ "$OUTPUT" -eq 0 ]; do
    OUTPUT=$(docker exec ss sh -c "[ -e '/principia/ss-0.png' ] && echo 1 || echo 0")

    echo "File existence check result: $OUTPUT"

    sleep 0.5
done

SCREENSHOT=${DATADIR}/thumbs_src/${LEVEL}.png

docker cp ss:/principia/ss-0.png ${SCREENSHOT}
docker exec ss rm /principia/ss-0.png

convert ${SCREENSHOT} -quality 85 ${DATADIR}/thumbs/${LEVEL}.jpg
convert ${SCREENSHOT} -resize 240 -unsharp 0x0.55+0.55+0.008 -quality 92 ${DATADIR}thumbs_low/${LEVEL}.jpg

chmod 666 ${DATADIR}/thumbs/${LEVEL}.jpg
chmod 666 ${DATADIR}thumbs_low/${LEVEL}.jpg
