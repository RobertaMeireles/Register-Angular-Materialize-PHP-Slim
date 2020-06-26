<?php namespace InvoicesApp\Controllers;

//CONTROLADOR DAS ROTAS DO CUSTOMERS

//CLASSES QUE SAO UTILIZADAS
use Exception;
use InvoicesApp\Repositories\CustomersRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class CustomersController {

    //ATRIBUTOS
    private $database;
    private $customerRepository;

    //CONTROLADOR
    /*Necessario receber um container como parametro.
    Esse $container['database'] é a instancia da database criada na classe invoices, setupDependencies
    instanciar o repositorio CustomersRepository passando o conteudo de database   */
    public function __construct(Container $container)
    {
        $this->database = $container['database'];
        $this->customerRepository = new CustomersRepository($this->database);
    }



    //METODO PARA MOSTRAR TODOS OS CUSTOMERS
    /*
    - ir buscar os filtros possiveis que o usuário fizer:
    - pagina, onde ou sera o valor digitado pelo o usuario ou zero
    - o tamanho de cada item por pg que será o tamanho digitado pelo usuario ou 5
    - o filtro que o usuário deseja ou zero.
    - na variavel $customers envia informaçao para o repositorio, metodo all os valores $page, $size, $filter


    retorna para o o status 200 e o valor da variavel customers (em forma de json) constando o 
    pedido enviado para o repositorio. 
     */
    public function index(Request $request, Response $response) {
        $page = $request->getQueryParam('page', 0);
        $size = $request->getQueryParam('size', 5);
        $filter = $request->getQueryParam('filter', '');

        $customers = $this->customerRepository->all($page, $size, $filter);

        return $response
            ->withStatus(200)
            ->withJson($customers);
    }
    

    //METODO PARA MOSTRAR UM UNICO CUSTOMER
    /*array com os meus argumentos 
    - em id convertendo para int oque vai receber no parametro
    - onde envia o id para o metodo byId do repositorio tratar na base de dados.  
    - retorna para o o status 200 e o valor da variavel customers (em forma de json) constando o 
    pedido enviado para o repositorio.  */
    public function show(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $customer = $this->customerRepository->byId($id);
        
        if ($customer) {
            return $response
                ->withStatus(200)
                ->withJson($customer);
        }

        return $response
            ->withStatus(404)
            ->withJson([
                'code' => 404,
                'message' => sprintf('Customer with id %d not found!', $id)
            ]);
    }
    

    //METODO PARA CRIAR UM CUSTOMER
    /*Quando passo um metodo post para a rota store sera invocado esse metodo onde
    - Usando o getParsedBody buscar todos os dados que estao no body do request e colocar na variavel data 
    - passando os dados da variavel data para o metodo create do repositorio.
    retornando o status 201 e o valor da variavel data para o metodo create do repositorio*/
    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody();
        
        try {
            $customer = $this->customerRepository->create($data);
            return $response
                ->withStatus(201)
                ->withJson($customer);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage() //envia a msg de erro conforme recebida do metodo create do repositorio
                ]);
        }

    }
    

    //METODO PARA ATULIZAR UM UM CUSTOMER
    /*array $args com os meus argumentos~
    1 buscar o id na rota $id = (int)$args['id']; assim saber qual customer esta a fazer update
    2 passando ao metodo byId do repository o id que deseja alterar para verificar se o id existe
    e se nao existir responde com 
    'code' => 404,
    'message' => sprintf('Customer with id %d not found!', $id)

    caso existe buscar ao request o getParsedBody e colocar na variavel data
    onde há todos os dados do utilizador

    no try fazendo um update ao user ao enviar o id e o array data que vem do getParsedBody
    retorna um response com o status 200 de ok e o json de customer


    Caso contrario retorna um Exception $e com o codigo e aparesenta o erro 

    */
    public function update(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $customer = $this->customerRepository->byId($id);

        if (!$customer) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code' => 404,
                    'message' => sprintf('Customer with id %d not found!', $id)
                ]);
        }

        $data = $request->getParsedBody();

        try {
            $customer = $this->customerRepository->update($id, $data);
            return $response
                ->withStatus(200)
                ->withJson($customer);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }
    }
    

    //METODO PARA REMOVER UM CUSTOMER
    /*array $args com os meus argumentos
    1 buscar o id na rota $id = (int)$args['id']; assim saber qual customer deseja deletar
    2 passando ao metodo byId do repository o id que deseja deletar para verificar se o id existe
    e se nao existir responde com 
    'code' => 404,
    'message' => sprintf('Customer with id %d not found!', $id)

    Se existir

    no try fazendo chamando o metodo remove do repositorio passando o id que recebeu pelo get
    e uma resposta 204.
    
   Caso contrario retorna um Exception $e com o codigo e aparesenta o erro */
    public function remove(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $customer = $this->customerRepository->byId($id);

        if (!$customer) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code' => 404,
                    'message' => sprintf('Customer with id %d not found!', $id)
                ]);
        }

        try {
            $this->customerRepository->remove($id);
            return $response->withStatus(204);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }
    }
}