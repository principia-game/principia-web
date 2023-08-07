#!/usr/bin/python
import sys
import re
import json

def replace_all(text, dic):
    for i, j in dic.items():
        text = text.replace(i, j)
    return text

insert = True
delete = False
print_object_list = True
outdata = {}

object_path = '/home/administrator/.principia2/obj'

fh = open(object_path, 'r')
object_file_string = fh.read()
fh.close()

categories = {}
categories_tuple = ('Basic', 'Mechanics', 'Electronics', 'Robotics', 'Signal-i1o1', 'Signal-i2o1', 'Signal-misc', 'Tools/effects', 'Interaction', 'Game', 'Other')
for category_name in categories_tuple:
	categories[category_name] = []

for obj in object_file_string.split('====='):
	g_id, name, description, category_name, layer, sublayer, edevice = obj.split(':::::')

	if name == 'Lua Script':
		# luascriptsidan är äcklig
		continue

	g_id = int(g_id)
	is_edevice = False if edevice == '0' else True
	edevice = edevice[1:] # remove the 0 or 1 which specifies if it's an edevice or not

	obj_value = {
		'g_id':g_id,
		'name':name,
		'description':description,
		'sublayer':sublayer,
		'is_edevice':is_edevice}

	if name == 'Item':
		obj_value[description] = 'Selection of items for robots.'

	# we don't want the laser duplicate
	if name == 'Laser' and g_id == 147:
		continue

	# blarg
	if category_name == 'Testing':
		category_name = "Other"

	categories.get(category_name).append(obj_value)

for category_name in categories_tuple:
	for obj in categories[category_name]:
		# convert ~~HTML~~GTK pango styling to sorta equivalent markdown
		description = replace_all(obj['description'], {
			'<b><big>':		'## ',
			'</big></b>':	'',
			"\n<b><tt>":	"\n- **`",
			"<b><tt>":		"**`",
			'</tt></b>':	'`**',

			'<span font_family="monospace">': '`',
			'</span>': '`',

			'<sub><i>':		'^',
			'</i></sub>':	'^',

			'<b>':			'**',
			'</b>':			'**',
			'<s>':			'~~',
			'</s>':			'~~'
		})

		s = \
			'{{{{ infobox_object({{\n' \
			'\t"id": {self[g_id]},\n' \
			'\t"name": "{self[name]}",\n' \
			'\t"category": "{category_name}",\n' \
			'\t"sublayer_width": {self[sublayer]},\n' \
			'}}) }}}}\n\n{description}'.format(self=obj, category_name=category_name, description=description)
		outdata[obj['name']] = s

with open("outdata.json", "w") as f:
	json.dump(outdata, f, indent=4)
