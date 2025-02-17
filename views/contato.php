<?php include 'header-page.php'; ?>
<style type="text/css">
    .form {
        display: grid;
        margin: 0 auto;
        max-width: 600px;
    }

    .form input {
        margin: 10px;
        padding: 10px;
        font-size: 20px;
        border: solid 1px #b3b3b3;
        border-bottom: 3px solid #b3b3b3;
        background: white;
    }

    .form textarea {
        margin: 10px;
        padding: 10px;
        font-size: 20px;
        border: solid 1px #b3b3b3;
        border-bottom: 3px solid #b3b3b3;
        min-height: 90px;
    }

    .btn {
        margin: 25px auto;
        min-width: 300px;
        background: #63c319;
        border: 0;
        color: white;
        cursor: pointer;
    }
</style>

<div id="app">

    <div class="title-detalhe">
        <h4>Contato com <?php echo $config['nome']; ?></h4>
    </div>
    <div class="conteudo">
        <section class="form">
            <input type="text" name="nome" id="nome" Placeholder="Seu nome" />
            <input type="text" name="assunto" id="assunto" Placeholder="Assunto" />
            <textarea name="mensagem" id="tx-mensagem" Placeholder="Sua mensagem"></textarea>
            <button type="submit" class="btn" onclick="EnviarMensagem()">Enviar</button>

        </section>
    </div>
</div>
<?php
$page = 1;
include 'footer-page.php';
?>