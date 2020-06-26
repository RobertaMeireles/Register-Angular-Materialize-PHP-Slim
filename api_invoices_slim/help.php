<?php

/*
-------------------------------------------------------------------------------------------
INICIANDO O PROJETO
Inciando o projeto metendo o composer init gerando o composer.json

Inserindo o slim no composer. O professor fez manualmente digitando o texto abaixo no 
composer.json e em seguida indicando no cdm para atualizar o composer (composer install)
mas pode ser feito essa instalação do slim por linha de comando.
e no composer.json alterando
    "require": {
        "slim/slim": "3.12.1"
    }

A biblioteca slim  foi instalada e o compose.json e vendor instalado.

--------------------------------------------------------------------------------------------
Adicionar a biblioteca de token: composer require emarref/jwt
banco de dados exercicios.
acesso cadastrado:
{
	"username": "roberta@roberta.com",
	"password": "123456"
}

---------------------------------------------------------------------------------------------
CRIADO PASTAS
- criado a pasta public com index.php onde sera a instancia da app slim que inicia a aplicaçao (x)
- criado a pasta configs onde ficará as configuraçoes da base de dados onde retorna o array de settings do slim (x)

- criado a pasta src:


** CORE/DATABASE (x)
- DATABASE.PHP 
Onde será a classe generica que vai comunicar com a base de dados para trazer as informaçoes do CRUD


**INVOICESAPP
- ROUTES.PHP (x)
Rota da aplicação


- INVOICEAPP.PHP (x)
Classe inicial da aplicaçao (é chamada no index) essa classe tem o objetivo de instanciar o PDO e a classe de rota


- CONTROLLES
Controladores da aplicação
Customers para controlar os clientes
Customerinvoices para controlar as notas fiscais
CustomerInvoicesController para controlar a nota fiscal e o produto
ProductsController para controlar os produtos
AuthController para criar a autenticaçao


- MODELS
Modelos da aplicaçao

- REPOSITORIES
Um padrao de desenho que é uma classe que gera todas as operaçoes para uma determinada entidade. 
por exemplo: se quero todos os clientes, se quero guardar um cliente, deletar um cliente e ect..

-SECURITY
Realizar a criação de token.
quando utiliza o metodo de guardar o token dentro da base de dados:
Onde a base de dados, geravar um token aleatorio e guardava na base de dados e la a verificaçao era feita com base nesse token
a base de dados é responsavel em guardar esse token e nos pedidos irá enviar no header da autorizaçao
o que sera feito a seguir é criar uma verificaçao antes do pedido, antes do controlador ser execultado
para verificar se eu recebo token e se receber token se o token esta valido, o slim permite 
criar isso com uma coisa que chama mido, ou seja, vou criar uma classe que vai dizer no grupos/rotas
que antes da rota ser execultada tem que passar pelo aquele middleware que esta dentro de security.


    /*Nota acessar a api com perfis diferentes, quando busca o utilizador conforme o metodo 
    userByToken no authRepository poderia buscar todos os perfis e depois iria verificar se aquele 
    metodo pode ou nao ser acessado por aquele perfil. Ou pode fazer varios middlewares para diferentes perfis
    e adcione nas rota em que precisa

Quando utiliza jwt

-----------------------------------------------------------------------------------------------------
Inserido os nameespaces na aplicação no autoload do composer.json:
    "autoload": {
        "psr-4":{
            "Core\\": "src/Core",
            "InvoicesApp\\": "src/InvoicesApp"
        }

rodar o comando abaixo para atualizar a alteração:
composer dump-autoload

-----------------------------------------------------------------------------------------------------
RESUMO 
- Index instancia a app que instancia o PDO e a classe rota.
- a rota chama o controlador e o metodo que o usuário deseja.
- que envia dados para o repositorio acessar a base de dados.


-----------------------------------------------------------------------------------------------
COLOCAR O SLIM PARA FUNCIONAR NO BROWSER
no cmd, na pasta do projeto public : php -S localhost:8080
mas na realizada para testar a api nao será utilizada 

-------------------------------------------------------------------------------------------------






*/