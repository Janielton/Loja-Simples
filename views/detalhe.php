<?php
include 'header.php';
$urlPost = $site . $_SERVER['REQUEST_URI'];
?>
<style>
    .loading {
        display: none
    }
</style>

<div id="app">
    <section id="container_home">
        <div class="abas-menu">
            <nav>
                <ul class="nav-u">
                    <?php
                    $catAtual = $produto->id_categoria;
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
    </section>
    <section id="container_detalhe">
        <div class="title-detalhe" id="title-primario"><a href="<?php echo $site; ?>" id="voltar" onclick="VoltarHome(event)"><i class="material-icons-outlined">arrow_back</i></a>
            <h1 class="titulo"><?php echo $produto->nome_produto ?></h1>
        </div>
        <div class="row">
            <div class="col-detalhe">
                <div class="produto-single"><span class="desc-detalhe" style="display:none"></span><span class="produto-thumb img-detalhe"><img src="<?php echo $produto->imagem_produto ?>" alt="produto-imagem"></span>
                    <div class="produto-body">
                        <div class="produto-desc">
                            <div class="descricao"><?php echo $produto->descricao_produto ?></div>
                            <div class="container-quantidade">
                                <h3>Selecione quantitade</h3>
                                <div class="box-quantity">
                                    <div class="input-group bootstrap-touchspin"><input id="quantidade_item" type="text" value="1" class="form-control" disabled=""><span class="input-group-btn-vertical"><button class="btn-control control-up" type="button" onclick="AlterarQuantidade(true)"><i class="material-icons-outlined">add</i></button><button class="btn-control control-down" type="button" onclick="AlterarQuantidade(false)"><i class="material-icons-outlined">remove</i></button></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="produto-controls">
                            <p class="produto-price" id="preco_detalhe"><?php echo fMoeda($produto->valor_produto) ?></p><button onclick="addPedido(event, 0)" class="btn-add btn-detalhe">Add Pedido<i class="fas fa-shopping-cart"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($config['comentario']) : ?>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v13.0&appId=1108577612510117&autoLogAppEvents=1" nonce="ZbB5swOp"></script>
            <div class="comentarios">
                <div class="fb-comments" data-href="<?php echo $urlPost; ?>" data-width="" data-mobile="true" data-numposts="5"></div>
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
    var valorDetalhe = <?php echo $produto->valor_produto; ?>;
    var detalhe = true;
    var produto = '[<?php $produto->descricao_produto = "no";
                    echo json_encode($produto); ?>]';
</script>

<div class="share">
    <a class="icon-share" id="whats" href="https://api.whatsapp.com/send?text=<?php echo 'Comprar ' . $pagina . ' - ' . $urlPost; ?>" target="_blank" title="Compartilhar no Whatsapp"><i class="material-icons">whatsapp</i></a>
    <a class="icon-share" id="face" href="http://www.facebook.com/sharer.php?u=<?php echo $urlPost; ?>" target="_blank" title="Compartilhar no Facebook"><i class="material-icons">facebook</i></a>
    <a class="icon-share" id="telegram" href="https://t.me/share/url?url=<?php echo $urlPost . '&text=Comprar ' . $pagina; ?>" target="_blank" title="Compartilhar no Telegram"><i class="material-icons">telegram</i></a>
    <a class="icon-share" id="twitter" href="https://twitter.com/share?url=<?php echo $urlPost . '&text=Comprar ' . $pagina; ?>" target="_blank" title="Compartilhar no Twitter"><i class="material-icons"><img src="https://abs.twimg.com/favicons/twitter.2.ico" /></i></a>
</div>
<?php include 'footer.php'; ?>