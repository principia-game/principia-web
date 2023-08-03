#!/bin/sh
# Start a gource visualisation with the commit history of principia-web along with the (old) forum repo and wiki.

gource --output-custom-log /tmp/gource_principia-web.log .

# Forum repo (running from old copy of forum repo)
gource --output-custom-log /tmp/gource_principia-web-forum.log ../principia-web_misc/forum/
sed -i -r "s#(.+)\|#\1|/forum#" /tmp/gource_principia-web-forum.log

# Wiki repo
gource --output-custom-log /tmp/gource_principia-web-wiki.log wiki/
sed -i -r "s#(.+)\|#\1|/wiki#" /tmp/gource_principia-web-wiki.log

# Concatenate them together
cat /tmp/gource_principia-web{,-forum,-wiki}.log | sort -n | gource $@ \
	--log-format custom \
	--key -1280x720 \
	--file-idle-time 0 \
	--bloom-multiplier 0.5 --bloom-intensity 0.5 \
	--title "principia-web source code visualisation (2020-2022)" \
	--user-image-dir "misc/avies" \
	--caption-file "misc/gource_events.txt" --caption-size 20 \
	--seconds-per-day 0.25 --auto-skip-seconds 0.01 \
	-
