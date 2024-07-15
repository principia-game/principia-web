
function vote(el, id) {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "/level/"+id, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("vote=yes");

	el.innerHTML = 'Liked!';
	el.disabled = 'disabled';
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

	if (currentTheme.startsWith('/css/style.css')) {
		themeStylesheet.setAttribute('href', '/css/darkmode.css?v='+cssver);
		document.cookie = 'darkmode=1; max-age=31536000; path=/';
	} else {
		themeStylesheet.setAttribute('href', '/css/style.css?v='+cssver);
		document.cookie = 'darkmode=0; max-age=31536000; path=/';
	}
}

document.getElementById('dark-mode-toggle').addEventListener('click', toggleDarkMode);


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
