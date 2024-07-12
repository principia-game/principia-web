<?php
internalAuth();

$latestnews = News::getLatestArticle();

echo "37:Latest news article: ".$latestnews['title'];
