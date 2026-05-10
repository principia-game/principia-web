<?php

namespace DiscordWebhooks;

/**
 * Client generates the payload and sends the webhook payload to Discord
 */
class Client
{
	protected $url;
	protected $username;
	protected $avatar;
	protected $message;
	protected $embeds;
	protected $tts;
	protected $files;

	public function __construct()
	{
		$this->files = array();
	}

	public function tts($tts = false) {
		$this->tts = $tts;
		return $this;
	}
	public function username($username)
	{
		$this->username = $username;
		return $this;
	}

	public function avatar($new_avatar)
	{
		$this->avatar = $new_avatar;
		return $this;
	}

	public function message($new_message)
	{
		$this->message = $new_message;
		return $this;
	}

	public function embed($embed) {
		$this->embeds[] = array_filter($embed->toArray(), fn($value) => $value !== null);
		return $this;
	}

	public function addFile($file_path, $posted_filename = null, $mime_type = null) {
		if (!file_exists($file_path)) {
			throw new \Exception("$file_path: File not found.");
		}
		if (!$posted_filename) {
			$posted_filename = basename($file_path);
		}
		$this->files[] = new \CURLFile($file_path, $mime_type, $posted_filename);
		return $this;
	}

	public function addStringFile($data, $posted_filename, $mime_type = null) {
		$this->files[] = new \CURLStringFile($data, $posted_filename, $mime_type);
		return $this;
	}

	public function clearFiles() {
		$this->files = array();
		return $this;
	}

	public function send($url)
	{
		$data = [
			'content' => $this->message,
			'username' => $this->username,
			'avatar_url' => $this->avatar,
		];

		if ($this->embeds)
			$data['embeds'] = $this->embeds;
		if ($this->tts)
			$data['tts'] = $this->tts;

		$payload = json_encode($data);

		if ($this->files) {
			$postFields = $this->files;
			$postFields['payload_json'] = $payload;
			$headers = array('Content-Type: multipart/form-data');
		} else {
			$postFields = $payload;
			$headers = array('Content-Type: application/json');
		}

		$this->executeRequest($url, $postFields, $headers);

		return $this;
	}

	protected function executeRequest($url, $postFields, $headers)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		print($postFields);

		$result = curl_exec($ch);
		// Check for errors and display the error message
		if($errno = curl_errno($ch)) {
			$error_message = curl_strerror($errno);
			throw new \Exception("cURL error ({$errno}):\n {$error_message}");
		}

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpcode != 204 && $httpcode != 200)
		{
			throw new \Exception($httpcode . ':' . $result);
		}
	}
}
