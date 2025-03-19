<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class RotaController extends Controller{
    
    public function rota(){
        $get = ['pedidos','produtos','clientes'];
        $getId = ['pedidos/(:num)','produtos(:num)','clientes(:num)'];
        $getFiltro = ['pedidos/?limit=2&page=2','produtos/?limit=2&page=2','clientes/?limit=2&page=2'];

        $post = ['pedidos','produtos','clientes'];

        $put = ['pedidos','produtos','clientes'];

        $delete = ['pedidos','produtos','clientes'];

        $postPublico = ['registrar', 'login'];

        $getPublico = ['user','user'];

        return view('rotas_disponiveis',[
            'rotasGet'          => $get,
            'rotasGetId'        => $getId,
            'rotasGetFiltro'    => $getFiltro,
            'post'              => $post,
            'put'               => $put,
            'delete'            => $delete,
            'postPublico'       => $postPublico,
            'getPublico'        => $getPublico,
        ]);
    }
}