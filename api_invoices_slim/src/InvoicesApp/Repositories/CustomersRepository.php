<?php namespace InvoicesApp\Repositories;

use Core\Database\Database;
use Exception;
use InvoicesApp\Models\Customer;
use PDO;

class CustomersRepository {

    //ATRIBUTO
    private $database;

    //CONSTRUTOR
    /*Receber uma instancia de database */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    //METODO PARA BUSCAR TODOS OS CUSTOMERS NA BASE DE DADOS COM A CIDADE
    /*Recebendo a pagina que deseja e quantos registros por pagina e o filter 
    pq posso querer o meus registros filtrados. 
    SQL:
    $offset = $page * $size;                                           //offset apartir de que registro quero enviar as paginas para ter o limite no sql. sendo a minha pg x o size. ex: quero 5 pg. 0x5 =0 entao começa a aparesentar no registro zero, quero 5pg. 1x5=5 entao começo no registro 5 durante 5 pg
            $sql = "SELECT 
                    cl.*,                                              //buscar tudo da tabela cliente
                    ci.cidade                                          //da tabela de cidade apenas o nome da cidade
                FROM clientes cl                                       //onde? em clientes que irá chamar cl
                LEFT JOIN cidades ci ON cl.cod_postal = ci.cod_postal  //REGRA DO JOIN: LEFT JOIN da tabela cidade que vai chamar ci, onde o cod_postal da tabela cl (clientes) seja igual ao cod_postal da tabela cs cidade possui 
                WHERE                                                  //PODE APROVEITAR E PASSAR O FILTRO DESEJADO (QUERO ISSO ACIMA QUANDO(CONFORME ABAIXO)) ONDE
                    cl.nome LIKE :filter OR                            //O nome do cliente (cl) contenha aquilo que passar no filtro (recebendo pelo parametro) OU     
                    cl.morada LIKE :filter OR                          //a morada do cliente (cl) contenha aquilo que passar no filtro (recebendo pelo parametro) OU
                    ci.cidade LIKE :filter                             // a cidade do cliente (ci) contenha aquilo que passar no filtro (recebendo pelo parametro)
                LIMIT $offset, $size";                                 //passando o valor offset e o valor da pg para enviar o limit para a base de dados

        $stmt = $this->database->query($sql, [                         //passando a informaçao para o metodo query que retorna um PDOStatement qu depois extrai a informaçao 
            'filter' => '%' . $filter . '%'                            passando o sql feito acima e o filter recebido no parametro concatenado com duas % que o que vai existir no meu PDOStatement
        ]);                                                            se nao passar um filter é tudo se passar o filtro filtra aquilo que pedir

        $totalRecords = $this->count($filter);                                            //para fazer a paginaçao. variavel que irá conter o total de itens da tabela possui conforme filtro utilizando a funçao count criada logo abaixo, e passando o filtrer como parametro
        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Customer');    // customers vai ser o fetchAll passando como parametro uma FETCH_CLASS da classe de Models/Customers, onde cada elemento sera retornado de uma forma de instancia de Customers 

        O QUE VAI RETORNAR PARA O USUÁRIO.
        NOTE QUE PASSANDO TUDO ABAIXO FACILITA O USUÁRIO A SABER QUANTOS REGISTROS POSSUI, QUANTAS PG E ECT...
        return [
            'total_records' => $totalRecords,             //variavel com o total de itens conforme tabela
            'total_pages' => ceil($totalRecords / $size), //total de pg existem
            'num_records' => count($customers),           //numero de customers que tem essa pg
            'content' => $customers                       //itens cadastrados conforme o cliente deseja ver na tela
        ];


    */
    public function all(int $page = 0, int $size = 5, string $filter = '') {
        // $customers = $this->database->all('clientes'); //nao feito pq nao aparece a cidade do cliente
        $offset = $page * $size;

        $sql = "SELECT
                    cl.*,
                    ci.cidade
                FROM clientes cl
                LEFT JOIN cidades ci ON cl.cod_postal = ci.cod_postal
                WHERE
                    cl.nome LIKE :filter OR
                    cl.morada LIKE :filter OR
                    ci.cidade LIKE :filter
                LIMIT $offset, $size";

        $stmt = $this->database->query($sql, [
            'filter' => '%' . $filter . '%'
        ]);
        
        $totalRecords = $this->count($filter);
        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Customer');

        return [
            'total_records' => $totalRecords,
            'total_pages' => ceil($totalRecords / $size),
            'num_records' => count($customers),
            'content' => $customers
        ];
    }

    /*METODO PARA BUSCAR UM ESPECIFICO CUSTOMERS NA BASE DE DADOS
    public function byId(int $id): ?Customer { //o ponto de interrogaçao indica que vai retornar ou um Customer ou nulo
    $sql = "SELECT
            $sql = "SELECT 
                    cl.*,                                              //buscar tudo da tabela cliente
                    ci.cidade                                          //da tabela de cidade apenas o nome da cidade
                FROM clientes cl                                       //onde? em clientes que irá chamar cl
                LEFT JOIN cidades ci ON cl.cod_postal = ci.cod_postal  //REGRA DO JOIN: LEFT JOIN da tabela cidade que vai chamar ci, onde o cod_postal da tabela cl (clientes) seja igual ao cod_postal da tabela cs cidade possui 
                WHERE id = :id                                         //onde o id seja igual o id
                LIMIT 1";                                               //limitando para 1
        $stmt = $this->database->query($sql, [                         //passando a query para o metodo query da base de dados junto com o id recebido no paramatro
            'id' => $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'InvoicesApp\Models\Customer');   // customers vai ser o setFetchModel passando como parametro uma FETCH_CLASS da classe de Models/Customers, onde cada elemento sera retornado de uma forma de instancia de Customers 
        $customer = $stmt->fetch();                                             //alimentando na variavel customer o fetch
        return $customer ? $customer : null;                                    //por fim retornando o customer se ele existir*/
    public function byId(int $id): ?Customer {
        $sql = "SELECT
                    cl.*,
                    ci.cidade
                FROM clientes cl
                LEFT JOIN cidades ci ON cl.cod_postal = ci.cod_postal
                WHERE id = :id
                LIMIT 1";
        $stmt = $this->database->query($sql, [
            'id' => $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'InvoicesApp\Models\Customer');
        $customer = $stmt->fetch();
        return $customer ? $customer : null;
    }

    //METODO PARA CRIAR UM CUSTOMERS NA BASE DE DADOS
    /*
    1 public function create(array $data): ?Customer { //o ponto de interrogaçao indica que vai retornar ou um Customer ou nulo
    2 fazer uma validação abaixo: se nao apresentar valores nos campos recebido 
    por paramentro em data crie uma instancia de Exception('Name is required');

    3 - em  $city = $this->getCity($data['cod_postal']); validar se o código postal digitado 
    existe na base de dados, chamando o metodo getCity 

    4 - Caso exista a city, insere o customers chamando o metodo insert passando a que seja inserido
    o item na tabela clientes e as informaçoes data recebida no parametro dessa funçao (data) 

    E no final vai dizer que o array de data vai passar tem um id passando ou seja 
    um $result->lastInsertId pois a classe dataBase, metodo insert retorna um lastInsertId;
    e a cidade que é a $city->cidade

    Caso nao existe o cep cadastrado throw new Exception('Postal code not found!');


        if ($city) {
            $result = $this->database->insert('clientes', $data);

            $data['id'] = $result->lastInsertId;
            $data['cidade'] = $city->cidade;

            return new Customer($data);
        }

        throw new Exception('Postal code not found!');


        retonando a instancia de Customer
    } */
    public function create(array $data): ?Customer {

        if (!isset($data['nome'])) {
            throw new Exception('Name is required');
        }
        if (!isset($data['idade'])) {
            throw new Exception('Age is required');
        }
        if (!isset($data['morada'])) {
            throw new Exception('Address is required');
        }
        if (!isset($data['cod_postal'])) {
            throw new Exception('Postal code is required');
        }

        $city = $this->getCity($data['cod_postal']);

        if ($city) {
            $result = $this->database->insert('clientes', $data);

            $data['id'] = $result->lastInsertId;
            $data['cidade'] = $city->cidade;

            return new Customer($data);
        }

        throw new Exception('Postal code not found!');
    }

    //METODO PARA FAZER UPDADE DE UM CUSTOMER NA BASE DE DADOS
    /*recebendo um id e um array constando os dados do utilizador 
    vai chamar o metodo update de database, passando a tabela que 
    consta o customer, as informações e o id do cliente
    Em $this->byId($id) retornando para o front end o usuário atualizado 
    chamando a função byid que retorna um usuário*/
    public function update(int $id, array $data): ?Customer {
        $this->database->update('clientes', $data, 'id = :id', [
            'id' => $id
        ]);

        return $this->byId($id);
    }



    //METODO PARA DELETAR UM UM CUSTOMER NA BASE DE DADOS
    /*recebe do controlador o id, passando em seguida esse valor para o metodo
    delete da classe database com os parametros nome da tabela e o valor do id
    que deseja excluir. Retornanado um metodo de stmt se for maior que zero 
    apresente quantos valores foram deletados*/
    public function remove(int $id): bool {
        $stmt = $this->database->delete('clientes', 'id = :id', [
            'id' => $id
        ]);
        return $stmt->rowCount() > 0;
    }


    //METODO PARA TRAZER A CIDADE DE UM CUSTOMER
    /*Se tenho a cidade eu tenho cidade, caso contrario false */
    public function getCity(int $postalCode) {
        $sql = "SELECT * FROM cidades WHERE cod_postal = :cod_postal LIMIT 1";
        $stmt = $this->database->query($sql, [
            'cod_postal' => $postalCode
        ]);

        return $stmt->fetch();
    }

    //METODO PARA FAZER O COUNT PARA FAZER A SOMA DA PAGINAÇAO
    /*Esse count vai devolver um objeto que tem um numero do total */
    public function count(string $filter = '') {
        $sql = "SELECT
                    COUNT(*) AS total
                FROM clientes cl
                LEFT JOIN cidades ci ON cl.cod_postal = ci.cod_postal
                WHERE
                    cl.nome LIKE :filter OR
                    cl.morada LIKE :filter OR
                    ci.cidade LIKE :filter";
        
        $stmt = $this->database->query($sql, [
            'filter' => '%' . $filter . '%'
        ]);
        $rs = $stmt->fetch();
        //se tiver rs retona o total, caso contrario null
        return $rs ? (int)$rs->total : null; 
    }
}