<?php

$latestnews = News::getLatestArticle();

redirect('/news/'.$latestnews['id']);
