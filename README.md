# Rinha de backend 2023

![Achou que eu estava brincando?](/assets/php.jpeg)

Github [lauroappelt](https://github.com/lauroappelt)

Linkedin [lauroappelt](https://www.linkedin.com/in/lauro-henrique-appelt/)

Link do projeto [https://github.com/lauroappelt/rinha-de-backend-2023](https://github.com/lauroappelt/rinha-de-backend-2023)

## Stack
* PHP Hyperf/Swoole
* Postgres
* Nginx - Load balancer
* Redis - Cache / Async queue

## para rodar o projeto

* docker-compose up -d
* docker exec rinha-backend-api-01 composer install 
* docker exec -d rinha-backend-api-01 php bin/hyperf.php start 
* docker exec -d rinha-backend-api-02 php bin/hyperf.php start