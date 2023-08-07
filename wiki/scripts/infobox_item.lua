local l, json, data = ...

l.write('<div class="infobox"><table>')
l.write('<tr><td class="heading" colspan="2">%s</td></tr>', data.name)
l.write('<tr><th style="width:50%%">ID</th><td>%s</td></tr>', data.id)

if data.type and data.type ~= "" then
	l.write('<tr><th>Type</th><td>%s</td></tr>', data.type)
end

if data.version then
	l.write('<tr><th>Added in</th><td>%s</td></tr>', data.version)
end

l.write('</table></div>')

l.output()
