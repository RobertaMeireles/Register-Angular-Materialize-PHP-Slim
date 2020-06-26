<?php namespace InvoicesApp;

//CLASSE INICIAL DA APLICAÇÃO (É CHAMADA NO INDEX) TEM O OBJETIVO DE INSTANCIAR O PDO

//CLASSES UTILIZADAS
use Core\Database\Database;
use Slim\App;

class InvoicesApp {

    //ATRIBUTOS
    private $slim;
    private $routes;

    //CONTRUTOR
    /*chama o settings, cria uma instancia da classe slim App passando como atributo o ficheiro 
    da settings.
    - setupDependencies chamar essa funçao dessa classe mesmo, criada logo abaixo
    - Cria uma instancia da classe Routes passando o objeto criado apartir de new App()  */
    public function __construct()
    {
        $settings = require __DIR__ . '/../../configs/settings.php';
        $this->slim = new App($settings);
        $this->setupDependencies();

        $this->routes = new Routes($this->slim);
    }









    //METODO PARA INSTANCIAR O PDO
    /*- primeiro precisa do container do slim atraves do getContainer  
    - utilizando o get para buscar o db do array settings.
    - extract a variavel que consta a informaçao do banco 
    em db (formando assim variaveis $host, $user, $pass, $name )
    - instanciar a classe database  */
    public function setupDependencies(): void {
        $container = $this->slim->getContainer();
        //UMA MANEIRA DE INSTANCIAR O PDO DA APLICAÇAO SEM NAO COLOCAR A CLASSE GENERICA DATABASE
        // $container['database'] = function ($c) {
        //     extract($c->get('settings')['db']);
            
        //     $dsn = sprintf("mysql:host=%s;dbname=%s", $host, $name);
            
        //     try {
        //         return new PDO($dsn, $user, $pass, [
        //             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        //         ]);
        //     } catch (PDOException $e) {
        //         die("Error connecting to database: " . $e->getMessage());
        //     }
        // };

        $container['database'] = function ($c) {
            $dbSettings = $c->get('settings')['db'];
            extract($dbSettings);
            
            return new Database($host, $user, $pass, $name);
        };
    }

    //METODO RUN DA CLASSE DO SLIM\APP
    public function start(): void {
        $this->slim->run();
    }
}


