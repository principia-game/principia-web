<?php

if (isset($path[2])) {
	require('pages/paste/get.php');
} else {
	require('pages/paste/upload.php');
}
