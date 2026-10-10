<?php

const ALL_BADGES = [
	1 => [
		'name' => 'project_maintainer',
		'title' => 'Project Maintainer',
		'description' => 'The project maintainer of Principia.'
	],
	2 => [
		'name' => 'gold_trophy',
		'title' => 'Golden Contest Trophy',
		'description' => 'Finished first place in a Principia building contest.'
	],
	3 => [
		'name' => 'silver_trophy',
		'title' => 'Silver Contest Trophy',
		'description' => 'Finished second place in a Principia building contest.'
	],
	4 => [
		'name' => 'highlighted_trophy',
		'title' => 'Highlighted Contest Trophy',
		'description' => 'Level highlighted in a Principia building contest.'
	],
	5 => [
		'name' => 'moderator',
		'title' => 'Moderator',
		'description' => 'This user is an official moderator on the website.'
	],
	6 => [
		'name' => 'code_contributor',
		'title' => 'Code Contributor',
		'description' => 'Has made code contributions to Principia\'s source repositories.'
	],
	7 => [
		'name' => 'wiki_editor',
		'title' => 'Wiki Editor',
		'description' => 'Has made changes to the Principia Wiki.'
	],
	8 => [
		'name' => 'anniversary_five',
		'title' => '5 Years',
		'description' => 'Has been registered on the current iteration of the community site for 5 years.',
		'hidden' => true
	],
	9 => [
		'name' => 'anniversary_ten',
		'title' => '10 Years',
		'description' => 'Has been registered on the current iteration of the community site for 10 years.',
		'hidden' => true
	],
	10 => [
		'name' => 'met_rollerozxa',
		'title' => 'Met ROllerozxa',
		'description' => 'This user has met ROllerozxa in person.'
	],
	11 => [
		'name' => 'donator',
		'title' => 'Donator',
		'description' => 'Has donated €5 or more to ROllerozxa.'
	],
	12 => [
		'name' => 'influencer',
		'title' => 'Influencer',
		'description' => 'Have a level be featured on the Principia YouTube/PeerTube channel.'
	],
	13 => [
		'name' => 'featured_level',
		'title' => 'Featured Level',
		'description' => 'Has had a level featured on the front page.'
	],
	14 => [
		'name' => 'well_liked',
		'title' => 'Well Liked',
		'description' => 'Has a level with at least 20 likes.'
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
