<?php

function deleteLevelThumbs($lid) {
	unlink("data/thumbs/$lid.jpg");
	unlink("data/thumbs_low/$lid.jpg");
}
