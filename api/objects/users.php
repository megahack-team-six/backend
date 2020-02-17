<?php
class User {

    private $conn;
    public $id;
    public $nome;
    public $email;
    public $password;
    public $rules;
    public $perfil;
    public $status;
    public $id_atendente;
    public $id_unidade;
    public $alterar_senha;

    public function __construct($db) {
        $this->conn = $db;
    }

    function create() {

        $query = "INSERT INTO users SET nome=:nome,email=:email,perfil=:perfil,password=:password,status=1";

        $stmt = $this->conn->prepare($query);
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':perfil', $this->perfil);
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) return $this->conn->lastInsertId();
        return false;
    }

    function update() {

        $query = "UPDATE users SET nome=:nome,email=:email,password=:password WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        if ($stmt->execute()) return true;
        return false;
    }

    function updatesenha() {

        $query = "UPDATE users SET password=:password,alterar_senha=:alterar_senha WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':alterar_senha', $this->alterar_senha);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) return true;
        return false;
    }

    function desativar() {

        $query = "UPDATE users SET status=0 WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) return true;
        return false;
    }

    function ativar() {
        $query = "UPDATE users SET status=1 WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) return true;
        return false;
    }

    function emailExists() {

        $query = "SELECT id,nome,password,perfil,status,alterar_senha FROM users WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num>0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->alterar_senha = $row['alterar_senha'];
            $this->password = $row['password'];
            $this->status = $row['status'];
            $this->perfil = json_decode($row['perfil']);
            return true;
        }
        return false;
    }

    public function readOne() {

        $query = "SELECT id,nome,email,criado,modificado,perfil,status FROM users WHERE id=? LIMIT 0,1";

        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->nome = $row['nome'];
        $this->email = $row['email'];
        $this->criado = $row['criado'];
        $this->modificado = $row['modificado'];
        $this->status = $row['status'];
        $this->perfil = json_decode($row['perfil']);
    }

    public function listar() {

        $query = "SELECT id,nome,email,criado,modificado FROM users ORDER BY nome DESC";
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        return $stmt;
    }

    function user_by_atendente() {

        $query = "INSERT INTO users SET nome=:nome,email=:email,password=:password,alterar_senha=:alterar_senha";

        $stmt = $this->conn->prepare($query);
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':alterar_senha', $this->alterar_senha);

        if ($stmt->execute()) return $this->conn->lastInsertId();
        return false;
    }

    function deletarUser() {
        $query = "DELETE FROM users WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) return true;
        return false;
    }
}