<?php
require('lib/common.php');

needsLogin();

echo twigloader()->render('userexport.twig');
