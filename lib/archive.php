<?php

function maybeArchive($path) {
	return (IS_ARCHIVE ? 'archive/' : '') . $path;
}

function mainSite($path) {
	return IS_ARCHIVE ? "https://principia-web.se".$path : $path;
}
