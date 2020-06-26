<?php namespace InvoicesApp\Repositories;

//REPOSITORIO PARA AS FATURAS

use Core\Database\Database;
use Exception;
use PDO;

class InvoicesRepository {

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

            $sql = "SELECT
                    f.*,                                        //onde deve apresentar o total da tabela facturas (f)
                    c.nome                                      //a coluna nome na tabela clientes (c)
                FROM
                    facturas f                                  //onde faturas (f)
                LEFT JOIN clientes c ON f.id_cliente = c.id     //REGRA DO JOIN: LEFT JOIN da tabela clientes que vai chamar c, onde o id do cliente em facturas for igual ao id do cliente em clientes
                WHERE f.deleted = 0 "; //onde na tabela facturas, na coluna deleted seja igual a zero (ou seja nao irá apresentar as facturas que foram deletadas)

        $data = [];                                            //criando um array em branco 
        if (!is_null($start) && !is_null($end)) {              //se start e end recebido no parametro nao estiver nulo e 
            $sql .= " AND data BETWEEN :start AND :end";       //faça essa linha da query para filtrar. 
            $data['start'] = $start;                           //onde no array data irá possuir as facturas vindo do filtro acima start e end
            $data['end'] = $end;
        }

        $offset = $page * $size;                              //offset apartir de que registro quero enviar as paginas para ter o limite no sql. sendo a minha pg x o size. ex: quero 5 pg. 0x5 =0 entao começa a aparesentar no registro zero, quero 5pg. 1x5=5 entao começo no registro 5 durante 5 pg 
        $sql .= " LIMIT $offset, $size";                      //colocando na query esse limite para a paginaçao

        $stmt = $this->database->query($sql, $data);         //passando a query criada acima para o metodo query da base de dados
        
        $totalRecords = $this->count($start, $end);          //para fazer a paginaçao. variavel que irá conter o total de itens da tabela possui conforme filtro utilizando a funçao count criada logo abaixo, e passando o filtrer como parametro
        $invoices = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Invoice');  // invoices vai ser o fetchAll passando como parametro uma FETCH_CLASS da classe de Models/Customers, onde cada elemento sera retornado de uma forma de instancia de Invoicces 

        O QUE VAI RETORNAR PARA O USUÁRIO.
        NOTE QUE PASSANDO TUDO ABAIXO FACILITA O USUÁRIO A SABER QUANTOS REGISTROS POSSUI, QUANTAS PG E ECT...
        return [                                            
            'total_records' => $totalRecords,               //variavel com o total de itens conforme tabela
            'total_pages' => ceil($totalRecords / $size),   //total de pg existem
            'num_records' => count($invoices),              //numero de customers que tem essa pg
            'content' => $invoices                          //itens cadastrados conforme o cliente deseja ver na tela */
    public function all($page = 0, $size = 5, $start = null, $end = null) {
        $sql = "SELECT
                    f.*,
                    c.nome
                FROM
                    facturas f
                LEFT JOIN clientes c ON f.id_cliente = c.id
                WHERE f.deleted = 0 ";

        $data = [];
        if (!is_null($start) && !is_null($end)) {
            $sql .= " AND data BETWEEN :start AND :end";
            $data['start'] = $start;
            $data['end'] = $end;
        }

        $offset = $page * $size;
        $sql .= " LIMIT $offset, $size";

        $stmt = $this->database->query($sql, $data);
        
        $totalRecords = $this->count($start, $end);
        $invoices = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Invoice');

        // TODO: Get invoice products
        // TODO: Calculate invoice total

        return [
            'total_records' => $totalRecords,
            'total_pages' => ceil($totalRecords / $size),
            'num_records' => count($invoices),
            'content' => $invoices
        ];
    }


    /*METODO PARA BUSCAR UMA ESPECIFICA INVOICES NA BASE DE DADOS
        $sql = "SELECT
                    f.*,                                              //buscar tudo da tabela facturas
                    c.nome                                            //coluna nome da tabela clientes
                FROM
                    facturas f                                        //da tabela facturas
                LEFT JOIN clientes c ON f.id_cliente = c.id           //REGRA DO JOIN: LEFT JOIN da tabela clientes que vai chamar c, onde o id do cliente em facturas for igual ao id do cliente em clientes
                WHERE f.deleted = 0 AND f.id = :id                    //onde na tabela facturas, coluna deleted esteja zero e o id da coluna facturas seja igual ao id enviado por parametro 
                LIMIT 1";                                             //limite de 1 apenas

        $stmt = $this->database->query($sql, [                        //passando a query criada acima para o metodo query da base de dados
            'id' => $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'InvoicesApp\Models\Invoice'); // invoices vai ser o fetchAll passando como parametro uma FETCH_CLASS da classe de Models/Customers, onde cada elemento sera retornado de uma forma de instancia de Invoicces 
        $invoice = $stmt->fetch();                                           //pedindo 1 apenas da tabela 

        //PARA ADCIONAR O TOTAL E OS PRODUTOS DA FATURA  
        if ($invoice) {                                                     //se estiver invoices quero buscar o products dessa invoice
            $products = $this->getInvoiceProducts($id);                     //na variavel products busque o id no metodo abaixo getInvoiceProducts

            $invoice->total = 0;                                            //indica que o total de invoice seja igual a zero para fazer o total da factura
            foreach ($products as $product) {                               //criar um total da nf, fazendo um somatorio de todos os produtos criando um foreach com o products onde para cada produto some o valor do produto colocando em invoice
                $invoice->total += $product->subtotal;
            }

            $invoice->products = $products;                                 //no invoice sera acrescentado mais os products

            return $invoice;                                                //retorna $invoice criada
        }

        return null;                                                        //retone null caso nao localizar*/
    public function byId(int $id) {
        // $invoice = $this->database->byId('facturas', $id);
        // return $invoice;
        $sql = "SELECT
                    f.*,
                    c.nome
                FROM
                    facturas f
                LEFT JOIN clientes c ON f.id_cliente = c.id
                WHERE f.deleted = 0 AND f.id = :id
                LIMIT 1";

        $stmt = $this->database->query($sql, [
            'id' => $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'InvoicesApp\Models\Invoice');
        $invoice = $stmt->fetch();

        if ($invoice) {
            $products = $this->getInvoiceProducts($id);

            $invoice->total = 0;
            foreach ($products as $product) {
                $invoice->total += $product->subtotal;
            }

            $invoice->products = $products;

            return $invoice;
        }

        return null;
    }


    /*METODO PARA CRIAR UM INVOICES NA BASE DE DADOS
            if (!isset($customerId)) {                                  //Se não existir o o id doc cliente
            throw new Exception('Customer is required');               //retona uma  Exception
        }

        $customerRepository = new CustomersRepository($this->database);   //saber se o cliente existe é criar uma instancia da classe base de dados
        $customer = $customerRepository->byId($customerId);               //invoca o metodo byid do repositorio customerRepository esse $customer é o objeto que vem da base de dados
        
        if ($customer) {                                                  //se existir o customer
            $data = [                                                     //na variavel data é acrescentado a data atual e o id da fatura
                'id_cliente' => $customerId, 
                'data' => date('Y-m-d H:i:s')                            
            ];
            $result = $this->database->insert('facturas', $data);        //passando para o metodo insert da database indicando que quer inserir na tabela faturas o que tiver no array data

            $invoiceId = $result->lastInsertId;                           //variavel para constar o numero da factura
            $data['id'] = $invoiceId;                                     //variavel para constar o numero da factura
            $data['products'] = [];                                       //criado um array em branco

            foreach ($products as $productId) {                             //pagar o array de produtos vindo do parametro e inserindo todos na base de dados, inserindo na tabela linha de facturas
                $product = $this->database->byId('produtos', $productId);   //buscar dados do produto
                $this->database->insert('linhas_de_factura', [
                    'id_factura' => $invoiceId,                            //no id da factura que acabei de intrudozir 
                    'id_produto' => $productId,                            //o id producto que é o productid
                    'quantidade' => 1,
                    'valor' => $product->preco                             //o valor vai ser o preço do produto
                ]);
                $data['products'][] = $product;                            //sera alimentado os productos dentro do array data no ultimo indice
            }

            return $data;                                            //retona a data
        }

        throw new Exception('Customer not found!');                           //caso o cliente nao existir                 */   
    public function create(int $customerId, array $products = []) {
        if (!isset($customerId)) {
            throw new Exception('Customer is required');
        }

        $customerRepository = new CustomersRepository($this->database);
        $customer = $customerRepository->byId($customerId);
        
        if ($customer) {
            $data = [
                'id_cliente' => $customerId,
                'data' => date('Y-m-d H:i:s')
            ];
            $result = $this->database->insert('facturas', $data);

            $invoiceId = $result->lastInsertId;
            $data['id'] = $invoiceId;
            $data['products'] = [];

            foreach ($products as $productId) {
                $product = $this->database->byId('produtos', $productId);
                $this->database->insert('linhas_de_factura', [
                    'id_factura' => $invoiceId,
                    'id_produto' => $productId,
                    'quantidade' => 1,
                    'valor' => $product->preco
                ]);
                $data['products'][] = $product;
            }

            return $data;
        }

        throw new Exception('Customer not found!');
    }                   


    //METODO PARA DELETAR UM UM CUSTOMER NA BASE DE DADOS
    /*recebe do controlador o id, passando em seguida esse valor para o metodo
    update para que seja alterado para 1 o campo deleted*/
    public function remove(int $id) {
        $this->database->update('facturas', ['deleted' => 1], 'id = :id', ['id' => $id]);
    }



    //METODO PARA FAZER O COUNT PARA FAZER A SOMA DA PAGINAÇAO
    /*Esse count vai devolver um objeto que tem um numero do total */
    public function count($start = null, $end = null, $customer = null) {
        $sql = "SELECT COUNT(*) as total FROM facturas WHERE deleted = 0 ";

        $data = [];

        if (!is_null($customer)) {
            $sql .= "AND id_cliente = :customer ";
            $data['customer'] = $customer;
        }
        if (!is_null($start) && !is_null($end)) {
            $sql .= " AND data BETWEEN :start AND :end";
            $data['start'] = $start;
            $data['end'] = $end;
        }

        $stmt = $this->database->query($sql, $data);
        $rs = $stmt->fetch();

        return $rs ? (int)$rs->total : null;
    }


    /*METODO PARA RETORNAR UM PRODUTO DA FACTURA
    pedindo as linhas de faturas, juntamente com os produtos
    alem disso o arrendondamento da minha de fatura vezes a quantidade
    as o subtotal para apresentar o subtotal daquela fatura
    passando para a query da database o id que nesse caso é o
    $invoiceId
    e reronando esse metodo fetchAll
    */
    public function getInvoiceProducts(int $invoiceId) {
        $sql = "SELECT
                    p.id,
                    p.designacao,
                    p.descricao,
                    p.id_categoria,
                    ldf.quantidade,
                    ldf.valor AS preco_unitario,
                    ROUND(ldf.valor * ldf.quantidade) AS subtotal
                FROM linhas_de_factura ldf 
                LEFT JOIN produtos p ON p.id = ldf.id_produto
                WHERE ldf.id_factura = :id";

        $stmt = $this->database->query($sql, [
            'id' => $invoiceId
        ]);
        return $stmt->fetchAll();
    }


    /*METODO PARA RETORNAR AS FATURAS DE UM ESPECIFICO CLIENTE */
    public function getInvoicesByCustomer($customer, $page = 0, $size = 5, $start = null, $end = null) {
        $sql = "SELECT
                f.*,
                c.nome
            FROM
                facturas f
            LEFT JOIN clientes c ON f.id_cliente = c.id
            WHERE f.deleted = 0 AND id_cliente = :customer";

        $data = [
            'customer' => $customer
        ];
        if (!is_null($start) && !is_null($end)) {
        $sql .= " AND data BETWEEN :start AND :end";
        $data['start'] = $start;
        $data['end'] = $end;
        }

        $offset = $page * $size;
        $sql .= " LIMIT $offset, $size";

        $stmt = $this->database->query($sql, $data);

        $totalRecords = $this->count($start, $end, $customer);
        $invoices = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Invoice');

        return [
            'total_records' => $totalRecords,
            'total_pages' => ceil($totalRecords / $size),
            'num_records' => count($invoices),
            'content' => $invoices
        ];
    }
}