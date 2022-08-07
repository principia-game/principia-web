<?php
chdir('../');
require('lib/common.php');

internalAuth();

echo readfile('featured/fl.cache');
