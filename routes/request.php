<?php

namespace Routes;

class Request
{

	protected $files = [];
	protected $base;
	protected $uri;
	protected $method;
	protected $protocol;
	protected $data = [];

	public function __construct()
	{
		$this->base = $_SERVER['REQUEST_URI'];
		$this->uri  = $_REQUEST['uri'] ?? '/';
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
		$this->setData();

		if (count($_FILES)) {
			$this->setFiles();
		}
	}

	protected function setData()
	{
		switch ($this->method) {
			case 'post':
				$this->data = $_POST;
				break;
			case 'get':
				$this->data = $_GET;
				break;
			case 'patch':
				$this->data = file_get_contents('php://input');
				break;
			case 'head':
			case 'patch':
			case 'put':
			case 'delete':
			case 'options':
				parse_str(file_get_contents('php://input'), $this->data);
		}
	}

	protected function setFiles()
	{
		$size = count($_FILES);
		//	if ($size == count($this->files)) return;
		//echo $size . " - " . count($this->files);
		foreach ($_FILES as $key => $value) {
			$this->files[$key] = $value;
			if ($size == 1) break;
		}
	}


	public function base()
	{
		return $this->base;
	}

	public function method()
	{

		return $this->method;
	}

	public function uri()
	{
		return $this->uri;
	}

	public function getDados()
	{
		return $this->data;
	}

	public function __isset($key)
	{
		return isset($this->data[$key]);
	}

	public function __get($key)
	{
		if (isset($this->data[$key])) {
			return $this->data[$key];
		}
	}

	public function hasFile($key)
	{

		return isset($this->files[$key]);
	}

	public function file($key)
	{

		if (isset($this->files[$key])) {
			return $this->files[$key];
		}
	}
}
