<?php
function conn() {
    $mysql_host = 'localhost';
    $mysql_username = 'root';
    $mysql_password = '';
    $mysql_database = 'judbrass';
    $con = @new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

    if ($con->connect_errno) {
        $con = @new mysqli($mysql_host, $mysql_username, $mysql_password);
        $con->query("CREATE DATABASE IF NOT EXISTS `judbrass` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
        $con = @new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
    }
    return $con;
}

$entrada = isset($_GET['operacao']) ? $_GET['operacao'] : '';

switch ($entrada) {
    case 'full':
        full();
        basico();
        funcoes();
        echo '<script>
            alert("Estrutura básicos criada com sucesso");
            window.history.go(-1);
        </script>';
    break;
    case 'update':
        update();
        funcoes();
        echo '<script>
            alert("Estrutura atualizada com sucesso");
            window.history.go(-1);
        </script>';
    break;
    case 'basico':
        basico();
        echo '<script>
            alert("dados básicos sicronizado com sucesso");
            window.history.go(-1);
        </script>';
    break;
}

function full() {
    $con = conn();
    $templine = '';
    $lines = file("./sql/full.sql");
    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;
        $templine .= $line;

        if (substr(trim($line), -1, 1) == ';') {
            try{
                $con->query($templine);
            } catch (mysqli_sql_exception $e){}
            $templine = '';
        }
    }
    echo 'Operação realizar com Sucesso';
    $con->close();
}

function update() {
    $con = conn();
    $templine = '';
    $lines = file_get_contents("./sql/update.sql");
    if (strtok($lines,"\r\n")) {
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $con->query($templine);
                $templine = '';
            }
        }
        print_r('Operação realizar com Sucesso <br>');
    } else print_r('Arquivo sql vazio! <br>');
    $con->close();
}

function basico() {
    $con = conn();
    foreach (glob("./sql/basico/*.sql") as $sql) {
        $templine = '';
        $lines = file($sql);
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            $templine .= $line;

            if (substr(trim($line), -1, 1) == ';') {
                try{
                    $con->query($templine);
                    $templine = '';
                } catch (Exception $e) {}
            }
        }
    }
    print_r('Operação realizar com Sucesso <br>');
    $con->close();
}


function funcoes() {
    $con = conn();
    foreach (glob("./sql/funcoes/*.sql") as $sql) {
        $file = file_get_contents($sql);
        $con->query($file);
    }
    print_r('Operação realizar com Sucesso <br>');
    $con->close();
}