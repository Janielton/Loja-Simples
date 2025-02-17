<?php include 'header.php';?>
<div class="container-logo">
<div class="box-logo">
<a href="<?php echo $config['site'];?>" class="mad-logo"><img src="<?php echo $config['logo'];?>" alt="Logo"></a>
</div>

</div>
<div id="app">
<div class="title-detalhe"><h4>Politica de Privacidade</h4></div>
<div class="conteudo">
<p>
    Estamos empenhados em salvaguardar a sua privacidade ao utilizar a plataforma. Este termo tem a finalidade de deixar o mais claro possível a nossa política de coleta e compartilhamento de dados, informando sobre os dados coletados e como os utilizamos.
</p>
<p>
    Ao utilizar esta plataforma você declara o seu EXPRESSO CONSENTIMENTO para podermos armazenar informações sobre você quando julgarmos adequado à prestação de nossos serviços.
</p>
<p>
    Podemos registrar dados de sua visita à plataforma através de cookies e outras tecnologias de rastreamento incluindo seu endereço IP e nome de domínio, a versão do seu navegador e do seu sistema operacional, dados de tráfego online, dados de localização, logs da web e outros dados de navegação. Também podemos armazenar seus dados pessoas, como nome e endereço, para uso em pedidos posteriores.
</p>
<p>
  Os seus dados armazendados não serão compartilhados com nenhuma empresa ou terceiros.
</p>
</div>   
</div>
<script type="text/javascript">
const enderecos = <?php echo json_encode($config['enderecos']); ?>;
</script>
<script src="js/page.js?versao=1"></script>
<?php include 'footer.php';?>