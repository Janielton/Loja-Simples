<?php include 'header.php'; ?>
<style>

</style>
<div id="app">
    <div class="abas-menu">
        <nav>
            <ul class="nav-u">
                <?php
                $catAtual = $categoria->id_categoria;
                foreach ($config['categorias'] as $cat) {
                    if ($catAtual != $cat['id_categoria']) {
                        echo '<li><a href="' . $site . '/categoria/' . $cat['slug_categoria'] . '">' . $cat['nome_categoria'] . '</a></li>';
                    } else {
                        echo '<li><a class="active" href="' . $site . '/categoria/' . $cat['slug_categoria'] . '">' . $cat['nome_categoria'] . '</a></li>';
                    }
                }
                ?>
            </ul>
        </nav>
        <button id="arrou_menu"><i class="material-icons-outlined">arrow_forward</i></button>
    </div>
    <div class="title-detalhe" id="title-primario"><a href="<?php echo $site ?>" id="voltar" onclick="VoltarHome(event)"><i class="material-icons-outlined">arrow_back</i></a>
        <h1 class="titulo">Produtos em <?php echo $pagina ?></h1>
    </div>
    <section id="container_home">
    </section>
    <section id="container_detalhe">
        <input id="quantidade_item" type="hidden" value="1">
    </section>
</div>
<div id="pos-app"></div>
<script>
    var categoria = <?php echo json_encode($categoria); ?>;
    var valorDetalhe = 0.0;
</script>

<?php include 'footer.php'; ?>