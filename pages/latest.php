<?php
$type = (isset($path[2]) && typeToCat($path[2]) ? $path[2] : 'all');
redirectPerma('/levels'.(IS_ARCHIVE ? '?archive=1&type=' . $type : '?type=' . $type));
