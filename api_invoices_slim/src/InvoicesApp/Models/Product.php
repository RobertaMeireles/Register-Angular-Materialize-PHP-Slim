<?php namespace InvoicesApp\Models;

//CLASSE PARA CRIAÇAO DOS PRODUTOS

class Product {

    public $id;
    public $designacao;
    public $descricao;
    public $preco;
    public $id_categoria;
    public $categoria;

    public function __construct(array $data = null)
    {
        if (!is_null($data)) {
            $this->id = $data['id'];
            $this->designacao = $data['designacao'];
            $this->descricao = $data['descricao'];
            $this->preco = $data['preco'];
            $this->id_categoria = $data['id_categoria'];
            $this->categoria = $data['categoria'];
        }

        $this->id = (int)$this->id;
        $this->preco = (float)$this->preco;
        $this->id_categoria = (int)$this->id_categoria;
    }
}