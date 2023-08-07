local l, json, data = ...

l.write([[<iframe width="640" height="360" src="https://www.youtube.com/embed/%s"
frameborder="0"	allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
allowfullscreen></iframe>]], data.id)

l.output()
