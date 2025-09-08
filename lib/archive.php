<?php

function maybeArchive($path) {
	return (IS_ARCHIVE ? 'archive/' : '') . $path;
}
