<?php
require('lib/common.php');

$twig = twigloader();
echo $twig->render('404.twig');