<?php

    use Slim\Routing\RouteCollectorProxy;

    $app->group('/rest', function (RouteCollectorProxy $group) {
        $group->post('/payments', 'App\Controllers\PagamentosController:criarPagamento');
        $group->get('/payments', 'App\Controllers\PagamentosController:listarPagamentos');
        $group->get('/payments/{id}', 'App\Controllers\PagamentosController:verPagamento');
        $group->patch('/payment/{id}', 'App\Controllers\PagamentosController:confirmarPagamento');
        $group->delete('/payments/{id}', 'App\Controllers\PagamentosController:cancelarPagamento');
    });