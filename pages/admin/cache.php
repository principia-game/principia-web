<?php
if (!IS_ADMIN) error('403');

echo twigloader()->render('admin/cache.twig', [
	'info' => apcu_cache_info()
]);
