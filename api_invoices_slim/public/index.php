<?php

//comando para liberar o cors policy
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');

//usar a classe InvoicesApp
use InvoicesApp\InvoicesApp;

//chamando o autoload do php
require_once __DIR__ . '/../vendor/autoload.php';


//instanciando a Api slim onde inicia a aplicaçao e em seguida chamando o metodo start que faz parta da classe app
$app = new InvoicesApp();
$app->start();