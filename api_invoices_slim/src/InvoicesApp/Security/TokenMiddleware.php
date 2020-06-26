<?php namespace InvoicesApp\Security;


//CLASSE PARA GERAR OS TOKENS


use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Exception\VerificationException;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Verification\Context;
use InvoicesApp\Repositories\AuthRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class TokenMiddleware {

    //ATRIBUTOS
    private $database;

    //CONTRUTOR
    public function __construct(Container $container)
    {
        $this->database = $container['database'];   
    }

    //METODO PARA CRIAR MIDDLEWARE BASEADO EM CLASSE
    /*- Sempre que for invocada essa classe esse metodo sera disparado e se tudo ocorrer
    bem invoca a funçao next que é o controlador.
    . Os tokes vao sempre no header com o nome autorization retona e para busca-los
    utilize $header = $request->getHeader('Authorization'); onde ira retornar um array 
    - sempre por Bearer sendo assim busca o conteudo utilizando o getHeader 
    se existir, faz um  tr_replace juntando o Bearer com o token original (header).
    chamando em seguida o repositorio de autenticaçao */
    public function __invoke(Request $request, Response $response, $next)
    {
        $header = $request->getHeader('Authorization');
        if (isset($header[0])) {
            $token = str_replace('Bearer ', '', $header[0]);
    
            $repository = new AuthRepository($this->database);
            //GUARDANDO O TOKEN NA BASE DE DADOS
            // $user = $repository->userByToken($token); 
    
            // if ($user) { //SE MEU USER EXISTIR FAZER SET DO USER DENTRO REQUEST
            //     $request = $request->withAttribute('user', $user); 
            //     $request = $request->withAttribute('token', $token);
    
            //     return $next($request, $response); //CONTINUAR A EXECUÇAO CHAMA A PROXIMA FUNCAO DO CONTROLADOR
            // }

            //OKEN JWT usando a biblioteca 
            try {
                $jwt = new Jwt();
                $tokenInstance = $jwt->deserialize($token);

                $algorithm = new Hs256('VerySecretToken');
                $encryption = Factory::create($algorithm);
                $context = new Context($encryption);
                $context->setIssuer('api.invoicesapp.com');

                $jwt->verify($tokenInstance, $context);

                $userId = $tokenInstance->getPayload()->findClaimByName('user_id')->getValue();
                $user = $repository->userById($userId);

                $request = $request->withAttribute('user', $user);
                $request = $request->withAttribute('token', $token);

                return $next($request, $response); //Continuar a funçao chama a proxima funçao do controlador

            } catch (VerificationException $e) {
                return $response
                    ->withStatus(403)
                    ->withJson([
                        'code' => 403,
                        'message' => $e->getMessage()
                    ]);
            }
        }

        return $response
            ->withStatus(403)
            ->withJson([
                'code' => 403,
                'message' => 'Invalid token!'
            ]);
    }
}