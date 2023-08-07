local l, json, data = ...

-- actual code:

l.write('<div style="border:1px solid black;display:inline-block;padding:10px">hello this is a lua template test.<ul>')

for k,v in pairs(data) do
	l.write('<li><b>'..k..'</b> - '..v..'</li>')
end
--l.write(data)

l.write('</div>')

l.output()
