{% if newsid %}
	<h2>{{ news.title }}</h2>
	<p>{{ news.text }}</p>
	<p><em>Published on the {{ time }} (GMT)</em></p>
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