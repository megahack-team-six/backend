# Backend
Backend da aplicação.

# Instalação

## Com o docker

### 1. Execute o comando dentro da pasta raiz do projeto `/backend`

```
sudo docker run -p "8000:80" -v ${PWD}/:/app mattrayner/lamp:latest-1804
```

### 2. Espere ele terminar de baixar a imagem e inicializar o container, após isso ele irá gerar uma senha randomica para o banco de dados e mostrará no terminal dessa maneira:
```
You can now connect to this MySQL Server with YL02aBQeByqm
```

### 3. Copie a senha `YL02aBQeByqm` e edite o arquivo `/api/db/sync.php`
```
$mysql_username = 'admin';
$mysql_password = 'YL02aBQeByqm';
```

### 4. A api já está disponivel em:
```
http://localhost:8000/api
```

### 5. Você pode acessar o phpmyadmin na url:
```
http://localhost:8000/phpmyadmin
```

## Com a LAMP stack instalada na sua máquina

### 1. Instale o Mysql, Apache e PHP

### 2. Configure um usuário e senha para o mysql

### 3. Edite o arquivo `/api/db/sync.php`
```
$mysql_username = 'USUÁRIO';
$mysql_password = 'SENHA';
```

### 4. Adicione a pasta API no servidor PHP

## Utilização

### 1. Acesse a API pelo navegador

```
# Com o docker
Exemplo: http://localhost:8000/api

# Sem o docker
Exemplo: http://localhost/api
```

### 3. Clique em "Criar estrutura"

```
Vai criar toda a base de dados da aplicação, criando o usuário "ti@judbrass.com.br", com a senha "adm.judbrass"
```

<img src="./Index-API.PNG" alt="Index API" style="height: auto !important;width: auto !important;">

## Arquivo de importação para o INSOMNIA

```
* Login - "Insomnia_mega_hack.json"
```