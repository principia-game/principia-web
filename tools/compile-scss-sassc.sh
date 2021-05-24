#!/bin/sh

# "Compressed" style gives lowest filesize
# Load path is assuming you're running this script from the root of the principia-web directory
common_arguments="--style compressed --load-path ./"

sassc ${common_arguments} assets/css/style.scss assets/css/style.css