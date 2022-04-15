#!/bin/sh
# Start a gource visualisation with the commit history of principia-web and the forums combined.

gource --output-custom-log /tmp/gource_principia-web.log .
gource --output-custom-log /tmp/gource_principia-web-forum.log forum/
sed -i -r "s#(.+)\|#\1|/forum#" /tmp/gource_principia-web-forum.log

cat /tmp/gource_principia-web.log /tmp/gource_principia-web-forum.log | sort -n | gource --key -1280x720 $@
