<?php

namespace Routes;

use Routes\Route as Rota;
use Controllers\Constants as consts;
//// FUNÇÕES ////
function ValidarAll()
{
    if (ValidarRef()) return true;
    $user = consts::getAcesso();
    $username = NULL;
    $password = NULL;
    if (isset($_SERVER['PHP_AUTH_USER'])) {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if ($username == NULL)  return false;
        if ($username == $user->usuario && $password == $user->senha) {
            return true;
        }
    }
    return false;
}

function ValidarRef()
{
    $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
    if (empty($ref)) return false;
    $array = explode(".", $ref);
    if ($array[1] === "appmania") return true;
    return false;
}

function ValidarApi()
{
    $user = consts::getAcesso();
    $username = NULL;
    $password = NULL;
    if (isset($_SERVER['PHP_AUTH_USER'])) {
        $username = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];
        if ($username == NULL)  return false;
        if ($username == $user->usuario && $password == $user->senha) {
            return true;
        }
    }

    return false;
}
/// GET ///
//ControllerView
Rota::get(['set' => '/', 'as' => 'index'], 'ControllerView@index');
Rota::get(['set' => '/contato', 'as' => 'index'], 'ControllerView@contato');
Rota::get(['set' => '/sobre', 'as' => 'index'], 'ControllerView@sobre');
Rota::get(['set' => '/carrinho', 'as' => 'carrinho.index'], 'ControllerView@carrinho');
Rota::get(['set' => '/pagamento', 'as' => 'pagamento.index'], 'ControllerView@pagamento');
Rota::get(['set' => '/privacidade', 'as' => 'privacidade.index'], 'ControllerView@privacidade');
Rota::get(['set' => '/produto/{slug}', 'as' => 'index.post'], 'ControllerView@getPost');
Rota::get(['set' => '/categoria/{slug}', 'as' => 'index.post'], 'ControllerView@getPostbyCat');
Rota::post(['set' => '/pagamento', 'as' => 'pagamento.index'], 'ControllerView@criarPagamento');

//ControllerCarrinho
Rota::get(['set' => '/carrinho/itens', 'as' => 'carrinho.itens'], 'ControllerCarrinho@getCarrinho');
Rota::post(['set' => '/carrinho/remove', 'as' => 'carrinho.remove'], 'ControllerCarrinho@removeCarrinhoItem');
Rota::post(['set' => '/carrinho/add', 'as' => 'carrinho.add'], 'ControllerCarrinho@addCarrinho');

//Outros 
Rota::get(['set' => '/start', 'as' => 'index'], 'ControleDados@Start');
Rota::post(['set' => '/constant', 'as' => 'config.constant'], 'ControleDados@setConstants');


///REFERENCIA///
if (ValidarRef()) :
    Rota::get(['set' => '/produtos', 'as' => 'produtos.index'], 'ControleDados@Produtos');
    Rota::get(['set' => '/produtos/s/{palavra}', 'as' => 'produtos.busca'], 'ControleDados@BuscaProdutos');
    Rota::get(['set' => '/produtos/cat/{id}', 'as' => 'produtos.bycat'], 'ControleDados@getProdutosbyCat');
    Rota::post(['set' => '/add-pedido/{id}', 'as' => 'pedido.add'], 'ControleDados@addPedido');

///API///
elseif (ValidarApi()) :
    Rota::get(['set' => '/clientes', 'as' => 'clientes.index'], 'ControleDados@Clientes');
    Rota::get(['set' => '/produtos/all', 'as' => 'produtos.all'], 'ControleDados@getProdutosAndCategorias');
    Rota::get(['set' => '/produtos/s/{palavra}', 'as' => 'produtos.busca'], 'ControleDados@BuscaProdutos');
    Rota::get(['set' => '/pedidos', 'as' => 'pedidos.get'], 'ControleDados@getPedidos');
    Rota::get(['set' => '/pedido/{id}', 'as' => 'pedido.get'], 'ControleDados@getPedido');

    //ControleDados
    Rota::get(['set' => '/categorias', 'as' => 'produtos.categorias'], 'ControleDados@getCategoriasJson');
    Rota::post(['set' => '/sincronize', 'as' => 'config.sincronize'], 'ControleDados@Sincronize');
    Rota::get(['set' => '/configura', 'as' => 'config.get'], 'ControleDados@getConfig');
    Rota::patch(['set' => '/conteudos', 'as' => 'config.conteudo'], 'ControleDados@setConteudos');
    Rota::post(['set' => '/style', 'as' => 'config.css'], 'ControleDados@setCSS');
    Rota::post(['set' => '/logo', 'as' => 'config.css'], 'ControleDados@setLogo');
    Rota::patch(['set' => '/configura', 'as' => 'config.set'], 'ControleDados@setConfig');

    /// DELETE ///
    Rota::delete(['set' => '/produto/{id}', 'as' => 'produtos.delete'], 'ControleDados@deleteProduto');
    Rota::delete(['set' => '/categoria/{id}', 'as' => 'categoria.delete'], 'ControleDados@deleteCategoria');
    Rota::delete(['set' => '/pedido-del/{id}', 'as' => 'pedido.delete'], 'ControleDados@deletePedido');

    /// POST ///
    Rota::post(['set' => '/produto/add', 'as' => 'produtos.add'], 'ControleDados@addProduto');
    Rota::post(['set' => '/categoria/add', 'as' => 'categoria.add'], 'ControleDados@addCategoria');
    Rota::post(['set' => '/cachear', 'as' => 'pagina.cachear'], 'ControleDados@CachearPagina');

    /// PUT ///
    Rota::put(['set' => '/produto/status', 'as' => 'produtos.status'], 'ControleDados@statusProduto');
    Rota::put(['set' => '/produto/{id}', 'as' => 'produtos.editar'], 'ControleDados@editarProduto');
    Rota::put(['set' => '/categoria/{id}', 'as' => 'categoria.editar'], 'ControleDados@editCategoria');

endif;
