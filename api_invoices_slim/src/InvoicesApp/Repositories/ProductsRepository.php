<?php namespace InvoicesApp\Repositories;

//REPOSITORIO PARA OS PRODUTOS

use Core\Database\Database;
use Exception;
use InvoicesApp\Models\Customer;
use InvoicesApp\Models\Product;
use PDO;

class ProductsRepository {

    //ATRIBUTO
    private $database;

    //CONSTRUTOR
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    //METODO PARA BUSCAR TODOS OS PRODUTOS NA BASE DE DADOS COM A CATEGORIA
    public function all(int $page = 0, int $size = 5, string $filter = '') {
        $offset = $page * $size;

        $sql = "SELECT
                    p.*,
                    c.designacao AS categoria
                FROM produtos p
                LEFT JOIN categorias c ON c.id = p.id_categoria
                WHERE
                    p.designacao LIKE :filter OR
                    p.descricao LIKE :filter OR
                    c.designacao LIKE :filter
                LIMIT $offset, $size";

        $stmt = $this->database->query($sql, [
            'filter' => '%' . $filter . '%'
        ]);
        
        $totalRecords = $this->count($filter);
        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'InvoicesApp\Models\Product');

        return [
            'total_records' => $totalRecords,
            'total_pages' => ceil($totalRecords / $size),
            'num_records' => count($customers),
            'content' => $customers
        ];
    }

    //METODO PARA BUSCAR UM ESPECIFICO PRODUTO NA BASE DE DADOS PELO ID
    //Nota se quiser buscar dados de 2 tabelas com o mesmo nome precisa indicar qual tabela, que é o caso do where
    public function byId(int $id): ?Product {
        $sql = "SELECT
                    p.*,
                    c.designacao AS categoria
                FROM produtos p
                LEFT JOIN categorias c ON c.id = p.id_categoria
                WHERE p.id = :id 
                LIMIT 1";
        $stmt = $this->database->query($sql, [
            'id' => $id
        ]);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'InvoicesApp\Models\Product');
        $product = $stmt->fetch();
        return $product ? $product : null;
    }

    public function create(array $data): ?Product {

        if (!isset($data['designacao'])) {
            throw new Exception('Name is required');
        }
        if (!isset($data['descricao'])) {
            throw new Exception('Description is required');
        }
        if (!isset($data['preco'])) {
            throw new Exception('Price is required');
        }
        if (!isset($data['id_categoria'])) {
            throw new Exception('Category is required');
        }
        
        $category = $this->getCategory($data['id_categoria']);

        if ($category) {
            $result = $this->database->insert('produtos', $data);

            $data['id'] = $result->lastInsertId;
            $data['categoria'] = $category->designacao;

            return new Product($data);
        }

        throw new Exception('Category not found!');
    }

    //METODO PARA FAZER UPDATE DO PRODUTO
    public function update(int $id, array $data): ?Product {
        $this->database->update('produtos', $data, 'id = :id', [
            'id' => $id
        ]);

        return $this->byId($id);
    }

    //METODO PARA REMOVER UM PRODUTO
    public function remove(int $id): bool {
        $stmt = $this->database->delete('produtos', 'id = :id', [
            'id' => $id
        ]);
        return $stmt->rowCount() > 0;
    }

    //METODO PARA BUSCAR UMA CATEGORIA POR ID
    public function getCategory(int $id) {
        return $this->database->byId('categorias', $id);
    }

    //METODO PARA FAZER O COUNT DO TOTAL DE PRODUTOS
    public function count(string $filter = '') {
        $sql = "SELECT
                    COUNT(*) AS total
                FROM produtos p
                LEFT JOIN categorias c ON c.id = p.id_categoria
                WHERE
                    p.designacao LIKE :filter OR
                    p.descricao LIKE :filter OR
                    c.designacao LIKE :filter";
        
        $stmt = $this->database->query($sql, [
            'filter' => '%' . $filter . '%'
        ]);
        $rs = $stmt->fetch();

        return $rs ? (int)$rs->total : null;
    }
}