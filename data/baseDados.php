<?php

namespace Data;

class BaseDados
{

  //enviar pedido
  public function AdicinarPedido($nometabela, $cliente, $numero, $data, $hora, $observacao, $pagamento, $telefone, $endereco, $produtos, $valor, $status, $entrega)
  {
    $conexao = $this->conexao();
    $sql = "INSERT INTO $nometabela (cliente, numero, data, hora, observacao, pagamento, telefone, endereco, produtos, valor, status, app, entrega) 
    VALUES ('$cliente', '$numero', '$data', '$hora', '$observacao', '$pagamento', '$telefone', '$endereco', '$produtos', '$valor', '$status', '0', '$entrega')";

    if (!$cliente == "") {
      try {
        $conexao->exec($sql);
        if ($conexao->changes() > 0) {

          echo true;
        } else {
          echo "false";
        }
      } catch (\PDOException $e) {
        echo $e;
      }
    } else {
      echo "false";
    }
  }

  //adicionar
  public function AdicinarCategoria($nometabela, $nome, $sub, $url)
  {
    $conexao = $this->conexao();
    $sql = "INSERT INTO $nometabela (cat_nome, sub_cat, url_img) VALUES ('$nome', '$sub', '$url')";

    if (!$nome == "") {
      try {
        $conexao->exec($sql);
        if ($conexao->changes() > 0) {
          $this->adicionado = "AddSucesso()";
        } else {
          $this->adicionado = "AddErro()";
        }
      } catch (\PDOException $e) {
        $this->adicionado = "AddErro()";
        echo "Erro ao criar categoria: " . $e->getMessage();
      }
    }
  }

  public function AdicinarProduto($nometabela, $nome, $valor, $gtin, $url, $catid)
  {
    $conexao = $this->conexao();
    $sql = "INSERT INTO $nometabela (nome, valor, gtin , url_img, cat_id) VALUES ('$nome', '$valor', '$gtin','$url', '$catid')";

    if (!$nome == "") {
      try {
        $conexao->exec($sql);
        if ($conexao->changes() > 0) {
          $this->adicionado = "AddSucesso()";
        } else {
          $this->adicionado = "AddErro()";
        }
      } catch (\PDOException $e) {
        $this->adicionado = "AddErro()";
        echo "Erro ao criar categoria: " . $e->getMessage();
      }
    }
  }


  public function AdicinarColuna($nometabela, $nomecoluna, $valorpadrao)
  {
    $conexao = $this->conexao();
    $sql = "ALTER TABLE $nometabela ADD COLUMN $nomecoluna TEXT DEFAULT '$valorpadrao' NULL";

    try {
      $conexao->exec($sql);

      echo $nomecoluna . " adiconada em " . $nometabela;
    } catch (\PDOException $e) {
      echo "Erro ao limpar registro: " . $e->getMessage();
    }
    //echo $this->var;
  }


  //Apagar e Limpar
  public function Apagar($nometabela, $id)
  {
    $conexao = $this->conexao();
    $sql = "DELETE FROM $nometabela WHERE id = $id";

    try {
      $conexao->exec($sql);
      echo "Registro no ID " . $id . " apagado";
    } catch (\PDOException $e) {
      echo "Erro ao apagar registro: " . $e->getMessage();
    }
    //echo $this->var;
  }
  public function Limpar($nometabela)
  {
    $conexao = $this->conexao();
    $sql = "DELETE FROM $nometabela";
    $sql2 = "VACUUM";

    try {
      $conexao->exec($sql);
      $conexao->exec($sql2);
      echo $nometabela . " foi limpa";
    } catch (\PDOException $e) {
      echo "Erro ao limpar registro: " . $e->getMessage();
    }
    //echo $this->var;
  }
  public function ApagarTabela($nometabela)
  {
    $conexao = $this->conexao();
    $sql = "DROP TABLE $nometabela";

    try {

      $conexao->exec($sql);
      if ($conexao->changes() > 0) {
        echo $nometabela . " tabela apagada";
      }
    } catch (\PDOException $e) {
      echo "Erro ao limpar registro: " . $e->getMessage();
    }
    //echo $this->var;
  }

  public function ApagarColuna($nometabela, $coluna)
  {
    $conexao = $this->conexao();
    $sql = "ALTER TABLE $nometabela DROP COLUMN $coluna;";

    try {
      $conexao->exec($sql);

      echo $nometabela . " coluna apagada";
    } catch (\PDOException $e) {
      echo "Erro ao apagar coluna: " . $e->getMessage();
    }
    //echo $this->var;
  }

  public function Atualizar($tabela, $id, $dados)
  {

    $conexao = $this->conexao();
    $sql = "UPDATE $tabela SET nome = '$dados[0]', celular = '$dados[1]', endereco = '$dados[2]' WHERE id = $id";

    try {
      $conexao->exec($sql);
      echo "Registro atualizado";
    } catch (\PDOException $e) {
      echo "Erro ao atualizar registro: " . $e->getMessage();
    }
  }
  public function AtualizarEntregue($tabela, $id)
  {

    $conexao = $this->conexao();
    $sql = "UPDATE $tabela SET status = 'Entregue' WHERE id = $id";

    try {
      $conexao->exec($sql);
    } catch (\PDOException $e) {
      echo "Erro ao atualizar registro: " . $e->getMessage();
    }
  }
  public function ApagarPedido($tabela, $id)
  {

    $conexao = $this->conexao();
    $sql = "DELETE FROM $tabela WHERE id = $id";

    try {
      $conexao->exec($sql);
    } catch (\PDOException $e) {
      echo "Erro ao apagar registro: " . $e->getMessage();
    }
  }
  public function Busca($tabela, $nome)
  {
    $conexao = $this->conexao();
    $sql = "SELECT * FROM $tabela WHERE nome LIKE '%$nome%'";
    $result = $conexao->query($sql);
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      echo 'Nome: ' . $row['nome'] . ': Celular ' . $row['celular'] . '<br/>';
    }
  }


  ///Query
  public function ExecuteQuery($query, $param)
  {
    $conexao = $this->conexao();
    $stmt = $conexao->prepare($query);
    if (!empty($param)) {
      foreach ($param as $key => $val) {
        if (gettype($val) == 'string') {
          $stmt->bindValue(":{$key}", $val, SQLITE3_TEXT);
        } else {
          $stmt->bindValue(":{$key}", $val, SQLITE3_INTEGER);
        }
      }
    }
    $stmt->execute();
    return $conexao->changes() > 0;
  }

  public function ExecuteQueryResult($query, $param)
  {
    $conexao = $this->conexao();
    $stmt = $conexao->prepare($query);
    if (!empty($param)) {
      foreach ($param as $key => $val) {
        if (gettype($val) == 'string') {
          $stmt->bindValue(":{$key}", $val, SQLITE3_TEXT);
        } else {
          $stmt->bindValue(":{$key}", $val, SQLITE3_INTEGER);
        }
      }
    }
    $result = $stmt->execute();
    $registros = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      array_push($registros, $row);
    }
    return $registros;
  }

  public function ExecuteQueryUnico($query, $param)
  {
    $conexao = $this->conexao();
    $stmt = $conexao->prepare($query);
    if (!empty($param)) {
      foreach ($param as $key => $val) {
        if (gettype($val) == 'string') {
          $stmt->bindValue(":{$key}", $val, SQLITE3_TEXT);
        } else {
          $stmt->bindValue(":{$key}", $val, SQLITE3_INTEGER);
        }
      }
    }
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    // print_r($row);
    return $row;
  }


  public function conexao()
  {
    $pathDB = "data/BaseDadosNew.sqlite";
    return $this->db = new \SQLite3($pathDB);
  }

  public function startDB($path)
  {
    fopen($path, "w");
    $this->db = new \SQLite3($path);
    $conexao = $this->db;
    $conexao->exec("CREATE TABLE IF NOT EXISTS produtos (id_produto INTEGER PRIMARY KEY AUTOINCREMENT, slug_produto TEXT, nome_produto TEXT, valor_produto REAL, status_produto INTEGER, id_categoria INTEGER,imagem_produto TEXT,descricao_produto	TEXT, UNIQUE(slug_produto))");
    $conexao->exec("CREATE TABLE IF NOT EXISTS categorias (id_categoria INTEGER PRIMARY KEY AUTOINCREMENT, nome_categoria TEXT, slug_categoria TEXT, UNIQUE(slug_categoria))");
    $conexao->exec("CREATE TABLE IF NOT EXISTS pedidos (id INTEGER PRIMARY KEY AUTOINCREMENT, id_pedido INTEGER, json_pedido TEXT, data_pedido TEXT)");
    return $conexao->changes() > 0;
  }

  public function close()
  {
    return $this->db->close();
  }
}
