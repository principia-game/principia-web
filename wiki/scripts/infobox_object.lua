local l, json, data = ...

l.write('<div class="infobox"><table>')
local heading_classes = {'heading'}
if data.beta then	table.insert(heading_classes, 'beta')	end
if data.unused then	table.insert(heading_classes, 'unused')	end

l.write('<tr><td class="%s" colspan="2">%s</td></tr>', table.concat(heading_classes, ' '), data.name)
l.write('<tr><th style="width:50%%">ID</th><td>%s</td></tr>', data.id)
l.write('<tr><th>Category</th><td>%s</td></tr>', data.category)

if data.sublayer_width then
	l.write('<tr><th>Sublayer width</th><td>%s</td></tr>', data.sublayer_width)
end

if data.stationary then
	l.write('<tr><th>Stationary</th><td>%s</td></tr>', data.stationary)
end

if data.version then
	l.write('<tr><th>Added in</th><td>%s</td></tr>', data.version)
end

l.write('</table></div>')

l.output()
