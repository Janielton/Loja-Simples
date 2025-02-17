<?php
require "controllers/ControleDados.php";
require "controllers/ControllerView.php";
require "controllers/ControllerCarrinho.php";
require "routes/request.php";
require "routes/route.php";
require __DIR__ . '/vendor/autoload.php';

function getPart($nome)
{
	include 'views/' . $nome . '.php';
}
try {

	include_once "rotas.php";
	resolve();
} catch (\Exception $e) {
	echo $e->getMessage();
}
