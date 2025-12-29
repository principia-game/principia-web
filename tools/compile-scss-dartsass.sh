#!/bin/sh

sass --style=compressed --no-source-map --watch \
	scss/adminer.scss:static/css/adminer.css \
	scss/dark.scss:static/css/dark.css \
	scss/fonts.scss:static/css/fonts.css \
	scss/light.scss:static/css/light.css
