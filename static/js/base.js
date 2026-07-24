
$ = function (el) {	return document.getElementById(el); }

function vote(el, id) {
	fetch("/level/" + id, {
		method: "POST",
		headers: {"Content-Type": "application/x-www-form-urlencoded"},
		body: "vote=yes"
	}).then(() => {
		el.innerHTML = 'Liked!';
		el.disabled = true;
	});
}

if (protocolButton = document.getElementById('protocol-button')) {
	let attempts = 0;

	protocolButton.addEventListener('click', () => {
		attempts++;

		if (attempts >= 3)
			document.getElementById('protocol-issues').style.display = 'block';
	});
}

// toggle visibility of element with id `id`,
// reflecting the status in id `feedback`.
function toggleVis(id, feedback) {
	// descriptive variables yes
	var x = document.getElementById(id);
	var y = document.getElementById(feedback);

	if (x.style.display == "none") {
		x.style.display = "block";
		y.innerHTML = 'Hide';
	} else {
		x.style.display = "none";
		y.innerHTML = 'Show';
	}
}


// Toggle dark mode
function toggleDarkMode() {
	let themeStylesheet = document.getElementById('style');
	let currentTheme = themeStylesheet.getAttribute('href');
	let cssver = currentTheme.split('?v=')[1];

	if (currentTheme.startsWith('/css/light.css')) {
		themeStylesheet.setAttribute('href', '/css/dark.css?v='+cssver);
		document.cookie = 'darkmode=1; max-age=31536000; path=/';
	} else {
		themeStylesheet.setAttribute('href', '/css/light.css?v='+cssver);
		document.cookie = 'darkmode=0; max-age=31536000; path=/';
	}
}

if (darkModeToggle = document.getElementById('dark-mode-toggle'))
	darkModeToggle.addEventListener('click', toggleDarkMode);


// Contest countdown
function startCountdown(elementId, deadline) {
	var countDownDate = new Date(deadline).getTime();
	var x = setInterval(function() {
		var now = new Date();
		var nowUTC = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());

		var distance = countDownDate - nowUTC;

		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		document.getElementById(elementId).innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

		if (distance < 0) {
			clearInterval(x);
			document.getElementById(elementId).innerHTML = "Contest is over!";
		}
	}, 250);
}


// Play choice dialog
let pendingPlayBtn = null;

function selectPlayMode(mode) {
	if ($('remember-choice').checked) {
		document.cookie = `play_mode=${encodeURIComponent(mode)};`
			+`max-age=${60 * 60 * 24 * 365}; path=/; SameSite=Lax`;
	}

	$("play-choice-dialog").close();

	if (mode === "native")
		window.location.href = pendingPlayBtn.dataset.playNative;
	else
		window.location.href = pendingPlayBtn.dataset.playWeb;

	pendingPlayBtn = null;
}

function playLevel(button) {
	const match = document.cookie.match(/(?:^|;\s*)play_mode=([^;]*)/);
	const mode = match ? decodeURIComponent(match[1]) : null;

	if (mode === "native")
		window.location.href = button.dataset.playNative;
	else if (mode === "web")
		window.location.href = button.dataset.playWeb;
	else {
		pendingPlayBtn = button;
		$('remember-choice').checked = true;
		$("play-choice-dialog").showModal();
	}
}

document.querySelectorAll("[data-play-button]").forEach(function (button) {
	button.addEventListener("click", function (event) {
		event.preventDefault();
		playLevel(button);
	});
});

$("play-choice-dialog")
	.querySelectorAll("[data-play-mode]")
	.forEach(function (option) {
		option.addEventListener("click", function () {
			const mode = option.dataset.playMode;
			selectPlayMode(mode);
		});
	});


// Forum thread.php code
function submitmod(act) {
	document.getElementById('action').value = act;
	document.getElementById('mod').submit();
}
function submitmove(fid) {
	document.mod.arg.value = fid;
	submitmod('move')
}
function movetid() {
	var x = document.getElementById('forumselect').selectedIndex;
	document.getElementById('move').innerHTML = document.getElementsByTagName('option')[x].value;
	return document.getElementsByTagName('option')[x].value;
}
function trashConfirm(e) {
	if (confirm('Are you sure you want to trash this thread?'));
	else {
		e.preventDefault();
	}
}
