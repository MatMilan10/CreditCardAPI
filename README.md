# CreditCardAPI
API REST de pagamentos de cartão de crédito

**Pré requisitos:** Docker, Docker Compose, Composer, Node.JS

**Images:** Memcached, MySQL, PHPMyAdmin, Nginx, PHP-fpm.

**Composer:** Slim, PHPUnit.

**Passo a Passo**
O primeiro passo é instalar os pré requisitos na maquina para conseguir rodar a aplicação. Logo após a instalação seguir o passo a passo abaixo.

```
# Criar e levantar os containers
docker-compose up -d

# Pode verificar se o container esta rodando.
docker-compose ps

# Instalação dos Frameworks via Composer
composer install

# Instalação da SDK do Mercado Pago
npm install @mercadopago/sdk-react

# Adicionar a public-key para se conectar ao mercado pago
import { initMercadoPago } from '@mercadopago/sdk-react'
initMercadoPago('YOUR_PUBLIC_KEY');
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
```

**SDK Mercado Pago**
```
Qualquer duvida referente ao SDK do Mercado Pago, consultar a documentação.
https://github.com/mercadopago/sdk-react
```