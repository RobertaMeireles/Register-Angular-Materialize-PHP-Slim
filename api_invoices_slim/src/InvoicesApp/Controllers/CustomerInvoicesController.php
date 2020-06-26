<?php namespace InvoicesApp\Controllers;

//CONTROLADOR PARA BUSCAR TODAS AS FATURAS DE UM DETERMINADO CLIENTE

//CLASSES QUE SAO UTILIZADAS
use Exception;
use InvoicesApp\Repositories\InvoicesRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class CustomerInvoicesController {

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

    //METODO PARA BUSCAR AS FATURAS DE TODOS OS PRODUTOS
    /*Necessario receber um container como parametro.
    Esse $container['database'] é a instancia da database criada na classe invoices, setupDependencies
    instanciar o repositorio CustomersRepository passando o conteudo de database*/
    public function index(Request $request, Response $response, array $args) {
        $page = $request->getQueryParam('page', 0);
        $size = $request->getQueryParam('size', 5);
        $start = $request->getQueryParam('start', null);
        $end = $request->getQueryParam('end', null);

        $customer = (int)$args['id'];

        $invoices = $this->invoicesRepository->getInvoicesByCustomer($customer, $page, $size, $start, $end);

        return $response
            ->withStatus(200)
            ->withJson($invoices);
    }
    
    
}