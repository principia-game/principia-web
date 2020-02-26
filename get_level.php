<?php

if(!isset($_GET["i"]))
	return;

echo readfile("levels/".strval(intval($_GET["i"])).".plvl");