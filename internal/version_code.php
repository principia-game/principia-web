<?php
internalAuth();

$latestnews = News::getLatestArticle();

echo "39:Latest news article: ".$latestnews['title'];
