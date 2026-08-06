<?php
require('lib/common.php');
foreach (glob("lib/wiki/*.php") as $filename)
	require($filename);

$pagecontent = getPageContent();

$buffer = ["strict digraph {\n"];

// Iterate over page contents, get a list of linked pages.
$linkedpages = [];
foreach ($pagecontent as $pagename => $content) {
	//if (in_array($pagename, ['Objects', 'Objects (by ID)']))
	//	continue;

	$buffer[] = sprintf(' "%s";', $pagename);
	preg_match_all('/\[\[(.*?)\]\]/', $content, $links);
	foreach ($links[1] as $link)
		$buffer[] = sprintf(' "%s" -> "%s";'.PHP_EOL, $pagename, $link);
}

$buffer[] = "}\n";

mkdir('tools/wiki_graph');
file_put_contents('tools/wiki_graph/graph.dot', join($buffer));

system("dot -Kfdp -Tsvg tools/wiki_graph/graph.dot -o tools/wiki_graph/graph.svg");

system("neato -Goverlap=false -GK=2 -Nfontsize=8 -Tsvg tools/wiki_graph/graph.dot -o tools/wiki_graph/graph_neato.svg");
