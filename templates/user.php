{% extends "_layout.php" %}

	{% block content %}
<h2>{{ name }}</h2>

{% for level in levels %}
	{{ level(level) }}
{% else %}
	This user doesn't have any uploaded levels.
{% endfor %}

<br><br>
	{% endblock %}