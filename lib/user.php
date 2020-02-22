<?php

function userlink($user) {
	return <<<HTML
		<a href="user.php?id={$user['id']}"><span class="t_user">{$user['name']}</span></a>
	HTML;
}
