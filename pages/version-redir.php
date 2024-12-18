<?php

$latestnews = News::getLatestArticle();

redirect('/news/%d', $latestnews['id']);
