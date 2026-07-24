<?php

if (androidWebviewVersion())
	error(400, "You're already playing Principia!!!");

header("Cross-Origin-Resource-Policy: same-origin");
header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Opener-Policy: same-origin");

twigloader()->display("play.twig");
