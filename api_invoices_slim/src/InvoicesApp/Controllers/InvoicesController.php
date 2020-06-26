<?php namespace InvoicesApp\Controllers;

//CONTROLADOR DAS ROTAS DAS NOTAS FISCAIS

//CLASSES QUE SAO UTILIZADAS
use Exception;
use InvoicesApp\Repositories\InvoicesRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;


class InvoicesController {

    //ATRIBUTOS
    private $database;
    private $invoicesRepository;

    //CONTROLADOR
    /*Necessario receber um container como parametro.
    Esse $container['database'] é a instancia da database criada na classe invoices, setupDependencies
    instanciar o repositorio CustomersRepository passando o conteudo de database   */
    public function __construct(Container $container)
    {
        $this->database = $container['database'];
        $this->invoicesRepository = new InvoicesRepository($this->database);
    }

    //METODO PARA MOSTRAR TODOS AS INVOICES
    /*
    - ir buscar os filtros possiveis que o usuário fizer:
    - pagina, onde ou sera o valor digitado pelo o usuario ou zero
    - o tamanho de cada item por pg que será o tamanho digitado pelo usuario ou 5
    - o filtro de inicio da data emissao da nf, se nao passar é null
    - o filtro do fim da data emissao da nf, se nao passar é null
    - na variavel $invoices envia informaçao para o repositorio, metodo all os valores $page, $size, $start, $end

    retorna para o o status 200 e o valor da variavel customers (em forma de json) constando o 
    pedido enviado para o repositorio. 
     */
    public function index(Request $request, Response $response) {
        $page = $request->getQueryParam('page', 0);
        $size = $request->getQueryParam('size', 5);
        $start = $request->getQueryParam('start', null);
        $end = $request->getQueryParam('end', null);

        $invoices = $this->invoicesRepository->all($page, $size, $start, $end);

        return $response
            ->withStatus(200)
            ->withJson($invoices);
    }
    
    //METODO PARA MOSTRAR UMA UNICA INVOICE PELO ID
    /*array com os meus argumentos 
    - em id convertendo para int oque vai receber no parametro
    - onde envia o id para o metodo byId do repositorio tratar na base de dados.  
    - retorna para o o status 200 e o valor da variavel customers (em forma de json) constando o 
    pedido enviado para o repositorio.  */
    public function show(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];

        $invoice = $this->invoicesRepository->byId($id);

        if ($invoice) {
            return $response
                ->withStatus(200)
                ->withJson($invoice);
        }

        return $response
            ->withStatus(404)
            ->withJson([
                'code' => 404,
                'message'=> sprintf('Invoice with id %d not found!', $id)
            ]);
    }
    
    //METODO PARA CRIAR UMA INVOICE
    /*Quando passo um metodo post para a rota store sera invocado esse metodo onde
    - Usando o getParsedBody buscar todos os dados que estao no body do request e colocar na variavel data 
    - passando os dados da variavel data para o metodo create do repositorio.
    retornando o status 201 e o valor da variavel data para o metodo create do repositorio*/
    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody();

        try {
            $invoice = $this->invoicesRepository->create($data['id_cliente'], $data['products']);
            return $response
                ->withStatus(201)
                ->withJson($invoice);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }

    }
    
    //METODO PARA ATULIZAR UM UM INVOICED
    /*array $args com os meus argumentos
    Retorna o status 501*/
    public function update(Request $request, Response $response, array $args) {
        return $response->withStatus(501);
    }
    

    //METODO PARA REMOVER UMA INVOICED
    /*array $args com os meus argumentos
    1 buscar o id na rota $id = (int)$args['id']; assim saber qual customer deseja alterar colocando o status 1
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
        $invoice = $this->invoicesRepository->byId($id);

        if (!$invoice) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code' => 404,
                    'message'=> sprintf('Invoice with id %d not found!', $id)
                ]);
        }

        try {
            $this->invoicesRepository->remove($id);
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