#!/bin/sh

common_arguments="--style compressed --load-path ./"

mkdir -p static/css

sassc ${common_arguments} scss/adminer.scss static/css/adminer.css
sassc ${common_arguments} scss/dark.scss static/css/dark.css
sassc ${common_arguments} scss/fonts.scss static/css/fonts.css
sassc ${common_arguments} scss/light.scss static/css/light.css
