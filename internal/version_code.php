<?php
internalAuth();

$latestnews = News::getLatestArticle();

echo "35:Latest news article: ".$latestnews['title'];
