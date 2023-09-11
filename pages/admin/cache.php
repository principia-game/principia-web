<?php
echo twigloader()->render('admin/cache.twig', [
	'info' => apcu_cache_info()
]);
