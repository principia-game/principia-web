<?php
require('lib/common.php');

pageheader();

$twig = twigloader();
echo $twig->render('404.php');

pagefooter();