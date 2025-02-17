<?php include 'header.php'; ?>
<div id="app">
  <div class="abas-menu">
    <nav>
      <ul class="nav-u">
        <?php
        foreach ($config['categorias'] as $cat) {
          echo '<li><a href="' . $site . '/categoria/' . $cat['slug_categoria'] . '">' . $cat['nome_categoria'] . '</a></li>';
        }
        ?>
      </ul>
    </nav>
    <button id="arrou_menu"><i class="material-icons-outlined">arrow_forward</i></button>
  </div>

  <section id="container_home">
  </section>
  <section id="container_detalhe" style="display: none;">
    <input id="quantidade_item" type="hidden" value="1">
  </section>
</div>
<div id="pos-app"></div>
<script>
  var valorDetalhe = 0.0;
  var home = true;
</script>
<?php include 'footer.php'; ?>