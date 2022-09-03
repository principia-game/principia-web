
function vote(el, id) {
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", "/level.php?id="+id+"&vote", true);
	xhttp.send();

	el.innerHTML = 'Liked!';
	el.disabled = 'disabled';
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
