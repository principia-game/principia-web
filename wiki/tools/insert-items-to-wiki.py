from pathlib import Path
from natsort import natsorted
import json
import re

outdata = {}

# this thing needs to be inside the custom-object-help repo folder!

def main():
	pathlist = Path('item_help').glob('*.txt')

	# This is all for making the entries be ordered by ID.
	filelist = []
	for path in pathlist:
		filelist.append(path.stem)
	filelist = natsorted(filelist, reverse=False)

	for file in filelist:
		description = Path("item_help/"+file+".txt").read_text().strip()

		if description == "TODO":
			description = "No description added for this item yet, sorry!"

		item = re.findall("([0-9]+):(.+)", file)
		print(item)

		s = \
			'{{{{ infobox_item({{\n' \
			'\t"id": {item[0][0]},\n' \
			'\t"name": "{item[0][1]}",\n' \
			'\t"type": "",\n' \
			'}}) }}}}\n\n{description}'.format(item=item, description=description)

		outdata[item[0][1]] = s

	with open("outdata.json", "w") as f:
		json.dump(outdata, f, indent=4)

if __name__ == "__main__":
	main()
