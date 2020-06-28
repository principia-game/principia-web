{% if newsid %}
	<h2>{{ news.title }}</h2>
	<p><em>Published on the {{ time }} (GMT)</em></p>
	<p>{{ news.text }}</p>

	<h2>Comments</h2>
	{{ comments(comments, 'news', news.id) }}
	<br>
{% else %}
	<h2>Latest News</h2>
	<ul>
		{% for new in news %}
			<li><a href="news.php?id={{ new.id }}">{{ new.title }}</li>
		{% else %}
			<li>No news.</li>
		{% endfor %}
	<ul>
{% endif %}