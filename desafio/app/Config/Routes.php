<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'RotaErro::rota');


// TODOS OS DADOS


$routes->group('clientes',['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ClienteController::index');  // GET todos clientes
    $routes->get('(:num)', 'ClienteController::show/$1'); // GET cliente por ID
    $routes->post('', 'ClienteController::create'); // POST novo cliente
    $routes->put('(:num)', 'ClienteController::update/$1'); // PUT atualizar cliente
    $routes->delete('(:num)', 'ClienteController::delete/$1'); // DELETE cliente

    $routes->get('(:any)', 'RotaController::rota');
    $routes->put('', 'RotaController::rota'); 
    $routes->put('(:any)', 'RotaController::rota');
    $routes->post('(:any)', 'RotaController::rota'); 
    $routes->delete('', 'RotaController::rota'); 
    $routes->delete('(:any)','RotaController::rota');
    
});

$routes->group('produtos',['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdutoController::index');  // GET todos clientes
    $routes->get('(:num)', 'ProdutoController::show/$1'); // GET cliente por ID
    $routes->post('', 'ProdutoController::create'); // POST novo cliente
    $routes->put('(:num)', 'ProdutoController::update/$1'); // PUT atualizar cliente
    $routes->delete('(:num)', 'ProdutoController::delete/$1'); // DELETE cliente

    $routes->get('(:any)', 'RotaController::rota');
    $routes->put('', 'RotaController::rota'); 
    $routes->put('(:any)', 'RotaController::rota');
    $routes->post('(:any)', 'RotaController::rota'); 
    $routes->delete('', 'RotaController::rota'); 
    $routes->delete('(:any)','RotaController::rota');
});

$routes->group('pedidos',['filter' => 'auth'], function ($routes) {
    $routes->get('', 'PedidoController::index');  // GET todos clientes
    $routes->get('(:num)', 'PedidoController::show/$1'); // GET cliente por ID
    $routes->post('', 'PedidoController::create'); // POST novo cliente
    $routes->put('(:num)', 'PedidoController::update/$1'); // PUT atualizar cliente
    $routes->delete('(:num)', 'PedidoController::delete/$1'); // DELETE cliente

    $routes->get('(:any)', 'RotaController::rota');
    $routes->put('', 'RotaController::rota'); 
    $routes->put('(:any)', 'RotaController::rota');
    $routes->post('(:any)', 'RotaController::rota'); 
    $routes->delete('', 'RotaController::rota'); 
    $routes->delete('(:any)','RotaController::rota');
});

$routes->post('registrar', 'AuthController::registrar'); 
$routes->post('login', 'AuthController::login');
$routes->get('user', 'AuthController::getUser');
$routes->set404Override('App\Controllers\RotaController::rota');
