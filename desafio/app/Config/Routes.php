<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Para Pedidos

$routes->get('/', 'Home::index');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('clientes', 'ClienteController::index'); 

    $routes->get('clientes/(:num)', 'ClienteController::show/$1');
    $routes->get('clientes/(:any)', 'ClienteController::show');

    $routes->put('clientes/(:num)', 'ClienteController::update/$1');
    $routes->put('clientes', 'ClienteController::update');

    $routes->post('clientes', 'ClienteController::create');
    $routes->post('/clientes/(:any)', 'ClienteController::create');

    $routes->delete('clientes/(:num)', 'ClienteController::delete/$1');
    $routes->delete('clientes', 'ClienteController::delete');


    $routes->get('produtos', 'ProdutoController::index'); 

    $routes->get('produtos/(:num)', 'ProdutoController::show/$1');
    $routes->get('produtos/(:any)', 'ProdutoController::show');

    $routes->post('produtos', 'ProdutoController::create');

    $routes->put('produtos/(:num)', 'ProdutoController::update/$1');
    $routes->put('produtos', 'ProdutoController::update');

    $routes->delete('produtos/(:num)', 'ProdutoController::delete/$1');
    $routes->delete('produtos', 'ProdutoController::delete');


    $routes->get('pedidos', 'PedidoController::index'); 
    $routes->get('pedidos/(:num)', 'PedidoController::show/$1');
    $routes->get('pedidos/(:any)', 'PedidoController::show');

    $routes->post('pedidos','PedidoController::create');

    $routes->put('pedidos/(:num)','PedidoController::update/$1');
    $routes->put('pedidos', 'PedidoController::update');

    $routes->delete('pedidos/(:num)','PedidoController::delete/$1');
    $routes->delete('pedidos/','PedidoController::delete');


});

// Rotas públicas (não precisam de autenticação)
$routes->post('registrar', 'AuthController::registrar'); 
$routes->post('login', 'AuthController::login');
$routes->get('user', 'AuthController::getUser');

