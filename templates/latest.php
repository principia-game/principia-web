<h2>Latest levels</h2>
<div class="latest-buttons">
	<a {% if type == 'custom' %}class="sel"{% endif %} href="latest.php?type=custom">Custom</a>
	<a {% if type == 'adventure' %}class="sel"{% endif %} href="latest.php?type=adventure">Adventure</a>
	<a {% if type == 'puzzle' %}class="sel"{% endif %} href="latest.php?type=puzzle">Puzzle</a>
	<a {% if type == 'all' %}class="sel"{% endif %} href="latest.php">All</a>
</div>

{% for level in levels %}
	{{ level(level) }}
{% else %}
	There are no {% if type != 'all' %}{{ type }}{% endif %} levels. You could create one!
{% endfor %}

<br><br>