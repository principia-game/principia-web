<form action="/_post_comment.php" method="POST">
	<input type="hidden" name="id" value="{{ id }}">
	<input type="hidden" name="t" value="lvl">
	<textarea name="comment" class="comment-input"></textarea>
	<br><input type="submit" value="Post"></input>
</form>
<div class="comments">
	{% for cmnt in cmnts %}
		<div class="comment-entry" id="comment-67712">
			<span class="date">2 hours ago</span>
			{{ userlink(cmnt, 'u_') }}: <span class="comment-text">{{ cmnt.message }}</span>
		</div>
	{% endfor %}
</div>