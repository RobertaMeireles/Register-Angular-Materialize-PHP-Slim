<?php namespace InvoicesApp\Controllers;

//CONTROLADOR DE AUTENTICAÇÃO

//CLASSES UTILIZADAS
use Exception;
use InvoicesApp\Repositories\AuthRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController {

    //ATRIBUTOS
    private $database;
    private $authRepository;

    //CONTRUTOR
    public function __construct(Container $container)
    {
        $this->database = $container['database'];
        $this->authRepository = new AuthRepository($this->database);
    }

    /*METODO PARA LOGIN
    Buscando o username e password.
    chamando o metdodo attemptLogin do repositorio passando o username e password para buscar o token
    */
    public function login(Request $request, Response $response) {
        $username = $request->getParsedBodyParam('username', null);
        $password = $request->getParsedBodyParam('password', null);

        try {
            $token = $this->authRepository->attemptLogin($username, $password);

            if ($token) {
                return $response
                    ->withStatus(200)
                    ->withJson([
                        'token' => $token
                    ]);
            }

            return $response
                ->withStatus(401)
                ->withJson([
                    'code' => 401,
                    'message' => 'Bad credentials'
                ]);
        } catch (Exception $e) {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code' => 400,
                    'message' => $e->getMessage()
                ]);
        }
    }

    /*METODO PARA DAR O PERFIL DE UM UTILIZADOR
    Vai ao request buscar o user user = $request->getAttribute('user') retornando a response com o user;
    */
    public function profile(Request $request, Response $response) {
        $user = $request->getAttribute('user'); 
        return $response->withStatus(200)->withJson($user);
    }
    
    //METODO PARA LOGOUT
    public function logout(Request $request, Response $response) {
        $token = $request->getAttribute('token');
        $this->authRepository->removeToken($token);
        return $response->withStatus(204);
    }
}