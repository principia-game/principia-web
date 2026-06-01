<?php

/*
	https://github.com/Eleirbag89/MastodonBotPHP

	Copyright (c) 2020 Gabriele Grillo

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
*/

class MastodonAPI {
	private $token;
	private $instance_url;

	public function __construct($token, $instance_url) {
		$this->token = $token;
		$this->instance_url = $instance_url;
	}

	public function postStatus($status) {
		return $this->callAPI('/api/v1/statuses', 'POST', $status);
	}

	public function uploadMedia($media) {
		return $this->callAPI('/api/v2/media', 'POST', $media);
	}

	public function callAPI($endpoint, $method, $data) {
		$headers = [
			'Authorization: Bearer '.$this->token,
			'Content-Type: multipart/form-data',
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->instance_url.$endpoint);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$reply = curl_exec($ch);

		if (!$reply) {
			return json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
		}
		curl_close($ch);

		return json_decode($reply, true);
	}
}
