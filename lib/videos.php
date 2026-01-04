<?php

function getVideos() {
	return query("SELECT v.*, @userfields FROM videos v
			JOIN users u ON v.author = u.id
			ORDER BY v.id DESC");
}

function getVideoByYouTubeID($youtube_id) {
	return fetch("SELECT v.*, @userfields FROM videos v
			JOIN users u ON v.author = u.id
			WHERE v.youtube_id = ?", [$youtube_id]);
}
