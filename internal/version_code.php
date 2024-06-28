<?php
internalAuth();

$latestnews = News::getLatestArticle();

echo "36:Latest news article: ".$latestnews['title'];
