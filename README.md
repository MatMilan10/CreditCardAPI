# CreditCardAPI
API REST de pagamentos de cartão de crédito

**Pré requisitos:** Docker, Docker Compose, Composer, Node.JS

**Images:** Memcached, MySQL, PHPMyAdmin, Nginx, PHP-fpm.

**Composer:** Slim, PHPUnit.

**Passo a Passo**
O primeiro passo é instalar os pré requisitos na maquina para conseguir rodar a aplicação. Logo após a instalação seguir o passo a passo abaixo.

**Back-end**
```
# Ir até o diretório
cd backend

# Criar e levantar os containers
docker-compose up -d

# Pode verificar se o container esta rodando.
docker-compose ps

# Instalação dos Frameworks via Composer
composer install
```

**Front-end**
```
# Ir até o diretório (Caso tenha que voltar a pasta, utilizar o comando -> cd ..)
cd frontend

# Instalação da SDK do Mercado Pago
npm install @mercadopago/sdk-react

Qualquer duvida referente ao SDK do Mercado Pago, consultar a documentação.
https://github.com/mercadopago/sdk-react
```


**Acesso localhost**
```
http://localhost:8000
```

**Acesso phpMyAdmin**
```
http://localhost:8080

servidor: mysql
user: root
senha: root