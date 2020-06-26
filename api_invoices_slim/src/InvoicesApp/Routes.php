<?php namespace InvoicesApp;

use InvoicesApp\Security\TokenMiddleware;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class Routes {

    private $slim;

    public function __construct(App $slim)
    {
        
        $this->slim = $slim;
        $this->setupRoutes();
    }

    public function setupRoutes() {
        $this->slim->get('/', function (Request $request, Response $response) {
            return $response->withJson([
                'version' => '1.0.0',
                'name' => 'Flag Invoices API'
            ]);
        });

        $this->slim->group('/api', function (App $app) {



            $app->group('/auth', function (App $app) {
                $app->post('/login', 'InvoicesApp\Controllers\AuthController:login');
                $app->get('/profile', 'InvoicesApp\Controllers\AuthController:profile')->add(new TokenMiddleware($app->getContainer()));
                $app->delete('/logout', 'InvoicesApp\Controllers\AuthController:logout')->add(new TokenMiddleware($app->getContainer()));
            });//a rota login qualquer pessoa pode entrar

            $app->group('/customers', function (App $app) {
                $app->get('', 'InvoicesApp\Controllers\CustomersController:index'); // Get all customers
                $app->get('/{id}', 'InvoicesApp\Controllers\CustomersController:show'); // Get custoner detail
                $app->post('', 'InvoicesApp\Controllers\CustomersController:store'); // Create customer
                $app->put('/{id}', 'InvoicesApp\Controllers\CustomersController:update'); // Update customer
                $app->delete('/{id}', 'InvoicesApp\Controllers\CustomersController:remove'); // Delete customer

                $app->get('/{id}/invoices', 'InvoicesApp\Controllers\CustomerInvoicesController:index');
            })->add(new TokenMiddleware($app->getContainer())); //todas as rotas desse grupo tem que ser autenticada pelo TokenMiddleware antes de entrar no controlador para verificar o token. Se nao enviar um token valido reponde por forbidan
           
            $app->group('/products', function (App $app) {
                $app->get('', 'InvoicesApp\Controllers\ProductsController:index'); 
                $app->get('/{id}', 'InvoicesApp\Controllers\ProductsController:show'); 
                $app->post('', 'InvoicesApp\Controllers\ProductsController:store'); 
                $app->put('/{id}', 'InvoicesApp\Controllers\ProductsController:update');
                $app->delete('/{id}', 'InvoicesApp\Controllers\ProductsController:remove');

                $app->get('/{id}/invoices', 'InvoicesApp\Controllers\CustomerInvoicesController:index');
            })->add(new TokenMiddleware($app->getContainer()));;
            $app->group('/invoices', function (App $app) {
                $app->get('', 'InvoicesApp\Controllers\InvoicesController:index');
                $app->get('/{id}', 'InvoicesApp\Controllers\InvoicesController:show'); 
                $app->post('', 'InvoicesApp\Controllers\InvoicesController:store');
                $app->delete('/{id}', 'InvoicesApp\Controllers\InvoicesController:remove');
            })->add(new TokenMiddleware($app->getContainer()));; 
        });
    }
}