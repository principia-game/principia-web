{% extends "_layout.php" %}

{% block title %}Home{% endblock %}

	{% block content %}
{% if just_registered %}
	<div class="header_message">You've been successfully registered!</div>
{% endif %}

<h2 class="header">Latest news <a href="news.php">More</a></h3>
<ul>
	{% for new in news %}
		<li><a href="news.php?id={{ new.id }}">{{ new.title }}</a></li>
	{% else %}
		<li>No news.</li>
	{% endfor %}
</ul>

<h2 class="header">Latest custom levels <a href="latest.php?type=custom">More</a></h2>
{% for custom_level in custom_levels %}
	{{ level(custom_level) }}
{% else %}
	There are no custom levels. You could create one!
{% endfor %}

<h2 class="header">Latest adventures <a href="latest.php?type=adventure">More</a></h2>
{% for adventure_level in adventure_levels %}
	{{ level(adventure_level) }}
{% else %}
	There are no adventure levels. You could create one!
{% endfor %}

<h2 class="header">Latest comments<a href="comments">More</a></h2>
<div class="comments">
{% for i in 0..10 %}
<div class="comment-entry" id="comment-1">
	<p>
		<a class="user" href="user.php?id=1"><span class="t_user">null</span></a>
		commented on <a href="level.php?id=1">some level</a>:
		<span class="date">2 hours ago</span>
	</p>
	<span class="comment-text">Blah blah</span>
</div>
{% endfor %}
</div>
<br>
	{% endblock %}