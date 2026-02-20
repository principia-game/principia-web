<?php

const ALL_BADGES = [
	1 => [
		'name' => 'project_maintainer',
		'title' => 'Project Maintainer',
		'description' => 'The project maintainer of Principia.'
	],
	[
		'name' => 'gold_trophy',
		'title' => 'Golden Contest Trophy',
		'description' => 'Finished first place in a Principia building contest.'
	],
	[
		'name' => 'silver_trophy',
		'title' => 'Silver Contest Trophy',
		'description' => 'Finished second place in a Principia building contest.'
	],
	[
		'name' => 'highlighted_trophy',
		'title' => 'Highlighted Contest Trophy',
		'description' => 'Level highlighted in a Principia building contest.'
	],
	[
		'name' => 'moderator',
		'title' => 'Moderator',
		'description' => 'This user is an official moderator on the website.'
	],
	[
		'name' => 'code_contributor',
		'title' => 'Code Contributor',
		'description' => 'Has made code contributions to Principia\'s source repositories.'
	],
	[
		'name' => 'wiki_editor',
		'title' => 'Wiki Editor',
		'description' => 'Has made changes to the Principia Wiki.'
	],
	[
		'name' => 'anniversary_five',
		'title' => '5 Years',
		'description' => 'Has been registered on the current iteration of the community site for 5 years.'
	],
	[
		'name' => 'anniversary_ten',
		'title' => '10 Years',
		'description' => 'Has been registered on the current iteration of the community site for 10 years.'
	],
	[
		'name' => 'met_rollerozxa',
		'title' => 'Met ROllerozxa',
		'description' => 'This user has met ROllerozxa in person.'
	],
	[
		'name' => 'donator',
		'title' => 'Donator',
		'description' => 'Has donated €5 or more to ROllerozxa.'
	],
	[
		'name' => 'influencer',
		'title' => 'Influencer',
		'description' => 'Have a level be featured on the Principia YouTube/PeerTube channel.'
	],
	[
		'name' => 'featured_level',
		'title' => 'Featured Level',
		'description' => 'Has had a level featured on the front page.'
	]
];

function getUserBadges($id, $limit = -1) {
	$limitClause = $limit >= 0 ? "LIMIT $limit" : "";
	$queryy = query("SELECT ub.badge, ub.comment FROM user_badges ub WHERE ub.user = ? $limitClause", [$id]);

	$out = [];
	foreach ($queryy as $record)
		$out[] = array_merge(ALL_BADGES[$record['badge']], $record);

	return $out;
	/*$limitClause = $limit >= 0 ? "LIMIT $limit" : "";
	return query("SELECT b.id, b.name, b.title, b.description, ub.comment
			FROM badges b JOIN user_badges ub ON ub.badge = b.id
			WHERE ub.user = ? $limitClause",
		[$id]);*/
}
