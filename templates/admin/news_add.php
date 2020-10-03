{% extends "_layout.php" %}

{% block title %}New news article{% endblock %}

	{% block content %}
<h2>New news article</h2>

<form action="/news.php?new=1" method="POST">
	<input type="hidden" name="ApOsTaL" value="jupiter">
	<p><input type="text" name="title" placeholder="Title" size=60></p>
	<p><textarea name="text" placeholder="Body text" cols="80" rows="15"></textarea></p>
	<p><input type="text" name="redirect" placeholder="Redirect URL (optional, leave blank for none)" size=60></p>

	<p><input type="submit" value="Post"></input></p>
</form>
	{% endblock %}