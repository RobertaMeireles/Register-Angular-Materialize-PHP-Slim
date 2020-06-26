<?php namespace InvoicesApp\Controllers;

//CONTROLADOR PARA CRIAR UM PRODUCT

//CLASSES QUE SAO UTILIZADAS
use Exception;
use InvoicesApp\Repositories\ProductsRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductsController {
    //ATRIBUTOS
    private $database;
    private $productsRepository;

    //CONTROLADOR
    public function __construct(Container $container)
    {
        $this->database = $container['database'];
        $this->productsRepository = new ProductsRepository($this->database);
    }

     //METODO PARA BUSCAR TODOS OS PRODUTOS
    public function index(Request $request, Response $response) {
        $page = $request->getQueryParam('page', 0);
        $size = $request->getQueryParam('size', 5);
        $filter = $request->getQueryParam('filter', '');

        $products = $this->productsRepository->all($page, $size, $filter);

        return $response
            ->withStatus(200)
            ->withJson($products);
    }
    
    //METODO PARA MOSTRAR UM PRODUTO POR ID
    public function show(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $product = $this->productsRepository->byId($id);
        
        if ($product) {
            return $response
                ->withStatus(200)
                ->withJson($product);
        }

        return $response
            ->withStatus(404)
            ->withJson([
                'code' => 404,
                'message' => sprintf('Product with id %d not found!', $id)
            ]);
    }
    
    //METODO PARA CRIAR UM PRODUTOS
    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody();

        try {
            $product = $this->productsRepository->create($data);
            return $response
                ->withStatus(201)
                ->withJson($product);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }

    }
    
    //METODO PARA FAZER UPDATE DE UM PRODUTO
    public function update(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $product = $this->productsRepository->byId($id);

        if (!$product) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code' => 404,
                    'message' => sprintf('Product with id %d not found!', $id)
                ]);
        }

        $data = $request->getParsedBody();

        try {
            $product = $this->productsRepository->update($id, $data);
            return $response
                ->withStatus(200)
                ->withJson($product);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }
    }
    
    //METODO PARA REMOVER UM PRODUTO
    public function remove(Request $request, Response $response, array $args) {
        $id = (int)$args['id'];
        $product = $this->productsRepository->byId($id);

        if (!$product) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code' => 404,
                    'message' => sprintf('Product with id %d not found!', $id)
                ]);
        }

        try {
            $this->productsRepository->remove($id);
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