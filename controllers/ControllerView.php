<?php

namespace Controllers;

use Controllers\Constants;
use Controllers\View;

require "Constants.php";
require "View.php";

class ControllerView
{
   public function view($view, $data = [])
   {
      try {
         $viewer = new View();
         $viewer->render($view, $data);
      } catch (\Exception $e) {
         echo $e->getMessage();
      }
   }

   public function index()
   {
      $index = Constants::getPathCache() . "/index.html";
      if ($this->noRealPage() && file_exists($index)) {
         return readfile($index);
      }
      $file = file_get_contents("config.json");
      $carrinho = new ControllerCarrinho();
      $data = array('carrinho' => $carrinho->getCarArray(), 'config' => json_decode($file, true), 'pagina' => 'Início');
      return $this->view('index', $data);
   }

   public function getPost($slug)
   {
      $index = Constants::getPathCache() . "/posts/$slug/index.html";
      if ($this->noRealPage() && file_exists($index)) {
         return readfile($index);
      }
      $ctl = new ControleDados(false);
      $dados = $ctl->ProdutobySlug($slug);
      if (empty($dados)) return $this->notFound();
      $dados = (object) $dados;
      $file = file_get_contents("config.json");
      $carrinho = new ControllerCarrinho();
      $data = array('produto' => $dados, 'config' => json_decode($file, true), 'carrinho' => $carrinho->getCarArray(), 'pagina' => $dados->nome_produto);
      return $this->view('detalhe', $data);
   }

   public function getPostbyCat($slug)
   {
      $index = Constants::getPathCache() . "/categorias/$slug/index.html";
      if ($this->noRealPage() && file_exists($index)) {
         return readfile($index);
      }
      $file = file_get_contents("config.json");
      $config = json_decode($file, true);
      $cat = $this->getCatBySlug($slug, $config['categorias']);
      if ($cat == "invalido") return $this->notFound();
      $carrinho = new ControllerCarrinho();
      // $data = array('produtos' => $dados, 'config' => $config, 'carrinho' => $carrinho->getCarArray(), 'pagina' => $cat->nome_categoria, 'categoria' => $cat);
      $data = array(
         'config' => $config,
         'carrinho' => $carrinho->getCarArray(),
         'pagina' => $cat->nome_categoria,
         'categoria' => $cat
      );
      return $this->view('categoria', $data);
   }


   function getCatBySlug($slug, $cats)
   {
      foreach ($cats as $cat) {
         if ($cat['slug_categoria'] == $slug) return (object) $cat;
      }
      return "invalido";
   }

   function isAberto()
   {
      $translate = [0, 1, 2, 3, 4, 5, 6];
      echo json_encode($translate);
      $data = new \DateTime('NOW');     // Pega a data de hoje

      $hoje = $data->format('w');
      echo in_array($hoje, $translate) ? "Aberto" : "Fechado";
   }

   public function sobre()
   {
      $file = file_get_contents("config.json");
      $data = array('carrinho' => '', 'config' => json_decode($file, true), 'pagina' => 'Sobre');
      return $this->view('sobre', $data);
   }

   public function privacidade()
   {
      $file = file_get_contents("config.json");
      $data = array('carrinho' => '', 'config' => json_decode($file, true), 'pagina' => 'Privacidade');
      return $this->view('privacidade', $data);
   }

   public function contato()
   {
      $file = file_get_contents("config.json");
      $data = array('carrinho' => '', 'config' => json_decode($file, true), 'pagina' => 'Contato');
      return $this->view('contato', $data);
   }

   public function carrinho()
   {
      $file = file_get_contents("config.json");
      $carrinho = new ControllerCarrinho();
      $data = array('carrinho' => $carrinho->getCarArray(), 'config' => json_decode($file, true), 'pagina' => 'Carrinho');
      return $this->view('carrinho', $data);
   }

   public function pagamento()
   {
      $file = file_get_contents("config.json");
      $carrinho = new ControllerCarrinho();
      $data = array('carrinho' => $carrinho->getCarArray(), 'config' => json_decode($file, true), 'pagina' => 'Pagamento', 'tipo' => 2);
      return $this->view('pagamento', $data);
   }
   public function criarPagamento()
   {
      $file = file_get_contents("config.json");
      $carrinho = new ControllerCarrinho();
      $data = array('total' => $carrinho->getTotal(), 'config' => json_decode($file, true), 'pagina' => 'Pagamento', 'tipo' => 1);
      return $this->view('pagamento', $data);
   }

   public function notFound()
   {
      $file = file_get_contents("config.json");
      $data = array('carrinho' => '', 'config' => json_decode($file, true), 'pagina' => 'Não encontrada');
      return $this->view('404', $data);
   }

   private function noRealPage()
   {
      return false;
      return !(isset($_GET['real']) && $_GET['real'] == "true");
   }
}
