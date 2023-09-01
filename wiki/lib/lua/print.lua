
local buffer = {}

return {
	write = function(text, ...)
		table.insert(buffer, string.format(text, ...))
	end,
	output = function()
		io.write(table.concat(buffer, ""))
	end
}
