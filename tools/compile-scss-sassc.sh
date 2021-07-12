#!/bin/sh

common_arguments="--style compressed --load-path ./"

sassc ${common_arguments} assets/scss/style.scss assets/css/style.css
sassc ${common_arguments} assets/scss/darkmode.scss assets/css/darkmode.css
