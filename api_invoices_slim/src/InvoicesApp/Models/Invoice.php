<?php namespace InvoicesApp\Models;

//MODELO DO OBJETO PARA CRIAR UMA INVOICE
class Invoice {

    public $id;
    public $data;
    public $id_cliente;
    public $nome;
    private $deleted;
}