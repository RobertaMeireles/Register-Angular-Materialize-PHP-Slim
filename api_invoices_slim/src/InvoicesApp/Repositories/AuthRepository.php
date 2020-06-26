<?php namespace InvoicesApp\Repositories;

//REPOSITORIO PARA O ACESSO

//CLASSES UTILIZADAS
use Core\Database\Database;
use DateTime;
use Emarref\Jwt\Algorithm\Hs256;
use Emarref\Jwt\Claim\Expiration;
use Emarref\Jwt\Claim\IssuedAt;
use Emarref\Jwt\Claim\Issuer;
use Emarref\Jwt\Claim\JwtId;
use Emarref\Jwt\Claim\NotBefore;
use Emarref\Jwt\Claim\PublicClaim;
use Emarref\Jwt\Encryption\Factory;
use Emarref\Jwt\Jwt;
use Emarref\Jwt\Token;
use Exception;

class AuthRepository {

    private $database;

    //CONSTRUTOR CHAMANDO A DATABASE
    public function __construct(Database $database) 
    {
        $this->database = $database;
    }

    //METODO PARA FAZER O LOGIN
    /*1 Se for nulo o user name e a password instancie um erro Exception
    2 criar uma query sql para ir a tabela utilizadores e buscando os dados do usuário utilizando o email como base
    3 enviando a query criada acima para o metodo query da classe base de dados com as informaçoes email e password
    4 fecth do usuario
    5 se o user for verdadeiro (se tiver user estou autenticado e por isso preciso gerar o token e enviar
    para o utilizador o token) se nao tiver retorna nulo
    para gerar o token é possivel seguir 2 abordagens:
    - Geravar um token aleatorio e guardava na base de dados e la a verificaçao era feita com base nesse token
    a base de dados é responsavel em guardar esse token e nos pedidos irá enviar no header da autorizaçao
    o que sera feito a seguir é criar uma verificaçao antes do pedido, antes do controlador ser execultado
    para verificar se eu recebo token e se receber token se o token esta valido, o slim permite 
    criar isso com uma coisa que chama mido, ou seja, vou criar uma classe que vai dizer no grupos/rotas
    que antes da rota ser execultada tem que passar pelo aquele middleware que esta dentro de security
    - Ou utilizando um JWT onde não é preciso de guardar o token na base de dados, sao gerador diretamente pela api
    
    */
    public function attemptLogin($username, $password) {
        if (is_null($username) || is_null($password)) {
            throw new Exception('Username and password is required!');
        }

        $sql = "SELECT * FROM utilizadores WHERE email = :email AND password = :password LIMIT 1";
        $stmt = $this->database->query($sql, [
            'email' => $username,
            'password' => hash('sha256', $password)
        ]);

        $user = $stmt->fetch();

        if ($user) {
            //GUARDANDO O TOKEN NA BASE DE DADOS
            // $token = base64_encode(md5(time() . '-' . uniqid()));

            // $date = new DateTime();
            // $date->setTimestamp(time() + 3600); //meu token expira daqui uma hora

            // $result = $this->database->insert('tokens', [
            //     'id_user' => $user->id,
            //     'token' => $token,
            //     'exp' => $date->format('Y-m-d H:i:s')
            // ]);

            // return $token;

            //TOKEN JWT usando a biblioteca 
            $token = new Token();

            $token->addClaim(new Expiration(new DateTime('12 hours')));
            $token->addClaim(new IssuedAt(new DateTime('now')));
            $token->addClaim(new Issuer('api.invoicesapp.com'));
            $token->addClaim(new JwtId($user->id));
            $token->addClaim(new NotBefore(new DateTime('now')));

            $token->addClaim(new PublicClaim('name', $user->nome));
            $token->addClaim(new PublicClaim('email', $user->email));
            $token->addClaim(new PublicClaim('user_id', $user->id));

            $jwt = new Jwt();
            $algorithm = new Hs256('VerySecretToken');
            $encryption = Factory::create($algorithm);

            return $jwt->serialize($token, $encryption);
        }

        return null;
    }    


    //METODO QUE VAI PROCURAR NA BASE DE DADOS UM UTILIZADOR PARA UM DETERMINADO TOKEN
    /*Nota acessar a api com perfis diferentes, quando busca o utilizador conforme abaixo
    poderia buscar todos os perfis e depois iria verificar se aquele metodo pode ou nao ser
    acessado por aquele perfil. Ou pode fazer varios middlewares para diferentes perfis
    e adcione nas rota em que precisa*/
    public function userByToken(string $token) {
        $sql = "SELECT 
                    u.id,
                    u.email,
                    u.nome
                FROM utilizadores u
                INNER JOIN tokens t ON u.id = t.id_user
                WHERE
                    t.token = :token";

        $stmt = $this->database->query($sql, [
            'token' => $token
        ]);

        return $stmt->fetch();
    }



    public function userById(int $id) {
        $sql = "SELECT 
                    u.id,
                    u.email,
                    u.nome
                FROM utilizadores u
                WHERE
                    u.id = :id";

        $stmt = $this->database->query($sql, [
            'id' => $id
        ]);

        return $stmt->fetch();
    }


    //METODO PARA REMOVER UM TOKEN
    public function removeToken(string $token) {
        $this->database->delete('tokens', 'token = :token', [
            'token' => $token
        ]);
    }
}