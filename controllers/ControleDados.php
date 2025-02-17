<?php

namespace Controllers;

use Data\BaseDados;
use Routes\Request;
use stdClass;
use Controllers\Constants;

require "data/baseDados.php";

class ControleDados
{
    private $dados;
    public function __construct($json = true)
    {
        $this->dados = new BaseDados();
        if ($json) header("Content-Type: application/json");
    }

    public function Start()
    {
        $pathDB = Constants::getPathDB() . "/BaseDados.sqlite";
        try {
            if (!file_exists($pathDB)) {
                $this->dados->startDB($pathDB);
                echo "Start concluido";
            } else {
                echo "Já existe";
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    //// Sincronize ////
    public function Sincronize()
    {
        $request = new Request;
        try {
            $uploads_dir = Constants::getPathDB();
            $file = $request->file('file2')['tmp_name'];
            $name = "BaseDados.sqlite";
            move_uploaded_file($file, "$uploads_dir/$name");
            echo json_encode(['sucesso' => true]);
        } catch (\Exception $e) {
            echo json_encode(['sucesso' => false]);
        }
    }

    //// Lista ////

    public function Produtos()
    {
        $param = new stdClass();
        $param->id = 1;
        $params = get_object_vars($param);
        $query = "SELECT p.id_produto, p.slug_produto, p.nome_produto, p.valor_produto, p.status_produto, p.imagem_produto, p.descricao_produto, c.nome_categoria FROM produtos as p INNER JOIN categorias as c on p.id_categoria = c.id_categoria WHERE p.status_produto = 1 LIMIT 20";
        $result = $this->dados->ExecuteQueryResult($query, $params);
        $this->dados->close();
        echo json_encode($result);
    }

    public function BuscaProdutos($palavra)
    {
        $param = new stdClass();
        $param->s = str_replace('-', ' ', $palavra);
        $params = get_object_vars($param);
        $query = "SELECT p.id_produto, p.slug_produto, p.nome_produto, p.valor_produto, p.status_produto, p.imagem_produto, p.descricao_produto, c.nome_categoria FROM produtos as p INNER JOIN categorias as c on p.id_categoria = c.id_categoria WHERE p.status_produto = 1 AND nome_produto LIKE '%' || :s ||'%'";
        $result = $this->dados->ExecuteQueryResult($query, $params);
        $this->dados->close();
        echo json_encode($result);
    }

    public function PostbyCat($slug)
    {
        $param = new stdClass();
        $param->slug = $slug;
        $params = get_object_vars($param);
        $query = "SELECT p.id_produto, p.slug_produto, p.nome_produto, p.valor_produto, p.status_produto, p.imagem_produto, p.descricao_produto, c.nome_categoria FROM produtos as p INNER JOIN categorias as c on p.id_categoria = c.id_categoria WHERE p.status_produto = 1 AND c.slug_categoria = :slug";
        $result = $this->dados->ExecuteQueryResult($query, $params);
        $this->dados->close();
        return $result;
    }

    public function getProdutosbyCat($id)
    {
        $param = new stdClass();
        $param->id = $id;
        $params = get_object_vars($param);
        $query = "SELECT p.id_produto, p.slug_produto, p.nome_produto, p.valor_produto, p.status_produto, p.imagem_produto, p.descricao_produto, c.nome_categoria FROM produtos as p INNER JOIN categorias as c on p.id_categoria = c.id_categoria WHERE p.status_produto = 1 AND c.id_categoria = :id";
        $result = $this->dados->ExecuteQueryResult($query, $params);
        $this->dados->close();
        echo json_encode($result);
    }
    public function getCategorias()
    {
        $query = "SELECT * FROM categorias";
        $result = $this->dados->ExecuteQueryResult($query, "");
        $this->dados->close();
        return $result;
    }

    public function getCategoriasJson()
    {
        $query = "SELECT * FROM categorias";
        $result = $this->dados->ExecuteQueryResult($query, "");
        $this->dados->close();
        echo json_encode($result);
    }

    public function getProdutosAll()
    {
        $file = file_get_contents("produtos.json");
        die(json_encode($file));
        $query = "SELECT * FROM produtos";
        $result = $this->dados->ExecuteQueryResult($query, "");
        $this->dados->close();
        echo json_encode($result);
    }

    public function getProdutosAndCategorias()
    {
        $query = "SELECT * FROM produtos";
        $query2 = "SELECT * FROM categorias";
        $produtos = $this->dados->ExecuteQueryResult($query, "");
        $categorias = $this->dados->ExecuteQueryResult($query2, "");
        $objeto = new stdClass();
        $objeto->produtos = $produtos;
        $objeto->categorias = $categorias;
        echo json_encode($objeto);
        $this->dados->close();
    }

    //Unico

    public function GetProduto($id)
    {
        $param = new stdClass();
        $param->id = $id;
        $params = get_object_vars($param);
        $query = "SELECT * FROM `produtos` WHERE id_produto = :id";
        $result = $this->dados->ExecuteQueryUnico($query, $params);
        $this->dados->close();
        echo json_encode($result);
    }

    public function ProdutobySlug($slug)
    {
        $param = new stdClass();
        $param->slug = $slug;
        $params = get_object_vars($param);
        $query = "SELECT * FROM `produtos` WHERE slug_produto = :slug";
        $result = $this->dados->ExecuteQueryUnico($query, $params);
        $this->dados->close();
        return $result;
    }

    //// SetDados ////
    //Produtos
    public function addProduto()
    {
        $request = new Request;
        $dados = new stdClass();
        $dados->categoria = $request->categoria;
        $dados->status = $request->status;
        $dados->nome = trim($request->nome);
        $dados->descricao = trim($request->descricao);
        $dados->slug = trim($request->slug);
        $dados->valor = $request->valor;
        $dados->image = $request->image;
        $params = get_object_vars($dados);
        $query = "INSERT INTO produtos (id_categoria, nome_produto, slug_produto, valor_produto, status_produto, imagem_produto, descricao_produto) VALUES (:categoria, :nome,:slug, :valor, :status, :image, :descricao)";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        $this->CachearPost($dados->slug);
        echo json_encode(['sucesso' => $result]);
    }

    public function deleteProduto($id)
    {
        $param = new stdClass();
        $param->id = $id;
        $params = get_object_vars($param);
        $query = "DELETE FROM produtos WHERE id_produto = :id;";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        echo json_encode(['sucesso' => $result]);
    }

    public function editarProduto($id)
    {
        $request = new Request;
        $objeto = new stdClass();
        $objeto->id = $id;
        $objeto->categoria = $request->categoria;
        $objeto->status = $request->status;
        $objeto->nome = trim($request->nome);
        $objeto->descricao = trim($request->descricao);
        $objeto->slug = trim($request->slug);
        $objeto->valor = $request->valor;
        $objeto->image = $request->image;
        $params = get_object_vars($objeto);

        $query = "UPDATE produtos SET nome_produto = :nome, slug_produto = :slug, descricao_produto = :descricao, valor_produto = :valor, id_categoria = :categoria, status_produto = :status, imagem_produto = :image WHERE id_produto = :id;";

        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        $this->CachearPost($objeto->slug);
        echo json_encode(['sucesso' => $result]);
    }

    public function statusProduto()
    {
        $request = new Request;
        $dados = new stdClass();
        $dados->id = $request->id;
        $dados->status = $request->status;
        $params = get_object_vars($dados);

        $query = "UPDATE produtos SET status_produto = :status WHERE id_produto = :id";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        echo json_encode(['sucesso' => $result, 'mensagem' => $dados->id . "-" . $dados->status]);
    }

    //CATEGORIAS
    public function addCategoria()
    {
        $request = new Request;
        $dados = new stdClass();
        $dados->nome = $request->nome;
        $dados->slug = trim($request->slug);
        $params = get_object_vars($dados);

        $query = "INSERT INTO categorias (nome_categoria, slug_categoria) VALUES (:nome, :slug)";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        if (!$this->setCategoriasConfig()) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao adicionar categorias']));
        if (!$this->CachearCategoria($dados->slug)) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cachear categoria']));
        echo json_encode(['sucesso' => $result, 'mensagem' => 'OK']);
    }
    public function editCategoria($id)
    {
        $request = new Request;
        $dados = new stdClass();
        $dados->id = $id;
        $dados->nome = $request->nome;
        $dados->slug = trim($request->slug);
        $params = get_object_vars($dados);

        $query = "UPDATE categorias SET nome_categoria = :nome, slug_categoria = :slug WHERE id_categoria = :id";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        if (!$this->setCategoriasConfig()) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao alterar categorias']));
        if (!$this->CachearCategoria($dados->slug)) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cachear categoria']));
        echo json_encode(['sucesso' => $result, 'mensagem' => 'OK']);
    }

    public function deleteCategoria($id)
    {
        $param = new stdClass();
        $param->id = $id;
        $params = get_object_vars($param);
        $query = "DELETE FROM categorias WHERE id_categoria = :id;";
        $result = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        if (!$this->setCategoriasConfig()) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao alterar categorias']));
        if (!$this->CachearHome()) die(json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cachear categoria']));
        echo json_encode(['sucesso' => $result]);
    }

    //Pedido
    public function addPedido($id)
    {
        $request = new Request;
        // echo $request->dados;
        $json = $request->dados;
        // $json = str_replace('\\', '', $json);
        $params = new stdClass();
        $params->id = $id;
        $params->json = $json;
        $params->data = date('Y-m-d H:i:s');
        $query = "INSERT INTO pedidos (id_pedido, json_pedido, data_pedido) VALUES (:id, :json, :data)";
        $acao = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        if ($acao) {
            echo '{"sucesso":true,"mensagem":"OK"}';
        } else {
            echo '{"sucesso":false,"mensagem":"Erro ao adicionar pedido"}';
        }
    }

    public function getPedidos()
    {
        $query = "SELECT * FROM pedidos";
        $result = $this->dados->ExecuteQueryResult($query, "");
        $this->dados->close();
        echo json_encode($result);
    }

    public function getPedido($id)
    {
        $params = new stdClass();
        $params->id = $id;
        $query = "SELECT * FROM pedidos WHERE id_pedido = :id;";
        $result = $this->dados->ExecuteQueryResult($query, $params);
        $this->dados->close();
        echo json_encode($result);
    }

    public function deletePedido($id)
    {
        $params = new stdClass();
        $params->id = $id;
        $query = "DELETE FROM pedidos WHERE id = :id;";
        $acao = $this->dados->ExecuteQuery($query, $params);
        $this->dados->close();
        if ($acao) {
            echo '{"sucesso":true,"mensagem":"OK"}';
        } else {
            echo '{"sucesso":false,"mensagem":"Erro ao deletar pedido"}';
        }
    }


    //// CONFIG ////
    public function setConfig()
    {
        $request = new Request;
        try {
            file_put_contents("config.json", $request->getDados());
            echo json_encode(['sucesso' => true, 'mensagem' => 'OK']);
        } catch (\Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    public function getConfig()
    {
        echo file_get_contents("config.json");
    }

    function setCategoriasConfig()
    {
        $file = file_get_contents("config.json");
        $array = json_decode($file);
        $array->categorias = $this->getCategorias();
        file_put_contents("config.json", json_encode($array));
        return true;
    }

    public function setConteudos()
    {
        $request = new Request;
        try {
            $objeto = json_decode($request->getDados());
            file_put_contents("coteudos.json", $request->getDados());
            $status = !empty($objeto->sobre);
            $this->setSobre($status);
            echo json_encode(['sucesso' => true, 'mensagem' => 'OK']);
        } catch (\Exception $e) {
            echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
        }
    }

    private function setSobre($status)
    {
        $objeto = json_decode(file_get_contents("config.json"));
        $objeto->sobre = $status;
        file_put_contents("config.json", json_encode($objeto));
    }

    public function setLogo()
    {
        $request = new Request;
        if ($request->hasFile('arquivo')) {
            $image = $request->file('arquivo')['tmp_name'];
            $acao = move_uploaded_file($image, Constants::getPathAssets() . "/logo.png");
            if ($acao > 0) {
                echo '{"sucesso":true,"mensagem":"OK"}';
            } else {
                echo '{"sucesso":false,"mensagem":"Erro ao criar cache de página"}';
            }
        }
    }

    public function setCSS()
    {
        $request = new Request;
        if ($request->hasFile('arquivo')) {
            $css = file_get_contents($request->file('arquivo')['tmp_name']);
            $acao = file_put_contents(Constants::getPathCSS() . "/style.css", $css);
            if ($acao > 0) {
                echo '{"sucesso":true,"mensagem":"OK"}';
            } else {
                echo '{"sucesso":false,"mensagem":"Erro ao criar cache de página"}';
            }
        }
    }

    public function setConstants()
    {
        $request = new Request;
        if ($request->acesso == "TWNHTqrpku") {
            // $file = file_get_contents($request->file('arquivo')['tmp_name']);
            $file = '<?php 
            namespace Controllers; 
            use stdClass;
            class Constants { 
                //Painel
                const path = "{path}"; 
                static function getPath() { return self::path; } 
                static function getPathDB() { return self::path . "data"; } 
                static function getPathCache(){ return self::path . "data/cache"; }
                static function getPathCSS() { return self::path . "css"; } 
                static function getPathJS() { return self::path . "js"; } 
                static function getPathAssets(){ return self::path . "assets"; }
                static function getSite() { return "{site}"; } 
                static function getAcesso(){
                    $obj = new stdClass();
                    $obj->usuario = "Elton";
                    $obj->senha = "0502taty";
                    return $obj;
                }
            }';
            $path = str_replace("controllers", "", __DIR__);
            $search = array("{path}", "{site}", "            ", "Elton", "0502taty");

            $replace = array($path, $request->site, "", "$request->usuario", "$request->senha");
            $acao = file_put_contents($path . "controllers/Constants.php", str_replace($search, $replace, $file));
            if ($acao) {
                echo '{"sucesso":true,"mensagem":"OK"}';
            } else {
                echo '{"sucesso":false,"mensagem":"Erro ao criar cache de página"}';
            }
        }
    }
    ///CACHEAR

    public function CachearPagina()
    {
        $request = new Request;
        $acao = false;
        if ($request->tipo == 1) $acao = $this->CachearHome();
        elseif ($request->tipo == 2) $acao = $this->CachearPost($request->slug);
        elseif ($request->tipo == 3) $acao = $this->CachearCategoria($request->slug);
        if ($acao) {
            echo '{"sucesso":true,"mensagem":"OK"}';
        } else {
            echo '{"sucesso":false,"mensagem":"Erro ao criar cache de página"}';
        }
        exit();
    }
    function CachearHome()
    {
        try {
            $url = Constants::getSite();
            $folder = Constants::getPathCache();
            $this->AlterarArquivo($folder . "/index.html", $url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function CachearPost($slug)
    {
        try {
            $url = Constants::getSite() . "/produto/$slug";
            $folder = Constants::getPathCache() . "/posts/" . $slug;
            if (!file_exists($folder)) {
                if (!mkdir($folder, 0755)) die('{"sucesso":false,"mensagem":"Erro ao criar diretório"}');
            }
            $this->AlterarArquivo($folder . "/index.html", $url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function CachearCategoria($slug)
    {
        try {
            $url = Constants::getSite() . "/categoria/$slug";
            $folder = Constants::getPathCache() . "/categorias/$slug";
            if (!file_exists($folder)) {
                if (!mkdir($folder, 0755)) die('{"sucesso":false,"mensagem":"Erro ao criar diretório"}');
            }
            $this->AlterarArquivo($folder . "/index.html", $url);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function minify_html($html)
    {
        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/', # Delete multispace (Without \n)
            '/\>\s+\</', # strip whitespaces between tags
            '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'
        ); # strip whitespaces between = "'

        $replace = array(
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1"
        );

        $html = preg_replace($search, $replace, $html);
        return $html;
    }
    function AlterarArquivo($caminho, $url)
    {
        $data = file_get_contents("{$url}?real=true");
        $html = $this->minify_html($data);
        file_put_contents($caminho, $html . '<!-- Painel Cache -->');
    }
}
