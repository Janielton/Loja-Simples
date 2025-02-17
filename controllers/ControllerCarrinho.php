<?php

namespace Controllers;

use Routes\Request;
use stdClass;

session_start();

class ControllerCarrinho
{
    public function __construct()
    {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = array();
        }
    }
    public function addCarrinho()
    {
        $request = new Request;
        $dados = new stdClass();
        $dados->id = $request->id;
        $dados->nome = $request->nome;
        $dados->valor = $request->valor;
        $dados->cat = $request->cat;
        $dados->image = $request->image;
        $dados->quantidade = $request->quantidade;

        if ($this->inCarrinho($request->id)) {
            $this->setQuantidade($request->id, $request->quantidade);
            echo json_encode(['status' => 2]);
        } else {
            array_push($_SESSION['carrinho'], $dados);
            echo json_encode(['status' => 1]);
        }

        //  $this->getCarrinho();

    }

    function inCarrinho($id)
    {
        foreach ($_SESSION['carrinho'] as $item) {
            if ($item->id == $id) {
                return true;
                break;
            }
        }
        return false;
    }
    function setQuantidade($id, $q)
    {
        $i = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            if ($item->id == $id) {
                $_SESSION['carrinho'][$i]->quantidade = intval($item->quantidade) + intval($q);
                break;
            }
            $i++;
        }
    }

    function CorrigeArray()
    {
        $array = array();
        foreach ($_SESSION['carrinho'] as $item) {
            array_push($array, $item);
        }
        $_SESSION['carrinho'] = $array;
    }

    public function getCarrinho()
    {
        // print_r($_SESSION['carrinho']);
        // $this->CorrigeArray();
        // echo "================================</br>";
        echo json_encode($_SESSION['carrinho'], true);
    }

    public function removeCarrinhoItem()
    {
        $request = new Request;
        $items = $_SESSION['carrinho'];
        $reposta = ['sucesso' => 'true', 'valor' => 0.0];
        $i = 0;
        try {
            foreach ($items as $item) {
                if ($request->id === $item->id) {
                    $reposta['valor'] = $item->valor * $item->quantidade;
                    unset($_SESSION['carrinho'][$i]);
                    break;
                }
                $i++;
            }
            $reposta['mensagem'] = "Ok";
        } catch (\Exception $ex) {
            $reposta['sucesso'] = false;
            $reposta['mensagem'] = $ex;
        }
        $this->CorrigeArray();
        echo json_encode($reposta);
    }

    public function getCarArray()
    {
        // $this->clearCarrinho();
        return $_SESSION['carrinho'];
    }

    public function getTotal()
    {
        $total = 0.0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item->valor;
        }
        return $total;
    }

    public function clearCarrinho()
    {
        $_SESSION['carrinho'] = array();
        setcookie("carrinho", 0, time() + 3600);
        echo 1;
    }
}
