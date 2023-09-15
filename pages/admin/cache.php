<?php
if (!IS_ADMIN) error('403');

twigloader()->display('admin/cache.twig', [
	'info' => apcu_cache_info()
]);
