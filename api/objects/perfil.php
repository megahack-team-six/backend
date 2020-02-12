<?php
class Perfil {

    private $conn;
    public $id;
    public $descricao;
    public $servicos;

    public function __construct($db) {
        $this->conn = $db;
    }
    function create() {
        $query = "INSERT INTO perfil SET descricao=:descricao, servicos=:servicos";

        $stmt = $this->conn->prepare($query);
        $this->descricao=htmlspecialchars(strip_tags($this->descricao));
        $this->servicos = json_encode(array());

        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":servicos", $this->servicos);

        if ($stmt->execute()) return $this->conn->lastInsertId();
        return $this->conn->errorInfo();
    }
    function update() {
        $query = "UPDATE perfil SET descricao=:descricao, servicos =:servicos WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->descricao=htmlspecialchars(strip_tags($this->descricao));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":servicos", $this->servicos);

        if ($stmt->execute()) return $this->id;
        return $this->conn->errorInfo();
    }

    function listar() {
        $query = "SELECT * FROM perfil";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getTodosServicos() {
        $query = "SELECT * FROM servicos ORDER BY servico, acao";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getServico($servicos, $servicoId) {
        $ids = array_column($servicos, 'id');
        $key = array_search($servicoId, $ids);
        return  $servicos[$key];
    }

    function getRule($servicos, $servicoId) {
        $servico = $this->getServico($servicos, $servicoId);
        if ($servico['acao'])
            return "${servico['servico']}.${servico['acao']}";
        return $servico['servico'];
    }

    function montaPerfil($servicos, $row) {
        $servicoIds = json_decode($row['servicos']);
        $rules = array();
        if ($servicoIds && count($servicoIds) > 0) {
            foreach ($servicoIds as $key => $servicoId) {
                array_push($rules, $this->getRule($servicos, $servicoId));
            }
        }
        $row['rules'] = $rules;
        unset($row['servicos']);
        return $row;
    }

    public function getRulesByUser($perfilIds) {
        $stmt = $this->listar();
        $servicos = $this->getTodosServicos();
        $list = array();
        if ($perfilIds && count($perfilIds) > 0) {
            foreach ($perfilIds as $key => $id) {
                $this->id = $id;
                $row = $this->readOne();
                $perfil = $this->montaPerfil($servicos, $row);
                foreach ($perfil['rules'] as $key => $rule) {
                    array_push($list, $rule);
                }
            }
        }
        return $list;
    }

    function readOne() {
        $query = "SELECT * FROM perfil WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getTodosPerfil() {
        $stmt = $this->listar();
        $servicos = $this->getTodosServicos();

        $list=array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($list, $this->montaPerfil($servicos, $row));
        }
        return $list;
    }
}
?>