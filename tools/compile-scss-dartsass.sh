#!/bin/sh

sass --style=compressed --no-source-map \
	--silence-deprecation color-functions \
	--silence-deprecation global-builtin \
	--silence-deprecation import \
	scss/adminer.scss:static/css/adminer.css \
	scss/dark.scss:static/css/dark.css \
	scss/fonts.scss:static/css/fonts.css \
	scss/light.scss:static/css/light.css
