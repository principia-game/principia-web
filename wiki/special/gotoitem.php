<?php

// Special page to redirect to an item page, given the item's ID.

// item id
if (isset($_GET['id']))
	$id = (int)$_GET['id'];
else
	die("<p>This is an internal special page for use by the Principia client.</p>"
	   ."<p>Usage: /wiki/Special:GotoItem?id=&lt;g_id&gt;</p>"
	   ."<p>It will redirect to the specified item ID's Wiki page.</p>");

$pagename = match ($id) {
	0 => "Arm_Cannon",
	1 => "Builder",
	2 => "Shotgun",
	3 => "Railgun",
	4 => "Oil_Barrel",
	5 => "Speed_oil",
	6 => "Jump_oil",
	7 => "Armour_oil",
	8 => "Zapper",
	9 => "Miner_upgrade",
	10 => "Rocket_Launcher",
	11 => "Somersault_Circuit",
	12 => "Jetpack",
	13 => "Upgraded_Jetpack",
	14 => "Advanced_Jetpack",
	15 => "Bomb_Launcher",
	16 => "Robot_head",
	17 => "Cow_head",
	18 => "Pig_head",
	19 => "Robot_front",
	20 => "Teslagun",
	21 => "Plasma_Gun",
	22 => "Arm_Cannon_bullet",
	23 => "Shotgun_pellet",
	24 => "Plasma_Gun_plasma",
	25 => "Rocket_Launcher_rocket",
	26 => "Heisenberg's_Hat",
	27 => "Mega_Buster",
	28 => "Mega_Buster_Solar_Bullet",
	29 => "Feet",
	30 => "Miniwheels",
	31 => "Monowheel",
	32 => "Quadruped",
	33 => "Ninja_Helmet",
	34 => "Black_robot_front",
	35 => "Riding_Circuit",
	36 => "Faction_Wand",
	37 => "Wizard_Hat",
	38 => "Robot_back",
	39 => "Uncovered_robot_head",
	40 => "Wood_Bolt_Set",
	41 => "Steel_Bolt_Set",
	42 => "Sapphire_Bolt_Set",
	43 => "Diamond_Bolt_Set",
	44 => "Conical_Hat",
	45 => "Ostrich_head",
	46 => "Circuit_of_Regeneration",
	47 => "Zombie_Circuit",
	48 => "Police_Hat",
	49 => "Black_robot_back",
	50 => "Top_Hat",
	51 => "Compressor",
	52 => "King's_crown",
	53 => "Dummy_head",
	54 => "Jester_hat",
	55 => "Training_sword",
	56 => "Witch_Hat",
	57 => "War_hammer",
	58 => "Simple_axe",
	59 => "Chainsaw",
	60 => "Spiked_Club",
	61 => "Steel_Sword",
	62 => "Baseball_bat",
	63 => "Spear",
	64 => "War_axe",
	65 => "Pixel_sword",
	66 => "Hard_hat",
	67 => "Serpent_Sword",
	68 => "Pioneer_front",
	69 => "Pioneer_back",
	70 => "Viking_Helmet",
	71 => "Pickaxe",
	default => null
};

if ($pagename)
	redirect(sprintf("/wiki/%s", $pagename));
else
	error('404', 'Invalid or nonexistant item ID. (item id: '.$id.')');
