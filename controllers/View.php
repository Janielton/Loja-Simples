<?php

namespace Controllers;

class View
{

    public function render($view, $data = [])
    {

        $view = str_replace('.', '/', $view);

        $filename = 'views/' . $view . '.php';

        if (!file_exists($filename)) {
            throw new \Exception("A view não pode ser renderizada. Arquivo <u>{$filename}</u> não encontrado.");
        }
        ob_start();
        /**
         * Gerar variáveis automaticamente
         */
        //  var_dump($data);
        if (count($data) > 0) {
            foreach ($data as $k => $v) {
                ${$k} = $v;
            }
        }
        require_once $filename;
        /// readfile($filename);
        ob_end_flush();
    }
}
