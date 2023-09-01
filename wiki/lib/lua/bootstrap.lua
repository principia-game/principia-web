-- wiki bootstrapper

local json = dofile('lib/lua/json.lua')
local l = dofile('lib/lua/print.lua')
local data = json.decode(arg[2])

loadfile("scripts/"..arg[1]..".lua")(l, json, data)
