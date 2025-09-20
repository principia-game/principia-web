
$     = function (el) {	return document.getElementById(el); }
$hide = function (el) { el.style.display = 'none'; };
$show = function (el) { el.style.display = 'block'; };

$('init_code_1').value = `function init()
	this:init_draw(img_width, img_height)

	for x=1, img_width do
		for y=1, img_height do
			local c = colors[x+((y-1)*img_width)]
			this:set_sprite_texel(base_x+x-1, base_y+img_height-y, c[1]/255, c[2]/255, c[3]/255, (c[4] ~= nil and c[4]/255 or 1.0))
		end
	end
end`;

$('init_code_2').value = `function init()
	this:init_draw(img_width, img_height)

	local j = 0
	for i=1, #colors do
		local ca = colors[i]

		for n=1, ca[2] do
			local c = ca[1]
			this:set_sprite_texel(base_x + (j % img_width), base_y + img_height-math.floor(j / img_width)-1, c[1]/255, c[2]/255, c[3]/255, (c[4] ~= nil and c[4]/255 or 1.0))
			j = j + 1
		end
	end
end`;

$('draw_image').value = `function step()
	this:draw_sprite(0, 0, 0, 5, 5, base_x, base_y, base_x+img_width, base_y+img_height)
end`;


function image_to_lua() {
	var el = $('temp_upload');
	var canvas = $('temp_canvas');
	let img = $('temp_img');
	const include_alpha = $('include_alpha').checked;
	$show($('loading'));

	img.src = '';

	if (el.files.length != 1)
		return;

	var f = el.files[0];
	if (!f.type.match(/image.*/))
		return;

	var reader = new FileReader();

	reader.onload = function (e) { img.src = e.target.result; }
	reader.readAsDataURL(f);

	img.onload = function (e) {
		var w = img.width;
		var h = img.height;

		$('img_res').innerHTML = `${w}x${h}`;

		canvas.width = w;
		canvas.height = h;

		var ctx = canvas.getContext("2d");
		ctx.clearRect(0, 0, 512, 512);
		ctx.drawImage(img, 0, 0);

		var img_data = ctx.getImageData(0, 0, w, h);
		var data = img_data.data;

		var str_1 = str_2 = str_3 = '';
		var vars = '';

		var prev_ic = -1;
		var var_counter = 0;

		var colors = [];
		var counter = 0;

		var varname = '';

		var s = 0;
		for (var i = 0, n = data.length; i < n; i += 4) {
			var red = data[i];
			var green = data[i + 1];
			var blue = data[i + 2];
			var alpha = 0;
			var color_str = '';
			if (include_alpha) {
				alpha = data[i + 3];
				color_str = '{' + red + ',' + green + ',' + blue + ',' + alpha + '}';
			} else
				color_str = '{' + red + ',' + green + ',' + blue + '}';

			var ic = red + (green << 8) + (blue << 16) + (alpha << 24);

			var c = colors[ic];

			if (ic == prev_ic || prev_ic == -1)
				var_counter = var_counter + 1;
			else {
				// push changes
				str_3 += '{' + varname + ',' + var_counter + '},';
				var_counter = 1;
			}

			if (c === undefined) {
				varname = 'c' + ++counter;
				colors[ic] = varname;
				vars += varname + '=' + color_str + ';';
			} else
				varname = c;

			str_1 += color_str + ',';
			str_2 += varname + ',';

			prev_ic = ic;

			console.log(s % w && s > w);

			if (s++ % w == w - 1) {
				/*
				str_1 += '\n';
				str_2 += '\n';
				*/
			}
		}

		str_3 += '{' + varname + ',' + var_counter + '},';

		const based = `img_width=${w};img_height=${h}
base_x=${$('base_x').value};base_y=${$('base_y').value};\n`;

		str_1 = based + '\ncolors={ ' + str_1 + '}';
		str_2 = based + vars + '\ncolors={ ' + str_2 + '}';
		str_3 = based + vars + '\ncolors={ ' + str_3 + '}';

		var best_str = '';
		var best_str_len = 0;

		if (str_1.length > str_2.length) {
			best_str = str_2;
			best_str_len = str_2.length;
		} else {
			best_str = str_1;
			best_str_len = str_1.length;
		}

		$('code_1').value = best_str;
		$('v1_chars').innerHTML = best_str_len;

		$('code_2').value = str_3;
		$('v2_chars').innerHTML = str_3.length;

		console.log('str1: ' + str_1.length);
		console.log('str2: ' + str_2.length);
		console.log('str3: ' + str_3.length);

		$show($('results'));
		$hide($('loading'));
	}
}
