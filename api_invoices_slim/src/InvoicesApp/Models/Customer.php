<?php namespace InvoicesApp\Models;

/*MODELO DO OBJETO CUSTOMER - Classe especifica - Representa um Customer
Possui as mesma propriedades da base de dados. Assim esta a tipificar o que vam da 
base de dados por modelos
*/
class Customer {

    public $id;
    public $nome;
    public $idade;
    public $morada;
    public $cod_postal;
    public $cidade; //se passar cidade pra privado cidade deixaria de aparecer
    //public $x = 123; pode passar atributo já fixo.


    /*CONTRUTOR PARA CONSTRUIR UM CUSTOMER */
    public function __construct(array $data = null)
    {
        if (!is_null($data)) {
            $this->id = $data['id'];
            $this->nome = $data['nome'];
            $this->idade = $data['idade'];
            $this->morada = $data['morada'];
            $this->cod_postal = $data['cod_postal'];
            $this->cidade = $data['cidade'];
        }

        //CONVERTENDO PARA INT
        $this->id = (int)$this->id;
        $this->idade = (int)$this->idade;
        $this->cod_postal = (int)$this->cod_postal;
    }
}