<?php include 'header-page.php'; ?>
<div id="app">
    <div class="title-detalhe">
        <h4>Sobre - <?php echo $config['nome']; ?></h4>
    </div>
    <div class="conteudo">
        <?php
        $file = file_get_contents("coteudos.json");
        echo json_decode($file)->sobre;
        ?>
    </div>
</div>
<?php
$page = 2;
include 'footer-page.php';
?>